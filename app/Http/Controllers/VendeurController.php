<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Commande;
use App\Models\LivraisonCl;
use App\Models\Facture;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Categorie;
use Carbon\Carbon;

class VendeurController extends Controller
{
    // ─────────────────────────────────────────────
    //  Middleware : seuls les vendeurs accèdent ici
    // ─────────────────────────────────────────────
    public function __construct()
{
    $this->middleware('auth');
    $this->middleware(function ($request, $next) {
        if (auth()->user()->role !== 'vendeur') {
            abort(403, 'Accès refusé.');
        }
        return $next($request);
    });
}
    // ─────────────────────────────────────────────
    //  DASHBOARD PRINCIPAL
    // ─────────────────────────────────────────────
    public function dashboard()
    {
        // ── Statistiques ──────────────────────────
        $stats = [

            // Commandes en attente de traitement
            'commandes_attente' => Commande::whereIn('statut_cmd', ['en_attente', 'confirmee'])
                ->count(),

            // Livraisons actuellement en cours
            'livraisons_en_cours' => LivraisonCl::where('statut', 'en_cours')
                ->count(),

            // Total clients enregistrés
            'total_clients' => Client::count(),

            // Factures dont la commande n'est pas encore payée
            'factures_impayees' => Facture::whereHas('commande', fn($q) =>
                $q->where('statut_paiement', '!=', 'paye')
            )->count(),

            // Ventes du jour (commandes livrées ou payées aujourd'hui)
            'ventes_du_jour' => Commande::where('statut_paiement', 'paye')
                ->whereDate('updated_at', today())
                ->sum('montant_total'),

            // Commandes livrées ce mois-ci
            'commandes_livrees_mois' => Commande::where('statut_cmd', 'livree')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count(),

            // Chiffre d'affaires du mois (commandes payées)
            'ca_mois' => Commande::where('statut_paiement', 'paye')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->sum('montant_total'),
        ];

        // ── Produits disponibles (aperçu, stock > 0) ──
        $produits_disponibles = Produit::with(['categorie', 'stock', 'calibre'])
            ->whereHas('stock', fn($q) => $q->where('quantite_stock', '>', 0))
            ->latest()
            ->take(8)
            ->get();

        // ── Catégories pour le select de recherche ──
        $categories = Categorie::orderBy('libelle')->get();

        // ── Commandes à traiter (en_attente + confirmee + en_preparation) ──
        $commandes_a_traiter = Commande::with('client')
            ->whereIn('statut_cmd', ['en_attente', 'confirmee', 'en_preparation'])
            ->latest()
            ->take(8)
            ->get();

        // ── Livraisons en cours ──
        $livraisons_en_cours = LivraisonCl::with(['commande.client'])
            ->where('statut', 'en_cours')
            ->latest()
            ->take(6)
            ->get();

        // ── Clients récents avec stats ──
        $clients_recents = Client::withCount('commandes')
            ->withSum('commandes', 'montant_total')
            ->latest()
            ->take(6)
            ->get();

        // ── Factures impayées ──
        $factures_impayees = Facture::with('commande.client')
            ->whereHas('commande', fn($q) =>
                $q->where('statut_paiement', '!=', 'paye')
            )
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.vendeur', compact(
            'stats',
            'produits_disponibles',
            'categories',
            'commandes_a_traiter',
            'livraisons_en_cours',
            'clients_recents',
            'factures_impayees'
        ));
    }

    // ─────────────────────────────────────────────
    //  RECHERCHE / LISTE PRODUITS
    // ─────────────────────────────────────────────
    public function produits(Request $request)
    {
        $query = Produit::with(['categorie', 'stock', 'calibre'])
            ->whereHas('stock', fn($q) => $q->where('quantite_stock', '>', 0));

        if ($request->filled('q')) {
            $query->where('libelle_prod', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('categorie')) {
            $query->where('id_categorie', $request->categorie);
        }

        $produits   = $query->latest()->paginate(16)->withQueryString();
        $categories = Categorie::orderBy('libelle')->get();

        return view('dashboard.vendeur.produits', compact('produits', 'categories'));
    }

    // ─────────────────────────────────────────────
    //  COMMANDES — liste
    // ─────────────────────────────────────────────
    public function commandes(Request $request)
    {
        $query = Commande::with('client')->latest();

        if ($request->filled('statut')) {
            $query->where('statut_cmd', $request->statut);
        }

        if ($request->filled('q')) {
            $query->where('reference', 'like', '%' . $request->q . '%')
                  ->orWhereHas('client', fn($q) =>
                      $q->where('nom_client', 'like', '%' . $request->q . '%')
                        ->orWhere('prenom_client', 'like', '%' . $request->q . '%')
                  );
        }

        $commandes = $query->paginate(15)->withQueryString();

        return view('dashboard.vendeur.commandes.index', compact('commandes'));
    }

    // ─────────────────────────────────────────────
    //  COMMANDES — détail
    // ─────────────────────────────────────────────
    public function commandeShow($id)
{
    $commande = Commande::with([
        'client',
        'produits.categorie',
        'produits.calibre',
        'facture',
        'livraisonCl',
    ])->findOrFail($id);

    $livreurs = \App\Models\Livreur::where('actif', true)->get();

    return view('dashboard.vendeur.commandes.show', compact('commande', 'livreurs'));
}
    // ─────────────────────────────────────────────
    //  COMMANDES — changer statut
    // ─────────────────────────────────────────────
    public function commandeConfirmer($id)
{
    $commande = Commande::findOrFail($id);
    $commande->update(['statut_cmd' => 'confirmee']);

    // Créer la facture automatiquement
    if (!$commande->facture) {
        Facture::create([
            'id_cmd'       => $commande->id_cmd,
            'date_fact'    => now()->toDateString(),
            'montant_fact' => $commande->montant_total,
            'mode_paie'    => $commande->momo_operateur ?? 'especes',
        ]);
    }

    return back()->with('success', "Commande {$commande->reference} confirmée et facture générée.");
}

    public function commandePreparer($id)
    {
        $commande = Commande::findOrFail($id);
        $commande->update(['statut_cmd' => 'en_preparation']);

        return back()->with('success', "Commande {$commande->reference} en préparation.");
    }
public function commandeEnvoyer($id)
{
    $commande  = Commande::findOrFail($id);
    $livreurs  = \App\Models\Livreur::where('actif', true)->get();

    return view('dashboard.vendeur.commandes.assigner', compact('commande', 'livreurs'));
}
// Nouvelle méthode pour créer la livraison avec livreur
public function commandeAssignerLivreur(Request $request, $id)
{
    $request->validate([
        'id_livreur'   => 'required|exists:livreurs_cl,id_livreur',
        'date_livcl'   => 'required|date',
        'adresse_livcl'=> 'required|string',
    ]);

    $commande = Commande::findOrFail($id);

    // Créer la livraison
    LivraisonCl::create([
        'id_cmd'       => $commande->id_cmd,
        'id_livreur'   => $request->id_livreur,
        'date_livcl'   => $request->date_livcl,
        'adresse_livcl'=> $request->adresse_livcl,
        'statut'       => 'en_attente',
    ]);

    $commande->update(['statut_cmd' => 'en_livraison']);

    return back()->with('success', "Livraison créée et livreur assigné !");
}
    // ─────────────────────────────────────────────
    //  LIVRAISONS — liste
    // ─────────────────────────────────────────────
    public function livraisons(Request $request)
    {
        $query = LivraisonCl::with(['commande.client'])
            ->latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $livraisons = $query->paginate(15)->withQueryString();

        return view('dashboard.vendeur.livraisons.index', compact('livraisons'));
    }

    // ─────────────────────────────────────────────
    //  LIVRAISONS — détail
    // ─────────────────────────────────────────────
    public function livraisonShow($id)
    {
        $livraison = LivraisonCl::with(['commande.client', 'commande.produits', 'livreur'])
            ->findOrFail($id);

        return view('dashboard.vendeur.livraisons.show', compact('livraison'));
    }

    // ─────────────────────────────────────────────
    //  LIVRAISONS — marquer livrée
    // ─────────────────────────────────────────────
    public function livraisonLivrer($id)
    {
        $livraison = LivraisonCl::with('commande')->findOrFail($id);

        DB::transaction(function () use ($livraison) {
            // Mettre à jour la livraison
            $livraison->update(['statut' => 'livree']);

            // Mettre à jour la commande associée
            $livraison->commande->update(['statut_cmd' => 'livree']);
        });

        return back()->with('success', 'Livraison marquée comme livrée.');
    }

    // ─────────────────────────────────────────────
    //  CLIENTS — liste
    // ─────────────────────────────────────────────
    public function clients(Request $request)
    {
        $query = Client::withCount('commandes')
            ->withSum('commandes', 'montant_total')
            ->latest();

        if ($request->filled('q')) {
            $query->where('nom_client', 'like', '%' . $request->q . '%')
                  ->orWhere('prenom_client', 'like', '%' . $request->q . '%')
                  ->orWhere('telephone', 'like', '%' . $request->q . '%');
        }

        $clients = $query->paginate(15)->withQueryString();

        return view('dashboard.vendeur.clients.index', compact('clients'));
    }

    // ─────────────────────────────────────────────
    //  CLIENTS — détail
    // ─────────────────────────────────────────────
    public function clientShow($id)
    {
        $client = Client::with([
            'commandes' => fn($q) => $q->latest()->take(10),
            'commandes.produits',
        ])
        ->withCount('commandes')
        ->withSum('commandes', 'montant_total')
        ->findOrFail($id);

        return view('dashboard.vendeur.clients.show', compact('client'));
    }

    // ─────────────────────────────────────────────
    //  FACTURES — liste
    // ─────────────────────────────────────────────
    public function factures(Request $request)
    {
        $query = Facture::with('commande.client')->latest();

        if ($request->filled('statut_paiement')) {
            $query->whereHas('commande', fn($q) =>
                $q->where('statut_paiement', $request->statut_paiement)
            );
        }

        $factures = $query->paginate(15)->withQueryString();

        return view('dashboard.vendeur.factures.index', compact('factures'));
    }

    // ─────────────────────────────────────────────
    //  FACTURES — détail
    // ─────────────────────────────────────────────
    public function factureShow($id)
    {
        $facture = Facture::with(['commande.client', 'commande.produits'])
            ->findOrFail($id);

        return view('dashboard.vendeur.factures.show', compact('facture'));
    }

    // ─────────────────────────────────────────────
    //  FACTURES — marquer payée
    // ─────────────────────────────────────────────
    public function facturePayer($id)
    {
        $facture = Facture::with('commande')->findOrFail($id);

        $facture->commande->update(['statut_paiement' => 'paye']);

        return back()->with('success', "Facture #{$facture->id_fact} marquée comme payée.");
    }

    // ─────────────────────────────────────────────
    //  FACTURES — télécharger PDF
    // ─────────────────────────────────────────────
    public function factureImprimer($id)
{
    $facture = Facture::with(['commande.client', 'commande.produits'])
        ->findOrFail($id);

    return view('dashboard.vendeur.factures.imprimer', compact('facture'));
}
}
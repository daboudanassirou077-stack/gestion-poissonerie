<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Calibre;
use App\Models\Stock;
use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\BonFrs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GerantController extends Controller
{
    // ===== MIDDLEWARE =====
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!in_array(auth()->user()->role, ['gerant', 'admin'])) {
                abort(403, 'Accès refusé.');
            }
            return $next($request);
        });
    }

    // ===== DASHBOARD =====
    public function dashboard()
    {
        $stats = [
            'total_produits'      => Produit::count(),
            'produits_actifs'     => Produit::where('actif', true)->count(),
            'stock_faible'        => Stock::whereRaw('quantite_stock <= seuil_alerte')->count(),
            'total_commandes'     => Commande::count(),
            'commandes_attente'   => Commande::where('statut_cmd', 'en_attente')->count(),
            'commandes_livraison' => Commande::where('statut_cmd', 'en_livraison')->count(),
            'revenus_total'       => Commande::where('statut_paiement', 'paye')->sum('montant_total'),
            'total_fournisseurs'  => Fournisseur::count(),
        ];

        $produits_stock_faible = Stock::with('produit')
            ->whereRaw('quantite_stock <= seuil_alerte')
            ->take(5)->get();

        $dernieres_commandes = Commande::with('client')
            ->latest()->take(5)->get();

        return view('dashboard.gerant', compact(
            'stats',
            'produits_stock_faible',
            'dernieres_commandes'
        ));
    }

    // ===== LISTE PRODUITS =====
    public function produits()
    {
        $produits    = Produit::with(['categorie', 'calibre', 'stock'])->paginate(10);
        $categories  = Categorie::all();
        $calibres    = Calibre::all();
        return view('dashboard.gerant-produits', compact('produits', 'categories', 'calibres'));
    }

    // ===== AJOUTER PRODUIT =====
    public function storeProduit(Request $request)
    {
        $request->validate([
            'libelle_prod' => 'required|string|max:150',
            'id_categorie' => 'required|exists:categories,id_categorie',
            'id_calibre'   => 'required|exists:calibres,id_calibre',
            'prix'         => 'required|numeric|min:0',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'quantite_stock' => 'required|numeric|min:0',
            'seuil_alerte'   => 'required|numeric|min:0',
        ], [
            'libelle_prod.required' => 'Le nom du produit est obligatoire.',
            'id_categorie.required' => 'La catégorie est obligatoire.',
            'id_calibre.required'   => 'Le calibre est obligatoire.',
            'prix.required'         => 'Le prix est obligatoire.',
        ]);

        // Image
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/produits'), $imageName);
        }

        // Créer le produit
        $produit = Produit::create([
            'libelle_prod' => $request->libelle_prod,
            'id_categorie' => $request->id_categorie,
            'id_calibre'   => $request->id_calibre,
            'prix'         => $request->prix,
            'description'  => $request->description,
            'image'        => $imageName,
            'actif'        => true,
        ]);

        // Créer le stock
        Stock::create([
            'id_prod'        => $produit->id_prod,
            'quantite_stock' => $request->quantite_stock,
            'seuil_alerte'   => $request->seuil_alerte,
        ]);

        return redirect()->route('gerant.produits')
                         ->with('success', '✅ Produit "' . $produit->libelle_prod . '" ajouté avec succès !');
    }

    // ===== MODIFIER PRODUIT =====
    public function editProduit(Produit $produit)
    {
        $categories = Categorie::all();
        $calibres   = Calibre::all();
        return view('dashboard.gerant-edit-produit', compact('produit', 'categories', 'calibres'));
    }

    public function updateProduit(Request $request, Produit $produit)
    {
        $request->validate([
            'libelle_prod' => 'required|string|max:150',
            'id_categorie' => 'required|exists:categories,id_categorie',
            'id_calibre'   => 'required|exists:calibres,id_calibre',
            'prix'         => 'required|numeric|min:0',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne
            if ($produit->image && file_exists(public_path('images/produits/' . $produit->image))) {
                unlink(public_path('images/produits/' . $produit->image));
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/produits'), $imageName);
            $produit->image = $imageName;
        }

        $produit->update([
            'libelle_prod' => $request->libelle_prod,
            'id_categorie' => $request->id_categorie,
            'id_calibre'   => $request->id_calibre,
            'prix'         => $request->prix,
            'description'  => $request->description,
            'actif'        => $request->has('actif'),
        ]);

        return redirect()->route('gerant.produits')
                         ->with('success', '✅ Produit modifié avec succès !');
    }

    // ===== SUPPRIMER PRODUIT =====
    public function deleteProduit(Produit $produit)
    {
        $nom = $produit->libelle_prod;

        if ($produit->image && file_exists(public_path('images/produits/' . $produit->image))) {
            unlink(public_path('images/produits/' . $produit->image));
        }

        $produit->delete();

        return back()->with('success', '🗑️ Produit "' . $nom . '" supprimé.');
    }

    // ===== GESTION STOCK =====
    public function stocks()
    {
        $stocks = Stock::with('produit.categorie')
                       ->orderByRaw('quantite_stock <= seuil_alerte DESC')
                       ->paginate(10);
        return view('dashboard.gerant-stocks', compact('stocks'));
    }

    public function updateStock(Request $request, Stock $stock)
    {
        $request->validate([
            'quantite_stock' => 'required|numeric|min:0',
            'seuil_alerte'   => 'required|numeric|min:0',
        ]);

        $stock->update([
            'quantite_stock' => $request->quantite_stock,
            'seuil_alerte'   => $request->seuil_alerte,
        ]);

        return back()->with('success', '✅ Stock mis à jour !');
    }

    // ===== SUIVI COMMANDES =====
    public function commandes()
    {
        $commandes = Commande::with('client.user')
                             ->latest()
                             ->paginate(10);
        return view('dashboard.gerant-commandes', compact('commandes'));
    }

    public function updateCommande(Request $request, Commande $commande)
    {
        $request->validate([
            'statut_cmd'      => 'required|in:en_attente,confirmee,en_preparation,en_livraison,livree,annulee',
            'statut_paiement' => 'required|in:en_attente,paye,echoue',
        ]);

        $commande->update([
            'statut_cmd'      => $request->statut_cmd,
            'statut_paiement' => $request->statut_paiement,
        ]);

        return back()->with('success', '✅ Commande mise à jour !');
    }

    // ===== APPROVISIONNEMENT =====
    public function approvisionnement()
    {
        $fournisseurs = Fournisseur::all();
        $bons         = BonFrs::with('fournisseur')->latest()->paginate(10);
        $produits     = Produit::where('actif', true)->get();
        return view('dashboard.gerant-approvisionnement', compact('fournisseurs', 'bons', 'produits'));
    }

    public function storeFournisseur(Request $request)
    {
        $request->validate([
            'nom_frs'    => 'required|string|max:100',
            'telephone'  => 'required|string|max:20',
            'email'      => 'nullable|email|unique:fournisseurs,email',
            'adresse'    => 'nullable|string',
        ]);

        Fournisseur::create($request->only('nom_frs', 'prenom_frs', 'telephone', 'email', 'adresse'));

        return back()->with('success', '✅ Fournisseur ajouté !');
    }

    // ===== ÉTAT STOCK (rapport) =====
    public function etatStock()
    {
        $stocks = Stock::with('produit.categorie')->get();
        $total_valeur = $stocks->sum(fn($s) => $s->quantite_stock * ($s->produit->prix ?? 0));
        return view('dashboard.gerant-etat-stock', compact('stocks', 'total_valeur'));
    }

    // ===== ÉTAT VENTES (rapport) =====
    public function etatVentes()
    {
        $commandes = Commande::with('produits')
                             ->where('statut_paiement', 'paye')
                             ->latest()
                             ->get();

        $total_ventes   = $commandes->sum('montant_total');
        $nb_commandes   = $commandes->count();

        return view('dashboard.gerant-etat-ventes', compact('commandes', 'total_ventes', 'nb_commandes'));
    }
}
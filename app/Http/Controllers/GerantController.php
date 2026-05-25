<?php
// ══════════════════════════════════════════════
//  CORRECTION 1 : GerantController.php
//  Ajouter les routes catégories pour le gérant
//  + passer $categories au dashboard
// ══════════════════════════════════════════════

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Calibre;
use App\Models\Stock;
use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\BonFrs;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GerantController extends Controller
{
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

    // ─────────────────────────────────────────
    //  DASHBOARD — CORRECTION : + $categories
    // ─────────────────────────────────────────
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

        // ── AJOUT : catégories pour la section de gestion ──
        $categories = Categorie::withCount('produits')->get();

        return view('dashboard.gerant', compact(
            'stats',
            'produits_stock_faible',
            'dernieres_commandes',
            'categories'           // ← NOUVEAU
        ));
    }

    // ─────────────────────────────────────────
    //  PRODUITS (inchangé)
    // ─────────────────────────────────────────
    public function produits()
    {
        $produits   = Produit::with(['categorie', 'calibre', 'stock'])->paginate(10);
        $categories = Categorie::all();
        $calibres   = Calibre::all();
        return view('dashboard.gerant-produits', compact('produits', 'categories', 'calibres'));
    }

    public function storeProduit(Request $request)
    {
        $request->validate([
            'libelle_prod'   => 'required|string|max:150',
            'id_categorie'   => 'required|exists:categories,id_categorie',
            'id_calibre' => $request->id_categorie == 1
             ? 'required|exists:calibres,id_calibre'
              : 'nullable',
            'prix'           => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048',
            'quantite_stock' => 'required|numeric|min:0',
            'seuil_alerte'   => 'required|numeric|min:0',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/produits'), $imageName);
        }

        $produit = Produit::create([
            'libelle_prod' => $request->libelle_prod,
            'id_categorie' => $request->id_categorie,
            'id_calibre'   => $request->id_calibre,
            'prix'         => $request->prix,
            'description'  => $request->description,
            'image'        => $imageName,
            'actif'        => true,
        ]);

        Stock::create([
            'id_prod'        => $produit->id_prod,
            'quantite_stock' => $request->quantite_stock,
            'seuil_alerte'   => $request->seuil_alerte,
        ]);

        return redirect()->route('gerant.produits')
            ->with('success', '✅ Produit "' . $produit->libelle_prod . '" ajouté avec succès !');
    }

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
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048',
        ]);

        if ($request->hasFile('image')) {
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

   public function deleteProduit(Produit $produit)
{
    $nom = $produit->libelle_prod;

    // Supprimer les lignes de commande associées
    \DB::table('commander')->where('id_prod', $produit->id_prod)->delete();

    // Supprimer le stock associé
    if ($produit->stock) {
        $produit->stock->delete();
    }

    // Supprimer l'image
    if ($produit->image && file_exists(public_path('images/produits/' . $produit->image))) {
        unlink(public_path('images/produits/' . $produit->image));
    }

    // Supprimer le produit
    $produit->delete();

    return back()->with('success', '🗑️ Produit "' . $nom . '" supprimé avec succès.');
}

    // ─────────────────────────────────────────
    //  CATÉGORIES — NOUVEAU pour le gérant
    // ─────────────────────────────────────────
// ══════════════════════════════════════════════
//  SEULES LES MÉTHODES MODIFIÉES du GerantController
//  categories(), storeCategorie(), deleteCategorie()
// ══════════════════════════════════════════════

// ─────────────────────────────────────────
//  PAGE CATÉGORIES
// ─────────────────────────────────────────
public function categories()
{
    $categories = Categorie::withCount('produits')->get();
    return view('dashboard.gerant-categories', compact('categories'));
}

// ─────────────────────────────────────────
//  AJOUTER UNE CATÉGORIE (avec photo)
// ─────────────────────────────────────────
public function storeCategorie(Request $request)
{
    $request->validate([
        'libelle'     => 'required|string|max:100|unique:categories,libelle',
        'description' => 'nullable|string|max:255',
        'image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:5048',
    ], [
        'libelle.required'  => 'Le nom de la catégorie est obligatoire.',
        'libelle.unique'    => 'Cette catégorie existe déjà.',
        'image.required'    => 'La photo est obligatoire.',
        'image.image'       => 'Le fichier doit être une image.',
        'image.mimes'       => 'Format accepté : JPG, PNG, WEBP.',
        'image.max'         => 'La photo ne doit pas dépasser 5 MB.',
    ]);

    // ── Sauvegarder la photo dans public/images/categories/ ──
    $imageName = null;
    if ($request->hasFile('image')) {
        // Créer le dossier s'il n'existe pas
        $dossier = public_path('images/categories');
        if (!is_dir($dossier)) {
            mkdir($dossier, 0755, true);
        }
        $imageName = time() . '_' . Str::slug($request->libelle) . '.' . $request->image->extension();
        $request->image->move($dossier, $imageName);
    }

    Categorie::create([
        'libelle'     => $request->libelle,
        'slug'        => Str::slug($request->libelle),
        'description' => $request->description,
        'image'       => $imageName,
    ]);

    return back()->with('success', '✅ Catégorie "' . $request->libelle . '" ajoutée avec sa photo ! Elle apparaît maintenant sur la page d\'accueil.');
}

// ─────────────────────────────────────────
//  SUPPRIMER UNE CATÉGORIE
// ─────────────────────────────────────────
public function deleteCategorie(Categorie $categorie)
{
    if ($categorie->produits()->count() > 0) {
        return back()->with('error', '❌ Impossible de supprimer : ' . $categorie->produits()->count() . ' produit(s) lié(s).');
    }

    // Supprimer la photo du serveur
    if ($categorie->image && file_exists(public_path('images/categories/' . $categorie->image))) {
        unlink(public_path('images/categories/' . $categorie->image));
    }

    $nom = $categorie->libelle;
    $categorie->delete();

    return back()->with('success', '🗑️ Catégorie "' . $nom . '" et sa photo supprimées.');
}

    // ─────────────────────────────────────────
    //  STOCKS (inchangé)
    // ─────────────────────────────────────────
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

    // ─────────────────────────────────────────
    //  COMMANDES (inchangé)
    // ─────────────────────────────────────────
    public function commandes()
    {
        $commandes = Commande::with('client.user')->latest()->paginate(10);
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

    // ─────────────────────────────────────────
    //  APPROVISIONNEMENT (inchangé)
    // ─────────────────────────────────────────
    public function approvisionnement()
{
    $fournisseurs = Fournisseur::all();
    $bons         = BonFrs::with(['fournisseur', 'produits'])->latest()->paginate(10);
    $produits     = Produit::where('actif', true)->orderBy('libelle_prod')->get();
    
    return view('dashboard.gerant-approvisionnement', compact('fournisseurs', 'bons', 'produits'));
}

    public function storeFournisseur(Request $request)
    {
        $request->validate([
            'nom_frs'   => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'email'     => 'nullable|email|unique:fournisseurs,email',
            'adresse'   => 'nullable|string',
        ]);
        Fournisseur::create($request->only('nom_frs', 'prenom_frs', 'telephone', 'email', 'adresse'));
        return back()->with('success', '✅ Fournisseur ajouté !');
    }

    // ─────────────────────────────────────────
    //  RAPPORTS (inchangé)
    // ─────────────────────────────────────────
    public function etatStock()
    {
        $stocks       = Stock::with('produit.categorie')->get();
        $total_valeur = $stocks->sum(fn($s) => $s->quantite_stock * ($s->produit->prix ?? 0));
        return view('dashboard.gerant-etat-stock', compact('stocks', 'total_valeur'));
    }

    public function etatVentes()
    {
        $commandes    = Commande::with('produits')->where('statut_paiement', 'paye')->latest()->get();
        $total_ventes = $commandes->sum('montant_total');
        $nb_commandes = $commandes->count();
        return view('dashboard.gerant-etat-ventes', compact('commandes', 'total_ventes', 'nb_commandes'));
    }


    public function storeBon(Request $request)
{
    $request->validate([
        'id_frs'        => 'required|exists:fournisseurs,id_frs',
        'date_bon'      => 'required|date',
        'montant_total' => 'required|numeric|min:0',
        'statut'        => 'required|in:brouillon,envoye,recu,annule',
        'produits'      => 'required|array|min:1',
        'produits.*.id_prod'  => 'required|exists:produits,id_prod',
        'produits.*.quantite' => 'required|numeric|min:0.01',
        'produits.*.prix'     => 'required|numeric|min:0',
    ]);

    $bon = \App\Models\BonFrs::create([
        'id_frs'        => $request->id_frs,
        'date_bon'      => $request->date_bon,
        'montant_total' => $request->montant_total,
        'statut'        => $request->statut,
    ]);

    foreach ($request->produits as $ligne) {
        \DB::table('commander_frs')->insert([
            'id_bon'       => $bon->id_bon,
            'id_prod'      => $ligne['id_prod'],
            'quantite_cmd' => $ligne['quantite'],
            'prix_comd'    => $ligne['prix'],
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }

    return back()->with('success', '✅ Bon de commande créé avec succès !');
}
}
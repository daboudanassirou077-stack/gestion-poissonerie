<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GerantController;
use App\Http\Controllers\VendeurController;

// ─────────────────────────────────────
// Pages publiques (tout le monde)
// ─────────────────────────────────────

// Accueil
Route::get('/', function () {
    $produits = \App\Models\Produit::with(['categorie', 'calibre', 'stock'])
                ->where('actif', true)
                ->latest()
                ->take(3)
                ->get();

    $categories = \App\Models\Categorie::withCount('produits')
                ->orderBy('libelle')
                ->get();

    return view('home', compact('produits', 'categories'));
})->name('home');

// Boutique
Route::get('/shop', function () {
    $categories = \App\Models\Categorie::withCount('produits')
                ->orderBy('libelle')
                ->get();

    $produits = \App\Models\Produit::with(['categorie', 'calibre', 'stock'])
        ->where('actif', true)
        ->when(request('categorie'), fn($q) =>
            $q->whereHas('categorie', fn($q) => $q->where('slug', request('categorie')))
        )
        ->when(request('q'), fn($q) =>
            $q->where('libelle_prod', 'like', '%' . request('q') . '%')
        )
        ->when(request('prix_max'), fn($q) =>
            $q->where('prix', '<=', request('prix_max'))
        )
        ->when(request('en_stock'), fn($q) =>
            $q->whereHas('stock', fn($q) => $q->where('quantite_stock', '>', 0))
        )
        ->when(request('tri') === 'prix_asc',   fn($q) => $q->orderBy('prix', 'asc'))
        ->when(request('tri') === 'prix_desc',  fn($q) => $q->orderBy('prix', 'desc'))
        ->when(request('tri') === 'nouveautes', fn($q) => $q->latest())
        ->paginate(12)
        ->withQueryString();

    return view('shop', compact('produits', 'categories'));
})->name('shop');

use App\Models\Produit;

Route::get('/shop/{id_prod}', function ($id_prod) {
    $produit = Produit::with(['categorie', 'calibre', 'stock'])
        ->findOrFail($id_prod);
    return view('shop-show', compact('produit'));
})->name('shop.show');

Route::get('/about',   fn() => view('about'))->name('about');
Route::get('/contact', fn() => view('contact'))->name('contact');
Route::post('/contact/send', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'prenom'  => 'required|string|max:80',
        'nom'     => 'required|string|max:80',
        'email'   => 'required|email',
        'sujet'   => 'required|string|max:150',
        'message' => 'required|string|min:10',
        'website' => 'max:0',
    ]);
    return back()->with('success', 'Votre message a bien été envoyé !');
})->name('contact.send');

// Panier public
Route::get( '/cart',             [CartController::class, 'index'])  ->name('cart');
Route::get( '/cart/index',       [CartController::class, 'index'])  ->name('cart.index');
Route::post('/cart/add',         [CartController::class, 'add'])    ->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update')->where('id', '.*');
Route::get( '/cart/remove/{id}', [CartController::class, 'remove']) ->name('cart.remove');
Route::get( '/cart/clear',       [CartController::class, 'clear'])  ->name('cart.clear');
Route::get( '/cart/count',       [CartController::class, 'count'])  ->name('cart.count');

// Newsletter
Route::post('/newsletter', fn() => back()->with('success', 'Merci !'))->name('newsletter');
Route::get('/wishlist/add/{id}', fn() => back())->name('wishlist.add');

// ─────────────────────────────────────
// Authentification (visiteurs seulement)
// ─────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get( '/login',    [AuthController::class, 'showLogin'])   ->name('login');
    Route::post('/login',    [AuthController::class, 'login'])       ->name('login.post');
    Route::get( '/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])    ->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
     ->name('logout')
     ->middleware('auth');

// ─────────────────────────────────────
// Pages protégées (connexion obligatoire)
// ─────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Checkout et commande
    Route::get( '/checkout',           [CartController::class, 'checkout'])     ->name('cart.checkout');
    Route::post('/checkout/order',     [CartController::class, 'order'])        ->name('cart.order');
    Route::get( '/checkout/payment',   [CartController::class, 'payment'])      ->name('cart.payment');
    Route::get( '/order/confirmation', [CartController::class, 'confirmation']) ->name('order.confirmation');

    // Dashboard client
    Route::get('/dashboard/client', fn() => view('dashboard.client'))->name('client.dashboard');

    // Profil client
    Route::put('/client/profile/update',  [AuthController::class, 'updateProfile']) ->name('client.profile.update');
    Route::put('/client/password/update', [AuthController::class, 'updatePassword'])->name('client.password.update');
});

// ─────────────────────────────────────
// Routes Admin
// ─────────────────────────────────────
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard',                     [AdminController::class, 'dashboard'])      ->name('admin.dashboard');
    Route::get('/users',                         [AdminController::class, 'users'])          ->name('admin.users');
    Route::get('/users/create',                  [AdminController::class, 'createUser'])     ->name('admin.create-user');
    Route::post('/users/store',                  [AdminController::class, 'storeUser'])      ->name('admin.store-user');
    Route::post('/users/{user}/toggle',          [AdminController::class, 'toggleUser'])     ->name('admin.toggle-user');
    Route::delete('/users/{user}',               [AdminController::class, 'deleteUser'])     ->name('admin.delete-user');
    Route::get('/categories',                    [AdminController::class, 'categories'])     ->name('admin.categories');
    Route::post('/categories',                   [AdminController::class, 'storeCategorie']) ->name('admin.store-categorie');
    Route::delete('/categories/{categorie}',     [AdminController::class, 'deleteCategorie'])->name('admin.delete-categorie');
});

// ─────────────────────────────────────
// Routes Gérant
// ─────────────────────────────────────
Route::prefix('gerant')->middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [GerantController::class, 'dashboard'])->name('gerant.dashboard');

    // Produits
    Route::get('/produits',                  [GerantController::class, 'produits'])     ->name('gerant.produits');
    Route::post('/produits/store',           [GerantController::class, 'storeProduit']) ->name('gerant.store-produit');
    Route::get('/produits/{produit}/edit',   [GerantController::class, 'editProduit'])  ->name('gerant.edit-produit');
    Route::put('/produits/{produit}/update', [GerantController::class, 'updateProduit'])->name('gerant.update-produit');
    Route::delete('/produits/{produit}',     [GerantController::class, 'deleteProduit'])->name('gerant.delete-produit');

    // Catégories ── NOUVEAU ──
    Route::get('/categories',                [GerantController::class, 'categories'])     ->name('gerant.categories');
    Route::post('/categories/store',         [GerantController::class, 'storeCategorie']) ->name('gerant.store-categorie');
    Route::delete('/categories/{categorie}', [GerantController::class, 'deleteCategorie'])->name('gerant.delete-categorie');

    // Stocks
    Route::get('/stocks',                [GerantController::class, 'stocks'])      ->name('gerant.stocks');
    Route::put('/stocks/{stock}/update', [GerantController::class, 'updateStock']) ->name('gerant.update-stock');

    // Commandes
    Route::get('/commandes',                  [GerantController::class, 'commandes'])      ->name('gerant.commandes');
    Route::put('/commandes/{commande}/update',[GerantController::class, 'updateCommande']) ->name('gerant.update-commande');

    // Approvisionnement
    Route::get('/approvisionnement',   [GerantController::class, 'approvisionnement']) ->name('gerant.approvisionnement');
    Route::post('/fournisseur/store',  [GerantController::class, 'storeFournisseur'])  ->name('gerant.store-fournisseur');

    // Rapports
    Route::get('/etat-stock',  [GerantController::class, 'etatStock'])  ->name('gerant.etat-stock');
    Route::get('/etat-ventes', [GerantController::class, 'etatVentes']) ->name('gerant.etat-ventes');
    Route::post('/bon/store', [GerantController::class, 'storeBon'])->name('gerant.store-bon');
});

// ─────────────────────────────────────
// Routes Vendeur
// ─────────────────────────────────────
Route::prefix('vendeur')->middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [VendeurController::class, 'dashboard'])->name('vendeur.dashboard');

    // Produits (recherche)
    Route::get('/produits', [VendeurController::class, 'produits'])->name('vendeur.produits');

    // Commandes
    Route::get('/commandes',                  [VendeurController::class, 'commandes'])        ->name('vendeur.commandes');
    Route::get('/commandes/{id}',             [VendeurController::class, 'commandeShow'])     ->name('vendeur.commandes.show');
    Route::patch('/commandes/{id}/confirmer', [VendeurController::class, 'commandeConfirmer'])->name('vendeur.commandes.confirmer');
    Route::patch('/commandes/{id}/preparer',  [VendeurController::class, 'commandePreparer']) ->name('vendeur.commandes.preparer');
    Route::get('/commandes/{id}/envoyer',     [VendeurController::class, 'commandeEnvoyer'])  ->name('vendeur.commandes.envoyer');

    // Livraisons
    Route::get('/livraisons',             [VendeurController::class, 'livraisons'])      ->name('vendeur.livraisons');
    Route::get('/livraisons/{id}',        [VendeurController::class, 'livraisonShow'])   ->name('vendeur.livraisons.show');
    Route::patch('/livraisons/{id}/livrer',[VendeurController::class, 'livraisonLivrer'])->name('vendeur.livraisons.livrer');
    Route::post('/commandes/{id}/assigner-livreur', [VendeurController::class, 'commandeAssignerLivreur'])->name('vendeur.commandes.assigner-livreur');

    // Clients
    Route::get('/clients',      [VendeurController::class, 'clients'])    ->name('vendeur.clients');
    Route::get('/clients/{id}', [VendeurController::class, 'clientShow']) ->name('vendeur.clients.show');

    // Factures
    Route::get('/factures',               [VendeurController::class, 'factures'])       ->name('vendeur.factures');
    Route::get('/factures/{id}',          [VendeurController::class, 'factureShow'])    ->name('vendeur.factures.show');
    Route::patch('/factures/{id}/payer',  [VendeurController::class, 'facturePayer'])   ->name('vendeur.factures.payer');
    Route::get('/factures/{id}/imprimer', [VendeurController::class, 'factureImprimer'])->name('vendeur.factures.pdf');
    
});
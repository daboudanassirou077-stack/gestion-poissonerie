<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GerantController;

// ─────────────────────────────────────
// Pages publiques (tout le monde)
// ─────────────────────────────────────
// Accueil
Route::get('/', function () {
    $produits = \App\Models\Produit::with(['categorie', 'calibre', 'stock'])
                ->where('actif', true)
                ->latest()
                ->take(8)
                ->get();
    return view('home', compact('produits'));
})->name('home');

// Boutique
Route::get('/shop', function () {
    $produits = \App\Models\Produit::with(['categorie', 'calibre', 'stock'])
        ->where('actif', true)
        ->when(request('categorie'), function($q) {
            $q->whereHas('categorie', function($q) {
                $q->where('slug', request('categorie'));
            });
        })
        ->when(request('q'), function($q) {
            $q->where('libelle_prod', 'like', '%' . request('q') . '%');
        })
        ->when(request('prix_max'), function($q) {
            $q->where('prix', '<=', request('prix_max'));
        })
        ->when(request('tri') === 'prix_asc',   fn($q) => $q->orderBy('prix', 'asc'))
        ->when(request('tri') === 'prix_desc',  fn($q) => $q->orderBy('prix', 'desc'))
        ->when(request('tri') === 'nouveautes', fn($q) => $q->latest())
        ->paginate(9);

    return view('shop', compact('produits'));
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



// Panier public (ajouter, voir, modifier)
Route::get( '/cart',             [CartController::class, 'index'])  ->name('cart');
Route::get( '/cart/index',       [CartController::class, 'index'])  ->name('cart.index');
Route::post('/cart/add',         [CartController::class, 'add'])    ->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update']) ->name('cart.update');
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

    // Checkout et commande (connexion obligatoire)
    Route::get( '/checkout',           [CartController::class, 'checkout'])     ->name('cart.checkout');
    Route::post('/checkout/order',     [CartController::class, 'order'])        ->name('cart.order');
    Route::get( '/checkout/payment',   [CartController::class, 'payment'])      ->name('cart.payment');
    Route::get( '/order/confirmation', [CartController::class, 'confirmation']) ->name('order.confirmation');

    // Dashboards
    Route::get('/dashboard/client',  fn() => view('dashboard.client')) ->name('client.dashboard');
    Route::get('/dashboard/admin',   fn() => view('dashboard.admin'))  ->name('admin.dashboard');
    Route::get('/dashboard/gerant',  fn() => view('dashboard.gerant')) ->name('gerant.dashboard');
    Route::get('/dashboard/vendeur', fn() => view('dashboard.vendeur'))->name('vendeur.dashboard');

    //Route profil client

    // Profil client
Route::put('/client/profile/update',  [AuthController::class, 'updateProfile']) ->name('client.profile.update');
Route::put('/client/password/update', [AuthController::class, 'updatePassword'])->name('client.password.update');
});

// ─────────────────────────────────────
// Routes Admin
// ─────────────────────────────────────
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard',        [AdminController::class, 'dashboard'])      ->name('admin.dashboard');
    Route::get('/users',            [AdminController::class, 'users'])          ->name('admin.users');
    Route::get('/users/create',     [AdminController::class, 'createUser'])     ->name('admin.create-user');
    Route::post('/users/store',     [AdminController::class, 'storeUser'])      ->name('admin.store-user');
    Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('admin.toggle-user');
    Route::delete('/users/{user}',  [AdminController::class, 'deleteUser'])     ->name('admin.delete-user');
    Route::get('/categories',       [AdminController::class, 'categories'])     ->name('admin.categories');
    Route::post('/categories',      [AdminController::class, 'storeCategorie']) ->name('admin.store-categorie');
    Route::delete('/categories/{categorie}', [AdminController::class, 'deleteCategorie'])->name('admin.delete-categorie');
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

    // Stocks
    Route::get('/stocks',                    [GerantController::class, 'stocks'])      ->name('gerant.stocks');
    Route::put('/stocks/{stock}/update',     [GerantController::class, 'updateStock']) ->name('gerant.update-stock');

    // Commandes
    Route::get('/commandes',                         [GerantController::class, 'commandes'])      ->name('gerant.commandes');
    Route::put('/commandes/{commande}/update',        [GerantController::class, 'updateCommande']) ->name('gerant.update-commande');

    // Approvisionnement
    Route::get('/approvisionnement',         [GerantController::class, 'approvisionnement']) ->name('gerant.approvisionnement');
    Route::post('/fournisseur/store',        [GerantController::class, 'storeFournisseur'])  ->name('gerant.store-fournisseur');

    // Rapports
    Route::get('/etat-stock',  [GerantController::class, 'etatStock'])  ->name('gerant.etat-stock');
    Route::get('/etat-ventes', [GerantController::class, 'etatVentes']) ->name('gerant.etat-ventes');
});
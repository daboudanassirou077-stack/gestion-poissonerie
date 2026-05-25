<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Client;

class CartController extends Controller
{
    // ───────────────────────────────────────────
    // Helpers session
    // ───────────────────────────────────────────

    private function getCart(): array
    {
        return session('cart', []);
    }

    private function saveCart(array $cart): void
    {
        session(['cart' => $cart]);
    }

    // ───────────────────────────────────────────
    // Afficher le panier
    // ───────────────────────────────────────────

    public function index()
    {
        $cart  = $this->getCart();
        $total = collect($cart)->sum(fn($item) => $item['prix'] * $item['quantite']);

        return view('cart', compact('cart', 'total'));
    }

    // ───────────────────────────────────────────
    // Ajouter au panier
    // ───────────────────────────────────────────
public function add(Request $request)
{
    $request->validate([
        'produit_id' => 'required|integer',
        'quantite'   => 'required|numeric|min:0.1|max:100',
        'calibre_id' => 'nullable|integer|exists:calibres,id_calibre',
    ]);

    $id         = $request->produit_id;
    $qty        = (float) $request->quantite;
    $calibreId  = $request->calibre_id;

    $produit = \App\Models\Produit::with(['categorie', 'calibre', 'stock'])->find($id);

    if (!$produit) {
        return back()->with('error', 'Produit introuvable.');
    }

    if ($produit->stock && $produit->stock->quantite_stock <= 0) {
        return back()->with('error', '❌ Ce produit est épuisé.');
    }

    // Si c'est un poisson et qu'aucun calibre n'est choisi → erreur
    $estPoisson = $produit->calibre && $produit->calibre->type_produit === 'poisson';
    if ($estPoisson && !$calibreId) {
        return back()->with('error', '⚠️ Veuillez choisir un calibre pour ce poisson.');
    }

    // Récupérer le calibre choisi
    $calibre     = $calibreId ? \App\Models\Calibre::find($calibreId) : $produit->calibre;
    $calibreNom  = $calibre ? ucfirst($calibre->taille ?? $calibre->type_produit) : '';

    // Clé unique : produit + calibre (permet d'avoir Grande et Moyen séparément)
    $cartKey = $estPoisson ? $id . '_cal_' . $calibreId : $id;

    $cart = $this->getCart();

    if (isset($cart[$cartKey])) {
        $cart[$cartKey]['quantite'] += $qty;
    } else {
        $cart[$cartKey] = [
            'id'          => $produit->id_prod,
            'nom'         => $produit->libelle_prod . ($calibreNom ? ' — ' . $calibreNom : ''),
            'nom_base'    => $produit->libelle_prod,
            'prix'        => $produit->prix,
            'image'       => $produit->image,
            'categorie'   => $produit->categorie->libelle ?? '',
            'unite'       => 'kg',
            'quantite'    => $qty,
            'calibre_id'  => $calibreId,
            'calibre_nom' => $calibreNom,
            'est_poisson' => $estPoisson,
        ];
    }

    $this->saveCart($cart);

    $msg = $estPoisson
        ? "✅ {$produit->libelle_prod} ({$calibreNom}) ajouté au panier !"
        : "✅ {$produit->libelle_prod} ajouté au panier !";

    return back()->with('success', $msg);
}
   

  
    // ───────────────────────────────────────────
    // Mettre à jour la quantité
    // ───────────────────────────────────────────

    public function update(Request $request, string $id)
    {
        $request->validate(['quantite' => 'required|integer|min:1|max:50']);

        $cart = $this->getCart();

        if (isset($cart[$id])) {
            $cart[$id]['quantite'] = (int) $request->quantite;
            $this->saveCart($cart);
        }

        if ($request->ajax()) {
            $total     = collect($cart)->sum(fn($i) => $i['prix'] * $i['quantite']);
            $itemTotal = $cart[$id]['prix'] * $cart[$id]['quantite'];
            return response()->json([
                'item_total' => number_format($itemTotal, 0, ',', ' '),
                'cart_total' => number_format($total,     0, ',', ' '),
                'count'      => collect($cart)->sum('quantite'),
            ]);
        }

        return redirect()->route('cart.index');
    }

    // ───────────────────────────────────────────
    // Supprimer un article
    // ───────────────────────────────────────────

    public function remove(string $id)
    {
        $cart = $this->getCart();
        unset($cart[$id]);
        $this->saveCart($cart);

        return back()->with('success', 'Article retiré du panier.');
    }

    // ───────────────────────────────────────────
    // Vider le panier
    // ───────────────────────────────────────────

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Panier vidé.');
    }

    // ───────────────────────────────────────────
    // Page checkout
    // ───────────────────────────────────────────

    public function checkout()
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $total = collect($cart)->sum(fn($item) => $item['prix'] * $item['quantite']);

        return view('checkout', compact('cart', 'total'));
    }

    // ───────────────────────────────────────────
    // Traiter la commande (Créer en base)
    // ───────────────────────────────────────────

    public function order(Request $request)
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $request->validate([
            'prenom'        => 'required|string|max:80',
            'nom'           => 'required|string|max:80',
            'telephone'     => 'required|string|max:20',
            'quartier'      => 'required|string|max:100',
            'adresse'       => 'required|string|max:255',
            'instructions'  => 'nullable|string|max:255',
        ]);

        $total = collect($cart)->sum(fn($i) => $i['prix'] * $i['quantite']);

        // Récupérer ou créer le client
        $user = auth()->user();
        $client = $user->client ?? Client::create([
            'id_user' => $user->id_user,
            'nom_client' => $request->nom,
            'prenom_client' => $request->prenom,
            'email_client' => $user->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse . ', ' . $request->quartier,
        ]);

        // Créer la commande
        $reference = 'FM-' . strtoupper(uniqid());
        $commande = Commande::create([
            'id_client' => $client->id_client,
            'reference' => $reference,
            'date_cmd' => now()->toDateString(),
            'statut_cmd' => 'en_attente',
            'montant_total' => $total,
            'adresse_livraison' => $request->adresse,
            'quartier' => $request->quartier,
            'instructions_livraison' => $request->instructions,
            'statut_paiement' => 'en_attente',
        ]);

        // Créer les lignes de commande
        foreach ($cart as $id => $item) {
            $commande->produits()->attach($item['id'], [
                'quantite_cmder' => $item['quantite'],
                'prix_comd' => $item['prix'],
            ]);
        }

        // Sauvegarder l'id_cmd en session pour récupération après paiement
        session(['commande_id' => $commande->id_cmd]);

        return redirect()->route('cart.payment');
    }

    // ───────────────────────────────────────────
    // Page de paiement Kkiapay
    // ───────────────────────────────────────────

    public function payment(Request $request)
    {
        $commandeId = session('commande_id');

        if (!$commandeId) {
            return redirect()->route('cart.checkout')->with('error', 'Commande introuvable.');
        }

        $commande = Commande::with('produits')->find($commandeId);

        if (!$commande) {
            return redirect()->route('cart.checkout')->with('error', 'Commande introuvable.');
        }

        // Vérifier si callback avec transaction_id
        if ($request->has('transaction_id')) {
            $transactionId = $request->transaction_id;

            if (empty(config('services.kkiapay.public_key')) || empty(config('services.kkiapay.private_key'))) {
                return redirect()->route('cart.checkout')->with('error', 'Configuration Kkiapay manquante.');
            }

            $kkiapay = new \Kkiapay\Kkiapay(
                config('services.kkiapay.public_key'),
                config('services.kkiapay.private_key'),
                config('services.kkiapay.secret'),
                config('services.kkiapay.sandbox', true)
            );

            $verification = $kkiapay->verifyTransaction($transactionId);

            \Log::info('Kkiapay verification response', ['transaction_id' => $transactionId, 'response' => $verification]);

            $status = null;
            if (is_array($verification) && isset($verification['status'])) {
                $status = $verification['status'];
            } elseif (is_object($verification) && isset($verification->status)) {
                $status = $verification->status;
            }

            if ($status !== 'SUCCESS') {
                // Marquer le paiement comme échoué
                $commande->update(['statut_paiement' => 'echoue']);
                return redirect()->route('cart.checkout')->with('error', 'Paiement échoué. Veuillez réessayer.');
            }

            // Paiement réussi - mettre à jour la commande
            $commande->update([
                'statut_paiement' => 'paye',
                'statut_cmd' => 'confirmee',
            ]);

            $commande->refresh();

            // Stocker en session pour la page de confirmation
            session([
                'last_order' => [
                    'reference'      => $commande->reference,
                    'prenom'         => $commande->client->prenom_client,
                    'nom'            => $commande->client->nom_client,
                    'telephone'      => $commande->client->telephone,
                    'adresse'        => $commande->adresse_livraison . ', ' . $commande->quartier,
                    'instructions'   => $commande->instructions_livraison,
                    'total'          => $commande->montant_total,
                    'transaction_id' => $transactionId,
                    'momo_operateur' => $commande->momo_operateur ?? null,
                    'statut_paiement' => 'paye',
                    'articles'       => $commande->produits->map(function($produit) {
                        return [
                            'id' => $produit->id_prod,
                            'nom' => $produit->libelle_prod,
                            'prix' => $produit->pivot->prix_comd,
                            'quantite' => $produit->pivot->quantite_cmder,
                            'image' => $produit->image,
                        ];
                    })->toArray(),
                    'created_at'     => $commande->created_at->format('d/m/Y H:i'),
                ]
            ]);

            // Vider le panier et nettoyer la session
            session()->forget(['cart', 'commande_id']);

            return redirect()->route('order.confirmation');
        }

        // Afficher la page de paiement
        return view('payment', compact('commande'));
    }

    // ───────────────────────────────────────────
    // Page de confirmation
    // ───────────────────────────────────────────

    public function confirmation()
    {
        $order = session('last_order');
        // dd($order);

        if (!$order) {
            return redirect()->route('home');
        }

        return view('order-confirmation', compact('order'));
    }

    // ───────────────────────────────────────────
    // Compter les articles (AJAX navbar)
    // ───────────────────────────────────────────

    public function count()
    {
        $cart  = $this->getCart();
        $count = collect($cart)->sum('quantite');
        return response()->json(['count' => $count]);
    }
}

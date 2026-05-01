<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Gerant;
use App\Models\Vendeur;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ===== MIDDLEWARE =====
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Accès refusé.');
            }
            return $next($request);
        });
    }

    // ===== DASHBOARD =====
    public function dashboard()
    {
        $stats = [
            'total_users'     => User::count(),
            'total_clients'   => User::where('role', 'client')->count(),
            'total_gerants'   => User::where('role', 'gerant')->count(),
            'total_vendeurs'  => User::where('role', 'vendeur')->count(),
            'total_commandes' => Commande::count(),
            'total_produits'  => Produit::count(),
            'revenus_total'   => Commande::where('statut_paiement', 'paye')->sum('montant_total'),
            'commandes_attente' => Commande::where('statut_cmd', 'en_attente')->count(),
        ];

        $derniers_users    = User::latest()->take(5)->get();
        $dernieres_commandes = Commande::latest()->take(5)->get();

        return view('dashboard.admin', compact('stats', 'derniers_users', 'dernieres_commandes'));
    }

    // ===== LISTE UTILISATEURS =====
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard.admin-users', compact('users'));
    }

    // ===== CRÉER COMPTE (Gérant / Vendeur / Livreur) =====
    public function createUser()
    {
        return view('dashboard.admin-create-user');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'nom'       => 'required|string|max:100',
            'prenom'    => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'telephone' => 'required|string|max:20',
            'role'      => 'required|in:gerant,vendeur,admin',
            'mdp'       => 'required|string|min:6',
        ], [
            'email.unique' => 'Cet email est déjà utilisé.',
            'role.in'      => 'Le rôle sélectionné est invalide.',
        ]);

        // Créer le user
        $user = User::create([
            'nom'       => $request->nom,
            'prenom'    => $request->prenom,
            'email'     => $request->email,
            'telephone' => $request->telephone,
            'role'      => $request->role,
            'mdp'       => Hash::make($request->mdp),
        ]);

        // Créer le profil selon le rôle
        match($request->role) {
            'gerant'  => Gerant::create(['id_user' => $user->id_user]),
            'vendeur' => Vendeur::create(['id_user' => $user->id_user]),
            default   => null,
        };

        return redirect()->route('admin.users')
                         ->with('success', '✅ Compte ' . $request->role . ' créé pour ' . $user->prenom . ' !');
    }

    // ===== BLOQUER / DÉBLOQUER =====
    public function toggleUser(User $user)
    {
        // On ne peut pas bloquer un admin
        if ($user->role === 'admin') {
            return back()->with('error', '❌ Impossible de bloquer un administrateur.');
        }

        // Utiliser le champ role pour bloquer
        if ($user->role === 'bloque') {
            // Débloquer — on remet client par défaut
            $user->update(['role' => 'client']);
            $message = '✅ Compte débloqué avec succès.';
        } else {
            $user->update(['role' => 'bloque']);
            $message = '🔒 Compte bloqué avec succès.';
        }

        return back()->with('success', $message);
    }

    // ===== SUPPRIMER UTILISATEUR =====
    public function deleteUser(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', '❌ Impossible de supprimer un administrateur.');
        }

        $nom = $user->prenom . ' ' . $user->nom;
        $user->delete();

        return back()->with('success', '🗑️ Compte de ' . $nom . ' supprimé.');
    }

    // ===== GESTION CATÉGORIES =====
    public function categories()
    {
        $categories = Categorie::withCount('produits')->get();
        return view('dashboard.admin-categories', compact('categories'));
    }

    public function storeCategorie(Request $request)
    {
        $request->validate([
            'libelle'     => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        Categorie::create([
            'libelle'     => $request->libelle,
            'slug'        => \Illuminate\Support\Str::slug($request->libelle),
            'description' => $request->description,
        ]);

        return back()->with('success', '✅ Catégorie ajoutée avec succès !');
    }

    public function deleteCategorie(Categorie $categorie)
    {
        if ($categorie->produits->count() > 0) {
            return back()->with('error', '❌ Impossible de supprimer une catégorie qui contient des produits.');
        }

        $categorie->delete();
        return back()->with('success', '🗑️ Catégorie supprimée.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // ===== PAGE INSCRIPTION =====
    public function showRegister()
    {
        return view('auth.register');
    }

    // ===== TRAITEMENT INSCRIPTION =====
    public function register(Request $request)
    {
        $request->validate([
            'nom'       => 'required|string|max:100',
            'prenom'    => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'telephone' => 'required|string|max:20',
            'adresse'   => 'required|string|max:255',
            'mdp'       => 'required|string|min:6|confirmed',
        ], [
            'nom.required'       => 'Le nom est obligatoire.',
            'prenom.required'    => 'Le prénom est obligatoire.',
            'email.required'     => 'L\'email est obligatoire.',
            'email.unique'       => 'Cet email est déjà utilisé.',
            'telephone.required' => 'Le téléphone est obligatoire.',
            'adresse.required'   => 'L\'adresse est obligatoire.',
            'mdp.required'       => 'Le mot de passe est obligatoire.',
            'mdp.min'            => 'Le mot de passe doit faire au moins 6 caractères.',
            'mdp.confirmed'      => 'Les mots de passe ne correspondent pas.',
        ]);

        // Garder le mot de passe en clair pour l'email
        $motDePasseClair = $request->mdp;

        // Créer le user
        $user = User::create([
            'nom'       => $request->nom,
            'prenom'    => $request->prenom,
            'email'     => $request->email,
            'telephone' => $request->telephone,
            'mdp'       => Hash::make($request->mdp),
            'role'      => 'client',
        ]);

        // Créer le client associé
        Client::create([
            'id_user'       => $user->id_user,
            'nom_client'    => $request->nom,
            'prenom_client' => $request->prenom,
            'email_client'  => $request->email,
            'telephone'     => $request->telephone,
            'adresse'       => $request->adresse,
        ]);

        // Envoyer l'email de bienvenue avec les identifiants
        try {
            Mail::to($user->email)->send(new WelcomeMail($user, $motDePasseClair));
        } catch (\Exception $e) {
            // Si l'email échoue, on continue quand même
        }

        // Connecter automatiquement
        Auth::login($user);

        return redirect()->route('home')
                         ->with('success', '🎉 Bienvenue ' . $user->prenom . ' ! Vos identifiants ont été envoyés par email.');
    }

    // ===== PAGE CONNEXION =====
    public function showLogin()
    {
        return view('auth.login');
    }

    // ===== TRAITEMENT CONNEXION =====
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mdp'   => 'required|string',
        ], [
            'email.required' => 'L\'email est obligatoire.',
            'mdp.required'   => 'Le mot de passe est obligatoire.',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->mdp], $request->remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            return match($user->role) {
                'admin'   => redirect()->route('admin.dashboard'),
                'gerant'  => redirect()->route('gerant.dashboard'),
                'vendeur' => redirect()->route('vendeur.dashboard'),
                default   => redirect()->route('home'),
            };
        }

        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.',
        ])->withInput($request->only('email'));
    }

    // ===== DÉCONNEXION =====
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
                         ->with('success', 'Vous avez été déconnecté avec succès.');
    }

    // ===== MODIFIER PROFIL =====
public function updateProfile(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'prenom'    => 'required|string|max:100',
        'nom'       => 'required|string|max:100',
        'telephone' => 'nullable|string|max:20',
        'adresse'   => 'nullable|string|max:255',
    ], [
        'prenom.required' => 'Le prénom est obligatoire.',
        'nom.required'    => 'Le nom est obligatoire.',
    ]);

    // Mettre à jour le user
    $user->update([
        'prenom'    => $request->prenom,
        'nom'       => $request->nom,
        'telephone' => $request->telephone,
    ]);

    // Mettre à jour le client associé
    if ($user->client) {
        $user->client->update([
            'nom_client'    => $request->nom,
            'prenom_client' => $request->prenom,
            'telephone'     => $request->telephone,
            'adresse'       => $request->adresse,
        ]);
    }

    return back()->with('success', '✅ Profil mis à jour avec succès !');
}

// ===== CHANGER MOT DE PASSE =====
public function updatePassword(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'mdp_actuel' => 'required|string',
        'mdp'        => 'required|string|min:6|confirmed',
    ], [
        'mdp_actuel.required' => 'Le mot de passe actuel est obligatoire.',
        'mdp.required'        => 'Le nouveau mot de passe est obligatoire.',
        'mdp.min'             => 'Le mot de passe doit faire au moins 6 caractères.',
        'mdp.confirmed'       => 'Les mots de passe ne correspondent pas.',
    ]);

    // Vérifier l'ancien mot de passe
    if (!Hash::check($request->mdp_actuel, $user->mdp)) {
        return back()->withErrors([
            'mdp_actuel' => 'Le mot de passe actuel est incorrect.'
        ]);
    }

    // Mettre à jour
    $user->update([
        'mdp' => Hash::make($request->mdp),
    ]);

    return back()->with('success', '✅ Mot de passe modifié avec succès !');
}
}
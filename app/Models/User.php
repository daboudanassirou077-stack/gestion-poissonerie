<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Clé primaire personnalisée
    protected $primaryKey = 'id_user';
    protected $authPasswordName = 'mdp';

    // Champs autorisés à la création
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mdp',
        'telephone',
        'role',
    ];

    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'email_verified_at' => 'datetime',
];

    // Champs cachés (jamais retournés en JSON)
    protected $hidden = [
        'mdp',
        'remember_token',
    ];

    // Dire à Laravel que le mot de passe s'appelle 'mdp'
    public function getAuthPassword()
    {
        return $this->mdp;
    }

    // ===== RELATIONS =====

    // Un user peut être un client
    public function client()
    {
        return $this->hasOne(Client::class, 'id_user', 'id_user');
    }

    // Un user peut être un gérant
    public function gerant()
    {
        return $this->hasOne(Gerant::class, 'id_user', 'id_user');
    }

    // Un user peut être un vendeur
    public function vendeur()
    {
        return $this->hasOne(Vendeur::class, 'id_user', 'id_user');
    }

    // Un user peut être un admin
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_user', 'id_user');
    }

    // ===== HELPERS RÔLES =====

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGerant(): bool
    {
        return $this->role === 'gerant';
    }

    public function isVendeur(): bool
    {
        return $this->role === 'vendeur';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $primaryKey = 'id_client';

    protected $fillable = [
        'id_user',
        'nom_client',
        'prenom_client',
        'email_client',
        'telephone',
        'adresse',
    ];

    // ===== RELATIONS =====

    // Un client appartient à un user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Un client peut avoir plusieurs commandes
    public function commandes()
    {
        return $this->hasMany(Commande::class, 'id_client', 'id_client');
    }
}
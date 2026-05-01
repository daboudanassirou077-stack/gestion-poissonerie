<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $primaryKey = 'id_cmd';
    protected $fillable = [
        'id_client',
        'reference',
        'date_cmd',
        'statut_cmd',
        'montant_total',
        'adresse_livraison',
        'quartier',
        'instructions_livraison',
        'momo_operateur',
        'momo_numero',
        'statut_paiement',
    ];

    protected $casts = [
    'date_cmd'   => 'date',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];


    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'commander', 'id_cmd', 'id_prod')
                    ->withPivot('quantite_cmder', 'prix_comd')
                    ->withTimestamps();
    }

    public function facture()
    {
        return $this->hasOne(Facture::class, 'id_cmd', 'id_cmd');
    }

    public function livraisonCl()
    {
        return $this->hasOne(LivraisonCl::class, 'id_cmd', 'id_cmd');
    }

    // Helper statut
    public function estLivree(): bool
    {
        return $this->statut_cmd === 'livree';
    }

    public function estPayee(): bool
    {
        return $this->statut_paiement === 'paye';
    }
}
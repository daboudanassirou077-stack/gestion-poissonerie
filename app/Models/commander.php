<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Commander extends Model
{
    protected $table = 'commander';

    protected $fillable = [
        'id_cmd',
        'id_prod',
        'quantite_cmder',
        'prix_comd',
    ];

    // Relation vers la commande
    public function commande()
    {
        return $this->belongsTo(Commande::class, 'id_cmd', 'id_cmd');
    }

    // Relation vers le produit
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_prod', 'id_prod');
    }

    // Calculer le sous-total
    public function getSousTotalAttribute(): float
    {
        return $this->quantite_cmder * $this->prix_comd;
    }
}
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CommanderFrs extends Model
{
    protected $table = 'commander_frs';

    protected $fillable = [
        'id_bon',
        'id_prod',
        'quantite_cmd',
        'prix_comd',
    ];

    // Relation vers le bon fournisseur
    public function bonFrs()
    {
        return $this->belongsTo(BonFrs::class, 'id_bon', 'id_bon');
    }

    // Relation vers le produit
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_prod', 'id_prod');
    }

    // Calculer le sous-total
    public function getSousTotalAttribute(): float
    {
        return $this->quantite_cmd * $this->prix_comd;
    }
}
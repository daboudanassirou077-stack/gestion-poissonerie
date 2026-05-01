<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BonFrs extends Model
{
    protected $table = 'bons_frs';
    protected $primaryKey = 'id_bon';
    protected $fillable = [
        'id_frs',
        'date_bon',
        'montant_total',
        'statut',
    ];

    protected $casts = [
        'date_bon' => 'date',
    ];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'id_frs', 'id_frs');
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'commander_frs', 'id_bon', 'id_prod')
                    ->withPivot('quantite_cmd', 'prix_comd')
                    ->withTimestamps();
    }

    public function livraison()
    {
        return $this->hasOne(Livraison::class, 'id_bon', 'id_bon');
    }
}
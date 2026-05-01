<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Livrer extends Model
{
    protected $table = 'livrer';

    protected $fillable = [
        'id_livs',
        'id_prod',
        'quantite',
    ];

    // Relation vers la livraison
    public function livraison()
    {
        return $this->belongsTo(Livraison::class, 'id_livs', 'id_livs');
    }

    // Relation vers le produit
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_prod', 'id_prod');
    }
}
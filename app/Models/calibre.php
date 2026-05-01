<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Calibre extends Model
{
    protected $primaryKey = 'id_calibre';
    protected $fillable = [
        'type_produit',
        'unite_vente',
        'poids_min',
        'poids_max',
        'taille',
    ];

    public function produits()
    {
        return $this->hasMany(Produit::class, 'id_calibre', 'id_calibre');
    }
}
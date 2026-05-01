<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Livraison extends Model
{
    protected $primaryKey = 'id_livs';
    protected $fillable = [
        'id_bon',
        'date_livs',
        'adresse_liv',
        'statut',
    ];

    protected $casts = [
        'date_livs' => 'date',
    ];

    public function bonFrs()
    {
        return $this->belongsTo(BonFrs::class, 'id_bon', 'id_bon');
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'livrer', 'id_livs', 'id_prod')
                    ->withPivot('quantite')
                    ->withTimestamps();
    }
}
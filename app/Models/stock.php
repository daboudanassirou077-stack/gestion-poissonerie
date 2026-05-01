<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $primaryKey = 'id_stock';
    protected $fillable = [
        'id_prod',
        'quantite_stock',
        'seuil_alerte',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_prod', 'id_prod');
    }

    // Vérifier si stock faible
    public function estFaible(): bool
    {
        return $this->quantite_stock <= $this->seuil_alerte;
    }
}
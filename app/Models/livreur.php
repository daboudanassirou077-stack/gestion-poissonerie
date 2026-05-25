<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Livreur extends Model
{
    protected $table = 'livreurs_cl';
    protected $primaryKey = 'id_livreur';

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email',
        'adresse',
        'actif',
    ];

    // Relation vers les livraisons effectuées
    public function livraisons()
    {
        return $this->hasMany(LivraisonCl::class, 'id_livreur', 'id_livreur');
    }

    // Helper — livreur disponible ?
    public function estActif(): bool
    {
        return $this->actif === true;
    }
}
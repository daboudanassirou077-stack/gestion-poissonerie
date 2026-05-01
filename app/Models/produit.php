<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $primaryKey = 'id_prod';
    protected $fillable = [
        'id_categorie',
        'id_calibre',
        'libelle_prod',
        'description',
        'prix',
        'image',
        'actif',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'id_categorie', 'id_categorie');
    }

    public function calibre()
    {
        return $this->belongsTo(Calibre::class, 'id_calibre', 'id_calibre');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'id_prod', 'id_prod');
    }

    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commander', 'id_prod', 'id_cmd')
                    ->withPivot('quantite_cmder', 'prix_comd')
                    ->withTimestamps();
    }

    public function bonsFrs()
    {
        return $this->belongsToMany(BonFrs::class, 'commander_frs', 'id_prod', 'id_bon')
                    ->withPivot('quantite_cmd', 'prix_comd')
                    ->withTimestamps();
    }
}
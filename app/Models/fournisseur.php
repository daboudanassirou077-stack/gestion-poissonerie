<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $primaryKey = 'id_frs';
    protected $fillable = [
        'nom_frs',
        'prenom_frs',
        'telephone',
        'email',
        'adresse',
    ];

    public function bonsFrs()
    {
        return $this->hasMany(BonFrs::class, 'id_frs', 'id_frs');
    }
}
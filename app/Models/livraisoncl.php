<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LivraisonCl extends Model
{
    protected $table = 'livraisons_cl';
    protected $primaryKey = 'id_livcl';
    protected $fillable = [
        'id_cmd',
        'id_livreur',
        'date_livcl',
        'adresse_livcl',
        'statut',
    ];

    protected $casts = [
        'date_livcl' => 'date',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'id_cmd', 'id_cmd');
    }

    public function livreur()
    {
        return $this->belongsTo(LivreurCl::class, 'id_livreur', 'id_livreur');
    }
}
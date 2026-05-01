<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $primaryKey = 'id_fact';
    protected $fillable = [
        'id_cmd',
        'date_fact',
        'montant_fact',
        'mode_paie',
    ];

    protected $casts = [
        'date_fact' => 'date',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'id_cmd', 'id_cmd');
    }
}
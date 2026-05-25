<?php
// app/Models/Categorie.php — UPDATED

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $primaryKey = 'id_categorie';

    protected $fillable = [
        'libelle',
        'slug',
        'description',
        'image',        // ← NOUVEAU
    ];

    public function produits()
    {
        return $this->hasMany(Produit::class, 'id_categorie', 'id_categorie');
    }

    // ── Helper : retourne l'URL de l'image ou un placeholder ──
    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('images/categories/' . $this->image))) {
            return asset('images/categories/' . $this->image);
        }
        return asset('images/img-pro-01.jpg'); // image par défaut
    }
}
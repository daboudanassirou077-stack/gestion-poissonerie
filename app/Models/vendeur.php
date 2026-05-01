<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Vendeur extends Model
{
    protected $primaryKey = 'id_vendeur';
    protected $fillable = ['id_user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
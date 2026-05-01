<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Gerant extends Model
{
    protected $primaryKey = 'id_gerant';
    protected $fillable = ['id_user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
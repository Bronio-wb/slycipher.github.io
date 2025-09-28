<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logro extends Model
{
    protected $table = 'logros';
    protected $primaryKey = 'achievement_id';

    protected $fillable = [
        'titulo',
        'descripcion',
        'criterios',
        'language_id',
    ];

    // Relaciones
    public function lenguaje()
    {
        return $this->belongsTo(Lenguaje::class, 'language_id');
    }

    public function usuarios()
    {
        return $this->hasMany(LogroUsuario::class, 'achievement_id');
    }
}

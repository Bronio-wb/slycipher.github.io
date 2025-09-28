<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desafio extends Model
{
    protected $table = 'desafios';
    protected $primaryKey = 'challenge_id';

    protected $fillable = [
        'course_id',
        'language_id',
        'titulo',
        'descripcion',
        'dificultad',
        'solucion',
    ];

    // Relaciones
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'course_id');
    }

    public function lenguaje()
    {
        return $this->belongsTo(Lenguaje::class, 'language_id');
    }

    public function intentos()
    {
        return $this->hasMany(DesafioUsuario::class, 'challenge_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lenguaje extends Model
{
    protected $table = 'lenguajes';
    protected $primaryKey = 'language_id';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'color',
        'version',
        'estado',
    ];

    // Relaciones
    public function cursos()
    {
        return $this->hasMany(Curso::class, 'language_id');
    }

    public function desafios()
    {
        return $this->hasMany(Desafio::class, 'language_id');
    }

    public function logros()
    {
        return $this->hasMany(Logro::class, 'language_id');
    }
}

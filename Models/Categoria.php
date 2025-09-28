<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'color',
        'estado',
    ];

    // Relaciones
    public function cursos()
    {
        return $this->hasMany(Curso::class, 'category_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'cursos';
    protected $primaryKey = 'course_id';

    protected $fillable = [
        'titulo',
        'descripcion',
        'nivel',
        'language_id',
        'category_id',
        'creado_por',
        'estado',
    ];

    // Relaciones
    public function lenguaje()
    {
        return $this->belongsTo(Lenguaje::class, 'language_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'category_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    // Relación con lecciones
    public function lecciones()
    {
        return $this->hasMany(\App\Models\Leccion::class, 'course_id', 'id');
    }

    // Relación many-to-many con categorías (ajusta pivot si tu tabla es distinta)
    public function categorias()
    {
        return $this->belongsToMany(\App\Models\Categoria::class, 'curso_categoria', 'course_id', 'category_id');
    }

    // Relación many-to-many con lenguajes (ajusta pivot si tu tabla es distinta)
    public function lenguajes()
    {
        return $this->belongsToMany(\App\Models\Lenguaje::class, 'curso_lenguaje', 'course_id', 'language_id');
    }

    // Relación con progresos (ajusta el modelo y fk si tu tabla se llama distinto)
    public function progresos()
    {
        return $this->hasMany(\App\Models\Progreso::class, 'course_id', 'id');
    }

    // Garantizar que el route model binding use course_id
    public function getRouteKeyName()
    {
        return $this->primaryKey;
    }
}


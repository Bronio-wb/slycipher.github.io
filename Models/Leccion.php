<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leccion extends Model
{
    protected $table = 'lecciones';
    protected $primaryKey = 'lesson_id';

    protected $fillable = [
        'course_id',
        'titulo',
        'contenido',
        'orden',
        'estado',
    ];

    // Relaciones
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'course_id');
    }

    public function progresos()
    {
        return $this->hasMany(ProgresoUsuario::class, 'lesson_id');
    }
}

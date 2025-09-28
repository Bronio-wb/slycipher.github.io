<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgresoUsuario extends Model
{
    protected $table = 'progreso_usuarios';
    protected $primaryKey = 'progress_id';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'estado',
        'completado_en',
        'puntaje',
    ];

    protected $casts = [
        'completado_en' => 'datetime',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function leccion()
    {
        return $this->belongsTo(Leccion::class, 'lesson_id');
    }
}

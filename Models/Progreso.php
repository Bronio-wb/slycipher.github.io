<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progreso extends Model
{
    use HasFactory;

    protected $table = 'progresos';

    protected $fillable = [
        'usuario_id',
        'desafio_id',
        'intentos',
        'completado',
        'codigo_actual',
        'puntos_ganados',
        'fecha_completado'
    ];

    protected $casts = [
        'completado' => 'boolean',
        'fecha_completado' => 'datetime',
        'intentos' => 'integer',
        'puntos_ganados' => 'integer'
    ];

    /**
     * Relación con el usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación con el desafío
     */
    public function desafio()
    {
        return $this->belongsTo(Desafio::class, 'desafio_id');
    }
}
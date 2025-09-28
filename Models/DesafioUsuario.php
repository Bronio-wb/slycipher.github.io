<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesafioUsuario extends Model
{
    protected $table = 'desafios_usuarios';
    protected $primaryKey = 'user_challenge_id';

    protected $fillable = [
        'user_id',
        'challenge_id',
        'estado',
        'envio',
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

    public function desafio()
    {
        return $this->belongsTo(Desafio::class, 'challenge_id');
    }
}

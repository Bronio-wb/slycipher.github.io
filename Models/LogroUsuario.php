<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogroUsuario extends Model
{
    protected $table = 'logros_usuarios';
    protected $primaryKey = 'user_achievement_id';

    protected $fillable = [
        'user_id',
        'achievement_id',
        'desbloqueado_en',
    ];

    protected $casts = [
        'desbloqueado_en' => 'datetime',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function logro()
    {
        return $this->belongsTo(Logro::class, 'achievement_id');
    }
}

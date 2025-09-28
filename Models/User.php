<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'nombre',
        'apellido',
        'name',
        'email',
        'password',
        'rol',
        'ultimo_login',
        'estado',
        'activo',
        'racha',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'ultimo_login' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    // Relaciones
    public function cursosCreados()
    {
        return $this->hasMany(Curso::class, 'creado_por');
    }

    public function inscripciones()
    {
        return $this->hasMany(InscripcionCurso::class, 'user_id');
    }

    public function progreso()
    {
        return $this->hasMany(ProgresoLeccion::class, 'user_id');
    }

    public function progresos()
    {
        return $this->hasMany(ProgresoLeccion::class, 'user_id');
    }

    public function desafios()
    {
        return $this->hasMany(DesafioUsuario::class, 'user_id');
    }

    public function logros()
    {
        return $this->hasMany(LogroUsuario::class, 'user_id');
    }
}

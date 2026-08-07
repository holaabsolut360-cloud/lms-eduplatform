<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nombre', 'email', 'password', 'rol', 'avatar_url', 'telefono', 'activo',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activo' => 'boolean',
    ];

    public function cursosDictados(): HasMany
    {
        return $this->hasMany(Curso::class, 'instructor_id');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'estudiante_id');
    }

    public function esAdministrador(): bool
    {
        return $this->rol === 'administrador';
    }

    public function esInstructor(): bool
    {
        return in_array($this->rol, ['instructor', 'administrador']);
    }

    public function esEstudiante(): bool
    {
        return $this->rol === 'estudiante';
    }
}

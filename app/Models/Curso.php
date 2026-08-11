<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    protected $fillable = [
        'categoria_id', 'instructor_id', 'titulo', 'slug', 'descripcion_corta',
        'descripcion_larga', 'imagen_portada', 'video_promocional_url',
        'precio', 'precio_oferta', 'moneda', 'nivel', 'estado',
        'bloqueo_secuencial', 'certificado_habilitado', 'nota_minima_aprobacion',
        'destacado', 'orden_destacado',
    ];

    protected $casts = [
        'bloqueo_secuencial' => 'boolean',
        'certificado_habilitado' => 'boolean',
        'destacado' => 'boolean',
        'precio' => 'decimal:2',
        'precio_oferta' => 'decimal:2',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function modulos(): HasMany
    {
        return $this->hasMany(Modulo::class)->orderBy('orden');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function examenes(): HasMany
    {
        return $this->hasMany(Examen::class);
    }

    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class);
    }

    public function totalLecciones(): int
    {
        return $this->modulos->sum(fn ($m) => $m->lecciones->count());
    }

    // Un administrador puede gestionar cualquier curso; un instructor solo los suyos.
    public function perteneceA(User $user): bool
    {
        return $user->esAdministrador() || $this->instructor_id === $user->id;
    }
}

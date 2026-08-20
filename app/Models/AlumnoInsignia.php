<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumnoInsignia extends Model
{
    protected $table = 'alumno_insignias';

    protected $fillable = ['estudiante_id', 'curso_id', 'tipo', 'obtenida_en'];

    protected $casts = [
        'obtenida_en' => 'datetime',
    ];

    // Catálogo central de insignias: nombre visible + icono para las vistas
    public const TIPOS = [
        'primer_modulo' => ['nombre' => 'Primer módulo completado', 'icono' => '🎯'],
        'primer_examen_aprobado' => ['nombre' => 'Primer examen aprobado', 'icono' => '📝'],
        'curso_completado' => ['nombre' => 'Curso completado', 'icono' => '🏆'],
        'racha_7_dias' => ['nombre' => 'Racha de 7 días', 'icono' => '🔥'],
    ];

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function nombre(): string
    {
        return self::TIPOS[$this->tipo]['nombre'] ?? $this->tipo;
    }

    public function icono(): string
    {
        return self::TIPOS[$this->tipo]['icono'] ?? '⭐';
    }
}

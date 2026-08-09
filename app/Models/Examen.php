<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Examen extends Model
{
    protected $table = 'examenes';

    protected $fillable = [
        'curso_id', 'modulo_id', 'titulo', 'instrucciones',
        'tiempo_limite_min', 'intentos_permitidos', 'nota_minima_aprobacion',
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }

    public function preguntas(): HasMany
    {
        return $this->hasMany(Pregunta::class)->orderBy('orden');
    }

    public function intentos(): HasMany
    {
        return $this->hasMany(IntentoExamen::class);
    }

    public function puntajeTotal(): int
    {
        return $this->preguntas->sum('puntaje');
    }

    // Cuántos intentos le quedan a una matrícula específica
    public function intentosRestantes(int $matriculaId): int
    {
        $usados = $this->intentos()->where('matricula_id', $matriculaId)->count();
        return max(0, $this->intentos_permitidos - $usados);
    }
}

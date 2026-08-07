<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pregunta extends Model
{
    protected $fillable = [
        'examen_id', 'enunciado', 'tipo', 'puntaje', 'orden', 'respuesta_esperada',
    ];

    public function examen(): BelongsTo
    {
        return $this->belongsTo(Examen::class);
    }

    public function opciones(): HasMany
    {
        return $this->hasMany(OpcionPregunta::class)->orderBy('orden');
    }

    // Calificación automática para opción múltiple / verdadero-falso / respuesta corta
    public function esCorrecta(mixed $respuesta): bool
    {
        return match ($this->tipo) {
            'opcion_multiple', 'verdadero_falso' => $this->opciones()
                ->where('id', $respuesta)
                ->where('es_correcta', true)
                ->exists(),
            'respuesta_corta' => mb_strtolower(trim((string) $respuesta)) === mb_strtolower(trim((string) $this->respuesta_esperada)),
            default => false,
        };
    }
}

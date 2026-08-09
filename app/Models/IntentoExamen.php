<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntentoExamen extends Model
{
    protected $table = 'intentos_examen';

    protected $fillable = [
        'examen_id', 'matricula_id', 'numero_intento', 'respuestas',
        'nota_obtenida', 'aprobado', 'iniciado_en', 'finalizado_en',
    ];

    protected $casts = [
        'respuestas' => 'array',
        'aprobado' => 'boolean',
        'iniciado_en' => 'datetime',
        'finalizado_en' => 'datetime',
    ];

    public function examen(): BelongsTo
    {
        return $this->belongsTo(Examen::class);
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    // Recibe [{pregunta_id, respuesta}] y calcula la nota sobre 100 automáticamente
    public function calificar(): void
    {
        $examen = $this->examen()->with('preguntas')->first();
        $puntajeTotal = $examen->puntajeTotal() ?: 1;
        $puntajeObtenido = 0;

        foreach ($this->respuestas as $r) {
            $pregunta = $examen->preguntas->firstWhere('id', $r['pregunta_id']);
            if ($pregunta && $pregunta->esCorrecta($r['respuesta'] ?? null)) {
                $puntajeObtenido += $pregunta->puntaje;
            }
        }

        $nota = round(($puntajeObtenido / $puntajeTotal) * 100, 2);

        $this->update([
            'nota_obtenida' => $nota,
            'aprobado' => $nota >= $examen->nota_minima_aprobacion,
            'finalizado_en' => now(),
        ]);
    }
}

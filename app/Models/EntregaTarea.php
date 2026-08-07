<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntregaTarea extends Model
{
    protected $table = 'entregas_tarea';

    protected $fillable = [
        'tarea_id', 'matricula_id', 'archivo_url', 'comentario_alumno',
        'estado', 'nota', 'feedback_docente', 'entregado_en', 'calificado_en',
    ];

    protected $casts = [
        'entregado_en' => 'datetime',
        'calificado_en' => 'datetime',
    ];

    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class);
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    public function calificar(float $nota, ?string $feedback = null): void
    {
        $this->update([
            'nota' => $nota,
            'feedback_docente' => $feedback,
            'estado' => 'calificada',
            'calificado_en' => now(),
        ]);
    }
}

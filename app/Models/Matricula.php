<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matricula extends Model
{
    protected $fillable = [
        'curso_id', 'estudiante_id', 'estado', 'monto_pagado', 'moneda',
        'bloqueo_secuencial_override', 'porcentaje_avance', 'completado_en', 'matriculado_en',
        'ultimo_recordatorio_inactividad_en',
    ];

    protected $casts = [
        'bloqueo_secuencial_override' => 'boolean',
        'completado_en' => 'datetime',
        'matriculado_en' => 'datetime',
        'ultimo_recordatorio_inactividad_en' => 'datetime',
    ];

    // Fecha de la última actividad registrada: última lección completada, o fecha de matrícula si aún no completa ninguna
    public function ultimaActividad(): \Illuminate\Support\Carbon
    {
        $ultimaLeccion = $this->progreso()->max('completada_en');

        return $ultimaLeccion ? \Illuminate\Support\Carbon::parse($ultimaLeccion) : $this->matriculado_en;
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function progreso(): HasMany
    {
        return $this->hasMany(ProgresoLeccion::class);
    }

    // Regla efectiva: si hay override a nivel matrícula, gana; si no, se usa la del curso
    public function bloqueoSecuencialEfectivo(): bool
    {
        return $this->bloqueo_secuencial_override ?? $this->curso->bloqueo_secuencial;
    }
}

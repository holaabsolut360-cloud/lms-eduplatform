<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Orden extends Model
{
    protected $fillable = [
        'codigo', 'curso_id', 'estudiante_id', 'metodo_pago_id', 'monto', 'moneda',
        'estado', 'comprobante_url', 'motivo_rechazo', 'revisado_por', 'revisado_en',
    ];

    protected $casts = [
        'revisado_en' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Orden $orden) {
            $orden->codigo ??= 'ORD-' . now()->format('Y') . '-' . str_pad((string) (static::max('id') + 1), 6, '0', STR_PAD_LEFT);
        });
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class);
    }

    public function revisadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    /**
     * El admin aprueba el comprobante: la orden pasa a "aprobada" y se
     * crea (o reactiva) automáticamente la matrícula del alumno al curso.
     */
    public function aprobar(User $admin): Matricula
    {
        $this->update([
            'estado' => 'aprobada',
            'revisado_por' => $admin->id,
            'revisado_en' => now(),
        ]);

        return Matricula::updateOrCreate(
            ['curso_id' => $this->curso_id, 'estudiante_id' => $this->estudiante_id],
            [
                'estado' => 'activa',
                'monto_pagado' => $this->monto,
                'moneda' => $this->moneda,
                'matriculado_en' => now(),
            ]
        );
    }

    public function rechazar(User $admin, string $motivo): void
    {
        $this->update([
            'estado' => 'rechazada',
            'motivo_rechazo' => $motivo,
            'revisado_por' => $admin->id,
            'revisado_en' => now(),
        ]);
    }
}

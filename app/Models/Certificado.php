<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificado extends Model
{
    protected $fillable = ['matricula_id', 'codigo_verificacion', 'pdf_url', 'emitido_en'];

    protected $casts = [
        'emitido_en' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Certificado $cert) {
            $cert->codigo_verificacion ??= 'CERT-' . strtoupper(Str::random(8));
        });
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    /**
     * Emite el certificado para una matrícula si el curso lo permite y el
     * alumno cumple los requisitos (100% de avance, y si el curso tiene
     * examen final, nota >= nota_minima_aprobacion).
     */
    public static function emitirParaMatricula(Matricula $matricula): ?self
    {
        $curso = $matricula->curso;

        if (! $curso->certificado_habilitado) return null;
        if ($matricula->porcentaje_avance < 100) return null;

        return static::firstOrCreate(
            ['matricula_id' => $matricula->id],
            ['emitido_en' => now()]
        );
    }
}

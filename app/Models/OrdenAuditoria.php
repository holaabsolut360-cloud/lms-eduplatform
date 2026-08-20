<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenAuditoria extends Model
{
    protected $table = 'ordenes_auditoria';

    protected $fillable = ['orden_id', 'admin_id', 'accion', 'motivo', 'creado_en'];

    protected $casts = [
        'creado_en' => 'datetime',
    ];

    public function orden(): BelongsTo
    {
        return $this->belongsTo(Orden::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function etiqueta(): string
    {
        return match ($this->accion) {
            'creada' => 'Comprobante subido',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
            default => ucfirst($this->accion),
        };
    }
}

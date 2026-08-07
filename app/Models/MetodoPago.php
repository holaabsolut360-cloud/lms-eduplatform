<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    protected $table = 'metodos_pago';

    protected $fillable = [
        'tipo', 'moneda', 'titular', 'numero_celular', 'qr_imagen_url',
        'banco', 'numero_cuenta', 'numero_cci', 'activo', 'orden',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function scopeActivos($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }

    public function scopeParaMoneda($query, string $moneda)
    {
        return $query->where('moneda', $moneda);
    }
}

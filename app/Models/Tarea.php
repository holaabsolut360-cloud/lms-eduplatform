<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tarea extends Model
{
    protected $fillable = [
        'curso_id', 'modulo_id', 'titulo', 'instrucciones', 'fecha_limite', 'puntaje_maximo',
    ];

    protected $casts = [
        'fecha_limite' => 'datetime',
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }

    public function entregas(): HasMany
    {
        return $this->hasMany(EntregaTarea::class);
    }

    public function estaVencida(): bool
    {
        return $this->fecha_limite && now()->greaterThan($this->fecha_limite);
    }
}

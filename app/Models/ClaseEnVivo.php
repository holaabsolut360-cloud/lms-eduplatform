<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaseEnVivo extends Model
{
    protected $table = 'clases_en_vivo';

    protected $fillable = [
        'curso_id', 'modulo_id', 'titulo', 'plataforma', 'link_reunion',
        'fecha_hora', 'duracion_minutos', 'notas',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }

    public function yaPaso(): bool
    {
        return $this->fecha_hora->addMinutes($this->duracion_minutos)->isPast();
    }

    public function estaEnVivoAhora(): bool
    {
        return now()->between($this->fecha_hora, $this->fecha_hora->copy()->addMinutes($this->duracion_minutos));
    }
}

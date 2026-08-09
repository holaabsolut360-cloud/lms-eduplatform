<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgresoLeccion extends Model
{
    protected $table = 'progreso_lecciones';

    protected $fillable = ['matricula_id', 'leccion_id', 'completada_en'];

    protected $casts = [
        'completada_en' => 'datetime',
    ];

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    public function leccion(): BelongsTo
    {
        return $this->belongsTo(Leccion::class);
    }
}

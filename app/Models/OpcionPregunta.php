<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpcionPregunta extends Model
{
    protected $table = 'opciones_pregunta';

    protected $fillable = ['pregunta_id', 'texto', 'es_correcta', 'orden'];

    protected $casts = [
        'es_correcta' => 'boolean',
    ];

    public function pregunta(): BelongsTo
    {
        return $this->belongsTo(Pregunta::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modulo extends Model
{
    protected $fillable = ['curso_id', 'titulo', 'orden'];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function lecciones(): HasMany
    {
        return $this->hasMany(Leccion::class)->orderBy('orden');
    }
}

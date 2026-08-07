<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    protected $fillable = ['nombre', 'slug', 'icono'];

    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class);
    }
}

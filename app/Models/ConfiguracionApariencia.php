<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionApariencia extends Model
{
    protected $table = 'configuracion_apariencia';

    protected $fillable = [
        'hero_titulo', 'hero_subtitulo', 'hero_texto_boton', 'hero_imagen_fondo',
        'color_marca', 'cifra_estudiantes', 'cifra_empresas', 'cifra_rating',
        'nosotros_texto', 'cursos_destacados_ids',
    ];

    protected $casts = [
        'cursos_destacados_ids' => 'array',
    ];

    // Siempre trabajamos con una sola fila de configuración
    public static function actual(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}

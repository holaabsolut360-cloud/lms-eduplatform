<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leccion extends Model
{
    protected $fillable = [
        'modulo_id', 'titulo', 'orden', 'tipo', 'video_youtube_url',
        'contenido_html', 'archivo_url', 'duracion_minutos', 'es_preview_gratis',
    ];

    protected $casts = [
        'es_preview_gratis' => 'boolean',
    ];

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }

    // Extrae el ID de YouTube para armar el embed nocookie
    public function youtubeId(): ?string
    {
        if (! $this->video_youtube_url) return null;
        preg_match('/(?:youtu\.be\/|v=)([a-zA-Z0-9_-]{11})/', $this->video_youtube_url, $m);
        return $m[1] ?? null;
    }
}

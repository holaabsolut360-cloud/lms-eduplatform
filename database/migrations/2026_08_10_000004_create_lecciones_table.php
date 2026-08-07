<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('modulos')->cascadeOnDelete();

            $table->string('titulo');
            $table->unsignedInteger('orden')->default(0);
            $table->enum('tipo', ['video', 'texto', 'pdf', 'archivo'])->default('video');

            $table->string('video_youtube_url')->nullable(); // solo si tipo = video
            $table->longText('contenido_html')->nullable();  // solo si tipo = texto
            $table->string('archivo_url')->nullable();       // solo si tipo = pdf/archivo

            $table->unsignedInteger('duracion_minutos')->default(0);
            $table->boolean('es_preview_gratis')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecciones');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examen_id')->constrained('examenes')->cascadeOnDelete();

            $table->text('enunciado');
            $table->enum('tipo', ['opcion_multiple', 'verdadero_falso', 'respuesta_corta'])->default('opcion_multiple');
            $table->unsignedInteger('puntaje')->default(1);
            $table->unsignedInteger('orden')->default(0);

            // solo aplica a tipo = respuesta_corta, para calificación automática por coincidencia
            $table->string('respuesta_esperada')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preguntas');
    }
};

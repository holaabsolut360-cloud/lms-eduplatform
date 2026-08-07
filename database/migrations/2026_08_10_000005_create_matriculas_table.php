<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('estudiante_id')->constrained('users')->cascadeOnDelete();

            $table->enum('estado', ['pendiente_pago', 'activa', 'completada', 'suspendida'])->default('pendiente_pago');
            $table->decimal('monto_pagado', 10, 2)->nullable();
            $table->string('moneda', 3)->nullable();

            // override por alumno de la regla de bloqueo secuencial del curso
            $table->boolean('bloqueo_secuencial_override')->nullable();

            $table->unsignedTinyInteger('porcentaje_avance')->default(0);
            $table->timestamp('completado_en')->nullable();
            $table->timestamp('matriculado_en')->nullable();

            $table->timestamps();
            $table->unique(['curso_id', 'estudiante_id']); // no duplicar matrícula
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};

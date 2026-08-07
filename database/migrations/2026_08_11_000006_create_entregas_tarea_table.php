<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entregas_tarea', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->constrained('tareas')->cascadeOnDelete();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();

            $table->string('archivo_url')->nullable();
            $table->text('comentario_alumno')->nullable();

            $table->enum('estado', ['entregada', 'calificada', 'rechazada'])->default('entregada');
            $table->decimal('nota', 5, 2)->nullable();
            $table->text('feedback_docente')->nullable();

            $table->timestamp('entregado_en')->nullable();
            $table->timestamp('calificado_en')->nullable();

            $table->timestamps();
            $table->unique(['tarea_id', 'matricula_id']); // una entrega por alumno por tarea
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entregas_tarea');
    }
};

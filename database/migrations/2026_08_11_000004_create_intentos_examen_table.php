<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intentos_examen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examen_id')->constrained('examenes')->cascadeOnDelete();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();

            $table->unsignedTinyInteger('numero_intento')->default(1);
            $table->json('respuestas'); // [{pregunta_id, opcion_id|texto}]
            $table->decimal('nota_obtenida', 5, 2)->nullable(); // sobre 100
            $table->boolean('aprobado')->default(false);

            $table->timestamp('iniciado_en')->nullable();
            $table->timestamp('finalizado_en')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intentos_examen');
    }
};

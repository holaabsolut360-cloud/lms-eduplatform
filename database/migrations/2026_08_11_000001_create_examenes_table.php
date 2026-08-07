<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('modulo_id')->nullable()->constrained('modulos')->nullOnDelete();

            $table->string('titulo');
            $table->text('instrucciones')->nullable();
            $table->unsignedInteger('tiempo_limite_min')->nullable(); // null = sin límite
            $table->unsignedTinyInteger('intentos_permitidos')->default(1);
            $table->unsignedTinyInteger('nota_minima_aprobacion')->default(70); // sobre 100

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examenes');
    }
};

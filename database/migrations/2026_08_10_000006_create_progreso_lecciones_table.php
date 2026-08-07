<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progreso_lecciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->foreignId('leccion_id')->constrained('lecciones')->cascadeOnDelete();
            $table->timestamp('completada_en')->nullable();
            $table->timestamps();
            $table->unique(['matricula_id', 'leccion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progreso_lecciones');
    }
};

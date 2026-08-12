<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clases_en_vivo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('modulo_id')->nullable()->constrained('modulos')->nullOnDelete();

            $table->string('titulo');
            $table->enum('plataforma', ['zoom', 'google_meet', 'otro'])->default('zoom');
            $table->string('link_reunion');
            $table->timestamp('fecha_hora');
            $table->unsignedInteger('duracion_minutos')->default(60);
            $table->text('notas')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clases_en_vivo');
    }
};

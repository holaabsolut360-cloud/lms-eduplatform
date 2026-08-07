<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();

            $table->string('titulo');
            $table->string('slug')->unique();
            $table->string('descripcion_corta', 255)->nullable();
            $table->longText('descripcion_larga')->nullable();
            $table->string('imagen_portada')->nullable();
            $table->string('video_promocional_url')->nullable(); // YouTube

            $table->decimal('precio', 10, 2)->default(0);
            $table->decimal('precio_oferta', 10, 2)->nullable();
            $table->enum('moneda', ['PEN', 'USD'])->default('PEN');

            $table->enum('nivel', ['basico', 'intermedio', 'avanzado'])->default('basico');
            $table->enum('estado', ['borrador', 'publicado', 'archivado'])->default('borrador');

            $table->boolean('bloqueo_secuencial')->default(true); // regla por defecto del curso
            $table->boolean('certificado_habilitado')->default(true);
            $table->unsignedTinyInteger('nota_minima_aprobacion')->default(70); // sobre 100
            $table->boolean('destacado')->default(false); // se muestra en home
            $table->unsignedInteger('orden_destacado')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};

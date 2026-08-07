<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Tabla de una sola fila (single row settings), ideal para el panel "Apariencia"
    public function up(): void
    {
        Schema::create('configuracion_apariencia', function (Blueprint $table) {
            $table->id();
            $table->string('hero_titulo')->default('Los mejores cursos, a tu propio ritmo');
            $table->string('hero_subtitulo')->nullable();
            $table->string('hero_texto_boton')->default('Ver cursos');
            $table->string('hero_imagen_fondo')->nullable();

            $table->string('color_marca', 7)->default('#6c5ce7'); // hex

            $table->string('cifra_estudiantes')->default('1500+');
            $table->string('cifra_empresas')->default('30+');
            $table->string('cifra_rating')->default('4.8');

            $table->text('nosotros_texto')->nullable();
            $table->json('cursos_destacados_ids')->nullable(); // orden manual [3,1,7]

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_apariencia');
    }
};

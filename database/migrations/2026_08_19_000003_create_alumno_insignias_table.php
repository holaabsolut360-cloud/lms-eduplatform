<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumno_insignias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('curso_id')->nullable()->constrained('cursos')->nullOnDelete();
            $table->string('tipo'); // primer_modulo, primer_examen_aprobado, curso_completado, racha_7_dias
            $table->timestamp('obtenida_en');
            $table->timestamps();

            $table->unique(['estudiante_id', 'tipo', 'curso_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('racha_actual')->default(0)->after('activo');
            $table->unsignedInteger('racha_maxima')->default(0)->after('racha_actual');
            $table->date('ultima_actividad_en')->nullable()->after('racha_maxima');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['racha_actual', 'racha_maxima', 'ultima_actividad_en']);
        });

        Schema::dropIfExists('alumno_insignias');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracion_apariencia', function (Blueprint $table) {
            $table->string('login_video_url')->nullable()->after('hero_imagen_fondo');
            $table->string('contacto_telefono')->nullable()->after('nosotros_texto');
            $table->string('contacto_whatsapp')->nullable()->after('contacto_telefono'); // solo números, ej: 51927788737
            $table->string('contacto_email')->nullable()->after('contacto_whatsapp');
        });
    }

    public function down(): void
    {
        Schema::table('configuracion_apariencia', function (Blueprint $table) {
            $table->dropColumn(['login_video_url', 'contacto_telefono', 'contacto_whatsapp', 'contacto_email']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clases_en_vivo', function (Blueprint $table) {
            $table->boolean('recordatorio_enviado')->default(false)->after('notas');
        });
    }

    public function down(): void
    {
        Schema::table('clases_en_vivo', function (Blueprint $table) {
            $table->dropColumn('recordatorio_enviado');
        });
    }
};

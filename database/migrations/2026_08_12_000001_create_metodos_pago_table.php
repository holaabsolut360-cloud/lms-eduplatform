<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['yape', 'plin', 'cuenta_bancaria']);
            $table->string('moneda', 3); // PEN | USD
            $table->string('titular');

            // Yape / Plin
            $table->string('numero_celular')->nullable();
            $table->string('qr_imagen_url')->nullable();

            // Cuenta bancaria
            $table->string('banco')->nullable();
            $table->string('numero_cuenta')->nullable();
            $table->string('numero_cci')->nullable();

            $table->boolean('activo')->default(true);
            $table->unsignedInteger('orden')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metodos_pago');
    }
};

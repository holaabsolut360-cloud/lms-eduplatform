<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Rol dentro de la plataforma (single-tenant, no hay multi-academia)
            $table->enum('rol', ['estudiante', 'instructor', 'administrador'])->default('estudiante');

            $table->string('avatar_url')->nullable();
            $table->string('telefono')->nullable();
            $table->boolean('activo')->default(true);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

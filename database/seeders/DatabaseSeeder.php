<?php

namespace Database\Seeders;

use App\Models\MetodoPago;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Crea el primer usuario administrador y algunos métodos de pago de
     * ejemplo, para poder entrar a /admin apenas se despliegue el proyecto.
     *
     * IMPORTANTE: cambia el correo y la contraseña antes de correr esto
     * en producción, o cambia la contraseña del admin inmediatamente
     * después del primer ingreso.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@eduplatform.test'],
            [
                'nombre' => 'Administrador',
                'password' => Hash::make('cambiar-esta-clave'),
                'rol' => 'administrador',
                'activo' => true,
            ]
        );

        MetodoPago::firstOrCreate(
            ['tipo' => 'yape', 'moneda' => 'PEN'],
            ['titular' => 'Nombre del titular', 'numero_celular' => '999999999', 'activo' => true, 'orden' => 1]
        );

        MetodoPago::firstOrCreate(
            ['tipo' => 'plin', 'moneda' => 'PEN'],
            ['titular' => 'Nombre del titular', 'numero_celular' => '999999999', 'activo' => true, 'orden' => 2]
        );

        MetodoPago::firstOrCreate(
            ['tipo' => 'cuenta_bancaria', 'moneda' => 'USD'],
            ['titular' => 'Nombre del titular', 'banco' => 'BCP', 'numero_cuenta' => '000-000000-0-00', 'numero_cci' => '', 'activo' => true, 'orden' => 3]
        );
    }
}

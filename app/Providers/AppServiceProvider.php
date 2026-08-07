<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Acceso al panel /admin: instructores y administradores.
        // (el instructor solo ve sus propios cursos; el admin ve todo —
        // esa distinción se resuelve dentro de cada controlador/policy).
        Gate::define('administrar-plataforma', fn (User $user) => $user->esInstructor());

        // Solo el rol administrador puede aprobar/rechazar pagos.
        Gate::define('gestionar-pagos', fn (User $user) => $user->esAdministrador());
    }
}

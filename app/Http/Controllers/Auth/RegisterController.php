<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function mostrar(): View
    {
        return view('auth.registro');
    }

    public function registrar(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'telefono' => ['nullable', 'string', 'max:20'],
        ]);

        // El registro público SIEMPRE crea cuentas de estudiante.
        // Las cuentas de instructor/administrador las crea el admin desde su panel.
        $usuario = User::create([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'telefono' => $data['telefono'] ?? null,
            'rol' => 'estudiante',
        ]);

        Auth::login($usuario);

        \Illuminate\Support\Facades\Mail::to($usuario)->send(new \App\Mail\BienvenidaMail($usuario));

        return redirect()->route('publico.home')->with('success', '¡Bienvenido! Tu cuenta fue creada.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PerfilController extends Controller
{
    public function editar(): View
    {
        return view('admin.perfil.editar');
    }

    public function actualizar(Request $request): RedirectResponse
    {
        $usuario = auth()->user();

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $usuario->id],
            'telefono' => ['nullable', 'string', 'max:20'],
        ]);

        $usuario->update($data);

        return back()->with('success', 'Perfil actualizado.');
    }

    public function actualizarContrasena(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'contrasena_actual' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('success', 'Contraseña actualizada.');
    }
}

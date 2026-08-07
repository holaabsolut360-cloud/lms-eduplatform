<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function index(): View
    {
        $usuarios = User::where('rol', '!=', 'estudiante')->latest()->paginate(20);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Solo un administrador puede crear otros instructores/administradores.
        abort_unless(auth()->user()->esAdministrador(), 403);

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'rol' => ['required', 'in:instructor,administrador'],
        ]);

        User::create([
            ...$data,
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('success', 'Usuario creado correctamente.');
    }

    public function desactivar(User $usuario): RedirectResponse
    {
        abort_unless(auth()->user()->esAdministrador(), 403);
        abort_if($usuario->id === auth()->id(), 400, 'No puedes desactivar tu propia cuenta.');

        $usuario->update(['activo' => ! $usuario->activo]);

        return back()->with('success', $usuario->activo ? 'Usuario reactivado.' : 'Usuario desactivado.');
    }
}

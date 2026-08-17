<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Certificado;
use App\Models\EntregaTarea;
use App\Models\IntentoExamen;
use App\Models\Orden;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CuentaController extends Controller
{
    public function index(): View
    {
        $usuario = auth()->user();

        $matriculas = $usuario->matriculas()->where('estado', '!=', 'pendiente_pago')->with('curso')->get();
        $certificados = Certificado::whereHas('matricula', fn ($q) => $q->where('estudiante_id', $usuario->id))
            ->with('matricula.curso')->get();
        $ordenes = Orden::where('estudiante_id', $usuario->id)->with('curso')->latest()->get();

        $resultadosExamenes = IntentoExamen::whereHas('matricula', fn ($q) => $q->where('estudiante_id', $usuario->id))
            ->with('examen.curso')->latest()->get();
        $entregasCalificadas = EntregaTarea::whereHas('matricula', fn ($q) => $q->where('estudiante_id', $usuario->id))
            ->where('estado', 'calificada')->with('tarea.curso')->latest()->get();

        return view('estudiante.cuenta.index', compact(
            'matriculas', 'certificados', 'ordenes', 'resultadosExamenes', 'entregasCalificadas'
        ));
    }

    public function perfil(): View
    {
        return view('estudiante.cuenta.perfil');
    }

    public function actualizar(Request $request): RedirectResponse
    {
        $usuario = auth()->user();

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $usuario->id],
            'telefono' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar_url'] = $request->file('avatar')->store('avatares', 'public');
        }
        unset($data['avatar']);

        $usuario->update($data);

        return back()->with('success', 'Perfil actualizado.');
    }

    public function actualizarContrasena(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'contrasena_actual' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        auth()->user()->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Contraseña actualizada.');
    }
}

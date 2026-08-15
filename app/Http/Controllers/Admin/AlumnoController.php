<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificado;
use App\Models\NotaAlumno;
use App\Models\Orden;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlumnoController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(auth()->user()->esAdministrador(), 403, 'Solo un administrador puede ver el listado global de alumnos.');

        $alumnos = User::where('rol', 'estudiante')
            ->withCount(['matriculas' => fn ($q) => $q->where('estado', '!=', 'pendiente_pago')])
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($sub) => $sub
                ->where('nombre', 'like', '%' . $request->q . '%')
                ->orWhere('email', 'like', '%' . $request->q . '%')
            ))
            ->latest()
            ->paginate(20);

        return view('admin.alumnos.index', compact('alumnos'));
    }

    public function show(User $alumno): View
    {
        abort_unless(auth()->user()->esAdministrador(), 403);
        abort_unless($alumno->rol === 'estudiante', 404);

        $matriculas = $alumno->matriculas()->with('curso')->where('estado', '!=', 'pendiente_pago')->get();
        $ordenes = Orden::where('estudiante_id', $alumno->id)->with('curso', 'metodoPago')->latest()->get();
        $certificados = Certificado::whereHas('matricula', fn ($q) => $q->where('estudiante_id', $alumno->id))
            ->with('matricula.curso')->get();
        $notas = NotaAlumno::where('estudiante_id', $alumno->id)->with('autor')->latest()->get();

        return view('admin.alumnos.show', compact('alumno', 'matriculas', 'ordenes', 'certificados', 'notas'));
    }

    public function storeNota(User $alumno, Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->esAdministrador(), 403);

        $data = $request->validate([
            'contenido' => ['required', 'string', 'max:1000'],
        ]);

        NotaAlumno::create([
            'estudiante_id' => $alumno->id,
            'autor_id' => auth()->id(),
            'contenido' => $data['contenido'],
        ]);

        return back()->with('success', 'Nota agregada.');
    }

    public function destroyNota(User $alumno, NotaAlumno $nota): RedirectResponse
    {
        abort_unless(auth()->user()->esAdministrador(), 403);
        abort_unless($nota->estudiante_id === $alumno->id, 404);

        $nota->delete();

        return back()->with('success', 'Nota eliminada.');
    }
}

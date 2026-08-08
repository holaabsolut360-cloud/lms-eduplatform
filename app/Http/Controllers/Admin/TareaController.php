<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\EntregaTarea;
use App\Models\Tarea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TareaController extends Controller
{
    public function store(Curso $curso, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'modulo_id' => ['nullable', 'exists:modulos,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'instrucciones' => ['nullable', 'string'],
            'fecha_limite' => ['nullable', 'date'],
            'puntaje_maximo' => ['required', 'integer', 'min:1'],
        ]);

        $curso->tareas()->create($data);

        return back()->with('success', 'Tarea creada.');
    }

    public function entregas(Curso $curso, Tarea $tarea): View
    {
        abort_unless($tarea->curso_id === $curso->id, 404);

        $entregas = $tarea->entregas()->with('matricula.estudiante')->latest('entregado_en')->get();

        return view('admin.tareas.entregas', compact('curso', 'tarea', 'entregas'));
    }

    public function calificar(Curso $curso, Tarea $tarea, EntregaTarea $entrega, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nota' => ['required', 'numeric', 'min:0', 'max:' . $tarea->puntaje_maximo],
            'feedback_docente' => ['nullable', 'string', 'max:1000'],
        ]);

        $entrega->calificar($data['nota'], $data['feedback_docente'] ?? null);

        return back()->with('success', 'Entrega calificada.');
    }

    public function destroy(Curso $curso, Tarea $tarea): RedirectResponse
    {
        $tarea->delete();

        return back()->with('success', 'Tarea eliminada.');
    }
}

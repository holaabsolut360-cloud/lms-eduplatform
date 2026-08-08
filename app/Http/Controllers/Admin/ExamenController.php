<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Examen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamenController extends Controller
{
    public function store(Curso $curso, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'modulo_id' => ['nullable', 'exists:modulos,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'instrucciones' => ['nullable', 'string'],
            'tiempo_limite_min' => ['nullable', 'integer', 'min:1'],
            'intentos_permitidos' => ['required', 'integer', 'min:1', 'max:10'],
            'nota_minima_aprobacion' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $examen = $curso->examenes()->create($data);

        return redirect()
            ->route('admin.examenes.edit', [$curso, $examen])
            ->with('success', 'Examen creado. Ahora agrega las preguntas.');
    }

    public function edit(Curso $curso, Examen $examen): View
    {
        $examen->load('preguntas.opciones');

        return view('admin.examenes.edit', compact('curso', 'examen'));
    }

    public function destroy(Curso $curso, Examen $examen): RedirectResponse
    {
        $examen->delete();

        return back()->with('success', 'Examen eliminado.');
    }
}

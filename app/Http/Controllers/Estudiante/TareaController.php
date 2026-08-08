<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\EntregaTarea;
use App\Models\Matricula;
use App\Models\Tarea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TareaController extends Controller
{
    public function mostrar(Curso $curso, Tarea $tarea): View
    {
        $matricula = $this->matriculaDelUsuario($curso);

        abort_unless($tarea->curso_id === $curso->id, 404);

        $entrega = EntregaTarea::where('tarea_id', $tarea->id)
            ->where('matricula_id', $matricula->id)
            ->first();

        return view('estudiante.tarea', compact('curso', 'tarea', 'entrega'));
    }

    public function entregar(Curso $curso, Tarea $tarea, Request $request): RedirectResponse
    {
        $matricula = $this->matriculaDelUsuario($curso);

        abort_if($tarea->estaVencida(), 403, 'La fecha límite de esta tarea ya pasó.');

        $data = $request->validate([
            'comentario_alumno' => ['nullable', 'string', 'max:2000'],
            'archivo' => ['required_without:comentario_alumno', 'file', 'max:10240'],
        ]);

        $rutaArchivo = $request->hasFile('archivo')
            ? $request->file('archivo')->store('entregas-tarea', 'public')
            : null;

        EntregaTarea::updateOrCreate(
            ['tarea_id' => $tarea->id, 'matricula_id' => $matricula->id],
            [
                'archivo_url' => $rutaArchivo,
                'comentario_alumno' => $data['comentario_alumno'] ?? null,
                'estado' => 'entregada',
                'entregado_en' => now(),
                // si se re-entrega después de una calificación, se limpia para que el docente vuelva a revisar
                'nota' => null,
                'feedback_docente' => null,
                'calificado_en' => null,
            ]
        );

        return back()->with('success', 'Tarea entregada correctamente.');
    }

    private function matriculaDelUsuario(Curso $curso): Matricula
    {
        return Matricula::where('curso_id', $curso->id)
            ->where('estudiante_id', auth()->id())
            ->where('estado', '!=', 'suspendida')
            ->firstOrFail();
    }
}

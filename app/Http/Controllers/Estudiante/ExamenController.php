<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\IntentoExamen;
use App\Models\Matricula;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamenController extends Controller
{
    public function mostrar(Curso $curso, Examen $examen): View
    {
        $matricula = $this->matriculaDelUsuario($curso);

        abort_unless($examen->curso_id === $curso->id, 404);

        $intentosRestantes = $examen->intentosRestantes($matricula->id);
        $ultimoIntento = $examen->intentos()
            ->where('matricula_id', $matricula->id)
            ->latest()
            ->first();

        return view('estudiante.examen', [
            'curso' => $curso,
            'examen' => $examen->load('preguntas.opciones'),
            'intentosRestantes' => $intentosRestantes,
            'ultimoIntento' => $ultimoIntento,
        ]);
    }

    public function enviar(Curso $curso, Examen $examen, Request $request): RedirectResponse
    {
        $matricula = $this->matriculaDelUsuario($curso);

        abort_if($examen->intentosRestantes($matricula->id) <= 0, 403, 'Ya no tienes intentos disponibles para este examen.');

        $data = $request->validate([
            'respuestas' => ['required', 'array'],
            'respuestas.*.pregunta_id' => ['required', 'exists:preguntas,id'],
            'respuestas.*.respuesta' => ['nullable'],
        ]);

        $numeroIntento = $examen->intentos()->where('matricula_id', $matricula->id)->count() + 1;

        $intento = IntentoExamen::create([
            'examen_id' => $examen->id,
            'matricula_id' => $matricula->id,
            'numero_intento' => $numeroIntento,
            'respuestas' => $data['respuestas'],
            'iniciado_en' => now(),
        ]);

        $intento->calificar();

        return redirect()
            ->route('estudiante.examen.resultado', [$curso, $examen, $intento])
            ->with('success', $intento->aprobado ? '¡Aprobaste el examen!' : 'No alcanzaste la nota mínima. Revisa tu resultado.');
    }

    public function resultado(Curso $curso, Examen $examen, IntentoExamen $intento): View
    {
        abort_unless($intento->matricula->estudiante_id === auth()->id(), 403);

        return view('estudiante.examen-resultado', compact('curso', 'examen', 'intento'));
    }

    private function matriculaDelUsuario(Curso $curso): Matricula
    {
        return Matricula::where('curso_id', $curso->id)
            ->where('estudiante_id', auth()->id())
            ->where('estado', '!=', 'suspendida')
            ->firstOrFail();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\Pregunta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PreguntaController extends Controller
{
    public function store(Examen $examen, Request $request): RedirectResponse
    {
        abort_unless($examen->curso->perteneceA(auth()->user()), 403);

        $data = $request->validate([
            'enunciado' => ['required', 'string'],
            'tipo' => ['required', 'in:opcion_multiple,verdadero_falso,respuesta_corta'],
            'puntaje' => ['required', 'integer', 'min:1'],
            'respuesta_esperada' => ['nullable', 'required_if:tipo,respuesta_corta', 'string'],
            'opciones' => ['required_unless:tipo,respuesta_corta', 'array'],
            'opciones.*.texto' => ['required_with:opciones', 'string'],
            'opciones.*.es_correcta' => ['nullable', 'boolean'],
        ]);

        $pregunta = $examen->preguntas()->create([
            'enunciado' => $data['enunciado'],
            'tipo' => $data['tipo'],
            'puntaje' => $data['puntaje'],
            'orden' => $examen->preguntas()->max('orden') + 1,
            'respuesta_esperada' => $data['respuesta_esperada'] ?? null,
        ]);

        if ($data['tipo'] !== 'respuesta_corta') {
            foreach ($data['opciones'] as $i => $opcion) {
                $pregunta->opciones()->create([
                    'texto' => $opcion['texto'],
                    'es_correcta' => (bool) ($opcion['es_correcta'] ?? false),
                    'orden' => $i,
                ]);
            }
        }

        return back()->with('success', 'Pregunta agregada.');
    }

    public function destroy(Examen $examen, Pregunta $pregunta): RedirectResponse
    {
        abort_unless($examen->curso->perteneceA(auth()->user()), 403);

        $pregunta->delete();

        return back()->with('success', 'Pregunta eliminada.');
    }
}

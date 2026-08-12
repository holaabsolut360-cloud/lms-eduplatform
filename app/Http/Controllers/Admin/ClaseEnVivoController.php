<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClaseEnVivo;
use App\Models\Curso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClaseEnVivoController extends Controller
{
    public function store(Curso $curso, Request $request): RedirectResponse
    {
        abort_unless($curso->perteneceA(auth()->user()), 403);

        $data = $this->validarDatos($request);

        $curso->clasesEnVivo()->create($data);

        return back()->with('success', 'Clase en vivo agendada.');
    }

    public function update(Curso $curso, ClaseEnVivo $clase, Request $request): RedirectResponse
    {
        abort_unless($curso->perteneceA(auth()->user()), 403);

        $clase->update($this->validarDatos($request));

        return back()->with('success', 'Clase en vivo actualizada.');
    }

    public function destroy(Curso $curso, ClaseEnVivo $clase): RedirectResponse
    {
        abort_unless($curso->perteneceA(auth()->user()), 403);

        $clase->delete();

        return back()->with('success', 'Clase en vivo eliminada.');
    }

    private function validarDatos(Request $request): array
    {
        return $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'plataforma' => ['required', 'in:zoom,google_meet,otro'],
            'link_reunion' => ['required', 'url', 'max:500'],
            'fecha_hora' => ['required', 'date'],
            'duracion_minutos' => ['required', 'integer', 'min:15', 'max:600'],
            'notas' => ['nullable', 'string', 'max:500'],
        ]);
    }
}

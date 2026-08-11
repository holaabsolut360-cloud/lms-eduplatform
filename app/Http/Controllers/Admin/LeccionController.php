<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leccion;
use App\Models\Modulo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LeccionController extends Controller
{
    public function store(Modulo $modulo, Request $request): RedirectResponse
    {
        abort_unless($modulo->curso->perteneceA(auth()->user()), 403);

        $data = $this->validarDatos($request);
        $data['orden'] = $modulo->lecciones()->max('orden') + 1;

        $modulo->lecciones()->create($data);

        return back()->with('success', 'Lección agregada.');
    }

    public function update(Modulo $modulo, Leccion $leccion, Request $request): RedirectResponse
    {
        abort_unless($modulo->curso->perteneceA(auth()->user()), 403);

        $leccion->update($this->validarDatos($request));

        return back()->with('success', 'Lección actualizada.');
    }

    public function destroy(Modulo $modulo, Leccion $leccion): RedirectResponse
    {
        abort_unless($modulo->curso->perteneceA(auth()->user()), 403);

        $leccion->delete();

        return back()->with('success', 'Lección eliminada.');
    }

    private function validarDatos(Request $request): array
    {
        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'in:video,texto,pdf,archivo'],
            'video_youtube_url' => ['nullable', 'required_if:tipo,video', 'url'],
            'contenido_html' => ['nullable', 'required_if:tipo,texto', 'string'],
            'duracion_minutos' => ['nullable', 'integer', 'min:0'],
            'es_preview_gratis' => ['boolean'],
        ]);

        // archivo_url se maneja aparte si el tipo es pdf/archivo (subida de archivo real)
        if ($request->hasFile('archivo') && in_array($data['tipo'], ['pdf', 'archivo'])) {
            $data['archivo_url'] = $request->file('archivo')->store('lecciones', 'public');
        }

        return $data;
    }
}

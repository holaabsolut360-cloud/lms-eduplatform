<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    public function store(Curso $curso, Request $request): RedirectResponse
    {
        $data = $request->validate(['titulo' => ['required', 'string', 'max:255']]);

        $curso->modulos()->create([
            'titulo' => $data['titulo'],
            'orden' => $curso->modulos()->max('orden') + 1,
        ]);

        return back()->with('success', 'Módulo agregado.');
    }

    public function update(Curso $curso, Modulo $modulo, Request $request): RedirectResponse
    {
        $data = $request->validate(['titulo' => ['required', 'string', 'max:255']]);
        $modulo->update($data);

        return back()->with('success', 'Módulo actualizado.');
    }

    public function reordenar(Curso $curso, Request $request): RedirectResponse
    {
        // $request->orden = [modulo_id => nuevo_orden, ...]
        foreach ($request->validate(['orden' => ['required', 'array']])['orden'] as $moduloId => $orden) {
            Modulo::where('id', $moduloId)->where('curso_id', $curso->id)->update(['orden' => $orden]);
        }

        return back()->with('success', 'Orden actualizado.');
    }

    public function destroy(Curso $curso, Modulo $modulo): RedirectResponse
    {
        $modulo->delete();

        return back()->with('success', 'Módulo eliminado.');
    }
}

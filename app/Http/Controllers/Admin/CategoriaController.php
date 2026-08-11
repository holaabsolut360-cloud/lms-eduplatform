<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function index(): View
    {
        $categorias = Categoria::withCount('cursos')->orderBy('nombre')->get();

        return view('admin.categorias.index', compact('categorias'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'icono' => ['nullable', 'string', 'max:50'],
        ]);

        $data['slug'] = Str::slug($data['nombre']) . '-' . Str::random(4);

        Categoria::create($data);

        return back()->with('success', 'Categoría creada.');
    }

    public function update(Categoria $categoria, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'icono' => ['nullable', 'string', 'max:50'],
        ]);

        $categoria->update($data);

        return back()->with('success', 'Categoría actualizada.');
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        if ($categoria->cursos()->exists()) {
            return back()->with('error', 'No puedes eliminar una categoría que tiene cursos asignados. Reasigna esos cursos primero.');
        }

        $categoria->delete();

        return back()->with('success', 'Categoría eliminada.');
    }
}

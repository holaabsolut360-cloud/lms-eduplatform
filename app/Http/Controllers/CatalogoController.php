<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\ConfiguracionApariencia;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogoController extends Controller
{
    public function home(): View
    {
        $apariencia = ConfiguracionApariencia::actual();

        $destacados = Curso::query()
            ->where('estado', 'publicado')
            ->where('destacado', true)
            ->orderBy('orden_destacado')
            ->limit(6)
            ->get();

        $categorias = Categoria::withCount(['cursos' => fn ($q) => $q->where('estado', 'publicado')])
            ->having('cursos_count', '>', 0)
            ->get();

        return view('publico.home', compact('apariencia', 'destacados', 'categorias'));
    }

    public function buscar(Request $request): View
    {
        $cursos = Curso::query()
            ->where('estado', 'publicado')
            ->when($request->filled('q'), fn ($q) => $q->where('titulo', 'like', '%' . $request->q . '%'))
            ->when($request->filled('categoria'), fn ($q) => $q->where('categoria_id', $request->categoria))
            ->with('categoria', 'instructor')
            ->paginate(12);

        return view('publico.catalogo', compact('cursos'));
    }

    public function detalle(Curso $curso): View
    {
        abort_unless($curso->estado === 'publicado', 404);

        $curso->load(['modulos.lecciones', 'categoria', 'instructor']);

        $yaMatriculado = auth()->check()
            ? $curso->matriculas()->where('estudiante_id', auth()->id())->exists()
            : false;

        return view('publico.curso-detalle', compact('curso', 'yaMatriculado'));
    }
}

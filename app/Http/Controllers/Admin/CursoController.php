<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CursoController extends Controller
{
    public function index(): View
    {
        $cursos = Curso::withCount('matriculas')
            ->with('categoria')
            ->when(!auth()->user()->esAdministrador(), fn ($q) => $q->where('instructor_id', auth()->id()))
            ->latest()
            ->paginate(15);

        return view('admin.cursos.index', compact('cursos'));
    }

    public function create(): View
    {
        $categorias = \App\Models\Categoria::orderBy('nombre')->get();

        return view('admin.cursos.create', compact('categorias'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validarDatos($request);
        $data['slug'] = Str::slug($data['titulo']) . '-' . Str::random(4);
        $data['instructor_id'] = auth()->id();

        $curso = Curso::create($data);

        return redirect()
            ->route('admin.cursos.edit', $curso)
            ->with('success', 'Curso creado. Ahora agrega los módulos y lecciones.');
    }

    public function edit(Curso $curso): View
    {
        $this->autorizar($curso);

        $curso->load('modulos.lecciones');
        $categorias = \App\Models\Categoria::orderBy('nombre')->get();

        return view('admin.cursos.edit', compact('curso', 'categorias'));
    }

    public function update(Request $request, Curso $curso): RedirectResponse
    {
        $this->autorizar($curso);

        $curso->update($this->validarDatos($request));

        return back()->with('success', 'Curso actualizado.');
    }

    public function publicar(Curso $curso): RedirectResponse
    {
        $this->autorizar($curso);

        abort_if($curso->modulos()->doesntExist(), 400, 'Agrega al menos un módulo antes de publicar.');

        $curso->update(['estado' => 'publicado']);

        return back()->with('success', 'Curso publicado. Ya es visible en el catálogo.');
    }

    public function destroy(Curso $curso): RedirectResponse
    {
        $this->autorizar($curso);

        $curso->delete();

        return redirect()->route('admin.cursos.index')->with('success', 'Curso eliminado.');
    }

    public function alumnos(Curso $curso): View
    {
        $this->autorizar($curso);

        $matriculas = $curso->matriculas()
            ->where('estado', '!=', 'pendiente_pago')
            ->with('estudiante')
            ->orderByDesc('porcentaje_avance')
            ->get();

        return view('admin.cursos.alumnos', compact('curso', 'matriculas'));
    }

    // Un instructor solo puede tocar sus propios cursos; un administrador puede tocar cualquiera.
    private function autorizar(Curso $curso): void
    {
        abort_unless($curso->perteneceA(auth()->user()), 403, 'Este curso pertenece a otro instructor.');
    }

    private function validarDatos(Request $request): array
    {
        return $request->validate([
            'categoria_id' => ['nullable', 'exists:categorias,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion_corta' => ['nullable', 'string', 'max:255'],
            'descripcion_larga' => ['nullable', 'string'],
            'video_promocional_url' => ['nullable', 'url'],
            'precio' => ['required', 'numeric', 'min:0'],
            'precio_oferta' => ['nullable', 'numeric', 'min:0', 'lt:precio'],
            'moneda' => ['required', 'in:PEN,USD'],
            'nivel' => ['required', 'in:basico,intermedio,avanzado'],
            'bloqueo_secuencial' => ['boolean'],
            'certificado_habilitado' => ['boolean'],
            'nota_minima_aprobacion' => ['integer', 'min:0', 'max:100'],
            'destacado' => ['boolean'],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionApariencia;
use App\Models\Curso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AparienciaController extends Controller
{
    public function editar(): View
    {
        $apariencia = ConfiguracionApariencia::actual();

        $cursosPublicados = Curso::where('estado', 'publicado')
            ->orderBy('titulo')
            ->get(['id', 'titulo', 'imagen_portada']);

        return view('admin.apariencia.editar', compact('apariencia', 'cursosPublicados'));
    }

    public function actualizar(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hero_titulo' => ['required', 'string', 'max:255'],
            'hero_subtitulo' => ['nullable', 'string', 'max:500'],
            'hero_texto_boton' => ['required', 'string', 'max:50'],
            'hero_imagen_fondo' => ['nullable', 'image', 'max:4096'],
            'login_video_url' => ['nullable', 'url'],
            'color_marca' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'cifra_estudiantes' => ['required', 'string', 'max:20'],
            'cifra_empresas' => ['required', 'string', 'max:20'],
            'cifra_rating' => ['required', 'string', 'max:10'],
            'nosotros_texto' => ['nullable', 'string', 'max:1000'],
            'contacto_telefono' => ['nullable', 'string', 'max:30'],
            'contacto_whatsapp' => ['nullable', 'string', 'max:20'],
            'contacto_email' => ['nullable', 'email', 'max:255'],
            'cursos_destacados_ids' => ['nullable', 'array', 'max:6'],
            'cursos_destacados_ids.*' => ['exists:cursos,id'],
        ]);

        $apariencia = ConfiguracionApariencia::actual();

        if ($request->hasFile('hero_imagen_fondo')) {
            $data['hero_imagen_fondo'] = $request->file('hero_imagen_fondo')->store('apariencia', 'public');
        } else {
            unset($data['hero_imagen_fondo']);
        }

        // Marca los cursos elegidos como destacados y desmarca el resto,
        // respetando el orden en que llegaron desde el panel (drag and drop)
        Curso::query()->update(['destacado' => false]);
        foreach (array_values($data['cursos_destacados_ids'] ?? []) as $orden => $cursoId) {
            Curso::where('id', $cursoId)->update(['destacado' => true, 'orden_destacado' => $orden]);
        }

        $apariencia->update($data);

        return back()->with('success', 'Apariencia actualizada. Los cambios ya son visibles en la web pública.');
    }
}

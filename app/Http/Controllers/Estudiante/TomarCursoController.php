<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Certificado;
use App\Models\Curso;
use App\Models\Leccion;
use App\Models\Matricula;
use App\Models\ProgresoLeccion;
use App\Services\GamificacionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TomarCursoController extends Controller
{
    /**
     * Vista principal "tomar curso": muestra la primera lección pendiente
     * (o la última vista) junto con el sidebar de contenido.
     */
    public function index(Curso $curso): View|RedirectResponse
    {
        $matricula = $this->matriculaDelUsuario($curso);

        $primeraLeccion = $curso->modulos->first()?->lecciones->first();
        $ultimaVista = ProgresoLeccion::where('matricula_id', $matricula->id)
            ->latest('completada_en')
            ->first();

        $leccion = $ultimaVista?->leccion ?? $primeraLeccion;

        if (! $leccion) {
            return back()->with('error', 'Este curso todavía no tiene contenido publicado.');
        }

        return redirect()->route('estudiante.curso.leccion', [$curso, $leccion]);
    }

    public function verLeccion(Curso $curso, Leccion $leccion): View
    {
        $matricula = $this->matriculaDelUsuario($curso);

        if (! $this->leccionDesbloqueada($matricula, $leccion)) {
            abort(403, 'Debes completar el contenido anterior antes de continuar.');
        }

        $curso->load('modulos.lecciones', 'clasesEnVivo', 'examenes', 'tareas');

        $leccionesCompletadasIds = ProgresoLeccion::where('matricula_id', $matricula->id)
            ->pluck('leccion_id')
            ->toArray();

        return view('estudiante.tomar-curso', [
            'curso' => $curso,
            'leccionActual' => $leccion,
            'matricula' => $matricula,
            'completadasIds' => $leccionesCompletadasIds,
            'bloqueoSecuencial' => $matricula->bloqueoSecuencialEfectivo(),
        ]);
    }

    public function marcarCompletada(Curso $curso, Leccion $leccion): RedirectResponse
    {
        $matricula = $this->matriculaDelUsuario($curso);

        ProgresoLeccion::updateOrCreate(
            ['matricula_id' => $matricula->id, 'leccion_id' => $leccion->id],
            ['completada_en' => now()]
        );

        $gamificacion = app(GamificacionService::class);
        $gamificacion->registrarActividad($matricula->estudiante);
        $gamificacion->revisarPrimerModulo($matricula, $leccion);

        $this->actualizarAvance($matricula);

        $siguiente = $this->siguienteLeccion($curso, $leccion);

        if ($siguiente) {
            return redirect()->route('estudiante.curso.leccion', [$curso, $siguiente]);
        }

        return redirect()->route('estudiante.curso.index', $curso)
            ->with('success', '¡Felicidades! Has completado todo el contenido del curso.');
    }

    /**
     * Regla central del bloqueo secuencial pedido por el cliente:
     * revisa primero si hay excepción a nivel de matrícula (alumno
     * específico); si no, aplica la regla general del curso.
     */
    private function leccionDesbloqueada(Matricula $matricula, Leccion $leccion): bool
    {
        if (! $matricula->bloqueoSecuencialEfectivo()) {
            return true; // sin bloqueo: acceso libre a todo el contenido
        }

        if ($leccion->es_preview_gratis) {
            return true;
        }

        $todasLasLecciones = $leccion->modulo->curso->modulos->flatMap->lecciones->values();
        $posicion = $todasLasLecciones->search(fn ($l) => $l->id === $leccion->id);

        if ($posicion === 0) {
            return true; // primera lección del curso, siempre accesible
        }

        $leccionAnterior = $todasLasLecciones->get($posicion - 1);

        return ProgresoLeccion::where('matricula_id', $matricula->id)
            ->where('leccion_id', $leccionAnterior->id)
            ->exists();
    }

    private function siguienteLeccion(Curso $curso, Leccion $actual): ?Leccion
    {
        $todas = $curso->modulos->flatMap->lecciones->values();
        $posicion = $todas->search(fn ($l) => $l->id === $actual->id);

        return $todas->get($posicion + 1);
    }

    private function actualizarAvance(Matricula $matricula): void
    {
        $curso = $matricula->curso()->with('modulos.lecciones')->first();
        $totalLecciones = $curso->totalLecciones();
        $completadas = ProgresoLeccion::where('matricula_id', $matricula->id)->count();

        $porcentaje = $totalLecciones > 0
            ? (int) round(($completadas / $totalLecciones) * 100)
            : 0;

        $matricula->update([
            'porcentaje_avance' => $porcentaje,
            'completado_en' => $porcentaje >= 100 ? now() : null,
            'estado' => $porcentaje >= 100 ? 'completada' : $matricula->estado,
        ]);

        if ($porcentaje >= 100) {
            Certificado::emitirParaMatricula($matricula);
            app(GamificacionService::class)->otorgarCursoCompletado($matricula);
        }
    }

    private function matriculaDelUsuario(Curso $curso): Matricula
    {
        return Matricula::where('curso_id', $curso->id)
            ->where('estudiante_id', auth()->id())
            ->where('estado', '!=', 'suspendida')
            ->firstOrFail();
    }
}

<?php

namespace App\Services;

use App\Models\AlumnoInsignia;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Support\Carbon;

class GamificacionService
{
    /**
     * Actualiza la racha de días consecutivos de estudio del alumno.
     * Se llama cada vez que completa una lección.
     */
    public function registrarActividad(User $estudiante): void
    {
        $hoy = Carbon::today();
        $ultima = $estudiante->ultima_actividad_en ? Carbon::parse($estudiante->ultima_actividad_en) : null;

        if ($ultima && $ultima->isSameDay($hoy)) {
            return; // ya contamos hoy, no duplicar
        }

        $rachaActual = ($ultima && $ultima->isSameDay($hoy->copy()->subDay()))
            ? $estudiante->racha_actual + 1
            : 1; // se rompió la racha o es la primera vez

        $estudiante->update([
            'racha_actual' => $rachaActual,
            'racha_maxima' => max($rachaActual, $estudiante->racha_maxima),
            'ultima_actividad_en' => $hoy,
        ]);

        if ($rachaActual >= 7) {
            $this->otorgar($estudiante, 'racha_7_dias');
        }
    }

    /** Otorga la insignia de "primer módulo completado" (una sola vez en la vida del alumno, sin importar el curso). */
    public function revisarPrimerModulo(Matricula $matricula, \App\Models\Leccion $leccionCompletada): void
    {
        if (AlumnoInsignia::where('estudiante_id', $matricula->estudiante_id)->where('tipo', 'primer_modulo')->exists()) {
            return;
        }

        $modulo = $leccionCompletada->modulo()->with('lecciones')->first();

        $completadasDelModulo = $matricula->progreso()
            ->whereIn('leccion_id', $modulo->lecciones->pluck('id'))
            ->count();

        if ($completadasDelModulo >= $modulo->lecciones->count()) {
            $this->otorgar($matricula->estudiante, 'primer_modulo', $matricula->curso_id);
        }
    }

    /** Otorga la insignia de "primer examen aprobado" (una sola vez en la vida del alumno). */
    public function otorgarPrimerExamenAprobado(User $estudiante): void
    {
        if (AlumnoInsignia::where('estudiante_id', $estudiante->id)->where('tipo', 'primer_examen_aprobado')->exists()) {
            return;
        }

        $this->otorgar($estudiante, 'primer_examen_aprobado');
    }

    public function otorgarCursoCompletado(Matricula $matricula): void
    {
        $this->otorgar($matricula->estudiante, 'curso_completado', $matricula->curso_id);
    }

    /** Crea la insignia solo si el alumno no la tiene ya (evita duplicados por el unique de la tabla). */
    private function otorgar(User $estudiante, string $tipo, ?int $cursoId = null): void
    {
        AlumnoInsignia::firstOrCreate(
            ['estudiante_id' => $estudiante->id, 'tipo' => $tipo, 'curso_id' => $cursoId],
            ['obtenida_en' => now()]
        );
    }
}

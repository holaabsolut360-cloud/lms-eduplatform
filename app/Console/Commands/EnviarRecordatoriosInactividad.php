<?php

namespace App\Console\Commands;

use App\Mail\RecordatorioInactividadMail;
use App\Models\Matricula;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class EnviarRecordatoriosInactividad extends Command
{
    protected $signature = 'alumnos:enviar-recordatorios-inactividad {--dias=7 : Días sin actividad para considerar inactivo}';

    protected $description = 'Envía un correo a los alumnos que no han avanzado en un curso en X días, sin repetir el aviso antes de otros X días';

    public function handle(): int
    {
        $dias = (int) $this->option('dias');
        $limite = now()->subDays($dias);

        $matriculas = Matricula::where('estado', '!=', 'pendiente_pago')
            ->whereNull('completado_en')
            ->with(['curso', 'estudiante', 'progreso'])
            ->get()
            ->filter(function (Matricula $matricula) use ($limite, $dias) {
                $ultimaActividad = $matricula->ultimaActividad();

                if (! $ultimaActividad || $ultimaActividad->gt($limite)) {
                    return false;
                }

                // No repetir el recordatorio antes de que pasen $dias desde el último enviado
                if ($matricula->ultimo_recordatorio_inactividad_en
                    && $matricula->ultimo_recordatorio_inactividad_en->gt(now()->subDays($dias))) {
                    return false;
                }

                return true;
            });

        if ($matriculas->isEmpty()) {
            $this->info('No hay alumnos inactivos pendientes de recordatorio.');

            return self::SUCCESS;
        }

        foreach ($matriculas as $matricula) {
            if (! $matricula->estudiante) {
                continue;
            }

            Mail::to($matricula->estudiante->email)
                ->send(new RecordatorioInactividadMail($matricula, $matricula->estudiante));

            $matricula->update(['ultimo_recordatorio_inactividad_en' => now()]);

            $this->info("Recordatorio enviado a {$matricula->estudiante->email} — curso \"{$matricula->curso->titulo}\".");
        }

        return self::SUCCESS;
    }
}

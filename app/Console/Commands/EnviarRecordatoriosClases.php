<?php

namespace App\Console\Commands;

use App\Mail\RecordatorioClaseMail;
use App\Models\ClaseEnVivo;
use App\Models\Matricula;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EnviarRecordatoriosClases extends Command
{
    protected $signature = 'clases:enviar-recordatorios';

    protected $description = 'Envía un correo recordatorio a los alumnos matriculados de las clases en vivo que empiezan dentro de la próxima hora';

    public function handle(): int
    {
        $clases = ClaseEnVivo::where('recordatorio_enviado', false)
            ->whereBetween('fecha_hora', [now(), now()->addHour()])
            ->with('curso')
            ->get();

        if ($clases->isEmpty()) {
            $this->info('No hay clases próximas pendientes de recordatorio.');

            return self::SUCCESS;
        }

        foreach ($clases as $clase) {
            $estudiantes = Matricula::where('curso_id', $clase->curso_id)
                ->where('estado', '!=', 'pendiente_pago')
                ->with('estudiante')
                ->get()
                ->pluck('estudiante')
                ->filter();

            foreach ($estudiantes as $estudiante) {
                Mail::to($estudiante->email)->send(new RecordatorioClaseMail($clase, $estudiante));
            }

            $clase->update(['recordatorio_enviado' => true]);

            $this->info("Recordatorio enviado para \"{$clase->titulo}\" a {$estudiantes->count()} alumno(s).");
        }

        return self::SUCCESS;
    }
}

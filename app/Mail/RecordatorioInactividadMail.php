<?php

namespace App\Mail;

use App\Models\Matricula;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecordatorioInactividadMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Matricula $matricula, public User $estudiante)
    {
    }

    public function build(): self
    {
        return $this->subject('Te extrañamos en "' . $this->matricula->curso->titulo . '"')
            ->view('emails.recordatorio-inactividad');
    }
}

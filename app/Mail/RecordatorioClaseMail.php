<?php

namespace App\Mail;

use App\Models\ClaseEnVivo;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecordatorioClaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ClaseEnVivo $clase, public User $estudiante)
    {
    }

    public function build(): self
    {
        return $this->subject('Tu clase "' . $this->clase->titulo . '" empieza pronto')
            ->view('emails.recordatorio-clase');
    }
}

<?php

namespace App\Mail;

use App\Models\Orden;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrdenAprobadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Orden $orden)
    {
    }

    public function build(): self
    {
        return $this->subject('¡Ya tienes acceso a ' . $this->orden->curso->titulo . '!')
            ->view('emails.orden-aprobada');
    }
}

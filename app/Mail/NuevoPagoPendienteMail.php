<?php

namespace App\Mail;

use App\Models\Orden;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NuevoPagoPendienteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Orden $orden)
    {
    }

    public function build(): self
    {
        return $this->subject('Nuevo pago por revisar: ' . $this->orden->codigo)
            ->view('emails.nuevo-pago-pendiente');
    }
}

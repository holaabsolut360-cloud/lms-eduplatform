<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BienvenidaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $usuario)
    {
    }

    public function build(): self
    {
        return $this->subject('¡Bienvenido a ' . config('app.name') . '!')
            ->view('emails.bienvenida');
    }
}

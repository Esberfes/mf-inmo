<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitudAnunciante extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitud;
    public $admin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($admin, $solicitud)
    {
        $this->solicitud = $solicitud;
        $this->admin = $admin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS', 'info@mundofranquicia.com'), 'mundoFranquicia inmobiliaria')
        ->subject('Solicitud anunciante')
        ->view('mails.solicitud-anunciante');
    }
}

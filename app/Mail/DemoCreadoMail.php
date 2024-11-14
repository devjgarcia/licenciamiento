<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemoCreadoMail extends Mailable
{
    use Queueable, SerializesModels;
    
    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( array $datos )
    {
        $this->data = $datos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.demo_creada')
                    ->subject('Bienvenido a Saca Móvil')
                    ->with([ 
                        'codigo'     => $this->data['codigo'],
                        'correo'     => $this->data['correo'],
                        'contrasena' => $this->data['contrasena'],
                        'url_saca'   => $this->data['url_saca'],
                    ]);
    }
}

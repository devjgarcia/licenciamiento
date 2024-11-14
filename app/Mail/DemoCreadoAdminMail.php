<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemoCreadoAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $datos )
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
        return $this->view('emails.demo_creada_admin')
                    ->subject('Nuevo Demo Saca Móvil')
                    ->with([ 
                        'codigo'     => $this->data['codigo'],
                        'correo'     => $this->data['correo'],
                        'empresa'    => $this->data['empresa'],
                        'contacto'   => $this->data['contacto'],
                        'telefono'   => $this->data['telefono'],
                        'pais'       => $this->data['pais'],
                    ]);
    }
}

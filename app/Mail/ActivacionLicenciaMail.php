<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivacionLicenciaMail extends Mailable
{
    use Queueable, SerializesModels;
    private $data;

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
        return $this->view('emails.activacion_licencia')
                    ->subject( $this->data['subject'] )
                    ->with([ 
                        'licencias' => $this->data['licencias'],
                        'usuario'   => $this->data['usuario'],
                        'fecha'     => $this->data['fecha'],
                    ]);
    }
}

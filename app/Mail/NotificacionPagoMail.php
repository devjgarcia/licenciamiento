<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionPagoMail extends Mailable
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
        return $this->view('emails.notificacion_pago')
                    ->subject('Notificación de Pago Saca Móvil')
                    ->with([ 
                        'licencia' => $this->data['licencia'],
                        'pago'     => $this->data['pago'],
                    ])
                    ->attach( $this->data['comprobante'], [
                        'as' => $this->data['as_comprobante'],
                        'mime' => $this->data['mime_comprobante'],
                    ]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $data )
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'garcia.jcarlos95@gmail.com';
        $subject = 'This is a demo!';
        $name = 'Juan Garcia';

        return $this->view('emails.test')
                    ->subject($subject)
                    ->with([ 'test_message' => $this->data['message'] ]);
    }
}

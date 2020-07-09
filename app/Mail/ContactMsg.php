<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMsg extends Mailable
{
    use Queueable, SerializesModels;

    public $cd;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($objContact)
    {
        $this->cd = $objContact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("New query via Contact section on Iaspirant")
                ->view('mailer.contact');
    }
}

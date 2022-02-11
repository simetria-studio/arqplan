<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewDemonstrationContact extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
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
        $subject = 'Nova solicitação de contato - arqplann.com.br!';
        $name = 'ArqPlann';

        return $this->view('emails.demonstrationcontact.new')
                    ->to(env('MAIL_TO_NEW_CONTACTS'), $name)
                    ->from(env('MAIL_FROM_ADDRESS'), $name)
                    ->subject($subject)
                    ->with(['data' => $this->data]);
    }
}

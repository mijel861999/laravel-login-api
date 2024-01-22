<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    public $token;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function content(): Content
    {
        return new Content(
            html: 'mails.reset_password_mail',
        );
    }

    public function build()
    {
        return $this->view('emails.reset_password_mail')
                    ->with(['token' => $this->token]);
    }
}

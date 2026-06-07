<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public string $token
    ) {
        $this->verificationUrl = route('verification.verify') . '?token=' . $token;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Verifique seu endereço de e-mail - SyncMatch')
                    ->view('emails.verify-email');
    }
}

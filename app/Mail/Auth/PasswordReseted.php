<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordReseted extends Mailable
{
    use Queueable, SerializesModels;

    private string $username;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $username)
    {
        $this->username = $username;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.users.auth.password_reseted')->with(
            [
                'username' => $this->username,
            ]
        );
    }
}

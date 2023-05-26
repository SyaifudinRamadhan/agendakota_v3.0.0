<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $email;
    public $link;
    public function __construct($props)
    {
        $this->email = $props['email'];
        $this->link = $props['link_verification'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.reset-password')
                    ->with(
                      [
                            'email' => $this->email,
                            'link' => $this->link,
                      ]);
    }
}

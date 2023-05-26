<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user;
    public $otp;

    public function __construct($props)
    {
        $this->user = $props['user'];
        $this->otp = $props['otp'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.otp', [
            'user' => $this->user,
            'otp' => $this->otp,
            'message' => $this
        ]);
    }
}

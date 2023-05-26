<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name;
    public $email;
    public $link;
    public function __construct($probs)
    {
        $this->name = $probs['name'];
        $this->email = $probs['email'];
        $this->link = $probs['link_verification'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        return $this->view('mails.aktivasi-email')
        ->with(
          [
                'name' => $this->name,
                'email' => $this->email,
                'link' => $this->link,
                'message' => $this
          ]);
    }
}

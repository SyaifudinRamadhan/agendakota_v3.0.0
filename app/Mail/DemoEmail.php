<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemoEmail extends Mailable
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

    public function __construct($props)
    {
        $this->name = $props['name'];
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
       // return $this->view('view.name');
       return $this->view('mails.aktivasi-email')
                    ->with(
                      [
                            'name' => $this->name,
                            'email' => $this->email,
                            'link' => $this->link,
                      ]);
    }
}

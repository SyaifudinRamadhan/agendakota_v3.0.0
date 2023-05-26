<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $arrView;
    private $username;
    private $command;
    public function __construct($user, $command, $arrData)
    {
        $this->arrView = $arrData;
        $this->username = $user;
        $this->command = $command;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.withdrawNotification')->with([
            'username' => $this->username,
            'dataView' => $this->arrView,
            'command' => $this->command,
        ]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteMembers extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $organisasi;
    public $email;
    public $organisasiID;
    public $link_invite;
    public $nameSender;
    public $mailSender;

    public function __construct($props)
    {
        $this->organisasi = $props['organisasi'];
        $this->email = $props['email'];
        $this->organisasiID = $props['organisasiID'];
        $this->link_invite = $props['link_invite'];
        $this->nameSender = $props['sender'];
        $this->mailSender = $props['senderMail'];

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        return $this->from($this->mailSender, $this->nameSender)
                    ->view('mails.invite-team')
                    ->with(
                      [
                            'organisasi' => $this->organisasi,
                            'email' => $this->email,
                            'link_invite' => $this->link_invite,
                            'organisasiID' => $this->organisasiID,
                      ]);
    }
}

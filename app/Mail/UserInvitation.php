<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $ticket_id;
    public $event_id;
    public $sender;
    public $email;
    public $participant;
    public $link;
    public $receiver;
    public $senderEmail;
    public $password;
    public function __construct($props)
    {
        $this->sender = $props['sender'];
        $this->link = $props['link'];
        $this->event =$props['event'];
        $this->senderEmail = $props['sender_mail'];
        if(isset($props['receiver'])){
            $this->receiver =$props['receiver'];

        }
        if(isset($props['email'])){
            $this->email = $props['email'];

        }
        if(isset($props['ticket_id']) && isset($props['event'])){
            $this->ticket_id =$props['ticket_id'];
        }
        if(isset($props['participant'])){
            $this->participant = $props['participant'];
        }
        if(isset($props['password'])){
            $this->password = $props['password'];
        }
        // dd($this->senderEmail);

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if(isset($this->participant) && isset($this->email) && isset($this->password)){
            return $this->from($this->senderEmail, $this->sender)->view('mails.invitation-page')
                    ->with(
                      [
                            'sender' => $this->sender,
                            'link' => $this->link,
                            'email' => $this->email,
                            'event' => $this->event,
                            'participant' => $this->participant,
                            'password' => $this->password,
                      ]);
        //     return $this->view('mails.invitation-page', [
        //         'sender' => $this->sender,
        //         'link' => $this->link,
        //         'email' => $this->email,
        //         'event' => $this->event,
        //         'participant' => $this->participant,
        //         'password' => $this->password,
        //   ]);
        }
        elseif(isset($this->ticket_id) && isset($this->email) ){

            return $this->from($this->senderEmail, $this->sender)->view('mails.invitation-page')
                        ->with(
                          [
                                'sender' => $this->sender,
                                'link' => $this->link,
                                'email' => $this->email,
                                'event' => $this->event,
                                'ticket_id' => $this->ticket_id,
                          ]);
        //     return $this->view('mails.invitation-page', [
        //         'sender' => $this->sender,
        //         'link' => $this->link,
        //         'email' => $this->email,
        //         'event' => $this->event,
        //         'ticket_id' => $this->ticket_id,
        //   ]);
        }elseif(isset($this->email)){
            //tidak centang, tidak punya akun

            return $this->from($this->senderEmail, $this->sender)->view('mails.invitation-page')
                        ->with(
                          [
                                'sender' => $this->sender,
                                'link' => $this->link,
                                'email' => $this->email,
                                'event' => $this->event,
                          ]);
        //     return $this->view('mails.invitation-page',[
        //         'sender' => $this->sender,
        //         'link' => $this->link,
        //         'email' => $this->email,
        //         'event' => $this->event,
        //   ]);
        }elseif(isset($this->ticket_id)){//ada akun pilih tiket
            return $this->from($this->senderEmail, $this->sender)->view('mails.invitation-page')
                        ->with(
                          [
                                'sender' => $this->sender,
                                'link' => $this->link,
                                'receiver' => $this->receiver,
                                'event' => $this->event,
                                'ticket_id' => $this->ticket_id,
                          ]);
        //     return $this->view('mails.invitation-page', [
        //         'sender' => $this->sender,
        //         'link' => $this->link,
        //         'receiver' => $this->receiver,
        //         'event' => $this->event,
        //         'ticket_id' => $this->ticket_id,
        //   ]);
        }else{
            //tidak centang tiket ada akun
            return $this->from($this->senderEmail, $this->sender)->view('mails.invitation-page')
                        ->with(
                          [
                                'sender' => $this->sender,
                                'link' => $this->link,
                                'receiver' => $this->receiver,
                          ]);
        //     return $this->view('mails.invitation-page',[
        //         'sender' => $this->sender,
        //         'link' => $this->link,
        //         'receiver' => $this->receiver,
        //   ]);
        }
    }
}

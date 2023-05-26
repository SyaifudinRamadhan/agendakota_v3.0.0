<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BankAccountVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $username;
    private $accountNumber;
    private $bankName;
    private $linkVerfiy;
    public function __construct($username, $account, $bank, $links)
    {
        $this->username = $username;
        $this->accountNumber = $account;
        $this->bankName = $bank;
        $this->linkVerfiy = $links;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.bankAccountVerify')->with([
            'username' => $this->username,
            'account' => $this->accountNumber,
            'bank' => $this->bankName,
            'links' => $this->linkVerfiy,
        ]);
    }
}

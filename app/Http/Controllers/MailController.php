<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\DemoEmail;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send()
    {
        $objDemo = new \stdClass();
        $objDemo->demo_one = 'Demo One Value';
        $objDemo->demo_two = 'Demo Two Value';
        $objDemo->sender = 'SenderUserName';
        $objDemo->receiver = 'ReceiverUserName';

        $user['name'] = "Rennata Natalia";
        $user['email'] = "renaacc0301@gmail.com";
        $user['link_verification'] = route("user.emailVerification", base64_encode($user['email']));

        Mail::to("renaacc0301@gmail.com")->send(new DemoEmail($user));
    }
}

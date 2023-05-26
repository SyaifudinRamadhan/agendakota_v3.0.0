<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Mail\UserInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\UserController;
use App\Models\User;
use Session;

class InvitationController extends Controller
{
    public static function get($filter = NULL) {
        if ($filter == NULL) {
            return new Invitation();
        }
        return Invitation::where($filter);
    }
    // public function sendInvitation($eventID, Request $request)
    // {
    //     $validateData = $this->validate($request, [
    //         'email' => 'required|email',
    //     ]);
    //     $myData = UserController::me();
    //     $email= $request->email;
    //     $buyticket = $request->breakdowns;

    //     $receiverEmail = UserController::get()->where('email', $email)->first();
    //     if(isset($receiverEmail)){
    //         $receiverID= $receiverEmail->id;
    //     }


    //     $senderID = $myData->id;
    //     $ticketID = $request->ticket;
    //     $event = EventController::get()->where('id', $eventID)->first();
    //     // $ticket=TicketController::get([['id','=',$ticketID]])->first();


    //     if(isset($buyticket) && $ticketID == 0){
    //         return redirect()->back()->with('pesan', 'Tolong Pilih Tiket Untuk Teman Anda');
    //     }elseif(!isset($buyticket) && isset($receiverID)){//tidak centang, ada akun

    //         $buyticket =0;
    //         $ticketID = 0;

    //         $invitation['link']=route("user.loginPage");
    //         $invitation['sender']=$myData->name;
    //         $invitation['receiver']=$receiverEmail->name;
    //         $invitation['event']=$event->name;
    //         $invitation['sender_mail'] = $myData->email;

    //         // dd($invitation, $myData);
    //         // dd(new UserInvitation($invitation));

    //         Mail::to($email)->send(new UserInvitation($invitation));
    //         $savedata = Invitation::create([
    //             'sender' => $myData->id,
    //             'receiver'=>$receiverID,
    //             'event_id'=>$eventID,
    //             'buy_ticket'=>$buyticket
    //         ]);

    //         return redirect()->back()->with('pesan',"Anda Telah Berhasil Mengundang Teman");
    //     }elseif(isset($buyticket) && isset($receiverID)){//centang ada akun
    //         $buyticket = 1;
    //         $invitation['link']=route('user.loginPage');
    //         $invitation['sender']=$myData->name;
    //         $invitation['receiver']=$receiverEmail->name;
    //         $invitation['ticket_id']=$ticketID;
    //         $invitation['event']=$event->name;
    //         $invitation['sender_mail'] = $myData->email;

    //         // dd($invitation,  $myData);
    //         // dd(new UserInvitation($invitation));

    //         Mail::to($email)->send(new UserInvitation($invitation));
    //         $savedata = Invitation::create([
    //             'sender' => $myData->id,
    //             'receiver'=>$receiverID,
    //             'event_id'=>$eventID,
    //             'ticket_id'=>$ticketID,
    //             'buy_ticket'=>$buyticket
    //         ]);
    //         return redirect()->back()->with('pesan',"Anda Telah Berhasil Mengundang Teman");
    //     }elseif(!isset($buyticket) && !isset($receiverID)){//tidak centang, tidak punya akun
    //         $buyticket =0;
    //         $invitation['link'] = route("user.eventDetail",$event->slug);
    //         $invitation['email'] = $request->email;
    //         $invitation['sender']=  $myData->name;
    //         $invitation['event']=$event->name;
    //         $invitation['sender_mail'] = $myData->email;

    //         // dd($invitation,  $myData);
    //         // dd(new UserInvitation($invitation));

    //         Mail::to($email)->send(new UserInvitation($invitation));
    //         // Mail::to($request->email)->send(new UserVerification($user));
    //         return redirect()->back()->with('pesan',"Anda Telah Berhasil Mengundang Teman");
    //     }elseif(isset($buyticket) && !isset($receiverID)){//centang, tapi tidak ada akun
    //         $buyticket =1;
    //         $invitation['ticket_id'] = $ticketID;
    //         $invitation['event'] = $event->name;
    //         $invitation['sender'] = $myData->name;
    //         $invitation['email'] = $request->email;
    //         $invitation['link'] = route('user.event.register-invitation',[base64_encode($invitation['email']),base64_encode($invitation['event']),$invitation['sender'],$invitation['ticket_id']]);
    //         $invitation['sender_mail'] = $myData->email;

    //         // dd($invitation,  $myData);
    //         // dd(new UserInvitation($invitation));

    //         Mail::to($email)->send(new UserInvitation($invitation));
    //         return redirect()->back()->with('pesan',"Anda Telah Berhasil Mengundang Teman");
    //     }


    // }

    public function sendInvitation($myData,$email,$purchase)
    {
        
        $buyticket = 1;

        $eventID = $purchase->event_id;
        $ticketID = $purchase->ticket_id;

        $receiverEmail = UserController::get()->where('email', $email)->first();
        if(isset($receiverEmail)){
            $receiverID= $receiverEmail->id;
        }


        $senderID = $myData->id;
       
        $event = EventController::get()->where('id', $eventID)->first();
        // $ticket=TicketController::get([['id','=',$ticketID]])->first();


       if(isset($buyticket) && isset($receiverID)){//centang ada akun
            $buyticket = 1;
            $invitation['link']=route('user.loginPage');
            $invitation['sender']=$myData->name;
            $invitation['receiver']=$receiverEmail->name;
            $invitation['ticket_id']=$ticketID;
            $invitation['event']=$event->name;
            $invitation['sender_mail'] = $myData->email;

            // dd($invitation,  $myData);
            // dd(new UserInvitation($invitation));

            Mail::to($email)->send(new UserInvitation($invitation));
            $savedata = Invitation::create([
                'sender' => $myData->id,
                'receiver'=>$receiverID,
                'purchase_id' => $purchase->id,
                'event_id'=>$eventID,
                'ticket_id'=>$ticketID,
                'buy_ticket'=>$buyticket,
            ]);
            return redirect()->back()->with('pesan',"Anda Telah Berhasil Mengundang Teman");
        }else if(isset($buyticket) && !isset($receiverID)){//centang, tapi tidak ada akun
            $buyticket =1;
            $invitation['ticket_id'] = $ticketID;
            $invitation['event'] = $event->name;
            $invitation['sender'] = $myData->name;
            $invitation['email'] = $email;
            $invitation['purchase'] = $purchase->id;
            $invitation['link'] = route('user.event.register-invitation',[base64_encode($invitation['email']),base64_encode($invitation['event']),$invitation['sender'],$invitation['ticket_id'],$invitation['purchase']]);
            $invitation['sender_mail'] = $myData->email;

            // dd($invitation,  $myData);
            // dd(new UserInvitation($invitation));

            Mail::to($email)->send(new UserInvitation($invitation));
            return redirect()->back()->with('pesan',"Anda Telah Berhasil Mengundang Teman");
        }


    }

    public function registerinvitation($email,$event,$sender, $ticket, $purchase)
    {
        $email_user =base64_decode($email);
        $event_name =base64_decode($event);

        $senderID='';
        $ticketID =$ticket;


        $userObj = User::where('name', $sender)->get();
        if(count($userObj) == 0 || count($userObj) > 1){
        
            return redirect()->route('user.registerPage')->withErrors('Nama pengirim undangan tidak valid, silahkan anda register dahulu kemudian mintalah di invite ulang');
        }else{
            $senderID = $userObj[0]->id;
        }

        $userRegister = User::where('email', $email_user)->get();
        // dd($userRegister, $email_user);
        if(count($userRegister) > 0){
            $event = EventController::get([['name','=',$event_name]])->first();
            // dd($userRegister[0]->id);
            $checkDouble = Invitation::where('receiver', $userRegister[0]->id)->where('sender', $senderID)->where('purchase_id', $purchase)->get();
            // dd($checkDouble);
            if(count($checkDouble) < 5){
                $savedata = Invitation::create([
                    'sender' => $senderID,
                    'receiver'=>$userRegister[0]->id,
                    'purchase_id' => $purchase,
                    'event_id'=>$event->id,
                    'ticket_id'=>$ticketID,
                    'buy_ticket'=>1,
                ]);
                return redirect()->route('user.loginPage')->with('berhasil', 'Undangan berhasil kamu dapatkan. Silahkan login');
            }
            return redirect()->route('user.loginPage')->with('gagal', 'Kamu sudah pernah mengunjungi link undangannya');
        }
        session([
            'email'=> $email_user,
            'ticketID' => $ticketID,
            'senderID'=>$senderID,
            'event_name'=>$event_name,
            'purchase' => $purchase,
        ]);
        return view('user.register',[
            'email'=> $email_user,
            'ticketID' => $ticketID,
            'senderID'=>$senderID,
            'event_name'=>$event_name,
            'purchase' => $purchase,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketCart;


class CartController extends Controller
{
    public function pembelian(Request $request,$slug, $extTicketID = False, $inviter = False){

        $myData = UserController::me();
        // dd($request);
        if(isset($myData)){

            if(isset($request->breakdowns)){
                
                $tickets = $request->breakdowns;
                $user_id = $myData->id;

                for($i=0; $i<count($tickets); $i++){
                    $dataSave = [
                        'user_id' => $user_id,
                        'ticket_id' => $tickets[$i],
                        'status' => 'notPay',
                    ];

                    TicketCart::create($dataSave);
                }

                return redirect()->route('user.myTickets');
            }
            // dd($request);
            return redirect()->route('user.eventDetail', [$slug])->with('gagal', 'Kamu harus memilih ticket untuk bisa dibeli');

        }else{
            if(isset($request->breakdowns)){
                // dump($request->breakdowns);
                // dd(serialize($request->breakdowns));
                return redirect()->route('user.loginPage2',[http_build_query(array('myArray' => $request->breakdowns))]);
            }
            // dd($request);
            return redirect()->route('user.eventDetail', [$slug])->with('gagal', 'Kamu harus memilih ticket untuk bisa dibeli');
        }

    }
    // ----------------- Metode pemeriksaan cart yang dibeli apakah ada Ticket ID yang sama --------------------------------------
    # Hapus ticket jika ada lebih dari satu ticket -> sisakan satu. Untuk masalah jumlah sudah diatasi oleh tombol + dan -
    public static function cartCheckLoad($userID)
    {
        $carts = TicketCart::where('user_id', $userID)->where('status', 'notPay')->get();
        $cartByTicket = $carts->groupBy('ticket_id');
        foreach ($cartByTicket as $key => $value) {
            if(count($value) > 1){
                for ($i=0; $i < count($value)-1; $i++) { 
                    TicketCart::where('id', $value[$i]->id)->delete();
                }
            }
        }
        $carts = TicketCart::where('user_id', $userID)->where('status', 'notPay')->get();
        return $carts;
    }
}

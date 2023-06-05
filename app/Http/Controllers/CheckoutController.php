<?php

namespace App\Http\Controllers;

use Str;
use Carbon\Carbon;
use App\Models\Purchase;
use App\Models\Payment;
use App\Models\TicketCart;
use App\Models\Ticket;
use App\Models\TempTicketShare;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
	
	public function checkoutPay(Request $request){
		$myData = UserController::me();

		// Yang harus diisi untuk purchase
		// 'user_id'
		// 'send_from'
		// 'event_id'
		// 'ticket_id'
		// 'cart_id'
		// 'payment_id'
		// 'quantity'
		// 'price'
       
        $eventIDs = $request->eventID;
        $ticketIDs = $request->ticketID;
        $cartIDs = $request->cartID;
        // =============== Cara ambil data ini perlu dikoreksi lagi. Banyak celahnya ===================
        $quantitys = $request->quantity;
        // $prices = $request->price;
        // =============================================================================================
        // ================ Revisi metode diatas ===============================================
        $tickets = [];
        for($i=0; $i<count($ticketIDs); $i++){
            $tickets[$i] = Ticket::where('id', $ticketIDs[$i])->first();
        }
        // =====================================================================================
        // ------------- Data custom prices -------------------------
        $customPrices = $request->custom_prices;
        // -----------------------------------------------------------

		$userID = $myData->id;
		$totalPrice = $request->totalPay;
		$tokenTrx = "";
		$payState = 'Belum Terbayar';
		$orderId = "";

        $realTotalPrice = 0;
        $totalQty = 0;

        if(isset($request->cancel)){
            for($i=0; $i<count($cartIDs); $i++){
                TicketCart::where('id', $cartIDs[$i])->delete();
            }
            return redirect()->route('user.myTickets');
        }

        for($i=0; $i<count($quantitys); $i++){
            if($tickets[$i]->deleted == 0){
                $purchase = Purchase::where('user_id', $myData->id)->where('ticket_id', $ticketIDs[$i])->get();
                // Ticket yang tersisa untuk user ini
                $restQty = 5 - count($purchase);
                if($quantitys[$i] > $restQty){
                    $quantitys[$i] = $restQty;
                }
                // Tetapkan nilai quantity[$i] menjadi = jumlah ticket tersisa jika jumlah yang dibeli melebihi batas ticket
                if($tickets[$i]->quantity < $quantitys[$i]){
                    $quantitys[$i] = $tickets[$i]->quantity;
                }
                $totalQty += $quantitys[$i];
                // Cek apakah ticket ini bertipe bayar suka suka (custom price)
                if($tickets[$i]->type_price == 2){
                    if($tickets[$i]->price <= $customPrices[$i]){
                        $realTotalPrice += ($quantitys[$i]*$customPrices[$i]);
                    }else{
                        $realTotalPrice += ($quantitys[$i]*$tickets[$i]->price);
                    }
                }else{
                    $realTotalPrice += ($quantitys[$i]*$tickets[$i]->price);
                }
            }
        }
		
		//Insert into payment table
        // Baru diubah per tgl 14 May 2022
		$CLIENT_KEY = config('agendakota')['midtrans_config']['CLIENT_KEY'];
        $SERVER_KEY = config('agendakota')['midtrans_config']['SERVER_KEY'];

        \Midtrans\Config::$serverKey = $SERVER_KEY;
        \Midtrans\Config::$clientKey = $CLIENT_KEY;
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = config('agendakota')['midtrans_config']['isProduction'];
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = config('agendakota')['midtrans_config']['isSanitized'];
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $orderId = '';

        // Menanggulangi jika ada orderID yang sama
        do{
            // Lakukan generate orderID
            $random = new RandomStrController();
            $orderId = $random->get();
        }while (count(Payment::where('order_id', $orderId)->get()) > 0);

        $params = array(
            'transaction_details' => array(
                'order_id' => $orderId,
                'gross_amount' => $realTotalPrice,
            ),
            "item_details" => array([                   
                  'id' => '01',
                  'price' => $realTotalPrice,
                  'quantity' => 1,
                  'name' => 'ID beli : '.$orderId,
            ]),
            'customer_details' => array(
                'first_name' => $myData->name,                        
                'email' => $myData->email,
                'phone' => $myData->phone,
            ),
        );

        $savePayment = "";

        if($realTotalPrice > 0){
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $tokenTrx = $snapToken;

            $saveDataPayment = [
                'user_id' => $userID,
                'token_trx' => $tokenTrx,
                'pay_state' => $payState,
                'order_id' => $orderId,
                'price' => $realTotalPrice,
            ];
            $savePayment = Payment::create($saveDataPayment);
        }else{
            $saveDataPayment = [
                'user_id' => $userID,
                'token_trx' => $tokenTrx,
                'pay_state' => $payState,
                'order_id' => $orderId,
                'price' => $realTotalPrice,
            ];
            $savePayment = Payment::create($saveDataPayment);
        }
		
		//insert into purchase table
        for($i=0; $i<count($quantitys); $i++){
            if($tickets[$i]->deleted == 0){
                for($j=0; $j<$quantitys[$i]; $j++){
                    $price = '';
                    if($tickets[$i]->type_price == 2){
                        if($tickets[$i]->price <= $customPrices[$i]){
                            $price = $customPrices[$i];
                        }else{
                            $price = $tickets[$i]->price;
                        }
                    }else{
                        $price = $tickets[$i]->price;
                    }
                    $saveDataPchs = [
                        'code' => strtoupper(Str::random(6)),
                        'user_id' => $userID,
                        'send_from' => $userID,
                        'event_id' => $eventIDs[$i],
                        'ticket_id' => $ticketIDs[$i],
                        'cart_id' => $cartIDs[$i],
                        'payment_id' => $savePayment->id,
                        'quantity' => 1,
                        'price' => $price,
                    ];
                    Purchase::create($saveDataPchs);
                }
                TicketCart::where('id', $cartIDs[$i])->update(['status' => 'waiting',]);
                $objTicket = Ticket::where('id', $ticketIDs[$i]);
                $newQty = $objTicket->first()->quantity - $quantitys[$i];
                $objTicket->update(['quantity' => $newQty]);
            }
        }

        //--------- Mengatasi cart yang gagal di checkout karena ticket dihapus admin ------------------
        TicketCart::where('user_id', $myData->id)->where('status','notPay')->delete();

        return redirect()->route('user.shareTickets2',[http_build_query(array('myArray' => $savePayment->id))]);
	}

    public function reCheckout($payment, $myData)
    {
        // dump('recheckout running');
        // Melakukan update checkout
        $CLIENT_KEY = config('agendakota')['midtrans_config']['CLIENT_KEY'];
        $SERVER_KEY = config('agendakota')['midtrans_config']['SERVER_KEY'];

        $payState = 'Belum Terbayar';

        \Midtrans\Config::$serverKey = $SERVER_KEY;
        \Midtrans\Config::$clientKey = $CLIENT_KEY;
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = config('agendakota')['midtrans_config']['isProduction'];
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = config('agendakota')['midtrans_config']['isSanitized'];
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $orderId = '';

        // Menanggulangi jika ada orderID yang sama
        do{
            // Lakukan generate orderID
            $random = new RandomStrController();
            $orderId = $random->get();
        }while (count(Payment::where('order_id', $orderId)->get()) > 0);

        $params = array(
            'transaction_details' => array(
                'order_id' => $orderId,
                'gross_amount' => $payment->price,
            ),
            "item_details" => array([                   
                  'id' => '01',
                  'price' => $payment->price,
                  'quantity' => 1,
                  'name' => 'ID beli : '.$orderId,
            ]),
            'customer_details' => array(
                'first_name' => $myData->name,                        
                'email' => $myData->email,
                'phone' => $myData->phone,
            ),
        );

        $savePayment = "";

        if($payment->price > 0){
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $tokenTrx = $snapToken;

            $saveDataPayment = [
                'user_id' => $myData->id,
                'token_trx' => $tokenTrx,
                'pay_state' => $payState,
                'order_id' => $orderId,
                'price' => $payment->price,
            ];
            // dump($saveDataPayment);
            $savePayment = Payment::where('id', $payment->id)->update($saveDataPayment);
        }else{
            $saveDataPayment = [
                'user_id' => $myData->id,
                'token_trx' => '',
                'pay_state' => 'Terbayar',
                'order_id' => $orderId,
                'price' => $payment->price,
            ];
            // dump($saveDataPayment);
            $savePayment = Payment::where('id', $payment->id)->update($saveDataPayment);
        }
    }

    public function ticketShare(Request $request){
        $myData = UserController::me();
        // dd($request->get('myArray'));
        $payments = Payment::where('id', $request->get('myArray'))->get();
        if(count($payments) == 0){
            // dd($payments);
            return redirect()->route('user.myTickets');
        }

        // ------- Jika ada tiket yang sudah dihapus dan belum terbayar ------------------------
        
        $ticketExpired =[];
        for($h=0; $h<count($payments); $h++){
            $purchases = $payments[$h]->purchases;
            $newPayment = false;
            for($i=0; $i<count($purchases); $i++){
                if($purchases[$i]->tickets->deleted == 1){
                    array_push($ticketExpired, $purchases[$i]->tickets);
                    // Ubah data uang di payment ID terkait
                    $payObject = Payment::where('id', $payments[$h]->id);
                    $priceTicket = $purchases[$i]->price;
                    $newPrice = $payObject->first()->price - $priceTicket;
                    $payObject->update(['price'=>$newPrice]);
                    // Langsung hapus data di purchases terkait
                    Purchase::where('id', $purchases[$i]->id)->delete();
                    $newPayment = true;
                }
            }
            $payCheck = Payment::where('id',$payments[$h]->id)->first();
            if(count($payCheck->purchases) == 0){
                Payment::where('id', $payments[$h]->id)->delete();
            }else if($newPayment == true){
                // Jika payments data ada perubahan lakukan re checkout
                $this->reCheckout($payCheck, $myData);
            }
        }
        $payments = Payment::where('id', $request->get('myArray'))->get();

        if(count($payments) == 0){
            // dd($payments);
            return redirect()->route('user.myTickets');
        }

        // --------------------------------------------------------------------------------
        
        return view('user.ticketShare', [
            'myData' => $myData,
            'purchases' => "",
            'organizationID' => "",
            'totalInvite' => "",
            'eventID' => "",
            'payments' => $payments,
            'mode' => 'singlePay',
        ]);
    }

    public function saveShare(Request $request){
        $giftsTo = $request->gifts;
        $purchaseIDs = $request->purchases;
        $paymentIDs = $request->payments;

        for($i=0; $i<count($giftsTo); $i++){
            $validateRule = [
                'gifts' => 'required',
            ];
            $validateMsg = [
                'required' => 'Kolom Give To Wajib diisi',
            ];
            $this->validate($request, $validateRule, $validateMsg);
            $saveData = [
                "payment_id" => $paymentIDs[$i],
                "purchase_id" => $purchaseIDs[$i],
                "share_to" => $giftsTo[$i],
            ];
            $ckeckDouble = TempTicketShare::where('purchase_id', $purchaseIDs[$i])->get();
            // dd($ckeckDouble);
            if(count($ckeckDouble) == 0){
                TempTicketShare::create($saveData);
            }
        }
        return redirect()->route('user.myTickets');
    }

    public function changeShare(Request $request){
        $myData = UserController::me();
        $purchaseID = $request->purchaseID;
        $sendTo = $request->sendTo;

        $purchaseObj = Purchase::where('id', $purchaseID)->first();

        $tempForID = "0";
        if(isset($purchaseObj->tempFor)){
            $tempForID = $purchaseObj->tempFor->id;
        }

        $invitationID = "0";
        if(isset($purchaseObj->invitation)){
            $invitationID = $purchaseObj->invitation->id;
        }

        if($purchaseObj->tempFor->share_to != $myData->email && $invitationID == "0"){
            return redirect()->back()->with('gagal', 'Kamu tidak bisa menarik undangan sebelum user register akun');
        }else{
            if($purchaseObj->tempFor->share_to != $myData->email && $invitationID != "0"){
                if($purchaseObj->invitation->response == "Accept"){
                    return redirect()->back()->with('gagal', 'Kamu tidak bisa menarik undangan yang sudah di accept');
                }else{
                    if($sendTo == $purchaseObj->tempFor->share_to){
                        return redirect()->back()->with('gagal', 'Kamu sudah mengiriminya undangan, silahkan tunggu'); 
                    }else if($sendTo == $myData->email){
                        //Tarik undangan
                        Invitation::where('id', $purchaseObj->invitation->id)->delete();
                        //Update tempShare
                        TempTicketShare::where('id', $purchaseObj->tempFor->id)->update(['share_to' => $sendTo]);

                         return redirect()->back()->with('berhasil', 'Kamu berhasil menarik kembali undanganmu'); 
                    }
                    //Tarik undangan
                    Invitation::where('id', $purchaseObj->invitation->id)->delete();
                    //Update tempShare
                    TempTicketShare::where('id', $purchaseObj->tempFor->id)->update(['share_to' => $sendTo]);
                    //Kirim Undangan
                    $objInvitation = new InvitationController();
                    $objInvitation->sendInvitation(
                        $myData,
                        $sendTo,
                        $purchaseObj
                    );
                    return redirect()->back()->with('berhasil', 'Kamu berhasil mengalihkan undanganmu');
                }
            }else if($purchaseObj->tempFor->share_to == $myData->email){
                if($sendTo == $myData->email){
                    return redirect()->back()->with('gagal', 'Kamu btidak bisa mengundang dirimu sendiri'); 
                }
                //Update tempShare
                TempTicketShare::where('id', $purchaseObj->tempFor->id)->update(['share_to' => $sendTo]);
                //Kirim Undangan JIKA SUDAH DIBAYAR
                if($purchaseObj->payment->pay_state == 'Terbayar'){
                    $objInvitation = new InvitationController();
                    $objInvitation->sendInvitation(
                        $myData,
                        $sendTo,
                        $purchaseObj
                    );
                }
                
                return redirect()->back()->with('berhasil', 'Kamu berhasil memberikan undangan ke temanmu');
            }
        }
    }
}

?>
<?php 

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Payment;
use App\Models\TicketCart;
use App\Models\Ticket;
use App\Models\TempTicketShare;

class PaymentController extends Controller{
	public function getTransaction($dataUser){
		
		$myData = $dataUser;
		$paymentsOri = Payment::where('user_id', $myData->id)->get();
		$payments = [];

		for($i=0; $i<count($paymentsOri); $i++){
			//Menguppdate dan memeriksa status pembayaran
	        if($paymentsOri[$i]->pay_state == "Belum Terbayar"){
	            $curl = curl_init();
				// Memindahkan value variable cURL ke agendakota.comfig per tgl 14 Mey 2022
	              curl_setopt_array($curl, array(
	              CURLOPT_URL => config('agendakota')['midtrans_config']['main_url'].'v2/'.$paymentsOri[$i]->order_id.'/status',
	              CURLOPT_RETURNTRANSFER => true,
	              CURLOPT_HTTPHEADER => config('agendakota')['midtrans_config']['CURLOPT_HTTPHEADER'],
	            ));

	            $output = curl_exec($curl);

	            curl_close($curl);

	            $dataFromJson = json_decode($output, true);
	            // var_dump($output);
	            // dd($dataFromJson);
	            // dump($dataFromJson);
	            if($dataFromJson != null){
	                $payState = $dataFromJson["status_code"];

	                if($payState == 200 || $payState == "200"){
	                    $dataUpdate = [
	                        'pay_state' => 'Terbayar'
	                    ];

	                    Payment::where('token_trx', $paymentsOri[$i]->token_trx)->where('user_id', $myData->id)->update($dataUpdate);
	                    //Lakukan pemanggilan function untuk mendaftarkan undangan di temp database
	                    $sharesTo = $paymentsOri[$i]->tempShares;
	                    for($j=0; $j<count($sharesTo); $j++){
	                    	if($sharesTo[$j]->share_to != $myData->email){
	                    		$objInvitation = new InvitationController();
	                    		$objInvitation->sendInvitation(
	                    			$myData,
	                    			$paymentsOri[$i]->tempShares[$j]->share_to,
	                    			$paymentsOri[$i]->tempShares[$j]->purchase);	
	                    	}
	                    }
	                    
	                }else if($paymentsOri[$i]->price == 0){
	                	$dataUpdate = [
	                        'pay_state' => 'Terbayar'
	                    ];
	                	
	                	Payment::where('id', $paymentsOri[$i]->id)->where('user_id', $myData->id)->update($dataUpdate);
	                	$sharesTo = $paymentsOri[$i]->tempShares;
	                    for($j=0; $j<count($sharesTo); $j++){
	                    	if($sharesTo[$j]->share_to != $myData->email){
	                    		$objInvitation = new InvitationController();
	                    		$objInvitation->sendInvitation(
	                    			$myData,
	                    			$paymentsOri[$i]->tempShares[$j]->share_to,
	                    			$paymentsOri[$i]->tempShares[$j]->purchase);	
	                    	}
	                    }
	                }else{
	                	$payments[$i] = $paymentsOri[$i];
	                }
	            }
	              
	        }
	        
		}
		return $payments;
        
	}

	public function cancelPayment($orderID)
	{
		$myData = UserController::me();
		// izinkan pembatalan pembayaran jika statusnya belum terbayar
		$payment = Payment::where('order_id', $orderID)->get();
		if(count($payment) == 0){
			return redirect()->back();
		}

		if($payment[0]->pay_state == 'Terbayar'){
			return redirect()->back();
		}

		// Update jumkah ticket yang dapat dibeli
		$payGroup = Payment::where('order_id', $orderID)->first()->purchases->groupBy('ticket_id');
		foreach($payGroup as $key => $value){
			$ticketObj = Ticket::where('id',$key);
			$ticketObj->update([
				'quantity' => $ticketObj->first()->quantity + count($value),
			]);
		}

		Payment::where('order_id', $orderID)->delete();

		return redirect()->back();
	}
}

 ?>


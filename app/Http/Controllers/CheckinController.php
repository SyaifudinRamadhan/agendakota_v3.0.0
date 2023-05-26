<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCheckin;
use App\Models\Purchase;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Payment;

class CheckinController extends Controller
{
    public static function manualCheck($idPurchase)
    {
        $purchase = Purchase::where('id', $idPurchase)->get();
        if(count($purchase) == 0){
            return -1;
        }
        UserCheckin::create([
            'user_id' => $purchase[0]->user_id,
            'purchase_id'=> $idPurchase,
            'checkin' => 1
        ]);

        return 1;
    }
    public function index($organizationID, $eventID, Request $request)
    {
        if(!isset($request->purchase)){
            return redirect()->back();
        }
        $idPurchase = base64_decode($request->purchase);
        $value = self::manualCheck($idPurchase);
        return redirect()->back();
    }

    public function exportCheckin($organizationID, $eventID)
    {
        $myData = UserController::me();
        $organization = Organization::where('id',$organizationID)->first();
        $tickets = Purchase::where('event_id', $eventID)->orderBy('created_at', 'DESC')->get();
        // --------- Cek apakah paket user aktif ? --------------------------------
        $isPkgActive = PackagePricingController::limitCalculator($organization->user);

        if($isPkgActive == 0 || $myData->package->report_download == 0){
            return redirect()->back();
        }
        // ------------------------------------------------------------------------
        return view('user.organization.event.ticket-selling-export', [
            'tickets' => $tickets,
            'myData' => $myData,
        ]);
    }

    public function qrCodeCheckin($organizationID, $eventID)
    {
        global $global;
        $global['organizationID'] = $organizationID;
        $myData = UserController::me();
        $event = Event::find($eventID);
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $tickets = Purchase::where('event_id', $eventID)->orderBy('created_at', 'DESC')->paginate(10);
        
        return view('user.organization.event.qr-code-checkin', [
            'tickets' => $tickets,
            'myData' => $myData,
            'event' => $event,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'isManageEvent' => 1
        ]);
    }

    public function scanCode($organizationID, $eventID, $orderID, $userID)
    {
        // Note : Atur ulang barcode / qr code isi dengan 'orderID-userID'
        // Cek apakah orderID valid ?
        $payment = Payment::where('order_id', $orderID)->get();
        if(count($payment) == 0){
            return json_encode([
                'error'=>1,
                'msg'=>'Gagal !!!. Order ID tidak ditemukan'
            ]);
        }

        // Cek pemabayarn orderID telah selesai
        if($payment[0]->pay_state == 'Belum Terbayar' || count($payment[0]->purchases) == 0){
            return json_encode([
                'error'=>1,
                'msg'=>'Gagal !!!. Ticketnya belum dibayar'
            ]);
        }

        // Cke apakah userID terdaftar di dalam orderID nya ? (Cari di tabel purchase)
        $checkined = 0;
        $dataPurchase = $payment[0]->purchases;
        for($i=0; $i<count($dataPurchase); $i++){
            if($dataPurchase[$i]->user_id == $userID && $dataPurchase[$i]->event_id == $eventID){
                // Cek apakah user sudah pernah checkin ?
                $checkin = UserCheckin::where('purchase_id', $dataPurchase[$i]->id)->get();
                if(count($checkin) == 0){
                    // Checkin
                    UserCheckin::create([
                        'user_id' => $dataPurchase[$i]->user_id,
                        'purchase_id'=> $dataPurchase[$i]->id,
                        'checkin' => 1
                    ]);
                    return json_encode([
                        'error'=>0,
                        'msg'=>'Berhasil !!!. Checkin berhasil dilakukan'
                    ]);
                }else{
                    // Sudah pernah checkin
                    $checkined+=1;
                }
            }
        }

        // Cek apakah $checkined > 0
        if($checkined > 0){
            return json_encode([
                'error'=>1,
                'msg'=>'Gagal !!!. Checkin sudah pernah dilakukan'
            ]);
        }

        // KOndisi terakhir User tidak pernah ditemukan
        return json_encode([
            'error'=>1,
            'msg'=>'Gagal !!!. ID Pembeli tidak ditemukan dalam data'
        ]);
    }
    public function scanCodeByUser($uniqueEventID)
    {
        // Perlu dioptimasi (Optimasi I tgl 26 Agustus 2022)
        $eventID = 0;
        $events = Event::where('unique_code', $uniqueEventID)->get();
        if(count($events) == 0){
            // return gagal checkin
            return json_encode([
                'error'=>1,
                'msg'=>'Gagal !!!. ID event tidak ditemukan dalam data'
            ]);
        }else{
            $eventID = $events[0]->id;
        }
        $hasCheckined = 0;
        $myData = UserController::me();
        $purchases = $myData->purchasesEvent($eventID)->get();
        for ($i=0; $i < count($purchases); $i++) { 
            if($purchases[$i]->payment->pay_state == "Terbayar" && $purchases[$i]->checkin == null){
                // Lakukan checkin
                UserCheckin::create([
                    'user_id' => $purchases[$i]->user_id,
                    'purchase_id'=> $purchases[$i]->id,
                    'checkin' => 1
                ]);
                
                // return sukses
                return json_encode([
                    'error'=>0,
                    'msg'=>'Berhasil !!!. Checkin berhasil dilakukan'
                ]);
            }else if($purchases[$i]->payment->pay_state == "Terbayar" && $purchases[$i]->checkin != null){
                $hasCheckined += 1;   
            }else{
                $hasCheckined = 0;
            }
        }

        
        if($hasCheckined > 0){
            // return gagal checkin
            return json_encode([
                'error'=>1,
                'msg'=>'Gagal !!!. Kamu sudah pernah checkin'
            ]);
        }else{
            // return gagal checkin
            return json_encode([
                'error'=>1,
                'msg'=>'Gagal !!!. ID Pembeli tidak ditemukan dalam data'
            ]);
        }
        
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PackagePayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\PackagePricing;
use DateTime;

class PackagePricingController extends Controller
{
    // ------ Function untuk menghitung sisa waktu setelah pembelian paket ----------
    // Waktu terhitung dari klik beli paket (Batas bayar = 1x 24 jam lebih dari itu = hangus)
    public static function limitCalculator($myData)
    {
        // $myData = UserController::me();
        $isPkgActive = $myData->pkg_status;
        # 1. Buat perhitungan masa aktif semua jenis akun berdasarkan created_at
        $now = new DateTime();
        $userTime = new DateTime($myData->created_at);
        $different = $userTime->diff($now);
        self::confirmUserPackage();
        // Batas waktu adalah 1 bulan = 30 hari
        $lastPaidPkg = PackagePayment::where('user_id',$myData->id)->where('status',1)->orderBy('id','DESC')->first();
        
        if($lastPaidPkg == null){
            $isPkgActive = 0;
        }else{
            $limitTime = 30;
            if($lastPaidPkg->nominal == $myData->package->price_in_year){
                $limitTime = 365;
            }
            
            if((int)$different->format('%r%a') >= $limitTime){
                User::where('id',$myData->id)->update([
                    'created_at' => $now,
                ]);
                // Nonaktifkan package untuk tipe berbayar
                if($myData->package->price != 0){
                    User::where('id',$myData->id)->update([
                        'pkg_status' => 0,
                    ]);
                    $isPkgActive = 0;
                }
            }
        }
        // Return value aktif tidaknya akun
        return $isPkgActive;
    }

    // ---------- function untuk update pilihan package user -------------
    public function updateUserPkg(Request $request)
    {

        //--------------------------- Midtran setup -----------------------------------------------------
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
        }while (count(PackagePayment::where('order_id', $orderId)->get()) > 0);

        
        // ---------------------------------------------------------------------------------------

        $selectType = $request->c2VsZWN0VHlwZT09;
        $idPkg = $request->aWRQa2dVc3I9PS0t;
        $myData = UserController::me();

        $idPkgSave = '';
        $price = 0;

        if(!isset($selectType) && !isset($idPkg)){
            // Lakukan pembelian ulang paket sebelumnya
            $allPaymments = PackagePayment::where('user_id',$myData->id)->where('status',1)->get();
            if(count($allPaymments) == 0){
                return redirect()->back()->with('gagal','Kamu belum pernah membeli paket satupun');
            }
            $lastPayment = $allPaymments[count($allPaymments)-1];
            // Cek apakah package masih tersedia / belum dihapus ?
            if($lastPayment->package->deleted == 1){
                return redirect()->back()->with('gagal','Paket yang kamu beli sudah dihapus. Beli paket lainnya');
            }

            $idPkgSave = $lastPayment->pkg_id;
            $price = $lastPayment->nominal;

        }else if(!isset($idPkg)){
            // Lakukan pembelian ulang dengan mode bayar bulanan
            $allPaymments = PackagePayment::where('user_id',$myData->id)->where('status',1)->get();
            if(count($allPaymments) == 0){
                return redirect()->back()->with('gagal','Kamu belum pernah membeli paket satupun');
            }
            $lastPayment = $allPaymments[count($allPaymments)-1];
            // Cek apakah package masih tersedia / belum dihapus ?
            if($lastPayment->package->deleted == 1){
                return redirect()->back()->with('gagal','Paket yang kamu beli sudah dihapus. Beli paket lainnya');
            }

            $idPkgSave = $lastPayment->pkg_id;
            $price = $lastPayment->package->price;

        }else if(!isset($selectType)){
            // Lakukan pembelian dengan mode bulanan
            $pkgId = base64_decode($idPkg);
            $pkg = PackagePricing::where('id',$pkgId)->get();

            // Cek apakah paket tersedia
            if(count($pkg) == 0){
                return redirect()->back()->with('gagal','Paket yang kamu beli tidak tersedia');
            }

            // Cek apakah statusnya dihapus
            if($pkg[0]->deleted == 1){
                return redirect()->back()->with('gagal','Paket yang kamu beli sudah dihapus');
            }

            $idPkgSave = $pkg[0]->id;
            $price = $pkg[0]->price;
        }else{
            // Pembelian dengan mode tertentu
            $pkgId = base64_decode($idPkg);
            $pkg = PackagePricing::where('id',$pkgId)->get();

            // Cek apakah paket tersedia
            if(count($pkg) == 0){
                return redirect()->back()->with('gagal','Paket yang kamu beli tidak tersedia');
            }

            // Cek apakah statusnya dihapus
            if($pkg[0]->deleted == 1){
                return redirect()->back()->with('gagal','Paket yang kamu beli sudah dihapus');
            }

            $idPkgSave = $pkg[0]->id;
            if(base64_decode($selectType) == 1){
                // Mode bulanan
                $price = $pkg[0]->price;
            }else{
                // Mode tahunan
                $price = $pkg[0]->price_in_year;
            }
        }

        // ----------------- Proses input ke databse ----------------------------------
        $status = 0;
        $snapToken = '-';
        if($price == 0){
            $status = 1;
        }else{
            //----------------- Pendaftaran pembayaran ke midtrans --------------------
            $params = array(
                'transaction_details' => array(
                    'order_id' => $orderId,
                    'gross_amount' => $price,
                ),
                "item_details" => array([                   
                    'id' => '01',
                    'price' => $price,
                    'quantity' => 1,
                    'name' => 'ID beli : '.$orderId,
                ]),
                'customer_details' => array(
                    'first_name' => $myData->name,                        
                    'email' => $myData->email,
                    'phone' => $myData->phone,
                ),
            );

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $status = 0;
            // --------------------------------------------------
        }

        $datasave = [
            'user_id' => $myData->id,
            'pkg_id' => $idPkgSave,
            'order_id' => $orderId,
            'token_trx'=> $snapToken,
            'nominal'=> $price,
            'status' => $status
        ];

        if($status == 0){
            PackagePayment::create($datasave);
        }else if($status == 1 && $myData->pkg_status == 0){
            PackagePayment::create($datasave);
        }

        // Update data user mengganti pkg_id dan status package aktif tidaknya
        if($status == 1 && $myData->pkg_status == 0){
            $now = new DateTime();
            User::where('id',$myData->id)->update([
                'pkg_id'=> $idPkgSave,
                'pkg_status'=> $status,
                'created_at' => $now,
            ]);
        }

        return redirect()->back()->with('berhasil','Segera tuntaskan tagihanmu');

    }

    // --------- function untuk konfirmasi pembayaran dan auto change user package status -------------
    public static function confirmUserPackage()
    {
        
        $myData = UserController::me();
        $packagesBuyed = $myData->packagePayments;
        $lastPackage = $packagesBuyed[count($packagesBuyed)-1];
        if($lastPackage->status == 0){
            // Jika statusnya belum terbayar
            $curl = curl_init();
			// Memindahkan value variable cURL ke agendakota.comfig per tgl 14 Mey 2022
	        curl_setopt_array($curl, array(
                CURLOPT_URL => config('agendakota')['midtrans_config']['main_url'].'v2/'.$lastPackage->order_id.'/status',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => config('agendakota')['midtrans_config']['CURLOPT_HTTPHEADER'],
                )
            );

	        $output = curl_exec($curl);

	        curl_close($curl);

	        $dataFromJson = json_decode($output, true);
	            
	        if($dataFromJson != null){
	            $payState = $dataFromJson["status_code"];

	            if($payState == 200 || $payState == "200"){
	                $dataUpdate = [
	                    'status' => 1
	                ];
                    #--------------------------------------------------------------------------
                    // Aturan untuk menentukan created_at pembaruan setelah pembayaran berhasil
                    // 1. Jika ID paket yang dibeli sama / melakukan beli lagi / melakukan pembelian sebelum jatuh tempo,
                    //    Maka masa aktif yang tersisa akan ditambkan dengan masa aktif yan terbaru
                    // 2. Masa aktif ditentukan dari tipe harga (menggunakan price atau price_in_year)
                    // 3. Penambahan masa aktif tidak berlaku untuk pembelian yang berbeda ID paketnya.

                    $newCreatedAt = '';
                    // Paket yang dibeli sebelumnya
                    $pastPkg = PackagePayment::where('user_id',$myData->id)->where('status',1)->orderBy('id','DESC')->first();

                    if($pastPkg != null){
                        if($pastPkg->pkg_id == $lastPackage->pkg_id){
                            // Akumulasikan masa aktif paket
                            if($myData->pkg_status == 1){
                                $userTime = new DateTime($myData->created_at);
                                $limitTime = 30;
                                if($pastPkg->nominal == $pastPkg->package->price_in_year){
                                    // Jika iya, terhitung limitTime = 365
                                    $limitTime = 365;
                                }
                                $newCreatedAt = $userTime->modify('+'.($limitTime).' day');
                                // dd($newCreatedAt,'case 1');
                            }else{
                                $now = new DateTime();
                                $newCreatedAt = $now;
                                // dd($newCreatedAt,'case 2');
                            }
                        }else{
                            // Ganti masa aktif menjadi mengikuti paket yang baru
                            $now = new DateTime();
                            $newCreatedAt = $now;
                            // dd($newCreatedAt,'case 3');
                        }
                    }else{
                        // Ganti masa aktif menjadi mengikuti paket yang baru
                        $now = new DateTime();
                        $newCreatedAt = $now;
                        // dd($newCreatedAt,'case 3');
                    }
                    // dd($newCreatedAt,'case 4');
                    #--------------------------------------------------------------------------
	                // Update status pembayaran user package
                    PackagePayment::where('id',$lastPackage->id)->update($dataUpdate);
                    // Update status active package di table user menjadi aktif dan update created_at user
                    // Created_at table user adalah parameter tanggal awal pembelian paket
                    // Untuk mengetahui masa akhir dilakukan oleh function self::limitCalculator($userData)
                    
                    User::where('id',$myData->id)->update([
                        'pkg_id' => $lastPackage->pkg_id,
                        'pkg_status'=>1,
                        // Created_at bisa bervariasi tergantung masa aktif sebelumnya
                        'created_at' => $newCreatedAt,
                    ]);
	            }
            }
        }
        return $lastPackage;
    }

    public function upgradePage()
    {
        $isPkgActive = self::limitCalculator(UserController::me());
        $myData = UserController::me();
        $pkgList = PackagePricing::where('deleted',0)->get();
        
        // Cek status pembayaran paket yang dibeli user
        $lastPaymentPkg = self::confirmUserPackage();
        $lastPackage = $lastPaymentPkg->package;

        //--------------------------- Midtran setup -----------------------------------------------------
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
        // ---------------------------------------------------------------------------------------


        return view('user.package-upgrade',[
            'myData'=>$myData,
            'myPaymentPkg' => $lastPaymentPkg,
            'pkgList' => $pkgList,
            'isPkgActive' => $isPkgActive,
            'CLIENT_KEY' => \Midtrans\Config::$clientKey,
        ]);
    }

    public function changeActivePkg(Request $request)
    {
        $interval = $request->interval;
        $userID = $request->user_id;
        if($interval == null || $userID == null){
            return redirect()->back()->with('gagal','Masukkan data yang benar');
        }
        $userData = User::where('id',$userID)->first();
        if($userData == null){
            return redirect()->back()->with('gagal','Masukkan data yang benar');
        }

        $this->validate($request,[
            'interval' => 'required|numeric'
        ],[
            'numeric' => 'input harus berupa angka'
        ]);

        $currentStartActiveTime = new DateTime($userData->created_at);
        $currentInterval = 0;
        $lastUserPkgPaid = PackagePayment::where('user_id',$userData->id)->where('status',1)->orderBy('id','DESC')->first();
        
        if($lastUserPkgPaid == null){
            return redirect()->back()->with('gagal','User belum pernah bertransaksi');
        }

        if($lastUserPkgPaid->nominal == $lastUserPkgPaid->package->price){
            $currentInterval = 30;
        }else{
            $currentInterval = 365;
        }

        $newStartActiveTime = 0;
        // Proses mengubah masa aktif paket user
        if($interval > $currentInterval){
            // Menambahkan interval aktif paket
            $addTime = (int)$interval - $currentInterval;
            $newStartActiveTime = $currentStartActiveTime->modify('+'.$addTime.' day');
        }else if($interval < $currentInterval){
            // Mengurangi interval aktif paket
            $lessTime = $currentInterval-$interval;
            $newStartActiveTime = $currentStartActiveTime->modify('-'.$lessTime.' day');
        }
        else{
            return redirect()->back();
        }
        User::where('id',$userData->id)->update([
            'created_at' => $newStartActiveTime,
        ]);
        return redirect()->back()->with('berhasil','Masa aktif paket user berhasil diubah');
    }
    public function viewUserPkgPayment($userID)
    {
        $userData = User::where('id',$userID)->first();

        if($userData == null){
            return redirect()->back();
        }

        return view('admin.user_packages_detail',['myData' => $userData]);
    }
    public function store(Request $request)
    {
        $validateRule = [
            'name' => 'required',
            'description' => 'required',
            'organizer_count' => 'required|numeric',
            'event_same_time' => 'required|numeric',
            'ticket_commission' => 'required|numeric',
            'session_count' => 'required|numeric',
            'sponsor_count' => 'required|numeric',
            'exhibitor_count' => 'required|numeric',
            'partner_media_count' => 'required|numeric',
            'max_attachment' => 'required|numeric',
            'price' => 'required|numeric',
            'price_in_year' => 'required|numeric',
        ];

        $msg = [
            'required' => 'Semua kolom wajib diisi',
            'numeric' => 'Inputan batas fitur hanya berupa angka'
        ];

        $this->validate($request, $validateRule, $msg);

        $name = $request->name;
        $desc = $request->description;
        $orgCount = $request->organizer_count;
        $evtSameTime = $request->event_same_time;
        $commission = $request->ticket_commission;
        $sessionCount = $request->session_count;
        $customLink = $request->custom_link;
        $sponsorCount = $request->sponsor_count;
        $exhCount = $request->exhibitor_count;
        $mediaCount = $request->partner_media_count;
        $download = $request->report_download;
        $maxFile = $request->max_attachment;
        $price = $request->price;
        $yearPrice = $request->price_in_year;
        $deleted = 0;

        if($customLink == null){
            $customLink = 0;
        }else{
            $customLink = 1;
        }

        if($download == null){
            $download = 0;
        }else{
            $download = 1;
        }

        $commission = (float)$commission / 100;
        PackagePricing::create([
            'name' => $name,
            'description' => $desc,
            'organizer_count' => $orgCount,
            'event_same_time' => $evtSameTime,
            'ticket_commission' => $commission,
            'session_count' => $sessionCount,
            'custom_link' => $customLink,
            'sponsor_count' => $sponsorCount,
            'exhibitor_count' => $exhCount,
            'partner_media_count' => $mediaCount,
            'report_download' => $download,
            'max_attachment' => $maxFile,
            'price' => $price,
            'price_in_year' => $yearPrice,
            'deleted' => $deleted
        ]);

        return redirect()->back()->with('berhasil','Paket berhasil ditambahkan');

    }
    public function update($packageID, Request $request)
    {
        $pkg = PackagePricing::where('id',$packageID);
        if($pkg->first() == null){
            return redirect()->back()->with('gagal','Paket yang dimaksud tidak ditemukan');
        }

        $validateRule = [
            'name' => 'required',
            'description' => 'required',
            'organizer_count' => 'required|numeric',
            'event_same_time' => 'required|numeric',
            'ticket_commission' => 'required|numeric',
            'session_count' => 'required|numeric',
            'sponsor_count' => 'required|numeric',
            'exhibitor_count' => 'required|numeric',
            'partner_media_count' => 'required|numeric',
            'max_attachment' => 'required|numeric',
            'price' => 'required|numeric',
            'price_in_year' => 'required|numeric',
        ];

        $msg = [
            'required' => 'Semua kolom wajib diisi',
            'numeric' => 'Inputan batas fitur hanya berupa angka'
        ];

        $this->validate($request, $validateRule, $msg);

        $name = $request->name;
        $desc = $request->description;
        $orgCount = $request->organizer_count;
        $evtSameTime = $request->event_same_time;
        $commission = $request->ticket_commission;
        $sessionCount = $request->session_count;
        $customLink = $request->custom_link;
        $sponsorCount = $request->sponsor_count;
        $exhCount = $request->exhibitor_count;
        $mediaCount = $request->partner_media_count;
        $download = $request->report_download;
        $maxFile = $request->max_attachment;
        $price = $request->price;
        $yearPrice = $request->price_in_year;
        $deleted = 0;

        if($customLink == null){
            $customLink = 0;
        }else{
            $customLink = 1;
        }

        if($download == null){
            $download = 0;
        }else{
            $download = 1;
        }

        $commission = (float)$commission / 100;
        $pkg->update([
            'name' => $name,
            'description' => $desc,
            'organizer_count' => $orgCount,
            'event_same_time' => $evtSameTime,
            'ticket_commission' => $commission,
            'session_count' => $sessionCount,
            'custom_link' => $customLink,
            'sponsor_count' => $sponsorCount,
            'exhibitor_count' => $exhCount,
            'partner_media_count' => $mediaCount,
            'report_download' => $download,
            'max_attachment' => $maxFile,
            'price' => $price,
            'price_in_year' => $yearPrice,
            'deleted' => $deleted
        ]);

        return redirect()->back()->with('berhasil','Paket berhasil diubah');

    }
    public function delete($packageID)
    {
        $pkg = PackagePricing::where('id',$packageID);
        if($pkg->first() == null){
            return redirect()->back()->with('gagal','Paket yang dimaksud tidak ditemukan');
        }

        $pkg->update([
            'deleted' => 1,
        ]);

        return redirect()->back()->with('berhasil','Paket berhasil dihapus');        
    }
}

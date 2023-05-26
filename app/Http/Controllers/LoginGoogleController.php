<?php

namespace App\Http\Controllers;

use Auth;
use Firebase\JWT\JWT;
use Session;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\UserVerification;
use Illuminate\Support\Facades\Mail;
use App\Models\Invitation;
use App\Models\OrganizationTeam;
use App\Models\Ticket;
use App\Models\TicketCart;
use App\Models\PackagePayment;
use App\Models\PackagePricing;

class LoginGoogleController extends Controller
{
    // ===================== Basic login register function ============================
    public static function login(Request $request)
    {
        $user = User::where([
            'email' => $request->user->user['email'],
            'google_id' => $request->user->user['sub']
        ])->first();

        if (!$user) {
            // Lakukan register dengan basic function / update googlle ID user
            $userData = User::where([
                'email' => $request->user->user['email']
            ])->get();
            if(count($userData) > 0){
                // Update data user tambahkan google ID
                User::where('id', $userData[0]->id)->update([
                    'google_id' => $request->user->user['sub']
                ]);
                $user = User::where([
                    'email' => $request->user->user['email'],
                    'google_id' => $request->user->user['sub']
                ])->first();
            }else{
                $request->name = $request->user->name;
                $request->email = $request->user->email;
                $request->gId = $request->user->user['sub'];
                return self::register($request);
            }
        }

        $loggingIn = Auth::guard('user')->login($user);

        $myData = UserController::me();

         // Menghubungi express server untuk login
         $url = env('STREAM_SERVER').'/api/v1/login-google';
         $payload = [
            'email' => $myData->email,
            'g_id' => $request->user->user['sub'],
            'exp' => time() + (1440 * 60)
         ];
         $json = json_encode([
             "credential"=> JWT::encode($payload, env('JWT_SIGNATURE_KEY'), env('JWT_ALG')),
         ]);

         $curl = curl_init();
        //  curl_setopt($curl, CURLOPT_URL, $url);
        //  curl_setopt($curl, CURLOPT_POST, true);
        //  curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: application/json"));
        //  curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        //  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$json,
            CURLOPT_HTTPHEADER => array(
              'Content-Type: application/json'
            ),
          ));
          
         
         $resp = curl_exec($curl);
         curl_close($curl);

        //  dd(json_decode($resp), $json, $request->user);

        if(Session::has('x-access-token')) Session::forget('x-access-token');
        Session::put('x-access-token', json_decode($resp)->token);
        
        if (Session::has('ticketBuyed')) {
            // dd($loggingIn);
            $tickets = session('ticketBuyed');
            for ($i = 0; $i < count($tickets); $i++) {
                $checkTicket = Ticket::where('id', $tickets[$i])->get();
                if (count($checkTicket) > 0) {
                    $dataSave = [
                        'user_id' => $myData->id,
                        'ticket_id' => $tickets[$i],
                        'status' => 'notPay',
                    ];

                    $checkDouble = TicketCart::where('user_id', $myData->id)->where('ticket_id', $tickets[$i])->where('status', 'notPay')->get();
                    if (count($checkDouble) == 0) {
                        TicketCart::create($dataSave);
                    }
                }
            }
        }

        if ($myData->is_active == 0) {
            $logout = Auth::guard('user')->logout();
            return redirect()->back()->withErrors(['Mohon klik link aktivasi pada email terlebih dahulu sebelum melanjutkan']);
        }

        if (session('redirectTo') != "") {
            Session::forget('redirectTo');
            return redirect()->route(session('redirectTo'));
        }

        // return redirect()->route('user.organization');
        if (Session::has('ticketBuyed')) {
            Session::forget('ticketBuyed');
            return redirect()->route('user.myTickets');
        }else if(Session::has('redirect-page')){
            Session::forget('redirect-page');
            return redirect()->route('create-event');
        } else {
            if (Session::has('navTo')) {
                if (session('navTo') == 'myTickets') {
                    Session::forget('navTo');
                    return redirect()->route('user.myTickets');
                }
                return redirect()->route('user.events');
            } else {
                return redirect()->route('user.events');
            }
        }
    }

    public static function register(Request $request)
    {
        // ------ Ambil data ID package ---------------------------
        // Lakukan pembelian paket default
        // Pilih ID satu jika tidak ada parameter Id paket yang di input

        $pkgID = session('packageID');

        $pkgStatus = 0;
        $price = 0;
        if ($pkgID == null) {
            $pkg = PackagePricing::where('deleted', 0)->first();
            $pkgID = $pkg->id;
            $price = $pkg->price;
        } else {
            $pkg = PackagePricing::where('id', $pkgID)->get();
            if (count($pkg) == 1) {
                if ($pkg[0]->deleted == 0) {
                    $pkgID = $pkg[0]->id;
                    $price = $pkg[0]->price;
                } else {
                    $pkg = PackagePricing::where('deleted', 0)->first();
                    if ($pkg == null) {
                        return redirect()->back()->withErrors(['Admin belum membuat paket']);
                    }
                    $pkgID = $pkg->id;
                    $price = $pkg->price;
                }
            } else {
                $pkg = PackagePricing::where('deleted', 0)->first();
                if ($pkg == null) {
                    return redirect()->back()->withErrors(['Admin belum membuat paket']);
                }
                $pkgID = $pkg->id;
                $price = $pkg->price;
            }
        }
        Session::forget('packageID');
        $saveData = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt("loginWithGoogle_AgendaKt2022-1012"),
            'photo' => 'default',
            'is_active' => 0,
            'pkg_id' => $pkgID,
            'pkg_status' => $pkgStatus,
            'google_id' => $request->gId
        ]);

        #--------------- Memasukkan data pembelian paket ke database dan midtrans ------------------

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
        do {
            // Lakukan generate orderID
            $random = new RandomStrController();
            $orderId = $random->get();
        } while (count(PackagePayment::where('order_id', $orderId)->get()) > 0);


        // ---------------------------------------------------------------------------------------


        // ----------------- Proses input ke databse pembelian paket ----------------------------------
        $status = 0;
        $snapToken = '-';
        if ($price == 0) {
            $status = 1;
        } else {
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
                    'name' => 'ID beli : ' . $orderId,
                ]),
                'customer_details' => array(
                    'first_name' => $saveData->name,
                    'email' => $saveData->email,
                    'phone' => $saveData->phone,
                ),
            );

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $status = 0;
            // --------------------------------------------------
        }

        $datasave = [
            'user_id' => $saveData->id,
            'pkg_id' => $pkgID,
            'order_id' => $orderId,
            'token_trx' => $snapToken,
            'nominal' => $price,
            'status' => $status
        ];

        PackagePayment::create($datasave);

        // Update data user mengganti pkg_id dan status package aktif tidaknya
        User::where('id', $saveData->id)->update([
            'pkg_status' => $status,
        ]);
        // dd($saveData);
        // --------------------------------------------------------
        #-------------------------------------------------------------------------------------------

        $organizationID = session('organizationID');
        if (Session::has('organizationID')) {
            OrganizationTeam::create([
                'user_id' => $saveData->id,
                'organization_id' => $organizationID,
                'role' => "member"
            ]);
            Session::forget('organizationID');
        }
        
        if (Session::has('ticketID')) {
            $ticket = TicketController::get([['id', '=', session('ticketID')]])->first();
            $event = EventController::get([['name', '=', session('event_name')]])->first();
            // dd($event);
            if($saveData->email == session('email')){
                Invitation::create([
                    'sender' => session('senderID'),
                    'event_id' => $event->id,
                    'receiver' => $saveData->id,
                    'purchase_id' => session('purchase'),
                    'ticket_id' => $ticket->id,
                    'buy_ticket' => 1
                ]);
            }
            Session::forget('ticketID');
            Session::forget('event_name');
            Session::forget('senderID');
            Session::forget('purchase');
        }
        if (Session::has('ticketBuyed')) {
            // dd($loggingIn);
            $tickets = session('ticketBuyed');
            for ($i = 0; $i < count($tickets); $i++) {
                $checkTicket = Ticket::where('id', $tickets[$i])->get();
                if (count($checkTicket) > 0) {
                    $dataSave = [
                        'user_id' => $saveData->id,
                        'ticket_id' => $tickets[$i],
                        'status' => 'notPay',
                    ];

                    $checkDouble = TicketCart::where('user_id', $saveData->id)->where('ticket_id', $tickets[$i])->where('status', 'notPay')->get();
                    if (count($checkDouble) == 0) {
                        TicketCart::create($dataSave);
                    }
                }
            }
        }

        $user['name'] = $request->name;
        $user['email'] = $request->email;
        $user['link_verification'] = route("user.emailVerification", [base64_encode($user['email']), 'navTo' => 'myTickets']);

        Mail::to($request->email)->send(new UserVerification($user));

        if (Session::has('ticketBuyed')) {
            Session::forget('ticketBuyed');
            return redirect()->route('user.loginPage', ['navTo' => 'myTickets'])->with('berhasil', 'Mohon Cek Email Untuk Aktivasi Akun');
        } else {
            return redirect()->route('user.loginPage')->with('berhasil', 'Mohon Cek Email Untuk Aktivasi Akun');
        }
    }
    // ================================================================================

    public function redirectToProvider($provider, $param = null)
    {
        if($param == 'create-event'){
            session(['redirect-page' => $param]);
        }
        return Socialite::driver($provider)->redirect();
    }
    

    public function handleProvideCallback(Request $request, $provider)
    {
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            return redirect()->back();
        }

        $request->user = $user;
        return self::login($request);
    }
}

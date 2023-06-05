<?php

namespace App\Http\Controllers;

use Auth;
use Firebase\JWT\JWT;
use Session;
use App;
use File;
use Image;
use Str;
use App\Http\Controllers\HomePageController;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\User;
use App\Mail\ForgotPassword;
use Illuminate\Http\Request;
use App\Mail\UserVerification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\OrganizationController;
use App\Models\Invitation;
use App\Models\Connection;
use App\Models\Event;
use App\Models\Organization;
use App\Models\OrganizationTeam;
use App\Models\Purchase;
use App\Models\Ticket;
use App\Models\TicketCart;
use App\Models\Speaker;
use App\Models\Exhibitor;
use App\Models\PackagePayment;
use App\Models\PackagePricing;
use App\Models\Payment;
use App\Models\TempTicketShare;
use App\Models\SessionSpeaker;
use App\Models\TicketPurchase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public $isManageEvent = false;

    public static function get($filter = NULL)
    {
        if ($filter == NULL) {
            return new User;
        }
        return User::where($filter);
    }

    public static function getOrganizations($userID)
    {
        return OrganizationController::get([
            ['user_id', '=', $userID]
        ]);
    }

    public static function getorganizationsTeam($userID)
    {
        return OrganizationTeamController::get([
            ['user_id', '=', $userID]
        ])->with('organizations');
    }
    public static function getNotifyInvitation($userID)
    {
        $invitations = Invitation::where('receiver', $userID)->get();

        $totalInvite = count($invitations);
        foreach ($invitations as $invitation) {
            if ($invitation->response == true) {
                $totalInvite = $totalInvite - 1;
            }
        }

        return $totalInvite;
    }

    public static function me()
    {
        // dd(Auth::guard('user')->user());
        $myData = Auth::guard('user')->user();
        if ($myData != "") {
            $myData->organization_types = \App\Models\OrganizationType::orderBy('name', 'ASC')->get();
            $myData->organization_interests = \App\Models\Category::orderBy('name', 'ASC')->get();
            if ($myData->token == null) {
                // Creating token
                $token = Str::random(32);
                $creatingToken = User::where('id', $myData->id)->update([
                    'token' => $token
                ]);
                $myData->token = $token;
            }
        }

        return $myData;
    }

    public static function myId()
    {
        return Auth::guard('user')->id();
    }

    public static function isSpeaker($idEvent = null)
    {
        $myData = Auth::guard('user')->user();
        if ($idEvent == null) {
            // return Speaker::where('email', $myData->email)->get();
            $speakers = Speaker::where('email', $myData->email)->get();
            $sessionsSpeaker = [];
            for ($i = 0; $i < count($speakers); $i++) {
                $tmp = SessionSpeaker::where('speaker_id', $speakers[$i]->id)->where('deleted', 0)->get();
                for ($j = 0; $j < count($tmp); $j++) {
                    array_push($sessionsSpeaker, $tmp[$j]);
                }
            }
            // dd($sessionsSpeaker);
            return $sessionsSpeaker;
        }
        return Speaker::where('email', $myData->email)->where('event_id', $idEvent)->get();
    }

    public static function isExihibitor($idEvent = null)
    {
        $myData = self::me();
        // dd($myData);
        if ($idEvent == null) {
            return Exhibitor::where('email', $myData->email)->get();
        }
        return Exhibitor::where('email', $myData->email)->where('event_id', $idEvent)->get();
    }

    public function emailVerification($email, Request $request)
    {
        $email = base64_decode($email);
        User::where('email', $email)->update(['is_active' => 1]);
        if ($request->get('navTo') !== null) {
            return redirect()->route('user.loginPage', ['navTo' => 'myTickets'])->with('berhasil', 'Selamat! Email anda berhasil divalidasi, Silahkan melakukan login dihalaman login');
        } else {
            return redirect()->route('user.loginPage')->with('berhasil', 'Selamat! Email anda berhasil divalidasi, Silahkan melakukan login dihalaman login');
        }
    }
    public function loginPage(Request $request)
    {
        $message = Session::get('message');
        session([
            'redirectTo' => $request->redirect_to,
            'navTo' => $request->get('navTo'),
        ]);
        return view('user.login', [
            'message' => $message,
            //redirect to??
            'redirectTo' => $request->redirect_to,
            'navTo' => $request->get('navTo'),
        ]);
    }
    public function login2Page(Request $request)
    {
        // dd($arrIDTicket);
        // dd($request->get('myArray'));
        $message = Session::get('message');
        $arrIDTicket = $request->get('myArray');
        session([
            'ticketBuyed' => $arrIDTicket,
            'redirectTo' => $request->redirect_to,
        ]);
        return view('user.login', [
            'message' => $message,
            'ticketBuyed' => $arrIDTicket,
            'redirectTo' => $request->redirect_to,
            'navTo' => null,
        ]);
    }
    public function registerPage(Request $request)
    {
        $message = Session::get('message');

        $tickets = null;
        if ($request->get('myArray') !== null) {
            $tickets = $request->get('myArray');
        }
        // dd($message);
        session([
            'ticketBuyed' => $tickets,
            'packageID' => $request->pkg_id,
        ]);
        return view('user.register', [
            'message' => $message,
            'ticketBuyed' => $tickets,
            'packageID' => $request->pkg_id,
        ]);
    }

    public function profilePage()
    {
        $myData = self::me();
        $users = User::where('id', $myData->id)->get();
        $totalInvite = $this->getNotifyInvitation($myData->id);

        return view('user.profile', [
            'users' => $users,
            'myData' => $myData,
            'totalInvite' => $totalInvite,
            'organizationID' => 0,
            'eventID' => 0,
            'isExhibitor' => self::isExihibitor(),
            'isSpeaker' => self::isSpeaker(),
        ]);
    }

    public function updateProfile($userId, Request $request)
    {
        $myData = UserController::me();
        $message = [
            'required' => 'Kolom :Attribute Wajib Diisi',
            'numeric' => 'Nomor Telepon Harus Diisi Dengan Angka',
            'active_url' => ':Attribute Harus Diisi Dengan URL ',
            'mimes' => 'Foto harus berformat jpg, jpeg, atau png',
            'max' => 'File upload maksimal ' . $myData->package->max_attachment . 'Mb',
        ];
        $validateData = $this->validate($request, [
            'name' => 'required',
            'bio' => 'required',
            'headline' => 'required',
            'phone' => 'numeric|digits_between:11,15',
            'instagram_profile' => 'active_url|nullable',
            'twitter_profile' => 'active_url|nullable',
            'linkedin_profile' => 'active_url|nullable',
            'photo' => 'mimes:jpg,png,jpeg|max:' . ($myData->package->max_attachment * 1024),
        ], $message);

        //logo
        $photo = $request->file('photo');
        $lama = User::find($userId);
        $savephoto = "";
        if ($photo) {
            // Storage::delete(['/public/profile_photo/'.$lama->photo]);
            // $photoFileName = $photo->getClientOriginalName();
            // $savephoto = $userId.'_'.$photoFileName;
            // $photo->storeAs('public/profile_photo/', $savephoto);
            if ($lama->photo != 'default') {
                File::delete('storage/profile_photos/' . $lama->photo);

                $photoName = $photo->getClientOriginalName();

                $filePath = public_path('storage/profile_photos');
                $img = Image::make($photo->path());
                $img->save($filePath . '/' . $photoName);

                $savephoto = $photoName;
            } else {
                $photoName = $photo->getClientOriginalName();

                $filePath = public_path('storage/profile_photos');
                $img = Image::make($photo->path());
                File::makeDirectory($filePath, $mode = 0777, true, true);

                $img->save($filePath . '/' . $photoName);

                $savephoto = $photoName;
            }
        } else {
            $savephoto = $lama->photo;
        }

        $saveData = User::where('id', $userId)->update([
            'photo' => $savephoto,
            'name' => $request->name,
            'bio' => $request->bio,
            'headline' => $request->headline,
            'phone' => $request->phone,
            'instagram_profile' => $request->instagram,
            'twitter_profile' => $request->twitter,
            'linkedin_profile' => $request->linkedin,
        ]);

        return redirect()->back()->with('berhasil', 'Akun Anda Telah Berhasil Diperbaharui');
    }

    public function updateProfilePassword($userId, Request $request)
    {
        $users = User::where('id', $userId)->get();
        $validateData = $this->validate($request, [
            'password_lama' => 'required',
            'password_baru' => 'required',
            'repassword_baru' => 'required',
        ]);

        if ($request->password_baru == $request->repassword_baru && count($users) != 0) {
            foreach ($users as $user) {
                // $loggingIn = Auth::guard('user')->attempt([
                //     'email' => $users->email,
                //     'password' => $request->password,
                // ]);
                // dd($users, $request, Hash::make($request->password_lama), $user->password ,Hash::check($request->password_lama, $user->password));
                if (Hash::check($request->password_lama, $user->password)) {

                    $saveData = User::where('id', $userId)->update([
                        'password' => bcrypt($request->repassword_baru)
                    ]);
                    return redirect()->back()->with('berhasil', 'Password Akun Anda Telah Berhasil Diperbaharui');
                } else {
                    return redirect()->back()->withErrors('Password Lama Yang Anda Masukkan Salah');
                }
            }
        } else {
            return redirect()->back()->withErrors('Password Yang dimasukkan Tidak Sama / User tidak terdaftar');
        }
    }

    public function forgotPasswordPage(Request $request)
    {
        $message = Session::get('message');

        return view('user.forgotPassword', [
            'message' => $message,
            'redirectTo' => $request->redirect_to,
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $user['email'] = $request->email;
        $user['link_verification'] = route("user.resetPasswordPage", base64_encode($user['email']));

        Mail::to($request->email)->send(new ForgotPassword($user));

        return redirect()->route('user.loginPage')->with('berhasil', 'Mohon Cek Email Untuk Reset Password Akun Anda');
    }

    public function resetPasswordPage($email, Request $request)
    {
        $email = base64_decode($email);
        $user = User::where('email', $email)->first();
        if ($user == "") {
            die("404 user not found");
        }
        $message = Session::get('message');

        return view('user.resetPassword', [
            'message' => $message,
            'email' => $email,
            'redirectTo' => $request->redirect_to,
        ]);
    }

    public function updatePassword(Request $request)
    {

        $user = User::where('email', $request->email)->update(['password' => bcrypt($request->passwordBaru)]);

        return redirect()->route('user.loginPage')->with('berhasil', 'Password anda telah diperbarui, mohon login kembali ke halaman login');
    }

    public function login(Request $request)
    {
        // =================================== Hapus session untuk login with google ========================================
        if (Session::has('ticketBuyed')) {
            Session::forget('ticketBuyed');
        }
        if (Session::has('redirectTo')) {
            Session::forget('redirectTo');
        }
        if (Session::has('navTo')) {
            Session::forget('navTo');
        }
        // ==================================================================================================================

        $validateData = $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
            // 'g-recaptcha-response' => 'required|captcha',
        ]);

        $loggingIn = Auth::guard('user')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // dd($loggingIn, Hash::make($request->password));
        if (!$loggingIn) {
            return redirect()->back()->withErrors(['Email atau kata sandi salah']);
        }

        $myData = self::me();

        // Menghubungi express server untuk login
        $url = env('STREAM_SERVER') . '/api/v1/login';
        $json = json_encode([
            "email" => $myData->email,
            "password" => $request->password
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
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        )
        );


        $resp = curl_exec($curl);
        curl_close($curl);

        //  dd(json_decode($resp), $json);
        if(Session::has('x-access-token')) Session::forget('x-access-token');
        Session::put('x-access-token', json_decode($resp)->token);
        // dd(session('x-access-token'));

        if (isset($request->ticket)) {
            // dd($loggingIn);
            $tickets = $request->ticket;
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

        if ($request->redirectTo != "") {
            return redirect()->route($request->redirectTo);
        }

        // return redirect()->route('user.organization');
        if (isset($request->ticket)) {
            return redirect()->route('user.myTickets');
        } else {
            if (isset($request->navTo)) {
                if ($request->navTo == 'myTickets') {
                    return redirect()->route('user.myTickets');
                }
                return redirect()->route('user.events');
            } else {
                return redirect()->route('user.events');
            }
        }
    }
    public function register(Request $request)
    {
        // ============================ Hapus session data untuk register with google ===================================
        if (Session::has('organizationID')) {
            Session::forget('organizationID');
        }
        if (Session::has('ticketID')) {
            Session::forget('ticketID');
            Session::forget('event_name');
            Session::forget('senderID');
            Session::forget('purchase');
        }
        if (Session::has('ticketBuyed')) {
            Session::forget('ticketBuyed');
        }
        // ==============================================================================================================

        $messages = [
            'required' => ':Attribute Wajib Diisi',
            'email' => 'Harap Masukan Format Email yang benar',
            'min' => ':Attribute Diisi Minimal :min Karakter',
            'max' => ':Attribute Diisi Minimal :max Karakter',
            'captcha' => 're-Captcha Wajib diisi',
        ];
        $withGoogle = $request->with_google;
        $toValidate = [
            'name' => 'required|max:255',
            'email' => 'required|email|min:5|max:255|unique:users',
            'password' => 'required|min:6',
        ];
        if ($withGoogle != "1") {
            $toValidate['g-recaptcha-response'] = 'required|captcha';
        } else {
            $checkEmail = User::where('email', $request->email)->first();
            if ($checkEmail != "") {
                return response()->json([
                    'status' => 1,
                    'message' => "Akun dengan email " . $request->email . " telah terdaftar. Mohon login menggunakan email tersebut"
                ]);
            }
        }
        $validateData = $this->validate($request, $toValidate, $messages);

        if ($withGoogle != 1 && ($request->password != $request->repeat_password)) {
            return redirect()->back()->withErrors(['Konfirmasi password tidak sesuai']);
        }

        // ------ Ambil data ID package ---------------------------
        // Lakukan pembelian paket default
        // Pilih ID satu jika tidak ada parameter Id paket yang di input
        $pkgID = $request->pkgID;

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

        $saveData = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'photo' => 'default',
            'is_active' => $withGoogle == 1 ?: 0,
            'pkg_id' => $pkgID,
            'pkg_status' => $pkgStatus,
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
                "item_details" => array(
                    [
                        'id' => '01',
                        'price' => $price,
                        'quantity' => 1,
                        'name' => 'ID beli : ' . $orderId,
                    ]
                ),
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

        $organizationID = $request->organizationID;
        if (isset($organizationID)) {
            OrganizationTeam::create([
                'user_id' => $saveData->id,
                'organization_id' => $organizationID,
                'role' => "member"
            ]);
        }

        if (isset($request->ticketID)) {
            $ticket = TicketController::get([['id', '=', $request->ticketID]])->first();
            $event = EventController::get([['name', '=', $request->event]])->first();
            // dd($event);
            Invitation::create([
                'sender' => $request->senderID,
                'event_id' => $event->id,
                'receiver' => $saveData->id,
                'purchase_id' => $request->purchaseID,
                'ticket_id' => $ticket->id,
                'buy_ticket' => 1
            ]);
        }
        if (isset($request->ticket)) {
            // dd($loggingIn);
            $tickets = $request->ticket;
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

        if ($withGoogle == 1) {
            return response()->json(['status' => 200]);
        } else {
            $user['name'] = $request->name;
            $user['email'] = $request->email;
            $user['link_verification'] = route("user.emailVerification", [base64_encode($user['email']), 'navTo' => 'myTickets']);

            Mail::to($request->email)->send(new UserVerification($user));

            if (isset($request->ticket)) {
                return redirect()->route('user.loginPage', ['navTo' => 'myTickets'])->with('berhasil', 'Mohon Cek Email Untuk Aktivasi Akun');
            } else {
                return redirect()->route('user.loginPage')->with('berhasil', 'Mohon Cek Email Untuk Aktivasi Akun');
            }
        }
    }
    public function register2(Request $request)
    {
        $ticketID = $request->ticketID;
        session(['ticketID' => $ticketID]);
        return redirect()->route('user.register')->with('ticketID');
    }
    public function resetPassword(Request $request)
    {
        $messages = [
            'required' => ':Attribute Wajib Diisi',
            'min' => ':Attribute Diisi Minimal :min Karakter',
            'max' => ':Attribute Diisi Minimal :max Karakter',
        ];
        $validateData = $this->validate($request, [
            'passwordBaru' => 'required|min:6',
            'reenterPassword' => 'required|same:passwordBaru'
        ], $messages);

        $updateData = User::updated([
            'password' => bcrypt($request->passwordBaru)
        ]);

        return redirect()->route('user.loginPage')->with('berhasil', 'Mohon Cek Email Untuk Aktivasi Akun');
    }

    public function logout()
    {
        $loggingOut = Auth::guard('user')->logout();

        return redirect()->route('user.loginPage')->with([
            'message' => "Berhasil logout"
        ]);
    }

    public function index()
    {
        return redirect()->route('user.loginPage');
    }
    public function events()
    {
        $organizationID = "";
        $organization = Organization::where('id', $organizationID)->where('deleted', 0)->with('events')->first();
        $eventID = "";
        $myData = self::me();
        $purchases = Purchase::where('user_id', $myData->id)->groupBy('event_id')->with(['users', 'tickets', 'events.organizer'])->get();
        // dd($purchases, Purchase::where('user_id', $myData->id)->with(['users','tickets','events.organizer'])->get()->groupBy('event_id'));
        $totalInvite = $this->getNotifyInvitation($myData->id);
        return view('user.event', [
            'myData' => $myData,
            'purchases' => $purchases,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'totalInvite' => $totalInvite,
            'eventID' => $eventID,
            'isExhibitor' => self::isExihibitor(),
            'isSpeaker' => self::isSpeaker(),
        ]);
    }

    public function selfCheckin()
    {
        $organizationID = "";
        $organization = Organization::where('id', $organizationID)->where('deleted', 0)->with('events')->first();
        $eventID = "";
        $myData = self::me();
        $purchases = Purchase::where('user_id', $myData->id)->groupBy('event_id')->with(['users', 'tickets', 'events.organizer'])->get();
        // dd($purchases, Purchase::where('user_id', $myData->id)->with(['users','tickets','events.organizer'])->get()->groupBy('event_id'));
        $totalInvite = $this->getNotifyInvitation($myData->id);
        return view('user.self-checkin', [
            'myData' => $myData,
            'purchases' => $purchases,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'totalInvite' => $totalInvite,
            'eventID' => $eventID,
            'isExhibitor' => self::isExihibitor(),
            'isSpeaker' => self::isSpeaker(),
        ]);
    }

    public function connections()
    {
        $myId = self::myId();
        $organizationID = "";
        $eventID = "";
        $events = Event::get();
        $myData = self::me();
        $totalInvite = $this->getNotifyInvitation($myData->id);
        $connection = Connection::where('user_id', $myId)->get();
        $users = User::get();

        return view('user.connection', [
            'myData' => $myData,
            'organizationID' => $organizationID,
            'totalInvite' => $totalInvite,
            'eventID' => $eventID,
            'connection' => $connection,
            'users' => $users,
            'events' => $events,
            'isExhibitor' => self::isExihibitor(),
            'isSpeaker' => self::isSpeaker(),
        ]);
    }
    public function invitations()
    {
        $organizationID = "";
        $eventID = "";
        $myData = self::me();
        $invitations = Invitation::where('receiver', $myData->id)->with(['senders', 'tickets', 'events.organizer'])->orderBy('created_at', 'DESC')->get();
        $totalInvite = $this->getNotifyInvitation($myData->id);
        return view('user.invitation', [
            'myData' => $myData,
            'invitations' => $invitations,
            'organizationID' => $organizationID,
            'eventID' => $eventID,
            'totalInvite' => $totalInvite,
            'isExhibitor' => self::isExihibitor(),
            'isSpeaker' => self::isSpeaker(),
        ]);
    }

    public function profile()
    {
        $myData = self::me();
        $totalInvite = $this->getNotifyInvitation($myData->id);
        return view('user.profile', [
            'myData' => $myData,
            'isManageEvent' => $this->isManageEvent,
            'totalInvite' => $totalInvite,
            'isExhibitor' => self::isExihibitor(),
            'isSpeaker' => self::isSpeaker(),
        ]);
    }

    public function messages(Request $request)
    {
        $msgTo = 0;
        if (isset($request['msgTo'])) {
            $msgTo = $request['msgTo'];
        }
        $myData = self::me();
        $myId = self::myId();
        $user = User::where('id', '!=', $myId)->get();
        $connection = Connection::where('user_id', '=', $myId)->get();
        $totalInvite = $this->getNotifyInvitation($myData->id);
        return view('user.messages', [
            'myData' => $myData,
            'totalInvite' => $totalInvite,
            'user' => $user,
            'connection' => $connection,
            'clickForm' => $msgTo,
            'isExhibitor' => self::isExihibitor(),
            'isSpeaker' => self::isSpeaker(),
        ]);
    }

    public function myOrganization()
    {
        $myId = self::myId();
        $organizationID = "";
        $eventID = "";
        $events = Event::get();
        $myData = self::me();
        $totalInvite = $this->getNotifyInvitation($myData->id);
        $organization = Organization::where('user_id', $myId)->where('deleted', 0)->get();
        $objOrganizationTeam = OrganizationTeam::where('user_id', $myId)->get();
        // if (count($organization) == 0) {
        //     $objOrganizationTeam = OrganizationTeam::where('user_id', $myId)->get();
        //     $organization = [];
        //     for ($i = 0; $i < count($objOrganizationTeam); $i++) {
        //         $objOrganization = Organization::where('id', $objOrganizationTeam[$i]->organization_id)->get();
        //         for ($j = 0; $j < count($objOrganization); $j++) {
        //             $organization[$j] = $objOrganization[$j];
        //         }
        //     }
        // }
        // dd($organization);
        return view('user.organization', [
            'myData' => $myData,
            'organizationID' => $organizationID,
            'totalInvite' => $totalInvite,
            'eventID' => $eventID,
            'organization' => $organization,
            'organizationTeam' => $objOrganizationTeam,
            'events' => $events,
            'isExhibitor' => self::isExihibitor(),
            'isSpeaker' => self::isSpeaker(),
        ]);
    }

    public function organizer()
    {
        return view('organizer.index', [
            'isManageEvent' => $this->isManageEvent
        ]);
    }
    public function OrganizerEvent()
    {
        $this->isManageEvent = true;

        return view('organizer.event', [
            'isManageEvent' => $this->isManageEvent,
            'isExhibitor' => self::isExihibitor(),
            'isSpeaker' => self::isSpeaker(),
        ]);
    }
    public function organization()
    {
        $myData = self::me();
        $organizations = self::getOrganizations($myData->id);
        $organizationsTeam = self::getorganizationsTeam($myData->id)->get();
        $totalInvite = $this->getNotifyInvitation($myData->id);
        return view('user.organization', [
            'organizations' => $organizations,
            'organizationsTeam' => $organizationsTeam,
            'totalInvite' => $totalInvite,
            'myData' => $myData,
            'isExhibitor' => self::isExihibitor(),
            'isSpeaker' => self::isSpeaker(),
        ]);
    }
    // public function myTicketsNew()
    // {
    //     $myData = self::me();
    //     $totalInvite = $this->getNotifyInvitation($myData->id);
    //     $purchases = TicketPurchase::where('user_id', $myData->id)
    //         ->with(['event', 'ticket'])
    //         ->get();

    //     return view('user.tickets', [
    //         'myData' => $myData,
    //         'purchases' => $purchases,
    //         'totalInvite' => $totalInvite,
    //         'useNewDependencies' => true
    //     ]);
    // }
    // public function ticketDetail($purchaseID)
    // {
    //     $myData = self::me();
    //     $totalInvite = $this->getNotifyInvitation($myData->id);
    //     $purchase = TicketPurchase::where('id', $purchaseID)
    //         ->with(['event', 'ticket.session'])
    //         ->first();

    //     return view('user.ticketDetail', [
    //         'myData' => $myData,
    //         'totalInvite' => $totalInvite,
    //         'purchase' => $purchase,
    //         'event' => $purchase->event,
    //         'useNewDependencies' => true
    //     ]);
    // }
    public function myTickets()
    {
        $myData = self::me();

        $objPayCtrl = new PaymentController();
        // Get transaction => untuk reaload data transaksi jika ada perubahan status terbayarnya suatu purchase
        $payments = $objPayCtrl->getTransaction($myData);

        // ------- Jika ada tiket yang sudah dihapus dan belum terbayar ------------------------

        $ticketExpired = [];
        foreach ($payments as $payment) {
            $purchases = $payment->purchases;
            $newPayment = false;
            for ($i = 0; $i < count($purchases); $i++) {
                if ($purchases[$i]->tickets->deleted == 1) {
                    array_push($ticketExpired, $purchases[$i]->tickets);
                    // Ubah data uang di payment ID terkait
                    $payObject = Payment::where('id', $payment->id);
                    $priceTicket = $purchases[$i]->price;
                    $newPrice = $payObject->first()->price - $priceTicket;
                    // dump($newPrice, $priceTicket, $payment->price);
                    $payObject->update(['price' => $newPrice]);
                    // Langsung hapus data di purchases terkait
                    Purchase::where('id', $purchases[$i]->id)->delete();
                    $newPayment = true;
                }
            }
            $payCheck = Payment::where('id', $payment->id)->first();
            if (count($payCheck->purchases) == 0) {
                Payment::where('id', $payment->id)->delete();
            } else if ($newPayment == true) {
                // Jika ada perubahan data payment (lakukan re checkout)
                $checkoutCtrl = new CheckoutController();
                // Re-Cheeckout => untuk memperbarui checkout data baik yang terbayar maupun belum bila terjadi penghapusan tiket oleh admin
                $checkoutCtrl->reCheckout($payCheck, $myData);
            }
        }
        // dump($ticketExpired);

        $organizationID = "";
        $eventID = "";
        $purchases = Purchase::where('user_id', $myData->id)->orWhere('send_from', $myData->id)->with(['users', 'tickets', 'events.organizer'])->orderBy('created_at', 'DESC')->get();
        $carts = CartController::cartCheckLoad($myData->id);
        // Get Transaction Ulang dari sebelumnya untuk mengatasi apabila ada perubahan data purchase akibat penghapusan tiket oleh admin
        $payments = $objPayCtrl->getTransaction($myData);

        // --------------------------------------------------------------------------------

        $totalInvite = $this->getNotifyInvitation($myData->id);

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

        return view('user.myticket', [
            'myData' => $myData,
            'purchases' => $purchases,
            'organizationID' => $organizationID,
            'totalInvite' => $totalInvite,
            'eventID' => $eventID,
            'carts' => $carts,
            'payments' => $payments,
            'CLIENT_KEY' => \Midtrans\Config::$clientKey,
            'isExhibitor' => self::isExihibitor(),
            'isSpeaker' => self::isSpeaker(),
        ]);
    }
    public function ticketShare()
    {
        $myData = self::me();
        $organizationID = "";
        $eventID = "";
        $purchases = Purchase::where('user_id', $myData->id)->with(['users', 'tickets', 'events.organizer'])->orderBy('created_at', 'DESC')->get();
        $carts = TicketCart::where('user_id', $myData->id)->where('status', 'notPay')->get();
        $totalInvite = $this->getNotifyInvitation($myData->id);
        return view('user.ticketShare', [
            'myData' => $myData,
            'organizationID' => $organizationID,
            'totalInvite' => $totalInvite,
            'eventID' => $eventID,
            'purchases' => $purchases,
            'mode' => 'normal',
            'isExhibitor' => self::isExihibitor(),
            'isSpeaker' => self::isSpeaker(),
        ]);
    }
    public function detailTicket($id)
    {
        $myData = self::me();
        $organizationID = "";
        $eventID = "";
        $purchases = Purchase::where('id', $id)->where('user_id', $myData->id)->with(['users', 'tickets', 'events.organizer'])->get();
        $totalInvite = $this->getNotifyInvitation($myData->id);
        $invoiceID = $purchases[0]->payment->order_id;

        $purchase = Purchase::where('ticket_id', $id)->where('user_id', $myData->id)->with(['users', 'tickets', 'events.organizer'])->first();

        // dd($purchase->tickets->session);

        return view('user.detail-ticket', [
            'myData' => $myData,
            'purchases' => $purchases,
            'organizationID' => $organizationID,
            'totalInvite' => $totalInvite,
            'eventID' => $eventID,
            'invoiceID' => $invoiceID,
            'id' => $id,
            'isExhibitor' => self::isExihibitor(),
            'isSpeaker' => self::isSpeaker(),
        ]);
    }

    public function printTicket($id, $type)
    {
        $myData = self::me();
        $purchase = Purchase::where('id', $id)->where('user_id', $myData->id)->with(['users', 'tickets', 'events.organizer'])->first();

        $totalInvite = $this->getNotifyInvitation($myData->id);
        $invoiceID = $purchase->payment->order_id;
        // $invoiceID =  $purchase->id . $purchase->user_id . "|" . $purchase->event_id . "|" . $purchase->ticket_id . $purchase->price;
        // dd('Catatan: Template dokumen download tiket masih belum dikirim dari mas Riannya');
        // set_time_limit(300);
        $data = [
            'myData' => $myData,
            'totalInvite' => $totalInvite,
            'invoiceID' => $invoiceID,
            'purchase' => $purchase,
            'type' => $type
        ];

        view()->share('data', $data);
        // dd(view('pdf.invoice-ticket'));
        $pdf = App::make('dompdf.wrapper');
        // $pdf->loadview('pdf.invoice-ticket', $data)->setPaper('a4', 'potrait');
        $pdf->loadView('pdf.invoice-ticket', [
            'myData' => $myData,
            'totalInvite' => $totalInvite,
            'invoiceID' => $invoiceID,
            'purchase' => $purchase,
            'type' => $type
        ]);
        return $pdf->download('invoice-ticket.pdf');
        // return ($pdf->loadview('pdf.invoice-ticket', $data)->setPaper('A4', 'potrait'));
        // dd($pdf->stream('ticket.pdf'));
        // return $pdf->stream('ticket.pdf');
        // return view('pdf.invoice-ticket', [
        //     'myData' => $myData,
        //     'totalInvite' => $totalInvite,
        //     'invoiceID' => $invoiceID,
        //     'purchase' => $purchase,
        //     'type' => $type
        // ]);
    }

    public function invitationIgnore($invitationID)
    {
        $invitationObj = Invitation::where('id', $invitationID);
        TempTicketShare::where('id', $invitationObj->first()->purchase->tempFor->id)->update(['share_to' => $invitationObj->first()->purchase->users->email]);
        $invitation = $invitationObj->update([
            'response' => 'Ignore'
        ]);

        return redirect()->back();
    }
    public function invitationAccept($invitationID, Request $request)
    {
        $myData = self::me();
        $invitation = Invitation::where('id', $invitationID)->with(['events', 'tickets'])->first();
        // dd(
        //     $invitation,
        //     $checkPurchase = Purchase::where('id', $invitation->purchase_id)->get()
        // );

        // Buat koneksi setelah undangan di terima
        $check = Connection::where([['connection_id', $invitation->sender], ['user_id', $myData->id]])->first();

        if ($check == null) {
            Connection::create([
                'user_id' => $myData->id,
                'connection_id' => $invitation->sender,
                'from_id' => $invitation->events->id,
            ]);
            // -------------- Update membuat koneksi untuk lawan koneksi -----------------------
            Connection::create([
                'user_id' => $invitation->sender,
                'connection_id' => $myData->id,
                'from_id' => $invitation->events->id,
            ]);
            // ---------------------------------------------------------------------------------
        }

        if (isset($invitation->tickets)) {
            $checkPurchase = Purchase::where('user_id', $invitation->receiver)->where('event_id', $invitation->events->id)->where('ticket_id', $invitation->tickets->id)->get();

            if (count($checkPurchase) < 5) {
                Purchase::where('id', $invitation->purchase_id)->update([
                    'user_id' => $myData->id,
                ]);

                $invitationsave = Invitation::where('id', $invitationID)->update([
                    'response' => 'Accept'
                ]);

                // $purchase = Purchase::create([
                //     'user_id'=> $invitation->receiver,
                //     'event_id'=>$invitation->events->id,
                //     'ticket_id'=>$invitation->tickets->id,
                //     'quantity'=>1,
                //     'price'=>$invitation->tickets->price
                // ]);
            } else {
                return redirect()->back()->with('gagal', 'Kamu sudah pernah membeli Tiket ini');
            }
        }
        return redirect()->back();
        // dd($invitationID, $eventID, $ticketID);

    }
    public function deleteInvitation($invitationID)
    {
        Invitation::where('id', $invitationID)->delete();
        return redirect()->back();
    }
    public function myExhibitions()
    {
        $myData = self::me();
        $totalInvite = $this->getNotifyInvitation($myData->id);
        $myExhibitor = UserController::isExihibitor();
        // dd($myExhibitor);
        return view('user.exhibitor-access', [
            'myData' => $myData,
            'totalInvite' => $totalInvite,
            'exhibitors' => $myExhibitor,
            'isExhibitor' => self::isExihibitor(),
            'isSpeaker' => self::isSpeaker(),
        ]);
    }
    public function speakerEvents()
    {
        if (count(self::isSpeaker()) == 0) {
            return abort('403');
        }

        $myData = self::me();

        //------ Mendapatkan data speaker jika trap atas lolos ----------
        $isSpeaker = Speaker::where('email', $myData->email)->get();
        // ---------------------------------------------------------------

        $totalInvite = $this->getNotifyInvitation($myData->id);
        $myExhibitor = UserController::isExihibitor();
        // dd($myExhibitor);
        return view('user.my-is-speaker', [
            'myData' => $myData,
            'totalInvite' => $totalInvite,
            'exhibitors' => $myExhibitor,
            'isExhibitor' => self::isExihibitor(),
            'isSpeaker' => $isSpeaker,
        ]);
    }
    public function speakerEventSessions($eventID)
    {
        if (count(self::isSpeaker()) == 0) {
            return abort('403');
        } else if (count(self::isSpeaker($eventID)) == 0) {
            return abort('403');
        }

        $myData = self::me();
        $totalInvite = $this->getNotifyInvitation($myData->id);
        $myExhibitor = UserController::isExihibitor();
        // dd($myExhibitor);

        $sessionsSpeaker = self::isSpeaker($eventID)[0]->SessionSpeaker;
        // $sessionsEvent = Event::where(id, $eventID)->get();
        // $sessionSelected = [];
        // for ($i=0; $i < count($sessionsEvent); $i++) { 
        //    for ($j=0; $j < count($sessionsSpeaker); $j++) { 
        //        # code...
        //    }
        // }
        // dd($sessionsSpeaker);
        // $allSessionEvent = SessionSpeaker::where('speaker_id', $speaker[0]->id)->where('event_id', $eventID)->get();
        // dd($allSessionEvent);
        return view('user.sessions-speaker', [
            'myData' => $myData,
            'totalInvite' => $totalInvite,
            'exhibitors' => $myExhibitor,
            'isExhibitor' => self::isExihibitor(),
            'isSpeaker' => self::isSpeaker($eventID),
            'event' => Event::where('id', $eventID)->first(),
            'rundowns' => Event::where('id', $eventID)->get()[0]->rundowns->where('deleted', 0)->groupBy('start_date'),
            // 'allSession' => $allSessionEvent,
        ]);
    }

    public function loginWithGoogle($email)
    {
        $email = base64_decode($email);
        $user = User::where([
            ['email', $email]
        ])->first();
        if ($user != "") {
            $loggingIn = Auth::guard('user')->login($user);
            return redirect()->route('user.events');
        }
        return redirect()->route('user.loginPage');
    }
    public function registerWithGoogle(Request $request)
    {
        $pkg = PackagePricing::where('deleted', 0)->first();
        $pkgID = $pkg->id;
        $registering = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt("loginWithGoogle"),
            'is_active' => 1,
            'photo' => 'default',
            'pkg_id' => $pkgID,
            'pkg_status' => 0,
        ]);

        return response()->json([
            'status' => 200,
        ]);
    }
}
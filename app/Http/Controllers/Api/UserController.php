<?php

namespace App\Http\Controllers\Api;

use Log;
use Str;
use Hash;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\BillingAccount;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Organization;
use App\Models\PackagePricing;
use App\Models\Otp;
use App\Models\PackagePayment;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public static function get($token) {
        return User::where('token', $token);
    }
    public static function getByID($userID) {
        return User::where('id', $userID);
    }
    public function organization(Request $request) {
        $token = $request->token;
        $user = User::where('token', $token)->with(['organizations', 'package', 'packagePayments.package'])->first();
        $user = json_decode(json_encode($user), FALSE);
        $user->can_create_organizer = true;

        $myPackage = $user->package;

        if (
            ($myPackage->organizer_count <= count($user->organizations)) &&
            $myPackage->organizer_count > 0
            ) {
            $user->can_create_organizer = false;
        }

        return response()->json([
            'status' => 200,
            'from' => 'organ',
            'token' => $token,
            'user' => $user,
            'myPackage' => $myPackage,
        ]);
    }
    public function login(Request $request) {
        $data = User::where('email', $request->email);
        $user = $data->first();

        if ($user == "") {
            return response()->json(['status' => 500, 'message' => "Email Anda belum terdaftar"]);
        }
        
        if ($request->with_google != 1) {
            $loggingIn = Hash::check($request->password, $user->password);
            if (!$loggingIn) {
                return response()->json(['status' => 500, 'message' => "Kombinasi email dan password tidak tepat"]);
            }
        }

        if ($user->is_active == 0 && $request->with_google == 1) {
            $data->update(['is_active' => 1]);
        }

        $updateData = $data->update(['token' => Str::random(32)]);
        $user = $data->first();

        return response()->json([
            'status' => 200,
            'message' => "Berhasil login",
            'user' => $user
        ]);
    }
    public function register(Request $request) {
        $withGoogle = $request->with_google;
        $checkEmail = User::where('email', $request->email);
        if ($checkEmail->first() != "") {
            return response()->json([
                'status' => 500,
                'message' => "Akun dengan email " . $request->email . " sudah terdaftar. Mohon gunakan email yang berbeda atau login saja"
            ]);
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

        $token = Str::random(32);

        $saveData = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $withGoogle == 1 ? bcrypt('withgoogle') : bcrypt($request->password),
            'photo' => 'default',
            'is_active' => $withGoogle == 1 ? 1 : 0,
            'pkg_id' => $pkgID,
            'pkg_status' => 1,
            'token' => $token,
        ]);

        if ($request->with_google != 1) {
            $otpCode = rand(1111, 9999);
            $createOtp = Otp::create([
                'user_id' => $saveData->id,
                'code' => $otpCode,
                'action' => 'register'
            ]);
            $sendOtp = Mail::to($request->email)->send(new \App\Mail\OtpMailer([
                'user' => $saveData,
                'otp' => $createOtp
            ]));
        }

        PackagePayment::create([
            'user_id' => $saveData->id,
            'pkg_id' => 1,
            'order_id' => Str::random(32),
            'token_trx' => '-',
            'status' => 1,
            'nominal' => 0,
        ]);

        return response()->json([
            'message' => "Berhasil register",
            'status' => 200,
            'user' => $saveData,
        ]);
    }
    public function otpAuth(Request $request) {
        $code = $request->code;
        $userID = $request->user_id;
        $user = User::where('id', $userID);
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');
        $token = Str::random(32);
        
        $otpQuery = Otp::where([
            ['code', $code],
            ['user_id', '>=', $userID],
            ['expiry', '>=', $dateNow],
        ]);
        $otp = $otpQuery->first();

        if ($otp == "") {
            return response()->json([
                'status' => 500,
                'message' => "Kode OTP salah"
            ]);
        } else {
            $otpQuery->update(['has_used' => 1]);
        }

        if ($otp->action == 'register') {
            $updateUser = $user->update([
                'is_active' => 1,
                'token' => $token
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => "Berhasil mengautentikasi OTP",
            'user' => $user->first()
        ]);
    }
    public function profile(Request $request) {
        $status = 200;
        $user = User::where('token', $request->token)->first();
        if ($user == "") {
            $status = 404;
        }

        return response()->json([
            'status' => $status,
            'user' => $user
        ]);
    }
    public function logout(Request $request) {
        $loggingOut = self::get($request->token)->update(['token' => null]);
        return response()->json(['status' => 200, 'message' => "Berhasil logout"]);
    }
    public function myTicket(Request $request) {
        $token = $request->token;
        $myData = self::get($token)->first();
        $purchases = \App\Models\Purchase::where('user_id', $myData->id)->orWhere('send_from', $myData->id)->with(['users', 'tickets', 'events.organizer'])->orderBy('created_at', 'DESC')->get();

        return response()->json([
            'message' => 'Berhasil mengambil tiket',
            'purchases' => $purchases
        ]);
    }
    public function banks(Request $request) {
        $user = self::get($request->token)->first();
        $banks = BillingAccount::where('user_id', $user->id)->get();

        return response()->json([
            'banks' => $banks
        ]);
    }
}

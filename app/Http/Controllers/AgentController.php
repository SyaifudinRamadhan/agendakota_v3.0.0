<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public static function me() {
        return Auth::guard('agent')->user();
    }
    public function loginPage() {
        $message = Session::get('message');
        return view('agent.login', [
            'message' => $message
        ]);
    }
    public function login(Request $request) {
        $loggingIn = Auth::guard('agent')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (!$loggingIn) {
            return redirect()->route('agent.loginPage')->withErrors([
                'Kombinasi email dan password tidak tepat'
            ]);
        }

        return redirect()->route('agent.dashboard');
    }
    public function logout() {
        $loggingOut = Auth::guard('agent')->logout();
        return redirect()->route('agent.dashboard')->with([
            'message' => "Berhasil logout"
        ]);
    }
    public function dashboard() {
        $myData = self::me();
        return view('agent.dashboard', [
            'myData' => $myData
        ]);
    }

    public function handout() {
        $myData = self::me();
        $message = Session::get('message');
        $handouts = BoothHandoutController::get([
            ['booth_id', $myData->booth_id]
        ])->get();

        return view('agent.handout', [
            'myData' => $myData,
            'message' => $message,
            'handouts' => $handouts
        ]);
    }
    public function product() {
        $myData = self::me();
        $message = Session::get('message');
        $products = BoothProductController::get([
            ['booth_id', $myData->booth_id]
        ])->get();

        return view('agent.product', [
            'myData' => $myData,
            'message' => $message,
            'products' => $products,
        ]);
    }
}

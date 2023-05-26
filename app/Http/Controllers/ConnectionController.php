<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use Illuminate\Http\Request;
use Auth;

class ConnectionController extends Controller
{
    public static function myId()
    {
        return Auth::guard('user')->id();
    }

    public function addConnection(Request $request)
    {
        $this->validate($request,[
            'connection_id' => 'required'
        ],[
            'required' => 'Wajib memilih salah satu user di form "Masukkan nama atau email"'
        ]);
        $myId = self::myId();

        if ($request->event_id == null) {
            $request->event_id = '0';
        }

        $check = Connection::where([['connection_id', $request->connection_id],['user_id', $myId]])->first();

        if ($check == null) {
            Connection::create([
                'user_id' => $myId,
                'connection_id' => $request->connection_id,
                'from_id' => $request->event_id,
            ]);
            // -------------- Update membuat koneksi untuk lawan koneksi -----------------------
            Connection::create([
                'user_id' => $request->connection_id,
                'connection_id' => $myId,
                'from_id' => $request->event_id,
            ]);
            // ---------------------------------------------------------------------------------
        }
        else {
            if($request->event_id != '0'){
                // dd($request);
                return redirect()->route('user.messages',['msgTo'=>$request->connection_id]);
            }
            return redirect()->back()->with('gagal', 'Koneksi Sudah Ada');
        }

        if($request->event_id != '0'){
            // dd($request);
            return redirect()->route('user.messages',['msgTo'=>$request->connection_id]);
        }
        return redirect()->back()->with('success', 'Berhasil Menambahkan Koneksi');
    }
}

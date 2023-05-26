<?php

namespace App\Http\Controllers;

use App\Models\Exhibitor;
use Illuminate\Http\Request;

class StreamExhibitorController extends Controller
{
    public function streamExhibitor($exhibitorID)
    {
        $myData = UserController::me();
        $username = "Guest User";
        $userMail = "Guest@gmail.com";

        if(isset($myData)){
            $username = $myData->name;
            $userMail = $myData->email;
        }

        $exhibitor = Exhibitor::where('id', $exhibitorID)->get();

        if(count($exhibitor) == 0){
            return abort('404');
        }

        $link = "";
        $e = "";
        $p = "";
        $e = "";
        $id = [];
        $pass = "";
        $password = [];

        // foreach ($urlMain as $url) {

        $link = $exhibitor[0]->booth_link;
        
            $e = explode("?", $link);
            if (count($e) == 0) {
                return redirect()->back()->with('pesan', 'Link meet tidak valid. Hubungi admin event');
            } else {
                $p = explode("/", $e[0]);
            }
            $e = explode("?", $link);
            if (count($p) == 0) {
                return redirect()->back()->with('pesan', 'Link meet tidak valid. Hubungi admin event');
            } else {
                array_push($id, $p[count($p) - 1]);
            }
            if (count($e) == 0 || count($e) == 1) {
                return redirect()->back()->with('pesan', 'Link meet tidak valid. Hubungi admin event');
            } else {
                $pass = explode("pwd=", $e[1]);
            }
            if (count($pass) == 0 || count($pass) == 1) {
                return redirect()->back()->with('pesan', 'Link meet tidak valid. Hubungi admin event');
            } else {
                array_push($password, $pass[1]);
            }
            $url_leave = route('user.home.exhibitions', [$exhibitor[0]->event_id, $exhibitor[0]->id]);
            $dataReturn = [
                'meet_id' => $id[0],
                'meet_pwd' => $password[0],
                'nama_peserta' => $username,
                'url_leave' => $url_leave,
                'email_peserta' => $userMail,
                'exhibitor' => $exhibitor[0],
                'myData' => $myData
            ];
        return view('user.stream-exhibitor', $dataReturn);    
    }
}

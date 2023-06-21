<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use App\Models\BoothCategory;
use App\Models\Session;
use App\Models\SessionSpeaker;
use App\Models\Purchase;
use DateTime;
use App\Models\Rundown;
use App\Models\Organization;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Str;

class TestController extends Controller
{
    private function autoLoginById($userData){
        $url = env('STREAM_SERVER') . '/api/v1/login-token';
        $payload = [
            'email' => $userData->email,
            'token' => $userData->token,
            'exp' => time() + (1440 * 60)
        ];
        $json = json_encode([
            "credential" => JWT::encode($payload, env('JWT_SIGNATURE_KEY'), env('JWT_ALG')),
        ]);

        $curl = curl_init();

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

        return json_decode($resp)->token;
    }

    private function getStreamKey($userData, String $sessionID = '', String $purchaseID = ''){
        $xAccessToken = $this->autoLoginById($userData);

        $url = '';
        if($sessionID != ''){
            $url = env('STREAM_SERVER') . '/api/v1/get-stream-key-session/' . $sessionID;
        }else if($purchaseID != ''){
            $url = env('STREAM_SERVER') . '/api/v1/get-stream-key-purchase/' . $purchaseID;
        }else{
            return null;
        }
      
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-access-token: '.$xAccessToken
            ),
        )
        );

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);

        if(!$response->stream_key){
            return null;
        }

        return $response->stream_key;
    }

    public function testRTMPVideo(Request $request, $purchaseID, $userID)
    {
        $dataReturn = [];
        $purchase = Purchase::where('id', $purchaseID)->first();
        $myData = User::where('id', $userID)->first();
        $urlMain = $purchase->tickets->session;
        //check ticket statuses
        if ($purchase->payment->pay_state != 'Terbayar' || $purchase->tempFor->share_to != $myData->email) {
            if ($purchase->payment->pay_state != 'Terbayar') {
                return redirect()->route('user.myTickets')->with('pesan', 'Kamu belum membeli Tiket ini');
            } else {
                return redirect()->route('user.myTickets')->with('pesan', 'Ticket ini bukan milikmu');
            }
        }

        $link = "";
        $e = "";
        $p = "";
        $e = "";
        $id = [];
        $pass = "";
        $password = [];

        // foreach ($urlMain as $url) {

        $link = $urlMain->link;
        $linkFor = "";
        if (preg_match("/zoom.us/i", $link)) {
            $linkFor = "zoom";
        } else if (preg_match("/youtube.com/i", $link)) {
            $linkFor = "youtube";
        } else if (preg_match("/rtmp-stream-key/i", $link)) {
            $linkFor = "rtmp-stream-key";
        } else if (preg_match("/webrtc-video-conference/i", $link)) {
            $linkFor = "webrtc-video-conference";
        }

        if ($linkFor == "zoom") {
            $e = explode("?", $link);
            if (count($e) == 0) {
                return redirect()->route('user.myTickets')->with('pesan', 'Link ticket tidak valid. Hubungi admin event');
            } else {
                $p = explode("/", $e[0]);
            }
            $e = explode("?", $link);
            if (count($p) == 0) {
                return redirect()->route('user.myTickets')->with('pesan', 'Link ticket tidak valid. Hubungi admin event');
            } else {
                array_push($id, $p[count($p) - 1]);
            }
            if (count($e) == 0 || count($e) == 1) {
                return redirect()->route('user.myTickets')->with('pesan', 'Link ticket tidak valid. Hubungi admin event');
            } else {
                $pass = explode("pwd=", $e[1]);
            }
            if (count($pass) == 0 || count($pass) == 1) {
                return redirect()->route('user.myTickets')->with('pesan', 'Link ticket tidak valid. Hubungi admin event');
            } else {
                array_push($password, $pass[1]);
            }
            // $url_leave = route('user.joinStream', [$purchaseID]);
            $dataReturn += [
                'id' => $id,
                'password' => $password,
                'nama_peserta' => $myData->name,
                'email_peserta' => $myData->email,
                'url' => Session::where('id', $urlMain->id)->get(),
            ];
        } elseif ($linkFor == "youtube") {
            $dataReturn += [
                'url' => Session::where('id', $urlMain->id)->get(),
            ];
        }else if ($linkFor == "rtmp-stream-key"){
            $streamKey = $this->getStreamKey( $myData,'', $purchaseID);
            if($streamKey == null){
                return redirect()->back()->with('gagal', 'Stream key failed getting from server');
            }
            $link = env('STREAM_SERVER') . '/streams/'. $streamKey .'/index.m3u8?purchase=' . $purchaseID;
            $dataReturn += [
                'id' => $id,
                //Bertipe Array
                'password' => $password,
                //Bertipe Array
                // 'streamWith' => $linkFor[0],
                'nama_peserta' => $myData->name,
                'email_peserta' => $myData->email,
                'url' => Session::where('id', $urlMain->id)->get(),
                // Kebutuhan untuk stream dengan RTMP & webrtc
                'xAccessToken' => $this->autoLoginById($myData),
                'link' => $link
            ];
        } else if ($linkFor == "webrtc-video-conference"){
            $link = env('STREAM_SERVER');
            $dataReturn += [
                'id' => $id,
                //Bertipe Array
                'password' => $password,
                //Bertipe Array
                // 'streamWith' => $linkFor[0],
                'nama_peserta' => $myData->name,
                'email_peserta' => $myData->email,
                'url' => Session::where('id', $urlMain->id)->get(),
                // Kebutuhan untuk stream dengan RTMP & webrtc
                'xAccessToken' => $this->autoLoginById($myData),
                'link' => $link
            ];
        }

        $url_leave = route('user.joinStream', [$purchaseID]);
        $dataReturn += [
            'url_leave' => $url_leave
        ];

        $totalInvitation = UserController::getNotifyInvitation($myData->id);
        $organization = Organization::where('id', $purchase->events->organizer_id)->with('events')->first();
        // }
        // ------------ Update auto chekin untuk event virtual ---------------------------------------------------------
        $startEvent = new DateTime($purchase->tickets->session->start_date . ' ' . $purchase->tickets->session->start_time);
        $now = new DateTime();

        #Jika watu event <= waktu sekarang, lakukan checkin
        if ($startEvent <= $now) {
            $value = CheckinController::manualCheck($purchase->id);
        }
        // -------------------------------------------------------------------------------------------------------------
        $categoryList = [];
        $exhibitorsGroup = [];
        foreach ($purchase->events->exhibitors->groupBy('category') as $key => $value) {
            $category = BoothCategory::where('event_id', $purchase->events->id)->where('name', $key)->first();
            $icon = asset('images/default_icon.png');
            $priority = 9999;
            if ($category) {
                if ($category->icon != 'default_icon.png') {
                    $icon = asset('storage/event_assets/' . $purchase->events->slug . '/exhibitors/exhibitor_cat_icon/' . $category->icon);
                }
                $priority = $category->priority;
            }
            $exhibitorsGroup[$key] = [];
            array_push($exhibitorsGroup[$key], $icon);
            array_push($exhibitorsGroup[$key], $value);
            array_push($exhibitorsGroup[$key], $priority);
            array_push($categoryList, [$key, $priority]);
        }

        // Mengurutkan berdasarkan prioritas kategori
        $temp = null;
        for ($i = 0; $i < count($categoryList); $i++) {
            for ($j = 0; $j < count($categoryList) - 1; $j++) {
                if ($categoryList[$j][1] > $categoryList[$j + 1][1]) {
                    $temp = $categoryList[$j];
                    $categoryList[$j] = $categoryList[$j + 1];
                    $categoryList[$j + 1] = $temp;
                }
            }
        }

        $newExhibitorsGroup = [];
        for ($i = 0; $i < count($categoryList); $i++) {
            $newExhibitorsGroup[$categoryList[$i][0]] = $exhibitorsGroup[$categoryList[$i][0]];
        }

        $temp = null;
        $exhibitorsGroup = null;

        $dataReturn += [
            'startSession' => $purchase->tickets->session,
            // 'endSession' => $purchase->tickets->end_session,
            'paramPage' => 'byUser',
            'streamType' => $linkFor,
            'purchase' => $purchase,
            'streamPage' => true,
            'myData' => $myData,
            'totalInvite' => $totalInvitation,
            'event' => $purchase->events,
            'organization' => $organization,
            'exhibitors' => $newExhibitorsGroup,
        ];
        return view('user.streaming-test-rtmp-view', $dataReturn);
    }
    public function testStudio(Request $req, $streamKey, $purchaseID)
    {
        return view('user.organization.event.studio');
    }
    public function testRTCStudio(Request $request, $purchaseID, $userID)
    {
        $dataReturn = [];
        $purchase = Purchase::where('id', $purchaseID)->first();
        $myData = User::where('id', $userID)->first();
        $urlMain = $purchase->tickets->session;
        //check ticket statuses
        if ($purchase->payment->pay_state != 'Terbayar' || $purchase->tempFor->share_to != $myData->email) {
            if ($purchase->payment->pay_state != 'Terbayar') {
                return redirect()->route('user.myTickets')->with('pesan', 'Kamu belum membeli Tiket ini');
            } else {
                return redirect()->route('user.myTickets')->with('pesan', 'Ticket ini bukan milikmu');
            }
        }

        $link = "";
        $e = "";
        $p = "";
        $e = "";
        $id = [];
        $pass = "";
        $password = [];

        // foreach ($urlMain as $url) {

        $link = $urlMain->link;
        $linkFor = "";
        if (preg_match("/zoom.us/i", $link)) {
            $linkFor = "zoom";
        } else if (preg_match("/youtube.com/i", $link)) {
            $linkFor = "youtube";
        } else if (preg_match("/rtmp-stream-key/i", $link)) {
            $linkFor = "rtmp-stream-key";
        } else if (preg_match("/webrtc-video-conference/i", $link)) {
            $linkFor = "webrtc-video-conference";
        }

        if ($linkFor == "zoom") {
            $e = explode("?", $link);
            if (count($e) == 0) {
                return redirect()->route('user.myTickets')->with('pesan', 'Link ticket tidak valid. Hubungi admin event');
            } else {
                $p = explode("/", $e[0]);
            }
            $e = explode("?", $link);
            if (count($p) == 0) {
                return redirect()->route('user.myTickets')->with('pesan', 'Link ticket tidak valid. Hubungi admin event');
            } else {
                array_push($id, $p[count($p) - 1]);
            }
            if (count($e) == 0 || count($e) == 1) {
                return redirect()->route('user.myTickets')->with('pesan', 'Link ticket tidak valid. Hubungi admin event');
            } else {
                $pass = explode("pwd=", $e[1]);
            }
            if (count($pass) == 0 || count($pass) == 1) {
                return redirect()->route('user.myTickets')->with('pesan', 'Link ticket tidak valid. Hubungi admin event');
            } else {
                array_push($password, $pass[1]);
            }
            // $url_leave = route('user.joinStream', [$purchaseID]);
            $dataReturn += [
                'id' => $id,
                'password' => $password,
                'nama_peserta' => $myData->name,
                'email_peserta' => $myData->email,
                'url' => Session::where('id', $urlMain->id)->get(),
            ];
        } elseif ($linkFor == "youtube") {
            $dataReturn += [
                'url' => Session::where('id', $urlMain->id)->get(),
            ];
        }else if ($linkFor == "rtmp-stream-key"){
            $streamKey = $this->getStreamKey($myData,'', $purchaseID);
            if($streamKey == null){
                return redirect()->back()->with('gagal', 'Stream key failed getting from server');
            }
            $link = env('STREAM_SERVER') . '/streams/'. $streamKey .'/index.m3u8?purchase=' . $purchaseID;
            $dataReturn += [
                'id' => $id,
                //Bertipe Array
                'password' => $password,
                //Bertipe Array
                // 'streamWith' => $linkFor[0],
                'nama_peserta' => $myData->name,
                'email_peserta' => $myData->email,
                'url' => Session::where('id', $urlMain->id)->get(),
                // Kebutuhan untuk stream dengan RTMP & webrtc
                'xAccessToken' => $this->autoLoginById($myData),
                'link' => $link
            ];
        } else if ($linkFor == "webrtc-video-conference"){
            $link = env('STREAM_SERVER');
            $dataReturn += [
                'id' => $id,
                //Bertipe Array
                'password' => $password,
                //Bertipe Array
                // 'streamWith' => $linkFor[0],
                'nama_peserta' => $myData->name,
                'email_peserta' => $myData->email,
                'url' => Session::where('id', $urlMain->id)->get(),
                // Kebutuhan untuk stream dengan RTMP & webrtc
                'xAccessToken' => $this->autoLoginById($myData),
                'link' => $link
            ];
        }

        $url_leave = route('user.joinStream', [$purchaseID]);
        $dataReturn += [
            'url_leave' => $url_leave
        ];

        $totalInvitation = UserController::getNotifyInvitation($myData->id);
        $organization = Organization::where('id', $purchase->events->organizer_id)->with('events')->first();
        // }
        // ------------ Update auto chekin untuk event virtual ---------------------------------------------------------
        $startEvent = new DateTime($purchase->tickets->session->start_date . ' ' . $purchase->tickets->session->start_time);
        $now = new DateTime();

        #Jika watu event <= waktu sekarang, lakukan checkin
        if ($startEvent <= $now) {
            $value = CheckinController::manualCheck($purchase->id);
        }
        // -------------------------------------------------------------------------------------------------------------
        $categoryList = [];
        $exhibitorsGroup = [];
        foreach ($purchase->events->exhibitors->groupBy('category') as $key => $value) {
            $category = BoothCategory::where('event_id', $purchase->events->id)->where('name', $key)->first();
            $icon = asset('images/default_icon.png');
            $priority = 9999;
            if ($category) {
                if ($category->icon != 'default_icon.png') {
                    $icon = asset('storage/event_assets/' . $purchase->events->slug . '/exhibitors/exhibitor_cat_icon/' . $category->icon);
                }
                $priority = $category->priority;
            }
            $exhibitorsGroup[$key] = [];
            array_push($exhibitorsGroup[$key], $icon);
            array_push($exhibitorsGroup[$key], $value);
            array_push($exhibitorsGroup[$key], $priority);
            array_push($categoryList, [$key, $priority]);
        }

        // Mengurutkan berdasarkan prioritas kategori
        $temp = null;
        for ($i = 0; $i < count($categoryList); $i++) {
            for ($j = 0; $j < count($categoryList) - 1; $j++) {
                if ($categoryList[$j][1] > $categoryList[$j + 1][1]) {
                    $temp = $categoryList[$j];
                    $categoryList[$j] = $categoryList[$j + 1];
                    $categoryList[$j + 1] = $temp;
                }
            }
        }

        $newExhibitorsGroup = [];
        for ($i = 0; $i < count($categoryList); $i++) {
            $newExhibitorsGroup[$categoryList[$i][0]] = $exhibitorsGroup[$categoryList[$i][0]];
        }

        $temp = null;
        $exhibitorsGroup = null;

        $dataReturn += [
            'startSession' => $purchase->tickets->session,
            // 'endSession' => $purchase->tickets->end_session,
            'paramPage' => 'byUser',
            'streamType' => $linkFor,
            'purchase' => $purchase,
            'streamPage' => true,
            'myData' => $myData,
            'totalInvite' => $totalInvitation,
            'event' => $purchase->events,
            'organization' => $organization,
            'exhibitors' => $newExhibitorsGroup,
        ];

        return view('user.streaming-test-rtc', $dataReturn);
    }
}
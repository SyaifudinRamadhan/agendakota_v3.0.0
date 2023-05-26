<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Session;
use App\Models\Ticket;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;

class StreamStudioController extends Controller
{
    public function studioStream($organizationID, $eventID, $sessionID, Request $req)
    {
        $myData = UserController::me();
        $session = Session::where('id', $sessionID)->first();

        if (!$session) {
            return redirect()->back()->with('error', 'Event tidak ditemukkan');
        } else {
            if ($session->event->id != $eventID || $session->event->organizer_id != $organizationID || $session->link != 'rtmp-stream-key') {
                return redirect()->back()->with('error', 'Sesi event tidak diatur untuk streaming RTMP satu arah');
            }
        }

        $token = JWT::encode([
            "email" => $myData->email
        ], env('JWT_SIGNATURE_KEY'), env('JWT_ALG'));

        $xAccessToken = session('x-access-token');

        $url = env('STREAM_SERVER') . '/api/v1/get-stream-key-session/' . $sessionID;
      
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
        // NB: Security belum ada 
//         category
// execution_type
// breakdown
// sessions
        $response = json_decode($response);

        if(!$response->stream_key){
            return redirect()->back()->with('error', 'Sesi event tidak sesuai dengan stream key');
        }

        return view('user.organization.event.studio', [
            'orgId' => $organizationID,
            'eventId' => $eventID,
            'myData' => $myData,
            'sessionId' => $sessionID,
            'streamKey' => $response->stream_key,
            'passwordToken' => $token,
            'xAccessToken' => $xAccessToken,
            'category' => $session->event->category,
            'breakdown' => $session->event->breakdown
        ]);
    }
}
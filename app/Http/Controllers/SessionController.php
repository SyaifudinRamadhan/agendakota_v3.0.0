<?php

namespace App\Http\Controllers;

use App\Models\BoothCategory;
use App\Models\Session;
use App\Models\Event;
use App\Models\SessionSpeaker;
use App\Models\Purchase;
use DateTime;
use App\Models\Ticket;
use App\Models\Rundown;
use Illuminate\Http\Request;
use App\Models\Organization;
use Illuminate\Support\Str;

use function PHPUnit\Framework\returnSelf;

class SessionController extends Controller
{
    public static function get($filter = NULL)
    {
        if ($filter == NULL) {
            return new Session;
        }
        return Session::where($filter);
    }

    private function getStreamKey(String $sessionID = '', String $purchaseID = ''){
        $xAccessToken = session('x-access-token');

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

    private function checkLink($link)
    {
        $linkFor = "";
        if (strpos($link, "zoom.us") == true) {
            $linkFor = "zoom";
        } else if (strpos($link, "youtube.com") == true) {
            $linkFor = "youtube";
        } else if (strpos($link, "meet.google.com") == true) {
            $linkFor = "meet";
        }

        if ($linkFor == "zoom") {
            $e = explode("?", $link);
            if (count($e) == 0) {
                return -1;
            } else {
                $p = explode("/", $e[0]);
            }
            $e = explode("?", $link);
            if (count($p) == 0) {
                return -1;
            } else {
                $id = $p[count($p) - 1];
            }
            if (count($e) == 0 || count($e) == 1) {
                return -1;
            } else {
                $pass = explode("pwd=", $e[1]);
            }
            if (count($pass) == 0 || count($pass) == 1) {
                return -1;
            } else {
                $password = $pass[1];
            }
            return $link;
        } elseif ($linkFor == "youtube") {
            $idVideo = "";

            if (preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/', $link)) {
                $urlInput = explode('watch?v=', $link);
                if (count($urlInput) == 1) {
                    $urlInput = explode('youtu.be/', $link);
                    if (count($urlInput) > 1) {
                        $idVideo = $urlInput[1];
                        $idEx = explode('&', $idVideo);
                        if (count($idEx) == 1) {
                            return ("https://www.youtube.com/embed/" . $idEx[0] . '?modestbranding=1&showinfo=0');
                        } else {
                            return ("https://www.youtube.com/embed/" . $idEx[0] . '?modestbranding=1&showinfo=0');
                        }
                    } else {
                        $urlInput = explode('/embed/', $link);
                        if (count($urlInput) > 1) {
                            $idVideo = $urlInput[1];
                            $idEx = explode('&', $idVideo);
                            if (count($idEx) == 1) {
                                return ("https://www.youtube.com/embed/" . $idEx[0] . '?modestbranding=1&showinfo=0');
                            } else {
                                return ("https://www.youtube.com/embed/" . $idEx[0] . '?modestbranding=1&showinfo=0');
                            }
                        }
                    }
                } else {
                    $idVideo = $urlInput[1];
                    $idEx = explode('&', $idVideo);
                    if (count($idEx) == 1) {
                        return ("https://www.youtube.com/embed/" . $idEx[0] . '?modestbranding=1&showinfo=0');
                    } else {
                        return ("https://www.youtube.com/embed/" . $idEx[0] . '?modestbranding=1&showinfo=0');
                    }
                }
            }
            return -1;
        }
    }

    private function crdRTMPKey($sessionID, $endpoint)
    {
        $url = env('STREAM_SERVER') . $endpoint;
        $json = json_encode([
            "session" => $sessionID
        ]);

        $curl = curl_init();
        //  curl_setopt($curl, CURLOPT_URL, $url);
        //  curl_setopt($curl, CURLOPT_POST, true);
        //  curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: application/json"));
        //  curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        //  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $json,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'x-access-token: ' . session('x-access-token')
                ),
            )
        );
        curl_exec($curl);
        curl_close($curl);
    }

    public function store($organizationID, $eventID, Request $request)
    {
        $myData = UserController::me();
        $event = Event::where('id', $eventID)->first();
        $validateRule = [
            'title' => 'required',
            'description' => 'required',
            'start_rundown' => 'required',
            'end_rundown' => 'required',
        ];

        // Tambahkan rule untuk link jika mode event online
        // if ($event->execution_type == 'online' || $event->execution_type == 'hybrid') {
        //     $validateRule['link'] = 'required';
        // }

        $validateMsg = [
            'required' => ':attribute wajib diisi',
            'date' => 'Kolom :attribute wajib berformat tanggal'
        ];

        $validateData = $this->validate($request, $validateRule, $validateMsg);

        // -------- Cek paket user aktif ? belum mencapai batas ? -------------------------------
        $organization = Organization::where('id', $organizationID)->first();
        $isPkgActive = PackagePricingController::limitCalculator($organization->user);

        $paramCombine = false;
        // paramCombine adalah parameter untuk jumlah unlimted / masih tersedia
        $sessions = Session::where('event_id', $eventID)->where('deleted', 0)->get();
        if ($organization->user->package->session_count <= -1) {
            $paramCombine = false;
        } else {
            if (count($sessions) >= $organization->user->package->session_count) {
                $paramCombine = true;
            }
        }

        // Cek kondisi paket aktif ? dan belum mencapai limit ?
        if ($isPkgActive == 0 || $paramCombine == true) {
            if ($paramCombine == true) {
                return redirect()->back()->with('gagal', 'Session yang kamu buat sudah mencapai batas');
            } else {
                return redirect()->back()->with('gagal', 'Paket yang kamu beli belum aktif / belum dibayar');
            }
        }

        // --------------------------------------------------------------------------------------

        $overview = $request->overview;
        if (!isset($overview)) {
            $overview = 0;
        } else {
            $overview = 1;
        }

        $startRundown = Rundown::where('id', $request->start_rundown)->first();
        $endRundown = Rundown::where('id', $request->end_rundown)->first();

        $fixLink = "";
        // Cek link wajib untuk mode event online
        if ($event->execution_type == 'online' || $event->execution_type == 'hybrid') {
            $link = $request->link;
            $streamOption = $request->streamOption;
            // dd($streamOption);
            if ($streamOption == 1) {
                // dd(session('x-access-token'));
                $fixLink = 'rtmp-stream-key';
            } else if ($streamOption == 2) {
                $now = new DateTime('now');
                $fixLink = 'webrtc-video-conference-' . Str::uuid()->toString() . $now->format('Y-m-d H:i:s');
            } else if ($streamOption != 0) {
                if ($link != null) {
                    $fixLink = $this->checkLink($link);
                    if ($fixLink == -1) {
                        return redirect()->back()->with('gagal', 'Link Youtube / Zoom yang dimasukkan tidak benar');
                    }
                } else {
                    return redirect()->back()->with('gagal', 'Link platform streaming wajib diisi');
                }
            } else {
                return redirect()->back()->with('gagal', 'Wajib untuk memilih platform streaming');
            }
        }

        // dd($fixLink);

        $saveData = Session::create([
            'event_id' => $eventID,
            'start_rundown_id' => $request->start_rundown,
            'end_rundown_id' => $request->end_rundown,
            'title' => $request->title,
            'link' => $fixLink,
            'description' => $request->description,
            'start_date' => $startRundown->start_date,
            'end_date' => $endRundown->end_date,
            'overview' => $overview,
            'start_time' => $startRundown->start_time,
            'end_time' => $endRundown->end_time,
        ]);

        if ($fixLink == 'rtmp-stream-key') {
            $this->crdRTMPKey($saveData->id, "/api/v1/reg-stream");
        }

        // $speakersID = $request->speakers;
        // foreach ($speakersID as $speakerID) {
        //     $saveSpeaker = SessionSpeaker::create([
        //         'session_id' => $saveData->id,
        //         'speaker_id' => $speakerID
        //     ]);
        // }

        return redirect()->route('organization.event.sessions', [$organizationID, $eventID])->with('berhasil', 'Session telah berhasil Ditambahkan');
    }

    public function update($organizationID, $eventID, Request $request)
    {

        //check event punya brakdown
        $eventCheck = Event::where('id', $eventID)->first();
        if ($eventCheck->breakdown == 'KOSONG' || preg_match('/Stage and Session/i', $eventCheck->breakdown) == false) {
            $validateRule = [
                'description' => 'required',
            ];

            $validateMsg = [
                'required' => ':attribute wajib diisi',
            ];

            $validateData = $this->validate($request, $validateRule, $validateMsg);

            $dataUpdate = [
                'description' => $request->description,
            ];

            $overview = $request->overview;
            if (isset($overview)) {
                $dataUpdate['overview'] = $request->overview;
            }

            if ($eventCheck->execution_type == 'online' || $eventCheck->execution_type == 'hybrid') {
                $link = $request->link;
                $streamOption = $request->streamOption;
                $fixLink = '';

                if ($streamOption == 1) {
                    // dd(session('x-access-token'));
                    $fixLink = 'rtmp-stream-key';
                    if($eventCheck->sessions[0]->link != 'rtmp-stream-key'){
                        $this->crdRTMPKey($eventCheck->sessions[0]->id, "/api/v1/reg-stream");
                    }
                } else if ($streamOption == 2) {
                    $now = new DateTime('now');
                    if ($eventCheck->sessions[0]->link == 'rtmp-stream-key') {
                        $this->crdRTMPKey($eventCheck->sessions[0]->id, "/api/v1/del-stream");
                    }
                    $fixLink = 'webrtc-video-conference-' . Str::uuid()->toString() . $now->format('Y-m-d H:i:s');
                } else if ($streamOption != 0) {
                    if ($link != null) {
                        $fixLink = $this->checkLink($link);
                        if ($fixLink == -1) {
                            return redirect()->back()->with('gagal', 'Link Youtube / Zoom yang dimasukkan tidak benar');
                        }
                        if ($eventCheck->sessions[0]->link == 'rtmp-stream-key') {
                            $this->crdRTMPKey($eventCheck->sessions[0]->id, "/api/v1/del-stream");
                        }
                    } else {
                        return redirect()->back()->with('gagal', 'Link platform streaming wajib diisi');
                    }
                } else {
                    return redirect()->back()->with('gagal', 'Wajib untuk memilih platform streaming');
                }

                $dataUpdate['link'] = $fixLink;
            }

            Session::where('id', $eventCheck->sessions[0]->id)->update($dataUpdate);

            return redirect()->back()->with('berhasil', 'Session telah berhasil Diedit');
        }

        $validateRule = [
            'title' => 'required',
            'description' => 'required',
            'start_rundown' => 'required',
            'end_rundown' => 'required',
        ];

        // if ($eventCheck->execution_type == 'online' || $eventCheck->execution_type == 'hybrid') {
        //     $validateRule['link'] = 'required';
        // }

        $validateMsg = [
            'required' => ':attribute wajib diisi',
            'date' => 'Kolom :attribute wajib berformat tanggal'
        ];

        $validateData = $this->validate($request, $validateRule, $validateMsg);

        $id = $request->session_id;

        $startRundown = Rundown::where('id', $request->start_rundown)->first();
        $endRundown = Rundown::where('id', $request->end_rundown)->first();

        $fixLink = "";
        if ($eventCheck->execution_type == 'online' || $eventCheck->execution_type == 'hybrid') {
            $link = $request->link;
            $sessionEvent = Session::where('id', $id)->first();
            // $link = $request->link;
            $streamOption = $request->streamOption;
            // $fixLink = '';

            if ($streamOption == 1) {
                // dd(session('x-access-token'));
                $fixLink = 'rtmp-stream-key';
                if ($sessionEvent->link != 'rtmp-stream-key'){
                    $this->crdRTMPKey($id, "/api/v1/reg-stream");
                }
            } else if ($streamOption == 2) {
                $now = new DateTime('now');
                if ($sessionEvent->link == 'rtmp-stream-key') {
                    $this->crdRTMPKey($id, "/api/v1/del-stream");
                }
                $fixLink = 'webrtc-video-conference-' . Str::uuid()->toString() . $now->format('Y-m-d H:i:s');
            } else if ($streamOption != 0) {
                if ($link != null) {
                    $fixLink = $this->checkLink($link);
                    if ($fixLink == -1) {
                        return redirect()->back()->with('gagal', 'Link Youtube / Zoom yang dimasukkan tidak benar');
                    }
                    if ($sessionEvent->link == 'rtmp-stream-key') {
                        $this->crdRTMPKey($id, "/api/v1/del-stream");
                    }
                } else {
                    return redirect()->back()->with('gagal', 'Link platform streaming wajib diisi');
                }
            } else {
                return redirect()->back()->with('gagal', 'Wajib untuk memilih platform streaming');
            }

            // if ($link != null) {
            //     $fixLink = $this->checkLink($link);
            //     if ($fixLink == -1) {
            //         return redirect()->back()->with('gagal', 'Link Youtube / Zoom yang dimasukkan tidak benar');
            //     }
            // }
        }

        // dd($fixLink);

        $updateData = Session::where('id', $id)->update([
            'start_rundown_id' => $request->start_rundown,
            'end_rundown_id' => $request->end_rundown,
            'title' => $request->title,
            'link' => $fixLink,
            'description' => $request->description,
            'start_date' => $startRundown->start_date,
            'end_date' => $endRundown->end_date,
            'overview' => $request->overview,
            'start_time' => $startRundown->start_time,
            'end_time' => $endRundown->end_time,
        ]);

        // $speakersID = $request->speakers;
        // // dd($speakersID);
        // if(isset($speakersID)){
        //     $deleteSpeaker = SessionSpeaker::where('session_id',$id)->delete();
        //     foreach ($speakersID as $speakerID) {
        //         $updateSpeaker = SessionSpeaker::create([
        //             'session_id' => $id,
        //             'speaker_id' => $speakerID
        //         ]);
        //     }
        // }
        return redirect()->route('organization.event.sessions', [$organizationID, $eventID])->with('berhasil', 'Session telah berhasil Diedit');
    }
    public function delete($organizationID, $eventID, $sessionID)
    {
        // --------- Metode lama delete data secara fisik -----------
        // $deleteData = Session::where('id', $sessionID)->delete();
        // $deleteSpeaker = SessionSpeaker::where('session_id',$sessionID)->delete();
        // -----------------------------------------------------------

        // -------- Metode baru soft delete -----------------------------------
        // Delete session akan melakukan soft delete juga di ticket create page
        $deleteData = Session::where('id', $sessionID)->update(['deleted' => 1]);
        $tickets = Session::where('id', $sessionID)->first()->tickets;
        for ($i = 0; $i < count($tickets); $i++) {
            Ticket::where('id', $tickets[$i]->id)->update(['deleted' => 1]);
        }
        return redirect()->route('organization.event.sessions', [$organizationID, $eventID])->with('berhasil', 'Session telah berhasil Dihapus');
    }

    public function url($organizationID, $eventID, $sessionID)
    {
        $dataReturn = [];
        $myData = UserController::me();
        $url = Session::where('id', $sessionID)->get();
        $session = Session::where('id', $sessionID)->first();

        $link = "";
        $e = "";
        $p = "";
        $e = "";
        $id = [];
        $pass = "";
        $password = [];

        $linkFor = [];
        for ($i = 0; $i < count($url); $i++) {
            if (preg_match("/zoom.us/i", $url[$i]->link)) {
                array_push($linkFor, 'zoom');
            } else if (preg_match("/youtube.com/i", $url[$i]->link)) {
                array_push($linkFor, "youtube");
            } else if (preg_match("/rtmp-stream-key/i", $url[$i]->link)) {
                array_push($linkFor, "rtmp-stream-key");
            } else if (preg_match("/webrtc-video-conference/i", $url[$i]->link)) {
                array_push($linkFor, "webrtc-video-conference");
            }
        }

        if (count($linkFor) == 0) {
            return redirect()->back();
        }

        for ($j = 0; $j < count($url); $j++) {
            if ($linkFor[$j] == "zoom") {

                $link = $url[$j]->link;
                $e = explode("?", $link);
                if (count($e) == 0) {
                    return redirect()->route('organization.event.sessions', [$organizationID, $eventID])->with('gagal', "Tombol mulai Zoom hanya untuk link zoom meeting");
                } else {
                    $p = explode("/", $e[0]);
                }
                $e = explode("?", $link);
                if (count($p) == 0) {
                    return redirect()->route('organization.event.sessions', [$organizationID, $eventID])->with('gagal', "Tombol mulai Zoom hanya untuk link zoom meeting");
                } else {
                    array_push($id, $p[count($p) - 1]);
                }
                if (count($e) == 0 || count($e) == 1) {
                    return redirect()->route('organization.event.sessions', [$organizationID, $eventID])->with('gagal', "Tombol mulai Zoom hanya untuk link zoom meeting => link = " . $e[0]);
                } else {
                    $pass = explode("pwd=", $e[1]);
                }
                if (count($pass) == 0 || count($pass) == 1) {
                    return redirect()->route('organization.event.sessions', [$organizationID, $eventID])->with('gagal', "Tombol mulai Zoom hanya untuk link zoom meeting pass = " . $pass[0]);
                } else {
                    array_push($password, $pass[1]);
                }

            } else if ($linkFor[$j] == "youtube") {
                array_push($id, "");
                array_push($password, "");
            } else if ($linkFor[$j] == "rtmp-stream-key"){
                $streamKey = $this->getStreamKey($sessionID, '');
                if($streamKey == null){
                    return redirect()->back()->with('gagal', 'Stream key failed getting from server');
                }
                $link = env('STREAM_SERVER') . '/streams/'. $streamKey .'/index.m3u8?session=' . $sessionID;
            } else if ($linkFor[$j] == "webrtc-video-conference"){
                $link = env('STREAM_SERVER');
            }
        }

        $url_leave = route('organization.event.session.url', [$organizationID, $eventID, $sessionID]);

        $dataReturn += [
            'id' => $id,
            //Bertipe Array
            'password' => $password,
            //Bertipe Array
            // 'streamWith' => $linkFor[0],
            'nama_peserta' => $myData->name,
            'url_leave' => $url_leave,
            'email_peserta' => $myData->email,
            'url' => $url, //Bertipe Array
            // Kebutuhan untuk stream dengan RTMP & webrtc
            'xAccessToken' => session('x-access-token'),
            'link' => $link
        ];

        $totalInvitation = UserController::getNotifyInvitation($myData->id);

        $categoryList = [];
        $exhibitorsGroup = [];
        foreach ($session->event->exhibitors->groupBy('category') as $key => $value) {
            $category = BoothCategory::where('event_id', $session->event->id)->where('name', $key)->first();
            $icon = asset('images/default_icon.png');
            $priority = 9999;
            if ($category) {
                if ($category->icon != 'default_icon.png') {
                    $icon = asset('storage/event_assets/' . $session->event->slug . '/exhibitors/exhibitor_cat_icon/' . $category->icon);
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
            'startSession' => $session,
            'streamType' => $linkFor[0],
            //Bertipe Array
            'streamPage' => true,
            'paramPage' => 'byAdmin',
            'organizationID' => $organizationID,
            'eventID' => $eventID,
            'sessionID' => $sessionID,
            'myData' => $myData,
            'organization' => Organization::where('id', $organizationID)->first(),
            'totalInvite' => $totalInvitation,
            'event' => $session->event,
            'exhibitors' => $newExhibitorsGroup,
        ];

        return view('user.streaming-new', $dataReturn);
    }
    public function joinStream($purchaseID)
    {
        $dataReturn = [];
        $purchase = Purchase::where('id', $purchaseID)->first();
        $myData = UserController::me();
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
            $streamKey = $this->getStreamKey('', $purchaseID);
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
                'xAccessToken' => session('x-access-token'),
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
                'xAccessToken' => session('x-access-token'),
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

        return view('user.streaming-new', $dataReturn);
    }

    public function joinStreamSpc($sessionID, $rundownID)
    {
        $session = "";
        // $myData = UserController::me();
        if ($sessionID == '0' && $rundownID != '0') {
            try {
                $event = Rundown::where('id', $rundownID)->first()->event;
            } catch (\Throwable $th) {
                return abort('404');
            }

            $isSpeaker = UserController::isSpeaker($event->id);
            if (count($isSpeaker) == 0) {
                return abort('403');
            }
            $sessions = $event->sessions;
            $allSession = [];
            // ------- Cari session yang statusnya belum dihapus --------------
            for ($i = 0; $i < count($sessions); $i++) {
                if ($sessions[$i]->deleted == 0) {
                    array_push($allSession, $sessions[$i]);
                }
            }

            dd($allSession);
            for ($i = 0; $i < count($allSession); $i++) {
                $rundown = Rundown::where('id', $rundownID)->first();
                $rundownStart = new DateTime($rundown->start_date . ' ' . $rundown->start_time, new \DateTimeZone('Asia/Jakarta'));
                $rundownEnd = new DateTime($rundown->end_date . ' ' . $rundown->end_time, new \DateTimeZone('Asia/Jakarta'));
                ;
                $sessionStart = new DateTime($allSession[$i]->start_date . ' ' . $allSession[$i]->start_time, new \DateTimeZone('Asia/Jakarta'));
                $sessionEnd = new DateTime($allSession[$i]->start_date . ' ' . $allSession[$i]->start_time, new \DateTimeZone('Asia/Jakarta'));
                if ($rundownStart >= $sessionStart || $rundownEnd <= $sessionEnd) {
                    $session = $allSession[$i];
                    $i = count($allSession);
                }
            }
        } else if ($rundownID == '0' && $sessionID != '0') {
            try {
                $event = Session::where('id', $sessionID)->first()->event;
            } catch (\Throwable $th) {
                return abort('404');
            }
            $isExhibitor = UserController::isExihibitor($event->id);
            if (count($isExhibitor) == 0) {
                return abort('403');
            }
            $session = Session::where('id', $sessionID)->first();
        } else {
            return abort('403');
        }


        if ($session != "") {
            return $this->url($session->event->organizer_id, $session->event->id, $session->id);
        } else {
            return abort('404');
        }
    }
}
<?php

namespace App\Http\Controllers;

use Str;
use Session as Sesi;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Session;
use App\Models\Organization;
use App\Models\Purchase;
use App\Models\EventHandout;
use App\Models\LoungeEvent;
use App\Models\User;
use App\Models\Handbook;
use App\Models\ReceptionistEvent;
use App\Models\OrganizationTeam;
use App\Models\Rundown;
use Illuminate\Http\Request;
use Image;
use File;
use App;
use App\Models\SessionSpeaker;
use App\Models\Category;
use App\Models\SlugCustom;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use DateTime;

class EventController extends Controller
{
    // public function __construct(Request $request)
    // {
    //     $myData = UserController::me();
    //     $isExihibitor = UserController::isExihibitor($request->route()->parameters['eventID']);
        
    // }

    // Jalankan pembuatan kode unik event saat dipanggil
    // 1. Open event management
    // 2. Menyimpan event baru
    private function createUniqueID($eventID){
        $event = Event::where('id', $eventID)->get();
        if(count($event) == 0){
            return abort('403');
        }else{
            $randomStr = new RandomStrController();
            $datetime = new DateTime();
            $strUnique = $randomStr->get().$datetime->format('d-M-Y');
            // dd($strUnique);
            if($event[0]->unique_code == null || $event[0]->unique_code == ""){
                Event::where('id',$eventID)->update([
                    'unique_code' => $strUnique
                ]);
            }
        }
    }

    public static function get($filter = NULL) {
        if ($filter == NULL) {
            return new Event;
        }
        return Event::where($filter);
    }
     // Sebelumnya. untuk menambahkan event online - offline beda page

    // Page add event online
    // public function create($organizationID) {
    //     $myData = UserController::me();
    //     $categories = Category::all();
    //     $types = config('agendakota')['event_types'];
    //     $organization = OrganizationController::get([
    //         ['id', '=', $organizationID]
    //     ])->first();
    //     $totalInvite = UserController::getNotifyInvitation($myData->id);
    //     $ongkirCtrl = new OngkirController();
    //     $provinces = $ongkirCtrl->get_province();
    //     // dd($provinces);
    //     // $citys = $ongkirCtrl->get_city('1');
    //     return view('user.organization.event.create', [
    //         'myData' => $myData,
    //         'organizationID' => $organizationID,
    //         'totalInvite' => $totalInvite,
    //         'eventID' => 0,
    //         'categories' => $categories,
    //         'types' => $types,
    //         'organization' => $organization,
    //         'provinces' => $provinces,
    //         'cityID' => $ongkirCtrl,
    //     ]);
    // }

    // Page add event offline
    // public function createSecond($organizationID) {
    //     $myData = UserController::me();
    //     $categories = Category::all();
    //     $types = config('agendakota')['event_types'];
    //     $organization = OrganizationController::get([
    //         ['id', '=', $organizationID]
    //     ])->first();
    //     $totalInvite = UserController::getNotifyInvitation($myData->id);
    //     $ongkirCtrl = new OngkirController();
    //     $provinces = $ongkirCtrl->get_province();
    //     // dd($provinces);
    //     // $citys = $ongkirCtrl->get_city('1');
    //     return view('user.organization.event.create2', [
    //         'myData' => $myData,
    //         'organizationID' => $organizationID,
    //         'totalInvite' => $totalInvite,
    //         'eventID' => 0,
    //         'categories' => $categories,
    //         'types' => $types,
    //         'organization' => $organization,
    //         'provinces' => $provinces,
    //         'cityID' => $ongkirCtrl,
    //     ]);
    // }

    public function verifySessionBuyed($event)
    {
        $sessions = $event->sessions;
        $sessionBuyed = [];
        $totalBuyed = 0;
        for($i=0; $i<count($sessions); $i++){
            $tickets = $sessions[$i]->tickets;
            $tmpBuyed = 0;
            for($j=0; $j<count($tickets); $j++){
                $purchases = Purchase::where('ticket_id', $tickets[$j]->id)->get();
                $tmpBuyed += count($purchases);
            }
            array_push($sessionBuyed, ['data'=>$sessions[$i], 'count'=>$tmpBuyed]);
            $totalBuyed += $tmpBuyed;
            
        }
        return ['total'=>$totalBuyed, 'dataBuyed'=>$sessionBuyed];
    }

    public function getPurchases($session)
    {
        $purchases = 0;
        $tickets = Ticket::where('session_id',$session->id)->get();
        for($i=0; $i<count($tickets); $i++){
            $purchases += count($tickets[$i]->purchases);
        }
        return $purchases;
    }

    public function create($organizationID) {
        $myData = UserController::me();
        $categories = Category::all();
        $types = config('event_types');
        $organization = OrganizationController::get([
            ['id', '=', $organizationID]
        ])->first();
        $totalInvite = UserController::getNotifyInvitation($myData->id);
        $ongkirCtrl = new OngkirController();
        $provinces = $ongkirCtrl->get_province();
        $topics = config('agendakota')['event_topics'];
        // dd($provinces);
        // $citys = $ongkirCtrl->get_city('1');
        return view('user.organization.event.create', [
            'myData' => $myData,
            'organizationID' => $organizationID,
            'totalInvite' => $totalInvite,
            'eventID' => 0,
            'categories' => $categories,
            'topics' => $topics,
            'types' => $types,
            'organization' => $organization,
            'provinces' => $provinces,
            'cityID' => $ongkirCtrl,
        ]);
    }

    public function store($organizationID, Request $request) {
        
        $myData = UserController::me();
        $organization = Organization::where('id',$organizationID)->first();
        $validateRule = [
            'name' => 'required|max:191',
            'logo' => 'required|mimes:jpg,png,jpeg|max:'.($organization->user->package->max_attachment*1024),
            'description' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            // 'start_time' => 'required',
            // 'end_time' => 'required',
            'type' => 'required',
            'city' => 'required',
            'province' => 'required',
            'instagram' => 'active_url|nullable',
            'twitter' => 'active_url|nullable',
            'website' => 'active_url|nullable',
            'facebook' => 'active_url|nullable',
            // 'punchline' => 'required',
            'execution_type' => 'required',
        ];

        if($request->execution_type == 'offline' || $request->execution_type == 'hybrid'){
            $validateRule['location'] = 'required';
        }

        $validateCustomMsg = [
            'required' => 'Kolom :Attribute wajib diisi',
            'name.required' => 'Kolom nama event harus diisi',
            'logo.required' => 'Kolom logo event harus diisi',
            'description.required' => 'Kolom deskripsi event wajib diisi',
            'start_date.required' => 'Tanggal & waktu mulai acara harus diisi',
            'end_date.required' => 'Tanggal & waktu akhir acara harus diisi',
            // 'start_time.required' => 'Waktu mulai acara harus diisi',
            // 'end_time.required' => 'Waktu akhir acara harus diisi',
            'name.max' => 'Field nama tidak boleh lebih dari 20 huruf',
            'logo.max' => 'Maksimal upload file '.$organization->user->package->max_attachment.' Mb',
            'mimes' => 'Tipe file yang diterima jpg, png, atau jpeg',
            'active_url'=> ':Attribute Harus Diisi Dengan URL ',
        ];

        $validateData = $this->validate($request, $validateRule, $validateCustomMsg);

        // -------- Cek date-time start <= date-time end ? ------------------------------------
        $startDate = new DateTime($request->start_date);
        $endDate = new DateTime($request->end_date);
        
        $startDt = $startDate->format('Y-m-d');
        $startTm = $startDate->format('H:i:s');
        $endDt = $endDate->format('Y-m-d');
        $endTm = $endDate->format('H:i:s');

        if($startDate > $endDate){
            return redirect()->back()->with('gagal','Input tanggal & waktu mulai kurang dari dari tanggal & waktu akhir');
        }
        // ------------------------------------------------------------------------------------

        // -------- Cek package aktif ? dan masih sesuai dengan config package ? --------------
        $isPkgActive = PackagePricingController::limitCalculator($organization->user);

        // Tidak boleh ada event yang start nya sama dalam satu organisasi
        $eventSameTime = Event::where('organizer_id',$organization->id)->where('start_date',$startDt)->where('deleted',0)->get();

        $paramCombine = false;
        // paramCombine adalah parameter untuk cek jumlahnya unlimited / sudah lewat batas
        if($organization->user->package->event_same_time <= -1){
            $paramCombine = false;
        }else{
            if(count($eventSameTime) > $organization->user->package->event_same_time){
                $paramCombine = true;
            }
        }

        if($isPkgActive == 0 || $paramCombine == true){
            if($isPkgActive == 0){
                return redirect()->back()->with('gagal','Paketmu sudah lewat satu bulan / belum dibayar');
            }else{
                return redirect()->back()->with('gagal','Event dengan tanggal mulai yang sama hanya boleh '.$organization->user->package->event_same_time.' / Organisasi');
            }
        }
        // ------------------------------------------------------------------------------------
        $timeSlug = new DateTime();
        $slug = Str::slug($request->name).$timeSlug->format('d-m-y_H-i-s').'_'.$myData->id.$organizationID;
        $breakdowns = $request->input('breakdowns');
        $breakdown =[];
        if(isset($breakdowns)){
            foreach($breakdowns as $break){
                $breakdown[] = $break;
            }
        }else{
            $breakdown[] ="KOSONG";
        }

        $typeExe = $request->execution_type;
        $location = "-";
        if($typeExe == 'offline' || $request->execution_type == 'hybrid'){
            $location = $request->location;
        }

        $logo = $request->file('logo');
        $logoFileName = $logo->getClientOriginalName();
        // if ($request->filled('instagram')) {
        //     $instagram = $request->instagram;
        // } else {
        //     $instagram = '-';
        // }
        // if ($request->filled('twitter')) {
        //     $twitter = $request->twitter;
        // } else {
        //     $twitter = '-';
        // }
        // if ($request->filled('website')) {
        //     $website = $request->website;
        // } else {
        //     $website = '-';
        // }
        $saveData = Event::create([
            'organizer_id' => $organizationID,
            'category' => $request->category,
            'logo' => $logoFileName,
            'slug' => $slug,
            'name' => $request->name,
            'type' => $request->type,
            'punchline' => $request->punchline,
            'description' => $request->description,
            'execution_type' => $typeExe,
            'location' => $location,
            'province' => $request->province,
            'city' => $request->city,
            'start_date' => $startDt,
            'end_date' => $endDt,
            'start_time' => $startTm,
            'end_time' => $endTm,
            'breakdown'=> implode(" ",$breakdown),
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'facebook' => $request->facebbok,
            'website' => $request->website,
            'featured' => 0,
            'is_publish' => 0,
            'has_withdrawn' => 0,
            'deleted' => 0,
            'snk' => $request->snk,
        ]);

        //Cek session centang atau tidak
        $sessionCheck = false;
        for($i = 0; $i<count($breakdown); $i++){
            if($breakdown[$i] == 'Stage and Session'){
                $sessionCheck = true;
            }
        }

        if($sessionCheck == false){
            $dataSession = [
                'event_id' => $saveData->id,
                'title' => $saveData->name,
                'start_date' => $saveData->start_date,
                'end_date' => $saveData->end_date,
                // Waktu session bertipe single harus sama dengan event
                'start_time' => $saveData->start_time,
                'end_time' => $saveData->end_time,
                // ----------------------------------------------------
                'overview' => 0
            ];

            $sessionSave = Session::create($dataSession);
        }


            $filePath = public_path('storage/event_assets/'.$slug.'/event_logo/thumbnail');
            $img = Image::make($logo->path());
            File::makeDirectory($filePath, $mode = 0777, true, true);


            $img->resize(1020, 408)->save($filePath.'/'.$logoFileName);

            // Jalankan function membuat unique_code ID event
            $this->createUniqueID($saveData->id);
        return redirect()->route('organization.event.rundowns', [$organizationID, $saveData->id])->withInput();

    }
    public function edit($organizationID, $id) {        
        $myData = UserController::me();
        $event = Event::find($id);
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $categories = Category::all();
        $types = config('event_types');
        $totalInvite = UserController::getNotifyInvitation($myData->id);
        $ongkirCtrl = new OngkirController();
        $provinces = $ongkirCtrl->get_province();
        $topics = config('agendakota')['event_topics'];

        // --------- Cek apakah jumlah sessionnya lebih dari satu ------------
        $sessions = $event->sessions;
        $multiSesssionBuyed = 0;
        if(count($sessions) > 1){
            $arrData = $this->verifySessionBuyed($event);
            $multiSesssionBuyed = $arrData['total'];
        }
        // -------------------------------------------------------------------

        return view('user.organization.event.edit', [
            'myData' => $myData,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'totalInvite' => $totalInvite,
            'event'=> $event,
            'types'=> $types,
            'categories' => $categories,
             'topics' => $topics,   
            'isManageEvent' => 1,
            'provinces' => $provinces,
            'cityID' => $ongkirCtrl,
            'allowEditSessionMode' => $multiSesssionBuyed,
        ]);
    }
    public function update($organizationID,$id, Request $request) {
        $myData = UserController::me();
        //breakdown
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $breakdowns = $request->input('breakdowns');        
        $breakdown = [];
        if(isset($breakdowns)){
            foreach($breakdowns as $break){
                $breakdown[] = $break;
            }
        }else{
            $breakdown[] ="KOSONG";
        }

        $validateRule = [
            'category' => 'required',
            'name' => 'required|max:191',
            'description' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            // 'start_time' => 'required',
            // 'end_time' => 'required',
            'instagram' => 'active_url|nullable',
            'twitter' => 'active_url|nullable',
            'website' => 'active_url|nullable',
            'facebook' => 'active_url|nullable',
            'type' => 'required',
            'city' => 'required',
            'province' => 'required',
            // 'punchline' => 'required',
            'execution_type' => 'required',
            'logo' => 'mimes:jpg,png,jpeg|max:'.($organization->user->package->max_attachment*1024),
        ];

        if($request->execution_type == 'offline' || $request->execution_type == 'hybrid'){
            $validateRule['location'] = 'required';
        }

        $message =[
            'required' => 'Kolom :Attribute Wajib Diisi',
            'active_url'=> ':Attribute Harus Diisi Dengan URL ',
            'name.max' => 'Field nama tidak boleh lebih dari 20 huruf',
            'logo.max' => 'Maksimal upload file '.$organization->user->package->max_attachment.' Mb',
            'mimes' => 'Tipe file yang diterima jpg, png, atau jpeg',
        ];

        $validateData = $this->validate($request, $validateRule, $message);

        // -------- Cek date-time start <= date-time end ? ------------------------------------
        $startDate = new DateTime($request->start_date);
        $endDate = new DateTime($request->end_date);
        
        $startDt = $startDate->format('Y-m-d');
        $startTm = $startDate->format('H:i:s');
        $endDt = $endDate->format('Y-m-d');
        $endTm = $endDate->format('H:i:s');

        if($startDate > $endDate){
            return redirect()->back()->with('gagal','Input tanggal & waktu mulai kurang dari dari tanggal & waktu akhir');
        }
        // ------------------------------------------------------------------------------------

        // -------- Cek package aktif ? dan masih sesuai dengan config package ? --------------
        $isPkgActive = PackagePricingController::limitCalculator($organization->user);

        // Tidak boleh ada event yang start nya sama dalam satu organisasi
        $eventSameTime = Event::where('organizer_id',$organization->id)->where('start_date',$startDt)->where('deleted',0)->get();

        $paramCombine = false;
        // paramCombine adalah parameter untuk cek jumlahnya unlimited / sudah lewat batas
        if($organization->user->package->event_same_time <= -1){
            $paramCombine = false;
        }else{
            if(count($eventSameTime) > $organization->user->package->event_same_time){
                $paramCombine = true;
                for($i=0; $i<count($eventSameTime); $i++){
                    if($eventSameTime[$i]->id == $id){
                        $paramCombine = false;
                    }
                }
            }
        }

        if($isPkgActive == 0 || $paramCombine == true){
            if($isPkgActive == 0){
                return redirect()->back()->with('gagal','Paketmu sudah lewat satu bulan / belum dibayar');
            }else{
                return redirect()->back()->with('gagal','Event dengan tanggal mulai yang sama hanya boleh '.$organization->user->package->event_same_time.' / Organisasi');
            }
        }
        // ------------------------------------------------------------------------------------

        $location = "-";
        if($request->execution_type == 'offline' || $request->execution_type == 'hybrid'){
            $location = $request->location;
        }

        $timeSlug = new DateTime();
        $slug = Str::slug($request->name).$timeSlug->format('d-m-y_H-i-s').'_'.$myData->id.$organizationID;

        //logo
        $logo = $request->file('logo');
        $event = Event::find($id);
        // -------- Mengambil data breakdown event lama -------------
        //          Untuk mengatasi waktu jika sessionnya tunggal
        $breakdownOld = $event->breakdown;
        // ----------------------------------------------------------

        // --------- Cek apakah jumlah sessionnya lebih dari satu ------------
        //  Mengatasi pelanggaran penggantian multi session ke single session
        $sessions = $event->sessions;
        $multiSesssionBuyed = 0;
        if(count($sessions) > 1){
            $arrData = $this->verifySessionBuyed($event);
            $multiSesssionBuyed = $arrData['total'];
        }

        if($multiSesssionBuyed > 0){
            array_push($breakdown, 'Stage and Session');
        }
        // -------------------------------------------------------------------
        
        if ($request->name != $event->name) {
            // dd("tidak sama");
            $newPath = public_path('storage/event_assets/'.$slug);
            File::makeDirectory($newPath, $mode = 0777, true, true);
            $slugLama = $event->slug;
            File::move(public_path("storage/event_assets/" . $slugLama), public_path("storage/event_assets/" . $slug));
            // File::delete('storage/event_assets/'.$slugLama);
        }else{
            $slug = $event->slug;
        }

        if($logo){
            // dd($event->logo);
            // Storage::delete(['public/event_assets/'.$slug.'/event_logo/'.$event->logo]);
            File::delete('storage/event_assets/'.$slug.'/event_logo/thumbnail/'.$event->logo);
            $logoFileName = $logo->getClientOriginalName();


            $filePath = public_path('storage/event_assets/'.$slug.'/event_logo/thumbnail');
            $img = Image::make($logo->path());
            File::makeDirectory($filePath, $mode = 0777, true, true);

            $img->resize(1020, 408)->save($filePath.'/'.$logoFileName);
            // $logo->storeAs('public/event_assets/'.$slug.'/event_logo/', $logoFileName);

        }else{
            $logoFileName = $event->logo;
        }

        $editData = Event::where('id',$id)->update([
            'organizer_id' => $organizationID,
            'category' => $request->category,
            'logo' => $logoFileName,
            'slug' => $slug,
            'name' => $request->name,
            'type' => $request->type,
            'punchline' => $request->punchline,
            'description' => $request->description,
            'execution_type' => $request->execution_type,
            'location' => $location,
            'province' => $request->province,
            'city' => $request->city,
            'start_date' => $startDt,
            'end_date' => $endDt,
            'start_time' => $startTm,
            'end_time' => $endTm,
            'breakdown'=> implode(" ",$breakdown),
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'facebook' => $request->facebbok,
            'website' => $request->website,
            'twn_url' => $request->twnUrl,
            'snk' => $request->snk,
        ]);

        // $logo->storeAs('public/event_logo', $logoFileName);

        //------------ Cek session berjenis multiple atau bukan --------
        $sessionCheck = false;
        for($i = 0; $i<count($breakdown); $i++){
            if($breakdown[$i] == 'Stage and Session'){
                $sessionCheck = true;
            }
        }

        $event = Event::where('id', $id)->first();

        // ----- Jika breakdown lama dan baru stage and session uncheck -> perbarui single session ------------
        if(($sessionCheck == false && preg_match('/Stage and Session/i', $breakdownOld) == false) || ($sessionCheck == true && preg_match('/Stage and Session/i', $breakdownOld) == false)){
            $dataSession = [
                'event_id' => $event->id,
                'title' => $event->name,
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'start_time' => $event->start_time,
                'end_time' => $event->end_time,
                'overview' => 0,
                'deleted' => 0
            ];
            $sessions = $event->sessions;
            $sessionSave = Session::where('id', $sessions[0]->id)->update($dataSession);
        }else if(($sessionCheck == false && preg_match('/Stage and Session/i', $breakdownOld) == true) && $multiSesssionBuyed == 0){
            // Jika awalnya centang, tapi cuma tunggal sesi yang sudah dibuat
            // cek apakah jumlah sesinya lebih dari satu ?
            if(count($sessions) > 1){
                // Hapus semua session ticket
                for($i=0; $i<count($sessions); $i++){
                    Session::where('id', $sessions[$i]->id)->delete();
                }
            }
            
            if(count($sessions) == 0){
                $dataSession = [
                    'event_id' => $event->id,
                    'title' => $event->name,
                    'start_date' => $event->start_date,
                    'end_date' => $event->end_date,
                    'start_time' => $event->start_time,
                    'end_time' => $event->end_time,
                    'overview' => 0
                ];
                $sessionSave = Session::create($dataSession);
            }
        }
        // ---------------------------------------------------------

        return redirect()->back()->with('berhasil', 'Event Anda Telah Berhasil diperbarui');
    }
    public function delete($organizationID, $eventID) {
        Event::find($eventID)->update([
            'deleted' => 1,
            'is_publish' => 0
        ]);
        return redirect()->back()->with('berhasil', 'Event Telah Berhasil Dihapus');
    }

    public function sessions($organizationID, $eventID) {
        $sessionCheck = Event::where('id', $eventID)->first();
        if($sessionCheck->breakdown == 'KOSONG'){
            return redirect()->route('organization.event.session.config', [$organizationID, $eventID]);
        }
        if(preg_match('/Stage and Session/i', $sessionCheck->breakdown) == false){
            return redirect()->route('organization.event.session.config', [$organizationID, $eventID]);
        }
        global $global;
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $global['organizationID'] = $organizationID;
        $myData = UserController::me();
        $sessions = SessionController::get([
            ['event_id', '=', $eventID],
            ['deleted','=',0]
        ])
        ->with('event.organizer')
        ->whereHas('event', function($query) {
            global $global;
            $query->where('organizer_id', '=', $global['organizationID']);
        })
        ->get();

        // ------- Mengecek apakah waktu di session ada yang tidak sesuai dengan event -----------------
        $notMatchDateTime = [];
        $notif = [];
        for($i=0; $i<count($sessions); $i++){
            $startSesn = new DateTime($sessions[$i]->start_date.$sessions[$i]->start_time);
            $endSesn = new DateTime($sessions[$i]->end_date.$sessions[$i]->end_time);
            
            $startEvt = new DateTime($sessionCheck->start_date.$sessionCheck->start_time);
            $endEvt = new DateTime($sessionCheck->end_date.$sessionCheck->end_time);
            
            if($startSesn < $startEvt || $endSesn > $endEvt){
                array_push($notMatchDateTime, $sessions[$i]);
            }
            // if($sessions[$i]->start_date < $sessionCheck->start_date || 
            //     $sessions[$i]->end_date > $sessionCheck->end_date ||
            //     $sessions[$i]->start_date > $sessionCheck->end_date ||
            //     $sessions[$i]->end_date < $sessionCheck->start_date){
            //         array_push($notMatchDateTime, $sessions[$i]);
            // }else if($sessions[$i]->start_time < $sessionCheck->start_time || 
            //         $sessions[$i]->end_time > $sessionCheck->end_time ||
            //         $sessions[$i]->start_time > $sessionCheck->end_time ||
            //         $sessions[$i]->end_time < $sessionCheck->start_time){

            //         if($sessions[$i]->start_date <= $sessionCheck->start_date || 
            //             $sessions[$i]->end_date >= $sessionCheck->end_date){
            //                 array_push($notMatchDateTime, $sessions[$i]);  
            //         }
            // }
        }
        
        // ---------------------------------------------------------------------------------------------

        // ---- Cari data foreign rundwon dari session berstatus deleted ------------------
        // $sessionRundownDel = 0;
        $sessionRundownDelData = [];
        for($i=0; $i<count($sessions); $i++){
            $startRundown = $sessions[$i]->start_rundown_id;
            $endRundown = $sessions[$i]->end_rundown_id;
            if($startRundown != null){
                // dump($sessions[$i]->rundownStart);
                if($sessions[$i]->rundownStart->deleted == 1){
                    // $sessionRundownDel += 1;
                    array_push($sessionRundownDelData,$sessions[$i]);
                }
            }
            if($endRundown != null){
                // dump($sessions[$i]->rundownEnd);
                if($sessions[$i]->rundownEnd->deleted == 1){
                    // $sessionRundownDel += 1;
                    array_push($sessionRundownDelData,$sessions[$i]);
                }
            }
        }
        // --------------------------------------------------------------------------------

        // ----------- Menghitung ticket terjual dari session -----------------------------
        // Untuk memberikan notifikasi saat session dihapus
        $sessionBuyed = [];
        for ($i=0; $i < count($sessions); $i++) { 
            $purchases = $this->getPurchases($sessions[$i]);
            array_push($sessionBuyed, $purchases);
        }
        
        // --------------------------------------------------------------------------------

        $purchases =Purchase::where('event_id',$eventID)->with(['events.sessions','tickets'])->get();
        // dd($purchases);
        $rundowns = Rundown::where('event_id', $eventID)->where('deleted', 0)->get();
        // dd($rundowns[0]->where('id', 1)->first());
        $event = Event::find($eventID);
        // dd($sessionID);
        return view('user.organization.event.session', [
            'sessions' => $sessions,
            'myData' => $myData,
            'event' => $event,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'purchases' => $purchases,
            'rundowns' => $rundowns,
            'isManageEvent' => 1,
            'notMatchConfig' => $notMatchDateTime,
            'sessionRundownDelData' => $sessionRundownDelData,
            'sessionBuyed' => $sessionBuyed
        ]);
    }
    public function tickets($organizationID, $eventID) {
        $myData = UserController::me();
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        // $purchases = DB::table('Purchases')
        // ->select('*', DB::raw('count(*) as total'))
        // ->groupBy('ticket_id')
        // ->where('event_id',$eventID)->where('pay_state', 'Terbayar')->get();
        
        // dd($purchases);
        $eventdetails= EventController::get([['id','=',$eventID]]);     
        $event = self::get([
            ['id', '=', $eventID]
        ])
        ->with('sessions.tickets')
        ->first();

        // dd($purchases);

        return view('user.organization.event.ticket', [
            'eventdetails' => $eventdetails,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'eventID' => $eventID,
            'myData' => $myData,
            'event' => $event,
            // 'purchases' => $purchases,
            'isManageEvent' => 1
        ]);
    }
    public function speakers($organizationID, $eventID) {
        $myData = UserController::me();
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $events = self::get([
            ['id', '=', $eventID]
        ])
        ->with('speakers')
        ->first();
        // dd($events);
        return view('user.organization.event.speaker', [
            'events' => $events,
            'event' => $events,
            'myData' => $myData,
            'organization' => $organization,
            'organizationID' => $organizationID,
            'eventID' => $eventID,
            // 'sessions' => $sessions,
            'isManageEvent' => 1
        ]);
    }
    public function sponsors($organizationID, $eventID) {
        $myData = UserController::me();
        $event = Event::where('id', $eventID)->with(['sponsors','organizer'])->first();
        $organization = $event->organizer;
        //dd($event->organizer);
        $sponsorTypes = config('agendakota')['sponsor_types'];

        return view('user.organization.event.sponsor', [
            'event' => $event,
            'sponsors' => $event->sponsors->sortBy('priority'),
            'organization' => $organization,
            'organizationID' => $organizationID,
            'myData' => $myData,
            'sponsorTypes' => $sponsorTypes,
            'isManageEvent' => 1
        ]);
    }
    public function mediaPartners($organizationID, $eventID) {
        $myData = UserController::me();
        $event = Event::where('id', $eventID)->with(['media_partners','organizer'])->first();
        $organization = $event->organizer;
        //dd($event->organizer);
        $sponsorTypes = config('agendakota')['media_partner_types'];

        return view('user.organization.event.media-partner', [
            'event' => $event,
            'organization' => $organization,
            'organizationID' => $organizationID,
            'myData' => $myData,
            'sponsorTypes' => $sponsorTypes,
            'isManageEvent' => 1
        ]);
    }
    public function overview($organizationID, $eventID) {
        $event = Event::find($eventID);
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        
        return view('user.organization.event.overview', [
            'organizationID' => $organizationID,
            'organization' => $organization,
            'event' => $event,
            'isManageEvent' => 1
        ]);
    }
    public function quiz($organizationID, $eventID) {
        $myData = UserController::me();
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $event = Event::where('id', $eventID)
        ->with('sessions')
        ->first();

        $quiz = QuizController::get([
            ['event_id', '=', $eventID]
        ])
        ->with('session')
        ->paginate(10);

        return view('user.organization.event.quiz', [
            'quiz' => $quiz,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'event' => $event,
            'isManageEvent' => 1,
            // 'totalInvite' => 0,
            'myData' => $myData,
        ]);
    }
    public function exhibitors($organizationID, $eventID)
    {
        global $global;
        $global['organizationID'] = $organizationID;
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $myData = UserController::me();
        $event = Event::where('id',$eventID)->with(['exhibitors'])->first();
        
        // dd(UserController::isExihibitor($eventID));

        $exhibitors = ExhibitorController::get([['event_id', '=', $eventID],])->with('event.organizer')->whereHas('event', function($query) {
            global $global;
            $query->where('organizer_id', '=', $global['organizationID']);
        })->get();

        return view('user.organization.event.exhibitor', [
            'exhibitors' => $exhibitors,
            'myData' => $myData,
            'event' => $event,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'isManageEvent' => 1
        ]);
    }
    public function handouts($organizationID, $eventID)
    {
        global $global;
        $global['organizationID'] = $organizationID;
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $myData = UserController::me();
        $handouts = EventHandoutController::get([
            ['event_id', '=', $eventID]
        ])
        ->with('event.organizer')
        ->get();
        // return $handouts;
        $event = Event::find($eventID);
        $getSlug = Event::where('id', $eventID)->get('slug');
        foreach ($getSlug as $sl){
            $slug = $sl->slug;
        }
        // dd($organizationID);
        // $organization =Event::where('organizer_id',$organizationID)->with(['handouts','organizer'])->get();
        // foreach ($organization as $o){

        // }
        $getFoto= EventHandout::where([
            ['type', '=', 'foto'],
            ['event_id', '=', $eventID]
        ])
        ->whereHas('event', function($query) {
            global $global;
            $query->where('organizer_id', '=', $global['organizationID']);
        })
        ->get();
        $getVideo= EventHandout::where([
            ['type', '=', 'video'],
            ['event_id', '=', $eventID]
        ])
        ->whereHas('event', function($query) {
            global $global;
            $query->where('organizer_id', '=', $global['organizationID']);
        })
        ->get();
        $getDokumen= EventHandout::where([
            ['type', '=', 'dokumen'],
            ['event_id', '=', $eventID]
        ])
        ->whereHas('event', function($query) {
            global $global;
            $query->where('organizer_id', '=', $global['organizationID']);
        })
        ->get();
        return view('user.organization.event.handout', [
            'handouts' => $handouts,
            'myData' => $myData,
            'event' => $event,
            'slug' => $slug,
            'getFoto' =>$getFoto,
            'getVideo' =>$getVideo,
            'getDokumen' =>$getDokumen,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'isManageEvent' => 1
        ]);
    }

    public function lounge($organizationID, $eventID)
    {
        global $global;
        $global['organizationID'] = $organizationID;
        $myData = UserController::me();
        $event = Event::find($eventID);
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $exhibitors = ExhibitorController::get([
            ['event_id', '=', $eventID],

        ])
        ->with('event.organizer')
        ->whereHas('event', function($query) {
            global $global;
            $query->where('organizer_id', '=', $global['organizationID']);
        })
        ->get();

        $loungeData = [];

        try{
            $loungeData = LoungeEvent::where('event_id', $eventID)->get();
        }catch(exception $e){
            $loungeData = [[
                'table_count' => 0,
                'chair_table' => 0
            ]];
        }

        return view('user.organization.event.lounge', [
            'exhibitors' => $exhibitors,
            'myData' => $myData,
            'lounge' => $loungeData,
            'eventID' => $eventID,
            'event' => $event,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'isManageEvent' => 1
        ]);
    }

    // public function viplounge($organizationID, $eventID)
    // {
    //     global $global;
    //     $global['organizationID'] = $organizationID;
    //     $myData = UserController::me();
    //     $event = Event::find($eventID);

    //     $exhibitors = ExhibitorController::get([
    //         ['event_id', '=', $eventID],

    //     ])
    //     ->with('event.organizer')
    //     ->whereHas('event', function($query) {
    //         global $global;
    //         $query->where('organizer_id', '=', $global['organizationID']);
    //     })
    //     ->get();
    //     return view('user.organization.event.vip-lounge', [
    //         'exhibitors' => $exhibitors,
    //         'myData' => $myData,
    //         'event' => $event,
    //         'organizationID' => $organizationID,
    //         'organization' => $organization,
    //         'isManageEvent' => 1
    //     ]);
    // }

    public function eventoverview($organizationID, $eventID)
    {
        global $global;
        $global['organizationID'] = $organizationID;
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $myData = UserController::me();
        $event = Event::find($eventID);
        $eventdetails= EventController::get([['id','=',$eventID]])
        ->with(['sponsors','exhibitors','sessions.tickets'])->get();
        $purchasesMain = Purchase::where('event_id', $eventID)->with('tickets')->get();
        $purchases = [];
        for($i=0; $i<count($purchasesMain); $i++){
            if($purchasesMain[$i]->payment->pay_state == 'Terbayar'){
                array_push($purchases, $purchasesMain[$i]);
            }
        }
        $customLink = SlugCustom::where('event_id', $eventID)->get();
        
        $isPkgActive = PackagePricingController::limitCalculator($organization->user);
        // dd($purchases);
        // dd($eventdetails);
        // $exhibitors = ExhibitorController::get([
        //     ['event_id', '=', $eventID],

        // ])
        // ->with('event.organizer')
        // ->whereHas('event', function($query) {
        //     global $global;
        //     $query->where('organizer_id', '=', $global['organizationID']);
        // })
        // ->get();
        // dd($event, $eventdetails, $event->sponsors);
        // $allUser = User::all();
        // dd($allUser[0]->attendances()->whereMonth('created_at', Carbon::now()->format('m')));
        return view('user.organization.event.event-overview', [
            'eventdetails' => $eventdetails,
            'myData' => $myData,
            'isPkgActive' => $isPkgActive,
            'event' => $event,
            'customLink' => $customLink,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'purchases' => $purchases,
            'isManageEvent' => 1
        ]);
    }
    public function boothCategory($organizationID, $eventID) {
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $myData = UserController::me();
        $event = Event::find($eventID);
        $categories = ExhibitorController::getCategories([
            ['event_id', $eventID]
        ])->get();
        
        return view('user.organization.event.boothCategory', [
            'myData' => $myData,
            'event' => $event,
            'eventID' => $eventID,
            'categories' => $categories,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'isManageEvent' => 1,
        ]);
    }
    public function eventPublish($organizationID, $eventID){
        // dd($organizationID);
        $update = Event::where('id', $eventID)->update([
            'is_publish' => 1
        ]);

        return redirect()->back()->with('berhasil', 'Event ini berhasil kamu publish / set public');
    }
    public function eventUnPublish($organizationID, $eventID){
        $update = Event::where('id', $eventID)->update([
            'is_publish' => 0
        ]);

        return redirect()->back()->with('berhasil', 'Event ini berhasil kamu set private');
    }
    public function site($organizationID, $eventID) {
        $myData = UserController::me();
        $organization = Organization::where('id', $organizationID)->first();
        $event = Event::where('id', $eventID)->with('site')->first();
        $message = Sesi::get('message');

        return view('user.organization.event.site', [
            'myData' => $myData,
            'event' => $event,
            'site' => $event->site,
            'organization' => $organization,
            'organizationID' => $organizationID,
            'isManageEvent' => 1,
            'message' => $message
        ]);
    }
    public function certificate($organizationID, $eventID) {
        $myData = UserController::me();
        $event = Event::where('id', $eventID)->with('certificate')->first();
        $organization = Organization::where('id', $organizationID)->first();

        return view('user.organization.event.certificate', [
            'myData' => $myData,
            'event' => $event,
            'organization' => $organization,
            'organizationID' => $organizationID,
            'isManageEvent' => 1
        ]);
    }
    public function handbooks($organizationID, $eventID){

        global $global;
        $global['organizationID'] = $organizationID;
        $myData = UserController::me();
        $event = Event::find($eventID);
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $handbooks = Handbook::where('event_id', $eventID)->get();
        // ->with('event.organizer')
        // ->whereHas('event', function($query) {
        //     global $global;
        //     $query->where('organizer_id', '=', $global['organizationID']);
        // })
        // ->get();
        return view('user.organization.event.handbook', [
            'handbooks' => $handbooks,
            'myData' => $myData,
            'event' => $event,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'isManageEvent' => 1
        ]);
    }
    public function ticketSelling($organizationID, $eventID){
        global $global;
        $global['organizationID'] = $organizationID;
        $myData = UserController::me();
        $event = Event::find($eventID);
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $tickets = Purchase::where('event_id', $eventID)->orderBy('created_at', 'DESC')->paginate(10);
        
        return view('user.organization.event.ticket-selling', [
            'tickets' => $tickets,
            'myData' => $myData,
            'event' => $event,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'isManageEvent' => 1
        ]);
    }
    public function vipLounge($organizationID, $eventID){
        global $global;
        $global['organizationID'] = $organizationID;
        $myData = UserController::me();
        $event = Event::find($eventID);
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $exhibitors = ExhibitorController::get([
            ['event_id', '=', $eventID],

        ])
        ->with('event.organizer')
        ->whereHas('event', function($query) {
            global $global;
            $query->where('organizer_id', '=', $global['organizationID']);
        })
        ->get();
        $sessions = Session::where('event_id', $eventID)->get();
        return view('user.organization.event.vip-lounge', [
            'exhibitors' => $exhibitors,
            'myData' => $myData,
            'event' => $event,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'isManageEvent' => 1,
            'sessions' => $sessions,
        ]);   
    }
    public function receptionist($organizationID, $eventID){
        global $global;
        $global['organizationID'] = $organizationID;
        $myData = UserController::me();
        $event = Event::find($eventID);
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $receptionists = ReceptionistEvent::where('event_id', $eventID)->paginate(10);
       $teams = OrganizationTeam::where('organization_id', $organizationID)->get();
        return view('user.organization.event.receptionist', [
            'receptionists' => $receptionists,
            'myData' => $myData,
            'event' => $event,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'isManageEvent' => 1,
            'teams' => $teams,
        ]);
    }
    public function configSession($organizationID, $eventID)
    {
        $sessionCheck = Event::where('id', $eventID)->first();
        if (preg_match('/Stage and Session/i', $sessionCheck->breakdown) || count($sessionCheck->sessions) > 1){
            return abort(403, 'Sessionmu tidak tunggal.');
        }
        // if($sessionCheck->breakdown != 'KOSONG'){
        //     return abort(403, 'Sessionmu tidak tunggal.');
        // }
        global $global;
        $global['organizationID'] = $organizationID;
        $myData = UserController::me();
        $event = Event::find($eventID);
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $purchases =Purchase::where('event_id',$eventID)->with(['events.sessions','tickets'])->get();

        $SessionSpeakers =SessionSpeaker::with('speaker')->get();
        $speakers = SpeakerController::get([['event_id', '=', $eventID]])->get();
        
        return view('user.organization.event.session-config', [
            'myData' => $myData,
            'event' => $event,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'isManageEvent' => 1,
            'sessions' => $sessionCheck->sessions,
            'SessionSpeakers' => $SessionSpeakers,
            'speakers' => $speakers,
            'purchases' => $purchases
        ]);
    }
    public function rundowns($organizationID, $eventID){

        global $global;
        $global['organizationID'] = $organizationID;
        $myData = UserController::me();
        $event = Event::find($eventID);
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $rundowns = Rundown::where('event_id', $eventID)->where('deleted', 0)->orderBy('start_date', 'ASC')->orderBy('start_time', 'ASC')->paginate(10);
        // foreach ($rundowns as $rundown){
        //     dump($rundown);
        // }
        // dd($rundowns);
        
        // Jalankan function create unique ID
        // Rundown dipilih, karena menu rundown adalah pintu masuk awal ke event manajemen
        $this->createUniqueID($eventID);

        // ------- Mengecek apakah waktu di rundown ada yang tidak sesuai dengan event -----------------
        $notMatchDateTime = [];
        for($i=0; $i<count($rundowns); $i++){
            $startRdn = new DateTime($rundowns[$i]->start_date.$rundowns[$i]->start_time);
            $endRdn = new DateTime($rundowns[$i]->end_date.$rundowns[$i]->end_time);
            
            $startEvt = new DateTime($event->start_date.$event->start_time);
            $endEvt = new DateTime($event->end_date.$event->end_time);
            
            if($startRdn < $startEvt || $endRdn > $endEvt){
                array_push($notMatchDateTime, $rundowns[$i]);
            }
            // if($rundowns[$i]->start_date < $event->start_date || 
            //     $rundowns[$i]->end_date > $event->end_date ||
            //     $rundowns[$i]->start_date > $event->end_date ||
            //     $rundowns[$i]->end_date < $event->start_date){
            //         array_push($notMatchDateTime, $rundowns[$i]);
            // }else if($rundowns[$i]->start_time < $event->start_time || 
            //         $rundowns[$i]->end_time > $event->end_time ||
            //         $rundowns[$i]->start_time > $event->end_time ||
            //         $rundowns[$i]->end_time < $event->start_time){

            //         if($rundowns[$i]->start_date <= $event->start_date || 
            //             $rundowns[$i]->end_date >= $event->end_date){
            //                 array_push($notMatchDateTime, $rundowns[$i]);  
            //         }
            // }
        }
        
        // ---------------------------------------------------------------------------------------------

        $SessionSpeakers =SessionSpeaker::with('speaker')->get();
        // dd($SessionSpeakers);
        $speakers = SpeakerController::get([['event_id', '=', $eventID]])->get();
        return view('user.organization.event.rundown', [
            'myData' => $myData,
            'event' => $event,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'isManageEvent' => 1,
            'rundowns' => $rundowns,
            'speakers' => $speakers,
            'SessionSpeakers' => $SessionSpeakers,
            'notMatchConfig' => $notMatchDateTime,
        ]);   
    }
    // function khusus admin -> melakukan set featured & change order fetured
    public function setFeatured($eventID)
    {
        // pertama di set featured = di atur di top featured
        // Cari nilai featured tertinggi
        $lastFeatured = Event::where('deleted',0)->where('featured','>',0)->orderBy('featured','DESC')->first();
        $value = 0;
        if($lastFeatured != null){
            $value = (int)$lastFeatured->featured + 1;
        }else{
            $value = 1;
        }
        Event::where('id', $eventID)->update([
            'featured' => $value,
        ]);
        return redirect()->back()->with('berhasil', 'Event telah diubah menjadi featured teratas');   
    }
    public function unsetFeatured($eventID)
    {
        Event::where('id', $eventID)->update([
            'featured' => 0,
        ]);
        return redirect()->back()->with('berhasil', 'Event telah dihapus dari featured event');   
    }
    public function getQREvent($organizationID,$eventID){
        global $global;
        $global['organizationID'] = $organizationID;
        $myData = UserController::me();
        $event = Event::find($eventID);
        $organization = Organization::where('id', $organizationID)->with('events')->first();
                
        return view('user.organization.event.unique-qr-user-scan', [
            'myData' => $myData,
            'event' => $event,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'isManageEvent' => 1,
        ]); 
    }
    public function getQREventDownload($organizationID,$eventID)
    {
        global $global;
        $global['organizationID'] = $organizationID;
        $myData = UserController::me();
        $event = Event::find($eventID);
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.unique-qr-download', [
            'myData' => $myData,
            'event' => $event,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'isManageEvent' => 1,
        ]);
        // return $pdf->stream('pdf-qr-event.pdf');
        return $pdf->download('pdf-qr-event.pdf');
        // return view('pdf.unique-qr-download', [
        //     'myData' => $myData,
        //     'event' => $event,
        //     'organizationID' => $organizationID,
        //     'organization' => $organization,
        //     'isManageEvent' => 1,
        // ]); 
    }
    // ----------------------------------------------------------------------
}

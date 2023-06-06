<?php

namespace App\Http\Controllers;

use Str;
use Auth;
use App\Models\Purchase;
use App\Models\Ticket;
use App\Models\Faq;
use App\Models\Page;
use App\Models\TicketCart;
use App\Models\Category;
use App\Models\Event;
use App\Models\FrontBanner;
use App\Models\PackagePayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomePageController extends Controller
{
    public $months = [
        'Jan' => "01",'Feb' => "02",'Mar' => "03",'Apr' => "04",'Mei' => "05",
        'Jun' => "06",'Jul' => "07",'Agu' => "08",'Sep' => "09",'Okt' => "10",
        'Nov' => "11",'Des' => "12",
    ];

    // Method untuk mengurutkan event dari yang paling populer
    public function shortPopularity($arrEvent)
    {
        for ($i=1; $i < count($arrEvent); $i++) { 
            for ($j = count($arrEvent)-$i; $j > 0 ; $j--) { 
                if(count($arrEvent[$j]->purchase) > count($arrEvent[$j-1]->purchase)){
                    $tmp = $arrEvent[$j-1];
                    $arrEvent[$j-1] = $arrEvent[$j];
                    $arrEvent[$j] = $tmp;
                }
            }
        }

        // Testing
        // for ($i=0; $i < count($arrEvent); $i++) { 
        //     dump($arrEvent[$i]->purchase);
        // }
        // dd('END');

        return $arrEvent;
    }

    public function homePageNew() {
        $myData = UserController::me();
        
        // $eventByCities = 

        return view('homepage_new.index', [
            'myData' => $myData,
        ]);
    }
    public function homePage() {
        // Perlu dioptimasi (Optimasi I 26 Agustus 2022)
        $myData = UserController::me();

        // NOTE : Tidak perlu melakukan filter event didelete atau tidak, karena delete event = deleted=0 + is_publish=0

        $organizations = $this->getOrganization();
        $sekarang = Carbon::now()->toDateString();
        $minggu_depan = Carbon::now()->subDays(-7)->toDateString();

        $cities = CityController::get()->orderBy('priority', 'DESC')->orderBy('updated_at', 'DESC')->take(5)->get();

        //event sekarang
        $events = EventController::get([
            ["end_date", ">=", $sekarang],
            ["is_publish","=",1]
            ])
        ->with(['organizer','sessions.tickets'])
        ->get();
        // diurutkan dari terpopuler
        $events = $this->shortPopularity($events);
        $events = $events->take(3);
        
        $categorys = config('event_types');
        
        // ------------ Memperbarui By Category ----------------------
        $byCategory = [];
        $allEvts = $this->shortPopularity(
            Event::where([
                    ["end_date", ">=", $sekarang],
                    ["is_publish","=",1]
            ])
            ->with(['organizer','sessions.tickets'])->get()
        );
        foreach($categorys as $key => $val){
            $byCategory[$val['name']] = [];
            foreach ($allEvts as $key2 => $val2) {
                if ( preg_match(('/'.$val['name'].'/i'), $val2->category)) {
                    array_push($byCategory[$val['name']], $val2);
                }
            }
        }
        // -----------------------------------------------------------
        
        // event minggu ini (yang dimulai minggu ini)
        $event_minggu_ini = Event::where([
            ["start_date", ">=", $sekarang],
            ["start_date", "<=", $minggu_depan],
            ["end_date", ">=", $sekarang],
            ["is_publish","=",1]
            ])
        ->with(['organizer','sessions.tickets'])->get();
        $event_minggu_ini = $this->shortPopularity($event_minggu_ini);
        
        $banner_images = FrontBanner::orderBy('priority','DESC')->get();
        
        $eventFeatured = Event::where('is_publish', 1)->where("end_date", ">=", $sekarang)->where('deleted', 0)->where('featured','>', 0)->orderBy('featured', 'DESC')->first();
        $isFeatured = $eventFeatured;
        if($eventFeatured == null){
            $eventFeatured = Event::where('is_publish', 1)->where("end_date", ">=", $sekarang)->where('deleted', 0)->get();
            if(count($eventFeatured) > 0){
                $eventFeatured = $eventFeatured->random();
            }else{
                $eventFeatured = null;
            }
        }
        // // dd($eventFeatured);
        return view('homepage.homepage',[
            'events' => $events,
            'cities' => $cities,
            'event_minggu_ini' => $event_minggu_ini,
            'myData' => $myData,
            'byCategory' => $byCategory,
            'organizations' => $organizations,
            'categorys' => $categorys,
            'dateNow' => $sekarang,
            'nextWeek' => $minggu_depan,
            'banners' => $banner_images,
            'eventFeatured' => $eventFeatured,
            'isFeatured' => $isFeatured,
        ]);
    }

    public function eventDetailNew($slugEvent) {
        $myData = UserController::me();
        $event = EventController::get([
            ['slug', $slugEvent]
        ])
        ->with(['organizer','sponsors','exhibitors','sessions.tickets','speakers', 'rundowns.sessions'])
        ->first();

        // grouping session by date
        $sessions = [];
        foreach ($event->sessions as $session) {
            $sessions[$session->start_date] = $session;
        }

        $display['event_name'] = Str::substr($event->name, 0, 45);
        if (Str::after($event->name, $display['event_name']) != "") {
            $display['event_name'] .= "...";
        }

        return view('homepage_new.event_detail', [
            'myData' => $myData,
            'event' => $event,
            'display' => $display,
            'sessions' => $sessions,
            'organizer' => $event->organizer
        ]);
    }
    public function eventDetail($slugEvent)
    {
        $myData = UserController::me();
        $organizations = $this->getOrganization();

        $event = EventController::get([
            ['slug', $slugEvent]
        ])
        ->with(['organizer','sponsors','exhibitors','sessions.tickets','speakers'])
        ->first();
        $purchase= Purchase::where('event_id',$event->id)->get();
        $schedule = $event->rundowns->where('deleted',0)->groupBy('start_date');
        // dd($schedule);
        return view('user.organization.event.eventDetail2',[
            'myData' => $myData,
            'event' => $event,
            'purchase' => $purchase,
            'organizations' => $organizations,
            'schedule' => $schedule,
        ]);
    }

    public function category($category, Request $request)
    {
        $myData = UserController::me();
        $organizations = $this->getOrganization();

        $sekarang = Carbon::now()->toDateString();
        $categorys = Category::all();
        $categoryIn = false;
        for($i=0; $i<count($categorys); $i++){
            if($category == $categorys[$i]->name){
                $categoryIn = true;
            }
        }
        if($categoryIn == true){
            $events = EventController::get([
                ["end_date", ">=", $sekarang],
                ["category", "=",$category],
                ["is_publish","=",1]
                ])
            ->with(['sessions.tickets','organizer'])->paginate(9);
        }else if($category == "event-populer"){
            $events = EventController::get([
                ["end_date", ">=", $sekarang],
                ["is_publish","=",1]
                ])
            ->with(['sessions.tickets','organizer'])->paginate(9);
        }else if($category == 'event-minggu-ini'){
            $tanggalAkhir = Carbon::now()->subDays(-7)->toDateString();
            $events = EventController::get([
                ["start_date", ">=", $sekarang],
                ["end_date", "<=", $tanggalAkhir],
                ["is_publish","=",1]
                ])
            ->with(['sessions.tickets','organizer'])->paginate(9);
        }
        else{
            $queryFilter = [
                ["end_date", ">=", $sekarang],
                ["name", "LIKE", "%".$category."%"],
                ["is_publish","=",1]
            ];
            if ($request->city != "") {
                array_push($queryFilter, [
                    'city', 'LIKE', "%".$request->city."%"
                ]);
            }
            $events = EventController::get($queryFilter)
            ->with(['sessions.tickets','organizer'])->paginate(9);
        }
        $price = "semua";
        // dd($events);
        
        return view('homepage.category',[
            'category' => $category,
            'events' => $events,
            'price' => $price,
            'categorys' => $categorys,
            'myData' => $myData,
            'organizations' => $organizations
        ]);
    }
    // public function explore($category, $filter, Request $req) {
    //     $myData = UserController::me();
    //     $organizations = $this->getOrganization();
    //     $price = $req->price;
    //     // dd($price);
    //     // $organizations = OrganizationController
    //     if($filter == "bulan-ini"){
    //         $price= "semua";
    //         $tanggalAwal = Carbon::now()->toDateString();
    //         $tanggalAkhir = Carbon::now()->endOfMonth()->toDateString();
    //         $queryFilter = [["start_date", ">=", $tanggalAwal],["end_date", "<=", $tanggalAkhir]];
    //     }elseif($filter == "bulan-depan"){
    //         $price= "semua";
    //         $tanggalAwal = Carbon::now()->startOfMonth()->addMonth()->toDateString();
    //         $tanggalAkhir = Carbon::now()->addMonth()->endOfMonth()->toDateString();
    //         $queryFilter = [["start_date", ">=", $tanggalAwal],["end_date", "<=", $tanggalAkhir]];
    //     }elseif($filter == "pencarian"){
    //         $tanggalAwal = Carbon::parse($req->startDate)->format('Y-m-d');
    //         $tanggalAkhir = Carbon::parse($req->endDate)->format('Y-m-d');
    //         if(isset($tanggalAwal) && isset($tanggalAkhir)){
    //             $queryFilter = [["start_date", ">=", $tanggalAwal],["end_date", "<=", $tanggalAkhir]];
    //         }elseif(isset($tanggalAwal)){
    //             $queryFilter = [["start_date", ">=", $tanggalAwal]];
    //         }
    //         else{
    //             $tanggalAwal = Carbon::now()->toDateString();
    //             $queryFilter = [["start_date", ">=", $tanggalAwal]];
    //         }
    //     }

    //     $categorys = Category::all();
    //     $categoryIn = false;
    //     for($i=0; $i<count($categorys); $i++){
    //         if($category == $categorys[$i]->name){
    //             $categoryIn = true;
    //         }
    //     }
    //     if($categoryIn == true){
    //         $events = EventController::get(
    //             $queryFilter,
    //             ["is_publish", "=", 1]
    //             )
    //         ->where('category', $category)
    //         ->with(['sessions.tickets','organizer'])
    //         ->paginate(9);
    //     }else{
    //         $events = EventController::get(
    //             $queryFilter,
    //             ["name", "LIKE", "%".$category."%"],
    //             ["is_publish","=",1]
    //             )
    //         ->with(['sessions.tickets','organizer'])
    //         ->paginate(9);
    //     }
    //     return view('homepage.category', [
    //         'myData' => $myData,
    //         'category' => $category,
    //         'categorys' => $categorys,
    //         'events' => $events,
    //         'price' => $price,
    //         'tanggalAwal' => $tanggalAwal,
    //         'tanggalAkhir' => $tanggalAkhir,
    //         'organizations' => $organizations
    //     ]);
    // }
    public function search(Request $request)
    {
        $search = $request->search;
        return redirect()->route('user.explore', $search);
    }
    public function allcategory()
    {
        $categorys = $categorys = config('event_types');
        $organizations = $this->getOrganization();
        return view('homepage.all_category',[
            'organizations' => $organizations,
            'categorys' =>$categorys
        ]);
    }

    // public function pembelian(Request $request,$slug, $extTicketID = False, $inviter = False)
    // {
    //     // Set your Merchant Server Key
    //     $CLIENT_KEY = "SB-Mid-client-ZGX5Z9E7g6GfWdNR";
    //     $SERVER_KEY = "SB-Mid-server-d4Dv5k-5_6XU-NFnj33fgYhd";

    //     \Midtrans\Config::$serverKey = $SERVER_KEY;
    //     \Midtrans\Config::$clientKey = $CLIENT_KEY;
    //     // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
    //     \Midtrans\Config::$isProduction = false;
    //     // Set sanitization on (default)
    //     \Midtrans\Config::$isSanitized = true;
    //     // Set 3DS transaction for credit card to true
    //     \Midtrans\Config::$is3ds = true;

    //     $payState = "Terbayar";
    //     $orderId = rand();

    //     $pilihTIket = [];
    //     if($extTicketID == false){
    //         if(isset($request->breakdowns)){
    //             $pilihTIket = implode($request->breakdowns);
    //         }else{
    //             $pilihTIket = "Tidak Ada";
    //         }
    //     }else{
    //         $pilihTIket = "InviteMode";
    //     }
    //     $myData =UserController::me();
    //     if($myData == ""){
    //         return redirect()->route('user.loginPage');
    //     } else{
    //         $event= EventController::get([["slug",'=',$slug]])->first();
    //         $userID =$myData->id;
    //         if($pilihTIket=="Tidak Ada"){
    //             return redirect()->back()->with('pesan', 'Kamu Gagal Membeli tiket');
    //         }else{

    //             $snapToken = "";
    //             $ticketID= "";
    //             $ticket = "";
    //             $realBuyer = "";
    //             if($pilihTIket != "InviteMode"){
    //                 $ticket=TicketController::get([['id','=',implode("",$request->breakdowns)]])->first();
    //                 $ticketID = $ticket->id;
    //                 $realBuyer = $myData->id;
    //             }else{
    //                 $ticket = Ticket::where('id', $extTicketID)->first();
    //                 $ticketID = $extTicketID;
    //                 $realBuyer = $inviter;
    //             }
                

    //             $lastOrder = Purchase::where([
    //                 ['user_id', '=', $myData->id],
    //                 ['event_id', '=', $event->id],
    //                 ['ticket_id', '=', $ticketID]
    //             ])->get();

    //             if(count($lastOrder) == 0){
    //                 if($ticket->price > 0){
    //                     $params = array(
    //                         'transaction_details' => array(
    //                             'order_id' => $orderId,
    //                             'gross_amount' => $ticket->price,
    //                         ),
    //                         "item_details" => array([                   
    //                               'id' => '01',
    //                               'price' => $ticket->price,
    //                               'quantity' => 1,
    //                               'name' => $ticket->name,
    //                         ]),
    //                         'customer_details' => array(
    //                             'first_name' => $myData->name,                        
    //                             'email' => $myData->email,
    //                             'phone' => $myData->phone,
    //                         ),
    //                     );
                        
    //                     $payState = "Belum Terbayar";
    //                     $snapToken = \Midtrans\Snap::getSnapToken($params);
    //                 }
    //             }else{
    //                 return redirect()->back()->with('pesan', 'Kamu Gagal Membeli Tiket, Karena Kamu Sudah Pernah Membelinya');
    //             }
                
                
    //             $savedata=Purchase::create([
    //                 'event_id' => $event->id,
    //                 'user_id'=>$userID,
    //                 'send_from'=> $realBuyer,
    //                 'ticket_id' =>$ticketID,
    //                 'quantity' => 1,
    //                 'price'=> $ticket->price,
    //                 'token_trx' => $snapToken,
    //                 'pay_state' => $payState,
    //                 'order_id' => $orderId
    //             ]);
    //             // return redirect()->back()->with(['payment' => $snapToken]);                                             
    //             return redirect()->back()->with(['pesan' => 'Tiket Telah Berhasil DIbeli, Lanjutkan pemabayaran di myTicket Menu']);                                             
    //         }
    //     }
    // }

    public function getOrganization()
    {
        $myData = UserController::me();
        if(isset($myData)){
            return OrganizationController::get()->where('user_id', $myData->id)->with('user')->get();
        }else{
            return null;
        }
    }

    public function faq()
    {
        $faq = Faq::get();

        return view('homepage.faq',[
            'faq' => $faq,            
        ]);
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)->first();

        return view('homepage.page',[
            'page' => $page,            
        ]);
    }

    public function exploreCore(Request $request)
    {
        $queryFilter = [];
        $startDate = Carbon::now()->format('Y-m-d');
        array_push($queryFilter, ['end_date', '>=', $startDate]);
        array_push($queryFilter, ['is_publish', 1]);

        if ($request->city != "") {
            array_push($queryFilter, ['city', 'LIKE', "%".$request->city."%"]);
        }
        if ($request->category != "") {
            array_push($queryFilter, ['category', 'LIKE', "%".$request->category."%"]);
        }
        if($request->search != ""){
            array_push($queryFilter, ['name', 'LIKE', "%".$request->search."%"]);
            
            // array_push($queryFilter, ['punchline', 'LIKE', "%".$request->search."%"]);
        }
        if($request->topic != ""){
            array_push($queryFilter, ['topics', 'LIKE', "%".$request->topic."%"]);
        }

        if ($request->start_date != "") {
            // $startDates = explode(" ", $request->start_date);
            $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
            // $startDate = $startDates[2]."-".$this->months[$startDates[1]]."-".$startDates[0];

            // $endDates = explode(" ", $request->end_date);
            $endDate = Carbon::parse($request->end_date)->format('Y-m-d');
            
            $queryFilter[0] = ['end_date', '<=', $endDate];
            // array_push($queryFilter, ['start_date', '>=', $startDate]);
            array_push($queryFilter, ['start_date','>=',$startDate]);
        }

        if($request->execution_type != ""){
            array_push($queryFilter, ['execution_type','=',$request->execution_type]);
        }

        
        $events = EventController::get($queryFilter)->with('organizer','sessions')->get();
        $events = $this->shortPopularity($events);
        $eventPrices = [];
        
        foreach($events as $event) { 
            $sessions = $event->sessions;
            $prices = [];
            for($i=0; $i<count($sessions); $i++){
                $tickets = $sessions[$i]->tickets->where('deleted', 0);
                foreach($tickets as $ticket){
                    array_push($prices, $ticket->price);
                }
            }
            sort($prices);
            array_push($eventPrices, $prices);
        }
        // dd($events, $eventPrices);
        if($request->price_from != null && $request->price_from != '' && $request->price_from != 'all'){
            $startPrice = $request->price_from;
            $newEventPrices = [];
            $newEvents = [];
            if($startPrice == 'free'){
                $startPrice = 0;
            }
            foreach ($eventPrices as $key => $value) {
                if(count($value) > 0){
                    if($startPrice == 0){
                        if($startPrice == $value[0]){
                            array_push($newEventPrices, $value);
                            array_push($newEvents, $events[$key]);
                        }
                    }else{
                        if($startPrice <= $value[0]){
                            array_push($newEventPrices, $value);
                            array_push($newEvents, $events[$key]);
                        }
                    }
                }
            }
            $events = $newEvents;
            $eventPrices = $newEventPrices;
        }

        return response()->json([
            'events' => $events,
            'eventPrices' => $eventPrices,
        ]);
    }
    
    public function exploreNewNew(Request $request) {
        $myData = UserController::me();
        $cities = CityController::get()->orderBy('priority', 'DESC')->orderBy('updated_at', 'DESC')->get();
        $event_types = config('event_types');

        return view('homepage_new.explore', [
            'myData' => $myData,
            'request' => $request,
            'cities' => $cities,
            'event_types' => $event_types,
        ]);
    }
    public function exploreNew(Request $request) {
        $myData = UserController::me();
        $categories = config('event_types');
        $cities = CityController::get()->orderBy('priority', 'DESC')->orderBy('updated_at', 'DESC')->get();

        $getFilter = $this->exploreCore($request);
        $topics = config('agendakota')['event_topics'];

        return view('homepage.explore', [
            'categories' => $categories,
            'topics' => $topics,
            'events' => $getFilter->original['events'],
            'eventPrices' => $getFilter->original['eventPrices'],
            'cities' => $cities,
            'myData' => $myData,
            'request' => $request,
        ]);
    }
    public function city() {
        $myData = UserController::me();
        $cities = CityController::get()->orderBy('name', 'ASC')->get();
        foreach ($cities as $city) {
            $city->events = EventController::get([
                ['city', 'LIKE', "%".$city->name."%"]
            ])->get('id')->count();
        }

        return view('homepage.city', [
            'cities' => $cities,
            'myData' => $myData
        ]);
    }
    public function createEvent() {
        $types = config('event_types');
        $myData = UserController::me();
        $organizers = [];
        $myPackage = null;
        
        // $organizers = OrganizationController::get([['user_id', 1]])->get();
        $topics = config('agendakota')['event_topics'];
        if ($myData != "") {
            $myPackage = PackagePayment::where('user_id', $myData->id)->with('package')->first();
            $organizers = OrganizationController::get([['user_id', $myData->id]])->get();
        }
        
        return view('homepage.createEvent', [
            'types' => $types,
            'organizers' => $organizers,
            'topics' => $topics,
            'myData' => $myData,
        ]);
    }
    public function afterCreateEvent(Request $request) {
        $user = User::where('token', $request->t)->first();
        $ref = "/".$request->ref;
        if ($user != "") {
            $loggingIn = Auth::guard('user')->loginUsingId($user->id);

            UserController::getAccessStreamManagement($user->email, $request->t);

            return redirect($ref);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use Log;
use File;
use Image;
use DateTime;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Organization;
use App\Models\Speaker;
use App\Models\Purchase;
use App\Models\Rundown;
use App\Models\Session;
use App\Models\SlugCustom;
use App\Models\Ticket;
use App\Models\TicketDiscount;
use App\Models\UserCheckin;


class EventController extends Controller
{
    private function checkLink($link)
    {
        $linkFor = "";
        if (strpos($link, "zoom.us") == true) {
            $linkFor = "zoom";
        } else if (strpos($link, "youtube.com") == true) {
            $linkFor = "youtube";
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

    private function createRundownSession($session, $saveData, $execution)
    {
        // buat session
        $sessionContext = [
            'event_id' => $saveData->id,
            'title' => $session->title,
            'description' => $session->description,
            'start_date' => $session->startSession->date,
            'end_date' => $session->endSession->date,
            'start_time' => $session->startSession->time . ":00",
            'end_time' => $session->endSession->time . ":00",
            'overview' => 1
        ];

        if ($execution != 'offline') {
            $rundown = Rundown::create([
                'event_id' => $saveData->id,
                'start_date' => $session->startSession->date,
                'end_date' => $session->endSession->date,
                'start_time' => $session->startSession->time . ":00",
                'end_time' => $session->endSession->time . ":00",
                'duration' => (strtotime($session->endSession->date . ' ' . $session->endSession->time) - strtotime($session->startSession->date . ' ' . $session->startSession->time)) / 3600,
                'name' => $session->title,
                'description' => $session->description,
            ]);

            $sessionContext['start_rundown_id'] = $rundown->id;
            $sessionContext['end_rundown_id'] = $rundown->id;
            if ($session->streamOption == "youtube-embed" || $session->streamOption == "zoom-embed") {
                $sessionContext['link'] = $session->streamUrl;
            } else if ($session->streamOption == "rtmp-stream") {
                $sessionContext['link'] = 'rtmp-stream-key';
            } else if ($session->streamOption == 'video-conference') {
                $now = new DateTime('now');
                $sessionContext['link'] = 'webrtc-video-conference-' . Str::uuid()->toString() . $now->format('Y-m-d H:i:s');
            }
        }

        $sessionData = Session::create($sessionContext);

        return $sessionData;
    }

    // =======================================================================================
    public function store(Request $request)
    {
        $tickets = json_decode(base64_decode($request->tickets));
        $sessions = json_decode(base64_decode($request->sessions));

        $logo = $request->file('cover');
        $logoFileName = $logo->getClientOriginalName();

        $slug = Str::slug($request->event_name);
        $breakdowns = [];

        $execution = strtolower($request->execution_type);
        if ($execution != "offline") {
            $breakdowns = implode(" ", explode(",", $request->breakdowns));
            foreach ($sessions as $session) {
                if ($session->streamOption == 'zoom-embed' || $session->streamOption == 'youtube-embed') {
                    $re = $this->checkLink($session->streamUrl);
                    if ($re == -1) {
                        return response()->json([
                            'error' => "Stream URL isn't recognized"
                        ], 403);
                    }
                } else if ($session->streamOption != "rtmp-stream" && $session->streamOption != "video-conference") {
                    return response()->json([
                        'error' => "Stream option not found"
                    ], 403);
                }
            }
        } else if ($execution == 'offline') {
            $breakdowns = " ";
        }

        $organizer = Organization::where('id', $request->organizer_id)->with('user')->first();

        $snk = '';
        if ($request->snk) {
            $snk = $request->snk;
        }

        $saveData = Event::create([
            'organizer_id' => $request->organizer_id,
            'category' => $request->selected_event_types,
            'topics' => $request->topics,
            'name' => $request->event_name,
            'description' => $request->event_description,
            'logo' => $logoFileName,
            'location' => "<p>" . $request->address . "</p>",
            'province' => $request->province,
            'city' => $request->city,
            'execution_type' => $execution,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time . ":00",
            'end_time' => $request->end_time . ":00",
            'breakdown' => gettype($breakdowns) == 'string' ? $breakdowns : implode(" ", $breakdowns),
            'punchline' => '',
            'slug' => $slug,
            'type' => $request->topic_str,
            'snk' => $snk,
            'featured' => 0,
            'is_publish' => 1,
            'has_withdrawn' => 0,
            'deleted' => 0
        ]);

        $filePath = public_path('storage/event_assets/' . $slug . '/event_logo/thumbnail');
        $img = Image::make($logo->path());
        File::makeDirectory($filePath, $mode = 0777, true, true);
        $img->resize(1020, 408)->save($filePath . '/' . $logoFileName);

        $sessionSave = [];

        if (count($sessions) > 0 && preg_match("/Stage and Session/i", $breakdowns)) {
            foreach ($sessions as $session) {
                $sessionData = $this->createRundownSession($session, $saveData, $execution);
                // array_push($sessionSave, $sessionData);
                $sessionSave[$session->key] = $sessionData;

                if ($session->streamOption == "rtmp-stream") {
                    $this->crdRTMPKey($sessionData->id, "/api/v1/reg-stream");
                }
            }
        } else if (count($sessions) > 0 && $execution != 'offline') {
            // ambil hanya satu sesi, jika ada banyak sesi
            $session = $sessions[0];
            $sessionData = $this->createRundownSession($session, $saveData, $execution);
            // array_push($sessionSave, $sessionData);
            $sessionSave[$session->key] = $sessionData;

            if ($session->streamOption == "rtmp-stream") {
                $this->crdRTMPKey($sessionData->id, "/api/v1/reg-stream");
            }
        }

        $ticketTypes = [
            'gratis' => 0,
            'berbayar' => 1,
            'suka-suka' => 2
        ];

        foreach ($tickets as $ticket) {
            $saveTicket = Ticket::create([
                'session_id' => $sessionSave[$ticket->session_key]->id,
                'name' => $ticket->name,
                'description' => $ticket->description,
                'start_quantity' => $ticket->quantity,
                'quantity' => $ticket->quantity,
                'start_date' => $ticket->start_date,
                'end_date' => $ticket->end_date,
                'type_price' => $ticketTypes[strtolower($ticket->type)],
                'price' => $ticket->price,
            ]);

            if ($ticket->voucher != null) {
                TicketDiscount::create([
                    'ticket_id' => $saveTicket->id,
                    'name' => $ticket->voucher->name,
                    'code' => $ticket->voucher->code,
                    'quantity' => $ticket->voucher->quantity,
                    'start_date' => $ticket->voucher->start,
                    'end_date' => $ticket->voucher->end,
                    'discount_type' => $ticket->voucher->type,
                    'discount_amount' => $ticket->voucher->amount,
                    'minimum_transaction' => $ticket->voucher->minimum_transaction,
                ]);
            }
        }

        return response()->json([
            'event_id' => $saveData->id,
            'organizer' => $organizer,
        ]);
    }

    // =======================================================================================
    public static function getEventLowestPrice($eventsRaw)
    {
        $events = [];
        foreach ($eventsRaw as $e) {
            $cheapestTicketFound = null;
            foreach ($e->sessions as $session) {
                if ($session->cheapest_ticket != null && count($session->cheapest_ticket) > 0) {
                    if ($cheapestTicketFound == null) {
                        $cheapestTicketFound = $session->cheapest_ticket[0]->price;
                    } else {
                        if ($cheapestTicketFound > $session->cheapest_ticket[0]->price) {
                            $cheapestTicketFound = $session->cheapest_ticket[0]->price;
                        }
                    }
                }
            }
            $e->price = $cheapestTicketFound;
            array_push($events, $e);
        }
        return $events;
    }
    public function byCity(Request $request)
    {
        $events = Event::where([
            ['is_publish', 1],
            ['city', 'LIKE', '%' . $request->city . '%'],
        ])
            ->with(['organizer'])
            ->take(15)->orderBy('created_at', 'DESC')->get();
        $events = self::getEventLowestPrice($events);

        return response()->json([
            'status' => 200,
            'events' => $events
        ]);
    }
    public function byTime(Request $request)
    {
        $filter = [['is_publish', 1]];
        $now = Carbon::now();
        $eventQuery = Event::where($filter);

        if ($request->timeframe == "this-week") {
            $eventQuery = $eventQuery->whereBetween('start_date', [
                $now->startOfWeek()->format('Y-m-d'),
                $now->endOfWeek()->format('Y-m-d')
            ])->where('start_date', '<=', $now->format('Y-m-d'));
        } else if ($request->timeframe == "next-week") {
            $nextWeek = $now->endOfWeek()->addDay();
            $startOfNextWeek = $nextWeek->format('Y-m-d');
            $endOfNextWeek = $nextWeek->endOfWeek()->format('Y-m-d');

            $eventQuery = $eventQuery->whereBetween('start_date', [$startOfNextWeek, $endOfNextWeek])
                ->where('start_date', '<=', $now->format('Y-m-d'));
        } else if ($request->timeframe == "this-month") {
            $eventQuery = $eventQuery->whereBetween('start_date', [
                $now->startOfMonth()->format('Y-m-d'),
                $now->endOfMonth()->format('Y-m-d')
            ])->where('start_date', '<=', $now->format('Y-m-d'));
        } else if ($request->timeframe == "next-month") {
            $nextMonth = $now->endOfMonth()->addDay();
            $startOfNextMonth = $nextMonth->format('Y-m-d');
            $endOfNextMonth = $nextMonth->endOfMonth()->format('Y-m-d');

            $eventQuery = $eventQuery->whereBetween('start_date', [$startOfNextMonth, $endOfNextMonth])
                ->where('start_date', '<=', $now->format('Y-m-d'));
        } else if ($request->timeframe == "this-day") {
            $eventQuery = $eventQuery->where('start_date', $now->format('Y-m-d'));
        } else if ($request->timeframe == "next-day") {
            $tomorrow = $now->addDay()->format('Y-m-d');
            $eventQuery = $eventQuery->where('start_date', $tomorrow);
        }

        $events = $eventQuery->with(['organizer'])->take(15)->orderBy('created_at', 'DESC')->get();
        $events = self::getEventLowestPrice($events);

        if ($request->payment_type == "gratis") {
            $eventsNew = [];
            foreach ($events as $event) {
                if ($event->price == 0) {
                    array_push($eventsNew, $event);
                }
            }
            $events = $eventsNew;
        } else if ($request->payment_type == "berbayar") {
            $eventsNew = [];
            foreach ($events as $event) {
                if ($event->price > 0 && $event->price != null) {
                    array_push($eventsNew, $event);
                }
            }
            $events = $eventsNew;
        }

        return response()->json([
            'status' => 200,
            'events' => $events
        ]);
    }
    public function featured()
    {
        $events = Event::where([
            ['is_publish', 1],
        ])
            ->orderBy('featured', 'DESC')
            ->take(9)->orderBy('created_at', 'DESC')->get();
        $events = self::getEventLowestPrice($events);

        return response()->json([
            'status' => 200,
            'events' => $events
        ]);
    }
    public function recommendation(Request $request)
    {
        $now = Carbon::now();
        $eventsRaw = Event::where([
            ['is_publish', 1],
            ['start_date', '>=', $now->format('Y-m-d')]
        ])->with(['organizer', 'sessions.cheapestTicket'])->take(15)
            ->orderBy('created_at', 'DESC')->get();
        $events = self::getEventLowestPrice($eventsRaw);

        return response()->json([
            'status' => 200,
            'events' => $events
        ]);
    }
    public function detail($id, Request $request)
    {
        $with = ['organizer', 'speakers', 'sessions.tickets'];
        $event = Event::where('id', $id)->with($with)->first();
        $event->relateds = Event::where([
            ['category', 'LIKE', "%" . $event->category . "%"],
            // ['id', '!=', $event->id]
        ])->take(4)->get();

        return response()->json([
            'status' => 200,
            'event' => $event
        ]);
    }
    public function speaker($eventID)
    {
        $speakers = Speaker::where('event_id', $eventID)->get();
        return response()->json([
            'status' => 200,
            'speakers' => $speakers
        ]);
    }
    public function explore(Request $request)
    {
        $now = Carbon::now();
        $eventQuery = Event::where([
            ['is_publish', 1]
        ]);
        if ($request->q != "") {
            $eventQuery = $eventQuery->where('name', 'LIKE', '%' . $request->q . '%');
        }
        if ($request->city != "") {
            $eventQuery = $eventQuery->where('city', 'LIKE', '%' . $request->city . '%');
        }
        if ($request->category != "") {
            $eventQuery = $eventQuery->where('category', 'LIKE', '%' . $request->category . '%');
        }
        if ($request->execution_type != "") {
            $eventQuery = $eventQuery->where('execution_type', 'LIKE', '%' . $request->execution_type . '%');
        }
        if ($request->timeframe == "this-week" || $request->timeframe == "") {
            $eventQuery = $eventQuery->whereBetween('events.start_date', [
                $now->startOfWeek()->format('Y-m-d'),
                $now->endOfWeek()->format('Y-m-d')
            ])->where('events.start_date', '<=', $now->format('Y-m-d'));
        } else if ($request->timeframe == "next-week") {
            $nextWeek = $now->endOfWeek()->addDay();
            $startOfNextWeek = $nextWeek->format('Y-m-d');
            $endOfNextWeek = $nextWeek->endOfWeek()->format('Y-m-d');

            $eventQuery = $eventQuery->whereBetween('events.start_date', [$startOfNextWeek, $endOfNextWeek])
                ->where('events.start_date', '<=', $now->format('Y-m-d'));
        } else if ($request->timeframe == "this-month") {
            $eventQuery = $eventQuery->whereBetween('events.start_date', [
                $now->startOfMonth()->format('Y-m-d'),
                $now->endOfMonth()->format('Y-m-d')
            ])->where('events.start_date', '<=', $now->format('Y-m-d'));
        } else if ($request->timeframe == "next-month") {
            $nextMonth = $now->endOfMonth()->addDay();
            $startOfNextMonth = $nextMonth->format('Y-m-d');
            $endOfNextMonth = $nextMonth->endOfMonth()->format('Y-m-d');

            $eventQuery = $eventQuery->whereBetween('events.start_date', [$startOfNextMonth, $endOfNextMonth])
                ->where('events.start_date', '<=', $now->format('Y-m-d'));
        } else if ($request->timeframe == "this-day") {
            $eventQuery = $eventQuery->where('events.start_date', $now->format('Y-m-d'));
        } else if ($request->timeframe == "next-day") {
            $tomorrow = $now->addDay()->format('Y-m-d');
            $eventQuery = $eventQuery->where('events.start_date', $tomorrow);
        }
        if (count($request->topics) > 0) {
            foreach ($request->topics as $topic) {
                if ($topic != "") {
                    $eventQuery = $eventQuery->where('topics', 'LIKE', '%' . $topic . '%');
                }
            }
        }

        if ($request->payment_type == "berbayar") {
            $eventQuery = $eventQuery->select('*', 'events.name as event_name')
                ->join('sessions', 'sessions.event_id', '=', 'events.id')
                ->join('tickets', 'tickets.session_id', '=', 'sessions.id')
                ->where('tickets.price', '>', 0);
        }
        if ($request->payment_type == "gratis") {
            $eventQuery = $eventQuery->select('*', 'events.name as event_name')
                ->join('sessions', 'sessions.event_id', '=', 'events.id')
                ->join('tickets', 'tickets.session_id', '=', 'sessions.id')
                ->where('tickets.price', '=', 0);
        }

        $events = $eventQuery->orderBy('events.created_at', 'DESC')
            ->with(['organizer', 'sessions.cheapestTicket'])->paginate(12);
        $events = json_decode(json_encode($events), FALSE);
        $sortedEvents = self::getEventLowestPrice($events->data);

        $events->data = $sortedEvents;

        return response()->json([
            'status' => 200,
            'events' => $events
        ]);
    }
    public function exploreOld(Request $request)
    {
        $queryFilter = [
            ['is_publish', 1]
        ];
        $filter = $request->filter;

        if ($request->location != null) {
            array_push($queryFilter, ['city', 'LIKE', "%" . $request->location . "%"]);
        }
        if ($filter['category'] != null) {
            array_push($queryFilter, ['category', 'LIKE', "%" . $filter['category'] . "%"]);
        }
        if ($filter['q'] != null) {
            array_push($queryFilter, ['name', 'LIKE', "%" . $filter['q'] . "%"]);
        }

        $events = Event::where($queryFilter)
            ->with('organizer')->get();

        return response()->json([
            'status' => 200,
            'events' => $events,
            'filter' => $filter
        ]);
    }
    public function rundown($id)
    {
        $rundowns = \App\Models\Rundown::where('event_id', $id)
            ->orderBy('start_date', 'ASC')->get();

        return response()->json([
            'rundowns' => $rundowns
        ]);
    }
    public function sponsor($id)
    {
        $sponsors = \App\Models\Sponsor::where('event_id', $id)->get();
        return response()->json([
            'sponsors' => $sponsors
        ]);
    }
    public function session($id)
    {
        $sessions = \App\Models\Session::where('event_id', $id)
            ->orderBy('start_date', 'ASC')
            ->orderBy('start_time', 'ASC')
            ->get();
        $rundowns = \App\Models\Rundown::where('event_id', $id)->orderBy('start_date', 'ASC')
            ->get();

        return response()->json([
            'sessions' => $sessions,
            'rundowns' => $rundowns
        ]);
    }
    public function handbook($id)
    {
        $handbooks = \App\Models\Handbook::where('event_id', $id)
            ->get();

        return response()->json([
            'handbooks' => $handbooks
        ]);
    }
    public function updateLink($id, Request $request)
    {
        $data = Event::where('id', $id);
        $event = $data->first();

        $slug = $request->custom_slug;
        $dateNow = date('Y-m-d');

        $check = Event::where([
            ['custom_slug', $slug],
            ['start_date', '>=', $dateNow]
        ])->get();

        if ($check->count() != 0) {
            return response()->json([
                'status' => 500,
                'message' => "Custom link telah dipakai, mohon gunakan custom link yang berbeda"
            ]);
        } else {
            $updateSlug = $data->update([
                'custom_slug' => $slug
            ]);

            return response()->json([
                'status' => 200,
                'message' => "Berhasil mengubah custom link"
            ]);
        }
    }
    public function overview($id)
    {
        $sponsors = \App\Models\Sponsor::where('event_id', $id)->get('id')->count();
        $exhibitors = \App\Models\Exhibitor::where('event_id', $id)->get('id')->count();

        return response()->json([
            'sponsors' => $sponsors,
            'exhibitors' => $exhibitors,
        ]);
    }
    public function dashboard($id, Request $request)
    {
        $now = Carbon::now();
        $startDate = $now->startOfMonth()->format('Y-m-d');
        $endDate = $now->endOfMonth()->format('Y-m-d');
        $maxPurchaseToShow = 5;

        $event = Event::where('id', $id)->with([
            // 'purchase' => function ($query) use ($startDate, $endDate) {
            //     $query->whereBetween('created_at', [$startDate, $endDate])
            //     ->orderBy('created_at', 'ASC');
            // },
            'purchase.users',
            'purchase.tickets',
            'sessions.tickets'
        ])
            ->first();
        $filter = "monthly";
        $revenue = $event->purchase->sum('price');

        $totalTicketsBeginning = 0;
        foreach ($event->sessions as $sesi) {
            $totalTicketsBeginning += $sesi->tickets->count();
        }
        // $totalTicketsOfEvent = $event->purchase->count() + $totalTicketsBeginning;
        $totalTicketsOfEvent = $event->purchase->count();

        $datesOnChart = [];
        $chartDatas = [];
        $revenueOnDate = [];
        $purchases = [];
        foreach ($event->purchase as $i => $purchase) {
            $theDate = Carbon::parse($purchase->created_at)->isoFormat('d MMM');
            if (in_array($theDate, $datesOnChart)) {
                $chartDatas[$theDate][] = $purchase;
                $revenueOnDate[$theDate] += $purchase->price;
            } else {
                $chartDatas[$theDate] = [$purchase];
                $revenueOnDate[$theDate] = $purchase->price;
                array_push($datesOnChart, $theDate);
            }

            if ($i < $maxPurchaseToShow) {
                array_push($purchases, $purchase);
            }
        }

        $chartData = [];
        foreach ($revenueOnDate as $rev) {
            array_push($chartData, $rev);
        }

        $visitors = Purchase::where([
            ['event_id', $event->id],
        ])
            ->whereHas('payment', function ($query) {
                $query->where('pay_state', 'Terbayar');
            })
            ->with('checkin')
            ->get('id');

        $shortlink = SlugCustom::where('event_id', $event->id)->orderBy('created_at', 'DESC')->take(1)->get();

        return response()->json([
            'event' => $event,
            'revenue' => $revenue,
            'total_ticket' => $totalTicketsOfEvent,
            'chart' => $chartDatas,
            'chart_label' => $datesOnChart,
            'chart_data' => $chartData,
            'visitors' => $visitors,
            'purchases' => $purchases,
            'shortlink' => $shortlink
        ]);
    }
    public function ticketSales($id, Request $request)
    {
        $limit = $request->limit == null ? 25 : $request->limit;
        $purchasesQuery = \App\Models\Purchase::where('event_id', $id)->with([
            'tickets',
            'users',
            'events'
        ]);
        $purchases = $purchasesQuery->get(['id', 'ticket_id', 'user_id', 'event_id']);
        $purchasesDisplay = $purchasesQuery->paginate($limit);

        $totalTickets = 0;
        $ticketHasCount = [];
        foreach ($purchases as $purchase) {
            if (!in_array($purchase->tickets->id, $ticketHasCount)) {
                $totalTickets += $purchase->tickets->start_quantity;
                array_push($ticketHasCount, $purchase->tickets->id);
            }
        }

        return response()->json([
            'purchases_display' => $purchasesDisplay,
            'purchases' => $purchases,
            'total_tickets' => $totalTickets
        ]);
    }
    public function checkin(Request $request)
    {
        $orderID = $request->order_id;
        $userID = $request->user_id;
        $response['message'] = '';
        $user = null;

        $payment = \App\Models\Payment::where('order_id', $orderID)->with('purchases')->first();
        if ($payment == "") {
            return response()->json([
                'message' => 'Gagal, Kode pembayaran tidak ditemukan',
                'status' => 300,
            ]);
        }

        if ($payment->pay_state == "Belum Terbayar") {
            return response()->json([
                'message' => 'Gagal, Pengunjung belum melakukan pembayaran',
                'status' => 300,
            ]);
        }

        $purchases = $payment->purchases;
        foreach ($purchases as $purchase) {
            if ($purchase->user_id == $userID) {
                $user = \App\Models\User::find($purchase->user_id);
                $queryCheckin = \App\Models\UserCheckin::where('purchase_id', $purchase->id);
                $isCheckedIn = $queryCheckin->first();
                if ($isCheckedIn == "") {
                    \App\Models\UserCheckin::create([
                        'user_id' => $userID,
                        'purchase_id' => $purchase->id,
                        'checkin' => 1
                    ]);
                } else {
                    $queryCheckin->increment('checkin');
                }
                $response['message'] = "Berhasil checkin (" . $user->name . ")";
            } else {
                $response['message'] = "Gagal, Kode pembayaran salah";
            }
        }

        return response()->json([
            'message' => $response['message'],
            'status' => 200
        ]);
    }
    public function visitor($eventID)
    {
        $datas = Purchase::where('event_id', $eventID)
            ->whereHas('payment', function ($query) {
                $query->where('pay_state', 'Terbayar');
            })
            ->has('checkin')
            ->with(['users', 'tickets.session'])
            ->get();

        return response()->json([
            'datas' => $datas
        ]);
    }
    public function favoriteOrganizer()
    {
        $organizers = Organization::withCount('events')->orderBy('events_count', 'DESC')
            ->take(10)->get();

        return response()->json([
            'status' => 200,
            'organizers' => $organizers
        ]);
    }
}
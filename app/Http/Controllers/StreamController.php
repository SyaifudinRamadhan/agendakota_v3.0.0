<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Faq;
use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StreamController extends Controller
{       
    public function index($slugEvent)
    {
        $myData = UserController::me();
        $organizationID = Event::where('slug', $slugEvent)->pluck('organizer_id');
        $organization = Organization::where('id', $organizationID)->first();

        $event = EventController::get([
            ['slug', $slugEvent]
        ])
        ->with(['organizer','sponsors','exhibitors','sessions.tickets','speakers','sessions.sessionspeakers.speaker'])
        ->first();
        $purchase= Purchase::where('event_id', $event->id)->get();
        // dd($event);
        return view('stream.info', [
            'myData' => $myData,
            'event' => $event,
            'purchase' => $purchase,
            'organization' => $organization
        ]);
    }

    public function chat($slugEvent)
    {      
        return view('stream.chat');
    }

    public function stage($slugEvent)
    {    
        $myData = UserController::me();
        $organizationID = Event::where('slug', $slugEvent)->pluck('organizer_id');
        $organization = Organization::where('id', $organizationID)->first();

        $event = EventController::get([
            ['slug', $slugEvent]
        ])
        ->with(['organizer','sponsors','exhibitors','sessions.tickets','speakers','sessions.sessionspeakers.speaker'])
        ->first();
        $purchase= Purchase::where('event_id', $event->id)->get();
    
        
        return view('stream.stage', [
            'myData' => $myData,
            'event' => $event,
            'purchase' => $purchase,
            'organization' => $organization
        ]);
    }
    
}

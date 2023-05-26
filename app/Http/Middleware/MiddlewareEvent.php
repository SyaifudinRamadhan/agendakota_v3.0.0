<?php

namespace App\Http\Middleware;

use App\Models\Event;
use App\Models\Organization;
use App\Models\Exhibitor;
use Auth;
use Closure;
use Illuminate\Http\Request;

class MiddlewareEvent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $myData = Auth::guard('user')->user();
        // dd($myData);
        //check orgnasisasi
        $organizationID= $request->route()->parameter('id');
        // $organization = Organization::where('id', $organizationID)->with('events')->first();

        //check event
        global $global;
        $global['organizationID'] = $organizationID;
        $event_id   = $request->route()->parameter('eventID');
        $events = Event::where('id',$event_id)->with('organizer')->whereHas('organizer', function($query) {
            global $global;
            $query->where('organizer_id', '=', $global['organizationID']);
        })->get();

         //check is exihibitor ?
        //  $isExihibitor = Exhibitor::where('email', $myData->email)->where('event_id', $event_id)->get();

        // dd($events);
        foreach($events as $event){
            $organizer_id = $event->organizer_id;
        }
        // foreach($isExihibitor as $exh){
        //     if($exh->event->id == $event_id){
        //         $thisExihbitor = $exh;
        //     }
        // }
        if(!isset($organizer_id)){
            abort(403);
        }
        // elseif(isset($thisExihbitor)){
        //     $nameRoute = $request->route()->getName();
        //     if($nameRoute != 'organization.event.exhibitors' ||
        //         $nameRoute != 'organization.event.exhibitor.edit' ||
        //         $nameRoute != 'organization.event.exhibitor.update'
        //     ){
        //         dd($request->route()->getName());
        //         return abort('403');
        //     }
        // }
        // dd($request->route()->getName());
        return $next($request);
    }
}

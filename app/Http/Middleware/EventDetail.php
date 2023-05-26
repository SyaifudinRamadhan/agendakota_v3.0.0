<?php

namespace App\Http\Middleware;

use App\Models\Event;
use Closure;
use Illuminate\Http\Request;

class EventDetail
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
        $slugEvent= $request->route()->parameter('slug');
        $event = Event::where('slug', $slugEvent)->get();

        if(count($event) == 0){
            abort(404);
        }else{
            return $next($request);
        }
    }
}

<?php

namespace App\Http\Middleware;


use App\Models\Event;
use App\Models\Organization;
use Auth;
use Route;
use Closure;
use Illuminate\Http\Request;

class User
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
        // dd(Route::current()->parameters(), Route::currentRouteName());
        $myData = Auth::guard('user')->user();
        // $currentRoute = Route::currentRouteName();
        // $params = Route::current()->parameters();
            // dd(Route::currentRoute());
        // dd($myData);
        if ($myData == "") {
            // $currentRoute = Route::currentRouteName();
            // dd(Route::currentRoute());
            // return redirect()->route('user.loginPage', ['redirect_to' => $currentRoute]);
            return redirect()->route('user.loginPage');
            // return redirect()->back();
        }
        return $next($request);
        // return redirect()->route('user.loginPage', ['redirect_to' => $currentRoute, array_values($params)]);
    }
}

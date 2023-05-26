<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Event;
use App\Models\Organization;
use Auth;
use Route;
use Closure;
use Illuminate\Http\Request;

class Admin
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
        // dd($request);
        $myData = Auth::guard('admin')->user();
        $currentRoute = Route::currentRouteName();
        // dd($myData);
        if ($myData == "") {
            return redirect()->route('admin.loginPage', ['redirect_to' => $currentRoute]);
        }else{
            // dd($myData);
            if($myData->role == '1' || $myData->role == 'admin'){
                return $next($request);
            }
        }
        return redirect()->back();
    }
}

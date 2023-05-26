<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class Mobile
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
        $token = $request->token;
        $user = User::where('token', $token)->first();
        if ($user == "") {
            return response()->json(['status' => 401]);
        }
        return $next($request);
    }
}

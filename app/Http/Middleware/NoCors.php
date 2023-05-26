<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoCors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        // header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: *");

        return $next($request);
    }
    public function handles(Request $request, Closure $next)
    {

        // ALLOW OPTIONS METHOD
        $headers = [
            'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'=> 'Content-Type, X-Auth-Token, Origin, X-CSRF-Token',
            'Access-Control-Allow-Origin' => '*'
        ];
        if($request->getMethod() == "OPTIONS") {
            // The client-side application can set only headers allowed in Access-Control-Allow-Headers
            return Response::make('OK', 200, $headers);
        }

        $response = $next($request);
        foreach($headers as $key => $value) {
            $response->header($key, $value);
        }
        return $response;
    }
}

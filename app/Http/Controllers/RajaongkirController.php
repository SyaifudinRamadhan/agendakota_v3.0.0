<?php

namespace App\Http\Controllers;

use Log;
use Http;
use Illuminate\Http\Request;

class RajaongkirController extends Controller
{
    protected $baseUrl = "https://pro.rajaongkir.com/api";

    public function province() {
        $uri = $this->baseUrl."/province//";
        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_KEY')
        ])->get($uri);
        $body = $response->body();
        $body = json_decode($body, false);

        return response()->json($body->rajaongkir->results);
    }
    public function city(Request $request) {
        $uri = $this->baseUrl."/city";
        $query = ['province' => $request->province_id];
        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_KEY')
        ])->get($uri, $query);
        $body = json_decode($response->body(), false);

        return response()->json($body->rajaongkir->results);
    }
}

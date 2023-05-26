<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    public function get(Request $request) {
        $query = City::orderBy('name', 'ASC');
        if ($request->take != "") {
            $query = $query->take($request->take);
        }
        $cities = $query->get();
        
        return response()->json([
            'status' => 200,
            'cities' => $cities
        ]);
    }
}

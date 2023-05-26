<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function get($id) {
        $data = Purchase::where('id', $id)->with(['events.organizer'])->first();
        
        return response()->json([
            'status' => 200,
            'purchase' => $data
        ]);
    }
}

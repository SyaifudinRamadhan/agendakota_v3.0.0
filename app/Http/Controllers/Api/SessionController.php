<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Session;

class SessionController extends Controller
{
    public function delete($eventID, $sessionID) {
        $data = Session::where('id', $sessionID);
        $deleteData = $data->delete();

        return response()->json(['status' => 200]);
    }
}

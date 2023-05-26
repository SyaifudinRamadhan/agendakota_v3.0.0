<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rundown;

class RundownController extends Controller
{
    public function store($eventID, Request $request) {
        $toSave = [
            'event_id' => $eventID,
			'start_date' => $request->start_date,
			'end_date' => $request->end_date,
			'start_time' => $start_time,
			'end_time' => $end_time,
			'duration' => (strtotime($request->end_time)-strtotime($request->start_time))/3600,
			'description' => $request->description
        ];
        $saveData = Rundown::create($toSave);
    }
}

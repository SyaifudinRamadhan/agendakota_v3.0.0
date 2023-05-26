<?php

namespace App\Http\Controllers;

use App\Models\BoothHandout as Handout;
use Illuminate\Http\Request;

class BoothHandoutController extends Controller
{
    public static function get($filter = NULL) {
        if ($filter == NULL) {
            return new Handout;
        }
        return Handout::where($filter);
    }
    public function store(Request $request) {
        $myData = AgentController::me();
        $booth = ExhibitorController::get([
            ['id', $myData->booth_id]
        ])->with('event')->first();
        $event = $booth->event;

        $content = $request->file('content');
        $contentFileName = $content->getClientOriginalName();

        $saveData = Handout::create([
            'booth_id' => $myData->booth_id,
            'type' => $request->type,
            'content' => $contentFileName,
        ]);

        $content->storeAs('public/event_assets/'.$event->slug."/exhibitors/handouts/", $contentFileName);

        return redirect()->route('agent.handout')->with([
            'message' => "Handout berhasil ditambahkan"
        ]);
    }
    public function delete(Request $request) {
        $id = $request->id;
        $deleteData = Handout::where('id', $id)->delete();

        return redirect()->route('agent.handout')->with([
            'message' => "Handout berhasil dihapus"
        ]);
    }
}

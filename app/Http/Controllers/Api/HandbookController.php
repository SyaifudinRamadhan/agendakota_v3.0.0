<?php

namespace App\Http\Controllers\Api;

use Str;
use File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Handbook;

class HandbookController extends Controller
{
    public function store($eventID, Request $request) {
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $slug = Str::slug($fileName);
        $fileType = $request->type_file;

        $toSave = [
            'file_name' => $fileName,
            'type_file' => $fileType,
            'slug' => Str::slug($fileName)
        ];

        $event = \App\Models\Event::where('id', $eventID)->first();
        if ($request->sender == "organizer") {
            $toSave['event_id'] = $eventID;
        } else {
            $toSave['exhibitor_id'] = $request->exhibitor_id;
        }

        $saveData = Handbook::create($toSave);
        $pathDoc = public_path('storage/event_assets/'.$event->slug.'/event_handbooks/');
        $file->move($pathDoc, $fileName);

        return response()->json([
            'message' => "Berhasil menambahkan handbook",
        ]);
    }
    public function delete($eventID, $id) {
        $data = Handbook::where('id', $id);
        $handbook = $data->with('event')->first();
        $deleteData = $data->delete();
        
        if ($handbook->type_file != "video") {
            $path = public_path('storage/event_assets/'.$handbook->event->slug.'/event_handbooks/'.$handbook->file_name);
            $deleteFile = File::delete($path);
        }

        return response()->json([
            'message' => "Handbook berhasil dihapus",
        ]);
    }
}

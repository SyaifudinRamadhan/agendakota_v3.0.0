<?php

namespace App\Http\Controllers\Api;

use Log;
use File;
use Image;
use Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sponsor;
use App\Models\Event;

class SponsorController extends Controller
{
    public static function getLogoPath($slug) {
        return public_path("storage/event_assets/".$slug."/sponsor_logo/");
    }
    public function store($id, Request $request) {
        $event = Event::find($id);
        $logoDir = self::getLogoPath($event->slug);
        $logo = $request->file('logo');
        $logoFileName = $logo->getClientOriginalName();

        $saveData = Sponsor::create([
            'event_id' => $id,
            'type' => $request->type,
            'name' => $request->name,
            'website' => $request->website,
            'logo' => $logoFileName
        ]);

        $img = Image::make($logo->path());
        File::makeDirectory($logoDir, $mode = 0777, true, true);
        $img->resize(400, 400)->save($logoDir.$logoFileName);

        return response()->json(['status' => 200, 'logo' => $logo]);
    }
    public function update($eventID, $sponsorID, Request $request) {
        $data = Sponsor::where('id', $sponsorID);
        $sponsor = $data->with('event')->first();
        $logoDir = self::getLogoPath($sponsor->event->slug);
        $logo = $request->file('logo');
        $logoFileName = $logo->getClientOriginalName();
        $l = explode("/". $logoFileName);

        $toUpdate = [
            'website' => $request->website,
            'name' => $request->name,
            'type' => $request->type,
        ];

        if ($l[0] != "http" || $l[0] != "https") {
            $deleteOldImage = Storage::delete($logoDir.$sponsor->logo);
        }

        $updateData = $data->update($toUpdate);

        return response()->json(['status' => 200]);
    }
    public function delete($eventID, $sponsorID) {
        $data = Sponsor::where('id', $sponsorID);
        $sponsor = $data->with('event')->first();
        Log::info($sponsorID);
        Log::info($sponsor);
        $dir = self::getLogoPath($sponsor->event->slug);
        $deleteData = $data->delete();
        $deleteImage = Storage::delete($dir.$sponsor->logo);
        
        return response()->json(['status' => 200]);
    }
}

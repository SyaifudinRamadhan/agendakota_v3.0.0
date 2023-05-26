<?php

namespace App\Http\Controllers;

use Str;
use File;
use Image;
use Storage;
use App\Models\Event;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public static function getCertificatePath($slug) {
        return "storage/event_assets/".$slug."/event_certificates/";
    }
    public function isHaveCertificate($eventID) {
        $data = Certificate::where('event_id', $eventID)->first();
        return $data == null ? false : $data;
    }
    public function store($organizationID, $eventID, Request $request) {
        $event = Event::where('id', $eventID)->first();
        $path = public_path(self::getCertificatePath($event->slug));
        $file = $request->file('file');
        if ($request->hasFile('file')) {
            $fileName = $eventID."_".$file->getClientOriginalName();
        }
        $posX = $request->position_x;
        $posY = $request->position_y;
        $namePosition = $posX."|".$posY;

        $haveCertificate = $this->isHaveCertificate($eventID);
        if (!$haveCertificate) {
            $saveData = Certificate::create([
                'event_id' => $eventID,
                'filename' => $fileName,
                'name_position' => $namePosition,
                'font_size' => $request->font_size,
                'font_family' => $request->font_family,
                'font_weight' => $request->font_weight,
            ]);

            $img = Image::make($file->path());
            File::makeDirectory($path, $mode = 0777, true, true);
            $img->save($path."/".$fileName);
        } else {
            $data = Certificate::where('event_id', $eventID);
            $toUpdate = [
                'font_size' => $request->font_size,
                'font_family' => $request->font_family,
                'font_weight' => $request->font_weight,
                'name_position' => $namePosition,
            ];
            if ($request->hasFile('file') && $haveCertificate->filename != $fileName) {
                // ganti file
                $toUpdate['filename'] = $fileName;
                $deleteOldFile = File::delete($path.$haveCertificate->filename);
                $img = Image::make($file->path());
                $img->save($path."/".$fileName);
            }
            $updateData = $data->update($toUpdate);
        }

        return redirect()->route('organization.event.certificate', [$organizationID, $eventID]);
    }
}

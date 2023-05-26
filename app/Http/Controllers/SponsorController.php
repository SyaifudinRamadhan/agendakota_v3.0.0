<?php

namespace App\Http\Controllers;

use App\Models\MediaPartner;
use App\Models\Organization;
use File;
use Image;
use Storage;
use App\Models\Sponsor;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    public static function getLogoPath($slug, $media = false) {
        if($media == true){
            return public_path("storage/event_assets/".$slug."/media_logo/");    
        }
        return public_path("storage/event_assets/".$slug."/sponsor_logo/");
    }
    public function store($organizationID, $eventID, Request $request) {
        $event = EventController::get([
            ['id', '=', $eventID]
        ])->first();

        // --------- Pengecekan package user aktif ? dan belum lewat batas ---------------
        $myData = UserController::me();
        $organization = Organization::where('id',$organizationID)->first();

        // 1. Untuk save data sponsors
        $paramCombibeSp = false;
        // paramCombine adalah parameter untuk cek apakah unlimited / sudah tercapai batasnya ?
        if($organization->user->package->sponsor_count <= -1){
            $paramCombibeSp = true;
        }else{
            if(count(Sponsor::where('event_id',$eventID)->get()) >= $organization->user->package->sponsor_count){
                $paramCombibeSp = true;
            }
        }

        // 2. Untuk save data media partner
        $paramCombibeMd = false;
        // paramCombine adalah parameter untuk cek apakah unlimited / sudah tercapai batasnya ?
        if($organization->user->package->partner_media_count <= -1){
            $paramCombibeMd = true;
        }else{
            if(count(MediaPartner::where('event_id',$eventID)->get()) >= $organization->user->package->sponsor_count){
                $paramCombibeMd = true;
            }
        }

        // Parameter untuk paket akun user
        $isPkgActive = PackagePricingController::limitCalculator($organization->user);

        // Proses cek paket akun user
        if($request->media != null){
            if($isPkgActive == 0 || $paramCombibeMd == true){
                if($paramCombibeMd == true){
                    return redirect()->back()->with('gagal','Kamu sudah mencapai batas membuat media partner');
                }else{
                    return redirect()->back()->with('gagal','Paket yang kamu beli belum aktif / belum kamu bayar');
                }
            }
        }else{
            if($isPkgActive == 0 || $paramCombibeSp == true){
                if($paramCombibeSp == true){
                    return redirect()->back()->with('gagal','Kamu sudah mencapai batas membuat sponsor');
                }else{
                    return redirect()->back()->with('gagal','Paket yang kamu beli belum aktif / belum kamu bayar');
                }
            }
        }
        // -------------------------------------------------------------------------------

        $logoDir = self::getLogoPath($event->slug);
        if($request->media != null){
            $logoDir = self::getLogoPath($event->slug, $media = true);
        }

        $logo = $request->file('logo');
        $logoFileName = $logo->getClientOriginalName();

        $validateRule = [
            'logo' => 'required|mimes:jpg,png,jpeg|max:'.($organization->user->package->max_attachment*1024),
            'type' => 'required',
            'name' => 'required',
            'website' => 'required|active_url|url'
        ];

        $msgValidate = [
            'required' => 'Semua field wajib diisi',
            'mimes' => 'File yang diterima bertipe jpg, png, atau jpeg',
            'max' => 'File input maksimal '.$organization->user->package->max_attachment.' Mb',
            'url' => 'Input harus berupa link url',
            'active_url' => 'URL harus link aktif',
        ];

        $this->validate($request, $validateRule, $msgValidate);
        
        if($request->media != null){
            MediaPartner::create([
                'event_id' => $eventID,
                'type' => $request->type,
                'logo' => $logoFileName,
                'name' => $request->name,
                'website' => $request->website,
            ]);
        }else{
            $saveData = Sponsor::create([
                'event_id' => $eventID,
                'type' => $request->type,
                'logo' => $logoFileName,
                'name' => $request->name,
                'website' => $request->website,
                'priority' => 0,
            ]);
        }

        $img = Image::make($logo->path());
        File::makeDirectory($logoDir, $mode = 0777, true, true);
        $img->save($logoDir.$logoFileName);
        // $logo->storeAs($logoDir, $logoFileName);

        return redirect()->back();
    }
    public function edit(Request $request) {
        //
    }
    public function update($organizationID, $eventID, Request $request) {
        $sponsorID = $request->sponsor_id;
        $data = '';
        if($request->media != null){
            $data = MediaPartner::where('id',$request->media_id);
        }else{
            $data = Sponsor::where('id', $sponsorID);
        }
        $sponsor = $data->with('event')->first();

        $organization = Organization::where('id',$organizationID)->first();

        $validateRule = [
            'logo' => 'mimes:jpg,png,jpeg|max:'.($organization->user->package->max_attachment*1024),
            'type' => 'required',
            'name' => 'required',
            'website' => 'required|active_url|url'
        ];

        $msgValidate = [
            'required' => 'Semua field wajib diisi',
            'mimes' => 'File yang diterima bertipe jpg, png, atau jpeg',
            'max' => 'File input maksimal '.$organization->user->package->max_attachment.' Mb',
            'url' => 'Input harus berupa link url',
            'active_url' => 'URL harus link aktif',
        ];

        $this->validate($request, $validateRule, $msgValidate);

        $toUpdate = [
            'type' => $request->type,
            'name' => $request->name,
            'website' => $request->website,
        ];

        $logo = $request->file('logo');
        if ($logo) {
            $logoFileName = $logo->getClientOriginalName();
            $toUpdate['logo'] = $logoFileName;

            $path = self::getLogoPath($sponsor->event->slug);
            if($request->media != null){
                $path = self::getLogoPath($sponsor->event->slug, $media = true);
            }
            File::delete($path.$sponsor->logo);
            $img = Image::make($logo->path());
            $img->save($path.$logoFileName);
        }

        $updateData = $data->update($toUpdate);

        return redirect()->back();
    }

    public function delete($organizationID, $eventID, $sponsorID, Request $request) {
        $data = '';
        if($request->media != null){
            $data = MediaPartner::where('id', $sponsorID);
        }else{
            $data = Sponsor::where('id', $sponsorID);
        }
        $sponsor = $data->with('event')->first();

        $path = '';
        if($request->media != null){
            $path = self::getLogoPath($sponsor->event->slug, $media = true);
        }else{
            $path = self::getLogoPath($sponsor->event->slug);
        }
        File::delete($path.$sponsor->logo);
        if($request->media != null){
            $deleteData = MediaPartner::where('id', $sponsorID)->delete();
        }else{
            $deleteData = Sponsor::where('id', $sponsorID)->delete();
        }
        // $deleteData = Sponsor::where('id', $sponsorID)->delete();

        return redirect()->back();

    }

    public function increase($organizationID, $eventID, $sponsorID, Request $request){
        $sponsor = Sponsor::where('id', $sponsorID)->first();
        Sponsor::where('id', $sponsorID)->update([
            'priority' => (int) $sponsor->priority + 1,
        ]);
        return redirect()->back();
    }

    public function decrease($organizationID, $eventID, $sponsorID, Request $request){
        $sponsor = Sponsor::where('id', $sponsorID)->first();
        if($sponsor->priority == 0){
            return redirect()->back();
        }
        Sponsor::where('id', $sponsorID)->update([
            'priority' => (int) $sponsor->priority - 1,
        ]);
        return redirect()->back();
    }
}

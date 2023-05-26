<?php

namespace App\Http\Controllers;

use App\Models\SlugCustom;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Purchase;
use Illuminate\Http\Request;

class CustomLinkController extends Controller
{
    // Function untuk view berdasar custom link
    public function index($slugCustom)
    {
        if(count(SlugCustom::where('slug_custom', $slugCustom)->get()) == 0){
            return abort('404');
        }
        $myData = UserController::me();
        $homePage = new HomePageController();
        $organizations = $homePage->getOrganization();
        $customLink = SlugCustom::where('slug_custom', $slugCustom)->first();
        $event = $customLink->event->with(['organizer','sponsors','exhibitors','sessions.tickets','speakers'])->first();
        $purchase= Purchase::where('event_id',$event->id)->get();
        $schedule = $event->rundowns->groupBy('start_date');
        // dd($schedule);
        return view('user.organization.event.eventDetail2',[
            'myData' => $myData,
            'event' => $event,
            'purchase' => $purchase,
            'organizations' => $organizations,
            'schedule' => $schedule,
        ]);
    }

    // --------- Note. Untuk membuat custom link tambahkan pemeriksaan paket yang dibeli user ----------------------
    // $isPkgActive = PackagePricingController::limitCalculator($userData);
    // if($isPkgActive == 0 || $myData->package->custom_link == 0){
    //     if($myData->package->custom_link == 0){
    //         return redirect()->back()->with('gagal','Kamu tidak punya fitur custom link di paket pembeliaanmu');
    //     }else{
    //         return redirect()->back()->with('gagal','Kamu belum membayar paket pembeliaanmu');
    //     }
    // }
    // -------------------------------------------------------------------------------------------------------------

    // Function untuk membuat dan update custom link
    public function createUpdate($organizationID, $eventID, Request $request)
    {
        $myData = UserController::me();
        $organization = Organization::where('id',$organizationID)->first();
        // ------------- Pengecekan paket pembelian user aktif ? dan ada fiturnya ? -------------------------------
        $isPkgActive = PackagePricingController::limitCalculator($organization->user);
        if($isPkgActive == 0 || $organization->user->package->custom_link == 0){
            if($organization->user->package->custom_link == 0){
                return redirect()->back()->with('gagal','Kamu tidak punya fitur custom link di paket pembeliaanmu');
            }else{
                return redirect()->back()->with('gagal','Kamu belum membayar paket pembeliaanmu');
            }
        }
        // ---------------------------------------------------------------------------------------------------------
        if(!isset($request->customLink)){
            return redirect()->back();
        }
        $customSlug = $request->customLink;
        $customSlug = explode('/', $customSlug);

        // Cek apakah slug custom sudah pernah ada
        if(count(SlugCustom::where('slug_custom', end($customSlug))->get()) == 0){
             // Cek apakah event_id sudah perbah ada di DB SLugCustom
            if(count(SlugCustom::where('event_id', $eventID)->get()) == 0){
                SlugCustom::create([
                    'event_id' => $eventID, 
                    'slug_custom' => end($customSlug),
                ]);
            }else{
                SlugCustom::where('event_id', $eventID)->update([
                    'slug_custom' => end($customSlug),
                ]);
            }
        }else{
            return redirect()->back()->with('gagal', 'Custom link sudah pernah digunakan orang lain');
        }
       
        return redirect()->back()->with('berhasil', 'Custom link berhasil diperbarui');
    }


    // --------- Ini daatabase belum di set. Kalau membuat controller, 
    // sekalian tolong database di Models dan migrations diatur-------------------------
    // ------- Method ini masih kurang, sementara pakai method yang tas dahulu --------
    public function update($organizationID, $eventID, Request $request) {
        $slug = $request->custom_slug;
        $dateNow = date('Y-m-d');

        $check = Event::where([
            ['custom_slug', $slug],
            ['start_date', '>=', $dateNow]
        ])->get();

        if ($check->count() != 0) {
            return redirect()->route('organization.event.eventoverview', [$organizationID, $eventID])->withErrors([
                'Custom link telah dipakai, mohon gunakan custom link yang berbeda'
            ]);
        } else {
            $updateSlug = Event::where('id', $eventID)->update([
                'custom_slug' => $slug
            ]);

            return redirect()->route('organization.event.eventoverview', [$organizationID, $eventID]);
        }
    }
    // ---------------------------------------------------------------------------------------
}

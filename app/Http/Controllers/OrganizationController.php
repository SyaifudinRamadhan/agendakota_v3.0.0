<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Organization;
use App\Models\OrganizationType;
use App\Models\UserWithdrawalEvent;
use Illuminate\Http\Request;

use Image;
use File;
use DateTime;
use Illuminate\Support\Facades\Storage;

use App\Http\Middleware\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Mail\InviteMembers;
use App\Models\OrganizationTeam;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Event;
use App\Models\User as ModelsUser;

class OrganizationController extends Controller
{

    // public function __construct($organizationID,$email){
    //    $email=base64_decode($email);
    //    $cekuser=UserController::get([['email','=',$email]])->first();


    //    if(!isset($cekuser)){
    //        return redirect()->route('organization.create-akun-member',$organizationID);
    //    }else{
    //        $cekteam= OrganizationTeamController::get([['user_id','=',$cekuser->id]])->first();
    //        dd($cekteam);

    //        if($cekteam){
    //            return redirect()->route('user.loginPage')->withErrors('Anda Sudah Berada Pada Organisasi Tersebut');
    //        }
    //    }

    //    OrganizationTeam::create([
    //        'user_id' => $cekuser->id,
    //        'organization_id' =>$organizationID,
    //        'role' => "member"
    //    ]);
    //    return redirect()->route('user.loginPage');
    // }
    public static function get($filter = NULL)
    {
        if ($filter == NULL) {
            return new Organization;
        }
        return Organization::where($filter);
    }
    public static function getPaymentsEvent($events, $onlyPaidEvents = false)
    {
        $dataPayments = [];
        $totalNominal = 0;
        for ($i = 0; $i < count($events); $i++) {
            $orders = $events[$i]->purchase->groupBy('payment_id');
            $payments = [];
            $valueMoney = 0;
            $j = 0;
            foreach ($orders as $key => $val) {
                // array_push($paymentIDs, $key);
                array_push($payments, Payment::where('id', $key)->first());
                if ($payments[$j]->pay_state == 'Terbayar') {
                    $valueMoney += (int)$payments[$j]->price;
                }
                // $valueMoney += (int)$payments[$j]->price;
                $j++;
            }

            $totalNominal += $valueMoney;
            if ($onlyPaidEvents) {
                if ($valueMoney > 0) {
                    array_push($dataPayments, ['data' => $payments, 'value' => $valueMoney, 'event' => $events[$i]]);
                }
            } else {
                array_push($dataPayments, ['data' => $payments, 'value' => $valueMoney, 'event' => $events[$i]]);
            }
        }
        return [$dataPayments,$totalNominal];
    }
    public function create()
    {

        $myData = UserController::me();
        $event = "";
        $totalInvite = UserController::getNotifyInvitation($myData->id);
        return view('user.organization.create', [
            'myData' => $myData,
            'organizationID' => 0,
            'totalInvite' => $totalInvite,
            'eventID' => 0,
        ]);
    }
    public function store(Request $request)
    {
        $myData = UserController::me();

        // Cek apakah syarat dari package masih terpenuhi ?
        $isPkgActive = PackagePricingController::limitCalculator($myData);
        $organizationCount = count($myData->organizations);

        $paramCombine = false;
        // paramCombine adalah parameter untuk cek jumlahnya unlimited / sudah lewat batas
        if ($myData->package->organizer_count <= -1) {
            $paramCombine = false;
        } else {
            if ($organizationCount >= $myData->package->organizer_count) {
                $paramCombine = true;
            }
        }

        if ($isPkgActive == 0 || $paramCombine == true) {
            // Batalkan proses
            return redirect()->back();
        }
        // Proses membuat data organisasi baru
        $message = [
            'required' => ':Attribute Wajib Diisi'
        ];
        $validateData = $this->validate($request, [
            // 'logo' => 'image| required',
            'name' => 'required',
            'type' => 'required',
            'interest' => 'required',
            'description' => 'required',

        ], $message);

        $timeSlug = new DateTime();
        $slug = Str::slug($request->name) . "-" . $timeSlug->format('d-m-y_H-i-s-u');

        // $logo = $request->file('logo');
        // $logoFileName = $logo->getClientOriginalName();

        $organization = Organization::create([
            'user_id' => $myData->id,
            'slug' => $slug,
            'name' => $request->name,
            'type' => $request->type,
            'interest' => $request->interest,
            // 'logo' => $logoFileName,
            'description' => $request->description,
        ]);

        // $logo->storeAs('public/organizer_logo', $logoFileName);

        // return redirect()->route('organization.event.create', [$organization->id]);
        return redirect()->route('create-event');
    }
    public function settings($organizationID)
    {
        $organization = Organization::find($organizationID);

        return view('organization.settings');
    }
    public function update($organizationID, Request $request)
    {
        $myData = UserController::me();
        $message = [
            'required' => ':Attribute Wajib Diisi',
            'digits_between' => 'Minimal Digit Nomor Telepon Harus Berjumlah 11 Angka',
            'numeric' => 'Nomor Telepon Harus Diisi Dengan Angka',
            'active_url' => ':Attribute Harus Diisi Dengan URL ',
            'mimes' => 'File yang diterima bertipe jpg, png, atau jpeg',
            'max' => 'Ukuran file maksimal ' . $myData->package->max_attachment,
        ];
        $validateData = $this->validate($request, [
            'logo' => 'mimes:jpg,png,jpeg|max:' . ($myData->package->max_attachment * 1024),
            'name' => 'required',
            'email' => 'required|email',
            'notelepon' => 'required|numeric|digits_between:11,15',
            'description' => 'required',
            'type' => 'required',
            'interest' => 'required',
            'instagram' => 'nullable|active_url',
            'twitter' => 'nullable|active_url',
            'linked' => 'nullable|active_url',
        ], $message);
        // $filePath = public_path('storage/event_assets/'.$slug.'/event_logo/thumbnail');
        // // $img = Image::make($logo->path());
        // // File::makeDirectory($filePath, $mode = 0755, true, true);


        // $img->resize(400, 200)->save($filePath.'/'.$logoFileName);
        // $logo->storeAs('public/event_assets/'.$slug.'/event_logo/', $logoFileName);
        $logo = $request->file('logo');
        $lama = Organization::find($organizationID);
        // ========================== check nama baru ==========================================
        $slug = '';
        $timeSlug = new DateTime();
        if ($request->name == $lama->name) {
            $slug = $lama->slug;
        } else {
            $slug = Str::slug($request->name) . "-" . $timeSlug->format('d-m-y_H-i-s-u');
        }
        // =====================================================================================
        if ($logo) {
            $filePath = public_path('storage/organization_logo');
            $logoFileName = $timeSlug->format('d-m-y_H-i-s-u') . "_" . $logo->getClientOriginalName();
            if ($lama->logo != '') {
                File::delete('storage/organization_logo/' . $lama->logo);
            }

            $img = Image::make($logo->path());
            File::makeDirectory($filePath, $mode = 0777, true, true);
            $img->resize(200, 200)->save($filePath . '/' . $logoFileName);
            // Storage::delete(['/storage/organization_logo/'.$lama->logo]);

            // $logo->storeAs('storage/organization_logo', $logoFileName);

        } else {
            $logoFileName = $lama->logo;
        }


        // ----- Check upload image banner --------------------
        $banner = $request->file('banner');
        $bannerFileName = null;
        if ($banner != null) {
            $filePath = public_path('storage/organization_logo');
            $bannerFileName = '_banner_' . $slug . $lama->id . '_' . $banner->getClientOriginalName();
            if ($lama->banner_img !=  '' || $lama->banner_img != null) {
                File::delete('storage/organization_logo/' . $lama->banner_img);
            }
            $img = Image::make($banner->path());
            File::makeDirectory($filePath, $mode = 0777, true, true);
            $img->save($filePath . '/' . $bannerFileName);
        } else {
            $bannerFileName = $lama->banner_img;
        }
        // -----------------------------------------------------

        $linkWeb = $request->website;
        if ($linkWeb == null) {
            $linkWeb = '';
        }

        $updateData = Organization::where('id', $organizationID)->update([
            'slug' => $slug,
            'name' => $request->name,
            'logo' => $logoFileName,
            'type' => $request->type,
            'interest' => $request->interest,
            'email' => $request->email,
            'no_telepon' => $request->notelepon,
            'description' => $request->description,
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'linked' => $request->linkedin,
            'website' => $linkWeb,
            'banner_img' => $bannerFileName,

        ]);
        return redirect()->back()->with('berhasil', 'Profil Organisai Anda Telah Berhasil Diperbaharui');
    }
    public function delete($organizationID, Request $request)
    {
        $organization = Organization::where('id', $organizationID)->where('deleted', 0)->first();
        if (!$organization) {
            return redirect()->back()->with('gagal', 'Data organisasi tidak ditemukan');
        }
        if ($request->org_name != $organization->name) {
            return redirect()->back()->with('gagal', 'Tindakan penghapusan ditolak. Verifikasi nama organisasi tidak sesuai');
        }
        Organization::where('id', $organizationID)->update([
            'deleted' => 1
        ]);
        OrganizationTeam::where('organization_id', $organizationID)->delete();
        Event::where('organizer_id', $organizationID)->update([
            'deleted' => 1
        ]);
        return redirect()->route('user.myorganization')->with('berhasil', ('Organisasi ' . $organization->name . ' berhasil dihapus'));
    }
    public function events($organizationID)
    {
        $myData = UserController::me();
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $events = $organization->events;
        $totalInvite = UserController::getNotifyInvitation($myData->id);
        return view('user.organization.events', [
            'organization' => $organization,
            'events' => $events,
            'totalInvite' => $totalInvite,
            'eventID' => 0,
            'organizationID' => $organizationID,
            'myData' => $myData
        ]);
    }
    public function eventDetail($organizationID, $eventID)
    {
        $event = EventController::get([
            ['id', '=', $eventID]
        ])->first();

        return view('user.organization.event');
    }

    public function profilePage($organizationID, Request $request)
    {
        $myData = UserController::me();
        // dd($myData->organizationsTeam);
        // Perintah untuk auto open tab di hallaman profil organisasi
        // 1. Tab events => event
        // 2. Tab Profil => profile
        // 3. Tab Team => team
        // 4. Tab Billing => billing
        $organizations = self::get()->where('id', $organizationID)->where('deleted', 0)->get();
        if (count($organizations) == 0) {
            return abort(404);
        }
        $events = Event::where('organizer_id', $organizationID)->orderBy('created_at', 'DESC')->get();
        $eventsView = Event::where('organizer_id', $organizationID)->where('deleted', 0)->orderBy('created_at', 'DESC')->with(['purchase'])->get();
        // $events = EventController::get([
        //     ['organizer_id','=', $organizationID]
        //     ])->with(['organizer','sessions.tickets'])->get();

        $isExhibitors = [];
        for ($i = 0; $i < count($events); $i++) {
            $exh = UserController::isExihibitor($events[$i]->id);
            $isExhibitors[$i] = count($exh);
        }


        $purchases = Purchase::orderBy('created_at','DESC')->get();
        
        $getData = $this->getPaymentsEvent($events);
        $dataPayments = $getData[0];
        $totalNominal = 0;
        $totalWd = 0;

        for ($i = 0; $i < count($dataPayments); $i++) {
            $trueNominal = (float) $dataPayments[$i]['value'] - (float) $dataPayments[$i]['value'] * $myData->package->ticket_commission;

            if ($trueNominal > (config('agendakota')['min_transfer'] + config('agendakota')['profit+'])) {
                $trueNominal = $trueNominal - config('agendakota')['profit+'];
            }

            $withdrawAcc = UserWithdrawalEvent::where('event_id', $dataPayments[$i]['event']->id)->where('status', 'accepted')->get();
            $withdrawWaiting = UserWithdrawalEvent::where('event_id', $dataPayments[$i]['event']->id)->where('status', 'waiting')->get();

            if (count($withdrawAcc) == 0 && count($withdrawWaiting) == 0) {
                $trueNominal = $trueNominal;
            } else {
                if (count($withdrawAcc) == 0) {
                     $trueNominal = $withdrawWaiting[0]->nominal;
                     $totalWd += $trueNominal;
                } else {
                     $trueNominal = $withdrawAcc[0]->nominal;
                     $totalWd += $trueNominal;
                }
            }
            $dataPayments[$i]['trueNominal'] = $trueNominal;
            $totalNominal += $trueNominal;
        }
        $totalNominal -= $totalWd;

        $role = "pemilik";
        $teams = OrganizationTeamController::get([['organization_id', '=', $organizationID]])->with('users')->get();
        // dd($teams)
        // dd($myData->organizationsTeam);
        // if(($myData->organizationsTeam)){
        //     foreach($myData->organizationsTeam as $organizationTeam){
        //         $role = $organizationTeam->role;
        //     }
        // }
        // ---------- Penentuan user sebagai member atau pemilik organisasi ---------------
        if (count($teams) > 0) {
            for ($i = 0; $i < count($teams); $i++) {
                if ($teams[$i]->user_id == $myData->id) {
                    $role = $teams[$i]->role;
                }
            }
        }
        // --------------------------------------------------------------------------------
        $totalInvite = UserController::getNotifyInvitation($myData->id);
        // $organization_types = config('agendakota')['organization_types'];
        $organization_types = $myData->organization_types;
        $organization_interests = $myData->organization_interests;

        return view('user.organization.profile-organisasi', [
            'myData' => $myData,
            'purchases' => $purchases,
            'role' => $role,
            'teams' => $teams,
            // 'teamsTable' => OrganizationTeamController::get([['organization_id','=',$organizationID]])->with('users')->paginate(10),
            'organizations' => $organizations,
            'totalInvite' => $totalInvite,
            'organizationID' => $organizationID,
            'events' => $events,
            'eventsView' => $eventsView,
            // 'eventsTable' => Event::where('organizer_id',$organizationID)->with(['organizer','sessions.tickets'])->paginate(1),
            'eventExhibitors' => $isExhibitors,
            'organization_types' => $organization_types,
            'organization_interests' => $organization_interests,
            'dataPayments' => $dataPayments,
            'totalNominal' => $totalNominal,
            'isExhibitor' => UserController::isExihibitor(),
            'isSpeaker' => UserController::isSpeaker(),
            'openTab' => $request->to,
        ]);
    }
    public function InviteTeams($organizationID, Request $request)
    {
        $myData = UserController::me();
        $message = [
            'required' => ':Attribute Wajib Diisi',
            'email' => ':Attribute Berupa Email',
        ];
        $validateData = $this->validate($request, [
            'member' => 'required|email'
        ], $message);

        $organization = self::get([['id', '=', $organizationID]])->first();
        $member['organisasiID'] = $organizationID;
        $member['organisasi'] = $organization->name;
        $member['email'] = $request->member;
        $member['link_invite'] = route('organization.confirmation-member', [$member['organisasiID'], base64_encode($member['email'])]);
        $member['sender'] = $myData->name;
        $member['senderMail'] = $myData->email;
        // dd($member);
        // dd(new InviteMembers($member));

        Mail::to($request->member)->send(new InviteMembers($member));
        return redirect()->back()->with('berhasil', 'Anda Telah Berhasil Mengundang Anggota Team Anda');
    }

    public function ConfirmationMember($organizationID, $email)
    {
        $organization = Organization::where('id', $organizationID)->first();
        if (!$organization) {
            return abort('404');
        }
        $email = base64_decode($email);
        $cekuser = UserController::get([['email', '=', $email]])->first();
        if (!isset($cekuser)) {
            return redirect()->route('organization.create-akun-member', $organizationID);
        } else {
            $cekteam = OrganizationTeamController::get([
                ['user_id', '=', $cekuser->id],
                ['organization_id', $organizationID]
            ])->first();
            // dd($cekteam);

            if ($cekteam) {
                return redirect()->route('user.loginPage')->withErrors('Anda Sudah Berada Pada Organisasi Tersebut');
            }
        }

        OrganizationTeam::create([
            'user_id' => $cekuser->id,
            'organization_id' => $organizationID,
            'role' => "member"
        ]);
        return redirect()->route('user.loginPage');
    }

    public function CreateAkunMember($organizationID)
    {
        $organization = Organization::where('id', $organizationID)->first();
        if (!$organization) {
            return abort('404');
        }
        $data = [
            'organizationID' => $organizationID,
        ];
        session([
            'organizationID' => $organizationID,
        ]);
        return view('user.register', $data);
    }

    public function organizationDetail($slug)
    {
        $myData = UserController::me();
        $organizations = Organization::where('slug', $slug)->get();
        $purchases = Purchase::all();

        if (count($organizations) == 0 || count($organizations) > 1) {
            return abort('404');
        }

        $event = Event::where('organizer_id', $organizations[0]->id)->where('is_publish', 1)->get();
        // dd($schedule);
        return view('user.organization.organization-detail', [
            'myData' => $myData,
            'events' => $event,
            'purchases' => $purchases,
            'organizations' => $organizations[0],
        ]);
    }
    public function storeType(Request $request)
    {
        $image = $request->file('image');
        $imageFileName = $image->getClientOriginalName();
        //return public_path('storage/organization_type');

        $saveData = OrganizationType::create([
            'name' => $request->name,
            'icon' => $imageFileName
        ]);

        // Catatan : Untuk menyimpan file jangan gunakan storeAs,
        // kedepannya gambar tidak dapat dimuat secara public
        // $image->storeAs('public/organizer_type/', $imageFileName);

        $filePath = public_path('storage/organization_type');
        $img = Image::make($image->path());
        File::makeDirectory($filePath, $mode = 0777, true, true);
        $img->save($filePath . '/' . $imageFileName);

        return redirect()->route('admin.user.organizationType')->with([
            'message' => "Berhasil menambahkan tipe organisasi baru"
        ]);
    }
    public function deleteType($id)
    {
        $data = OrganizationType::where('id', $id);
        $type = $data->first();
        $deleteData = $data->delete();

        $filePath = public_path('storage/organization_type');

        $deleteImage = File::delete($filePath . '/' . $type->icon);

        return redirect()->route('admin.user.organizationType')->with([
            'message' => "Berhasil menghapus tipe organisasi " . $type->name
        ]);
    }
}

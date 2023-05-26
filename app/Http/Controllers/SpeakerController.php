<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PackagePricing;
use App\Models\PackagePayment;
use App\Models\Event;
use App\Models\Organization;
use App\Models\User;
use Image;
use File;

class SpeakerController extends Controller
{
    public static function get($filter = NULL) {
        if ($filter == NULL) {
            return new Speaker;
        }
        return Speaker::where($filter);
    }
    public static function getSpeakerPath($slug) {
        return "storage/event_assets/".$slug."/speaker_photos/";
    }
    public function store($organizationID, $eventID, Request $request) {
        $organization = Organization::where('id',$organizationID)->first();
        $validateRule = [
            'name' => 'required',
            'email' => 'required',
            'company' => 'required',
            'job' => 'required',
            'photo' => 'required|mimes:jpg,png,jpeg|max:'.($organization->user->package->max_attachment*1024),
        ];

        $validateMsg = [
            'required' => 'Kolom :attribute wajib diisi',
            'mimes' => 'Hanya menerima file berformat jpg, png, atau jpeg',
            'max' => 'Maksimal file upload '.$organization->user->package->max_attachment.' Mb'
        ];

        $validateData = $this->validate($request, $validateRule, $validateMsg);

        $checkDouble = Speaker::where('event_id', $eventID)->where('email', $request->email)->get();
        if(count($checkDouble) > 0){
            return redirect()->back()->with('gagal', 'Gagal. Email Speaker yang didaftarkan sudah terdaftar di event ini');
        }

        $event = EventController::get([
            ['id', '=', $eventID]
        ])->first();
        $path = public_path(self::getSpeakerPath($event->slug));

        $photo = $request->file('photo');
        $original_name=$photo->getClientOriginalName();
        $photoFileName= $request->name.'_'.$original_name;
        $overview=$request->overview;
        if(!isset($overview)){
            $overview =0;
        }else{
            $overview=1;
        }
        $saveData = Speaker::create([
            'event_id' => $eventID,
            'name' => $request->name,
            'email' => $request->email,
            'company' => $request->company,
            'job' => $request->job,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
            'website' => $request->website,
            'instagram' => $request->instagram,
            'overview' => $overview,
            'photo' => $photoFileName,
        ]);

        if(count(User::where('email', $request->email)->get()) == 0){
            // $saveData = User::create([
            //     'name' => $request->name,
            //     'email' => $request->email,
            //     'password' => bcrypt('agendakotaspeaker'),
            //     'photo' => 'default',
            //     'is_active' => 1
            // ]);

            // Create new speaker = add user jika user belum terdaftar
            // Daftarkan user baru jika belum ada
            // ------ Ambil data ID package ---------------------------
            // Lakukan pembelian paket default
            // Pilih ID satu jika tidak ada parameter Id paket yang di input
            $pkgID = $request->pkgID;
            
            $pkgStatus = 0;
            $price = 0;
            if($pkgID == null){
                $pkg = PackagePricing::where('deleted',0)->first();
                $pkgID = $pkg->id;
                $price = $pkg->price;
            }else{
                $pkg = PackagePricing::where('id',$pkgID)->get();
                if(count($pkg) == 1){
                    if($pkg[0]->deleted == 0){
                        $pkgID = $pkg[0]->id;
                        $price = $pkg[0]->price;
                    }else{
                        $pkg = PackagePricing::where('deleted',0)->first();
                        $pkgID = $pkg->id;
                        $price = $pkg->price;
                    }
                }else{
                    $pkg = PackagePricing::where('deleted',0)->first();
                    $pkgID = $pkg->id;
                    $price = $pkg->price;
                }
            }
            
            $saveData = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'photo' => 'default',
                'is_active' => 0,
                'pkg_id' => $pkgID,
                'pkg_status' => $pkgStatus,
            ]);

            #--------------- Memasukkan data pembelian paket ke database dan midtrans ------------------
            
            //--------------------------- Midtran setup -----------------------------------------------------
            $CLIENT_KEY = config('agendakota')['midtrans_config']['CLIENT_KEY'];
            $SERVER_KEY = config('agendakota')['midtrans_config']['SERVER_KEY'];

            \Midtrans\Config::$serverKey = $SERVER_KEY;
            \Midtrans\Config::$clientKey = $CLIENT_KEY;
            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            \Midtrans\Config::$isProduction = config('agendakota')['midtrans_config']['isProduction'];
            // Set sanitization on (default)
            \Midtrans\Config::$isSanitized = config('agendakota')['midtrans_config']['isSanitized'];
            // Set 3DS transaction for credit card to true
            \Midtrans\Config::$is3ds = true;

            $orderId = '';

            // Menanggulangi jika ada orderID yang sama
            do{
                // Lakukan generate orderID
                $random = new RandomStrController();
                $orderId = $random->get();
            }while (count(PackagePayment::where('order_id', $orderId)->get()) > 0);

            
            // ---------------------------------------------------------------------------------------


            // ----------------- Proses input ke databse pembelian paket ----------------------------------
            $status = 0;
            $snapToken = '-';
            if($price == 0){
                $status = 1;
            }else{
                //----------------- Pendaftaran pembayaran ke midtrans --------------------
                $params = array(
                    'transaction_details' => array(
                        'order_id' => $orderId,
                        'gross_amount' => $price,
                    ),
                    "item_details" => array([                   
                        'id' => '01',
                        'price' => $price,
                        'quantity' => 1,
                        'name' => 'ID beli : '.$orderId,
                    ]),
                    'customer_details' => array(
                        'first_name' => $saveData->name,                        
                        'email' => $saveData->email,
                        'phone' => $saveData->phone,
                    ),
                );

                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $status = 0;
                // --------------------------------------------------
            }

            $datasave = [
                'user_id' => $saveData->id,
                'pkg_id' => $pkgID,
                'order_id' => $orderId,
                'token_trx'=> $snapToken,
                'nominal'=> $price,
                'status' => $status
            ];

            PackagePayment::create($datasave);

            // Update data user mengganti pkg_id dan status package aktif tidaknya
            User::where('id',$saveData->id)->update([
                'pkg_status'=> $status,
            ]);
            // dd($saveData);
            // --------------------------------------------------------
            #-------------------------------------------------------------------------------------------

        }

        // $photo->save($path.'/'.$photoFileName);
        $img = Image::make($photo->path());
        File::makeDirectory($path, $mode = 0777, true, true);
        $img->resize(400, 400)->save($path.'/'.$photoFileName);

        // $invitation['link'] = route('user.loginPage');
        // $invitation['sender'] = $myData->name;
        // $invitation['email'] = $request->name;
        // $invitation['participant'] = 'Pembicara';
        // $invitation['event'] = $event[0]->name;
        // $invitation['sender_mail'] = $myData->email;

        // // dd($invitation,  $myData);
        // // dd(new UserInvitation($invitation));

        // Mail::to($request->email)->send(new UserInvitation($invitation));

        return redirect()->route('organization.event.speakers', [$organizationID, $eventID])->with('berhasil', 'Speaker telah berhasil Ditambakan');
    }
    public function update($organizationID, $eventID, $speakerID, Request $request) {
        $organization = Organization::where('id', $organizationID)->first();
        $validateRule = [
            'name' => 'required',
            'email' => 'required',
            'company' => 'required',
            'job' => 'required',
            'photo' => 'mimes:jpg,png,jpeg|max:'.($organization->user->package->max_attachment*1024),
        ];

        $validateMsg = [
            'required' => 'Kolom :attribute wajib diisi',
            'mimes' => 'Hanya menerima file berformat jpg, png, atau jpeg',
            'max' => 'Maksimal file upload '.$organization->user->package->max_attachment.' Mb'
        ];

        $validateData = $this->validate($request, $validateRule, $validateMsg);

        $checkDouble = Speaker::where('event_id', $eventID)->where('email', $request->email)->where('id','!=',$speakerID)->get();
        if(count($checkDouble) > 0){
            return redirect()->back()->with('gagal', 'Gagal. Email Speaker yang didaftarkan sudah terdaftar di event ini');
        }

        $event = EventController::get([
            ['id', '=', $eventID]
        ])->first();

        $data = self::get([['id',$speakerID]])->first();
        $path = public_path(self::getSpeakerPath($event->slug));
        $photo = $request->file('photo');
        // dd($photo);
        if(isset($photo)){

            File::delete($path.$data->photo);
            $original_name=$photo->getClientOriginalName();
            $photoFileName= $request->name.'_'.$original_name;
            // $photo->storeAs($path, $photoFileName);
            $img = Image::make($photo->path());
            $img->resize(400,400)->save($path.'/'.$photoFileName);
        }else{
            $photoFileName = $data->photo;
        }

        $overview=$request->overview;
        if(!isset($overview)){
            $overview =0;
        }else{
            $overview=1;
        }
        $saveData = Speaker::where('id',$speakerID)->update([
            'event_id' => $eventID,
            'name' => $request->name,
            'email' => $request->email,
            'company' => $request->company,
            'job' => $request->job,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
            'website' => $request->website,
            'instagram' => $request->instagram,
            'overview' => $overview,
            'photo' => $photoFileName,
        ]);
        return redirect()->route('organization.event.speakers', [$organizationID, $eventID])->with('berhasil', 'Speaker telah berhasil Diedit');
    }
    public function delete($organizationID, $eventID, $speakerID) {
        $event = EventController::get([
            ['id', '=', $eventID]
        ])->first();
        $data = self::get([['id',$speakerID]])->first();
        $path = public_path(self::getSpeakerPath($event->slug));
        File::delete($path.$data->photo);
        $deleteData = Speaker::where('id', $speakerID)->delete();
        return redirect()->route('organization.event.speakers', [$organizationID, $eventID])->with('berhasil', 'Speaker telah berhasil Dihapus');
    }

    public function create($organizationID, $eventID)
    {
        $organization = Organization::where('id', $organizationID)->first();
        $myData = UserController::me();
        $event = Event::where('id',$eventID)->first();
        return view("user.organization.event.create-speaker",[
            'myData' => $myData,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'isManageEvent' => true,
            'eventID' => $eventID,
            'event'=>$event
        ]);
    }
    public function edit($organizationID, $eventID,$speakerID)
    {
        $organization = Organization::where('id', $organizationID)->first();
        $myData = UserController::me();
        $event = Event::where('id',$eventID)->first();
        $slug = EventController::get()->where('id', $eventID)->first('slug');
        $speaker= SpeakerController::get([['id','=',$speakerID]])->first();
        return view("user.organization.event.edit-speaker",[
            'myData' => $myData,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'speaker' => $speaker,
            'slug' => $slug,
            'isManageEvent' => true,
            'eventID' => $eventID,
            'event' => $event
        ]);
    }
}

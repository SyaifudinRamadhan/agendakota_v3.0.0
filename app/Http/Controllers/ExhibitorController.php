<?php

namespace App\Http\Controllers;

use App\Models\Exhibitor;
use App\Models\BoothAgent;
use App\Models\BoothCategory;
use App\Models\BoothProduct;
use App\Models\Organization;
use App\Models\Event;
use App\Models\User;
use App\Models\Handbook;
use App\Mail\UserInvitation;
use App\Models\PackagePayment;
use App\Models\PackagePricing;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use App\Http\Requests\exhibitor\ExhibtorStoreRequest;
// use App\Http\Requests\exhibitor\ExhibitorUpdateRequest;
use Image;
use File;

class ExhibitorController extends Controller
{
    public static function get($filter = NULL)
    {
        if ($filter == NULL) {
            return new Exhibitor;
        }
        return Exhibitor::where($filter);
    }

    public function checkLink($link)
    {
        if (strpos($link, "zoom.us") == false) {
            return -1;
        }

        $e = explode("?", $link);
        if (count($e) == 0) {
            return -1;
        } else {
            $p = explode("/", $e[0]);
        }
        $e = explode("?", $link);
        if (count($p) == 0) {
            return -1;
        } else {
            $id = $p[count($p) - 1];
        }
        if (count($e) == 0 || count($e) == 1) {
            return -1;
        } else {
            $pass = explode("pwd=", $e[1]);
        }
        if (count($pass) == 0 || count($pass) == 1) {
            return -1;
        } else {
            $password = $pass[1];
        }
        return 1;
    }

    // Function untuk cek paket akun user aktif ? atau melewati batas pembuatan
    public static function userPkgCheck($eventID)
    {
        $organization = Event::where('id', $eventID)->first()->organizer;
        $isPkgActive = PackagePricingController::limitCalculator($organization->user);
        $exhibitorEvent = Exhibitor::where('event_id', $eventID)->get();

        $paramCombine = false;
        // paramCombine adalah parameter untuk cek jumlahnya unlimited / sudah lewat batas
        if ($organization->user->package->exhibitor_count <= -1) {
            $paramCombine = false;
        } else {
            if (count($exhibitorEvent) >= $organization->user->package->exhibitor_count) {
                $paramCombine = true;
            }
        }

        if ($isPkgActive == 0 || $paramCombine == true) {
            if ($paramCombine == true) {
                return ['gagal', 'Kamu sudah mencapai batas untuk membuat exhibitor baru'];
            } else {
                return ['gagal', 'Paket yang kamu beli belum aktif / belum dibayar'];
            }
        }
        return [];
    }

    public function edit($organizationID, $eventID, $exhibitorID, Request $request)
    {
        $categories = BoothCategory::where('event_id', $eventID)->get();
        $myData = UserController::me();
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $exhibitor = ExhibitorController::get()->where('id', $exhibitorID)->first();
        $slug = EventController::get()->where('id', $eventID)->first('slug');
        $isManage = true;
        if ($organizationID == 0 || $organizationID == '0') {
            $isThisExhibitor = UserController::isExihibitor($idEvent = $eventID);
            if (count($isThisExhibitor) == 0) {
                return abort('403');
            }
            $isManage = false;
        } else {
            $nameRoute = $request->route()->getName();
            if ($nameRoute == "myExhibitionEdit") {
                return abort('403');
            }
        }
        $dataView = [
            'organizationID' => $organizationID,
            'eventID' => $eventID,
            'myData' => $myData,
            'isManageEvent' => $isManage,
            'slug' => $slug,
            'exhibitor' => $exhibitor,
            'categories' => $categories,
            'event' => Event::where('id', $eventID)->first()
        ];
        if ($organizationID == 0 || $organizationID == '0') {
            unset($dataView['isManageEvent']);
            return $dataView;
        }
        $dataView['organization'] = $organization;
        return view('user.organization.event.edit-exhibitor', $dataView);
    }

    public function edit2($eventID, $exhibitorID, Request $request)
    {
        $dataView = $this->edit(0, $eventID, $exhibitorID, $request);
        $handbooks = Handbook::where('event_id', $eventID)->where('exhibitor_id', $exhibitorID)->get();
        $dataView['handbooks'] = $handbooks;
        $dataView['totalInvite'] = 0;
        $dataView['isExhibitor'] = UserController::isExihibitor();
        $dataView['isSpeaker'] = UserController::isSpeaker();
        $dataView['boothProducts'] = BoothProduct::where('booth_id', $exhibitorID)->get();
        return view('user.my-exhibition-edit', $dataView);
    }

    public function registerUser($request)
    {
        $pkgID = $request->pkgID;

        $pkgStatus = 0;
        $price = 0;
        if ($pkgID == null) {
            $pkg = PackagePricing::where('deleted', 0)->first();
            $pkgID = $pkg->id;
            $price = $pkg->price;
        } else {
            $pkg = PackagePricing::where('id', $pkgID)->get();
            if (count($pkg) == 1) {
                if ($pkg[0]->deleted == 0) {
                    $pkgID = $pkg[0]->id;
                    $price = $pkg[0]->price;
                } else {
                    $pkg = PackagePricing::where('deleted', 0)->first();
                    $pkgID = $pkg->id;
                    $price = $pkg->price;
                }
            } else {
                $pkg = PackagePricing::where('deleted', 0)->first();
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
        do {
            // Lakukan generate orderID
            $random = new RandomStrController();
            $orderId = $random->get();
        } while (count(PackagePayment::where('order_id', $orderId)->get()) > 0);


        // ---------------------------------------------------------------------------------------


        // ----------------- Proses input ke databse pembelian paket ----------------------------------
        $status = 0;
        $snapToken = '-';
        if ($price == 0) {
            $status = 1;
        } else {
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
                    'name' => 'ID beli : ' . $orderId,
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
            'token_trx' => $snapToken,
            'nominal' => $price,
            'status' => $status
        ];

        PackagePayment::create($datasave);

        // Update data user mengganti pkg_id dan status package aktif tidaknya
        User::where('id', $saveData->id)->update([
            'pkg_status' => $status,
        ]);
        // dd($saveData);
        // --------------------------------------------------------
        #-------------------------------------------------------------------------------------------

    }

    public function store($organizationID, $eventID, Request $request)
    {
        $executionType = $request->execution_type;
        $validateRule = [];
        $myData = UserController::me();

        //-------- Cek paket akun user ----------------------------
        $arrConfirm = self::userPkgCheck($eventID);
        if (count($arrConfirm) > 0) {
            return redirect()->back()->with($arrConfirm[0], $arrConfirm[1]);
        }
        // --------------------------------------------------------

        $organization = Organization::where('id', $organizationID)->first();

        $validateRule = [
            'logo' => 'required|mimes:jpg,png,jpeg|max:' . ($organization->user->package->max_attachment * 1024),
            'name' => 'required|max:191',
            'email' => 'required|max:191|email',
            'category' => 'required|max:191',
            'address' => 'required|max:191',
            'instagram' => 'max:191|active_url|url|nullable',
            'linkedin' => 'max:191|active_url|url|nullable',
            'twitter' => 'max:191|active_url|url|nullable',
            'website' => 'max:191|active_url|url|nullable',
            'video' => 'required|active_url|url',
            'booth_image' => 'required|mimes:jpg,png,jpeg|max:' . ($organization->user->package->max_attachment * 1024),
            'description' => 'required',
            'phone' => 'numeric|digits_between:10,13',
        ];


        $validateMsg = [
            'max' => 'Kolom :Attribute maksimal 191 karakter',
            'numeric' => 'Kolom :Attribute harus berupa angka',
            'digits_between' => 'Kolom :Attribute harus antara 12 - 13 karakter angka',
            'active_url' => 'Kolom :Attribute harus beruppa link aktif',
            'url' => 'Kolom :Attribute harus berupa url link',
            'max' => 'Kolom :Attribute maksimal 191 karakter',
            'numeric' => 'Kolom :Attribute harus berupa angka',
            'digits_between' => 'Kolom :Attribute harus antara 12 - 13 karakter angka',
            'active_url' => 'Kolom :Attribute harus beruppa link aktif',
            'url' => 'Kolom :Attribute harus berupa url link',
            'required' => 'Kolom :Attribute wajib diisi',
            'name.max' => 'karakter field nama tidak boleh melebihi 191 karakter',
            'name.required' => 'tolong isi field nama',

            'website.max' => 'karakter field website tidak boleh melebihi 191 karakter',
            'website.active_url' => 'inputan yang anda masukkan bukan URL yang aktif',
            'website.url' => 'inputan yang anda masukkan bukan URL yang aktif',

            'logo.required' => 'tolong isi field logo',
            'booth_image.required' => 'tolong isi field booth image',

            'address.requried' => 'tolong isi field alamat',
            'address.max' => 'karakter field alamat tidak boleh melebihi 191 karakter',
            'mimes' => 'File yang diterimma bertipe jpg, png atau jpeg',
            'logo.max' => 'File upload maksimal ' . $organization->user->package->max_attachment . ' Mb',
            'booth_image.max' => 'File upload maksimal ' . $organization->user->package->max_attachment . ' Mb',
        ];

        $validateData = $this->validate($request, $validateRule, $validateMsg);

        $checkDouble = Exhibitor::where('event_id', $eventID)->where('email', $request->email)->get();
        if (count($checkDouble) > 0) {
            return redirect()->back()->with('gagal', 'Gagal. Email Exhibitor yang didaftarkan sudah terdaftar di event ini');
        }

        $logo = $request->file('logo');
        $originalnamelogo = $logo->getClientOriginalName();
        $logoFileName = $request->name . '_' . $originalnamelogo;

        $boot_imageFileName = " ";
        $videoPreviewName = " ";

        $booth_image = $request->file('booth_image');
        $originalnamebooth = $booth_image->getClientOriginalName();
        $boot_imageFileName = $request->name . '_' . $originalnamebooth;

        // $videoPreview = $request->file('video');
        // $originalNameVideo = $videoPreview->getClientOriginalName();
        $videoPreviewName = $request->video;

        // dd($request->video);

        $event = EventController::get()->where('id', $eventID)->get();
        $slug = "";
        foreach ($event as $ev) {
            $slug = $ev->slug;
        }
        $overview = $request->overview;
        if (!isset($overview)) {
            $overview = 0;
        } else {
            $overview = 1;
        }

        $virtual_booth = $request->virtual_booth;
        $boothLink = $request->booth_link;
        if (!isset($virtual_booth)) {
            $virtual_booth = 0;
            $boothLink = "";
        } else {
            $virtual_booth = 1;
            if (isset($boothLink)) {
                if ($this->checkLink($boothLink) == -1) {
                    return redirect()->back()->with('gagal', 'Booth Link wajib link zoom yang tepat');
                }
            } else {
                return redirect()->back()->with('gagal', 'Booth Link wajib diisi jika virtual boot di centang');
            }
        }



        $saveData = Exhibitor::create([
            'event_id' => $eventID,
            'name' => $request->name,
            'email' => $request->email,
            'website' => $request->website,
            'overview' => $overview,
            'logo' => $logoFileName,
            'booth_image' => $boot_imageFileName,
            'phone' => $request->phone,
            'address' => $request->address,
            'category' => $request->category,
            'instagram' => $request->instagram,
            'description' => $request->description,
            'linkedin' => $request->linkedin,
            'twitter' => $request->twitter,
            'virtual_booth' => $virtual_booth,
            'booth_link' => $boothLink,
            'video' => $videoPreviewName,
        ]);

        // Creating Agent
        // $agentName = $request->name."'s Agent";
        // $createAgent = BoothAgent::create([
        //     'booth_id' => $saveData->id,
        //     'name' => $agentName,
        //     'email' => $request->email,
        //     'password' => bcrypt($request->password),
        // ]);

        $filePathLogo = public_path('storage/event_assets/' . $slug . '/exhibitors/exhibitor_logo/');
        $filePathBooth = public_path('storage/event_assets/' . $slug . '/exhibitors/exhibitor_booth_image/');
        // $filePathVideo = public_path('storage/event_assets/'.$slug.'/exhibitors/exhibitor_vide_preview/');
        // $logo->storeAs('public/event_assets/'.$slug.'/exhibitors/exhibitor_logo/', $logoFileName);
        // $booth_image->storeAs('public/event_assets/'.$slug.'/exhibitors/exhibitor_booth_image/', $boot_imageFileName);
        $imgLogo = Image::make($logo->path());

        File::makeDirectory($filePathLogo, $mode = 0777, true, true);

        $imgLogo->resize(400, 400)->save($filePathLogo . '/' . $logoFileName);

        $imgBooth = Image::make($booth_image->path());

        File::makeDirectory($filePathBooth, $mode = 0777, true, true);
        // File::makeDirectory($filePathVideo, $mode = 0777, true, true);

        $imgBooth->save($filePathBooth . '/' . $boot_imageFileName);
        // $videoPreview->move($filePathVideo, $videoPreviewName);


        //create user exhibitor account
        $userDouble = User::where('email', $request->email)->get();
        if (count($userDouble) == 0) {
            $this->registerUser($request);
        }

        $invitation['link'] = route("user.emailVerification", [base64_encode($request->email), 'navTo' => 'myTickets']);
        $invitation['sender'] = $myData->name;
        $invitation['email'] = $request->email;
        $invitation['participant'] = 'Exhibitor';
        $invitation['event'] = $event[0]->name;
        $invitation['sender_mail'] = $myData->email;
        $invitation['password'] = $request->password;

        // dd($invitation,  $myData);
        // dd(new UserInvitation($invitation));

        Mail::to($request->email)->send(new UserInvitation($invitation));

        return redirect()->route('organization.event.exhibitors', [$organizationID, $eventID])->with('berhasil', 'Exhibitor telah berhasil ditambakan');
    }

    public function update($organizationID, $eventID, $exhibitorID, Request $request)
    {
        // dd($request);
        $organization = Event::where('id', $eventID)->first()->organizer;
        $myData = UserController::me();

        $validateRule = [
            'logo' => 'mimes:jpg,png,jpeg|max:' . ($organization->user->package->max_attachment * 1024),
            'name' => 'required|max:191',
            'email' => 'required|max:191|email',
            'category' => 'required|max:191',
            'address' => 'required|max:191',
            'instagram' => 'max:191|active_url|url|nullable',
            'linkedin' => 'max:191|active_url|url|nullable',
            'twitter' => 'max:191|active_url|url|nullable',
            'website' => 'max:191|active_url|url|nullable',
            'booth_image' => 'mimes:jpg,png,jpeg|max:' . ($organization->user->package->max_attachment * 1024),
            'video' => 'active_url|url',
            'description' => 'required',
            'phone' => 'numeric|digits_between:10,13',
        ];


        $validateMsg = [
            'required' => 'Kolom :Attribute wajib diisi',
            'name.max' => 'karakter field nama tidak boleh melebihi 191 karakter',
            'name.required' => 'tolong isi field nama',

            'website.max' => 'karakter field website tidak boleh melebihi 191 karakter',
            'website.active_url' => 'inputan yang anda masukkan bukan URL yang aktif',
            'website.url' => 'inputan yang anda masukkan bukan URL yang aktif',

            'logo.required' => 'tolong isi field logo',
            'booth_image.required' => 'tolong isi field booth image',

            'address.requried' => 'tolong isi field alamat',
            'address.max' => 'karakter field alamat tidak boleh melebihi 191 karakter',
            'mimes' => 'File yang diterimma bertipe jpg, png atau jpeg',
            'logo.max' => 'File upload maksimal ' . $organization->user->package->max_attachment . ' Mb',
            'booth_image.max' => 'File upload maksimal ' . $organization->user->package->max_attachment . ' Mb',
        ];

        $validateData = $this->validate($request, $validateRule, $validateMsg);

        $id = $exhibitorID;

        $event = EventController::get()->where('id', $eventID)->get();
        $slug = "";
        foreach ($event as $ev) {
            $slug = $ev->slug;
        }

        $filePathLogo = public_path('storage/event_assets/' . $slug . '/exhibitors/exhibitor_logo/');
        $filePathBooth = public_path('storage/event_assets/' . $slug . '/exhibitors/exhibitor_booth_image/');
        $filePathVideo = public_path('storage/event_assets/' . $slug . '/exhibitors/exhibitor_vide_preview/');

        $overview = $request->overview;
        if (!isset($overview)) {
            $overview = 0;
        } else {
            $overview = 1;
        }
        $exhibtor = Exhibitor::find($id);
        $logo = $request->file('logo');
        $booth_image = $request->file('booth_image');
        // $videoPreview = $request->file('video');

        $logoFileName = "";

        if ($logo) {
            // Storage::delete('public/event_assets/'.$slug.'/exhibitors/exhibitor_logo/'.$exhibtor->logo);
            $originalnamelogo = $logo->getClientOriginalName();
            $logoFileName = $request->name . '_' . $originalnamelogo;
            // $logo->storeAs('public/event_assets/'.$slug.'/exhibitors/exhibitor_logo/', $logoFileName);
            File::delete($filePathLogo . $exhibtor->logo);
            $imgLogo = Image::make($logo->path());
            $imgLogo->resize(400, 400)->save($filePathLogo . '/' . $logoFileName);
        } else {
            $logoFileName = $exhibtor->logo;
        }

        $boot_imageFileName = "";

        if ($booth_image) {
            // Storage::delete('public/event_assets/'.$slug.'/exhibitors/exhibitor_booth_image/'.$exhibtor->booth_image);
            $originalnamebooth = $booth_image->getClientOriginalName();
            $boot_imageFileName = $request->name . '_' . $originalnamebooth;
            // $booth_image->storeAs('public/event_assets/'.$slug.'/exhibitors/exhibitor_booth_image/', $boot_imageFileName);
            File::delete($filePathBooth . '/' . $exhibtor->booth_image);
            $imgBooth = Image::make($booth_image->path());
            $imgBooth->save($filePathBooth . '/' . $boot_imageFileName);
        } else {
            $boot_imageFileName = $exhibtor->booth_image;
        }

        $videoPreviewFileName = $request->video;

        // if($videoPreview){
        //     $originalVideoName = $videoPreview->getClientOriginalName();
        //     $videoPreviewFileName = $request->name.'_'.$originalVideoName;

        //     File::delete($filePathVideo.'/'.$exhibtor->video);
        //     $videoPreview->move($filePathVideo, $videoPreviewFileName);
        //     // dd($videoPreview);
        // }else{
        //     $videoPreviewFileName = $exhibtor->video;
        // }

        $overview = $request->overview;
        if (!isset($overview)) {
            $overview = 0;
        } else {
            $overview = 1;
        }

        $virtual_booth = $request->virtual_booth;
        $boothLink = $request->booth_link;
        if (!isset($virtual_booth)) {
            $virtual_booth = 0;
            $boothLink = "";
        } else {
            $virtual_booth = 1;
            if (isset($boothLink)) {
                if ($this->checkLink($boothLink) == -1) {
                    return redirect()->back()->with('gagal', 'Booth Link wajib link zoom yang tepat');
                }
            } else {
                return redirect()->back()->with('gagal', 'Booth Link wajib diisi jika virtual boot di centang');
            }
        }

        $emailExh = $request->email;
        $emailChange = false;

        $request->password = "(default milik user)";

        if ($organizationID == 0 || $organizationID == '0') {
            $isThisExhibitor = UserController::isExihibitor($eventID);
            // dd($isThisExhibitor);
            if (count($isThisExhibitor) == 0) {
                return abort('403');
            }
            $emailExh = $myData->email;
        } else {
            // Aku lupa ini untuk catch apa ?
            $nameRoute = $request->route()->getName();
            if ($nameRoute == "myExhibitionUpdate") {
                return abort('403');
                // dd($request);
            }
            // Handle update email oleh organizer
            $thisExhibitor = Exhibitor::where('id', $id)->first();
            if($request->email != $thisExhibitor->email){
                // Memindahkan / membuat akun exhibitor baru
                $userDouble = User::where('email', $request->email)->get();
                if(count($userDouble) == 0){
                    $request->password = "agendakota_exh";
                    $this->registerUser($request);
                }
                $emailChange = true;
            }
        }

        $updateData = Exhibitor::where('id', $id)->update([
            'event_id' => $eventID,
            'name' => $request->name,
            'email' => $emailExh,
            'website' => $request->website,
            'overview' => $overview,
            'logo' => $logoFileName,
            'booth_image' => $boot_imageFileName,
            'phone' => $request->phone,
            'address' => $request->address,
            'category' => $request->category,
            'instagram' => $request->instagram,
            'description' => $request->description,
            'linkedin' => $request->linkedin,
            'twitter' => $request->twitter,
            'virtual_booth' => $virtual_booth,
            'booth_link' => $boothLink,
            'video' => $videoPreviewFileName,
        ]);

        if($emailChange){
            $invitation['link'] = route("user.emailVerification", [base64_encode($request->email), 'navTo' => 'myTickets']);
            $invitation['sender'] = $myData->name;
            $invitation['email'] = $request->email;
            $invitation['participant'] = 'Exhibitor';
            $invitation['event'] = $event[0]->name;
            $invitation['sender_mail'] = $myData->email;
            $invitation['password'] = $request->password;

            Mail::to($request->email)->send(new UserInvitation($invitation));
        }

        if ($organizationID == 0 || $organizationID == '0' || $request->route()->getName() == "myExhibitionUpdate") {
            return redirect()->back()->with('berhasil', 'Exhibitor telah berhasil diedit');
        }
        return redirect()->route('organization.event.exhibitors', [$organizationID, $eventID])->with('berhasil', 'Exhibitor telah berhasil diedit');
    }
    public function delete($organizationID, $eventID, $exhibitorID)
    {


        $event = EventController::get()->where('id', $eventID)->get();
        $slug = "";
        foreach ($event as $ev) {
            $slug = $ev->slug;
        }
        $data = ExhibitorController::get()->where('id', $exhibitorID)->get();

        foreach ($data as $dt) {
            $logo = $dt->logo;
            File::delete(public_path('storage/event_assets/' . $slug . '/exhibitors/exhibitor_logo/' . $logo));

            $booth_image = $dt->booth_image;
            File::delete(public_path('storage/event_assets/' . $slug . '/exhibitors/exhibitor_booth_image/' . $booth_image));

            $videoPreview = $dt->video;
            File::delete(public_path('storage/event_assets/' . $slug . '/exhibitors/exhibitor_vide_preview/' . $booth_image));
        }

        $deleteData = Exhibitor::where('id', $exhibitorID)->delete();
        return redirect()->route('organization.event.exhibitors', [$organizationID, $eventID])->with('berhasil', 'Exhibitor telah berhasil dihapus');
    }

    public function createExhibitornPage($organizationID, $eventID)
    {
        //-------- Cek paket akun user ----------------------------
        $arrConfirm = self::userPkgCheck($eventID);
        if (count($arrConfirm) > 0) {
            return redirect()->back()->with($arrConfirm[0], $arrConfirm[1]);
        }
        // --------------------------------------------------------
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $myData = UserController::me();
        $categories = BoothCategory::where('event_id', $eventID)->get();
        // $totalInvite = UserController::getNotifyInvitation($myData->id);
        return view("user.organization.event.create-exhibitor", [
            'myData' => $myData,
            'categories' => $categories,
            'organizationID' => $organizationID,
            'organization' => $organization,
            'isManageEvent' => true,
            // 'totalInvite' => $totalInvite,
            'eventID' => $eventID,
            'event' => Event::where('id', $eventID)->first()
        ]);
    }
    public function storeCategory($organizationID, $eventID, Request $request)
    {
        $event = EventController::get()->where('id', $eventID)->first();
        $slug = $event->slug;
        
        $filePathIcon = public_path('storage/event_assets/' . $slug . '/exhibitors/exhibitor_cat_icon/');
        
        // validator
        $validateRule = [
            'icon' => 'mimes:jpg,png,jpeg|required',
            'name' => 'required',
            'priority' => 'required'
        ];

        $validateMsg = [
            'required' => 'Kolom :Attribute wajib diisi',
            'mimes' => 'File yang diterima bertipe jpg, png atau jpeg',
        ];

        $validate = $this->validate($request, $validateRule, $validateMsg);

        $checkDouble = BoothCategory::where('name', $request->name)->where('event_id', $event->id)->get();
        if(count($checkDouble) > 0){
            return redirect()->back()->with('gagal', 'Nama kategori sudah ada');
        }
        
        if (!file_exists($filePathIcon)){
            File::makeDirectory($filePathIcon, $mode = 0777, true, true);
        }

        $originalNameIcon = $request->file('icon')->getClientOriginalName();
        $bootCatIcon = $request->name . '_' . $originalNameIcon;
        $imgIconCatBooth = Image::make($request->file('icon')->path());
        $imgIconCatBooth->resize(50, 50)->save($filePathIcon . $bootCatIcon);

        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $saveData = BoothCategory::create([
            'event_id' => $eventID,
            'name' => $request->name,
            'priority' => $request->priority,
            'icon' => $bootCatIcon
        ]);

        return redirect()->route('organization.event.booth.category', [$organizationID, $eventID])->with([
            'message' => "Kategori booth baru berhasil ditambahkan"
        ]);
    }
    public function updateCategory($organizationID, $eventID, Request $request)
    {
        $id = $request->id;
        $organization = Organization::where('id', $organizationID)->with('events')->first();
        $data = BoothCategory::where('id', $id);
        $category = $data->first();

        $event = EventController::get()->where('id', $eventID)->first();
        $slug = $event->slug;
        
        $filePathIcon = public_path('storage/event_assets/' . $slug . '/exhibitors/exhibitor_cat_icon/');
        
        // validator
        $validateRule = [
            'icon' => 'mimes:jpg,png,jpeg',
            'name' => 'required',
            'priority' => 'required'
        ];

        $validateMsg = [
            'required' => 'Kolom :Attribute wajib diisi',
            'mimes' => 'File yang diterimma bertipe jpg, png atau jpeg',
        ];

        $this->validate($request, $validateRule, $validateMsg);

        $checkDouble = BoothCategory::where('name', $request->name)->where('event_id', $event->id)->get();
        if(count($checkDouble) > 1){
            return redirect()->back()->with('gagal', 'Nama kategori sudah ada');
        }else if(count($checkDouble) == 1 && $checkDouble[0]->id != $category->id){
            return redirect()->back()->with('gagal', 'Nama kategori sudah ada');
        }

        if (!file_exists($filePathIcon)){
            File::makeDirectory($filePathIcon, $mode = 0777, true, true);
        }

        $iconName = '';
        if($request->file('icon')){
            $originalNameIcon = $request->file('icon')->getClientOriginalName();
            $bootCatIcon = $request->name . '_' . $originalNameIcon;
            File::delete($filePathIcon.'/'.$category->icon);
            $imgIconCatBooth = Image::make($request->file('icon')->path());
            $imgIconCatBooth->resize(50, 50)->save($filePathIcon . $bootCatIcon);
            $iconName = $bootCatIcon;
        }else{
            $iconName = $category->icon;
        }

        $updateData = $data->update([
            'name' => $request->name,
            'priority' => $request->priority,
            'icon' => $iconName
        ]);

        return redirect()->route('organization.event.booth.category', [$organizationID, $eventID])->with([
            'message' => "Kategori " . $category->name . " berhasil diubah"
        ]);
    }
    public function deleteCategory($organizationID, $eventID, Request $request)
    {
        $id = $request->id;
        $data = BoothCategory::where('id', $id);
        $category = $data->first();

        $event = EventController::get()->where('id', $eventID)->first();
        $slug = $event->slug;

        $filePathIcon = public_path('storage/event_assets/' . $slug . '/exhibitors/exhibitor_cat_icon/');
        File::delete($filePathIcon.'/'.$category->icon);

        $deleteData = $data->delete();

        return redirect()->route('organization.event.booth.category', [$organizationID, $eventID])->with([
            'message' => "Kategori " . $category->name . " berhasil dihapus"
        ]);
    }
    public static function getCategories($filter = NULL)
    {
        if ($filter == NULL) {
            return new BoothCategory;
        }
        return BoothCategory::where($filter);
    }

    public function showExhibitor($eventID, $exhibitorID)
    {
        $exhibitors = "";
        if ($exhibitorID != 0 || $exhibitorID != '0') {
            $exhibitors = Exhibitor::where('id', $exhibitorID)->get();
        } else {
            $exhibitors = Exhibitor::where('event_id', $eventID)->get();
        }

        $myData = UserController::me();
        $organizations = null;
        if (isset($myData)) {
            $organizations = OrganizationController::get()->where('user_id', $myData->id)->with('user')->get();
        } else {
            $organizations = null;
        }

        $event = EventController::get([
            ['id', $eventID]
        ])
            ->with(['organizer', 'sponsors', 'exhibitors', 'sessions.tickets', 'speakers'])
            ->first();
        //    dd(redirect()->back());
        // dd($exhibitors, $eventID, $exhibitorID);
        return view('user.exhibitions', [
            'myData' => $myData,
            'event' => $event,
            'organizations' => $organizations,
            'exhibitors' => $exhibitors,
        ]);
    }
}

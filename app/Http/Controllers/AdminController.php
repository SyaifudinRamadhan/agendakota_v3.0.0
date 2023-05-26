<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Event;
use App\Models\FrontBanner;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserWithdrawalEvent;
use App\Models\OrganizationType;
use App\Models\PackagePayment;
use App\Models\PackagePricing;
use App\Models\OrganizationTeam;
use Session;
use Yajra\Datatables\Datatables;
use Str;
use Image;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{    

    public function validateStore($request){
        $messages=[
            'required'=>':Attribute Wajib Diisi',
            'email.unique'=>'Email '.$request->email.' sudah terdaftarkan',
            'min'=>':Attribute Diisi Minimal :min Karakter',
            'max'=>':Attribute Diisi Minimal :max Karakter',
        ];
        $validateData = $this->validate($request, [
            'name' => 'required|max:255' ,
            'email' => 'required|email|min:5|max:255|unique:admins,email',
            'password' => 'required|min:6'
        ],$messages);
    }

    public function vaidateUpdate($request){
        $messages=[
            'required'=>':Attribute Wajib Diisi',
            'min'=>':Attribute Diisi Minimal :min Karakter',
            'max'=>':Attribute Diisi Minimal :max Karakter',
        ];
        $validateRule = [
            'name' => 'required|max:255' ,
            'email' => 'required|email|min:5|max:255',            
        ];

        $karyawanData = Admin::where('id', $request->id)->first();

        if($request->email != $karyawanData->email){
            $messages['email.unique'] = 'Email '.$request->email.' sudah terdaftarkan';
            $validateRule['email'] = 'required|email|min:5|max:255|unique:admins,email';

        }

        $validateData = $this->validate($request, $validateRule, $messages);
    }


    public function dashboard() {
        $users = User::get(['id','name']);
        $events = Event::get(['id','name']);
        $organization = Organization::get(['id','name']);
        
        return view('admin.dashboard', [
            'users' => $users,  
            'events' => $events,  
            'organization' => $organization,                       
        ]);
    }

    public function karyawan(Request $request) {        
        
        return view('admin.karyawan');
    }

    public function getDataKaryawan(Request $request)
    {
        $karyawan = Admin::where('role', 1)->select(['id', 'name', 'email']);
        return Datatables::of($karyawan)        
                    ->addColumn('action', function($row){

                           
                        $btn = '<a class="col-md-5 ml-2 mr-2 btn btn-info btn-sm text-white edit" data-toggle="modal" data-target="#edit_admin" data-id="'.$row->id.'"><i class="fa fa-pencil"></i> Edit</a>';                           
                        $btn = $btn.'<a href="karyawan/' .$row->id. '/delete" class="col-md-5 ml-2 mr-2 btn btn-danger btn-sm" onclick="return confirm(' . "'Are you sure?'" . ')"><i class="fa fa-trash"></i> Delete</a>';
         
                            return $btn;
                    })
                    ->rawColumns(['action'])  
        ->make();
    }

    public function storeDataKaryawan(Request $request) {
        
        $this->validateStore($request);
         if($request->password != $request->repeat_password){
            return redirect()->route('admin.karyawan')->with('gagal', 'Konfirmasi ulang password tidak sesuai');
        }

        $saveData = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => '1',            
        ]);

        return redirect()->route('admin.karyawan')->with('berhasil','Berhasil Menambahkan Admin');
    }

    public function editKaryawan(Request $request)
    {
        $data = Admin::findOrFail($request->get('id'));
        echo json_encode($data);
    }

    public function update_karyawan(Request $request) {
        
        $this->vaidateUpdate($request);

        $updateData = Admin::where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,                               
        ]);

        return redirect()->route('admin.karyawan')->with('berhasil','Berhasil mengubah data adminr');
    }

    public function detil_karyawan($id) {
        $user = User::where('id', $id)->first();
        
        return view('admin.detil_user', [
            'user' => $user,            
        ]);
    }

    public function delete_karyawan($id) {
        $karyawan = Admin::where('id', $id)->delete();
        
        return redirect()->route('admin.karyawan')->with('berhasil', 'Data karyawan berhasil dihapus');
    }

    // ========================== Data FInance maksudnya apa ? =======================

    public function finance(Request $request) {        
        
        return view('admin.finance');
    }

    public function getDataFinance(Request $request)
    {
        $finance = Admin::where('role', 2)->select(['id', 'name', 'email']);
        return Datatables::of($finance)        
                    ->addColumn('action', function($row){

                           
                        $btn = '<a class="col-md-5 ml-2 mr-2 btn btn-info btn-sm text-white edit" data-toggle="modal" data-target="#edit_finance" data-id="'.$row->id.'"><i class="fa fa-pencil"></i> Edit</a>';
                        $btn = $btn.'<a href="finance/' .$row->id. '/delete" class="col-md-5 ml-2 mr-2 btn btn-danger btn-sm" onclick="return confirm(' . "'Are you sure?'" . ')"><i class="fa fa-trash"></i> Delete</a>';
         
                            return $btn;
                    })
                    ->rawColumns(['action'])  
        ->make();
    }

    public function storeDataFinance(Request $request) {
        $this->validateStore($request);
         if($request->password != $request->repeat_password){
            return redirect()->route('admin.finance')->with('gagal', 'Konfirmasi ulang password tidak sesuai');
        }

        $saveData = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => '2',            
        ]);

        return redirect()->route('admin.finance')->with('berhasil','Berhasil Menambahkan Finance');
    }

    public function editFinance(Request $request)
    {
        $data = Admin::findOrFail($request->get('id'));
        echo json_encode($data);
    }

    public function update_finance(Request $request) {
       $this->vaidateUpdate($request);

        $updateData = Admin::where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,                               
        ]);

        return redirect()->route('admin.finance')->with('berhasil','Berhasil Merubah Data');
    }

    public function detil_finance($id) {
        $finance = Admin::where('id', $id)->first();
        
        return view('admin.detil_finance', [
            'finance' => $finance,            
        ]);
    }

    public function delete_finance($id) {
        $finance = Admin::where('id', $id)->delete();
        
        return redirect()->route('admin.finance')->with('berhasil', 'Data admin finance berhasil dihapus');
    }

    //===============================================================================

    public function manager(Request $request) {        
        
        return view('admin.manager');
    }

    public function getDataManager(Request $request)
    {
        $manager = Admin::where('role', 3)->select(['id', 'name', 'email']);
        return Datatables::of($manager)        
                    ->addColumn('action', function($row){

                           
                        $btn = '<a class="col-md-5 ml-2 mr-2 btn btn-info btn-sm text-white edit" data-toggle="modal" data-target="#edit_manager" data-id="'.$row->id.'"><i class="fa fa-pencil"></i> Edit</a>';                      
                        $btn = $btn.'<a href="manager/' .$row->id. '/delete" class="col-md-5 ml-2 mr-2 btn btn-danger btn-sm" onclick="return confirm(' . "'Are you sure?'" . ')"><i class="fa fa-trash"></i> Delete</a>';
         
                            return $btn;
                    })
                    ->rawColumns(['action'])  
        ->make();
    }

    public function storeDataManager(Request $request) {
        $this->validateStore($request);
         if($request->password != $request->repeat_password){
            return redirect()->route('admin.manager')->with('gagal', 'Konfirmasi ulang password tidak sesuai');
        }
        $saveData = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => '3',            
        ]);

        return redirect()->route('admin.manager')->with('berhasil','Berhasil Menambahkan Manager');
    }
    
    public function editManager(Request $request)
    {
        $data = Admin::findOrFail($request->get('id'));
        echo json_encode($data);
    }

    public function update_manager(Request $request) {
        $this->vaidateUpdate($request);

        $updateData = Admin::where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,                               
        ]);

        return redirect()->route('admin.manager')->with('berhasil','Berhasil Merubah Data');
    }

    public function detil_manager($id) {
        $manager = Admin::where('id', $id)->first();
        
        return view('admin.detil_manager', [
            'manager' => $manager,            
        ]);
    }

    public function delete_manager($id) {
        $manager = Admin::where('id', $id)->delete();
        
        return redirect()->route('admin.manager')->with('berhasil', 'Admin manager berhasil dihapus');
    }

    public function author(Request $request) {        
        
        return view('admin.author');
    }

    public function getDataAuthor(Request $request)
    {
        $author = Admin::where('role', 4)->select(['id', 'name', 'email']);
        return Datatables::of($author)        
                    ->addColumn('action', function($row){

                           
                        $btn = '<a class="col-md-5 ml-2 mr-2 btn btn-info btn-sm text-white edit" data-toggle="modal" data-target="#edit_author" data-id="'.$row->id.'"><i class="fa fa-pencil"></i> Edit</a>';                         
                        $btn = $btn.'<a href="author/' .$row->id. '/delete" class="col-md-5 ml-2 mr-2 btn btn-danger btn-sm" onclick="return confirm(' . "'Are you sure?'" . ')"><i class="fa fa-trash"></i> Delete</a>';
         
                            return $btn;
                    })
                    ->rawColumns(['action'])  
        ->make();
    }

    public function storeDataAuthor(Request $request) {
        $this->validateStore($request);
         if($request->password != $request->repeat_password){
            return redirect()->route('admin.author')->with('gagal', 'Konfirmasi ulang password tidak sesuai');
        }

        $saveData = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => '4',            
        ]);

        return redirect()->route('admin.author')->with('berhasil','Berhasil Menambahkan Author');
    }

    public function editAuthor(Request $request)
    {
        $data = Admin::findOrFail($request->get('id'));
        echo json_encode($data);
    }

    public function update_author(Request $request) {
        $this->vaidateUpdate($request);

        $updateData = Admin::where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,                               
        ]);

        return redirect()->route('admin.author')->with('berhasil','Berhasil Merubah Data');
    }

    public function detil_author($id) {
        $author = Admin::where('id', $id)->first();
        
        return view('admin.detil_author', [
            'author' => $author,            
        ]);
    }

    public function delete_author($id) {
        $author = Admin::where('id', $id)->delete();
        
        return redirect()->route('admin.author')->with('berhasil', 'Admin author berhasil dihapus');
    }

    public function user(Request $request) {        
        
        return view('admin.user');
    }

    public function getDataUser(Request $request)
    {
        $user = User::select(['id', 'name', 'email']);

        return Datatables::of($user)        
                    ->addColumn('action', function($row){
                        
                        $btn = '<a href="user/' .$row->id. '/detil" class="col-md-3 ml-2 mr-2 btn btn-info btn-sm"><i class="fa fa-eye"></i> View</a>';
                        $btn = $btn.'<a class="col-md-3 ml-2 mr-2 btn btn-dark btn-sm text-white edit" data-toggle="modal" data-target="#edit_user" data-id="'.$row->id.'"><i class="fa fa-pencil"></i> Edit</a>';                           
                        $btn = $btn.'<a href="user/' .$row->id. '/delete" class="col-md-3 ml-2 mr-2 btn btn-danger btn-sm" onclick="return confirm(' . "'Are you sure?'" . ')"><i class="fa fa-trash"></i> Delete</a>';                     
                        
                            return $btn;
                    })
                    ->rawColumns(['action'])  
        ->make();
        
    }

    public function storeDataUser(Request $request) {
        $this->validateStore($request);
         if($request->password != $request->repeat_password){
            return redirect()->route('admin.user')->with('gagal', 'Konfirmasi ulang password tidak sesuai');
        }

        $saveData = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'photo' => 'default',
            'is_active' => 1
        ]);

        return redirect()->route('admin.user')->with('berhasil','Berhasil Menambahkan User');
    }

    public function editUser(Request $request)
    {
        $data = User::findOrFail($request->get('id'));
        echo json_encode($data);
    }

    public function update_user(Request $request) {
        $messages=[
            'required'=>':Attribute Wajib Diisi',
            'min'=>':Attribute Diisi Minimal :min Karakter',
            'max'=>':Attribute Diisi Minimal :max Karakter',
        ];

        $validateRule = [
            'name' => 'required|max:255',
            'email' => 'required|email|min:5|max:255',        
        ];

        $myData = User::where('id', $request->id)->first();

        //cek password
        if($request->email  != $myData->email){
            $messages['email.unique'] = 'Email ' .$request->email .' sudah pernah di daftarkan';
            $validateRule['email'] = 'required|email|min:5|max:255|unique:users,email';
        }

        $validateData = $this->validate($request, $validateRule, $messages);

        $updateData = User::where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,                               
        ]);

        return redirect()->route('admin.user')->with('berhasil','Berhasil Menambahkan User');
    }

    public function detil_user($id) {
        $user = User::where('id', $id)->first();
        
        return view('admin.detil_user', [
            'user' => $user,            
        ]);
    }

    public function delete_user($id) {
        $user = User::where('id', $id)->delete();
        
        return redirect()->route('admin.user')->with('berhasil','Berhasil Menghapus User');
    }

    public function organizer(Request $request) {        
        
        return view('admin.organizer');
    }

    public function getDataOrganizer(Request $request)
    {
        $organizer = Organization::select(['id', 'name', 'email'])->where('deleted', 0);

        return Datatables::of($organizer)        
                    ->addColumn('action', function($row){

                           $btn = '<a href="organizer/' .$row->id. '/detil" class="edit col-md-5 ml-2 mr-2 btn btn-info btn-sm"><i class="fa fa-eye"></i> View</a>';                           
                           $btn = $btn.'<a href="organizer/' .$row->id. '/delete" class="edit col-md-5 ml-2 mr-2 btn btn-danger btn-sm" onclick="return confirm(' . "'Are you sure?'" . ')"><i class="fa fa-trash"></i> Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])  
        ->make();
    }

    public function detil_organizer($id) {
        $organization = Organization::where('id', $id)->first();

        // dd($organization);
        return view('admin.detil_organizer', [
            'organization' => $organization,            
        ]);
    }

    public function delete_organizer($id) {
        // $organization = Organization::where('id', $id)->delete();
        $organization = Organization::where('id', $id)->where('deleted', 0)->first();
        if (!$organization) {
            return redirect()->back()->with('gagal', 'Data organisasi tidak ditemukan');
        }
      
        Organization::where('id', $id)->update([
            'deleted' => 1
        ]);
        OrganizationTeam::where('organization_id', $id)->delete();
        Event::where('organizer_id', $id)->update([
            'deleted' => 1
        ]);
        return redirect()->route('admin.organizer');
    }

    public function event(Request $request) {        
        
        $allCategory = CategoryController::get();

        $data = [
            'categories' => $allCategory,
        ];

        return view('admin.event', $data);
    }

    public function getDataEvent(Request $request)
    {
        $event = Event::where('deleted',0)->with(['organizer'])->get();

        $tables =  Datatables::of($event)        
                    ->addColumn('action', function($row){

                        // $btn = '<a href="user/' .$row->id. '/detil" class="col-md-3 ml-2 mr-2 btn btn-info btn-sm"><i class="fa fa-eye"></i> View</a>';
                        // $btn = $btn.'<a class="col-md-3 ml-2 mr-2 btn btn-dark btn-sm text-white edit" data-toggle="modal" data-target="#edit_user" data-id="'.$row->id.'"><i class="fa fa-pencil"></i> Edit</a>';                           
                        // $btn = $btn.'<a href="user/' .$row->id. '/delete" class="col-md-3 ml-2 mr-2 btn btn-danger btn-sm" onclick="return confirm(' . "'Are you sure?'" . ')"><i class="fa fa-trash"></i> Delete</a>';   

                           $btn = '<a href="'.route('user.eventDetail', [$row->slug]).'" class="edit col-md-3 ml-2 mr-2 mb-2 btn btn-info btn-sm">View</a>';
                        //    $btn = $btn.'<a class="edit col-md-3 ml-2 mr-2 btn btn-primary btn-sm" data-toggle="modal" data-target="#edit_event" data-id="'.$row->id.'">Edit</a>';
                            
                            if($row->featured == 0){
                                $btn = $btn.'<a href="'.route('admin.event.set_featured', [$row->id]).'" class="edit col-md-5 ml-2 mr-2 mb-2 btn btn-primary text-light btn-sm">Set Featured</a>';
                            }else{
                                $btn = $btn.'<a href="'.route('admin.event.set_featured', [$row->id]).'" class="edit col-md-5 ml-2 mr-2 mb-2 btn btn-primary text-light btn-sm">To Top</a>';
                                $btn = $btn.'<a href="'.route('admin.event.unset_featured', [$row->id]).'" class="edit col-md-5 ml-2 mr-2 mb-2 btn btn-danger btn-sm">Un-Featured</a>';
                            }

                            $btn = $btn.'<a href="'.route('admin.event.delete', [$row->organizer->id,$row->id]).'" class="edit col-md-3 ml-2 mr-2 mb-2 btn btn-danger btn-sm">Delete</a>';
         
                            return $btn;
                    })
                    ->addColumn('status', function ($row)
                    {
                        return $row->is_publish == 1 ? 'published' : 'unpublish';
                    })
                    ->rawColumns(['action'])  
        ->make();
        return $tables;
    }

    // public function eventDetail($idEvent){
    //     $event = Event::where('id', $idEvent)->first();

    //     $data = [
    //         'event'=>$event
    //     ];

    //     return view('admin.detail_event', $data);
    // }

    public function eventEdit(Request $request){
        $event = Event::findOrFail($request->get('idEventEdit'));
        echo json_encode($event);
    }

    public function updateEvent(Request $request){
        
        $validateRule = [
            'name' => 'required',
            'category' => 'required'
        ];

        $validateMsg = [
            'required' => 'Kolom :Attribute wajib diisi'
        ];

        $validateData = $this->validate($request, $validateRule, $validateMsg);

        $lastEvent = Event::where('id', $request->id)->first();

        if($lastEvent->slug != Str::slug($request->name)){
           
            File::move(public_path("storage/event_assets/" . $lastEvent->slug), public_path("storage/event_assets/" . Str::slug($request->name)));
        }

        $dataUpdate = [
            'name' => $request->name,
            'category' => $request->category,
            'slug' => Str::slug($request->name)
        ];

        $updateEvent = Event::where('id', $request->id)->update($dataUpdate);

        return redirect()->route('admin.event')->with('berhasil', 'Data event berhasil diperbarui');
    }

    // public function deleteEvent($idEvent){
    //     $eventObj = Event::where('id', $idEvent);

    //     $eventData = $eventObj->first();
    //     File::deleteDirectory('storage/event_assets/'.$eventData->slug);

    //     $eventObj->delete();
    //     return redirect()->route('admin.event')->with('berhasil', 'Event berhasil dihapus');
    // }

    public function statis() {
        return view('admin.page');
    }

    public static function me() {
        return Auth::guard('admin')->user();
    }

    public function loginPage(Request $request) {
        $message = Session::get('message');

        return view('admin.login', [
            'message' => $message,
            'redirectTo' => $request->redirect_to,
        ]);
    }

    public function login(Request $request) {
        $validateData = $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $loggingIn = Auth::guard('admin')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        if(!$loggingIn){
            return redirect()->route('admin.loginPage')->withErrors(['Email atau kata sandi salah']);;
        }

        $myData = self::me();

        if ($request->redirectTo != "") {
            return redirect()->route($request->redirectTo);
        }

        if($myData->role == '1' || $myData->role == 'admin'){
            return redirect()->route('admin.dashboard');
        }else if ($myData->role == '2'){

        }else if ($myData->role == '3'){
            return redirect()->route('admin.event');
        }else if ($myData->role == '4'){
            
        }
        
    }

    public function register(Request $request) {
        $validateData = $this->validate($request, [
            'name' => 'required|max:255:min:5',
            'email' => 'required|email|min:5|max:255',
            'password' => 'required|min:6'
        ]);

        $saveData = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_active' => 1
        ]);

        return redirect()->route('admin.registerSuccess');
    }

    public function logout() {
        $loggingOut = Auth::guard('admin')->logout();

        return redirect()->route('admin.loginPage')->with([
            'message' => "Berhasil logout"
        ]);
    }

    public function index() {
        return redirect()->route('admin.loginPage');
    }









    // ------------ Yang sudah diperbaiki backendnya ----------------------
    public function category() {
        $myData = self::me();
        $message = Session::get('message');
        $categories = CategoryController::get()->get();

        return view('admin.category',[
            'message' => $message,
            'myData' => $myData,
            'categories' => $categories,
        ]);
    }
    public function city() {
        $myData = self::me();
        $message = Session::get('message');
        $cities = CityController::get()->orderBy('priority', 'DESC')->orderBy('updated_at', 'DESC')
        ->get();

        return view('admin.city', [
            'myData' => $myData,
            'message' => $message,
            'cities' => $cities
        ]);
    }
    public function financeReport()
    {
        $totalIncome = 0;
        $totalWithdraw = '';
        $withdrawAccept = [];
        $withdrawDisaccept = [];
        $withdrawWaiting = [];
        $valueWithdrawAccept = 0;
        $valueWithdrawInverse = 0;
        $valueWithdrawWaiting = 0;
        $realProfit = 0;
        $valueResidual = 0;

        // $arrProfit= [];

        $allEvent = Event::all();
        $allPayment = Payment::where('pay_state', 'Terbayar')->get();
        $totalWithdraw = UserWithdrawalEvent::all();

        for($i=0; $i<count($allPayment); $i++){
            $totalIncome += $allPayment[$i]->price;
        }

        for ($i=0; $i < count($totalWithdraw); $i++) { 
            if($totalWithdraw[$i]->status == 'accepted'){
                array_push($withdrawAccept, $totalWithdraw[$i]);
                $valueWithdrawAccept += $totalWithdraw[$i]->nominal;
            }else if($totalWithdraw[$i]->status == 'rejected'){
                array_push($withdrawDisaccept, $totalWithdraw[$i]);
                $valueWithdrawInverse += $totalWithdraw[$i]->nominal;
            }else if($totalWithdraw[$i]->status == 'waiting'){
                array_push($withdrawWaiting, $totalWithdraw[$i]);
                $valueWithdrawWaiting += $totalWithdraw[$i]->nominal;
            }
        }

        $organizaionCtrl = new OrganizationController();
        $tmp = $organizaionCtrl->getPaymentsEvent($allEvent);
        $dataPayments = $tmp[0];

        for($i=0; $i<count($dataPayments); $i++){
            // $tmp = (float)$dataPayments[$i]['value'] - ((float)$dataPayments[$i]['value']*config('agendakota')['profit']);
            // ----------- Ubah nilai potongan sesuai config package user by event --------------------------------------------
            
            // ----- Mengatasi ketidak sinkronan data withdraw dengan data profit -----------------------------------------------------------------------
            $withdrawAcc = UserWithdrawalEvent::where('event_id', $dataPayments[$i]['event']->id)->where('status', 'accepted')->get();
            $withdrawWait = UserWithdrawalEvent::where('event_id', $dataPayments[$i]['event']->id)->where('status', 'waiting')->get();
            // ------------------------------------------------------------------------------------------------------------------------------------------
            $realEvtProfitUser = 0;
            $profitPercentage = $dataPayments[$i]['event']->organizer->user->package->ticket_commission;
            $realEvtProfitUser = (float)$dataPayments[$i]['value'] - ((float)$dataPayments[$i]['value']*$profitPercentage);
            
            if($realEvtProfitUser > (config('agendakota')['min_transfer']+config('agendakota')['profit+'])){
                $realEvtProfitUser -= config('agendakota')['profit+'];
            }

            // Mengatasi kesalapahaman pengguna / mengunci data profit jika sudah pernah terjadi withdraw dari user
            // Jika tidak dikunci nilai profit bisa berubah ubah sendiri sesuai nilai pensetasi paket jika diubah ubah

            if (count($withdrawAcc) == 0 && count($withdrawWait) == 0) {
                $realEvtProfitUser = $realEvtProfitUser;
            } else {
                if (count($withdrawAcc) == 0) {
                     $realEvtProfitUser = (float)$withdrawWait[0]->nominal;
                } else {
                     $realEvtProfitUser = (float)$withdrawAcc[0]->nominal;
                }
            }

            $realProfit += ($dataPayments[$i]['value'] - $realEvtProfitUser);
        }
        $valueResidual = $totalIncome - ($valueWithdrawAccept+$valueWithdrawInverse+$valueWithdrawWaiting+$realProfit);

        $data = [
            'totalIncome' => $totalIncome,

            'totalWithdraw'=>$totalWithdraw,
            'withdrawAccept'=>$withdrawAccept,
            'withdrawDisaccept'=>$withdrawDisaccept,
            'withdrawWaiting'=>$withdrawWaiting,
            'valueWithdrawAccept'=>$valueWithdrawAccept,
            'valueWithdrawInverse'=>$valueWithdrawInverse,
            'valueWithdrawWaiting'=>$valueWithdrawWaiting,

            'realProfit'=>$realProfit,
            'valueResidual'=>$valueResidual,

            'allPayment'=>$allPayment,
            // 'arrProfit'=>$arrProfit,
            'paymentEvents'=>$dataPayments,
            // 'total'=>$totalNominal,
        ];

        return view('admin.manage-finance', $data);
        // dd($data);

    }
    public function userWithdraw()
    {
        $users = User::all();
        $withdrawals = [];
        for ($i=0; $i < count($users); $i++) { 
            $reject = 0;
            $waiting = 0;
            $accept = 0;

            $withdraw = UserWithdrawalEvent::where('user_id', $users[$i]->id)->get();
            for ($j=0; $j < count($withdraw); $j++) { 
                if($withdraw[$j]->status == 'waiting'){
                    $waiting++;
                }else if($withdraw[$j]->status == 'accepted'){
                    $accept++;
                }else if($withdraw[$j]->status == 'rejected'){
                    $reject++;
                }
            }
            array_push($withdrawals, ['rejected'=>$reject, 'accepted'=>$accept, 'waiting'=>$waiting]);
        }

        return view('admin.user-withdrawals',[
            'users' => User::paginate(10),
            'data' => $withdrawals,
            'pageMain' => true,
        ]);
    }
    public function withdrawReport()
    {
        $withdrawals = UserWithdrawalEvent::orderBy('created_at','DESC');
        return view('admin.withdrawals',[
            'pageMain' => true,
            'withdrawals' => $withdrawals->paginate(10),
        ]);
    }
    public function withdrawReportSpecial($userID)
    {
        $withdrawals = UserWithdrawalEvent::where('user_id', $userID)->orderBy('created_at','DESC');
        return view('admin.withdrawals',[
            'withdrawals' => $withdrawals->paginate(10),
        ]);
    }
    public function organizationType() {
        $types = OrganizationType::orderBy('name', 'ASC')->get();
        foreach ($types as $type) {
            $type->count = Organization::where('type', 'LIKE', '%'.$type->name.'%')->get('id')->count();
        }

        return view('admin.organization_types', [
            'types' => $types
        ]);
    }
    public function packagesSelling()
    {
        $packagesPayment = PackagePayment::all();
        $paid = 0;
        $unpaid = 0;
        for($i=0; $i<count($packagesPayment); $i++){
            if($packagesPayment[$i]->status == 1){
                $paid += $packagesPayment[$i]->nominal;
            }else{
                $unpaid += $packagesPayment[$i]->nominal;
            }
        }

        $newIncome = PackagePayment::where('status',1)->orderBy('id','DESC')->first();
        $newFailIncome = PackagePayment::where('status',0)->orderBy('id','DESC')->first();

        return view('admin.package_selling',[
            'package_payments' => $packagesPayment,
            'pkg_paid' => $paid,
            'count_pkg_all' => count($packagesPayment),
            'pkg_failed' => $unpaid,
            'new_income' => $newIncome,
            'new_fail_in' => $newFailIncome,
        ]);
    }
    public function manageUserPkg()
    {
        $allUser = User::all();

        return view('admin.package_users',['usersData' => $allUser]);
    }
    public function packages()
    {
        $packages = PackagePricing::where('deleted',0)->get();

        return view('admin.packages',['packages'=>$packages]);
    }
    public function frontBanners()
    {
        $fBanners = FrontBanner::orderBy('priority','DESC')->get();
        $events = Event::where('deleted',0)->where('is_publish',1)->get();

        return view('admin.frontBanner',['banners' => $fBanners, 'events' => $events]);
    }
    // --------------------------------------------------------------

}

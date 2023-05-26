<?php

namespace App\Http\Controllers;

use App\Mail\BankAccountVerification;
use App\Mail\WithdrawNotification;
use Illuminate\Http\Request;
use App\Models\BillingAccount;
use App\Models\UserWithdrawalEvent;
use App\Models\Event;
use App\Models\Organization;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use DateTime;
use DateTimeZone;

class WithdrawController extends Controller
{
    // ----------- Cara alternatif cek form input wajib diisi dengan custom redirect messsage ---------------
    // if(!isset($request->event_select) || !isset($request->account)){
    //     return redirect()->back()->with([
    //         'gagal' => 'Semua form wajib diisi.',
    //         'from' => 'account-bank-tab'
    //     ]);
    // }
    // -------------------------------------------------------------------------------------------------------

    //POST data Rekening bank user
    public function storeBankAccount(Request $request)
    {
        // dd($request);
        $myData = UserController::me();
        // $rules = [
        //     'bankName' => 'required|max:212',
        //     'account' => 'required|max:212'
        // ];
        // $msgRules = [
        //     'required' => 'Semua kolom input data wajib diisi',
        //     'max' => 'Semua inputan data maksimal 212 karakter'
        // ];

        // $this->validate($request, $rules, $msgRules);
        if(!isset($request->bankName) || !isset($request->account)){
            return redirect()->back()->with([
                'gagal' => 'Semua form wajib diisi.',
                'from' => 'account-bank-tab'
            ]);
        }else if(strlen($request->bankName) > 212 || strlen($request->account) > 212){
            return redirect()->back()->with([
                'gagal' => 'Batasan karakter semua form adalah 212 karakter',
                'from' => 'account-bank-tab'
            ]);
        }

        $random = new RandomStrController();
        // dd($random->get());
        $accessCode = $random->get() ;
        $saveData = BillingAccount::create([
            'user_id' => $myData->id,
            'bank_name' => htmlspecialchars($request->bankName),
            'account_number' => htmlspecialchars($request->account),
            'access_code' => $accessCode,
            'status' => 0,
        ]);

        $links = [];
        $links[0] = route('user.bankAccountVerification', [base64_encode($myData->email), base64_encode(htmlspecialchars($request->account)), base64_encode(bcrypt($accessCode)), 'verify']);
        $links[1] = route('user.bankAccountVerification', [base64_encode($myData->email), base64_encode(htmlspecialchars($request->account)), base64_encode(bcrypt($accessCode)), 'delete']);

        // Kirim email verifikasi no rekening
        Mail::to($myData->email)->send(new BankAccountVerification($myData->name, htmlspecialchars($request->account), htmlspecialchars($request->bankName), $links));

        return redirect()->back()->with([
            'berhasil' => 'No Rekening berhasil ditambahkan. Cek e-mailmu untuk verifikasi',
            'from' => 'account-bank-tab'
        ]);
    }

    // Delete data rekening
    public function delBankAccount(Request $request)
    {
        if(!isset($request->idAccount)){
            return redirect()->back()->with([
                'from' => 'account-bank-tab'
            ]);
        }
        $id = $request->idAccount;
        BillingAccount::where('id', $id)->delete();
        return redirect()->back()->with([
            'berhasil' => 'No Rekening berhasil dihapus',
            'from' => 'account-bank-tab'
        ]);
    }

    // Verifikasi data Rekening dengan link
    
    function verifyBankAccount($email, $accountNumber, $accessCode, $command)
    {
        //Rule pengecekan 1. Email decode ke string 2. Cek Hash Code accessCode & rekening
        $myData = UserController::me();
        $mail = base64_decode($email);
        $accountBank = base64_decode($accountNumber);
        $accessCode = base64_decode($accessCode);
        // dd($email);
        if(count(User::where('email', $mail)->get()) == 0){
            return abort('403');
        }
       
        $user = User::where('email', $mail)->first();
        $billAccounts = $user->bankAccounts;

        for($i=0; $i<count($billAccounts); $i++){
            $accessCodeVal = $billAccounts[$i]->access_code;
            if(Hash::check($accessCodeVal, $accessCode) && $billAccounts[$i]->account_number == $accountBank){
                if($command == 'verify'){
                    // dd(BillingAccount::where('id', $billAccounts[$i]->id));
                    BillingAccount::where('id', $billAccounts[$i]->id)->update([
                        'status' => 1,
                    ]);
                }else if($command == 'delete'){
                    BillingAccount::where('id', $billAccounts[$i]->id)->delete();
                }
                $i = count($billAccounts);
            }
        }

        if($myData){
            return redirect()->route('user.myorganization');
        }else{
            return redirect()->route('user.loginPage', ['redirect_to' => 'user.myorganization']);
        }
    }

    // POST data penarikan dana
    public function storeWithdraw($organizationID, Request $request)
    {
        $timeZone = new DateTimeZone('Asia/Jakarta');
        $now = new DateTime();
        
        $myData = UserController::me();
        // $rules = [
        //     'event_select' => 'required',
        //     'account' => 'required'
        // ];
        // $msgRules = [ 'required' => 'Semua kolom wajib diisi'];
        // Validator::make($request);
        // dd($this->validate($request, $rules, $msgRules));

        // Validasi dta input wajib diisi semua dengan redirect back custom message message
        if(!isset($request->event_select) || !isset($request->account)){
            return redirect()->back()->with([
                'gagal' => 'Semua form wajib diisi.',
                'from' => 'withdrawals-tab'
            ]);
        }

        $eventIDs = $request->event_select;
        $accountID = $request->account;

        $organizatioController =  new OrganizationController();

        $errors = [];
        $results = [];
        $mailMsg = [];
        // Cek no rekening berstatus active
        $accountBank = BillingAccount::where('id', $accountID)->where('user_id', $myData->id)->get();
        if(count($accountBank) == 0){
            return redirect()->back()->with([
                'gagal' => 'No. Rekening tidak ditemukan / tidak aktif',
                'from' => 'withdrawals-tab'
            ]);
        }else{
            if($accountBank[0]->status == 0){
                return redirect()->back()->with([
                    'gagal' => 'No. Rekening tidak ditemukan / tidak aktif',
                    'from' => 'withdrawals-tab'
                ]);
            }
        }

        // Cek event ID benar millik user ?
        for($i=0; $i<count($eventIDs); $i++){
            $eventQuery = Event::where('id', $eventIDs[$i])->where('organizer_id', $organizationID);
            $event = $eventQuery->get();
            // $eventQuery->update([
            //     'has_withdrawn' => 1
            // ]);

            if(count($event) == 0){
                // return redirect()->back()->with([
                //     'gagal' => 'Event yang kamu pilih tidak ditemukan',
                //     'from' => 'account-bank-tab'
                // ]);
                // Simpan data errors
                array_push($errors, $eventIDs[$i]);
            }else{
                // Get Event by eventID
                $endEvent = new DateTime($event[0]->end_date . ' ' . $event[0]->end_time, new DateTimeZone('Asia/Jakarta'));
                // Cek apakah event sudah boleh ditarik pendapatannya ?
                if($now > $endEvent && ((count(
                    UserWithdrawalEvent::where('event_id', $event[0]->id)->where('status', 'accepted')->get()
                ) == 0 && count(
                    UserWithdrawalEvent::where('event_id', $event[0]->id)->where('status', 'waiting')->get()
                ) == 0))){
                    //Get data nominal
                    $nominal = $organizatioController->getPaymentsEvent($event)[1];
                    //Mengumpukan data untuk disimpan
                    // $nominal = (float)$nominal - ((float)$nominal * config('agendakota')['profit']);
                    // ------- Potongan dari config 2.5% diubah menjadi sesuai config package user ----------
                    $nominal = (float)$nominal - ((float)$nominal * $myData->package->ticket_commission);
                    if($nominal > (config('agendakota')['min_transfer']+config('agendakota')['profit+'])){
                        $nominal = $nominal-config('agendakota')['profit+'];
                    }
                    array_push($results, [
                        'user_id' => $myData->id,
                        'organization_id' => $organizationID,
                        'event_id' => $eventIDs[$i],
                        'account_id' => $accountID,
                        'bank_name' => $accountBank[0]->bank_name,
                        'account_number' => $accountBank[0]->account_number,
                        'nominal' => $nominal,
                        'status' => 'waiting'
                    ]);
                    // Mengumpulkan data untuk dikirim email
                    array_push($mailMsg, [
                        'event' => $event[0]->name,
                        'account' => $accountBank[0]->account_number,
                        'nominal' => $nominal,
                    ]);
                }else{
                    array_push($errors, $eventIDs[$i]);
                }
            }
        }
        // Simpan withdraw ke database
        for($i=0; $i<count($results); $i++){
            // dd($results[$i]);
            UserWithdrawalEvent::create($results[$i]);
        }
        // Kirim email notofikasi ke admin config('agendakota')['email-operator']
       
        if(count($errors) == count($eventIDs)){
            return redirect()->back()->with([
                'gagal' => 'Withdrawal gagal diajukan semuanya, data input tidak sesuai',
                'from' => 'withdrawals-tab'
            ]);
        }else if(count($errors) > 0){
            Mail::to(config('agendakota')['email_operator'])->send(new WithdrawNotification($myData->name, 'mengajukan',$mailMsg));
            return redirect()->back()->with([
                'gagal' => 'Withdrawal gagal diajukan beberapa, data input tidak sesuai, silahkan tunggu maksimal 24 jam',
                'from' => 'withdrawals-tab'
            ]);
        }else{
            Mail::to(config('agendakota')['email_operator'])->send(new WithdrawNotification($myData->name, 'mengajukan',$mailMsg));
            return redirect()->back()->with([
                'berhasil' => 'Withdrawal berhasil diajukan, silahkan tunggu maksimal 24 jam',
                'from' => 'withdrawals-tab'
            ]);
        }
    }
    public function delWithdraw($organizationID, Request $request)
    {
        $myData = UserController::me();
        if(!isset($request->idWithdraw)){
            return redirect()->back()->with([
                'from' => 'account-bank-tab'
            ]);
        }
        $idWithdrawal = $request->idWithdraw;
        $objWithdrawal = UserWithdrawalEvent::where('id', $idWithdrawal);

        $withdrawData = $objWithdrawal->first();

        if(!isset($withdrawData)){
            return redirect()->back()->with([
                'gagal' => 'Withdrawal gagal dibatalkan (ID tidak ditemukan)',
                'from' => 'withdrawals-tab'
            ]);
        }

        if($withdrawData->status != 'waiting'){
            return redirect()->back()->with([
                'gagal' => 'Withdrawal gagal dibatalkan',
                'from' => 'withdrawals-tab'
            ]);
        }

        Mail::to(config('agendakota')['email_operator'])->send(new WithdrawNotification($myData->name, 'membatalkan',[[
            'event' => $withdrawData->event->name,
            'account' => $withdrawData->account_number,
            'nominal' => $withdrawData->nominal,
        ]]));
        $objWithdrawal->delete();
        return redirect()->back()->with([
            'berhasil' => 'Withdrawal berhasil dibatalkan',
            'from' => 'withdrawals-tab'
        ]);
    }
    // Hak akses khusus admin untuk verifikasi withdrawa; oleh user (Setujui atau Tolak)
    public function adminVerifyWithdraw(Request $request)
    {
        if(!isset($request->command) || !isset($request->id)){
            return redirect()->back()->with([
                'from' => 'account-bank-tab'
            ]);
        }
        $command = $request->command;
        $withdrawID = $request->id;

        $objWithdrawal = UserWithdrawalEvent::where('id', $withdrawID);
        if($command == '1'){
            $objWithdrawal->update([
                'status' => 'accepted'
            ]);
        }else if($command == '0'){
            $objWithdrawal->update([
                'status' => 'rejected'
            ]);
        }

        return redirect()->back()->with([
            'from' => 'account-bank-tab',
            'berhasil' => 'Pengajuan telah diverifikasi'
        ]);
    }

    // Get data withdraw untuk view detail
    public function seeWithdraw($organizationID, $withdrawID)
    {   
        $myData = UserController::me();
        $withdraw = UserWithdrawalEvent::where('id', $withdrawID)->first();

        $totalInvite = UserController::getNotifyInvitation($myData->id);

        $organizatioController =  new OrganizationController();
        $nominal = $organizatioController->getPaymentsEvent(Event::where('organizer_id', $organizationID)->get())[1];

        return view('user.organization.withdraw-detail',[
            'myData' => $myData,
            'organizationID' => $organizationID,
            'organization' => Organization::where('id', $organizationID)->first(),
            'totalInvite' => $totalInvite,
            'allSaldo' => $nominal,
            'eventID' => 0,
            'withdraw' => $withdraw,
        ]);
    }
}

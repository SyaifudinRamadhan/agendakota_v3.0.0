<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;

class TicketController extends Controller
{
    public static function get($filter = NULL) {
        if ($filter == NULL) {
            return new Ticket;
        }
        return Ticket::where($filter);
    }
    public function store($organizationID, $eventID, Request $request) {
        // $ticket_id = self::get()->orderByDesc('id')->limit(1)->get('id');
        $validateRule = [
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'start_date' => 'required',
            'end_date' => 'required',
            'session_id' => 'required',
            'description' => 'required'
            // 'end_session_id' => 'required'
        ];

        $validateMsg = [
            'required' => ':Attribute wajib diisi',
            'start_date.required' => 'Tanggal mulai penjualan harus diisi',
            'end_date.required' => 'Tanggal akhir penjualan harus diisi',
            'price.numeric' => 'Kolom harga harus berupa angka',
            'quantity.numeric' => 'Kolom jumlah ticket harus berupa angka',
            // 'unique' => 'Kolom sesi ID-nya sudah pernah mendapatkan ticket'
        ];

        $validateData = $this->validate($request, $validateRule, $validateMsg);

        //Duplicate ticket in session check
        $startSession = $request->session_id;
        // $endSession = $request->end_session_id;

        $event = Event::where('id',$eventID)->first();

        // Menambahkan ketegori bayar (untuk penambahan fitur bayar suka suka)
        $typePrice = 0;
        if($request->type_price == 'gratis'){
            $typePrice = 0;
        }else if($request->type_price == 'Berbayar'){
            $typePrice = 1;
            if($request->price < config('agendakota')["min_transfer"]){
                return redirect()->back()->with('gagal', 'Minimal harga ticket 10.000');
            }
        }else if($request->type_price == 'suka-suka'){
            $typePrice = 2;
            if($request->price < config('agendakota')["min_transfer"]){
                return redirect()->back()->with('gagal', 'Minimal harga ticket 10.000');
            }
        }else{
            return redirect()->back()->with('gagal', 'Jenis Ticket tidak tersedia');
        }

        // Izinkan multiple ticket untuk event offline / hybrid dan check duplikasi jika event online
        // if($event->execution_type == 'online'){
        //     $doubleCheck = Ticket::where('session_id',$startSession)->get();
        //     if(count($doubleCheck) > 0){
        //         return redirect()->route('organization.event.tickets', [$organizationID, $eventID])->with('gagal', 'Ticket pada timeline session yang dipilih sudah ada');
        //     }
        // }
        
        $saveData = Ticket::create([
            'session_id' => $request->session_id,
            // 'end_session_id' => $request->end_session_id,
            // 'end_session_id' => $request->session_id,
            'name' => $request->name,
            'description' => $request->description,
            'type_price' => $typePrice,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'start_quantity' => $request->quantity,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        $update = Event::where('id', $eventID)->update([
            'is_publish' => 1
        ]);
        return redirect()->route('organization.event.tickets', [$organizationID, $eventID]);
    }
    public function update($organizationID, $eventID, Request $request) {
        $id = $request->ticket_id;

        $validateData = $this->validate($request, [
            'session_id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'start_date' => 'required',
            'end_date' => 'required',
            'description' => 'required'
        ]);

        // Menambahkan ketegori bayar (untuk penambahan fitur bayar suka suka)
        $typePrice = 0;
        if($request->type_price == 'gratis'){
            $typePrice = 0;
        }else if($request->type_price == 'Berbayar'){
            $typePrice = 1;
            if($request->price < config('agendakota')["min_transfer"]){
                return redirect()->back()->with('gagal', 'Minimal harga ticket 10.000');
            }
        }else if($request->type_price == 'suka-suka'){
            $typePrice = 2;
            if($request->price < config('agendakota')["min_transfer"]){
                return redirect()->back()->with('gagal', 'Minimal harga ticket 10.000');
            }
        }else{
            return redirect()->back()->with('gagal', 'Jenis Ticket tidak tersedia');
        }

        $updateData = Ticket::where('id', $id)->update([
            'session_id' => $request->session_id,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'type_price' => $typePrice,
            'quantity' => $request->quantity,
            'start_quantity' => $request->quantity,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        return redirect()->route('organization.event.tickets', [$organizationID, $eventID]);
    }
    public function delete($organizationID, $eventID, $ticketID) {
        // ------ Metode lama dengan real delete ------------------------------------------
        // $deleteData = Ticket::where('id', $ticketID)->delete();
        // --------------------------------------------------------------------------------

        // ---------------- Metode baru dengan soft delete --------------------------------
        $deleteData = Ticket::where('id', $ticketID)->update(['deleted'=>1]);
        return redirect()->route('organization.event.tickets', [$organizationID, $eventID]);
    }
}

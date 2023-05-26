<?php

namespace App\Http\Controllers\Api;

use Str;
use Log;
use Xendit\Xendit as Xendit;
use App\Http\Controllers\Controller;
use App\Models\PackagePayment;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketCart;
use App\Models\TicketPayment;
use App\Models\TicketPurchase;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Payment;

class TicketController extends Controller
{
    public function checkout(Request $request) {
        $user = UserController::get($request->token)->first();
        $ticketsRaw = $request->tickets;
        $externalID = "AK_TICK_".Str::random(32);
        $secretKey = env('XENDIT_MODE') == 'sandbox' ? env('XENDIT_SECRET_KEY_SANDBOX') : env('XENDIT_SECRET_KEY');

        $totalPayment = 0;
        
        // calculating total
        $purchasePayload = [];
        foreach ($ticketsRaw as $tick) {
            $ticket = Ticket::where('id', $tick['id'])->first();
            $price = $tick['quantity'] * $ticket->price;
            $totalPayment += $price;

            foreach ($tick['give_to'] as $giveTo) {
                array_push($purchasePayload, [
                    'ticket_id' => $ticket->id,
                    'ticket_price' => $ticket->price,
                    'user_email' => $giveTo,
                ]);
            }
        }

        if ($totalPayment > 0) {
            // invoice 
            Xendit::setApiKey($secretKey);
            $createInvoice = \Xendit\Invoice::create([
                'external_id' => $externalID,
                'payer_email' => $user->email,
                'description' => "Payment for Agendakota Ticket",
                'amount' => $totalPayment,
                'customer' => [
                    'given_names' => $user->name,
                    'email' => $user->email,
                ],
                'success_redirect_url' => "https://agendakota.id/payment-success/ticket",
            ]);
        } else {
            $createInvoice['invoice_url'] = null;
        }

        $createPayment = TicketPayment::create([
            'user_id' => $user->id,
            'external_id' => $externalID,
            'total' => $totalPayment,
            'grand_total' => $totalPayment,
            'status' => 0,
            'has_withdrawn' => 0,
            'payment_link' => $createInvoice['invoice_url']
        ]);

        foreach ($purchasePayload as $purchase) {
            $ticketCode = strtoupper(Str::random(6));
            $user = User::where('email', $purchase['user_email'])->first();
            if ($user == "") {
                $name = explode("@", $purchase['user_email'])[0];
                $user = User::create([
                    'name' => $name,
                    'email' => $purchase['user_email'],
                    'password' => bcrypt($purchase['user_email']),
                    'photo' => 'default',
                    'is_active' => 1,
                    'pkg_id' => 1,
                    'pkg_status' => 1
                ]);

                $createPackagePurchase = PackagePayment::create([
                    'user_id' => $user->id,
                    'pkg_id' => 1,
                    'order_id' => Str::random(12),
                    'token_trx' => '-',
                    'status' => 1,
                    'nominal' => 0,
                ]);
            }
            $createPurchase = TicketPurchase::create([
                'code' => $ticketCode,
                'event_id' => $request->event_id,
                'ticket_id' => $purchase['ticket_id'],
                'send_from' => $user->id,
                'user_id' => $user->id,
                'payment_id' => $createPayment->id,
                'price' => $purchase['ticket_price'],
                'checked_in' => 0,
            ]);
        }

        return response()->json([
            'status' => 200,
            'user' => $user,
            'payment' => $createPayment
        ]);
    }
    public function buy(Request $request) {
        $token = $request->token;
        $q = UserController::get([['token', $token]]);
        $user = $q->first();
        
        $saveData = TicketCart::create([
            'user_id' => $user->id,
            'ticket_id' => $request->ticket_id,
            'status' => "waiting"
        ]);

        return response()->json([
            'data' => $saveData,
            'status' => 200
        ]);
    }
    public function invoiceCallback(Request $request) {
        $externalID = $request->external_id;
        $extIDs = explode("_", $externalID);
        if ($extIDs[1] == "TICK") {
            $paymentQuery = TicketPayment::where('external_id', $externalID);
            $payment = $paymentQuery->first();
            $paymentQuery->update(['status' => 1]);
        }

        return response()->json([
            'text' => "Invoice received from " . $request->external_id
        ]);
    }
}

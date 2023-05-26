<?php

namespace App\Http\Controllers\Api;

use Str;
use App\Models\BillingAccount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function storeBank(Request $request) {
        $user = UserController::get($request->token)->first();
        $saveData = BillingAccount::create([
            'user_id' => $user->id,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'access_code' => Str::random(10),
            'status' => 1
        ]);

        return response()->json([
            'message' => "OK"
        ]);
    }
    public function updateBank(Request $request) {
        $user = UserController::get($request->token)->first();
        $data = BillingAccount::where('id', $request->id);
        $updateData = $data->update([
            'account_number' => $request->account_number
        ]);

        return response()->json([
            'message' => "OK"
        ]);
    }
    public function deleteBank(Request $request) {
        $data = BillingAccount::where('id', $request->id);
        $deleteData = $data->delete();

        return response()->json([
            'message' => "OK"
        ]);
    }
}

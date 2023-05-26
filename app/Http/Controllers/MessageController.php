<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use App\Models\Message;
use App\Models\Connection;
use App\Models\Purchase;
use Auth;
use DB;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public static function myId()
    {
        return Auth::guard('user')->id();
    }

    public function lastMessage($id)
    {
        $myId = self::myId();
        $latest = Message::where([['sender', $id],['receiver', $myId]])->orWhere([['receiver', $id],['sender', $myId]])->latest('id')->first();
        
        if ($latest == null) {
            return json_encode(null);
        } else {
            return json_encode($latest);
        }
    }

    public function AllMessage($id)
    {
        $myId = self::myId();
        $all = Message::where([['sender', $id],['receiver', $myId]])->orWhere([['receiver', $id],['sender', $myId]])->get();
        
        if ($all == null) {
            return json_encode(null);
        } else {
            return json_encode($all);
        }
    }

    public function AllMessageGroup($groupID)
    {
        $myId = self::myId();
        $purchase = Purchase::where('id', $groupID)->first();
        $allObj = Message::where('event_group', $purchase->events->id)->get();
        $all = [];
        for($i=0; $i<count($allObj); $i++){
            $tmp = [];
            $tmp['id'] = $allObj[$i]->id;
            $tmp['sender'] = $allObj[$i]->sender;
            $tmp['receiver'] = $allObj[$i]->receiver;
            $tmp['event_group'] = $allObj[$i]->event_group;
            $tmp['message'] = $allObj[$i]->message;
            $tmp['created_at'] = $allObj[$i]->created_at;
            $tmp['updated_at'] = $allObj[$i]->updated_at;
            $tmp['sender_mail'] = User::where('id', $allObj[$i]->sender)->first()->name;
            $tmp['photo'] = (User::where('id', $allObj[$i]->sender)->first()->photo == 'default' ? asset('images/profile-user.png') : asset('storage/profile_photos/' .User::where('id', $allObj[$i]->sender)->first()->photo));
            $all[$i] = $tmp;
        }
        // dd(json_encode($all), json_encode($allObj));

        if($all == null){
            return json_encode(null);
        }else{
            return json_encode($all);
        }
    }

    public function AllMessageGroup2($groupID)
    {
        $myId = self::myId();
       
        $allObj = Message::where('event_group', $groupID)->get();
        $all = [];
        for($i=0; $i<count($allObj); $i++){
            $tmp = [];
            $tmp['id'] = $allObj[$i]->id;
            $tmp['sender'] = $allObj[$i]->sender;
            $tmp['receiver'] = $allObj[$i]->receiver;
            $tmp['event_group'] = $allObj[$i]->event_group;
            $tmp['message'] = $allObj[$i]->message;
            $tmp['created_at'] = $allObj[$i]->created_at;
            $tmp['updated_at'] = $allObj[$i]->updated_at;
            $tmp['sender_mail'] = User::where('id', $allObj[$i]->sender)->first()->name;
            $tmp['photo'] = (User::where('id', $allObj[$i]->sender)->first()->photo == 'default' ? asset('images/profile-user.png') : asset('storage/profile_photos/' .User::where('id', $allObj[$i]->sender)->first()->photo));
            $all[$i] = $tmp;
        }
        // dd(json_encode($all), json_encode($allObj));

        if($all == null){
            return json_encode(null);
        }else{
            return json_encode($all);
        }
    }

    public function SendMessage(Request $request)
    {
        $myId = self::myId();

        Message::create([
            'id' => null,
            'sender' => $myId,
            'receiver' => $request->input('receiver'),
            'message' => $request->input('message'),
        ]);

        return response()->json(
            [
                'success' => true,
                'message' => 'Data inserted successfully'
            ]
        );
    }

    public function sendMessageGroup(Request $request)
    {
        $myId = self::myId();

        Message::create([
            'id' => null,
            'sender' => $myId,
            'receiver' => 0,
            'event_group' => $request->input('groupID'),
            'message' => $request->input('message'),
        ]);

        return response()->json(
            [
                'success' => true,
                'message' => 'Data inserted successfully'
            ]
        );
    }

    public function searchUser(Request $request)
    {
        if ($request->has('q')) {
            $q = $request->q;
            $data = DB::table('users')->where('name', 'LIKE', '%'.$q.'%')->orWhere('email', 'LIKE', '%'.$q.'%')->get();
            return response()->json($data);
        }
    }
}

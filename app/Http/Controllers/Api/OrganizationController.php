<?php

namespace App\Http\Controllers\Api;

use Str;
use Image;
use Mail;
use Log;
use Carbon\Carbon;
use App\Models\Organization;
use App\Models\OrganizationTeam as Team;
use App\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;

use App\Mail\InviteMembers;
use App\Models\User;
use App\Models\UserWithdrawalEvent;

class OrganizationController extends Controller
{
    public function profile($id) {
        $data = Organization::where('id', $id)->first();
        return response()->json([
            'data' => $data
        ]);
    }
    public function create(Request $request) {
        if ($request->token !== null || $request->token !== "null") {
            $user = UserController::get($request->token)->first();
        } else {
            $user = UserController::getByID($request->user_id)->first();
        }

        $eventName = $request->event_name;
        if ($eventName == "") {
            $organizerDescription = "Event Organizer oleh " . $user->name;
        } else {
            $organizerDescription = "Kami menyelenggarakan event " . $eventName;
        }

        $timeSlug = new DateTime();
        $slug = Str::slug($request->name)."-".$timeSlug->format('d-m-y_H-i-s-u');

        $logoFileName = "";
        if ($request->hasFile('logo')) {
            $filePath = public_path('storage/organization_logo');
            $logo = $request->file('logo');
            $logoFileName = $timeSlug->format('d-m-y_H-i-s-u')."_".$logo->getClientOriginalName();
            // $logo->storeAs('public/organization_logo', $logoFileName);
            $img = Image::make($logo->path());
            $img->resize(200, 200)->save($filePath.'/'.$logoFileName);
        }
        
        $saveData = Organization::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $organizerDescription,
            'logo' => $logoFileName,
            'type' => "Lainnya"
        ]);

        return response()->json([
            'status' => 200,
            'organizer' => $saveData
        ]);
    }
    public function update($id, Request $request) {
        $toUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,
            'interest' => $request->interest,
        ];

        $data = Organization::where('id', $id);
        $updateData = $data->update($toUpdate);
        $organizer = $data->first();

        return response()->json([
            'message' => "Berhasil menyimpan perubahan",
            'organizer' => $organizer
        ]);
    }
    public function event(Request $request) {
        $now = Carbon::now();
        
        $recent = Event::where([
            ['organizer_id', $request->organizer_id],
            ['end_date', '>=', $now->format('Y-m-d')]
        ])->orderBy('created_at', 'ASC')->with('purchase')->get();

        $past = Event::where([
            ['organizer_id', $request->organizer_id],
            ['end_date', '<=', $now->format('Y-m-d')]
        ])->orderBy('created_at', 'DESC')->with('purchase')->take(25)->get();

        return response()->json([
            'recent' => $recent,
            'past' => $past,
        ]);
    }
    public function inviteTeam($organizerID, Request $request) {
        $userID = $request->user_id;
        $email = $request->email;
        $organizer = Organization::where('id', $organizerID)->first();

        $user = UserController::getByID($userID)->first();

        $member['organisasiID'] =$organizerID;
        $member['organisasi']= $organizer->name;
        $member['email']= $email;
        $member['link_invite']= route('organization.confirmation-member', [$member['organisasiID'], base64_encode($member['email'])]);
        $member['sender'] = $user->name;
        $member['senderMail'] = $user->email;

        Mail::to($request->email)->send(new InviteMembers($member));

        return response()->json([
            'status' => 200,
        ]);
    }
    public function getTeam($organizerID, Request $request) {
        $teams = Team::where('organization_id', $organizerID)->with('users')->get();
        return response()->json([
            'teams' => $teams
        ]);
    }
    public function removeTeam($organizerID, Request $request) {
        Team::where('id', $request->id)->delete();

        return response()->json(['status' => 200]);
    }

    public function withdraw($organizerID, Request $request) {
        $user = User::where('token', $request->token)
        ->with(['withdrawals' => function ($query) use ($organizerID) {
            $query->where('organization_id', '=', $organizerID);
        }])->first();
        $now = Carbon::now();

        $events = Event::where([
            ['organizer_id', $organizerID],
            ['end_date', '<=', $now->format('Y-m-d')],
            ['has_withdrawn', 0]
        ])->get();
        $eventPayments = \App\Http\Controllers\OrganizationController::getPaymentsEvent($events, true);

        $payments = $eventPayments[0];
        $total_revenue = $eventPayments[1];

        return response()->json([
            'revenue' => $total_revenue,
            'payments' => $payments,
            'user' => $user,
        ]);
    }
}

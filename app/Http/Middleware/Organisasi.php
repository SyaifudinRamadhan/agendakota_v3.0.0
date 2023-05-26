<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Organization;
use App\Models\OrganizationTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Organisasi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $myData = Auth::guard('user')->user();
        $organizationID= $request->route()->parameter('id');
        $organization = Organization::where('id', $organizationID)->get();
        $organizationTeam = OrganizationTeam::where('organization_id', $organizationID)->where('user_id', $myData->id)->get();
        // dd($organization);
        // $user_id = "";
        // $users_invite = [];
        // if(isset($organization)){
        //     foreach ($organization as $organisasi){
        //         $user_id = $organisasi->user_id;
        //     }
        // }

        // if(isset($organizationTeam)){
        //     foreach ($organizationTeam as $organisasi){
        //         array_push($users_invite, $organisasi->user_id);
        //     }
        // }
        // // dd($myData->id, $organizationTeam, $users_invite);
        // foreach($users_invite as $user_invite){
        //     if($myData->id != $user_id){
        //         if($myData->id != $user_invite){
        //             abort(403);
        //         }
        //     }
        // }

        // Cek dahulu apakah organisasinya ada ?
        if(count($organization) == 0){
            return abort('404');
        }else{
            // Cek apakah user yang akses sesuai dengan user id prganisasi ?
            if($organization[0]->user_id != $myData->id){
                if(count($organizationTeam) == 0){
                    return abort('403');
                }   
            }
        }

        return $next($request);
    }
}

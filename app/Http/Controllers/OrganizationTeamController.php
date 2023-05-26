<?php

namespace App\Http\Controllers;

use App\Models\OrganizationTeam;
use Illuminate\Http\Request;

class OrganizationTeamController extends Controller
{
    public static function get($filter = NULL) {
        if ($filter == NULL) {
            return new OrganizationTeam;
        }
        return OrganizationTeam::where($filter);
    }
    public function delete($organizationID, $teamID){
        $team = OrganizationTeam::where('id', $teamID)->first();
        if(!$team){
            return abort('404');
        }
        OrganizationTeam::where('id', $teamID)->delete();
        return redirect()->back()->with('berhasil', ('Akun anggota '.$team->users->email.' telah berhasil dihapus'));
    }
}

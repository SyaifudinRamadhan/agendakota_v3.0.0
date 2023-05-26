<?php 

namespace App\Http\Controllers;

use Str;
use App\Models\Event;
use App\Models\VipLoungeEvent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VipLoungeController extends Controller
{
	public function save($organizationID, $eventID, Request $request){
		//cek ada atau belum
		$vipLounge = VipLoungeEvent::where('session_id', $request->sessionID)->get();
		if(count($vipLounge) == 0){
			//lakukan store
			$dataSave = [
				'session_id' => $request->sessionID,
				'table_count' => $request->tableCount,
				'chair_table' => $request->chairTable,
			];
			VipLoungeEvent::create($dataSave);
		}else{
			//lakukan update
			$dataSave = [
				'table_count' => $request->tableCount,
				'chair_table' => $request->chairTable,
			];
			VipLoungeEvent::where('session_id', $request->sessionID)->update($dataSave);
		}

		return redirect()->route('organization.event.vipLounge',[$organizationID, $eventID])->with('berhasil', 'Data lounge VIP acara sudah diperbarui');
	}
}
?>
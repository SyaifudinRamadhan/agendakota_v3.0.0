<?php 

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;

use App\Models\ReceptionistEvent;
use App\Models\Event;
use App\Models\User;
// use App\Models\Ticket;
// use App\Models\Faq;
// use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReceptionistEventController extends Controller
{
	public function store($organizationID, $eventID, Request $request){
		$validateRule=[
			'receptionist' => 'required',
		];
		$validateMsg = [
			'required' => 'Form pilihan receptionist harus disi',
		];

		$this->validate($request, $validateRule, $validateMsg);

		$inputUser = $request->input('receptionist');
		$messageFinal = 0;
		for ($i=0; $i<count($inputUser); $i++){
			//validasi tidak boleh berganda
			$idUserSelect = $inputUser[$i];
			$doubleCheck = ReceptionistEvent::where('event_id', $eventID)->where('user_id', $idUserSelect)->get();
			if(count($doubleCheck) > 0){
				$messageFinal++;
			}else{
				//SImpan ke database
				$saveData = [
					'event_id' => $eventID,
					'user_id' => $idUserSelect,
				];

				ReceptionistEvent::create($saveData);
			}
		}

		if($messageFinal == 0){
			return redirect()->route('organization.event.receptionist', [$organizationID, $eventID])->with('berhasil', 'Semua user select berhasil di tambahkan sebagai receptionist');
		}else{
			return redirect()->route('organization.event.receptionist', [$organizationID, $eventID])->with('gagal', 'Beberapa user select sudah terdaftar sebagai receptionist');
		}
	}

	public function delete($organizationID, $eventID, Request $request){
		$idUserSelect = $request->ID;

		ReceptionistEvent::where('id', $idUserSelect)->delete();

		return redirect()->route('organization.event.receptionist', [$organizationID, $eventID])->with('berhasil', 'User select berhasil dihapus dari receptionist');
	}
}

 ?>
<?php 

namespace App\Http\Controllers;

use Str;
use App\Models\Event;
use App\Models\LoungeEvent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoungeController extends Controller
{
	public function store($organizationID, $dataArray)
	{
		$saveData = LoungeEvent::create($dataArray);

		return redirect()->route('organization.event.lounge',[$organizationID, $saveData->event_id])->with('berhasil', 'Data lounge acara sudah diperbarui');
	}

	public function update($organizationID, $eventID, $dataArray)
	{
		$updateData = LoungeEvent::where('event_id', $eventID)->update($dataArray);

		return redirect()->route('organization.event.lounge', [$organizationID, $dataArray['event_id']])->with('berhasil', 'Data lounge berhasil di update');
	}

	public function receiveRequest($organizationID, $eventID, Request $request)
	{
		$validateRule = [
			'tableCount' => 'required|numeric',
			'tableChair' => 'required|numeric',
		];

		$validateMsg = [
			'tableChair.required' => 'Kolom jumlah kursi per meja wajib diisi',
			'tableCount.required' => 'Kolom jumlah meja wajib diisi',
			'tableCount.numeric' => 'Kolom jumlah meja harus berupa angka',
			'tableChair.numeric' => 'Kolom jumlah kursi per meja harus angka'
		];

		$this->validate($request, $validateRule, $validateMsg);
		//cek apakah sudah ada isinya
		$loungeData = LoungeEvent::where('event_id', $eventID)->get();

		$dataSave = [
			'event_id' => $eventID,
			'table_count' => $request->tableCount,
			'chair_table' => $request->tableChair
		];

		if(count($loungeData) == 0){
			return $this->store($organizationID ,$dataSave);
		}
		else{
			return $this->update($organizationID, $eventID, $dataSave);
		}
	}
}	

 ?>
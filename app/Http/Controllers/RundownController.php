<?php 

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Event;
use App\Models\Rundown;
use App\Models\SessionSpeaker;
use Illuminate\Http\Request;
use App\Mail\UserInvitation;
use DateTime;
use Illuminate\Support\Facades\Mail;

class RundownController extends Controller
{
	public function store($organizationID, $eventID, Request $request){
		$validateRule = [
			'name' => 'required',
			'start_date' => 'required',
			// 'start_time' => 'required',
			'end_date' => 'required',
			// 'end_time' => 'required',
			'description' => 'required',
		];
		$validateMsg = [
			'required' => 'Kolom :attribute wajib diisi'
		];
		$this->validate($request, $validateRule, $validateMsg);

		// $start_time = $request->start_time.':00';
		// $end_time = $request->end_time;

		$event = Event::where('id', $eventID)->first();
		// dd($request, $start_time, $event->start_time, $end_time, $event->end_time);
		
		#------------------- Metode lama ----------------------------------------------------------------------------------------------------------------------------------------------------
		// if(($request->start_date < $event->start_date || $request->start_date > $event->end_date) || ($request->end_date < $event->start_date || $request->end_date > $event->end_date)){
		// 	return redirect()->back()->with('gagal', 'Tanggal mulai dan akhir tidak boleh melewati interval waktu event '.$event->start_date.' - '.$event->end_date);
		// }

		// if(($start_time < $event->start_time || $start_time >= $event->end_time)){
		// 	if($request->start_date == $event->start_date && $start_time < $event->start_time){
		// 		return redirect()->back()->with('gagal', 'Waktu mulai dan akhir tidak boleh melewati interval waktu event '.$event->start_time.' - '.$event->end_time);
		// 	}else if($request->start_date == $event->end_date && $start_time >= $event->end_time){
		// 		return redirect()->back()->with('gagal', 'Waktu mulai dan akhir tidak boleh melewati interval waktu event '.$event->start_time.' - '.$event->end_time);
		// 	}
		// }

		// if(($end_time <= $event->start_time || $end_time > $event->end_time)){
		// 	if($request->end_date == $event->end_date && $end_time > $event->end_time){
		// 		return redirect()->back()->with('gagal', 'Waktu mulai dan akhir tidak boleh melewati interval waktu event '.$event->start_time.' - '.$event->end_time);
		// 	}else if($request->end_date == $event->start_date && $end_time <= $event->start_time){
		// 		return redirect()->back()->with('gagal', 'Waktu mulai dan akhir tidak boleh melewati interval waktu event '.$event->start_time.' - '.$event->end_time);
		// 	}else if($request->start_date == $request->end_date && $request->end_time <= $request->start_time){
		// 		return redirect()->back()->with('gagal', 'Waktu akhir tidak boleh kurang atau sama dengan waktu mulai di tanggal yang sama');
		// 	}
		// }
		#--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
		#--------------------------- Metode Baru ----------------------------------
		
		$startEvent = new DateTime($event->start_date.' '.$event->start_time);
		$endEvent = new DateTime($event->end_date.' '.$event->end_time);
		$startRundown = new DateTime($request->start_date);
		$endRundown = new DateTime($request->end_date);

		$startDate = $startRundown->format('Y-m-d');
		$startTime = $startRundown->format('H:i:s');
		$endDate = $endRundown->format('Y-m-d');
		$endTime = $endRundown->format('H:i:s');

		if($startRundown < $startEvent || $startRundown > $endEvent){
			return redirect()->back()->with('gagal','Waktu mulai rundown kurang / melebihi waktu event');
		}

		if($endRundown < $startRundown || $endRundown < $startRundown || $endRundown > $endEvent){
			if($endRundown < $startRundown){
				return redirect()->back()->with('gagal','Waktu mulai rundown kurang / melebihi waktu mulai rundown');
			}
			return redirect()->back()->with('gagal','Waktu mulai rundown kurang / melebihi waktu event');
		}

		#--------------------------------------------------------------------------

		$dataSave = [
			'event_id' => $eventID,
			'name' => $request->name,
			'start_date' => $startDate,
			'end_date' => $endDate,
			'start_time' => $startTime,
			'end_time' => $endTime,
			'duration' => (strtotime($endTime)-strtotime($startTime))/3600,
			'description' => $request->description
		];
		// dd($dataSave);
		$participant = 'Pembicara';
		if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow'){
			$participant = 'Pembicara';
		}else{
			$participant = 'Bintang Tamu';
		}
		$isUpdate = $request->update;
		if(isset($isUpdate)){
			Rundown::where('id', $request->id)->update($dataSave);

			$speakersID = $request->speakers;
			
             if(isset($speakersID)){
				// dd($speakersID);
                 $deleteSpeaker = SessionSpeaker::where('rundown_id',$request->id)->delete();
				 $myData = UserController::me();
                foreach ($speakersID as $speakerID) {
                    $saveSpeaker = SessionSpeaker::create([
                        'rundown_id' => $request->id,
                        'speaker_id' => $speakerID
                     ]);
					$speaker = SessionSpeaker::where('id', $saveSpeaker->id)->first()->speaker;
					$invitation['link'] = route('user.loginPage');
					$invitation['sender'] = $myData->name;
					$invitation['email'] = $speaker->email;
					$invitation['participant'] = $participant;
					$invitation['event'] = $event->name;
					$invitation['sender_mail'] = $myData->email;
					$invitation['password'] = 'agendakotaspeaker';

					// dd($invitation,  $myData);
					// dd(new UserInvitation($invitation));

					Mail::to($speaker->email)->send(new UserInvitation($invitation));
                 }
            }
			// Update session
			$sessionStart = Session::where('start_rundown_id', $request->id)->get();
			$sessionEnd = Session::where('end_rundown_id', $request->id)->get();

			if(count($sessionStart)>0){
				for($i=0; $i<count($sessionStart); $i++){
					$dataUpdate = [
						'start_date' => $startDate,
						'start_time' => $startTime,
					];
					Session::where('id', $sessionStart[$i]->id)->update($dataUpdate);
				}
			}
			if(count($sessionEnd)>0){
				for($i=0; $i<count($sessionEnd); $i++){
					$dataUpdate = [
						'end_date' => $endDate,
						'end_time' => $endTime,
					];
					Session::where('id', $sessionEnd[$i]->id)->update($dataUpdate);
				}
			}
		}
		else{
			$save = Rundown::create($dataSave);
			$myData = UserController::me();
			$speakersID = $request->speakers;
			if(isset($speakersID)){
		        foreach ($speakersID as $speakerID) {
		            $saveSpeaker = SessionSpeaker::create([
		                'rundown_id' => $save->id,
		                'speaker_id' => $speakerID
		            ]);
					$speaker = SessionSpeaker::where('id', $saveSpeaker->id)->first()->speaker;
					$invitation['link'] = route('user.loginPage');
					$invitation['sender'] = $myData->name;
					$invitation['email'] = $speaker->email;
					$invitation['participant'] = $participant;
					$invitation['event'] = $event->name;
					$invitation['sender_mail'] = $myData->email;
					$invitation['password'] = 'agendakotaspeaker';

					// dd($invitation,  $myData);
					// dd(new UserInvitation($invitation));

					Mail::to($speaker->email)->send(new UserInvitation($invitation));
		        }
		    }
		}
		return redirect()->back()->with('berhasil', 'Rundown berhasil di tambahkan');
	}
	public function delete($organizationID, $eventID, $idDEl){
		
		// ----- Metode lama full delete -----------
		// Rundown::where('id', $idDEl)->delete();
		// SessionSpeaker::where('rundown_id',$idDEl)->delete();

		// ------ Ubah jadi soft delete -------------
		Rundown::where('id', $idDEl)->update(['deleted'=>1]);
		SessionSpeaker::where('rundown_id',$idDEl)->update(['deleted'=>1]);

		return redirect()->back()->with('berhasil', 'Rundown berhasil dihapus');	
	}
}

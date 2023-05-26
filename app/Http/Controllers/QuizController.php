<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Purchase;
use DateTime;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public static function get($filter = NULL) {
        if ($filter == NULL) {
            return new Quiz;
        }
        return Quiz::where($filter);
    }
    public function store($organizationID, $eventID, Request $request) {
        $validateRule = [
            'session_id' => 'required',
            'name' => 'required'
        ];

        $validateMsg = [
            'session_id.required' => 'Kolom session acara wajib ada yang dipilih',
            'name.required' => 'Kolom nama quiz wajib diisi',
        ];

        $validateData = $this->validate($request, $validateRule, $validateMsg);

        $saveData = Quiz::create([
            'event_id' => $eventID,
            'session_id' => $request->session_id,
            'name' => $request->name,
        ]);
        
        return redirect()->route('organization.event.quiz.questions', [$organizationID, $eventID, $saveData->id]);
    }
    public function update($organizationID, $eventID, $quizID, Request $request) {
        $id = $quizID;

        $validateRule = [
            'session_id' => 'required',
            'name' => 'required'
        ];

        $validateMsg = [
            'session_id.required' => 'Kolom session acara wajib ada yang dipilih',
            'name.required' => 'Kolom nama quiz wajib diisi',
        ];

        $validateData = $this->validate($request, $validateRule, $validateMsg);

        $updateData = Quiz::where('id', $id)->update([
            'session_id' => $request->session_id,
            'name' => $request->name,
        ]);

        return redirect()->route('organization.event.quiz', [$organizationID, $eventID]);
    }
    public function delete($organizationID, $eventID, $id) {
        $deleteData = Quiz::where('id', $id)->delete();

        return redirect()->route('organization.event.quiz', [$organizationID, $eventID]);
    }

    public function questions($organizationID, $eventID, $quizID) {
        $quizQuestions = QuizQuestion::where('quiz_id', $quizID)->paginate(10);
        $myData = UserController::me();
        $event = Event::where('id', $eventID)->first();

        return view('user.organization.event.quiz.question', [
            'myData' => $myData,
            'event' => $event,
            'quiz' => Quiz::where('id', $quizID)->first(),
            'quizQuestions' => $quizQuestions,
            'organizationID' => $organizationID,
            'eventID' => $eventID,
            'isManageEvent' => 1
        ]);
    }
    public function showQuiz($purchaseID)
    {
        # ------------ Lakukan pemeriksaan tentang purchase user ----------------------
        $purchase = Purchase::where('id', $purchaseID)->first();
        $myData = UserController::me();
        $urlMain = $purchase->tickets->session;
        //check ticket statuses
        if ($purchase->payment->pay_state != 'Terbayar' || $purchase->tempFor->share_to != $myData->email) {
            if ($purchase->payment->pay_state != 'Terbayar') {
                return redirect()->route('user.myTickets')->with('pesan', 'Kamu belum membeli Tiket ini');
            } else {
                return redirect()->route('user.myTickets')->with('pesan', 'Ticket ini bukan milikmu');
            }
        }
        #------------------------------------------------------------------------------

        $quiz = Quiz::where('session_id', $urlMain->id)->get();
        if($quiz == null){
            return abort('404');
        }
        
        // Periksa waktu akses quiz => izinkan jika event sudah dimulai saja
        $startEvent = new DateTime($purchase->tickets->session->start_date.' '.$purchase->tickets->session->start_time);
        $now = new DateTime();
        // if($startEvent > $now){
        //     return abort('404');
        // }

        return view('user.myQuiz',[
            'quiz' => $quiz,
            'purchase' => $purchase,
        ]);
    }
    public function showQuizQuestion($purchaseID, $quizID)
    {
        # ------------ Lakukan pemeriksaan tentang purchase user ----------------------
        $purchase = Purchase::where('id', $purchaseID)->first();
        $myData = UserController::me();
        $urlMain = $purchase->tickets->session;
        //check ticket statuses
        if ($purchase->payment->pay_state != 'Terbayar' || $purchase->tempFor->share_to != $myData->email) {
            if ($purchase->payment->pay_state != 'Terbayar') {
                return redirect()->route('user.myTickets')->with('pesan', 'Kamu belum membeli Tiket ini');
            } else {
                return redirect()->route('user.myTickets')->with('pesan', 'Ticket ini bukan milikmu');
            }
        }
        #------------------------------------------------------------------------------

        $quiz = Quiz::where('id', $quizID)->first();
        if($quiz == null){
            if($quizID != null){
                return redirect()->back()->with('gagal','Quiz tidak tersedia');
            }
        }
        
        // Periksa waktu akses quiz => izinkan jika event sudah dimulai saja
        $startEvent = new DateTime($purchase->tickets->session->start_date.' '.$purchase->tickets->session->start_time);
        $now = new DateTime();
        // if($startEvent > $now){
        //     return abort('404');
        // }

        return view('user.quizQuestions',[
            'quiz' => $quiz,
            'purchase' => $purchase,
        ]);
    }
    public function storeResult($purchaseID, $quizID, Request $request)
    {
        # code...
    }
}

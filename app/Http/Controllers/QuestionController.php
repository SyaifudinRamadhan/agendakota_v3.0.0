<?php

namespace App\Http\Controllers;

use App\Models\QuizQuestion as Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store($organizationID, $eventID, $quizID, Request $request) {
        
        $validateRule = [
            'question' => 'required',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'correct_answer' => 'required',
            'point' => 'required|numeric'
        ];

        $validateMsg = [
            'required' => 'Kolom :Attribute wajib diisi',
            'point.numeric' => 'Kolom point wajib berisi angka',
        ];

        $validateData = $this->validate($request, $validateRule, $validateMsg);

        $saveData = Question::create([
            'quiz_id' => $quizID,
            'question' => $request->question,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_answer' => $request->correct_answer,
            'point' => $request->point,
        ]);

        return redirect()->route('organization.event.quiz.questions', [$organizationID, $eventID, $quizID]);
    }
    public function update($organizationID, $eventID, $questionID, Request $request) {
        
        $validateRule = [
            'question' => 'required',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'correct_answer' => 'required',
            'point' => 'required|numeric'
        ];

        $validateMsg = [
            'required' => 'Kolom :Attribute wajib diisi',
            'point.numeric' => 'Kolom point wajib berisi angka',
        ];

        $validateData = $this->validate($request, $validateRule, $validateMsg);

        $id = $request->question_id;

        $updateData = Question::where('id', $id)->update([
            'question' => $request->question,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_answer' => $request->correct_answer,
            'point' => $request->point,
        ]);
    }
    public function delete($organizationID, $eventID, $quizID, $questionID) {
        $deleteData = Question::where('id', $questionID)->delete();

        return redirect()->route('organization.event.quiz.questions', [$organizationID, $eventID, $quizID]);
    }
}

@extends('layouts.user')

@section('title', "Daftar Pertanyaan Quiz ".$quiz->name)

@section('content')
<div class="row">
    <div class="col-md-3">
        <h3>
            <a class="btn btn-primer rounded-5" href="{{ route('organization.event.quiz', [$organizationID, $eventID]) }}" class="teks-hitam">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </h3>
    </div>
    <div class="col-12">
        <h3>
            Daftar Pertanyaan Quiz {{ $quiz->name }}
        </h3>
    </div>
</div>

<button class="ke-kanan primer mb-3" onclick="munculPopup('#addQuestion')">
    <i class="fas fa-plus"></i> Pertanyaan
</button>

<br><br><br>

@include('admin.partials.alert')
<div class="mt-3 float-right">
    {{ $quizQuestions->links() }}
</div>
<div id="dummy-table" class="table-responsive" style="height: 10px">
    <table class="mt-5">
        <thead>
            <tr>
                <th style="min-width: 200px"></th>
                <th style="min-width: 100px"></th>
                <th style="min-width: 100px"></th>
                <th style="min-width: 100px"></th>
                <th style="min-width: 100px"></th>
                <th style="min-width: 100px"></th>
                <th style="min-width: 100px"></th>
                <th style="min-width: 100px"></th>
            </tr>
        </thead>
    </table>
</div>
<div id="real-table" class="table-responsive">
    <table class="mt-5">
        <thead>
            <tr>
                <th style="min-width: 200px">Pertanyaan</th>
                <th style="min-width: 100px">Opsi A</th>
                <th style="min-width: 100px">Opsi B</th>
                <th style="min-width: 100px">Opsi C</th>
                <th style="min-width: 100px">Opsi D</th>
                <th style="min-width: 100px">Jawaban</th>
                <th style="min-width: 100px">Poin</th>
                <th style="min-width: 100px"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quizQuestions as $question)
                <tr>
                    <td>{{ $question->question }}</td>
                    <td>{{ $question->option_a }}</td>
                    <td>{{ $question->option_b }}</td>
                    <td>{{ $question->option_c }}</td>
                    <td>{{ $question->option_d }}</td>
                    <td>
                        @php
                            $answer = $question->correct_answer;
                            $correctAnswer = $question->$answer;
                        @endphp
                        {{ $correctAnswer }}
                    </td>
                    <td>{{ $question->point }}</td>
                    <td>
                        <span class="teks-hijau mr-1 pointer" onclick='edit(<?= json_encode($question) ?>)'>
                            <i class="fas fa-edit"></i>
                        </span>
                        <a 
                            href="{{ route('organization.event.quiz.question.delete', [$organizationID, $eventID, $quiz->id, $question->id]) }}" 
                            onclick="deleteConfirm(event)"
                            class="teks-merah">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="bg"></div>
<div class="popupWrapper" id="addQuestion">
    <div class="popup" style="width: 70%">
        <h3><i class="fas bi bi-x-circle-fill op-5 pr-3 mt-3 ke-kanan pointer" onclick="hilangPopup('#addQuestion')"></i>
        </h3>
        <div class="wrap">
            <h3 class="text-center">Tambah Pertanyaan Baru
            </h3>
            <form action="{{ route('organization.event.quiz.question.store', [$organizationID, $eventID, $quiz->id]) }}" method="POST">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-12">
                        <div class="mt-2">Pertanyaan :</div>
                        <input type="text" class="box no-bg" name="question" required value="{{old('question')}}">
                    </div>
                    <div class="col-md-6">
                        <div class="mt-2">Opsi A :</div>
                        <input type="text" class="box no-bg" name="option_a" required value="{{old('option_a')}}">
                    </div>
                    <div class="col-md-6">
                        <div class="mt-2">Opsi B :</div>
                        <input type="text" class="box no-bg" name="option_b" required value="{{old('option_b')}}">
                    </div>
                    <div class="col-md-6">
                        <div class="mt-2">Opsi C :</div>
                        <input type="text" class="box no-bg" name="option_c" required value="{{old('option_c')}}">
                    </div>
                    <div class="col-md-6">
                        <div class="mt-2">Opsi D :</div>
                        <input type="text" class="box no-bg" name="option_d" required value="{{old('option_d')}}">
                    </div>
                    <div class="col-12">
                        <div class="mt-2">Jawaban benar :</div>
                    </div>
                    <div class="col-3 mt-1">
                        <input type="radio" name="correct_answer" value="option_a" {{old('correct_answer') == 'option_a' ? 'checked' : ''}}> A
                    </div>
                    <div class="col-3 mt-1">
                        <input type="radio" name="correct_answer" value="option_b" {{old('correct_answer') == 'option_b' ? 'checked' : ''}}> B
                    </div>
                    <div class="col-3 mt-1">
                        <input type="radio" name="correct_answer" value="option_c" {{old('correct_answer') == 'option_c' ? 'checked' : ''}}> C
                    </div>
                    <div class="col-3 mt-1">
                        <input type="radio" name="correct_answer" value="option_d" {{old('correct_answer') == 'option_d' ? 'checked' : ''}}> D
                    </div>
                    <div class="col-12">
                        <div class="mt-2">Poin :</div>
                        <input type="number" class="box no-bg" name="point" min="1" required value="{{old('point')}}">
                    </div>
                    <div class="col-12">
                        <button class="lebar-100 mt-3 primer">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="popupWrapper" id="editQuestion">
    <div class="popup" style="width: 70%">
        <h3><i class="fas bi bi-x-circle-fill op-5 pr-3 mt-3 ke-kanan pointer" onclick="hilangPopup('#editQuestion')"></i>
        </h3>
        <div class="wrap">
            <h3>Edit Pertanyaan</i>
            </h3>
            <form action="{{ route('organization.event.quiz.question.update', [$organizationID, $eventID, $quiz->id]) }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="question_id" id="questionID">
                <div class="row">
                    <div class="col-12">
                        <div class="mt-2">Pertanyaan :</div>
                        <input type="text" class="box no-bg" name="question" id="question" required>
                    </div>
                    <div class="col-md-6">
                        <div class="mt-2">Opsi A :</div>
                        <input type="text" class="box no-bg" name="option_a" id="option_a" required>
                    </div>
                    <div class="col-md-6">
                        <div class="mt-2">Opsi B :</div>
                        <input type="text" class="box no-bg" name="option_b" id="option_b" required>
                    </div>
                    <div class="col-md-6">
                        <div class="mt-2">Opsi C :</div>
                        <input type="text" class="box no-bg" name="option_c" id="option_c" required>
                    </div>
                    <div class="col-md-6">
                        <div class="mt-2">Opsi D :</div>
                        <input type="text" class="box no-bg" name="option_d" id="option_d" required>
                    </div>
                    <div class="col-12">
                        <div class="mt-2">Jawaban benar :</div>
                    </div>
                    <div class="col-3 mt-1">
                        <input type="radio" name="correct_answer" value="option_a"> A
                    </div>
                    <div class="col-3 mt-1">
                        <input type="radio" name="correct_answer" value="option_b"> B
                    </div>
                    <div class="col-3 mt-1">
                        <input type="radio" name="correct_answer" value="option_c"> C
                    </div>
                    <div class="col-3 mt-1">
                        <input type="radio" name="correct_answer" value="option_d"> D
                    </div>

                    <div class="col-12">
                        <div class="mt-2">Poin :</div>
                        <input type="number" class="box no-bg" name="point" min="1" id="point" required>
                    </div>
                    <div class="col-12">
                        <button class="lebar-100 mt-3 primer">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="{{ asset('js/user/quizPage.js') }}"></script>
<script>
    const edit = data => {
        // data = JSON.parse(data);
        munculPopup("#editQuestion");

        select("#editQuestion #questionID").value = data.id;
        select("#editQuestion #question").value = data.question;
        select("#editQuestion #option_a").value = data.option_a;
        select("#editQuestion #option_b").value = data.option_b;
        select("#editQuestion #option_c").value = data.option_c;
        select("#editQuestion #option_d").value = data.option_d;
        select(`#editQuestion input[type='radio'][value='${data.correct_answer}']`).checked = true;
        select("#editQuestion #point").value = data.point;
    }
</script>
@endsection
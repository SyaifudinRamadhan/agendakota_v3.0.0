@extends('layouts.user')

@section('title', "Quiz | ".$event->name)

@section('content')
<h1>Quiz
    
</h1>

<button class="ke-kanan pointer primer" onclick="munculPopup('#addQuiz')">
    <i class="fas fa-plus"></i> Tambah Kuis
</button>

@include('admin.partials.alert')
<div class="mt-3">
    {{ $quiz->links() }}
</div>
<div id="dummy-table" class="table-responsive" style="height: 10px">
    <table>
        <thead>
            <tr>
                <th style="min-width: 100px;"></th>
                <th style="min-width: 150px;"></th>
                <th style="min-width: 240px;"></th>
            </tr>
        </thead>
    </table>
</div>
<div id="real-table" class="table-responsive">
    <table>
        <thead>
            <tr>
                <th style="min-width: 100px;">Sesi</th>
                <th style="min-width: 150px;">Nama Kuis</th>
                <th style="min-width: 240px;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quiz as $quiz)
                <tr>
                    <td>{{ $quiz->session->title }}</td>
                    <td>{{ $quiz->name }}</td>
                    <td>
                        <a href="{{ route('organization.event.quiz.questions', [$organizationID, $event->id, $quiz->id]) }}" class="bg-primer rounded p-1 pl-2 pr-2 mr-1">
                            <i class="fas fa-question"></i> &nbsp; Pertanyaan
                        </a>
                        <a id="edit-quiz"
                            class="bg-warning rounded p-1 pl-2 pr-2" onclick="munculPopup('#editQuiz{{ $quiz->id }}')">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a id="del-quiz"
                            href="{{ route('organization.event.quiz.delete', [$organizationID, $event->id, $quiz->id]) }}"
                            class="bg-merah rounded p-1 pl-2 pr-2 ml-2" onclick="deleteConfirm(event)">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                
                {{-- --------- Edit Quiz ------------------------------ --}}
                <div class="bg"></div>
                <div class="popupWrapper" id="editQuiz{{ $quiz->id }}">
                    <div class="lebar-50 popup rounded-5">
                        <h3><i class="fas bi bi-x-circle-fill op-5 pr-3 mt-3 ke-kanan pointer" onclick="hilangPopup('#editQuiz{{ $quiz->id }}')"></i>
                        </h3>
                        <div class="pl-5 pr-5 pt-3 ob-3">
                    
                            <h3 class="text-center">Edit Quiz</h3>
                            <div class="wrap">
                    
                                <form action="{{ route('organization.event.quiz.update', [$organizationID, $event->id, $quiz->id]) }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="mt-2">Session :</div>
                                    <select name="session_id" class="box no-bg">
                                        @foreach ($event->sessions as $session)
                                            <option {{ $session->id == $quiz->session->id ? 'selected' : '' }} value="{{ $session->id }}">{{ $session->title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-2">Judul Quiz :</div>
                                    <input type="text" class="box no-bg" name="name" value="{{ $quiz->name }}" required>
                                    <button class="lebar-100 mt-2 primer">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- -------------------------------------------------- --}}
            @endforeach
        </tbody>
    </table>
</div>

<div class="bg"></div>
<div class="popupWrapper" id="addQuiz">
    <div class="lebar-50 popup rounded-5">
        <h3><i class="fas bi bi-x-circle-fill op-5 pr-3 mt-3 ke-kanan pointer" onclick="hilangPopup('#addQuiz')"></i>
        </h3>
        <div class="pl-5 pr-5 pt-3 ob-3">
    
            <h3 class="text-center">Buat Quiz Baru</h3>
            <div class="wrap">
    
                <form action="{{ route('organization.event.quiz.store', [$organizationID, $event->id]) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="mt-2">Session :</div>
                    <select name="session_id" class="box no-bg">
                        @foreach ($event->sessions as $session)
                            <option value="{{ $session->id }}">{{ $session->title }}</option>
                        @endforeach
                    </select>
                    <div class="mt-2">Judul Quiz :</div>
                    <input type="text" class="box no-bg" name="name" required>
                    <button class="lebar-100 mt-2 primer">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/user/quizPage.js') }}"></script>
@endsection


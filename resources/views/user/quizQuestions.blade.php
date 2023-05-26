<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quiz - Agendakota</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('js/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">


    <!-- Fevicon -->
    <link rel="shortcut icon" href="{{ asset('images/icon-ak.png') }}">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>



    <script src="https://kit.fontawesome.com/bc29c7987f.js" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/eventDetailPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/mainUser.css') }}">

    <style>
        .btn-outline-warning{
            /* background-color: #d8b502; */
            color: #e09300;
            border-color: #e09300;
        }
    </style>

</head>

<body>
    <div class="container">
        <h4 class="mt-4">
            <a href="{{ route('user.quizShow',[$purchase->id]) }}">
                <button class="btn btn-default btn-no-pd bg-primer" style="border: 1px solid #eb597b">
                    <b><i class="fas fa-arrow-left text-light"></i></b>
                </button>
            </a>
            <b>Quiz Questions</b>
        </h4>
        
        <div class="wrap mt-3">
            <div class="row">
                <div class="col-12">
                    <b id="question">
                        << PERTANYAAN ... >>
                    </b>
                </div>
                <div class="col-12 mt-3">
                    <input type="radio" class="btn-check" name="coiche" id="choice-1" value="" autocomplete="off">
                    <label id="choice-11" class="btn btn-outline-danger p-3 rounded-5 w-100" for="choice-1">C-1</label><br>
                </div>
                <div class="col-12 mt-3">
                    <input type="radio" class="btn-check" name="coiche" id="choice-2" value="" autocomplete="off">
                    <label id="choice-21" class="btn btn-outline-primary p-3 rounded-5 w-100" for="choice-2">C-2</label><br>
                </div>
                <div class="col-12 mt-3">
                    <input type="radio" class="btn-check" name="coiche" id="choice-3" autocomplete="off">
                    <label id="choice-31" class="btn btn-outline-warning p-3 rounded-5 w-100" for="choice-3">C-3</label><br>
                </div>
                <div class="col-12 mt-3 mb-2">
                    <input type="radio" class="btn-check" name="coiche" id="choice-4" autocomplete="off">
                    <label id="choice-41" class="btn btn-outline-success p-3 rounded-5 w-100" for="choice-4">C-4</label><br>
                </div>
                <div class="col-12 mt-5">
                    <div class="row">
                        <div class="col-3">
                            <button id="prev-btn" class="btn btn-primer">
                                <i class="fas fa-arrow-left text-light"></i>
                            </button>
                        </div>
                        <div class="col-6">
                            <button id="submit-btn" class="btn btn-danger text-center w-100">
                                Submit
                            </button>
                        </div>
                        <div class="col-3">
                            <button id="next-btn" class="btn btn-primer">
                                <i class="fas fa-arrow-right text-light"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/base.js') }}"></script>
    <script src="{{ asset('js/user/quizController.js') }}"></script>
    <script>
        setupData(<?php echo json_encode($quiz->questions) ?>);
    </script>

</body>

</html>

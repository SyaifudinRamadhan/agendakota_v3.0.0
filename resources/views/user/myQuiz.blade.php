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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">



    <script src="https://kit.fontawesome.com/bc29c7987f.js" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @yield('head.dependencies')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/mainUser.css') }}">

</head>

<body>
    <div class="container">
        <h4 class="mt-4"><b>Main Stage</b></h4>
        <h5 class="mt-3 mb-2"><b>Quiz</b></h5>
        <div class="wrap">
            <div class="row">
                @foreach ($quiz as $item)
                    <a class="col-12" href="{{ route('user.quizQuestions',[$purchase->id,$item->id]) }}">
                        <div class="bg-putih bayangan-5 lebar-100 box-card rounded-5 p-3 text-center" style="border: 1px solid #eb597b">
                            <div class="row">
                                <div class="col-12 text-center pt-4" style="font-size: 22px;
                                font-weight: bold;">
                                    {{ $item->name }}
                                </div>
                                <div class="col-12 text-right teks-primer">
                                    Buka Quiz
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>

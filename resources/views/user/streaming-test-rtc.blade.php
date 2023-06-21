<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Testing WebRTC</title>
    <link rel="stylesheet" href="{{ asset('js/select2.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <!-- Fevicon -->
    <link rel="shortcut icon" href="{{ asset('images/icon-ak.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;600;700&display=swap" rel="stylesheet">

    <script src="https://kit.fontawesome.com/bc29c7987f.js" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @yield('head.dependencies')

    <link rel="stylesheet" href="{{ asset('css/user/newStreamPage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/streamPage.css') }}">
</head>

<body>
    <input type="hidden" id="type-stream" value="{{ $streamType }}">
    <input type="hidden" id="url-server" value="{{ $link }}">
    <input type="hidden" id="room" value="{{ explode('webrtc-video-conference-', $url[0]['link'])[1] }}">
    <input type="hidden" id="name" value="{{ $nama_peserta }}">
    <input type="hidden" id="myData" value="{{ $myData }}">
    <input type="hidden" id="organization" value="{{ $organization }}">
    <input type="hidden" id="user-team" value="{{ $myData->organizationsTeam }}">
    <input type="hidden" id="token" value="{{ $xAccessToken }}">
    <input type="hidden" id="start-time" value="{{ $url[0]->start_date . ' 00:00:00' }}">
    <input type="hidden" id="end-time" value="{{ $url[0]->end_date . ' ' . $url[0]->end_time }}">

    <div id="multiple-conference"></div>

    <div id="stream-blank" class="d-none">
       
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset=utf-8 />
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
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
    <input type="hidden" id="url-stream" value="{{ $link }}">
    <input type="hidden" id="x-access-token" value="{{ $xAccessToken }}">
    <input type="hidden" id="start-time" value="{{ $url[0]->start_date.' '.$url[0]->start_time }}">
    <input type="hidden" id="end-time" value="{{ $url[0]->end_date.' '.$url[0]->end_time }}">
    
    <div id="rtmp-video"></div>

    <div id="stream-blank" class="d-none">
       
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

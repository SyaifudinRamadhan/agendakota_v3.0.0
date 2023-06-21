<!DOCTYPE html>
<html>
<head>
    <meta charset=utf-8 />
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
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

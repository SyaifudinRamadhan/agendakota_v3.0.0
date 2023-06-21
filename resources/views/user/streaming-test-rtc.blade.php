<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Testing WebRTC</title>
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

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>

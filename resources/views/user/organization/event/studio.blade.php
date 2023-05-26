<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Studio Streaming</title>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://kit.fontawesome.com/bc29c7987f.js" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body>

    <!-- React root DOM -->
    <input type="hidden" id="org-id" value="{{$orgId}}">
    <input type="hidden" id="event-id" value="{{$eventId}}">
    <input type="hidden" id="category" value="{{$category}}">
    <input type="hidden" id="breakdown" value="{{$breakdown}}">
    <input type="hidden" id="my-data" value="{{json_encode($myData)}}">
    <input type="hidden" id="session-id" value="{{$sessionId}}">
    <input type="hidden" id="x-access-token" value="{{$xAccessToken}}">
    <input type="hidden" id="password-token" value="{{$passwordToken}}">
    <input type="hidden" id="stream-key" value="{{$streamKey}}">
    <div id="studio">
    </div>

    <!-- React JS -->
    <script src="{{ asset('js/app.js') }}" defer></script>

</body>
</html>
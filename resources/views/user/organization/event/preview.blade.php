<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Preview Document</title>
</head>
<body>
    <object data="{{ asset('storage/event_assets/'.$event->slug.'/event_handouts/'.$handoutvalue)}}" width="100%" height="1000px"></object>
    {{-- <iframe src="{{ asset('storage/event_assets/'.$event->slug.'/event_handouts/'.$handoutvalue)}}"></iframe> --}}
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zoom Meeting</title>
</head>
<body>
    <?php var_dump($url) ?>
    <input type="hidden" value="{{ $id }}" id="meeting_number" name="meeting_number">
    <input type="hidden" value="{{ $password }}" id="meeting_pwd" name="meeting_pwd">
    <input type="hidden" value="0" id="meeting_role" name="meeting_role">
    <input type="hidden" value="{{$email_peserta}}" id="meeting_email" name="meeting_email">
    <input type="hidden" value="en-US" id="meeting_lang" name="meeting_lang">
    <input type="hidden" value="English" id="meeting_china" name="meeting_china">
    <input type="hidden" value="{{$nama_peserta}}" id="display_name" name="display_name">
    <input type="hidden" value="{{$url_leave}}" id="url_leave" name="url_leave">

    <script src="https://source.zoom.us/1.9.0/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/1.9.0/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/1.9.0/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/1.9.0/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/1.9.0/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-1.9.0.min.js"></script>

    <script src="{{asset('js/zoom-js/tool.js')}}"></script>
    <script src="{{asset('js/zoom-js/vconsole.min.js')}}"></script>
    <script src="{{asset('js/zoom-js/meeting.js')}}"></script>
</body>

<script>
     var API_KEY = "5Ktt2Z3fQTyn0wSVVTKpXg";
     var API_SECRET = "S4psQKnlyJWkXEzgP3H9fyEJ5ryft3tPCJlM";
</script>
</html>

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
    <input type="hidden" id="type-stream" value="zoom">
    <input type="hidden" value="{{ count($id) > 0 ? $id[0] : '' }}" id="meeting_number" name="meeting_number">
    <input type="hidden" value="{{ count($password) > 0 ? $password[0] : '' }}" id="meeting_pwd" name="meeting_pwd">
    <input type="hidden" value="0" id="meeting_role" name="meeting_role">
    <input type="hidden" value="{{ $email_peserta }}" id="meeting_email" name="meeting_email">
    <input type="hidden" value="en-US" id="meeting_lang" name="meeting_lang">
    <input type="hidden" value="English" id="meeting_china" name="meeting_china">
    <input type="hidden" value="{{ $nama_peserta }}" id="display_name" name="display_name">
    <input type="hidden" value="{{ $url_leave }}" id="url_leave" name="url_leave">

    <div id="streaming"></div>

    <script>
        var loop = 0;

        function autoChangeZoom(IDs, pwds) {
            var zoomID = $("#meeting_number");
            var passZoom = $("#meeting_pwd");
            if (zoomID.attr("value") == "") {
                zoomID.attr("value", IDs[0]);
                passZoom.attr("value", pwds[0]);
            }
        }


        setInterval(() => {
            var dataIDs = JSON.parse('<?php echo json_encode($id); ?>');
            var passwords = JSON.parse('<?php echo json_encode($password); ?>');

            autoChangeZoom(dataIDs, passwords);
        }, 500);
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>

    <script src="https://source.zoom.us/2.6.0/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/2.6.0/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/2.6.0/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/2.6.0/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/2.6.0/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-2.6.0.min.js"></script>

    <!-- For Component View -->
    <script src="https://source.zoom.us/2.6.0/zoom-meeting-embedded-2.6.0.min.js"></script>


    <script>
        let zmm = ZoomMtgEmbedded.createClient()
        undefined
        let rootZmm = document.getElementById('zmmtg-root');
        undefined
        zmm.init({
            zoomAppRoot: rootZmm,
            language: 'en-US',
            customize: {
                video: {
                    isResizable: true,
                    viewSizes: {
                        default: {
                            width: 500,
                            height: 200
                        },
                        ribbon: {
                            width: 150,
                            height: 350
                        }
                    }
                }
            }
        })
    </script>

    <script src="{{ asset('js/zoom-js/tool.js') }}"></script>
    <script src="{{ asset('js/zoom-js/vconsole.min.js') }}"></script>
    <script src="{{ asset('js/zoom-js/meeting.js') }}"></script>
    <script>
        var API_KEY = "5Ktt2Z3fQTyn0wSVVTKpXg";
        var API_SECRET = "S4psQKnlyJWkXEzgP3H9fyEJ5ryft3tPCJlM";
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>

    <div id="stream-blank" class="d-none">

    </div>

    <input id="err_code_join" type="hidden" name="err_code_join" value="">
    <input id="join_status" type="hidden" name="join_status" value="">
    <input id="meet_status" type="hidden" name="meet_status" value="">

</body>

</html>

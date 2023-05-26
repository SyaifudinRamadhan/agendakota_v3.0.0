<!DOCTYPE html>
<html>

<head>
    <meta charset=utf-8 />
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <title>videojs-contrib-hls embed</title>
    <style>
        /* .vjs-live .vjs-progress-control{
            display: flex !important;
            align-items: center;
        } */
    </style>

    Uses the latest versions of video.js and videojs-contrib-hls.
    To use specific versions, please change the URLs to the formhcuvhd sdbvdvjdiv sdnifvvi:
    <link href="/video-js-8.2.1/video-js.css" rel="stylesheet" />
    {{-- <link href="https://unpkg.com/video.js@5.16.0/dist/video-js.css" rel="stylesheet"> --}}
    {{-- <link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet"> --}}

    {{-- <script src="https://unpkg.com/video.js@5.16.0/dist/video.js"></script> --}}
    {{-- <script src="https://unpkg.com/videojs-contrib-hls@4.1.1/dist/videojs-contrib-hls.js"></script> --}}


    {{-- 
  <script src="https://unpkg.com/video.js/dist/video.js"></script>
  <script src="https://unpkg.com/videojs-contrib-hls/dist/videojs-contrib-hls.js"></script> --}}
    <script src="/video-js-8.2.1/video.min.js"></script>


</head>

<body>
    <h1>Video.js Example Embed</h1>

    <form id="form" action="">
        <label for="streamKey">Stream Key</label><input id="streamKey" type="text">
        <label for="email">Email</label><input id="email" type="email">
        <label for="pass">Password</label><input id="pass" type="password">
        <label for="purchase">Purchase ID</label><input id="purchase" type="text">
        <button type="submit">Submit</button>
    </form>

    <div id="video" style="margin: auto; margin-top: 50px; width: 80%; display: none;">

    </div>

    <script>
        document.getElementById('form').addEventListener('submit', function(event) {
            event.preventDefault()
            let key = document.getElementById('streamKey').value
            // let email = document.getElementById('email').value
            // let pass = document.getElementById('pass').value
            let pch = document.getElementById('purchase').value

            document.getElementById('video').innerHTML = `<video id="my_video_1" class="video-js vjs-default-skin" controls preload="auto" width="640" height="268"
            data-setup='{}'>
            <source id="src" src="" type="application/x-mpegURL">
        </video>`

            let lcOrigin = window.location.origin
            lcOrigin = lcOrigin.split(':8000')[0]
            document.getElementById('src').src = lcOrigin +
                `:3000/streams/${key}/index.m3u8?purchase=${pch}`
                // console.log(videojs.Vhs.xhr.beforeRequest);
            videojs.Vhs.xhr.beforeRequest = function(opt) {
                // opt.headers.access - control - allow - origin = nameDomain
                opt.headers = {
                    'x-access-token': "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjp7ImlkIjoxLCJnb29nbGVfaWQiOiIxMTI2NjgwNDEwOTQyMzg4MDYzMDYiLCJwa2dfaWQiOjEsIm5hbWUiOiJBaG1hZCBTeWFpZnVkaW4iLCJlbWFpbCI6InN5YWlmdWRpbnJhbWFkaGFuQGdtYWlsLmNvbSIsInBhc3N3b3JkIjoiJDJ5JDEwJFcyLzVjQnBkNUVlclY0eFRnUkdTdi5ETVhlbXFKdlB1ZW51Q0hkcFN5ejJSQW5TSkxYY2N1IiwicGhvdG8iOiJ1c2VyLmpwZyIsInBob25lIjoiMDg4MjE3NDY2NTMyIiwiYmlvIjoiaGVydWlnaGl1IGdldWlyamdvaWoiLCJoZWFkbGluZSI6ImhkeXVoZml1ZHNoZGZ1aSIsImluc3RhZ3JhbV9wcm9maWxlIjpudWxsLCJsaW5rZWRpbl9wcm9maWxlIjpudWxsLCJ0d2l0dGVyX3Byb2ZpbGUiOm51bGwsImlzX2FjdGl2ZSI6MSwicGtnX3N0YXR1cyI6MSwiY3JlYXRlZF9hdCI6IjIwMjItMTAtMjBUMTM6MzY6MTAuMDAwWiIsInVwZGF0ZWRfYXQiOiIyMDIzLTAyLTI0VDA5OjA2OjE2LjAwMFoiLCJ0b2tlbiI6ImVRa2p2dFpMemY1M0w1T09mQzM2ckxYQ1p4c1VES29kIn0sImlhdCI6MTY4MTc0NTM3OSwiZXhwIjoxNjgxODMxNzc5fQ.hWyDkB_hiq9usZbEWV3upoouLdjrEdhr4RfKcghdIXU"
                };
                console.log(opt.headers);
                console.log(lcOrigin +
                `:3000/streams/${key}/index.m3u8?purchase=${pch}`);
                return opt
            }
            const a = new videojs('my_video_1', {
                liveui: true,
                liveTracker: true,
                aspectRatio: "16:9",
                responsive: true
            });
            a.liveTracker.options_.trackingThreshold = 0
            document.getElementById('video').style.display = 'inherit'
        })
    </script>

</body>

</html>

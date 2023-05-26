<style>
    .content-stream-yt {
        max-width: unset !important;
        left: 1%;
        right: 1%;
        top: 60px;
    }

    .stream-row-2 {
        background-color: black;
    }

    .ytp-popup {
        display: none !important;
    }

    .ytp-title {
        display: none !important;
    }


</style>

{{-- $('.ytp-popup').remove;
        $('.ytp-title').remove;
        $('.ytp-title-channel').remove;
        $('.ytp-pause-overlay').remove;
        $('.ytp-youtube-button').remove; --}}

<div id="frame">
    @for ($i = 0; $i < count($url); $i++)
        <iframe allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen"
            msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen"
            webkitallowfullscreen="webkitallowfullscreen"
            class="iframe" id="stream{{ $i }}"
            src="{{ $url[$i]->link }}" frameborder="0">
        </iframe>
    @endfor
    {{-- <video src="blob:https://www.youtube.com/e9b26f97-1f43-4227-9b9c-157d1275d9a3"></video> --}}
</div>

<script src="{{ asset('js/user/youtubeStream.js') }}"></script>
<script>
    setInterval(() => {
        var dataSession = JSON.parse('<?php echo $url; ?>');
        //console.log(dataSession);
        autoChangeVideo(dataSession);
    }, 500);
</script>

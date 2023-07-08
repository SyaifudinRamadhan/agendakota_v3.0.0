@extends('layouts/streaming')

<link rel="stylesheet" href="{{ asset('css/user/streamPage.css') }}">

@php
    use Carbon\Carbon;
    $timeZone = new DateTimeZone('Asia/Jakarta');
    //$start = date_create(new DateTime(date('m/d/y h:i:s a')), $timeZone);
    //$end = date_create(date('m/d/y h:i:s a', strtotime($event->start_date.' '.$event->start_time)), $timeZone);
    //$diff = date_diff($start, $end);
    $end = '';
    $endEvent = '';
    
    $dt = new DateTime();
    $now = $dt;
    // dd($endSession);
    // if ($paramPage == 'byUser') {
    //     $end = new DateTime($startSession->start_date . ' ' . $startSession->start_time, new DateTimeZone('Asia/Jakarta'));
    //     $endEvent = new DateTime($startSession->end_date . ' ' . $startSession->end_time, new DateTimeZone('Asia/Jakarta'));
    // } else {
    //     $end = new DateTime($event->start_date . ' ' . $event->start_time, new DateTimeZone('Asia/Jakarta'));
    //     $endEvent = new DateTime($event->end_date . ' ' . $event->end_time, new DateTimeZone('Asia/Jakarta'));
    // }
    $end = new DateTime($startSession->start_date . ' ' . $startSession->start_time, new DateTimeZone('Asia/Jakarta'));
    $endEvent = new DateTime($startSession->end_date . ' ' . $startSession->end_time, new DateTimeZone('Asia/Jakarta'));
    $different = $now->diff($end);
    //dd($event->receptionists);
    // dd($different, $now, $end);
@endphp
{{-- @include('admin.partials.alert') --}}

<input id="event-status" type="hidden"
    value="{{ $different->invert == 0 ? 'Acara Dimulai Dalam' : ($now >= $endEvent ? 'Acara Telah Selesai' : 'Acara Telah Dimulai Selama') }}">
<input type="hidden" id="start" value="{{ $end->format('m/d/Y H:i:s') }}">
<input type="hidden" id="end" value="{{ $endEvent->format('m/d/Y H:i:s') }}">



@section('HomeStream')
    <div id="home-stream" class="col-12 main-stream pe-0 ps-0" style="animation: fadeIn 500ms;">

        {{-- Background part untuk absolute BG --}}
        {{-- BG sidemenu streaming ada di parent blade (user.blade.php) bagian nav streaming --}}
        <img id="bg-img-top" src="{{ asset('images/bg-streams/top-home.png') }}" alt=""
            style="z-index: -2; width: 100%; top: 0px; left: 0;">
        {{-- --------------------------------- --}}

        <div class="w-100 text-center mt-3" style="color: wheat;
        position: absolute; top: 30px; z-index: 2;">
            <div>
                <h4 id="status-event1" class="p-2">
                    {{-- <button class="btn btn-light fw-bold" style="background-color: aliceblue">
                        {{ $different->invert == 0 ? 'Acara Dimulai Dalam ' : ($now >= $endEvent ? 'Acara Telah Selesai' : 'Acara Telah Dimulai Selama ') }}
                    
                    <a id="countdown1" class="teks-primer"></a>
                    </button> --}}
                </h4>
            </div>
        </div>
        <div class="container-home-center">
            <div id="parent-banner" class="bg-home-center d-flex" alt="">
                {{-- Background part relative inner container home --}}
                <img class="img-bg-home-center" width="100%" src="{{ asset('images/bg-streams/center-home.png') }}"
                    alt="">
                {{-- ---------------------------------------- --}}
                <div class="position-absolute d-flex w-100">
                    <img id="banner-img" class="rounded-15 bg-banner banner-home mx-auto my-auto" style="width:unset;"
                        src="{{ asset('storage/event_assets/' . $event->slug . '/event_logo/thumbnail/' . $event->logo) }}">
                </div>
            </div>
        </div>
        {{-- Background part relative inner container home --}}
        {{-- <img width="100%" src="{{ asset('images/bg-streams/main-stage-split-center.png') }}" alt=""> --}}
        {{-- --------------------------------------------- --}}
        <div class="row m-0">
            <div class="col-4 p-0 d-flex text-end">
                {{-- Background part relative inner container home --}}
                <img width="100%" height="100%" src="{{ asset('images/bg-streams/bottom-l-home.png') }}" alt="">
                {{-- --------------------------------------------- --}}
                <div class="btn-door btn-door-1">
                    <a class="btn btn-no-pd bg-primer-transparent home-btn fs-14 ml-auto my-auto stream-menu rounded-pill pointer"
                        value="Receptionist">
                        Home Screen
                    </a>
                </div>

            </div>
            <div class="col-4 p-0 d-flex text-center">
                {{-- Background part relative inner container home --}}
                <img width="100%" height="100%" src="{{ asset('images/bg-streams/bottom-c-home.png') }}" alt="">
                {{-- --------------------------------------------- --}}
                <div class="btn-door btn-door-2">
                    <a class="btn btn-no-pd bg-primer-transparent stage-btn fs-14 mx-auto my-auto stream-menu rounded-pill pointer"
                        value="Descriptions">
                        Main Stage
                    </a>
                </div>
            </div>
            <div class="col-4 p-0 d-flex">
                {{-- Background part relative inner container home --}}
                <img width="100%" height="100%" src="{{ asset('images/bg-streams/bottom-r-home.png') }}" alt="">
                {{-- --------------------------------------------- --}}
                <div class="btn-door btn-door-3">
                    <a class="btn btn-no-pd exhibitions-btn bg-primer-transparent fs-14 mr-auto my-auto stream-menu rounded-pill pointer"
                        value="Handbooks">
                        Exhibitors
                    </a>
                </div>
            </div>
        </div>
        <div class="row row__mod">
            <div class="col-12 p-0">
                {{-- Background part relative inner container home --}}
                {{-- <img width="100%" height="100%" src="{{ asset('images/bg-streams/main-stage-bottom.png') }}" alt=""> --}}
                {{-- --------------------------------------------- --}}
            </div>
        </div>
    </div>
@endsection

@section('ReceptionistList')
    <div id="rcp-list" class="col-12 main-stream pe-0 ps-0 d-none">

    </div>
@endsection

@section('HandbookList')
    <div id="handbook-list" class="col-12 main-stream pe-0 ps-0 d-none" style="animation: fadeIn 500ms;">
        <div class="container">

            @php
                $allHandbook = $event->handbooks->groupBy('type_file');
                $photos = [];
                $videos = [];
                $documents = [];
                try {
                    $photos = $allHandbook['photo'];
                    $videos = $allHandbook['video'];
                    $documents = $allHandbook['documents'];
                } catch (\Throwable $th) {
                    //throw $th;
                }
            @endphp
            <h4 class="fw-bold mb-3">HandBooks</h4>
            @if (count($photos) == 0 && count($videos) == 0 && count($documents) == 0)
                <div class="col-md-12 text-center">
                    -- Handbook Tidak di Set --
                </div>
            @else
                <div class="row">
                    <div class="col">
                        {{-- tab --}}
                        <div class="tab scrollmenu" style="border: none; border-bottom: 1px solid #F0F1F2;">
                            <button class="btn tab-btn tablinks-event-handbook active mr-4 no-pd-l no-pd-r fs-17"
                                onclick="opentabs(event, 'event-handbook', 'photos')">Pictures</button>
                            <button class="btn tab-btn tablinks-event-handbook mr-4 no-pd-l no-pd-r fs-17"
                                onclick="opentabs(event, 'event-handbook', 'videos')">Videos</button>
                            <button class="btn tab-btn tablinks-event-handbook mr-4 no-pd-l no-pd-r fs-17"
                                onclick="opentabs(event, 'event-handbook', 'documents')">Documents</button>
                            <!-- <button class="tablinks-event-detail no-pd-l no-pd-r fs-17"
                                                                                                                                                                                                            onclick="opentabs(event, 'event-detail', 'Sponsors')">Sponsor</button> -->
                            <!-- <button class="tablinks-event-detail mr-4 no-pd-l no-pd-r fs-14" onclick="opentabs(event, 'event-detail', 'Booths')">Booths</button> -->

                        </div>
                        {{-- end tab --}}

                    </div>
                </div>
                <div id="photos" class="tabcontent-event-handbook mt-4 ml-3 mb-5" style="display: block; border: none;">
                    <div class="row mt-3">
                        @foreach ($photos as $photo)
                            <div class="col-lg-3">
                                <div class="bg-white rounded-5 shadow-box">
                                    <a
                                        href="{{ asset('storage/event_assets/' . $event->slug . '/event_handbooks/' . $photo->file_name) }}">
                                        <div class="tinggi-150"
                                            bg-image="{{ asset('storage/event_assets/' . $event->slug . '/event_handbooks/' . $photo->file_name) }}">
                                        </div>
                                    </a>
                                    <div class="">
                                        <div class="p-2 pb-1">
                                            <h6 class="detail fw-bold mt-2">{{ $photo->file_name }}

                                            </h6>
                                            <p class="teks-transparan detail">Uploaded
                                                {{ Carbon::parse($photo->created_at)->format('d M,') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div id="videos" class="tabcontent-event-handbook mt-4 ml-3 mb-5" style="display: none; border: none;">
                    <div class="row mt-3">
                        @foreach ($videos as $video)
                            <div class="col-lg-6 mb-4">
                                <iframe class="w-100 rounded-15 asp-rt-16-9" height="100%"
                                    src="{{ $video->file_name }}"></iframe>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div id="documents" class="tabcontent-event-handbook mt-4 ml-3 mb-5"
                    style="display: none; border: none;">
                    <div class="row mt-3">
                        @foreach ($documents as $document)
                            <div class="col-lg-3 mb-4">
                                <a
                                    href="{{ asset('storage/event_assets/' . $event->slug . '/event_handbooks/' . $document->file_name) }}">
                                    <div class="bg-putih rounded-5 shadow-box">
                                        <div class="tinggi-150 text-center">
                                            <i class="fa bi bi-file-earmark-text mt-5 teks-primer text-icon-2"></i>
                                        </div>
                                        <div class="">
                                            <div class="p-2 pb-1">
                                                <h6 class="detail fw-bold mt-2">{{ $document->file_name }}

                                                </h6>
                                                <p class="teks-transparan detail">Uploaded
                                                    {{ Carbon::parse($document->created_at)->format('d M,') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection

@section('ContentStream')
    <div id="content-stream" class="col-12 main-stream pe-0 ps-0 d-none" style="animation: fadeIn 500ms;">
        {{-- Background part untuk fixed BG untuk stream page --}}
        {{-- BG sidemenu streaming ada di parent blade (user.blade.php) bagian nav streaming --}}
        <img id="bg-img-top-stream" src="{{ asset('images/stream-stage.png') }}" alt=""
            class="bg-streaming w-100">
        {{-- --------------------------------- --}}

        @if ($streamType == 'zoom')
            <input type="hidden" id="type-stream" value="zoom">
            @if ($now <= $endEvent)
                @include('layouts.stream-part')
            @endif
        @elseif($streamType == 'youtube')
            <input type="hidden" id="type-stream" value="youtube">
            @include('layouts.stream-part-youtube')
        @elseif($streamType == 'rtmp-stream-key')
            {{-- <link href="/video-js-8.2.1/video-js.css" rel="stylesheet" /> --}}
            <input type="hidden" id="type-stream" value="{{ $streamType }}">
            <input type="hidden" id="url-stream" value="{{ $link }}">
            <input type="hidden" id="x-access-token" value="{{ $xAccessToken }}">
            <input type="hidden" id="start-time" value="{{ $url[0]->start_date.' '.$url[0]->start_time }}">
            <input type="hidden" id="end-time" value="{{ $url[0]->end_date.' '.$url[0]->end_time }}">
            <div id="rtmp-video"></div>
        @elseif($streamType == 'webrtc-video-conference')
            <input type="hidden" id="type-stream" value="{{ $streamType }}">
            <input type="hidden" id="url-server" value="{{$link}}">
            <input type="hidden" id="room" value="{{explode('webrtc-video-conference-', $url[0]['link'])[1]}}">
            <input type="hidden" id="name" value="{{$nama_peserta}}">
            <input type="hidden" id="myData" value="{{$myData}}">
            <input type="hidden" id="organization" value="{{$organization}}">
            <input type="hidden" id="user-team" value="{{$myData->organizationsTeam}}">
            <input type="hidden" id="token" value="{{$xAccessToken}}">
            <input type="hidden" id="start-time" value="{{ $url[0]->start_date.' 00:00:00' }}">
            <input type="hidden" id="end-time" value="{{ $url[0]->end_date.' '.$url[0]->end_time }}">
            
            <div id="multiple-conference"></div>
        @endif
        <div id="stream-blank" class="d-none">
            <h3 id="status-event2" class="text-center mt-5">
                {{ $different->invert == 0 ? 'Acara Dimulai Dalam ' : ($now >= $endEvent ? 'Acara Telah Selesai' : 'Acara Telah Dimulai Selama ') }}
                <a id="countdown2" class="teks-primer">
                </a>
            </h3>
            @if ($different->invert == 0 || $now <= $endEvent)
                <div class="d-flex w-100">
                    <div class="w-50 mx-auto">
                        <h6 class="text-center mt-5">
                            <b>
                                Tunggu event creator untuk memulai streaming videonya. Jika streaming belum dimulai otomatis
                                pada
                                jadwalnya, silahkan klik tombol dibawah ini.
                            </b>
                        </h6>
                        @if ($streamType == 'zoom')
                            <button id="reload-zoom-stream" class="btn btn-primer mt-4">Muat Ulang</button>
                        @else
                            <button id="reload-stream" class="btn btn-primer mt-4">Muat Ulang</button>
                        @endif
                    </div>
                </div>
            @else
                <h6 class="text-center mt-5">
                    Terimakasih telah mengikuti event kami. Semoga bermanfaat.
                </h6>
                {{-- <div class="d-flex w-100">
                    <div class="w-50 mx-auto">
                        <button id="reload-stream" class="btn btn-primer mt-4">Muat Ulang</button>
                    </div>
                </div> --}}
            @endif
        </div>

        @if ($streamType == 'rtmp-stream-key' || $streamType == 'webrtc-video-conference')
            <script>
                console.log(document.getElementById("room"));
            </script>
            <script src="{{ asset('js/app.js') }}"></script>
        @endif

        <input id="err_code_join" type="hidden" name="err_code_join" value="">
        <input id="join_status" type="hidden" name="join_status" value="">
        <input id="meet_status" type="hidden" name="meet_status" value="">

    </div>
@endsection

@section('ConnectionList')
    <div id="connect-list" class="col-12 main-stream pe-0 ps-0 d-none" style="animation: fadeIn 500ms;">
        <link rel="stylesheet" href="{{ asset('css/user/connectionPage.css') }}">
        @php
            $connections = $event->purchase->unique('user_id');
            // dd($connections);
        @endphp

        {{-- Background part relative inner container home --}}
        {{-- --------------------------------------------- --}}

        <div class="container">
            <h4 class="fw-bold mb-3">Connections</h4>
            <div class="row mb-3">
                @foreach ($connections as $connection)
                    <div class="col-md-6 connection-item mt-2">
                        <div class="wrap">
                            <div class="shadow-box rounded-5 bg-white">

                                @if ($connection->users->photo == 'default')
                                    <img src="{{ asset('images/profile-user.png') }}" class="picture">
                                @else
                                    <img src="{{ asset('storage/profile_photos/' . $connection->users->photo) }}"
                                        class="picture">
                                @endif
                                <div class="wrap ml-24">
                                    <h3>{{ $connection->users->name }}</h3>
                                    <p class="teks-transparan">
                                        @if (count($connection->users->organizations) > 0)
                                            {{ $connection->users->organizations[0]->name }}
                                        @else
                                            No Organization
                                        @endif
                                    </p>
                                </div>

                                <div class="socmed socmed-box ml-24">
                                    <a href="{{ $connection->users->linkedin_profile }}">
                                        <li><i class="fab fa-linkedin"></i></li>
                                    </a>
                                    <a href="{{ $connection->users->instagram_profile }}">
                                        <li><i class="fab fa-instagram"></i></li>
                                    </a>
                                    <a href="{{ $connection->users->twitter_profile }}">
                                        <li><i class="fab fa-twitter"></i></li>
                                    </a>
                                </div>

                                <div class="btn-message-box">
                                    @if ($myData->email != $connection->users->email)
                                        <form action="{{ route('message.tambah.user') }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="connection_id"
                                                value="{{ $connection->users->id }}">
                                            <input type="hidden" name="event_id" value={{ $event->id }}>
                                            <button class="btn btn-shadow teks-primer" type="submit">
                                                <i class="fa bi bi-chat-left-text mr-2"></i>Message
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('ExhibitorList')
    <div id="exh-list" class="col-12 main-stream pe-0 ps-0 d-none" style="animation: fadeIn 500ms;">

        <div class="container">
            @php
                $counter = 0;
            @endphp
            <div id="cat-list" class="row">
                <h4 class="fw-bold mb-3 pt-5">Exhibitors</h4>
                @foreach ($exhibitors as $key => $exhibitor)
                    <div class="col-xl-4" style="margin-top:2%;"
                        onclick="expandBooth('#cat-expand-'+{{ $counter }}, '#cat-list')">
                        <a id="category-{{ $counter }}">
                            <div class="bg-white shadow-box rounded-5">
                                <div class="">

                                    <div class="p-3 d-flex">
                                        <img src="{{ $exhibitor[0] }}" class="asp-rt-1-1 rounded-5" width="58px"
                                            height="58px">
                                        <div class="title-exhibitor ms-3 my-auto">
                                            <div class="fw-bold fs-6">
                                                {{ $key }}
                                            </div>
                                            <div>
                                                {{ count($exhibitor[1]) }} Booth
                                            </div>
                                        </div>
                                        <div style="padding-left: 10px; margin-top:-1%; color: #C4C4C4; font-size:10pt;">

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @php
                        $counter += 1;
                    @endphp
                @endforeach
            </div>
            <div id="expand-cat">
                @php
                    $counter = 0;
                @endphp
                @foreach ($exhibitors as $key => $booths)
                    <div id="cat-expand-{{ $counter }}" class="row d-none">

                        <h4 class="fw-bold mb-3 pt-5 pointer"
                            onclick="expandBooth('#cat-list', '#cat-expand-'+{{ $counter }})">
                            <button class="btn btn-primer" style="width:unset;"> <i
                                    class="bi bi-arrow-left-short fs-3 fw-bold"></i> </button>
                            {{ $key }} Booths
                        </h4>

                        @foreach ($booths[1] as $booth)
                            <div class="col-xl-4" style="margin-top:2%;">
                                <a href="{{ route('user.home.exhibitions', [$event->id, $booth->id]) }}" target="_blank">
                                    <div class="bg-white shadow-box rounded-5">
                                        <div class="">

                                            <div class="p-3 d-flex">

                                                <img src="{{ asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_logo/' . $booth->logo) }}"
                                                    class="rounded-circle asp-rt-1-1 mt-4" width="58px" height="58px">

                                                <div class="title-exhibitor ms-3 my-auto">
                                                    <div class="fw-bold fs-6">
                                                        {{ $booth->name }}
                                                    </div>
                                                    <div class="teks-primer font-info mt-2 ml-2">
                                                        @if ($booth->virtual_booth == 1)
                                                            <i class="fa fa-check pr-2 teks-primer"
                                                                aria-hidden="true"></i>Virtual
                                                            Booth<span></span>
                                                        @else
                                                            <i class="fas fa-times pr-2 teks-primer"
                                                                aria-hidden="true"></i>Nothing Virtual
                                                            Booth<span></span>
                                                        @endif

                                                    </div>
                                                </div>
                                                <div
                                                    style="padding-left: 10px; margin-top:-1%; color: #C4C4C4; font-size:10pt;">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @php
                        $counter += 1;
                    @endphp
                @endforeach
            </div>
        </div>

    </div>

    <script>
        const expandBooth = (targetId, parentId) => {
            document.querySelector(parentId).classList.add('d-none');
            document.querySelector(targetId).classList.remove('d-none');
        }

        const closeExpandBooth = (targetId, parentId) => {
            document.querySelector(parentId).classList.add('d-none');
            document.querySelector(targetId).classList.remove('d-none');
        }
    </script>
@endsection

@section('SponsorList')
    <!-- Modal -->
    <div class="modal fade" id="sponsorModal" tabindex="-1" aria-labelledby="sponsorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 900px">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="sponsorModalLabel">Sponsors</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach ($event->sponsors->sortBy('priority') as $sponsor)
                            <div class="col-md-4" style="margin-top:2%;">
                                <div>
                                    <div class="bg-white shadow-box rounded-5">
                                        <div class="">

                                            <div class="p-3 d-flex">
                                                <img src="{{ asset('storage/event_assets/' . $event->slug . '/sponsor_logo/' . $sponsor->logo) }}"
                                                    class="asp-rt-1-1 rounded-5" width="58px" height="58px">
                                                <div class="title-exhibitor ms-3 my-auto">
                                                    <div class="fw-bold fs-6">
                                                        {{ $sponsor->name }}
                                                    </div>
                                                    <div>
                                                        {{ $sponsor->type }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                style="padding-left: 10px; padding-bottom: 10px; margin-top:-1%; color: #e46161; font-size:10pt;">
                                                <a style="color: #e46161;" href="{{ $sponsor->website }}">See About
                                                    Sponsor</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('ScheduleList')
    <!-- Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="scheduleModalLabel">Schedules</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3">
                        <label class="mb-2" for="date">Tanggal Mulai</label>
                        <select id="date" class="form-select" aria-label="Default select schedule"
                            onchange="selected(this)">
                            @foreach ($event->rundowns->where('deleted', 0)->groupBy('start_date') as $key => $date)
                                <option value="{{ $key }}">{{ Carbon::parse($key)->format('D d-M-Y') }}</option>
                            @endforeach
                        </select>
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($event->rundowns->where('deleted', 0)->groupBy('start_date') as $key => $rundown)
                            <div id="{{ 'sch-' . $key }}" class="sch-box {{ $i > 0 ? 'd-none' : '' }}">
                                @foreach ($rundown as $rd)
                                    <div class="col-md-12" style="margin-top:2%;">
                                        <div class="bg-white shadow-box rounded-5">
                                            <div class="">
                                                <div class="p-3 d-flex">
                                                    <i class="bi bi-calendar-range fs-2 teks-primer my-auto"></i>
                                                    <div class="title-exhibitor ms-3 my-auto">
                                                        <div class="fw-bold fs-6">
                                                            {{ $rd->name }}
                                                        </div>
                                                        <div>
                                                            <b>{{ Carbon::parse($key)->format('d-M-Y H:i:s') }}</b>
                                                            s.d.
                                                            <b>{{ Carbon::parse($rd->end_date)->format('d-M-Y H:i:s') }}</b>
                                                        </div>
                                                        <div>
                                                            {!! $rd->description !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const changeSch = (targetId) => {
            document.querySelectorAll('.sch-box').forEach(el => {
                el.classList.add('d-none');
                console.log(el);
            });
            try {
                document.querySelector('#sch-' + targetId).classList.remove('d-none');
            } catch (error) {

            }
        }
        const selected = (obj) => {
            changeSch(obj.value);
        }
    </script>
@endsection

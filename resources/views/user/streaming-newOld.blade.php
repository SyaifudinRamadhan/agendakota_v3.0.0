@extends('layouts.user')

@section('title', 'Streaming')

<style>
    body{
        background-color: #242b2d !important;
    }
    #stream-row{
        margin-left: unset;
        margin-right: unset;
    }
    #right-stream{
        position: fixed;
        right: 0;
        padding-top: 10px;
        width: 29.33%;
    }
    #content-stream{
        padding: unset
    }
    .row__mod{
        margin-right: unset !important;
        margin-left: unset !important;
    }
    nav.left{
        border-right: unset !important;
    }
    .left.nav-atas{
        display: flex;
    }
    .container-home-center{
        width: 100%;
        /* position: absolute; */
        left: 0;
        aspect-ratio: 5/2;
        /* margin-top: 129px; */
    }
    .container-home-center .bg-home-center{
        z-index: -2;
        /* position: absolute; */
        width:100%;
        height:100%;
        display: flex;
    }
    .container-home-center .bg-home-center .img-bg-home-center{
        width: 100%;
        aspect-ratio: 5/2;
        z-index: -2;
        position: absolute;
    }
    .banner-home{
        width: 70% !important;
        top: 0;
    }
    .btn-door{
        position: absolute;
        top: 0;
        display: flex;
        width: 100%;
        height: 100%;
    }
    .btn-door a{
        color: #e5214f !important; /*#eb597b*/
        border-radius: 14px;
    }
    .btn-door-1{
        padding-right: 8%;
    }
    .btn-door-2{

    }
    .btn-door-3{
        padding-left: 8%;
    }
    .box-desc{
        width: 100%;
        margin-top: -100px;
        z-index: 1;
        display: none;
    }
</style>

@section('contentStream')
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
    @include('admin.partials.alert')
    {{-- Background part untuk absolute BG --}}
        {{-- BG sidemenu streaming ada di parent blade (user.blade.php) bagian nav streaming --}}
        <img id="bg-img-top" src="{{ asset('images/bg-streams/main-stage-top.png') }}" alt="" style="z-index: -2; width: 100%; height: 17%; min-height: 131px; top: 0px; left: 0;">
    {{-- --------------------------------- --}}
    {{-- Background part untuk fixed BG untuk stream page--}}
        {{-- BG sidemenu streaming ada di parent blade (user.blade.php) bagian nav streaming --}}
        <img id="bg-img-top-stream" src="{{ asset('images/stream-stage.png') }}" alt="" class="bg-streaming">
    {{-- --------------------------------- --}}
    
    <input id="event-status" type="hidden"
        value="{{ $different->invert == 0 ? 'Acara Dimulai Dalam' : ($now >= $endEvent ? 'Acara Telah Selesai' : 'Acara Telah Dimulai Selama') }}">
    <input type="hidden" id="start" value="{{ $end->format('m/d/Y H:i:s') }}">
    <input type="hidden" id="end" value="{{ $endEvent->format('m/d/Y H:i:s') }}">


    <div id="home" class="tabcontent-stream-menu tabcontent-stream-menu-2" style="display: block;">
        <div class="w-100 text-center" style="color: wheat;
        position: absolute; top: 30px;">
            <h3 id="status-event1" class="p-2">
                {{ $different->invert == 0 ? 'Acara Dimulai Dalam ' : ($now >= $endEvent ? 'Acara Telah Selesai' : 'Acara Telah Dimulai Selama ') }}
                <a id="countdown1" class="teks-primer"></a>
                </h3>
        </div>
        <div class="container-home-center">
            <div class="bg-home-center" alt="">
            {{-- Background part relative inner container home --}}
            <img class="img-bg-home-center" src="{{ asset('images/bg-streams/main-stage-center.png') }}" alt="">
            {{-- ---------------------------------------- --}}
                <img class="tinggi-350 rounded-15 bg-banner banner-home mx-auto my-auto"
                    src="{{ asset('storage/event_assets/' . $event->slug . '/event_logo/thumbnail/' . $event->logo) }}">
            </div>
        </div>
        {{-- Background part relative inner container home --}}
        <img width="100%" src="{{ asset('images/bg-streams/main-stage-split-center.png') }}" alt="">
        {{-- --------------------------------------------- --}}
        <div class="row row__mod">
            <div class="col-4 p-0">
                {{-- Background part relative inner container home --}}
                    <img width="100%" height="100%" src="{{ asset('images/bg-streams/main-stage-door-1.png') }}" alt="">
                {{-- --------------------------------------------- --}}
                <div class="btn-door btn-door-1">
                    <a href="#Receptionist" class="btn btn-no-pd bg-primer-transparent fs-14 ml-auto my-auto stream-menu" value="Receptionist">
                        Receptionist
                    </a>
                </div>
                
            </div>
            <div class="col-4 p-0">
                {{-- Background part relative inner container home --}}
                <img width="100%" height="100%" src="{{ asset('images/bg-streams/main-stage-door-2.png') }}" alt="">
                {{-- --------------------------------------------- --}}
                <div class="btn-door btn-door-2">
                    <a href="#Descriptions" class="btn btn-no-pd bg-primer-transparent fs-14 mx-auto my-auto stream-menu" value="Descriptions" >
                        Description
                    </a>
                </div>
            </div>
            <div class="col-4 p-0">
                {{-- Background part relative inner container home --}}
                <img width="100%" height="100%" src="{{ asset('images/bg-streams/main-stage-door-3.png') }}" alt="">
                {{-- --------------------------------------------- --}}
                <div class="btn-door btn-door-3">
                    <a href="#Handbooks" class="btn btn-no-pd bg-primer-transparent fs-14 mr-auto my-auto stream-menu" value="Handbooks">
                        Handbook
                    </a>
                </div>
            </div>
        </div>
        <div class="row row__mod">
            <div class="col-12 p-0">
                {{-- Background part relative inner container home --}}
                <img width="100%" height="100%" src="{{ asset('images/bg-streams/main-stage-bottom.png') }}" alt="">
                {{-- --------------------------------------------- --}}
            </div>
            <div class="p-3 box-desc">
                <div class="w-100 rounded-5 bg-white text-dark p-3">
                    <section id="Descriptions" class="box-desc-inner">
                        <h4 class="mt-4 text-center fw-bold">{{ $event->name }}</h4>
                        <div class="row d-flex align-items-center pl-3 pr-3 mt-5">
                            <div class="col-md-1 no-pd-l no-pd-r mr-4">
                                <img src="{{ $event->organizer->logo == '' ? asset('images/profile-user.png') : asset('storage/organization_logo/' . $event->organizer->logo) }}"
                                    class="rounded-circle asp-rt-1-1 mb-2 rsps-profile" width="100%" height="100%">
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <p style="color: #828D99; margin-bottom: 0px;">Diadakan oleh</p>
                                </div>
                                <div class="row">
                                    <h4 class="teks-tebal fs-17" style="color: black;">
                                        {{ $event->organizer->name }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <textarea name="description" id="description" class="description no-pd mt-3"
                            readonly>{{ $event->description }}</textarea>
                    </section>
                    <section id="Receptionist" class="box-desc-inner mt-5 d-none">
                        <h4>Receptionists</h4>
                        <div class="row d-flex align-items-center pl-3 pr-3">
                            {{-- @dd(count($event->receptionists)) --}}
                            @if (count($event->receptionists) == 0)
                                <div class="col-md-12 text-center">
                                    -- Resepsionis Tidak di Set --
                                </div>
                            @endif
                            @if (count($event->receptionists) > 0)
                                {{-- @dd(count($event->receptionists)) --}}
                                @foreach ($event->receptionists as $receptionist)
                                    <div class="col-md-6">
                                        <a
                                            href="{{ $receptionist->user->phone == null ? '#' : 'https://wa.me/' . $receptionist->user->phone }}">
                                            <div class="row">
                                                <div class="col-md-2 no-pd-l no-pd-r mr-4">
                                                    @if ($receptionist->user->photo == 'default')
                                                        <img class="rounded-circle asp-rt-1-1 mb-2" width="47px" height="47px"
                                                            src='{{ asset('storage/profile_photos/profile-user.png') }}'>
                                                    @else
                                                        <img class="rounded-circle asp-rt-1-1 mb-2" width="47px" height="47px"
                                                            src='{{ asset('storage/profile_photos/' . $receptionist->user->photo) }}'>
                                                    @endif
            
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="row">
                                                        <p class="wrap-overflow" style="color: #828D99; margin-bottom: 0px;">
                                                            {{ $receptionist->user->email }}</p>
                                                    </div>
                                                    <div class="row">
                                                        <h4 class="teks-tebal fs-15 mb-0 wrap-overflow" style="color: black;">
                                                            {{ $receptionist->user->name }}</h4>
                                                    </div>
                                                    <div class="row">
                                                        <p class="teks-tebal wrap-overflow"
                                                            style="color: black; font-size: 13px !important">
                                                            {{ $receptionist->user->phone }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
            
                        </div>
                    </section>
                    <section id="Handbooks" class="box-desc-inner mt-4 d-none">
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
                        <h4>HandBooks</h4>
                        @if (count($photos) == 0 && count($videos) == 0 && count($documents) == 0)
                            <div class="col-md-12 text-center">
                                -- Handbook Tidak di Set --
                            </div>
                        @else
                            <div class="row my-2">
                                <div class="col">
                                    {{-- tab --}}
                                    <div class="tab scrollmenu" style="border: none; border-bottom: 1px solid #F0F1F2;">
                                        <button class="tab-btn tablinks-event-handbook active mr-4 no-pd-l no-pd-r fs-17"
                                            onclick="opentabs(event, 'event-handbook', 'photos')">Pictures</button>
                                        <button class="tab-btn tablinks-event-handbook mr-4 no-pd-l no-pd-r fs-17"
                                            onclick="opentabs(event, 'event-handbook', 'videos')">Videos</button>
                                        <button class="tab-btn tablinks-event-handbook mr-4 no-pd-l no-pd-r fs-17"
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
                                        <div class="col-md-6">
                                            <div class="bg-putih rounded-5 bayangan-5">
                                                <a
                                                    href="{{ asset('storage/event_assets/' . $event->slug . '/event_handbooks/' . $photo->file_name) }}">
                                                    <div class="tinggi-150"
                                                        bg-image="{{ asset('storage/event_assets/' . $event->slug . '/event_handbooks/' . $photo->file_name) }}">
                                                    </div>
                                                </a>
                                                <div class="">
                                                    <div class="wrap pb-1">
                                                        <h6 class="detail font-inter-header mt-2">{{ $photo->file_name }}
            
                                                        </h6>
                                                        <p class="teks-transparan fs-normal detail">Uploaded
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
                                        <div class="col-12 mb-4">
                                            <iframe class="lebar-100 rounded-15 asp-rt-16-9" height="100%"
                                                src="{{ $video->file_name }}"></iframe>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div id="documents" class="tabcontent-event-handbook mt-4 ml-3 mb-5"
                                style="display: none; border: none;">
                                <div class="row mt-3">
                                    @foreach ($documents as $document)
                                        <div class="col-lg-4 mb-4">
                                            <a
                                                href="{{ asset('storage/event_assets/' . $event->slug . '/event_handbooks/' . $document->file_name) }}">
                                                <div class="bg-putih rounded-5 bayangan-5">
                                                    <div class="tinggi-150 rata-tengah">
                                                        <i class="fa bi bi-file-earmark-text mt-5 teks-primer text-icon-2"></i>
                                                    </div>
                                                    <div class="">
                                                        <div class="wrap pb-1">
                                                            <h6 class="detail font-inter-header mt-2">{{ $document->file_name }}
            
                                                            </h6>
                                                            <p class="teks-transparan fs-normal detail">Uploaded
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
            
                    </section>
                </div>
            </div>
        </div>
    </div>
    
    <div id="connections" class="tabcontent-stream-menu tabcontent-stream-menu-2" style="display: none; min-height: 768px">
        
        @php
            $connections = $event->purchase->unique('user_id');
            // dd($connections);
        @endphp

        {{-- Background part relative inner container home --}}
        <img width="100%" src="{{ asset('images/bg-streams/main-stage-full-center.png') }}" alt="" style="position: absolute">
        {{-- --------------------------------------------- --}}

        <div class="row row__mod">
            @foreach ($connections as $connection)
                <div class="col-md-6 connection-item mt-2">
                    <div class="wrap">
                        <div class="bayangan-5 rounded bg-white">
    
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
                                        <input type="hidden" name="connection_id" value="{{ $connection->users->id }}">
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
    <div id="streaming" class="streaming-wrap tabcontent-stream-menu tabcontent-stream-menu-2" style="display: none;">
         {{-- Background part relative inner container home --}}
        
         {{-- --------------------------------------------- --}}
     
        @if ($streamType == 'zoom')
            <input type="hidden" id="type-stream" value="zoom">
            @include('layouts.stream-part')
        @elseif($streamType == 'youtube')
            <input type="hidden" id="type-stream" value="youtube">
            @include('layouts.stream-part-youtube')
        @endif
        <div id="stream-blank" style="display: none;">
            <h3 id="status-event2" class="text-center mt-5">
                {{ $different->invert == 0 ? 'Acara Dimulai Dalam ' : ($now >= $endEvent ? 'Acara Telah Selesai' : 'Acara Telah Dimulai Selama ') }}
                <a id="countdown2" class="teks-primer">
                </a>
            </h3>
            @if ($different->invert == 0 || $now <= $endEvent)
                <h6 class="text-center mt-5">
                    <b>
                        Tunggu event creator untuk memulai streaming videonya. Jika streaming belum dimulai otomatis pada
                    jadwalnya, silahkan klik tombol dibawah ini.
                    </b>
                </h6>
                <button id="reload-zoom-stream" class="btn btn-primer mt-4">Muat Ulang</button>
            @else
                <h6 class="text-center mt-5">
                    Terimkasih telah mengikuti event kami. Semoga bermanfaat.
                </h6>
            @endif
        </div>
    </div>
    <div id="exhibitions" class="tabcontent-stream-menu tabcontent-stream-menu-2" style="display: none; min-height: 768px">
        {{-- Background part relative inner container home --}}
        <img width="100%" src="{{ asset('images/bg-streams/main-stage-full-center.png') }}" alt="" style="position: absolute">
        {{-- --------------------------------------------- --}}
    
        <div class="row row__mod">
            @foreach ($event->exhibitors as $exhibitor)
                <div class="col-xl-4" style="margin-top:2%;">
                    <a href="{{ route('user.home.exhibitions', [$event->id, $exhibitor->id]) }}" target="_blank">
                        <div class="bg-putih bayangan-5 rounded">
                            <div class="">
    
                                <div class="wrap">
    
                                    <img src="{{ asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_logo/' . $exhibitor->logo) }}"
                                        class="rounded-circle asp-rt-1-1 mt-4" width="58px" height="58px">
    
    
                                    <div class="title-exhibitor pt-3 ml-1">
                                        {{ $exhibitor->name }}
                                    </div>
                                    <div style="padding-left: 10px; margin-top:-1%; color: #C4C4C4; font-size:10pt;">
    
                                    </div>
                                    <div class="teks-primer font-info mt-2 ml-2 pb-4">
                                        @if ($exhibitor->virtual_booth == 1)
                                            <i class="fa fa-check pr-2 teks-primer" aria-hidden="true"></i>Virtual
                                            Booth<span></span>
                                        @else
                                            <i class="fas fa-times pr-2 teks-primer" aria-hidden="true"></i>Nothing Virtual
                                            Booth<span></span>
                                        @endif
    
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    
    <input id="err_code_join" type="hidden" name="err_code_join" value="">
    <input id="join_status" type="hidden" name="join_status" value="">
    <input id="meet_status" type="hidden" name="meet_status" value="">
    <script src="https://cdn.ckeditor.com/ckeditor5/27.0.0/classic/ckeditor.js"></script>

    <script>
        ClassicEditor.create(document.querySelector('#description'))
            .then(editor => {
                editor.isReadOnly = true; // make the editor read-only right after initialization
            });
    </script>
    <script src="{{ asset('js/user/streamContent.js') }}"></script>
@endsection

@section('body_only')
    <!-- <input type="checkbox" id="check"> <label class="chat-btn" for="check"> <i class="fa fa-commenting-o comment"></i> <i class="fa fa-close close"></i> </label> -->
    

    {{-- <script src="{{ asset('js/user/chatStream.js') }}"></script> --}}
    <div id="chat-foat"></div>

@endsection

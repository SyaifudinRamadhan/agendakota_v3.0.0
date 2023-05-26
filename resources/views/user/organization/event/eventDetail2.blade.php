@extends('layouts.homepage')

@section('title', 'Events Details')

@section('content')
    @php
    use Carbon\Carbon;
    $tanggalSekarang = Carbon::now()->toDateString();
    @endphp

    @php
    use App\Models\Exhibitor;
    $exhibitors = Exhibitor::where('event_id', $event->id)
        ->where('overview', 1)
        ->get();
    // dump($exhibitors);
    @endphp

    @include('admin.partials.alert')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/eventDetailPage.css') }}">

    <style>
        .popup{
            width: 50%;
        }
    </style>

    <div class="top-row">
        <div class="container">
            <div class="row pt-5 pb-5">
                <div class="col-xl-8 mb-2 mt-2">
                    <div class="tinggi-350 rounded-8 bg-banner d-flex bg-white">
                        <img class="asp-rt-5-2 w-100 mx-auto my-auto rounded-8" src="{{ asset('storage/event_assets/' . $event->slug . '/event_logo/thumbnail/' . $event->logo) }}" alt="">
                    </div>
                </div>
                <div class="col-xl-4 mb-2 mt-2">
                    <div class="mb-0 mt-0">
                        <div class="row row-no-margin bg-putih border py-3 pl-1 mb-3 rounded-8">
                            <div class="col-md-12 mt-2">
                                <h4 class="teks-tebal">{{ $event->name }}</h4>
                            </div>
                            <div class="col-md-12 my-2">
                                <i class="fa fa-calendar"></i> &nbsp;
                                <span class="text-inter" style="font-size: 12px;">
                                    {{ Carbon::parse($event->start_date)->format('d M,') }}
                                    {{ Carbon::parse($event->start_time)->format('H:i') }} WIB -
                                    {{ Carbon::parse($event->end_date)->format('d M,') }}
                                    {{ Carbon::parse($event->end_time)->format('H:i') }} WIB
                                </span>
                            </div>
                            <div class="col-md-12">
                                <i class="fa fa-user"></i> &nbsp;
                                <span class="text-inter" style="font-size: 12px;">
                                    {{ count($purchase) }} Orang Sudah Mendaftar
                                </span>
                            </div>
                            @if ($event->execution_type == 'offline' || $event->execution_type == 'hybrid')
                                <div class="col-md-12  my-2">
                                    <i class="fa fa-map"></i> &nbsp;
                                    <span id="locationSmall" class="text-inter" style="font-style: normal; font-size: 12px;">
                                        {{-- @php
                                            $location = explode('<p>',$event->location);
                                            $location = explode('</p>',$location[1]);
                                            $location = $location[0];
                                        @endphp --}}
                                        {{-- {{ 'Event '.$event->execution_type.', '.$location }}, Kota {{ $event->city }} --}}
                                        {!!  'Event '.$event->execution_type.', '.$event->location !!}
                                    </span>
                                </div>
                            @else
                                <div class="col-md-12  my-2">
                                    <i class="bi bi-globe2"></i> &nbsp;
                                    <span class="text-inter" style="font-size: 12px;">
                                        Event Online, Kota {{ $event->city }}
                                    </span>
                                </div>
                            @endif
                            {{-- @if (isset($myData))
                                <div class="col-sm-6 mt-4">
                                    <div class="pointer">
                                        <h5 class=""
                                            style="color: #e5214f; /*#EB597B*/ font-weight: 500; font-family: DM_Sans !important; font-style: normal; font-size: 16px;"
                                            onclick="copyLinkEvent()">
                                            <span style="color: #e5214f; /*#EB597B*/">
                                                <i class="fa fa-share-alt" style="font-size: 20px;"></i>
                                            </span>
                                            Bagikan Event
                                        </h5>
                                    </div>
                                </div>
                            @else
                                <div class="col-sm-6 mt-4">
                                    <div class="pointer" disabled>
                                        <h5 class="text-secondary"
                                            style="color: #e5214f; /*#EB597B*/ font-weight: 500; font-family: DM_Sans !important; font-style: normal; font-size: 16px;">
                                            <span style="color: #e5214f; /*#EB597B*/">
                                                <i class="fa fa-share-alt  text-secondary" style="font-size: 20px;"></i>
                                            </span>
                                            Bagikan Event
                                        </h5>
                                    </div>
                                </div>
                            @endif --}}
                            <div class="col-sm-6 mt-2">
                                <div class="pointer">
                                    <h5 class=""
                                        style="color: #e5214f; /*#EB597B*/ font-weight: 500; font-size: 16px;"
                                        onclick="munculPopup('#shareSosmed')">
                                        <span style="color: #e5214f; /*#EB597B*/">
                                            <i class="fa fa-share-alt" style="font-size: 20px;"></i>
                                        </span>
                                        Bagikan Event
                                    </h5>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-2 text-center">
                                <a class="mr-2" href="{{ $event->instagram }}"><i class="fa fa-instagram mr-1"
                                    style="color: grey;"></i></a>
                                <a class="ml-2 mr-2" href="{{ $event->twitter }}"><i class="fa fa-twitter mr-1"
                                    style="color: grey;"></i></a>
                                <a class="ml-2 mr-2" href="{{ $event->facebook }}"><i class="fa fa-facebook mr-1"
                                    style="color: grey; "></i></a>
                                <a class="ml-2" href="{{ $event->website }}"><i class="fa bi bi-globe mr-1"
                                    style="color: grey; "></i></a>
                            </div>
                        </div>
                        <!-- End row inner rigth ticket top -->
                        <div class="row row-no-margin">
                            <div class="col-md-12 rounded-8 bg-putih p-4">
                                {{-- @if (isset($myData))
                                    <!-- <button class="btn bg-primer lebar-100" onclick="munculPopup('#beliTiket')">
                                                                                                    Beli Tiket
                                                                                                </button> -->
                                @else
                                    <!--  <button class="btn bg-primer lebar-100" onclick="munculPopup('#beliTiket2')">
                                                                                                    Beli Tiket
                                                                                                </button> -->
                                @endif --}}
                                <button id="buy-ticket" class="btn bg-primer lebar-100 text-light rounded-pill">
                                    Beli Tiket
                                </button>
                            </div>
                        </div>
                        <!-- End row inner rigth ticket bottom -->
                    </div>
                    <!-- End wrap inner -->
                </div>
                <!-- End col outer rigth -->
            </div>
            <!-- End Outer Row -->
        </div>
        <!-- End inner container -->
    </div>
    <!-- End first Row puter -->

    <div class="">
        <div class="container">
            <div class="row row-no-margin">
                <!-- <div class="col-xl-12 no-pd-l no-pd-r">
                                                                                
                                                                            </div> -->
                <div class="col-md-8">
                    <div class="row my-2">
                        <div class="col">
                            {{-- tab --}}
                            <div class="tab scrollmenu" style="border: none; border-bottom: 1px solid #F0F1F2;">
                                <button class="tab-btn tablinks-event-detail active mr-4 no-pd-l no-pd-r fs-17"
                                    onclick="opentabs(event, 'event-detail', 'Overview')">Overview</button>
                                <button class="tab-btn tablinks-event-detail mr-4 no-pd-l no-pd-r fs-17"
                                    onclick="opentabs(event, 'event-detail', 'Schedule')">Schedule</button>
                                @if (count($event->speakers) > 0)
                                    <button class="tab-btn tablinks-event-detail mr-4 no-pd-l no-pd-r fs-17"
                                    onclick="opentabs(event, 'event-detail', 'Speakers')">
                                        @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
                                        Speakers
                                        @else
                                        Peformers
                                        @endif
                                    </button>
                                @endif
                                @if (count($event->sponsors) > 0)
                                     <button class="tab-btn tablinks-event-detail mr-4 no-pd-l no-pd-r fs-17"
                                    onclick="opentabs(event, 'event-detail', 'Sponsors')">Sponsor</button>
                                @endif
                                @if (count($event->exhibitors) > 0)
                                <button class="tab-btn tablinks-event-detail mr-4 no-pd-l no-pd-r fs-17"
                                    onclick="opentabs(event, 'event-detail', 'Booths')">Booths</button>
                                @endif
                                @if (count($event->media_partners) > 0)
                                <button class="tab-btn tablinks-event-detail mr-4 no-pd-l no-pd-r fs-17"
                                    onclick="opentabs(event, 'event-detail', 'Media')">Media Partners</button>
                                @endif
                                <button id="tab-tickets" class="tab-btn tablinks-event-detail mr-4 no-pd-l no-pd-r fs-17"
                                    onclick="opentabs(event, 'event-detail', 'Tickets')">Tickets</button>
                            </div>
                            {{-- end tab --}}

                        </div>
                    </div>
                    <div id="Overview" class="tabcontent-event-detail mt-4 ml-3 mb-5" style="display: block; border: none;">
                        <div>
                            {!! $event->description !!}
                        </div>
                        
                        @if (count($schedule) > 0)
                            <h4 class="teks-tebal mt-5 mb-4 fs-16" style="color: #304156;">
                                <span style="color: #304156;">
                                    <i class="fa fa-calendar fs-18" style="font-size: 24px;"></i>
                                </span>
                                Schedule
                            </h4>
                            <div class="row my-2">
                                <div class="col">
                                    {{-- tab schedule --}}
                                    <div class="tab scrollmenu" style="border: none; border-bottom: 1px solid #F0F1F2;">
                                        @php
                                            $i = 0;
                                        @endphp
                                        @foreach ($schedule as $key => $value)
                                            
                                            @foreach ($value->groupBy('end_date') as $key2 => $value2)
                                                @if ($i == 0)
                                                    <button class="tab-btn tablinks-schedule active mr-4 fs-15"
                                                        onclick="opentabs(event, 'schedule', '{{ $key.'-'.$key2 }}')">
                                                        {{ Carbon::parse($key)->format('d M Y') }} - {{ Carbon::parse($key2)->format('d M Y') }}
                                                    </button>
                                                @else
                                                    <button class="tab-btn tablinks-schedule mr-4 fs-15"
                                                        onclick="opentabs(event, 'schedule', '{{ $key.'-'.$key2 }}')">
                                                        {{ Carbon::parse($key)->format('d M Y') }} - {{ Carbon::parse($key2)->format('d M Y') }}
                                                    </button>
                                                @endif

                                                @php
                                                    $i++;
                                                @endphp
                                            @endforeach
                                            {{-- @dump($value->groupBy('end_date')) --}}
                                        @endforeach

                                        @php
                                            $j = 0;
                                        @endphp
                                    </div>
                                    <div>
                                        @foreach ($schedule as $key => $value)
                                            @foreach ($value->groupBy('end_date') as $key2 => $value2)
                                                @if ($j == 0)
                                                    <div id="{{ $key.'-'.$key2 }}"
                                                        class="tabcontent-schedule mt-4 lebar-100 fs-15"
                                                        style="display: block; border: none;">

                                                        <table class="table table-striped">
                                                            <tbody>
                                                                @foreach ($value2 as $val)
                                                                    <tr>
                                                                        <td>
                                                                            <b>{{ $val->name }}</b>
                                                                            <br><br>
                                                                            {{ $val->start_time }} - {{ $val->end_time }}
                                                                        </td>
                                                                        <td>
                                                                            <b>{{ $val->title }}</b> 
                                                                            <br>
                                                                            {!! $val->description !!}
                                                                            <br>
                                                                        </td>
                                                                        <td>
                                                                            <div class="row pt-3 pb-3">
                                                                                @foreach ($val->sessionspeakers as $sessionSpeaker)
                                                                                    <div class="col-xl-12 text-center">
                                                                                        <div>
                                                                                            <img src="{{ asset('storage/event_assets/' . $event->slug . '/speaker_photos/' . $sessionSpeaker->speaker->photo) }}"
                                                                                            style="width: 50px; height: 50px; border-radius:50%; margin: auto;">
                                                                                        </div>
                                                                                        <p class="text-center mb-0 mt-2 fw-bold">
                                                                                            {{ $sessionSpeaker->speaker->name }}
                                                                                        </p>
                                                                                        <p class="text-center" style="font-size: 10pt !important">
                                                                                            {{ $sessionSpeaker->speaker->company }} | {{ $sessionSpeaker->speaker->job }}
                                                                                        </p>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                @else
                                                    <div id="{{ $key.'-'.$key2 }}"
                                                        class="tabcontent-schedule mt-4 lebar-100 fs-15"
                                                        style="display: none; border: none;">

                                                        <table class="table table-striped">
                                                            <tbody>
                                                                @foreach ($value2 as $val)
                                                                    <tr>
                                                                        <td>
                                                                            <b>{{ $val->name }}</b>
                                                                            <br><br>
                                                                            {{ $val->start_time }} - {{ $val->end_time }}
                                                                        </td>
                                                                        <td>
                                                                            <b>{{ $val->title }}</b>
                                                                            <br>
                                                                            {!! $val->description !!}
                                                                            <br>
                                                                            <div class="row pd-tbl">
                                                                                @foreach ($val->sessionspeakers as $sessionSpeaker)
                                                                                    <div class="col-xl-5 square w-100-i text-inner-tbl no-pd-l"
                                                                                        style="color: #304156; height:30px; margin-right:20px;">
                                                                                        <img src="{{ asset('storage/event_assets/' . $event->slug . '/speaker_photos/' . $sessionSpeaker->speaker->photo) }}"
                                                                                            style="width: 25px; height: 25px; border-radius:50%; float: left; margin-top: 1px; margin-left: 3px;">
                                                                                        <p class="p-1">
                                                                                            {{ $sessionSpeaker->speaker->name }}
                                                                                        </p>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>

                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                @endif

                                                @php
                                                    $j++;
                                                @endphp
                                            @endforeach
                                        @endforeach
                                    </div>
                                    {{-- end tab --}}
                                </div>
                            </div>
                        @endif

                        @if (count($event->speakers) > 0)
                            <h4 class="teks-tebal mt-5 mb-4 fs-16" style="color: #304156;">
                                <span style="color: #304156;">
                                    <i class="fa fa-microphone fs-18" style="font-size: 24px;"></i>
                                </span>
                                @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
                                Speakers
                                @else
                                Peformers
                                @endif
                            </h4>

                            <div class="row">
                                @foreach ($event->speakers as $speaker)
                                    <div class="col-xl-6 mb-4">
                                        <div class="square-speaker">
                                            <div class="d-flex">

                                                <div>
                                                    <img src="{{ asset('storage/event_assets/' . $event->slug . '/speaker_photos/' . $speaker->photo) }}"
                                                        class="speaker-icon">
                                                </div>
                                                <div class="speaker-text">
                                                    <div class="teks-tebal fs-15">
                                                        {{ $speaker->name }}
                                                        <p class="speaker-detail fs-15">
                                                            {{ $speaker->email }}
                                                            <br>
                                                            {{ $speaker->company }}
                                                            <br>
                                                            {{ $speaker->job }}
                                                        </p>
                                                    </div>
                                                    <div class="teks-tipis fs-15" style="color: #C4C4C4;">
                                                        {{ $speaker->title }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12" style="margin-top: 10%;">
                                                <a href="{{ $speaker->website }}"><i class="fa fa-envelope mr-1"
                                                        style="color: grey;"></i></a>
                                                <a href="{{ $speaker->linked }}"><i class="fab fa-linkedin mr-1"
                                                        style="color: grey;"></i></a>
                                                <a href="{{ $speaker->instagram }}"><i class="fab fa-instagram mr-1"
                                                        style="color: grey;"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if (count($event->sponsors) > 0)
                            <br />
                            <h4 class="teks-tebal mt-5 mb-4 fs-16" style="color: #304156;">
                                <span style="color: #304156;">
                                    <i class="fa fa-handshake fs-18" style="font-size: 24px;"></i>
                                </span>
                                Sponsor
                            </h4>
                            <div class="row">
                                @foreach ($event->sponsors->sortBy('priority') as $sponsor)
                                    <div class="col-xl-4 mt-4">
                                        <div class="">
                                            <div class="bg-putih rounded-15 bayangan-5">
                                                <div class="label bg-primer lable-title fs-15" style="font-size: 11px !important;">{{ $sponsor->type }}</div>
                                                <div class="smallPadding">
                                                    <div class="wrap">
                                                        <div class="row">
                                                            {{-- <div class="col-xl-4 no-pd-r mx-wd-90">
                                                                <div class="logoCard"
                                                                    bg-image="{{ asset('storage/event_assets/' . $event->slug . '/sponsor_logo/' . $sponsor->logo) }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-8">
                                                                <h3 class="sponsor-title mt-2 fs-15">
                                                                    {{ $sponsor->name }}</h3>
                                                            </div> --}}
                                                            <div class="col-12">
                                                                <div class="w-100 asp-rt-5-2"
                                                                    bg-image="{{ asset('storage/event_assets/' . $event->slug . '/sponsor_logo/' . $sponsor->logo) }}" object-fit="contain">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class=" mt-2">
                                                            <a href="{{ $sponsor->website }}" target="_blank"
                                                                class="teks-primer fs-15">
                                                                See About Sponsor
                                                            </a>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if (count($event->media_partners) > 0)
                            <br />
                            <h4 class="teks-tebal mt-5 mb-4 fs-16" style="color: #304156;">
                                <span style="color: #304156;">
                                    <i class="fa fa-tv" style="font-size: 24px;"></i>
                                </span>
                                Media Partner
                            </h4>
                            <div class="row">
                                @foreach ($event->media_partners as $media_partner)
                                    <div class="col-xl-4 mt-4">
                                        <div class="">
                                            <div class="bg-putih rounded-15 bayangan-5">
    
                                                <div class="label bg-primer lable-title">{{ $media_partner->type }}</div>
    
    
    
                                                <div class="smallPadding">
                                                    <div class="wrap">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="w-100 asp-rt-5-2"
                                                                    bg-image="{{ asset('storage/event_assets/' . $event->slug . '/media_logo/' . $media_partner->logo) }}" object-fit="contain">
                                                                </div>
                                                            </div>
                                                            {{-- <div class="col-xl-8">
                                                                <h3 class="sponsor-title mt-2">{{ $media_partner->name }}</h3>
                                                            </div> --}}
                                                        </div>
                                                        <div class=" mt-2">
                                                            <a href="{{ $media_partner->website }}" target="_blank"
                                                                class="teks-primer">
                                                                See About Media
                                                            </a>
                                                        </div>
    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if (count($event->exhibitors) > 0)
                            <br />
                            {{-- tempat icon booths --}}

                            <h4 class="teks-tebal mt-5 mb-4 fs-16" style="color: #304156;">
                                <span style="color: #304156;">
                                    <i class="fa fa-chalkboard-teacher fs-18" style="font-size: 24px;"></i>
                                </span>
                                Exhibitors
                            </h4>
                            <div class="row">
                                {{-- @dd($event->exhibitors) --}}
                                @if (count($exhibitors) == 0)
                                    <div class="col-12">
                                        <p>
                                            << Semua Exhibitor tidak ditampilkan di event overview ini>>
                                        </p>
                                    </div>
                                @endif
                                @foreach ($exhibitors as $exhibitor)
                                    @if ($exhibitor->overview == 1)
                                        <div class="col-xl-4" style="margin-top:2%;">
                                            <a href="{{ route('user.home.exhibitions', [$event->id, $exhibitor->id]) }}">
                                                <div class="bg-putih">
                                                    <div class="rounded-15 bayangan-5 bg-exihibitor"
                                                        bg-image="{{ asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_booth_image/' . $exhibitor->booth_image) }}">
                                                    </div>
                                                    <div class="wrap">

                                                        <img src="{{ asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_logo/' . $exhibitor->logo) }}"
                                                            class="rounded-circle asp-rt-1-1 mt-4" width="58px"
                                                            height="58px">


                                                        <div class="title-exhibitor pt-3 ml-1 fs-15">
                                                            {{ $exhibitor->name }}
                                                        </div>
                                                        <div
                                                            style="padding-left: 10px; margin-top:-1%; color: #C4C4C4; font-size:10pt;">

                                                        </div>
                                                        <div class="teks-primer font-info mt-2 ml-2 pb-4 fs-15">
                                                            @if ($exhibitor->virtual_booth == 1)
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
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                                
                            </div>
                            <div>
                                <a href="{{ route('user.home.exhibitions', [$event->id, 0]) }}"
                                    class="mt-3 btn btn-outline-primer teks-primer rounded-5">Lihat Semua Exhibitor</a>
                            </div>
                        @endif

                    </div>
                    <div id="Schedule" class="tabcontent-event-detail mt-4 ml-4 mb-5" style="display: none; border: none;">
                        <h4 class="teks-tebal mt-5 mb-4 fs-16" style="color: #304156;">
                            <span style="color: #304156;">
                                <i class="fa fa-calendar fs-18" style="font-size: 24px;"></i>
                            </span>
                            Schedule
                        </h4>
                        <div class="row my-2">
                            <div class="col">
                                {{-- tab schedule --}}
                                <div class="tab scrollmenu" style="border: none; border-bottom: 1px solid #F0F1F2;">
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($schedule as $key => $value)
                                        
                                        @foreach ($value->groupBy('end_date') as $key2 => $value2)
                                            @if ($i == 0)
                                                <button class="tab-btn tablinks-schedule-sc active mr-4 fs-15"
                                                    onclick="opentabs(event, 'schedule-sc', '{{ $key.'-'.$key2.'-sc' }}')">
                                                    {{ Carbon::parse($key)->format('d M Y') }} - {{ Carbon::parse($key2)->format('d M Y') }}
                                                </button>
                                            @else
                                                <button class="tab-btn tablinks-schedule-sc mr-4 fs-15"
                                                    onclick="opentabs(event, 'schedule-sc', '{{ $key.'-'.$key2.'-sc' }}')">
                                                    {{ Carbon::parse($key)->format('d M Y') }} - {{ Carbon::parse($key2)->format('d M Y') }}
                                                </button>
                                            @endif

                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                        {{-- @dump($value->groupBy('end_date')) --}}
                                    @endforeach

                                    @php
                                        $j = 0;
                                    @endphp
                                </div>
                                <div>
                                    @foreach ($schedule as $key => $value)
                                        @foreach ($value->groupBy('end_date') as $key2 => $value2)
                                            @if ($j == 0)
                                                <div id="{{ $key.'-'.$key2.'-sc' }}"
                                                    class="tabcontent-schedule-sc mt-4 lebar-100 fs-15"
                                                    style="display: block; border: none;">

                                                    <table class="table table-striped">
                                                        <tbody>
                                                            @foreach ($value2 as $val)
                                                                <tr>
                                                                    <td>
                                                                        <b>{{ $val->name }}</b>
                                                                        <br><br>
                                                                        {{ $val->start_time }} - {{ $val->end_time }}
                                                                    </td>
                                                                    <td>
                                                                        <b>{{ $val->title }}</b>
                                                                        <br>
                                                                        {!! $val->description !!}
                                                                        <br>
                                                                        <div class="row pd-tbl">
                                                                            @foreach ($val->sessionspeakers as $sessionSpeaker)
                                                                                <div class="col-xl-5 square w-100-i text-inner-tbl no-pd-l"
                                                                                    style="color: #304156; height:30px; margin-right:20px;">
                                                                                    <img src="{{ asset('storage/event_assets/' . $event->slug . '/speaker_photos/' . $sessionSpeaker->speaker->photo) }}"
                                                                                        style="width: 25px; height: 25px; border-radius:50%; float: left; margin-top: 1px; margin-left: 3px;">
                                                                                    <p class="p-1">
                                                                                        {{ $sessionSpeaker->speaker->name }}
                                                                                    </p>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>

                                                </div>
                                            @else
                                                <div id="{{ $key.'-'.$key2.'-sc' }}"
                                                    class="tabcontent-schedule-sc mt-4 lebar-100 fs-15"
                                                    style="display: none; border: none;">

                                                    <table class="table table-striped">
                                                        <tbody>
                                                            @foreach ($value2 as $val)
                                                                <tr>
                                                                    <td>
                                                                        <b>{{ $val->name }}</b>
                                                                        <br><br>
                                                                        {{ $val->start_time }} - {{ $val->end_time }}
                                                                    </td>
                                                                    <td>
                                                                        <b>{{ $val->title }}</b>
                                                                        <br>
                                                                        {!! $val->description !!}
                                                                        <br>
                                                                        <div class="row pd-tbl">
                                                                            @foreach ($val->sessionspeakers as $sessionSpeaker)
                                                                                <div class="col-xl-5 square w-100-i text-inner-tbl no-pd-l"
                                                                                    style="color: #304156; height:30px; margin-right:20px;">
                                                                                    <img src="{{ asset('storage/event_assets/' . $event->slug . '/speaker_photos/' . $sessionSpeaker->speaker->photo) }}"
                                                                                        style="width: 25px; height: 25px; border-radius:50%; float: left; margin-top: 1px; margin-left: 3px;">
                                                                                    <p class="p-1">
                                                                                        {{ $sessionSpeaker->speaker->name }}
                                                                                    </p>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>

                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>

                                                </div>
                                            @endif

                                            @php
                                                $j++;
                                            @endphp
                                        @endforeach
                                    @endforeach
                                </div>
                                {{-- end tab --}}
                            </div>
                        </div>
                    </div>
                    <div id="Speakers" class="tabcontent-event-detail ml-4 mt-4 mb-5" style="display: none; border: none;">
                        <h4 class="teks-tebal mt-5 mb-4 fs-16" style="color: #304156;">
                            <span style="color: #304156;">
                                <i class="fa fa-microphone fs-18" style="font-size: 24px;"></i>
                            </span>
                            @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
                            Speakers
                            @else
                            Peformers
                            @endif
                        </h4>

                        <div class="row">
                            @foreach ($event->speakers as $speaker)
                                <div class="col-xl-6 mb-4">
                                    <div class="square-speaker">
                                        <div class="d-flex">

                                            <div>
                                                <img src="{{ asset('storage/event_assets/' . $event->slug . '/speaker_photos/' . $speaker->photo) }}"
                                                    class="speaker-icon">
                                            </div>
                                            <div class="speaker-text">
                                                <div class="teks-tebal fs-15">
                                                    {{ $speaker->name }}
                                                    <p class="speaker-detail fs-15">
                                                        {{ $speaker->email }}
                                                        <br>
                                                        {{ $speaker->company }}
                                                        <br>
                                                        {{ $speaker->job }}
                                                    </p>
                                                </div>
                                                <div class="teks-tipis fs-15" style="color: #C4C4C4;">
                                                    {{ $speaker->title }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="margin-top: 10%;">
                                            <a href="{{ $speaker->website }}"><i class="fa fa-envelope mr-1"
                                                    style="color: grey;"></i></a>
                                            <a href="{{ $speaker->linked }}"><i class="fab fa-linkedin mr-1"
                                                    style="color: grey;"></i></a>
                                            <a href="{{ $speaker->instagram }}"><i class="fab fa-instagram mr-1"
                                                    style="color: grey;"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div id="Sponsors" class="tabcontent-event-detail ml-4 mt-4 mb-5" style="display: none; border: none;">
                        <h4 class="teks-tebal mt-5 mb-4 fs-16" style="color: #304156;">
                            <span style="color: #304156;">
                                <i class="fa fa-handshake" style="font-size: 24px;"></i>
                            </span>
                            Sponsor
                        </h4>
                        <div class="row">
                            @foreach ($event->sponsors->sortBy('priority') as $sponsor)
                                <div class="col-xl-4 mt-4">
                                    <div class="">
                                        <div class="bg-putih rounded-15 bayangan-5">

                                            <div class="label bg-primer lable-title">{{ $sponsor->type }}</div>



                                            <div class="smallPadding">
                                                <div class="wrap">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="w-100 asp-rt-5-2"
                                                                bg-image="{{ asset('storage/event_assets/' . $event->slug . '/sponsor_logo/' . $sponsor->logo) }}" object-fit="contain">
                                                            </div>
                                                        </div>
                                                        {{-- <div class="col-xl-8">
                                                            <h3 class="sponsor-title mt-2">{{ $sponsor->name }}</h3>
                                                        </div> --}}
                                                    </div>
                                                    <div class=" mt-2">
                                                        <a href="{{ $sponsor->website }}" target="_blank"
                                                            class="teks-primer">
                                                            See About Sponsor
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div id="Media" class="tabcontent-event-detail ml-4 mt-4 mb-5" style="display: none; border: none;">
                        <h4 class="teks-tebal mt-5 mb-4 fs-16" style="color: #304156;">
                            <span style="color: #304156;">
                                <i class="fa fa-tv" style="font-size: 24px;"></i>
                            </span>
                            Media Partner
                        </h4>
                        <div class="row">
                            @foreach ($event->media_partners as $media_partner)
                                <div class="col-xl-4 mt-4">
                                    <div class="">
                                        <div class="bg-putih rounded-15 bayangan-5">

                                            <div class="label bg-primer lable-title">{{ $media_partner->type }}</div>



                                            <div class="smallPadding">
                                                <div class="wrap">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="w-100 asp-rt-5-2"
                                                                bg-image="{{ asset('storage/event_assets/' . $event->slug . '/media_logo/' . $media_partner->logo) }}" object-fit="contain">
                                                            </div>
                                                        </div>
                                                        {{-- <div class="col-xl-8">
                                                            <h3 class="sponsor-title mt-2">{{ $media_partner->name }}</h3>
                                                        </div> --}}
                                                    </div>
                                                    <div class=" mt-2">
                                                        <a href="{{ $media_partner->website }}" target="_blank"
                                                            class="teks-primer">
                                                            See About Sponsor
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div id="Booths" class="tabcontent-event-detail mb-5 ml-3" style="display: none; border: none;">
                        <h4 class="teks-tebal mt-5 mb-4" style="color: #304156;">
                            <span style="color: #304156;">
                                <i class="fa fa-chalkboard-teacher" style="font-size: 24px;"></i>
                            </span>
                            Exhibitors
                        </h4>
                        <div class="row">
                            @if (count($event->exhibitors) > 0)
                                @if (count($exhibitors) == 0)
                                    <div class="col-12">
                                        <p>
                                            << Semua Exhibitor tidak ditampilkan di event overview ini>>
                                        </p>
                                    </div>
                                @endif
                                @foreach ($event->exhibitors as $exhibitor)
                                    @if ($exhibitor->overview == 1)
                                        <div class="col-xl-4" style="margin-top:2%;">
                                            <a href="{{ route('user.home.exhibitions', [$event->id, $exhibitor->id]) }}">
                                                <div class="bg-putih">
                                                    <div class="rounded-15 bayangan-5 bg-exihibitor"
                                                        bg-image="{{ asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_booth_image/' . $exhibitor->booth_image) }}">
                                                    </div>
                                                    <div class="wrap">

                                                        <img src="{{ asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_logo/' . $exhibitor->logo) }}"
                                                            class="rounded-circle asp-rt-1-1 mt-4" width="58px"
                                                            height="58px">


                                                        <div class="title-exhibitor pt-3 ml-1">
                                                            {{ $exhibitor->name }}
                                                        </div>
                                                        <div
                                                            style="padding-left: 10px; margin-top:-1%; color: #C4C4C4; font-size:10pt;">

                                                        </div>
                                                        <div class="teks-primer font-info mt-2 ml-2 pb-4">
                                                            @if ($exhibitor->virtual_booth == 1)
                                                                <i class="fa fa-check pr-2 teks-primer"
                                                                    aria-hidden="true"></i>Virtual Booth<span></span>
                                                            @else
                                                                <i class="fas fa-times pr-2 teks-primer"
                                                                    aria-hidden="true"></i>Nothing Virtual
                                                                Booth<span></span>
                                                            @endif

                                                        </div>

                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                                <a href="{{ route('user.home.exhibitions', [$event->id, 0]) }}"
                                    class="mt-3 btn btn-outline-primer teks-primer rounded-5">Lihat Semua Exhibitor</a>
                            @else
                                <p>Exhibitor tidak tersedia untuk event ini</p>
                            @endif

                        </div>
                    </div>
                    <div id="Tickets" class="tabcontent-event-detail mb-5 ml-3" style="display: none; border: none;">
                        @php
                            $countTicket = 0;
                        @endphp
                        <form id="form-buy-tickets" action="{{ route('user.pembelian', $event->slug) }}" method="POST">
                            @csrf
                            @foreach ($event->sessions as $session)
                                @foreach ($session->tickets as $ticket)
                                    @if ($ticket->deleted == 0)
                                        <div class="col-md-12 pl-0 my-2 pr-0">
                                            @if ($ticket->quantity > 0 && $ticket->start_date <= $tanggalSekarang && $ticket->end_date >= $tanggalSekarang)
                                                <div class="btn-group-toggle" data-toggle="buttons" style="z-index: auto">
                                                    <label class="btn btn-outline-primer lebar-100 text-left rounded-15 p-4" style="white-space: normal">
                                                        <input type="checkbox" autocomplete="off" name="breakdowns[]"
                                                            id="{{ $ticket->id }}" value="{{ $ticket->id }}">
                                                        
                                                        <div class="row">
                                                            <div class="col-1 no-pd-r" style="min-width: 45px; max-width: 45px;">
                                                                <span style="color: #e5214f; /*#EB597B*/">
                                                                    <i class="fa fa-tags" style="font-size: 20px;"></i>
                                                                </span>
                                                            </div>
                                                            <div class="col-11 no-pd-l" style="width: calc(100% - 50px); flex: unset">
                                                                <h1 style="font-size: 18px; color:black;">
                                                                    <b>
                                                                        {{ $ticket->name }}
                                                                    </b>
                                                                </h1>
                                                            </div>
                                                        </div>
    
                                                        <h3 class="ml-4" style="font-size: 15px; color:#e5214f; /*#EB597B*/"><b>
                                                                @if ($ticket->price == 0)
                                                                    Gratis
                                                                @else
                                                                    @if ($ticket->type_price == 1)
                                                                        @currencyEncode($ticket->price)
                                                                    @elseif($ticket->type_price == 2)
                                                                        <p class="mb-0 mt-0"
                                                                            style="font-size:12px !important;margin-top:10px;">
                                                                            Bayar Sesukamu Minimal :
                                                                        </p>
                                                                        @currencyEncode($ticket->price)
                                                                    @endif
                                                                @endif
                                                            </b></h3>
    
                                                        <hr class="garis-tiket mt-3" style="color: lightgray;" />
                                                        <p class="mb-0"
                                                            style="color:#828D99;font-size:12px !important;margin-top:10px;">
                                                            {{ $ticket->description }}
                                                        </p>
                                                    </label>
                                                </div>
    
    
                                                <!-- ================================================================== -->
                                            @elseif($ticket->end_date < $tanggalSekarang)
                                                <div class="btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn btn-outline-primer lebar-100 text-left rounded-15 p-4" style="white-space: normal">
                                                        <div class="row">
                                                            <div class="col-1 no-pd-r" style="min-width: 45px;  max-width: 45px;">
                                                                <span style="color: #e5214f; /*#EB597B*/">
                                                                    <i class="fa fa-tags" style="font-size: 20px;"></i>
                                                                </span>
                                                            </div>
                                                            <div class="col-11 no-pd-l" style="width: calc(100% - 50px); flex: unset">
                                                                <h1 style="font-size: 18px; color:black;">
                                                                    <b>
                                                                        {{ $ticket->name }}
                                                                    </b>
                                                                </h1>
                                                            </div>
                                                        </div>
    
                                                        <h3 class="ml-4" style="font-size: 15px; color:#e5214f; /*#EB597B*/">
                                                            <b>
                                                                Penjualan Telah Berakhir
                                                            </b>
                                                        </h3>
                                                    </label>
                                                </div>
                                            @elseif($ticket->quantity == 0)
                                                <div class="btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn btn-outline-primer lebar-100 text-left rounded-15 p-4" style="white-space: normal">
                                                        <div class="row">
                                                            <div class="col-1 no-pd-r" style="min-width: 45px;  max-width: 45px;">
                                                                <span style="color: #e5214f; /*#EB597B*/">
                                                                    <i class="fa fa-tags" style="font-size: 20px;"></i>
                                                                </span>
                                                            </div>
                                                            <div class="col-11 no-pd-l" style="width: calc(100% - 50px); flex: unset">
                                                                <h1 style="font-size: 18px; color:black;">
                                                                    <b>
                                                                        {{ $ticket->name }}
                                                                    </b>
                                                                </h1>
                                                            </div>
                                                        </div>
    
                                                        <h3 class="ml-4" style="font-size: 15px; color:#e5214f; /*#EB597B*/"><b>
                                                                Maaf Tiket Habis
                                                            </b>
                                                        </h3>
                                                    </label>
                                                </div>
                                            @else
                                                <div class="btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn btn-outline-primer lebar-100 text-left rounded-15 p-4" style="white-space: normal">
                                                        <div class="row">
                                                            <div class="col-1 no-pd-r" style="min-width: 45px;  max-width: 45px;">
                                                                <span style="color: #e5214f; /*#EB597B*/">
                                                                    <i class="fa fa-tags" style="font-size: 20px;"></i>
                                                                </span>
                                                            </div>
                                                            <div class="col-11 no-pd-l" style="width: calc(100% - 50px); flex: unset">
                                                                <h1 style="font-size: 18px; color:black;">
                                                                    <b>
                                                                        {{ $ticket->name }}
                                                                    </b>
                                                                </h1>
                                                            </div>
                                                        </div>
    
                                                        <h3 class="ml-4" style="font-size: 15px; color:#e5214f; /*#EB597B*/"><b>
                                                                Tiket belum tersedia
                                                            </b>
                                                        </h3>
                                                    </label>
                                                </div>
                                            @endif
    
                                            <!-- ================================================================== -->
    
                                        </div>
                                        @php
                                            $countTicket += 1;
                                        @endphp
                                    @endif
                                @endforeach
                            @endforeach
                            
                            @if ($countTicket <= 0)
                                <h5 class="text-center mt-4">
                                    Ticket masih belum tersedia
                                </h6>
                            @endif
                        </form>
                        <button class="btn bg-primer lebar-100 mt-3 text-light" onclick="select('#buy-ticket').click()">
                            Beli Tiket
                        </button>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="row row-no-margin">
                        <div class="col-12 no-pd">
                            <a href="{{ route('user.organizationDetail', $event->organizer->slug) }}">
                                <div class="row row-no-margin border py-3 pl-3 mb-3 ml-4 mt-5" style="border-radius: 14px;">
                                    <div class="row d-flex align-items-center pl-3 pr-3">
                                        <div class="col-md-3 no-pd-l no-pd-r mr-4">
                                            <img src="{{ $event->organizer->logo == ''? asset('images/profile-user.png'): asset('storage/organization_logo/' . $event->organizer->logo) }}"
                                                class="rounded-circle asp-rt-1-1 rsps-profile mb-2" width="100%" height="100%">
                                        </div>
                                        <div class="col-md-7">
                                            <div class="row">
                                                <p style="color: #828D99; margin-bottom: 0px;">Diselenggarakan oleh</p>
                                            </div>
                                            <div class="row">
                                                <h4 class="teks-tebal fs-17" style="color: black;">
                                                    {{ $event->organizer->name }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row pl-3 mr-3">
                                        <div class="col-md-12 mt-4 no-pd-l">
                                            <p style="color: #828D99;">{{ $event->organizer->description }}</p>
                                        </div>
                                        <div class="col-md-12 no-pd-l">
                                            <a href="{{ $event->organizer->website }}"><i class="fa fa-envelope mr-1"
                                                    style="color: grey;"></i></a>
                                            <a href="{{ $event->organizer->linked }}"><i class="fab fa-linkedin mr-1"
                                                    style="color: grey;"></i></a>
                                            <a href="{{ $event->organizer->instagram }}"><i class="fab fa-instagram mr-1"
                                                    style="color: grey; "></i></a>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <a href="{{ route('user.organizationDetail', $event->organizer->slug) }}" class="teks-primer"><b>See More Details</b></a>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        @if (count($event->receptionists) > 0)
                            <div class="col-12 no-pd">
                                <div class="row row-no-margin border py-3 pl-3 mb-3 ml-4" style="border-radius: 14px;">
                                    <div class="row d-flex align-items-center pl-3 pr-3">
                                        <div class="col-md-12 no-pd-l no-pd-r mr-4 mb-2">
                                            <h4 class="teks-tebal fs-17" style="color: black;">Contact Person</h4>
                                        </div>
                                        @foreach ($event->receptionists as $receptionist)
                                            <div class="col-md-2 no-pd-l no-pd-r mr-4">
                                                @if ($receptionist->user->photo == 'default')
                                                    <img class="rounded-circle asp-rt-1-1 mb-2" width="47px" height="47px"
                                                        src="{{ asset('storage/profile_photos/profile-user.png') }}">
                                                @else
                                                    <img class="rounded-circle asp-rt-1-1 mb-2" width="47px" height="47px"
                                                        src="{{ asset('storage/profile_photos/' . $receptionist->user->photo) }}">
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
                                        @endforeach

                                    </div>

                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row second outer -->

        <!-- Pop Up dialog -->

        <div class="popupWrapper" id="beliTiket2">

            <div class="popup border py-3 pl-3 mb-3" style="border-radius: 14px;">
                <i class="fa fa-close op-5 ke-kanan pointer close-icon" onclick="hilangPopup('#beliTiket2')"></i>
                <div class="wrap rata-tengah">

                    Kamu Belum Login
                    <br>
                    <hr>
                    <a href="{{ route('user.login') }}">
                        <button class="btn bg-primer lebar-50 rounded-15">
                            Login Sekarang
                        </button>
                    </a>
                    <br>
                    <br>
                    ----- Atau -----
                    <br>
                    <br>
                    <a href="{{ route('user.register') }}">Buat Akun Baru</a>

                </div>
            </div>
        </div>

        <!-- Pop Up beli ticket -->
        {{-- <div class="bg"></div>
        <div class="popupWrapper" id="beliTiket">

            <div class="popup border py-3 pl-3 mb-3" style="border-radius: 14px;">
                <i class="fa fa-close op-5 ke-kanan pointer close-icon" onclick="hilangPopup('#beliTiket')"></i>
                <div class="wrap">
                    <div class="col-md-12 mb-2">
                        <h4 class="teks-tebal rata-tengah mb-4">
                            Tickets
                        </h4>
                    </div>
                    <form action="{{ route('user.pembelian', $event->slug) }}" method="POST">
                        @csrf
                        @foreach ($event->sessions as $session)
                            @foreach ($session->tickets as $ticket)
                                @if ($ticket->deleted == 0)
                                    <div class="col-md-12 pl-0 my-2">
                                        @if ($ticket->quantity > 0 && $ticket->start_date <= $tanggalSekarang && $ticket->end_date >= $tanggalSekarang)
                                            <div class="btn-group-toggle" data-toggle="buttons" style="z-index: auto">
                                                <label class="btn btn-outline-primer lebar-100 text-left rounded-15 p-4">
                                                    <input type="checkbox" autocomplete="off" name="breakdowns[]"
                                                        id="{{ $ticket->id }}" value="{{ $ticket->id }}">

                                                    <h1 style="font-size: 18px; color:black; text-transform: uppercase;">
                                                        <b>
                                                            <span style="color: #e5214f; /*#EB597B*/">
                                                                <i class="fa fa-tags" style="font-size: 20px;"></i>
                                                            </span>
                                                            &nbsp;
                                                            {{ $ticket->name }}
                                                        </b>
                                                    </h1>

                                                    <h3 class="ml-4" style="font-size: 15px; color:#e5214f; /*#EB597B*/"><b>
                                                            @if ($ticket->price == 0)
                                                                Gratis
                                                            @else
                                                                @if ($ticket->type_price == 1)
                                                                    @currencyEncode($ticket->price)
                                                                @elseif($ticket->type_price == 2)
                                                                    <p class="mb-0 mt-0"
                                                                        style="font-size:12px !important;margin-top:10px;">
                                                                        Bayar Sesukamu Minimal :
                                                                    </p>
                                                                    @currencyEncode($ticket->price)
                                                                @endif
                                                            @endif
                                                        </b></h3>

                                                    <hr class="garis-tiket mt-3" style="color: lightgray;" />
                                                    <p class="mb-0"
                                                        style="color:#828D99;font-size:12px !important;margin-top:10px;">
                                                        {{ $ticket->description }}
                                                    </p>
                                                </label>
                                            </div>


                                            <!-- ================================================================== -->
                                        @elseif($ticket->end_date < $tanggalSekarang)
                                            <div class="btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-outline-primer lebar-100 text-left rounded-15 p-4">
                                                    <h1 class="ml-4"
                                                        style="font-size: 18px; color:black; text-transform: uppercase;">
                                                        <b>
                                                            <span style="color: #e5214f; /*#EB597B*/">
                                                                <i class="fa fa-tags" style="font-size: 20px;"></i>
                                                            </span>
                                                            &nbsp;
                                                            {{ $ticket->name }}
                                                        </b>
                                                    </h1>

                                                    <h3 class="ml-4" style="font-size: 15px; color:#e5214f; /*#EB597B*/">
                                                        <b>
                                                            Penjualan Telah Berakhir
                                                        </b>
                                                    </h3>
                                                </label>
                                            </div>
                                        @elseif($ticket->quantity == 0)
                                            <div class="btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-outline-primer lebar-100 text-left rounded-15 p-4">
                                                    <h1 class="ml-4"
                                                        style="font-size: 18px; color:black; text-transform: uppercase;">
                                                        <b>
                                                            <span style="color: #e5214f; /*#EB597B*/">
                                                                <i class="fa fa-tags" style="font-size: 20px;"></i>
                                                            </span>
                                                            &nbsp;
                                                            {{ $ticket->name }}
                                                        </b>
                                                    </h1>

                                                    <h3 class="ml-4" style="font-size: 15px; color:#e5214f; /*#EB597B*/"><b>
                                                            Maaf Tiket Habis
                                                        </b>
                                                    </h3>
                                                </label>
                                            </div>
                                        @else
                                            <div class="btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-outline-primer lebar-100 text-left rounded-15 p-4">
                                                    <h1 class="ml-4"
                                                        style="font-size: 18px; color:black; text-transform: uppercase;">
                                                        <b>
                                                            <span style="color: #e5214f; /*#EB597B*/">
                                                                <i class="fa fa-tags" style="font-size: 20px;"></i>
                                                            </span>
                                                            &nbsp;
                                                            {{ $ticket->name }}
                                                        </b>
                                                    </h1>

                                                    <h3 class="ml-4" style="font-size: 15px; color:#e5214f; /*#EB597B*/"><b>
                                                            Tiket belum tersedia
                                                        </b>
                                                    </h3>
                                                </label>
                                            </div>
                                        @endif

                                        <!-- ================================================================== -->

                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                        <div class="lebar-100 rata-tengah">
                            <button id="pay-button" type="submit" class="bg-primer mt-3 lebar-50"
                                style="margin-right:5%; border-radius: 6px;">Beli Tiket</button>
                        </div>
                    </form>
                </div>
            </div>

        </div> --}}

        {{-- Pop Up share to medsos --}}
        <div class="bg"></div>
        <div class="popupWrapper" id="shareSosmed">

            <div class="popup border py-3 mb-3" style="border-radius: 14px;">
                <i class="fa fa-close op-5 ke-kanan pointer close-icon" onclick="hilangPopup('#shareSosmed')"></i>
                <div class="wrap">
                    <div class="col-md-12 mb-2">
                        <h4 class="teks-tebal rata-tengah mb-4">
                            Bagikan
                        </h4>
                    </div>
                    <div class="share-buttons-container">
                        <div class="share-list">
                          <!-- Copy Link -->
                          <a class="cp-h" onclick="copyLinkEvent()">
                            <img src="https://img.icons8.com/material-rounded/96/000000/copy.png">
                          </a>

                          <!-- FACEBOOK -->
                          <a class="fb-h" onclick="return fbs_click()" target="_blank">
                            <img src="https://img.icons8.com/material-rounded/96/000000/facebook-f.png">
                          </a>
                      
                          <!-- TWITTER -->
                          <a class="tw-h" onclick="return tbs_click()"  target="_blank">
                            <img src="https://img.icons8.com/material-rounded/96/000000/twitter-squared.png">
                          </a>
                      
                          <!-- LINKEDIN -->
                          <a class="li-h" onclick="return lbs_click()"  target="_blank">
                            <img src="https://img.icons8.com/material-rounded/96/000000/linkedin.png">
                          </a>
                      
                          <!-- REDDIT -->
                          <a class="wa-h" onclick="return wbs_click()" target="_blank">
                            <img src="https://img.icons8.com/ios-glyphs/90/000000/whatsapp.png">
                          </a>

                        </div>
                      </div>
                </div>
            </div>

        </div>


        <!-- Pop Up Undang teman -->
        {{-- Sistem Lama --}}
        {{-- <div class="bg"></div>
        @if (isset($myData))

            <div class="popupWrapper" id="undangTeman">
                <div class="popup rounded-15" style="width:683px !important;height:694px !important;">
                    <div class="wrap super">
                        <h3 style="text-align: center;font-family: 'Inter'; font-weight: 900; ">Undang Teman
                            <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#undangTeman')"></i>
                        </h3>
                        <h6 style="color:#828D99;text-align:center;">
                            Hadiri event bersama teman - temanmu
                        </h6>
                        <form action="{{ route('user.event.invitation', $event->id) }}" method="POST">
                            {{ csrf_field() }}
                            <div class="mt-5" style="font-family: 'Inter';"><b>Email Temanmu</b> </div>
                            <input type="email" class="box" name="email" placeholder="example@gmail.com"
                                required>
                            <div class="mt-5 wrapper breakdowns" breakdown-type="stage-session">
                                <input type="checkbox" name="breakdowns[]" value="1" id="stage-session"
                                    style="width: 30px; height:30px;">
                                <div class="d-flex">
                                    <div class="ke-kiri mt-3 mr-5" style="margin-left: -4%">
                                        <b>Tiket Untuk Teman</b>
                                        <p style="line-height: 100%;">Undang dan Belikan Temanmu Tiket Sekaligus</p>
                                    </div>
                                    <div class="ke-kanan mt-2" id="icon" style="font-size: 30pt;">
                                        <i class="fa fa-tags teks-tebal teks-primer"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5" style="font-family: 'Inter';"><b>Jenis Tiket</b> </div>
                            <select name="ticket" id="pilih-jenis-tiket" class="box" disabled>
                                <option value="0">Pilih Tiket Untuk Teman Anda</option>
                                @foreach ($event->sessions as $session)
                                    @foreach ($session->tickets as $ticket)
                                        <option value="{{ $ticket->id }}">
                                            @if ($ticket->price == 0)
                                                {{ $ticket->name }} (Free)
                                            @else
                                                {{ $ticket->name }} (
                                                @currencyEncode($ticket->price))
                                            @endif
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            <div class="rata-tengah">
                                <button type="submit" class="primer mt-5 lebar-50">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif --}}
        <!-- End Pop Up Dialog -->
    </div>
    @endsection

    @section('javascript')
        <!--  <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
                      data-client-key="<?php //echo $CLIENT_KEY
                      ?>"></script> -->
        <script src="{{ asset('js/base.js') }}"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/27.0.0/classic/ckeditor.js"></script>
        <script>

            // ------ Mengatasi output lokasi berupa element <p> ----------------
            var labelLocObj = document.querySelector("#locationSmall p");
            var labelLoc = labelLocObj.innerHTML;
            labelLocObj.remove();
            console.log(labelLoc);
            var spanLabel = (document.querySelector("#locationSmall").innerHTML +=
                labelLoc + ", Kota " + "{{  $event->city  }}");
            // ----------------------------------------------------------------

            @if (session('pesan'))
                window.addEventListener("load", function() {
                // alert("{{ session('pesan') }}");
                    Swal.fire({
                        title: 'Peringatan !!!',
                        text: {{ session('pesan') }},
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                });
            @endif

            @if (session('payment'))
                // window.snap.pay('{{ session('payment') }}', {
                // onSuccess: function(result){
                // /* You may add your own implementation here */
                // window.location.replace({{ route('user.payment_confirm') }});
                // },
                // onPending: function(result){
                // /* You may add your own implementation here */
                // window.location.replace({{ route('user.payment_confirm') }});
                // },
                // onError: function(result){
                // /* You may add your own implementation here */
                // alert("payment failed!"); console.log(result);
                // },
                // onClose: function(){
                // /* You may add your own implementation here */
                // alert('you closed the popup without finishing the payment');
                // }
                // }); // Replace it with your transaction token
            
                //
            @endif

            // -------- Function share to medsos -------------------
            var title = "{{ $event->name }}";
            var deskripsi = "{{ $event->description }}";
            var currentLocation =
                "https://explore.agendakota.id" + window.location.pathname.toString();
            var top = (screen.height - 570) / 2;
            var left = (screen.width - 570) / 2;
            var params =
                "menubar=no,toolbar=no,status=no,width=570,height=570,top=" +
                top +
                ",left=" +
                left;
            console.log(currentLocation);
            function fbs_click() {
                var url =
                    "https://web.facebook.com/sharer.php?u=" + encodeURI(currentLocation);
                window.open(url, "NewWindow", params);
            }

            function tbs_click() {
                var url =
                    "https://twitter.com/intent/tweet?url=" +
                    encodeURI(currentLocation) +
                    "&text=" +
                    encodeURI(deskripsi);
                window.open(url, "NewWindow", params);
            }

            function wbs_click() {
                var url =
                    "https://api.whatsapp.com/send?phone=&text=" +
                    encodeURI(title + " " + currentLocation);
                window.open(url, "NewWindow", params);
            }

            function lbs_click() {
                var url =
                    "https://linkedin.com/sharing/share-offsite/?url=" +
                    encodeURI(currentLocation);
                window.open(url, "NewWindow", params);
            }

        </script>
        <script src="{{ asset('js/user/organization/eventDetailPage.js') }}"></script>
    @endsection

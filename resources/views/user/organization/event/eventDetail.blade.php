@extends('layouts.homepage')

@section('title', 'Events Details')

@section('content')
    @php
    use Carbon\Carbon;
    $tanggalSekarang = Carbon::now()->toDateString();
    @endphp
    
    <style>
        .cotainer{
            margin-left: auto;
            margin-right: auto;
            max-width: 1120px;
            width: 100%;
        }
        .event-page{
            display: grid;
            gap: 32px;
              row-gap: 32px;
            max-width: 1216px;
            row-gap: 0;
        }
        .wrapper {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-bottom: 2%;
            margin-top: 2%;
            width: 100%;
            height: 115px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background-color: #FFFFFF;
        }

        .breakdowns.active {
            border: 3px solid #E6286E;
            color: #E6286E;
        }

        .square {
            text-align: center;
            width: 50px;
            height: 50px;
            background-color: #fff;
            border: 1px solid lightgray;
            border-radius: 6px;
        }

        .ticket {
            position: relative;
            border: 2px solid #E1E5E8;
            display: inline-block;
            padding: 1em 2em;
            width: 100%;
            height: 155px;
            border-radius: 20px;

        }

        .ticket.active {
            position: relative;
            border: 2px solid #E6286E;
            display: inline-block;
            padding: 1em 2em;
            width: 100%;
            height: 155px;
            border-radius: 20px;

        }

        .ticket.active:before,
        .ticket.active:after {
            height: 90px;
            width: 45px;
            content: '';
            position: absolute;
            top: 42%;
            height: 30px;
            width: 30px;
            border-radius: 900px;
            border: 2px solid #E6286E;
        }

        .ticket:before,
        .ticket:after {
            content: '';
            position: absolute;
            top: 42%;
            height: 30px;
            width: 30px;
            border: 2px solid #ddd;
            border-radius: 900px;
            background-color: #fff;
        }

        .ticket:before {
            left: -7px;
            border-left-color: white;
        }

        .ticket:after {
            right: -7px;
            border-right-color: white;
        }

        .ticket.active:before {
            left: -7px;
            border-left-color: white;
        }

        .ticket.active:after {
            right: -7px;
            border-right-color: white;
        }

        .text-inner-tbl{
            height: 25px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .garis-tiket {
            border-top: 2px dashed;
        }

        .square {
            text-align: center;
            width: 25%;
            height: 10%;
            background-color: #FAFBFB;
            border: 1px solid lightgray;
            border-radius: 25px;
            margin-bottom: 5%;
        }

        .square-speaker {
            width: 100%;
            height: 100%;
            background-color: #ffff;
            border: 1px solid lightgray;
            border-radius: 15px;
            margin-bottom: 5%;
            margin-right: 5%;
        }

        .square-sponsor {
            text-align: center;
            width: 80%;
            background-color: #ffff;
            border: 1px solid lightgray;
            border-radius: 15px;
            margin: 0px 5% 5% 0px;
        }

        .square-booth {
            width: 90%;
            height: 100%;
            background-color: #ffff;
            border: 1px solid lightgray;
            border-radius: 15px;
            padding-bottom: 5%;
        }

        .booth-item {
            position: relative;
            word-wrap: break-word;
        }

        .booth-item .bg-item {
            height: 200px;
            filter: blur(2px);
            border-radius: 15px;
        }

        .booth-item .content {
            position: absolute;
            top: 0px;
            left: 0px;
            right: 0px;
            bottom: 0px;
            background: #00000070;
            padding: 20px;
            color: #fff;
            border-radius: 15px;
        }

        .ck.ck-toolbar>.ck-toolbar__items {
            display: none;
        }

        .ck.ck-toolbar {
            border: none;
        }

        .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
            border-color: white;
        }
        .ck.ck-editor__editable_inline {
          overflow: auto;
          padding: unset;
          border: 1px solid transparent;
            border-top-color: transparent;
            border-right-color: transparent;
            border-bottom-color: transparent;
            border-left-color: transparent;
        }
        .rounded-15{
            border-radius: 15px !important;
        }
        .bg-banner{
            background-position: center center;
            background-size: cover;
            aspect-ratio: 5/2;
            width: 100%;
            height: 100%;
        }
        .pd-tbl{
            padding: 0.75rem;
        }
        .w-100-i{
            width: 100% !important;
        }
        .speaker-icon{
            margin-left:15%; 
            margin-top:20%; 
            width: 100px; 
            height: 100px;
            border-radius:50%;
        }
        .speaker-text{
            margin-top: 20px;
            margin-left: 10%;
            overflow: hidden;
            margin-right: 5%;
            height: 100px;
        }
        .speaker-detail{
            font-size: 13px !important;
            color: #8A8888;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .logoCard {
            height: 70px;
            width: 70px;
            border-radius: 8px !important;
        }
        .lable-title {
            font-size: 14px;
            overflow: hidden;
        }
        .label {
            border-top-right-radius: 26px;
            border-bottom-right-radius: 26px;
            display: inline-block;
            margin-top: 10px;
            padding: 4px 5px;
        }
        .sponsor-title {
            font-size: 18px;
            font-weight: bolder;
            height: 46px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .title-exhibitor {
            font-weight: bolder;
            font-size: 18px !important;
        }
        .bg-exihibitor{
            width: calc(100% - 30px);
            opacity: 35%;
            height: 88%;
            position: absolute;
        }
        .font-info {
            font-size: 12px;
            font-family: DM_Sans !important;
            font-weight: bold;
            font-style: normal;
        }

    </style>

    <div class="container event-page px-3 pt-3">
        <div class="row">
            <div class="col-md-9 px-5 py-3">
                <div class="row my-2 pl-3">
                    <div class="col">
                       <!--  {{-- <img src="{{asset('storage/event_assets/'.$event->slug.'/event_logo/thumbnail/'.$event->logo))}}" style="border-radius: 14px;" class="mw-100" alt=""> --}}
                        {{-- <img src="{{ asset('storage/event_assets/'.$event->slug.'/event_logo/thumbnail/'.$event->logo) }}" class="tinggi-350 rounded" style="width: 100%;" alt=""> --}} -->
                        <div class="tinggi-350 rounded-15 bg-banner"
                            bg-image="{{ asset('storage/event_assets/' . $event->slug . '/event_logo/thumbnail/' . $event->logo) }}">
                        </div>
                    </div>
                </div>
                
                <div class="row my-2">
                    <div class="col">
                        {{-- tab --}}
                        <div class="tab" style="border: none; border-bottom: 1px solid #F0F1F2;">
                            <button class="tablinks-event-detail active mr-4"
                                onclick="opentabs(event, 'event-detail', 'Overview')">Overview</button>
                            <button class="tablinks-event-detail mr-4"
                                onclick="opentabs(event, 'event-detail', 'Schedule')">Schedule</button>
                            <button class="tablinks-event-detail mr-4"
                                onclick="opentabs(event, 'event-detail', 'Speakers')">Speakers</button>
                            <button class="tablinks-event-detail mr-4"
                                onclick="opentabs(event, 'event-detail', 'Sponsors')">Sponsors</button>
                            {{-- <button class="tablinks-event-detail mr-4" onclick="opentabs(event, 'event-detail', 'Booths')">Booths</button> --}}
                        </div>
                        {{-- end tab --}}
                    </div>
                </div>
                <div id="Overview" class="tabcontent-event-detail mt-4 ml-3" style="display: block; border: none;">
                    <textarea name="description" id="description" class="description no-pd"
                        readonly>{{ $event->description }}</textarea>
                    <h4 class="teks-tebal mt-5 mb-4" style="color: #304156;">
                        <span style="color: #304156;">
                            <i class="fa fa-calendar" style="font-size: 24px;"></i>
                        </span>
                        Schedule
                    </h4>
                    <div class="row my-2">
                        <div class="col">
                            {{-- tab schedule --}}
                            <div class="tab" style="border: none; border-bottom: 1px solid #F0F1F2;">
                                @php
                                    $i=0;
                                @endphp
                                @foreach($schedule as $key => $value)
                                    @if($i == 0)
                                        <button class="tablinks-schedule active mr-4" onclick="opentabs(event, 'schedule', '{{ $key }}')">
                                            {{ $key }}
                                        </button>
                                        
                                    @else
                                        <button class="tablinks-schedule mr-4" onclick="opentabs(event, 'schedule', '{{ $key }}')">
                                            {{ $key }}
                                        </button>
                                        
                                    @endif

                                    @php
                                        $i++;
                                    @endphp

                                @endforeach

                                @php
                                    $j=0;
                                @endphp
                                @foreach($schedule as $key => $value)
                                    
                                    @if($j == 0)
                                        <div id="{{ $key }}" class="tabcontent-schedule mt-4 lebar-100" style="display: block; border: none;">
                                            
                                            <table class="table table-striped">
                                              <tbody>
                                                @foreach($value as $val)
                                                    <tr>
                                                      <td>
                                                        <b>{{ $val->start_time }} - {{ $val->end_time }}</b>
                                                        <br><br>
                                                        Sampai Tanggal : <br>
                                                        {{ $val->end_date }}
                                                    </td>
                                                      <td>
                                                        <b>{{ $val->title }}</b>
                                                        <br>
                                                        {{ $val->description }}
                                                        <br>
                                                        @foreach($val->sessionspeakers as $sessionSpeaker)
                                                            <div class="row pd-tbl">
                                                                <div class="col-xl-5 square w-100-i text-inner-tbl no-pd-l" style="color: #304156; height:30px; margin-right:20px;">
                                                                    <img src="{{ asset('storage/event_assets/' . $event->slug . '/speaker_photos/' .$sessionSpeaker->speaker->photo) }}"
                                                                        style="width: 25px; height: 25px; border-radius:50%; float: left; margin-top: 1px; margin-left: 3px;">
                                                                   <p class="p-1">
                                                                        {{ $sessionSpeaker->speaker->name }}
                                                                   </p>
                                                                </div>
                                                            </div>

                                                        @endforeach

                                                      </td>
                                                    </tr>
                                                @endforeach
                                              </tbody>
                                            </table>

                                        </div>
                                    @else
                                        <div id="{{ $key }}" class="tabcontent-schedule mt-4 lebar-100" style="display: none; border: none;">
                                            
                                            <table class="table table-striped">
                                              <tbody>
                                                @foreach($value as $val)
                                                    <tr>
                                                      <td>
                                                        <b>{{ $val->start_time }} - {{ $val->end_time }}</b>
                                                        <br><br>
                                                        Sampai Tanggal : <br>
                                                        {{ $val->end_date }}
                                                    </td>
                                                      <td>
                                                        <b>{{ $val->title }}</b>
                                                        <br>
                                                        {{ $val->description }}
                                                        <br>
                                                        @foreach($val->sessionspeakers as $sessionSpeaker)
                                                            <div class="row pd-tbl">
                                                                <div class="col-xl-5 square w-100-i text-inner-tbl no-pd-l" style="color: #304156; height:30px; margin-right:20px;">
                                                                    <img src="{{ asset('storage/event_assets/' . $event->slug . '/speaker_photos/' .$sessionSpeaker->speaker->photo) }}"
                                                                        style="width: 25px; height: 25px; border-radius:50%; float: left; margin-top: 1px; margin-left: 3px;">
                                                                   <p class="p-1">
                                                                        {{ $sessionSpeaker->speaker->name }}
                                                                   </p>
                                                                </div>
                                                            </div>

                                                        @endforeach

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
                                
                            </div>
                            {{-- end tab --}}
                        </div>
                    </div>
                    <!-- @foreach ($event->sessions as $session)
                        @dump($session)
                        @if ($session->overview == 1)
                            <p class="teks-primer" style="color: #e5214f; /*#EB597B*/ margin-top: 2%;">
                                <b>
                                    {{ Carbon::parse($session->date)->format('d F Y') }}
                                </b>
                            </p>
                            <ul style="color: #E6286E; padding-left:20px;">
                                <li style="color: #828D99;">
                                    {{ $session->start_time }} - {{ $session->end_time }} WIB
                                </li>
                                <h5 class="teks-tebal" style="color: #000000; margin-top: 2%;">{{ $session->title }}</h5>
                                <p style="color: #828D99;">{{ $session->description }}</p>
                            </ul>
                            @foreach ($session->sessionspeakers as $sessionspeaker)
                                <div class="square bagi bagi-3"
                                    style="color: #304156; height:30px; margin-right:20px;margin-left: 20px;">
                                    <img src="{{ asset('storage/event_assets/' . $event->slug . '/speaker_photos/' . $sessionspeaker->speaker->photo) }}"
                                        style="width: 25px; height: 25px; border-radius:50%; float: left; margin-top: 1px; margin-left: 3px;">
                                    {{ $sessionspeaker->speaker->name }}
                                </div>
                            @endforeach
                        @endif
                    @endforeach -->

                    <h4 class="teks-tebal mt-5 mb-4" style="color: #304156;">
                        <span style="color: #304156;">
                            <i class="fa fa-microphone" style="font-size: 24px;"></i>
                        </span>
                        Speakers
                    </h4>

                    <div class="row">
                    @foreach ($event->speakers as $speaker)
                        <div class="col-xl-6 mb-4">
                            <div class="square-speaker">
                                <div class="d-flex">
                                   
                                    <div>
                                        <img src="{{ asset('storage/event_assets/' . $event->slug . '/speaker_photos/' . $speaker->photo) }}" class="speaker-icon">
                                    </div>
                                    <div class="speaker-text">
                                        <div class="teks-tebal">
                                            {{ $speaker->name }}
                                            <p class="speaker-detail">
                                                {{ $speaker->email }}
                                                <br>
                                                {{ $speaker->company }}
                                                <br>
                                                {{ $speaker->job }}
                                            </p>
                                        </div>
                                        <div class="teks-tipis" style="color: #C4C4C4;">
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

                    <br />
                    <h4 class="teks-tebal mt-5 mb-4" style="color: #304156;">
                        <span style="color: #304156;">
                            <i class="fa fa-handshake" style="font-size: 24px;"></i>
                        </span>
                        Sponsor
                    </h4>
                    <div class="row">
                    @foreach ($event->sponsors as $sponsor)
                        <!-- {{-- @if ($sponsor->type == 'Platinum Sponsor') --}}
                        <div class="bagi bagi-3">
                            <div class="square-sponsor">
                                <div>
                                    <img src="{{ asset('storage/event_assets/' . $event->slug . '/sponsor_logo/' . $sponsor->logo) }}"
                                        style=" width: 100%; height: 100px; border-radius:6px;">
                                </div>
                                <div style="margin-top: 5%;">
                                    <p class="teks-tebal" style="">
                                        {{ $sponsor->name }}
                                    </p>
                                    <a href="{{ $sponsor->website }}" class="teks-tipis">
                                        <p class="teks-tipis" style="text-decoration: none; margin-top: -5%;">
                                            {{ substr($sponsor->website, 0, 20) }}
                                        </p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- @endif --}} -->
                        <!-- <div class="bagi bagi-3 mt-4">
                            <div class="wrap">
                                <div class="bg-putih rounded-5 bayangan-5">

                                    <div class="smallPadding">
                                        <div class="wrap">
                                            <div class="row">
                                                <div class="col-xl-4 no-pd-r mx-wd-90">
                                                    <div class="logoCard" bg-image="{{ asset('storage/event_assets/'.$event->slug.'/sponsor_logo/'.$sponsor->logo) }}"></div>
                                                </div>
                                                <div class="col-xl-8">
                                                    <h3 class="sponsor-title mt-2">{{ $sponsor->name }}</h3>
                                                </div>
                                            </div>
                                            <div class=" mt-2">
                                                <a href="{{ $sponsor->website }}" target="_blank" class="teks-primer">
                                                    See About Sponsor
                                                </a>
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <div class="col-xl-4 mt-4">
                            <div class="">
                                <div class="bg-putih rounded-15 bayangan-5">
                    
                                    <div class="label bg-primer lable-title">{{ $sponsor->type }}</div>
                                    
                                    

                                    <div class="smallPadding">
                                        <div class="wrap">
                                            <div class="row">
                                                <div class="col-xl-4 no-pd-r mx-wd-90">
                                                    <div class="logoCard" bg-image="{{ asset('storage/event_assets/'.$event->slug.'/sponsor_logo/'.$sponsor->logo) }}"></div>
                                                </div>
                                                <div class="col-xl-8">
                                                    <h3 class="sponsor-title mt-2">{{ $sponsor->name }}</h3>
                                                </div>
                                            </div>
                                            <div class=" mt-2">
                                                <a href="{{ $sponsor->website }}" target="_blank" class="teks-primer">
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

                    <br />
                    {{-- tempat icon booths --}}

                    <div class="row">
                    @foreach ($event->exhibitors as $exhibitor)
                        @if ($exhibitor->overview == 1)
                            <div class="col-xl-4" style="margin-top:2%;">
                                        
                                    <div class="bg-putih">
                                        <div class="rounded-15 bayangan-5 bg-exihibitor" bg-image="{{ asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_booth_image/' . $exhibitor->booth_image) }}"></div>
                                        <div class="wrap">
                                           
                                            <img src="{{ asset('storage/event_assets/'.$event->slug.'/exhibitors/exhibitor_logo/'.$exhibitor->logo) }}" class="rounded-circle asp-rt-1-1 mt-4" width="58px" height="58px">
                                            

                                            <div class="title-exhibitor pt-3 ml-1">
                                                {{ $exhibitor->name }}
                                            </div>
                                            <div style="padding-left: 10px; margin-top:-1%; color: #C4C4C4; font-size:10pt;">

                                            </div>
                                            <div class="teks-primer font-info mt-2 ml-2">
                                                @if ($exhibitor->virtual_booth == 1)
                                                    <i class="fa fa-check pr-2 teks-primer" aria-hidden="true"></i>Virtual Booth<span ></span>
                                                @else
                                                    <i class="fas fa-times pr-2 teks-primer" aria-hidden="true"></i>Nothing Virtual Booth<span ></span>
                                                @endif

                                            </div>
                                            <div class="teks-primer font-info mt-2 ml-2 pb-4">
                                                @if ($exhibitor->overview == 1)
                                                    <i class="fa fa-check pr-2 teks-primer" aria-hidden="true"></i>Show In Overview Event<span ></span>
                                                @else
                                                    <i class="fas fa-times pr-2 teks-primer" aria-hidden="true"></i>Not Show In Overview Event<span ></span>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                
                            </div>
                        @endif
                    @endforeach
                    </div>

                </div>
                <div id="Schedule" class="tabcontent-event-detail mt-4 ml-4" style="display: none; border: none;">
                    <h4 class="teks-tebal mt-5 mb-4" style="color: #304156;">
                        <span style="color: #304156;">
                            <i class="fa fa-calendar" style="font-size: 24px;"></i>
                        </span>
                        Schedule
                    </h4>
                    <div class="row my-2">
                        <div class="col">
                            {{-- tab schedule --}}
                            <div class="tab" style="border: none; border-bottom: 1px solid #F0F1F2;">
                                @php
                                    $i=0;
                                @endphp
                                @foreach($schedule as $key => $value)
                                    @if($i == 0)
                                        <button class="tablinks-schedule-2 active mr-4" onclick="opentabs(event, 'schedule-2', '{{ $key }}-2')">
                                            {{ $key }}
                                        </button>
                                        
                                    @else
                                        <button class="tablinks-schedule-2 mr-4" onclick="opentabs(event, 'schedule-2', '{{ $key }}-2')">
                                            {{ $key }}
                                        </button>
                                        
                                    @endif

                                    @php
                                        $i++;
                                    @endphp

                                @endforeach

                                @php
                                    $j=0;
                                @endphp
                                @foreach($schedule as $key => $value)
                                    
                                    @if($j == 0)
                                        <div id="{{ $key }}-2" class="tabcontent-schedule-2 mt-4 lebar-100" style="display: block; border: none;">
                                            
                                            <table class="table table-striped">
                                              <tbody>
                                                @foreach($value as $val)
                                                    <tr>
                                                      <td>
                                                        <b>{{ $val->start_time }} - {{ $val->end_time }}</b>
                                                        <br><br>
                                                        Sampai Tanggal : <br>
                                                        {{ $val->end_date }}
                                                    </td>
                                                      <td>
                                                        <b>{{ $val->title }}</b>
                                                        <br>
                                                        {{ $val->description }}
                                                        <br>
                                                        @foreach($val->sessionspeakers as $sessionSpeaker)
                                                            <div class="row pd-tbl">
                                                                <div class="col-xl-5 square w-100-i text-inner-tbl no-pd-l" style="color: #304156; height:30px; margin-right:20px;">
                                                                    <img src="{{ asset('storage/event_assets/' . $event->slug . '/speaker_photos/' .$sessionSpeaker->speaker->photo) }}"
                                                                        style="width: 25px; height: 25px; border-radius:50%; float: left; margin-top: 1px; margin-left: 3px;">
                                                                   <p class="p-1">
                                                                        {{ $sessionSpeaker->speaker->name }}
                                                                   </p>
                                                                </div>
                                                            </div>

                                                        @endforeach

                                                      </td>
                                                    </tr>
                                                @endforeach
                                              </tbody>
                                            </table>

                                        </div>
                                    @else
                                        <div id="{{ $key }}-2" class="tabcontent-schedule-2 mt-4 lebar-100" style="display: none; border: none;">
                                            
                                            <table class="table table-striped">
                                              <tbody>
                                                @foreach($value as $val)
                                                    <tr>
                                                      <td>
                                                        <b>{{ $val->start_time }} - {{ $val->end_time }}</b>
                                                        <br><br>
                                                        Sampai Tanggal : <br>
                                                        {{ $val->end_date }}
                                                    </td>
                                                      <td>
                                                        <b>{{ $val->title }}</b>
                                                        <br>
                                                        {{ $val->description }}
                                                        <br>
                                                        @foreach($val->sessionspeakers as $sessionSpeaker)
                                                            <div class="row pd-tbl">
                                                                <div class="col-xl-5 square w-100-i text-inner-tbl no-pd-l" style="color: #304156; height:30px; margin-right:20px;">
                                                                    <img src="{{ asset('storage/event_assets/' . $event->slug . '/speaker_photos/' .$sessionSpeaker->speaker->photo) }}"
                                                                        style="width: 25px; height: 25px; border-radius:50%; float: left; margin-top: 1px; margin-left: 3px;">
                                                                   <p class="p-1">
                                                                        {{ $sessionSpeaker->speaker->name }}
                                                                   </p>
                                                                </div>
                                                            </div>

                                                        @endforeach

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
                                
                            </div>
                            {{-- end tab --}}
                        </div>
                    </div>
                </div>
                <div id="Speakers" class="tabcontent-event-detail ml-4 mt-4" style="display: none; border: none;">
                    <h4 class="teks-tebal mt-5 mb-4" style="color: #304156;">
                        <span style="color: #304156;">
                            <i class="fa fa-microphone" style="font-size: 24px;"></i>
                        </span>
                        Speakers
                    </h4>

                    <div class="row">
                    @foreach ($event->speakers as $speaker)
                        <div class="col-xl-6 mb-4">
                            <div class="square-speaker">
                                <div class="d-flex">
                                   
                                    <div>
                                        <img src="{{ asset('storage/event_assets/' . $event->slug . '/speaker_photos/' . $speaker->photo) }}" class="speaker-icon">
                                    </div>
                                    <div class="speaker-text">
                                        <div class="teks-tebal">
                                            {{ $speaker->name }}
                                            <p class="speaker-detail">
                                                {{ $speaker->email }}
                                                <br>
                                                {{ $speaker->company }}
                                                <br>
                                                {{ $speaker->job }}
                                            </p>
                                        </div>
                                        <div class="teks-tipis" style="color: #C4C4C4;">
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
                <div id="Sponsors" class="tabcontent-event-detail ml-4 mt-4" style="display: none; border: none;">
                    <h4 class="teks-tebal mt-5 mb-4" style="color: #304156;">
                        <span style="color: #304156;">
                            <i class="fa fa-handshake" style="font-size: 24px;"></i>
                        </span>
                        Sponsor
                    </h4>
                    <div class="row">
                    @foreach ($event->sponsors as $sponsor)
                       
                        <div class="col-xl-4 mt-4">
                            <div class="">
                                <div class="bg-putih rounded-15 bayangan-5">
                    
                                    <div class="label bg-primer lable-title">{{ $sponsor->type }}</div>
                                    
                                    

                                    <div class="smallPadding">
                                        <div class="wrap">
                                            <div class="row">
                                                <div class="col-xl-4 no-pd-r mx-wd-90">
                                                    <div class="logoCard" bg-image="{{ asset('storage/event_assets/'.$event->slug.'/sponsor_logo/'.$sponsor->logo) }}"></div>
                                                </div>
                                                <div class="col-xl-8">
                                                    <h3 class="sponsor-title mt-2">{{ $sponsor->name }}</h3>
                                                </div>
                                            </div>
                                            <div class=" mt-2">
                                                <a href="{{ $sponsor->website }}" target="_blank" class="teks-primer">
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
                <div id="Booths" class="tabcontent-event-detail" style="display: none; border: none;">
                    @foreach ($event->exhibitors as $exhibitor)
                        <div class="bagi bagi-3">
                            <div class="wrap">
                                <div class="booth-item">
                                    <div class="bg-item"
                                        bg-image="{{ asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_booth_image/' . $exhibitor->booth_image) }}"
                                        style="width:100%; height:180px;"></div>
                                    <div class="content">
                                        <img src="{{ asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_logo/' . $exhibitor->logo) }}"
                                            style="width: 65px; height: 65px;">
                                        <h4 style="position: absolute;bottom: 30px;color: white">
                                            <b>{{ $exhibitor->name }}</b>
                                        </h4>
                                        <div style="position: absolute;bottom: 20px;"><a
                                                href="$exhibitor->website">{{ substr($exhibitor->website, 0, 25) . '...' }}<a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-3 py-4 pl-2 px-3">
                <div class="row border py-3 pl-1 mb-3" style="border-radius: 14px;">
                    <div class="col-md-12 mt-2">
                        <h4 class="teks-tebal" style="color: black;">{{ $event->name }}</h4>
                    </div>
                    <div class="col-md-12 my-2">
                        <i class="fa fa-calendar"></i> &nbsp;
                        <span class="text-inter" style="font-style: normal; font-size: 12px;">
                            {{ Carbon::parse($event->start_date)->format('d M,') }}
                            {{ Carbon::parse($event->start_time)->format('H:i') }} WIB -
                            {{ Carbon::parse($event->end_date)->format('d M,') }}
                            {{ Carbon::parse($event->end_time)->format('H:i') }} WIB
                        </span>
                    </div>
                    <div class="col-md-12">
                        <i class="fa fa-user"></i> &nbsp;
                        <span class="text-inter" style="font-style: normal; font-size: 12px;">
                            {{ count($purchase) }} Orang Sudah Mendaftar
                        </span>
                    </div>
                    @if (isset($myData))
                        <div class="col-sm-6 mt-4 " style="padding-right: 0px;">
                            <div class="pointer" onclick="munculPopup('#undangTeman')">
                                <h5 class=""
                                    style="color: #e5214f; /*#EB597B*/ font-weight: 500; font-family: DM_Sans !important; font-style: normal; font-size: 16px;">
                                    <span style="color: #e5214f; /*#EB597B*/">
                                        <i class="fa fa-user-plus" style="font-size: 20px;"></i>
                                    </span>
                                    Undang Teman
                                </h5>
                            </div>
                        </div>
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
                    @endif
                </div>

                @if (isset($myData))
                    <div class="bg"></div>
                    <div class="popupWrapper" id="undangTeman">
                        <div class="popup" style="width:683px !important;height:694px !important;">
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
                                    <input type="email" class="box" name="email" placeholder="example@gmail.com" required>
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
                                       @foreach($event->sessions as $session)

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
                @endif

                <div class="row border py-3 pl-3 mb-3" style="border-radius: 14px;">
                    <div class="col-md-12 mb-2">
                        <h4 class="teks-tebal" style="color: black;">
                            <span style="color: #e5214f; /*#EB597B*/">
                                <i class="fa fa-tags teks-tebal" style="font-size: 20px;"></i>
                            </span>
                            Tickets
                        </h4>
                    </div>
                    @foreach ($event->sessions as $session)
                        @foreach ($session->tickets as $ticket)
                            <div class="col-md-12 pl-0 my-2">
                                <form action="{{ route('user.pembelian', $event->slug) }}" method="POST">
                                    @csrf
                                    @if ($ticket->quantity > 0 && $ticket->start_date <= $tanggalSekarang && $ticket->end_date >= $tanggalSekarang)
                                        <h1 style="font-size: 18px; color:black; text-transform: uppercase;">
                                            <b>
                                                <input type="radio" name="breakdowns[]" id="{{ $ticket->id }}"
                                                    class="mr-2" value="{{ $ticket->id }}">
                                                {{ $ticket->name }}
                                            </b>
                                        </h1>

                                        <h3 class="ml-4" style="font-size: 15px; color:#e5214f; /*#EB597B*/"><b>
                                                @if ($ticket->price == 0)
                                                    Gratis
                                                @else
                                                    @currencyEncode($ticket->price)
                                                @endif
                                            </b></h3>
                                    @elseif($ticket->end_date < $tanggalSekarang) <h1 class="ml-4"
                                            style="font-size: 18px; color:black; text-transform: uppercase;">
                                            <b>
                                                <input type="radio" hidden name="breakdowns[]" id="{{ $ticket->id }}"
                                                    value="Tidak Ada">
                                                {{ $ticket->name }}
                                            </b>
                                            </h1>

                                            <h3 class="ml-4" style="font-size: 15px; color:#e5214f; /*#EB597B*/">
                                                <b>
                                                    Penjualan Telah Berakhir
                                                </b>
                                            </h3>
                                        @elseif($ticket->quantity == 0)
                                            <h1 class="ml-4"
                                                style="font-size: 18px; color:black; text-transform: uppercase;">
                                                <b>
                                                    <input type="radio" hidden name="breakdowns[]" id="{{ $ticket->id }}"
                                                        value="Tidak Ada">
                                                    {{ $ticket->name }}
                                                </b>
                                            </h1>

                                            <h3 class="ml-4" style="font-size: 15px; color:#e5214f; /*#EB597B*/"><b>
                                                    Maaf Tiket Habis
                                                </b></h3>
                                        @else
                                            <h1 class="ml-4"
                                                style="font-size: 18px; color:black; text-transform: uppercase;">
                                                <b>
                                                    <input type="radio" hidden name="breakdowns[]" id="{{ $ticket->id }}"
                                                        value="Tidak Ada">
                                                    {{ $ticket->name }}
                                                </b>
                                            </h1>

                                            <h3 class="ml-4" style="font-size: 15px; color:#e5214f; /*#EB597B*/"><b>
                                                    Tiket belum tersedia
                                                </b></h3>
                                    @endif
                                    <hr class="garis-tiket mt-3" style="color: lightgray;" />
                                    <p style="color:#828D99;font-size:12px !important;margin-top:10px;">
                                        {{ $ticket->description }}</p>

                            </div>
                        @endforeach
                    @endforeach
                    <button id="pay-button" type="submit" class="bg-primer mt-3 lebar-100" style="margin-right:5%; border-radius: 6px;">Beli Tiket</button>
                    </form>
                </div>

                <div class="row border py-3 pl-3 mb-3" style="border-radius: 14px;">
                    <div class="row d-flex align-items-center pl-3">
                        <div class="col-sm-3">
                            <img src="{{ asset('storage/organizer_logo/' . $event->organizer->logo) }}"
                                class="rounded-circle" style="max-width: 100%;" alt="">
                        </div>
                        <div class="col-sm-9">
                            <div class="row">
                                <p style="color: #828D99; margin-bottom: 0px;">Diadakan oleh</p>
                            </div>
                            <div class="row">
                                <h4 class="teks-tebal" style="color: black;">{{ $event->organizer->name }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row pl-3">
                        <div class="col-md-12 mt-4">
                            <p style="color: #828D99;">{{ $event->organizer->description }}</p>
                        </div>
                        <div class="col-md-12">
                            <a href="{{ $event->organizer->website }}"><i class="fa fa-envelope mr-1"
                                    style="color: grey;"></i></a>
                            <a href="{{ $event->organizer->linked }}"><i class="fab fa-linkedin mr-1"
                                    style="color: grey;"></i></a>
                            <a href="{{ $event->organizer->instagram }}"><i class="fab fa-instagram mr-1"
                                    style="color: grey; "></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
   <!--  <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="<?php //echo $CLIENT_KEY ?>"></script> -->
    <script src="{{ asset('js/base.js') }}"></script>    
    <script src="https://cdn.ckeditor.com/ckeditor5/27.0.0/classic/ckeditor.js"></script>
    <script>
        // ClassicEditor.create(document.querySelector('#description'));
        ClassicEditor.create(document.querySelector('#description'))
            .then(editor => {
                editor.isReadOnly = true; // make the editor read-only right after initialization
            });

        const getSelectedTicket = () => {
            let selectTicket = [];
            selectAll(".pilih-ticket").forEach(ticket => {
                if (ticket.classList.contains('ticket-list')) {
                    let ticketId = ticket.getAttribute('ticket-id');
                    ticket.checked = true;
                    ticket.classList.add('active');
                } else {
                    ticket.classList.remove('active');
                }
            });
        }
        selectAll(".pilih-ticket").forEach(ticket => {
            ticket.addEventListener("click", e => {
                let target = e.currentTarget;
                target.classList.add('ticket-list');
                let ticketId = ticket.getAttribute('ticket-id');
                getSelectedTicket();
                target.classList.remove('ticket-list');

            })
        })

        const getSelectedBreakdown = () => {
            let selectedBreakdown = [];
            selectAll(".breakdowns").forEach(breakdown => {
                if (breakdown.classList.contains('active')) {
                    let breakdownType = breakdown.getAttribute('breakdown-type');
                    selectedBreakdown.push(breakdownType);
                    document.getElementById(breakdownType).checked = true;
                    document.getElementById('pilih-jenis-tiket').disabled = false;
                }
            });
        }

        selectAll(".breakdowns").forEach(breakdown => {
            breakdown.addEventListener("click", e => {
                let target = e.currentTarget;
                target.classList.toggle('active');
                let breakdownType = breakdown.getAttribute('breakdown-type');
                document.getElementById(breakdownType).checked = false;
                document.getElementById('pilih-jenis-tiket').disabled = true;
                getSelectedBreakdown();
            })
        })

        @if (session('pesan'))
            window.addEventListener("load", function() {
            alert("{{ session('pesan') }}");
            });
        @endif

        function copyLinkEvent() {
            const elem = document.createElement('textarea');
            elem.value = "{{ route('user.eventDetail', $event->slug) }}";
            document.body.appendChild(elem);
            elem.select();
            document.execCommand('copy');
            document.body.removeChild(elem);
            alert("URL Telah Berhasil Di Copy");
        }                    

        // @if (session('payment'))
        //     window.snap.pay('{{ session('payment') }}', {
        //         onSuccess: function(result){
        //             /* You may add your own implementation here */
        //             window.location.replace({{ route('user.payment_confirm') }});
        //         },
        //         onPending: function(result){
        //             /* You may add your own implementation here */
        //             window.location.replace({{ route('user.payment_confirm') }});
        //         },
        //         onError: function(result){
        //             /* You may add your own implementation here */
        //             alert("payment failed!"); console.log(result);
        //         },
        //         onClose: function(){
        //             /* You may add your own implementation here */
        //             alert('you closed the popup without finishing the payment');
        //         }
        //     }); // Replace it with your transaction token

        // @endif
    </script>
@endsection

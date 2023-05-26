@extends('layouts.stream')

@section('title', 'Stream')

@section('head.style')
    <style>
        .thumb-sponsor {
            width: 100px;
            height: 100px;
            border-radius: 100px;
        }

    </style>
@endsection

@php
use Carbon\Carbon;
$tanggalSekarang = Carbon::now()->toDateString();
@endphp

{{-- @section('profile-menu')
  
@endsection --}}

@section('content')
    @include('admin.partials.alert')

    <div class="col-md mb-5">
        <div class="row my-2 pl-3">
            <div class="col">
                {{-- <img src="{{asset('storage/event_logo/'.$event->logo)}}" style="border-radius: 14px;" class="mw-100" alt=""> --}}
                {{-- <img src="{{ asset('storage/event_assets/'.$event->slug.'/event_logo/'.$event->logo) }}" class="tinggi-350 rounded" style="width: 100%;" alt=""> --}}
                <div class="tinggi-350 rounded"
                    bg-image="{{ asset('storage/event_assets/' . $event->slug . '/event_logo/' . $event->logo) }}">
                </div>
            </div>
        </div>
        <div class="col-md mt-3">
            <div id="Overview" class="">
                <h3>{{ $event->name }}</h3>
                <p class="font-16 "><i class="fa fa-calendar"></i>
                    {{ Carbon::parse($event->start_date)->format('d F Y') }},
                    {{ Carbon::parse($event->start_time)->format('H:i T') }} <i class="fa fa-arrow-right"></i>
                    {{ Carbon::parse($event->end_date)->format('d F Y') }},
                    {{ Carbon::parse($event->end_time)->format('H:i T') }} </p>
                <p>{!! $event->description !!}</p>

                <div class="row">
                    <div id="schedule" class="col-md-6 col">
                        <h3 class="mt-5 mb-4">
                            <span style="color: #304156;">
                                <i class="fa fa-calendar teks-tebal" style="font-size: 24px;"></i>
                            </span>
                            Schedule
                        </h3>
                        @foreach ($event->sessions as $session)
                            @if ($session->overview == 1)
                                <div class="col-md mt-3">
                                    <button class="btn btn-sm bg-primer text-light mb-2" style="border-radius:25px;">
                                        <b>
                                            {{ Carbon::parse($session->date)->format('d F Y') }}
                                        </b>
                                    </button>
                                    <ul style="color: #E6286E; padding-left:20px;">
                                        <li style="color: #828D99;">
                                            {{ $session->start_time }} - {{ $session->end_time }} WIB
                                        </li>
                                        <h5 class="teks-tebal" style="color: #000000; margin-top: 2%;">
                                            {{ $session->title }}
                                        </h5>
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
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div id="speaker" class="col-md-6 col">
                        <h3 class="mt-5 mb-4">
                            <span style="color: #304156;">
                                <i class="fa fa-microphone teks-tebal" style="font-size: 24px;"></i>
                            </span>
                            Speakers
                        </h3>
                        @foreach ($event->speakers as $speaker)
                            <div class="bagi bagi-2">
                                <div class="square-speaker">
                                    <div class="d-flex">
                                        <div>
                                            <img src="{{ asset('storage/event_assets/' . $event->slug . '/speaker_photos/' . $speaker->photo) }}"
                                                style="margin-left:15%; margin-top:15%; width: 100px; height: 100px;border-radius:50%;">
                                        </div>
                                        <div style="margin-left: 10%; margin-top:10%; ">
                                            <div class="teks-tebal">
                                                {{ $speaker->name }}
                                            </div>
                                            <div class="teks-tipis" style="color: #C4C4C4;">
                                                {{ $speaker->title }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="margin-bottom: 10%; margin-bottom: 5%; margin-top: 10%;">
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
                <div class="row">
                    <div id="sponsor" class="col-md-6 col">
                        <h3 class="mt-5 mb-4">
                            <span style="color: #304156;">
                                <i class="fa fa-handshake teks-tebal" style="font-size: 24px;"></i>
                            </span>
                            Sponsor
                        </h3>
                        @foreach ($event->sponsors as $sponsor)
                            {{-- @if ($sponsor->type == 'Platinum Sponsor') --}}
                            <div class="bagi bagi-2">
                                <div class="square-sponsor text-center">
                                    <div>
                                        <img src="{{ asset('storage/event_assets/' . $event->slug . '/sponsor_logo/' . $sponsor->logo) }}"
                                            class="thumb-sponsor">
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
                            {{-- @endif --}}
                        @endforeach
                    </div>
                    {{-- tempat icon booths --}}
                    <div id="booth" class="col-md-6 col">
                        <h3 class="mt-5 mb-4">
                            <span style="color: #304156;">
                                <i class="fa fa-store teks-tebal" style="font-size: 24px;"></i>
                            </span>
                            Booth
                        </h3>
                        @foreach ($event->exhibitors as $exhibitor)
                            @if ($exhibitor->overview == 1)
                                <div class="bagi bagi-2">
                                    <div class="booth-item text-center">
                                        <a href="{{ $exhibitor->website }}">
                                            <img src="{{ asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_booth_image/' . $exhibitor->booth_image) }}"
                                                style="width:100%; height:180px;">
                                            <h5>{{ $exhibitor->name }}</h5>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection

@section('content2')
    @include('stream.chat')
@endsection

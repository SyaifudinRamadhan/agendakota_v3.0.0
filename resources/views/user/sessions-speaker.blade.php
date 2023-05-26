@extends('layouts.user')

@section('title', 'My Sessions')

@section('head.dependencies')
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">

@endsection

@php
use Carbon\Carbon;
use App\Models\Organization;
$tanggalsekarang = Carbon::now()->toDateString();
@endphp

@section('content')

    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/eventDetailPage.css') }}">


    <div class="">
        <div class="bagi mb-3">
            @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
            <h2>Speaker Area</h2>
            <span class="text-secondary">Temukan semua event yang kamu diundang sebagai pembicara</span>
            @else
            <h2>Peformers Area</h2>
            <span class="text-secondary">Temukan semua event yang kamu diundang sebagai bintang tamu</span>
            @endif
            
        </div>
    </div>

    <div class="exhibitionstp tabcontent-exh" style="display: block; border: none;">

        <div class="row my-2">
            <div class="col">
                {{-- tab schedule --}}
                <div class="tab scrollmenu" style="border: none; border-bottom: 1px solid #F0F1F2;">
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($rundowns as $key => $value)
                        @if ($i == 0)
                            <button class="tab-btn tablinks-schedule-2 active mr-4 fs-15"
                                onclick="opentabs(event, 'schedule-2', '{{ $key }}-2')">
                                {{ Carbon::parse($key)->format('d M Y') }}
                            </button>
                        @else
                            <button class="tab-btn tablinks-schedule-2 mr-4 fs-15"
                                onclick="opentabs(event, 'schedule-2', '{{ $key }}-2')">
                                {{ Carbon::parse($key)->format('d M Y') }}
                            </button>
                        @endif

                        @php
                            $i++;
                        @endphp
                    @endforeach

                    @php
                        $j = 0;
                    @endphp
                    @foreach ($rundowns as $key => $value)
                        @if ($j == 0)
                            <div id="{{ $key }}-2" class="tabcontent-schedule-2 mt-4 lebar-100 fs-15"
                                style="display: block; border: none;">

                                <table class="table table-striped">
                                    <tbody>
                                        @foreach ($value as $val)
                                            @php
                                                $mailMatch = false;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <b>{{ $val->start_time }} -
                                                        {{ $val->end_time }}</b>
                                                    <br><br>
                                                    Sampai Tanggal : <br>
                                                    {{ Carbon::parse($val->end_date)->format('d M Y') }}
                                                </td>
                                                <td style="width: 70%">
                                                    <b>{{ $val->title }}</b>
                                                    <br>
                                                    {{ $val->description }}
                                                    <br>
                                                    <div class="row pd-tbl mt-3">
                                                        <div class="col-12 no-pd-l">
                                                            @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
                                                            <p><b>Speakers :</b></p>
                                                            @else
                                                            <p><b>Peformers :</b></p>
                                                            @endif
                                                        </div>
                                                        @foreach ($val->sessionspeakers as $sessionSpeaker)
                                                            <div class="col-md-2 no-pd-l">
                                                                <img src="{{ asset('storage/event_assets/' . $event->slug . '/speaker_photos/' . $sessionSpeaker->speaker->photo) }}"
                                                                    style="width: 100%; border-radius:15px; float: left; margin-top: 1px; margin-left: 3px;">
                                                                <p class="p-1 text-center">
                                                                    {{ $sessionSpeaker->speaker->name }}
                                                                </p>
                                                            </div>

                                                            @php
                                                                if ($sessionSpeaker->speaker->email == $myData->email) {
                                                                    $mailMatch = true;
                                                                }
                                                            @endphp
                                                        @endforeach
                                                    </div>


                                                    @if ($mailMatch == true)
                                                        <a href="{{ route('streamSpecial', ['0', $val->id]) }}"
                                                            class="btn btn-sm bg-primer rounded-5 fs-3-0 ke-kanan mb-3 mr-3">
                                                            Join Stream
                                                        </a>Î
                                                    @else
                                                        <a href="{{ route('streamSpecial', ['0', $val->id]) }}"
                                                            class="btn btn-sm btn-secondary rounded-5 fs-3-0 ke-kanan mb-3 mr-3">
                                                            Join Stream
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        @else
                            <div id="{{ $key }}-2" class="tabcontent-schedule-2 mt-4 lebar-100 fs-15"
                                style="display: none; border: none;">

                                <table class="table table-striped">
                                    <tbody>
                                        @foreach ($value as $val)
                                            @php
                                                $mailMatch = false;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <b>{{ $val->start_time }} -
                                                        {{ $val->end_time }}</b>
                                                    <br><br>
                                                    Sampai Tanggal : <br>
                                                    {{ Carbon::parse($val->end_date)->format('d M Y') }}
                                                </td>
                                                <td style="width: 70%">
                                                    <b>{{ $val->title }}</b>
                                                    <br>
                                                    {{ $val->description }}
                                                    <br>
                                                    <div class="row pd-tbl mt-3">
                                                        <div class="col-12 no-pd-l">
                                                            @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
                                                            <p><b>Speakers :</b></p>
                                                            @else
                                                            <p><b>Peformers :</b></p>
                                                            @endif
                                                        </div>
                                                        @foreach ($val->sessionspeakers as $sessionSpeaker)
                                                            <div class="col-md-2 no-pd-l">
                                                                <img src="{{ asset('storage/event_assets/' . $event->slug . '/speaker_photos/' . $sessionSpeaker->speaker->photo) }}"
                                                                    style="width: 100%; border-radius:15px; float: left; margin-top: 1px; margin-left: 3px;">
                                                                <p class="p-1 text-center">
                                                                    {{ $sessionSpeaker->speaker->name }}
                                                                </p>
                                                            </div>

                                                            @php
                                                                if ($sessionSpeaker->speaker->email == $myData->email) {
                                                                    $mailMatch = true;
                                                                }
                                                            @endphp
                                                        @endforeach
                                                    </div>

                                                    @if ($mailMatch == true)
                                                        <a href="{{ route('streamSpecial', ['0', $val->id]) }}"
                                                            class="btn btn-sm bg-primer rounded-5 fs-3-0 ke-kanan mb-3 mr-3">
                                                            Join Stream
                                                        </a>
                                                    @else
                                                        <a href="{{ route('streamSpecial', ['0', $val->id]) }}"
                                                            class="btn btn-sm btn-secondary rounded-5 fs-3-0 ke-kanan mb-3 mr-3">
                                                            Join Stream
                                                        </a>
                                                    @endif
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

        {{-- @foreach ($isSpeaker[0]->sesion as $speaker)
            @php
                $event = $speaker->event;
                
            @endphp
            <a style="text-decoration: none; color:black"
                href="{{ route('mySessionsSpeaker',[$event->id]) }}">
                <div class="bagi bagi-3 list-item">
                    <div class="wrap">
                        <div class="event-item grid">
                            <div class="tinggi-150"
                                bg-image="{{ asset('storage/event_assets/' . $event->slug . '/event_logo/thumbnail/' . $event->logo) }}">
                            </div>
                            <div class="detail smallPadding">
                                <div class="wrap">
                                    <div class="wrap">
                                        @if ($event->start_date > $tanggalsekarang)
                                            <div class="teks-upcoming">
                                                <p class="teks-tebal">Upcoming</p>
                                            </div>
                                        @elseif ($event->start_date <= $tanggalsekarang && $event->end_date >= $tanggalsekarang)
                                            <div class="teks-happening">
                                                <p class="teks-tebal">Happening</p>
                                            </div>
                                        @elseif ($event->start_date < $tanggalsekarang)
                                            <div class="teks-finished">
                                                <p class="teks-tebal">Finished</p>
                                            </div>
                                        @endif
                                    </div>
                                    <h4 class="title-card-event" style="position: relative">{{ $event->name }}</h4>
                                    <div class="desc-card-event teks-transparan organizer">Diadakan oleh
                                        <b>{{ $event->organizer->name }}</b>
                                    </div>
                                    <div class="date date-card-event">
                                        <i class="fas fa-calendar"></i> &nbsp;
                                        {{ Carbon::parse($event->start_date)->format('d M,') }}
                                        {{ Carbon::parse($event->start_time)->format('H:i') }} WIB -
                                        {{ Carbon::parse($event->end_date)->format('d M,') }}
                                        {{ Carbon::parse($event->end_time)->format('H:i') }} WIB
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach --}}

    </div>

@endsection

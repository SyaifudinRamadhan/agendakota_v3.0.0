@extends('layouts.user')

@section('title', 'My Exhibitions')

@section('head.dependencies')
@endsection

@php
    use Carbon\Carbon;
    $tanggalsekarang= Carbon::now()->toDateString();
@endphp

@section('content')

    <div class="">
        <div class="bagi mb-3">
            <h2>Your Events Exhibitions</h2>
            <span class="text-secondary">Temukan semua event pameranmu</span>
        </div>
    </div>

    <div class="exhibitionstp tabcontent-exh" style="display: block; border: none;">
        @foreach ($exhibitors as $exhibitor)
        @php
            $event = $exhibitor->event;

        @endphp
        <a style="text-decoration: none; color:black" href="{{ route('myExhibitionEdit',[$event->id, $exhibitor->id]) }}">
            <div class="bagi bagi-3 list-item">
                <div class="wrap">
                    <div class="event-item grid">
                        <div class="img-card-top"
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
                                    <b>{{ $event->organizer->name }}</b></div>
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
        @endforeach
    </div>
@endsection

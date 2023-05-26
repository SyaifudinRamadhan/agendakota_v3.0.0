@extends('layouts.user')

@section('title', 'Rundowns')

@section('head.dependencies')
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/sessionsPage.css') }}">
@endsection

@php
    use Carbon\Carbon;
    use Carbon\CarbonPeriod;
    use App\Models\SessionSpeaker;
    $period = CarbonPeriod::create($event->start_date, $event->end_date);
@endphp

@section('content')
    <div class="mb-2">
        <div class="row">
            <div class="col-md-7 mb-4">
                <h2 style="margin-top: -3%; color: #304156; font-size:32px">QR Event</h2>
                <h4 class="mt-2" style="color: #979797; font-size:14">Download QR Event For User Self Checkin</h4>
                {{-- <div class="teks-transparan">{{ $event->name }}</div> --}}
            </div>
        </div>
    </div>
    <div class="mt-5 row">
        <div class="col-12">
            <h4 class="text-center"><b>QR Event {{ $event->name }}</b></h4>
            <p class="text-center">by <img height="40px" src="{{ asset('images/logo.png') }}" alt=""></p>
        </div>
        <div class="col-12 text-center mt-3">
            <img src="data:image/png;base64,{{BarCode2::getBarcodePNG($event->unique_code, 'QRCODE', 8,8)}}" class="img-qrcode" alt="barcode" /> 
        </div>
        <div class="col-12 text-center">
            <a href="{{ route('organization.event.qr-event-download',[$organizationID, $event->id]) }}" target="_blank">
                <button class="btn btn-outline-danger mx-auto btn-download mt-5"><i class="fa fa-download teks-primer"></i><span class="teks-primer"> Download QR</span></button>
            </a>  
        </div>
    </div>

@endsection

@section('javascript')
    <script src="{{ asset('js/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
   
    <script src="{{ asset('js/user/searchTable.js') }}"></script>
    <script src="{{ asset('js/tableTopScroll.js') }}"></script>
    <script src="{{ asset('js/user/cardMinimize.js') }}"></script>
   
@endsection

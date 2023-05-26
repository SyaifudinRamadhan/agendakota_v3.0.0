@extends('layouts.user')

@section('title', 'Ticket Selling')

@section('head.dependencies')
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/sessionsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/ticketSellPage.css') }}">
@endsection

@section('content')
<div id="guide" class="card mb-3">
    <div class="card-header bg-primer">
        <div class="row w-100">
            <div class="col-6 text-left">
                Petunjuk
            </div>
            <div id="close" class="col-6 text-right">
                <a class="pointer">
                    <i class="bi bi-caret-up-fill text-light"></i> Tutup
                </a>
            </div>
            <div id="open" class="col-6 text-right d-none">
                <a class="pointer">
                    <i class="bi bi-caret-down-fill text-light"></i> Buka
                </a>
            </div>
        </div>
    </div>
    <div id="body-card" class="card-body">
        <h5 class="card-title">Untuk Melakukan Check-In Peserta Silahkan Ikuti Petunjuk Berikut</h5>
        <p class="card-text">1. Jika kamera perangkat belum aktif secara otomatis, silahkan klik tombol minta akses</p>
        <p class="card-text">2. Kamera akan aktif otomatis jika device hanya memiliki satu kamera</p>
        <p class="card-text">3. Jika kamera device lebih dari satu, silahkan pilih kamera, lalu kik start scan</p>
        <p class="card-text">4. Arahkan QR Code / Barcode tepat di area scan</p>
        <p class="card-text">5. Silahkan tunggu sejenak</p>
        
        {{-- <p class="card-text text-danger">*(Tombol ini & tombol check-in masih belum berfungsi masih proses)</p> --}}
        {{-- <a id="btn-ch-scan" class="btn btn-warning">Ubah Mode : USB QR Scan</a> --}}
    </div>
</div>
    <input type="hidden" id="position" value="user">
    <div style="max-width: 100%" id="reader"></div>
    
@endsection

@section('javascript')
    {{-- Library qrcode scanner --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="{{ asset('js/user/cardMinimize.js') }}"></script>
    <script>
        cardAction('#guide');
    </script>
    <script src="{{ asset('js/user/checkInScan.js') }}"></script>
@endsection

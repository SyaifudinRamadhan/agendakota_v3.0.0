@extends('layouts.homepage')

@section('title', 'Explore')

@php
use Carbon\Carbon;
Carbon::setLocale('id');
@endphp

@section('head.dependencies')
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
    <style>
        body {
            background-color: #ecf0f1;
            color: #555;
        }

        #title-nav {
            font-size: 10pt;
        }

        .h-100 {
            height: 100%;
        }

        .event-title {
            font-size: 18px;
            font-weight: bold;
            /* height: 43.2px;
            overflow: clip; */
            /* overflow-wrap: anywhere; */
            height: 43.19px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .box-card {
            background-color: #fff;
            border-radius: 15px 15px 6px 6px !important;
            /* height: calc(100% - 20px) !important; */
        }

        .organizer-logo {
            width: 40px;
            height: 40px;
            margin-right: 10px;
            border-radius: 60px;
            background-color: #ddd;
        }

        #header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
        }

        .filter-bar {
            z-index: 1;
            position: fixed;
            top: 60px;
            left: 0px;
            right: 0px;
            padding: 15px 25px;
            background-color: #fff;
            border-bottom: 1px solid #ddd;
        }

        .filter-bar input[readonly] {
            background-color: #fff;
        }

        .wrap-auto {
            margin-top: unset;
        }

        nav.left {
            position: unset;
            width: 100%;
            height: 100%;
            background-color: #fff;
        }

        /* #sidebar nav{
            width: 23%;
            max-width: 350px;
            position: fixed;
            z-index: 1;
            top: 54px !important;
        } */
        nav.left .menu h4 {
            font-size: 16pt;
        }

        .col-side-nav {
            max-width: 350px;
            min-width: 190px;
        }

        .row-date {
            border: 1px solid #ddd !important;
            border-radius: 5px;
            /* padding: 0px 15px; */
            margin-left: 15px;
            margin-right: 15px;
        }

        .price-from {
            border: 1px solid #ddd !important;
            border-radius: 5px;
            /* padding: 0px 15px; */
            margin-left: 15px;
            margin-right: 15px;
        }

        .float {
            z-index: 2;
        }

        /* Atribut atribut desain baru */

        /* .switch {
            width: 36px;
            height: 24px;
        }

        .slider::before {
            height: 16px;
            width: 16px;
            left: 2px;
        }

        input:checked+.slider:before {
            transform: translateX(16px);
        } */

        .form-check-input {
            height: 100%;
            margin-top: unset;
        }

        .select2-container--default .select2-selection--single {
            border: unset;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #ee962a;
            font-weight: bold;
        }

        .select2-dropdown {
            border: 1px solid #aaa !important;
            font-size: 13px !important;
        }

        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #e5214f;
            color: white;
        }

        .select2-results__option {
            padding: 6px;
            user-select: none;
            -webkit-user-select: none;
            background-color: unset;
            color: black;
        }

        .show-date-time-selected {
            background-color: #fff;
            color: #000;
            font-weight: bold;
            font-size: 12px !important;
            margin-top: -35px;
        }

        /* CSS skeleton template */
        @keyframes progress-content {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        .bg-skeleton {
            background-color: #d7d6d6f5;
            animation: progress-content 1000ms infinite;
        }

        .img-skeleton {
            aspect-ratio: 4/3;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .title-skeleton {
            height: 15px;
        }

        .p1-skeleton {
            height: 13px;
            width: 80%;
        }

        .p2-skeleton {
            height: 13px;
            width: 70%
        }

        .p3-skeleton {
            height: 13px;
            width: 50%
        }

        .btn-skeleton {
            height: 20px;
        }
        .rounded-pill{
            border-radius: 15px;
        }


        /* Responsive car img in this page */
        @media (max-width: 767.9px) {
            .content img {
                top: 360px;
            }

            .label-responsive {
                display: unset;
            }

            .label-full {
                display: none;
            }

            .footer {
                margin-top: 404px;
            }
        }

        @media (max-width: 423px) {
            .content img {
                top: 440px;
            }
        }

        @media (min-width: 768px) {
            .box-text {
                /* height: calc(100% - 170px); */
                /* height: calc(100% - 110px); */
            }
        }

        @media screen and (max-width:845px) {
            /* .wrap-auto{
                margin-top: 190px !important;
            } */

            #sidebar {
                flex: 0 0 0%;
            }

            #sidebar nav {
                width: unset;
                max-width: 250px;
                position: fixed;
                z-index: 1;
                top: 54px !important;
            }

            .bg {
                z-index: 1;
            }

            .bg-block {
                display: block !important;
            }

            #sidebar nav {
                top: 75px !important;
            }

            nav.left {
                height: unset;
            }

            .col-side-nav {
                min-width: unset;
            }
        }
    </style>

    <style>
        @media screen and (max-width:845px) {
            #sidebar nav {
                top: 135px !important;
            }
        }
    </style>
@endsection

@section('content-parent')
    <a id="btn-filter-bar" href="#" onclick="showFilterBar()" class="float d-md-1-none">
        <i class="fa bi bi-funnel my-float"></i>
    </a>
    <a id="btn-filter-bar-close" href="#" onclick="hiddenFilterBar()" class="float d-md-1-none d-none">
        <i class="fa bi bi-x-lg my-float"></i>
    </a>
    <div class="bg"></div>
@endsection

@section('content')

    <div class="row w-100">
        <div id="sidebar" class="col-3 col-side-nav">
            <nav class="left nav-atas" style="{{ isset($streamPage) == true ? 'width:20%;' : 'top: 100px;' }}">
                <div class="menu">
                    <div class="wrap mb-1 ml-0" style="padding-left: 8px;">
                        <h4>
                            <b id="title-nav">Filter</b>
                        </h4>

                    </div>
                    {{-- onchange="filter('execution_type', this.value)" --}}
                    <a class="fs-15">
                        <li class="mt-2 fs-15">
                            <span class="font-weight-bold text-dark">Jenis Event</span>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="event-type" id="input-typ-0"
                                    value="all" checked>
                                <label class="form-check-label font-weight-bold text-dark fs-13" for="input-typ-0">
                                    Semua Jenis
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="event-type" id="input-typ-1"
                                    value="online">
                                <label class="form-check-label font-weight-bold text-dark fs-13" for="input-typ-1">
                                    Online Event
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="event-type" id="input-typ-2"
                                    value="offline">
                                <label class="form-check-label font-weight-bold text-dark fs-13" for="input-typ-2">
                                    Offline Event
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="event-type" id="input-typ-3"
                                    value="hybrid">
                                <label class="form-check-label font-weight-bold text-dark fs-13" for="input-typ-3">
                                    Hybrid Event
                                </label>
                            </div>
                            <hr>
                        </li>
                    </a>
                    <a class="fs-15">
                        <li class="mt-3 fs-15">
                            {{-- <i class="fa fa-lg bi bi-grid mr-2 col-md-2 d-inline"></i>Kategori
                        <select name="category" id="category" class="box mt-0 bg-putih fs-13" onchange="filter('category', this.value)">
                            <option value="">Semua Event</option>
                            @foreach ($categories as $category)
                                <option {{ $request->category == $category->name ? "selected='selected'" : "" }}>{{ $category->name }}</option>
                            @endforeach
                        </select> --}}
                            <span class="font-weight-bold text-dark">Harga Tiket</span>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="event-price" id="input-prc-1"
                                    value="option1" checked>
                                <label class="form-check-label font-weight-bold text-dark fs-13" for="input-prc-1">
                                    Semua Tiket
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="event-price" id="input-prc-2"
                                    value="option2">
                                <label class="form-check-label font-weight-bold text-dark fs-13" for="input-prc-2">
                                    Gratis
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="event-price" id="input-prc-3"
                                    value="option3">
                                <label class="form-check-label font-weight-bold text-dark fs-13" for="input-prc-3">
                                    Berbayar
                                </label>
                            </div>
                            <div id="form-price" class="d-none fs-13">
                                Mulai dari
                                <input type="number" class="box m-0 price-from no-border no-shadow teks-tebal"
                                    style="background-color: #fff;" name="price_from" id="price"
                                    placeholder="Input harga ...">
                            </div>
                            <hr>
                        </li>
                    </a>

                    <a class="fs-15">
                        <li class="mt-3 fs-15" style="padding: 0px 5%">
                            {{-- <i class="fa fa-lg bi bi-building mr-2 col-md-2 d-inline"></i>Kota --}}
                            <select name="city" id="city" class="box mt-0 bg-putih fs-13">
                                <option style="color:#b17224" value="">Lokasi</option>
                                <option style="color:#b17224" value="all">Semua Lokasi</option>
                                @foreach ($cities as $city)
                                    <option {{ $request->city == $city->name ? "selected='selected'" : '' }}>
                                        {{ $city->name }}</option>
                                @endforeach
                            </select>
                            <hr>
                        </li>
                    </a>

                    <a class="fs-15">
                        <li class="mt-3 fs-15" style="padding: 0px 5%">
                            {{-- <i class="fa fa-lg bi bi-building mr-2 col-md-2 d-inline"></i>Kota --}}
                            <select name="category" id="category" class="box mt-0 bg-putih fs-13">
                                <option style="color:#b17224" value="">Kategori</option>
                                <option style="color:#b17224" value="all">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option {{ $request->category == $category['name'] ? "selected='selected'" : '' }}>
                                        {{ $category['name'] }}</option>
                                @endforeach
                            </select>
                            <hr>
                        </li>
                    </a>

                    <a class="fs-15">
                        <li class="mt-3 fs-15" style="padding: 0px 5%">
                            {{-- <i class="fa fa-lg bi bi-building mr-2 col-md-2 d-inline"></i>Kota --}}
                            <select name="topic" id="topic" class="box mt-0 bg-putih fs-13">
                                <option style="color:#b17224" value="">Topik</option>
                                <option style="color:#b17224" value="all">Semua Topik</option>
                                @foreach ($topics as $topic)
                                    <option {{ $request->topic == $topic ? "selected='selected'" : '' }}>
                                        {{ $topic }}</option>
                                @endforeach
                            </select>
                            <hr>
                        </li>
                    </a>

                    <a class="fs-15">
                        <li class="mt-3 fs-15">
                            <label id="label-date-pick" class="font-weight-bold text-dark pointer d-flex"
                                for="date">Waktu Mulai
                                <span class="teks-merah ml-auto my-auto mr-2 fas fa-filter pointer"
                                    style="top: 87px;right: 45px;"></span>
                            </label>
                            <div id="btn-clean-pick" class="font-weight-bold text-dark pointer d-none">Waktu Mulai
                                <span class="teks-merah ml-auto my-auto mr-2 fas fa-times pointer"
                                    style="top: 87px;right: 45px;"></span>
                            </div>
                            <input type="text" class="box m-0 no-border no-shadow teks-tebal fs-13"
                                style="background-color: #fff; height: 0;" name="date" id="date"
                                value="{{ $request->start_date != '' ? $request->start_date . ' - ' . $request->end_date : 'Semua Waktu' }}">
                            <div class="show-date-time-selected text-center ml-2 mr-2 fs-13">
                                <div id="text-time-selected" class=fs-13>Semua Waktu</div>
                            </div>
                        </li>
                    </a>
                </div>
            </nav>
        </div>
        <div id="content-exp" class="col no-pd-l no-pd-r">

            <div class="wrap small wrap-auto mt-3 mb-3">
                <div id="event-show" class="row">
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script src="{{ asset('js/base.js') }}"></script>
    <script src="{{ asset('js/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/flatpickr/dist/l10n/id.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment-with-locales.min.js"></script>
    <script>
        let priceFrom = "{{ $request->price_from }}";

        document.addEventListener('DOMContentLoaded', function() {
            if (priceFrom != 'all' && priceFrom != 'free' && priceFrom != '') {
                document.getElementById('form-price').classList.remove('d-none');
                document.querySelector('#form-price input').value = priceFrom;

            }
        });
    </script>
    <script>
        let url = new URL(document.URL);
        const filter = (type, value) => {
            if (type == 'date') {
                let date = value.split(' - ');
                if (date[1] == undefined) {
                    return false;
                }
                url.searchParams.set('start_date', date[0]);
                url.searchParams.set('end_date', date[1]);
            } else {
                url.searchParams.set(type, value);
            }
            window.location = url.toString();
        }

        const priceFilter = (value) => {
            if (value == 'paid') {
                document.getElementById('form-price').classList.remove('d-none');
            } else {
                filter('price_from', value);
            }
        }

        const datePicker = flatpickr("#date", {
            // mode: 'range',
            dateFormat: 'Y-m-d',
            locale: 'id',
            disableMobile : true,
        });


        const citySelect = $('#city').select2({
            placeholder: "Lokasi",
            allowClear: true
        });
        const categorySelect = $('#category').select2({
            placeholder: "Kategori",
            allowClear: true
        })
        const topicSelect = $('#topic').select2({
            placeholder: "Topik",
            allowClear: true
        })

        const removeDate = () => {
            let fullUrl = window.location.href.split("?");
            let params = new URLSearchParams(fullUrl[1]);
            params.delete('start_date');
            params.delete('end_date');
            console.log(fullUrl[0]);
            window.location = `${fullUrl[0]}?${params.toString()}`;
        }
    </script>
    <script>
        function showFilterBar() {
            document.getElementById('btn-filter-bar').classList.add('d-none');
            document.getElementById('btn-filter-bar-close').classList.remove('d-none');
            document.querySelector('#sidebar nav').classList.add('d-block');
            document.querySelector('.bg').classList.add('bg-block');
        }

        function hiddenFilterBar() {
            document.getElementById('btn-filter-bar').classList.remove('d-none');
            document.getElementById('btn-filter-bar-close').classList.add('d-none');
            document.querySelector('#sidebar nav').classList.remove('d-block');
            document.querySelector('.bg').classList.remove('bg-block');
        }
    </script>
    <script src="{{ asset('js/explore-templating.js') }}"></script>
    <script src="{{ asset('js/explore.js') }}"></script>
    <script>
        setDataEventByCategory(<?php echo json_encode($events); ?>, <?php echo json_encode($eventPrices); ?>);
    </script>
@endsection

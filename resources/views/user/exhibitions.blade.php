@extends('layouts.homepage')

@section('title', 'Exhibitions')

@section('head.dependencies')
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/mainUser.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/handbookPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/eventDetailPage.css') }}">

@endsection

@section('content')

    @php
        use Carbon\Carbon;
        $tanggalSekarang = Carbon::now()->toDateString();
    @endphp

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .bg-putih.pt-5 {
            margin-top: auto;
        }

        #content-section {
            margin-top: 170px;
        }

        .side-nav-exh {
            box-shadow: #00000045 0 0 20px 0px;
            margin-left: 20px;
            padding-right: 0.5rem !important;
            z-index: 2;
            max-height: fit-content;
            position: fixed;
            width: 15.666667%;
            max-width: 222px;
            min-width: 140px;
            font-size: 11pt !important;
        }

        .spc-btn {
            font-size: 11pt !important;
        }

        .spc-btn button {
            font-size: 10pt;
            padding: 5px;
        }

        .footer-sec {
            display: none;
        }

        .nav-show-btn {
            position: fixed;
            top: 200px;
            left: 0;
            transform: rotate(90deg) translate(230%, 70%);
            z-index: 2;
        }

        .nav-show-btn a div {
            padding: 10px;
        }

        .img-product {
            object-fit: contain;
        }

        .cover-item {
            background-color: #e5214f2b;
        }

        #col-nav {
            flex: 0 0 222px;
            max-width: 222px;
        }

        #col-content {
            flex: 0 0 calc(100% - 222px);
            max-width: calc(100% - 222px);
        }

        .icon-blank {
            font-size: 100pt;
            opacity: 40%;
        }

        .text-blank {
            opacity: 70%;
        }

        @media (max-width: 992px) {
            .col-nav {
                display: none;
            }

            #col-content {
                flex: unset;
                max-width: unset;
            }
        }

        @media(min-width: 992px) {
            .nav-show-btn {
                display: none;
            }

            .col-nav {
                display: unset !important;
            }
        }
    </style>

    <div class="pl-5 pr-5 mb-5">
        <div class="row">
            <div class="col-md text-left">
                @if (count($exhibitors) != 1)
                    <h4>{{ $event->name }} Exhibitions</h4>
                    <span class="text-secondary">Event Exhibitions</span>
                @else
                    <div class="row">
                        <div class="col-2">
                            <h4 class="d-flex">
                                <img src="{{ asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_logo/' . $exhibitors[0]->logo) }}"
                                    class="rounded-circle asp-rt-1-1 mx-auto" width="58px" height="58px">
                            </h4>
                        </div>
                        <div class="col-10 d-flex">
                            <h4 class="my-auto ml-1">
                                {{ $exhibitors[0]->name }} | {{ $event->name }} Exhibitions
                            </h4>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md pl-0 text-right">
                <a>
                    <button type="button" class="bg-primer mt-0 btn-add btn-no-pd float-right mr-2 mb-4"
                        onclick="history.back()">
                        <i class="fas fa-arrow-left fs-20"></i> Kembali
                    </button>
                </a>
            </div>
        </div>
    </div>

    <div class="d-block mt-3">
        @if (count($exhibitors) == 0)
            <div class="container mt-4 rata-tengah">
                <i class="bi bi-bag font-img teks-primer mb-3"></i>
                <h3>Exhibition Masih Kosong</h3>
                <p>Adakan berbagai event menarik di AgendaKota</p>
            </div>
        @else
            @if (count($exhibitors) != 1)
                <div class="container">
                    <div class="row">
                        @foreach ($exhibitors as $boothProducts)
                            <div class="col-xl-4" style="margin-top:2%;">
                                <a href="{{ route('user.home.exhibitions', [$event->id, $boothProducts->id]) }}">
                                    <div class="bg-putih bayangan-5 rounded">
                                        <div class="">

                                            <div class="wrap">

                                                <img src="{{ asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_logo/' . $boothProducts->logo) }}"
                                                    class="rounded-circle asp-rt-1-1 mt-4" width="58px" height="58px">


                                                <div class="title-exhibitor pt-3 ml-1">
                                                    {{ $boothProducts->name }}
                                                </div>
                                                <div
                                                    style="padding-left: 10px; margin-top:-1%; color: #C4C4C4; font-size:10pt;">

                                                </div>
                                                <div class="teks-primer font-info mt-2 ml-2 pb-4">
                                                    @if ($boothProducts->virtual_booth == 1)
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
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="row">
                    <div id="nav-show-btn" class="nav-show-btn">
                        <a>
                            <div class="bg-primer pointer">
                                Navigasi ^
                            </div>
                        </a>
                    </div>
                    <div id="col-nav" class="col-nav col-lg-2">
                        <div>
                            <div class="rounded-5 bg-white p-2 text-center side-nav-exh">
                                <div class="row">
                                    <div class="col-12 pb-2">
                                        <button id="nav-1"
                                            onclick="opentabs(event, 'event-detail', 'profil'); activeClick('nav-1', 'btn-side-nav')"
                                            class="btn-side-nav bg-primer bg-primer-transparent w-100 pr-2 pl-2 text-left">
                                            <i class="bi bi-easel2-fill"></i>
                                            Booth
                                        </button>
                                    </div>
                                    <div class="col-12 pb-2">
                                        <button id="nav-2"
                                            onclick="opentabs(event, 'event-detail', 'handbook'); activeClick('nav-2', 'btn-side-nav')"
                                            class="btn-side-nav bg-primer-transparent w-100 pr-1 pl-2 text-left"> <i
                                                class="bi bi-book-half"></i>
                                            Handbook
                                        </button>
                                    </div>
                                    <div class="col-12 pb-2">
                                        <button id="nav-3"
                                            onclick="opentabs(event, 'event-detail', 'product'); activeClick('nav-3', 'btn-side-nav')"
                                            class="btn-side-nav bg-primer-transparent w-100 pr-2 pl-2 text-left"> <i
                                                class="bi bi-boxes"></i>
                                            Product
                                        </button>
                                    </div>
                                    <div class="col-12 pb-2">
                                        <button id="nav-4"
                                            onclick="opentabs(event, 'event-detail', 'video'); activeClick('nav-4', 'btn-side-nav')"
                                            class="btn-side-nav bg-primer-transparent w-100 pr-2 pl-2 text-left"> <i
                                                class="bi bi-file-play-fill"></i>
                                            Video
                                        </button>
                                    </div>
                                    <div class="col-12 spc-btn pb-2">
                                        @foreach ($exhibitors as $exhibitor)
                                            @php
                                                $phoneNumber = str_split($exhibitor->phone);
                                                if($phoneNumber[0] == 0){
                                                    $phoneNumber[0] = '62';
                                                    $phoneNumber = implode($phoneNumber);
                                                }else if($phoneNumber[0] != '6'){
                                                    $phoneNumber = '62'.$exhibitor->phone;
                                                }
                                            @endphp
                                            @if ($exhibitor->virtual_booth == '1' || $exhibitor->virtual_booth == 1)
                                                <a href="{{ route('user.home.exhibitions.vidcall', [$exhibitor->id]) }}">
                                                    <button class="btn-side-nav bg-primer-transparent w-100"> <i
                                                            class="bi bi-camera-video-fill mr-auto"></i>
                                                        Meet With Exhibitor
                                                    </button>
                                                </a>
                                            @else
                                                <a href="#" disabled>
                                                    <button class="btn-side-nav btn-secondary w-100">
                                                        <i class="bi bi-camera-video-fill mr-auto"></i>
                                                        Meet With Exhibitor
                                                    </button>
                                                </a>
                                            @endif
                                        @endforeach

                                    </div>
                                    <div class="col-12 spc-btn">
                                        <a href="https://wa.me/{{ $phoneNumber }}" disabled>
                                            <button class="btn-side-nav btn-success w-100">
                                                <i class="bi bi-whatsapp mr-auto"></i>
                                                 Chat WhatsApp
                                            </button>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="col-content" class="col-lg-10">
                        <div id="profil" class="tabcontent-event-detail" style="display: block; border: none;">
                            <div style="background-color: #eb597b0f;">
                                <div class="pl-4 pr-4">
                                    <div class="row pb-5">
                                        @foreach ($exhibitors as $boothProducts)
                                            <div class="col-xl-8 mb-2 mt-2 rounded-5">
                                                {{-- <div class="tinggi-350 rounded-15 bg-banner"
                                                    style="background-image: url( '{{ asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_booth_image/' . $boothProducts->booth_image) }}' );">
                                                </div> --}}
                                                <div class="tinggi-350 rounded-15 bg-banner d-flex bg-white rounded-8">
                                                    <img height="100%" class="h-100 rounded-8 my-auto mx-auto""
                                                        src="{{ asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_booth_image/' . $boothProducts->booth_image) }}"
                                                        alt="">
                                                </div>
                                            </div>
                                            <div class="col-xl-4 mb-2 mt-2 bg-putih rounded-5">
                                                <div class="mb-0 mt-0">
                                                    <div class="row row-no-margin py-3 pl-1 mb-3">
                                                        <div class="col-md-12 mt-2 mb-3">
                                                            <h4 class="teks-tebal">{{ $boothProducts->name }}</h4>
                                                        </div>
                                                        <div class="col-md-12 mb-2">
                                                            <h6>
                                                                <i class="bi bi-map"></i>
                                                                &nbsp;{{ $boothProducts->address }}
                                                            </h6>
                                                        </div>
                                                        <div class="col-md-12 mb-2">
                                                            <h6>
                                                                <i class="bi bi-envelope"></i>
                                                                &nbsp;{{ $boothProducts->email }}
                                                            </h6>
                                                        </div>
                                                        <div class="col-md-12 mb-2">
                                                            <h6>
                                                                <i class="bi bi-telephone"></i>
                                                                &nbsp;+{{ $phoneNumber }}
                                                            </h6>
                                                        </div>
                                                        <div class="col-md-12 mb-3">
                                                            <h6>
                                                                <i class="bi bi-globe"></i> &nbsp;<a
                                                                    href="{{ $boothProducts->website }}"
                                                                    class="text-dark">{{ $boothProducts->website }}</a>
                                                            </h6>
                                                        </div>
                                                        <div class="col-3 mb-2">
                                                            <h6>
                                                                <a href="{{ $boothProducts->linkedin }}"><i
                                                                        class="bi bi-linkedin"></i></a>
                                                            </h6>
                                                        </div>
                                                        <div class="col-3 mb-2">
                                                            <h6>
                                                                <a href="{{ $boothProducts->instagram }}"><i
                                                                        class="bi bi-instagram"></i></a>
                                                            </h6>
                                                        </div>
                                                        <div class="col-3 mb-2">
                                                            <h6>
                                                                <a href="{{ $boothProducts->twitter }}"><i
                                                                        class="bi bi-twitter"></i></a>
                                                            </h6>
                                                        </div>
                                                        <div class="col-3 mb-2">
                                                            <h6>
                                                                <a href="https://wa.me/{{ $phoneNumber }}"><i
                                                                        class="bi bi-whatsapp"></i></a>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="pl-4 pr-4">
                                <div class="row">
                                    <div class="col-12  mt-4 ml-3 mb-5">
                                        @foreach ($exhibitors as $exhibitor)
                                            <h4 class="mb-3">{{ $exhibitor->name }}</h4>
                                            <p class="mb-3">Kategori Exhibition : {{ $exhibitor->category }}</p>
                                            <p class="mb-1">{{ $exhibitor->description }}</p>

                                            @if ($exhibitor->virtual_booth == '1' || $exhibitor->virtual_booth == 1)
                                                <a href="{{ route('user.home.exhibitions.vidcall', [$exhibitor->id]) }}"><button
                                                        class="mt-4 btn btn-outline-primer rounded-5"><i
                                                            class="bi bi-camera-video fs-20"></i>&nbsp; Meet With
                                                        Exhibitior</button></a>
                                            @else
                                                <p class="mt-5 mb-1">
                                                    << --- Exhibitor tidak mnyediakan virtual booth / exhibition --->>
                                                </p>
                                                <a href="#"><button class="mt-4 btn btn-secondary rounded-5"
                                                        disabled><i class="bi bi-camera-video fs-20"></i>&nbsp; Meet With
                                                        Exhibitior</button></a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pl-4 pr-4">
                            <div class="row">
                                <div id="product" class="tabcontent-event-detail col-12  mt-4 ml-3 mb-5"
                                    style="display: none; border: none;">
                                    <div class="row">

                                        @foreach ($exhibitors as $boothProducts)
                                            @if (count($boothProducts->products) == 0)
                                                <div class="col-12 mb-4 text-center">
                                                    <i class="bi bi-boxes teks-primer icon-blank"></i>
                                                    <h3 class="text-blank">Produk Masih Kosong</h3>
                                                </div>
                                            @else
                                                @foreach ($boothProducts->products as $prd)
                                                    <div class="col-lg-3 mb-4">

                                                        <div class="bg-putih rounded-5 bayangan-5">
                                                            <a href="{{ $prd->url }}">
                                                                <div class="tinggi-150 rata-tengah cover-item">
                                                                    <img class="img-product" src="{{ asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_products/'.$prd->image) }}"
                                                                        width="100%" height="100%" alt="">
                                                                </div>
                                                            </a>
                                                            <div class="">
                                                                <div class="wrap pb-1">
                                                                    <a href="{{ $prd->url }}">
                                                                        <h6 class="detail font-inter-header mt-2 text-dark"
                                                                            style="text-decoration: none">
                                                                            Lihat Detail
                                                                        </h6>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div id="handbook" class="tabcontent-event-detail col-12  mt-4 ml-3 mb-5"
                                    style="display: none; border: none;">
                                    <div class="row">
                                        @foreach ($exhibitors as $exh)
                                            @if (count($exh->handbooks) == 0)
                                                <div class="col-12 mb-4 text-center">
                                                    <i class="bi bi-book-half teks-primer icon-blank"></i>
                                                    <h3 class="text-blank">Handbook Masih Kosong</h3>
                                                </div>
                                            @else
                                                @foreach ($exh->handbooks as $handbook)
                                                    @if ($handbook->type_file == 'photo')
                                                        <div class="col-lg-3 mb-4">
                                                            <div class="bg-putih rounded-5 bayangan-5">
                                                                <a
                                                                    href="{{ asset('storage/event_assets/' . $handbook->event->slug . '/event_handbooks/' . $handbook->file_name) }}">
                                                                    <div class="tinggi-150"
                                                                        bg-image="{{ asset('storage/event_assets/' . $handbook->event->slug . '/event_handbooks/' . $handbook->file_name) }}">
                                                                    </div>
                                                                </a>
                                                                <div class="">
                                                                    <div class="wrap pb-1">
                                                                        <h6 class="detail font-inter-header mt-2">
                                                                            {{ $handbook->file_name }}

                                                                        </h6>
                                                                        <p class="teks-transparan fs-normal detail">
                                                                            Uploaded
                                                                            {{ Carbon::parse($handbook->created_at)->format('d M,') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif ($handbook->type_file == 'video')
                                                        <div class="col-lg-3 mb-4">
                                                            <div class="bg-putih rounded-5 bayangan-5">
                                                                <div class="tinggi-150">
                                                                    <iframe class="lebar-100 rounded-15-top"
                                                                        height="100%"
                                                                        src="{{ $handbook->file_name }}"></iframe>
                                                                </div>
                                                                <div class="">
                                                                    <div class="wrap pb-1">
                                                                        <h6 class="detail font-inter-header mt-2">
                                                                            {{ $handbook->file_name }}

                                                                        </h6>
                                                                        <p class="teks-transparan fs-normal detail">
                                                                            Uploaded
                                                                            {{ Carbon::parse($handbook->created_at)->format('d M,') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif ($handbook->type_file == 'documents')
                                                        <div class="col-lg-3 mb-4">
                                                            <a
                                                                href="{{ asset('storage/event_assets/' . $handbook->event->slug . '/event_handbooks/' . $handbook->file_name) }}">
                                                                <div class="bg-putih rounded-5 bayangan-5">
                                                                    <div class="tinggi-150 rata-tengah">
                                                                        <i
                                                                            class="fa bi bi-file-earmark-text mt-5 teks-primer text-icon-2"></i>
                                                                    </div>
                                                                    <div class="">
                                                                        <div class="wrap pb-1">
                                                                            <h6 class="detail font-inter-header mt-2">
                                                                                {{ $handbook->file_name }}

                                                                            </h6>
                                                                            <p class="teks-transparan fs-normal detail">
                                                                                Uploaded
                                                                                {{ Carbon::parse($handbook->created_at)->format('d M,') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div id="video" class="tabcontent-event-detail col-lg-6  mt-4 ml-3 mb-5"
                                    style="display: none; border: none;">
                                    <div class="row">
                                        @foreach ($exhibitors as $exhibitor)
                                            @if ($exhibitor->video == null || $exhibitor->video == '')
                                                <div class="col-lg-6 mb-4 text-center">
                                                    <i class="bi bi-file-play-fill teks-primer icon-blank"></i>
                                                    <h3 class="text-blank">Video Masih Kosong</h3>
                                                </div>
                                            @else
                                                <div class="col-12 text-center mb-4 bg-dark">
                                                    <iframe src="{{ $exhibitor->video }}" frameborder="0"
                                                        allowfullscreen="allowfullscreen"
                                                        mozallowfullscreen="mozallowfullscreen"
                                                        msallowfullscreen="msallowfullscreen"
                                                        oallowfullscreen="oallowfullscreen"
                                                        webkitallowfullscreen="webkitallowfullscreen"
                                                        style="width: 80%; aspect-ratio: 16 / 9"></iframe>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        @endif
    </div>



@endsection

@section('javascript')
    <script src="{{ asset('js/base.js') }}"></script>
    <script>
        let stateOpenNav = false;

        const activeClick = (idTraget, classTarget) => {
            document.querySelectorAll(`.${classTarget}`).forEach(element => {
                try {
                    element.classList.remove('bg-primer')
                } catch (err) {

                }
            });
            document.querySelector(`#${idTraget}`).classList.add('bg-primer')
        }

        document.querySelector('#nav-show-btn').addEventListener('click', function() {
            if (stateOpenNav === false) {
                this.style.transform = "rotate(90deg) translate(230%, -320%)"
                document.querySelector('#col-nav').style.display = 'unset'
                stateOpenNav = true
            } else {
                this.style.transform = "rotate(90deg) translate(230%, 70%)"
                document.querySelector('#col-nav').style.display = 'none'
                stateOpenNav = false
            }
        })
    </script>
@endsection

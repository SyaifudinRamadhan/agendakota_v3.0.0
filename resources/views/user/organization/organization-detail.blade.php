@extends('layouts.homepage')

@section('title', 'Organization Details')

@section('content')
    @php
    use Carbon\Carbon;
    $tanggalSekarang = Carbon::now()->toDateString();
    $countEventNow = 0;
    $countEventLast = 0;
    @endphp

    <style>
        .social-list a { color: #999; }
        .social-list a:hover { color: #e5214f; /*#eb597b*/ }
        .social-list i{
            margin-bottom: 1rem !important;
        }
        .social-list .text-center{
            align-self: center;
        }
        .img-avatar {
            width: 100px;
            height: 100px;
            position: absolute;
            border-radius: 100%;
            /* border: 6px solid white; */
            box-shadow: 1px 3px 5px 1px rgba(34, 35, 58, 0.5);
            /* background-image: linear-gradient(-60deg, #16a085 0%, #f4d03f 100%); */
            background-color: #fff;
            display: flex;
            top: 250px;
            /* left: 15px; */
            margin-left: 4%;
            z-index: 1;
        }

        .img-avatar img {
            width: 100%;
            margin: auto;
            border-radius: 100%;
        }

        .pd-15{
            padding-left: 15px;
            padding-right: 15px;
        }

        /* .left-profile{
            border-left: 1px solid rgba(28,29,29,.25);
            border-right: 1px solid rgba(28,29,29,.25);
        } */

        .top-banner{
            margin-top: 10px;
            display: flex;
            height: 232.5px;
            background-size: cover;
            background-position: center;
            background: linear-gradient(180deg, #FEF7E2 0%, #EEEEFD 100%);
            background-image: linear-gradient(rgb(254, 247, 226) 0%, rgb(238, 238, 253) 100%);
            background-position-x: initial;
            background-position-y: initial;
            background-size: initial;
            background-repeat-x: initial;
            background-repeat-y: initial;
            background-attachment: initial;
            background-origin: initial;
            background-clip: initial;
            background-color: initial;
        }
        .top-banner .img-banner{
            margin: auto;
            /* display: flex; */
            max-height: 232px;
            /* height: 100%; */
            width: 100%;
            max-width: 1160px;
            aspect-ratio: 5/1;
        }

    </style>
    {{-- @if (!isset($myData))
        <style>
            .img-avatar{
                top: 350px;
            }
            #content-section{
                margin-top: 150px
            }
        </style>
    @endif --}}
    <style>
        .img-avatar{
            top: 350px;
        }
        #content-section{
            margin-top: 150px
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/user/organization/profileOrganizationPage.css') }}">

    <div class="container" style="max-width: 1242.5px;">
        <div class="img-avatar">
            <img src="{{ $organizations->logo == ''? asset('images/profile-user.png'): asset('storage/organization_logo/' . $organizations->logo) }}" alt="">
        </div>
        <div class="row">
            <div class="col-12">
                <div class="top-banner" style="background-image: url({{ asset('images/pattern.png') }});
                border-bottom-left-radius: 7px;
                border-bottom-right-radius: 7px;">
                    @if ($organizations->banner_img != null || $organizations->banner_img != '')
                        <img class="img-banner" width="100%" src="{{ asset('storage/organization_logo/'.$organizations->banner_img) }}" alt="">
                    @endif
                </div>
                {{-- <img src="{{ asset('images/homepagedetailorganisasi.jpg') }}" class="asp-rt-8-2 w-100" alt="" srcset=""> --}}
            </div>
            
            <div class="col-12">
                <div class="row pd-15">
                    <div class="col-md-3 left-profile">
                        <div class="row row-no-margin bayangan-5 border py-3 pl-3 mb-3 mt-2" style="border-radius: 14px; min-height: 420px;">
                            <div class="row d-flex align-items-center pl-3 pr-3 mt-5">
                                {{-- <div class="col-md-3 no-pd-l no-pd-r mr-4">
                                    <img src="{{ $organizations->logo == ''? asset('images/profile-user.png'): asset('storage/organization_logo/' . $organizations->logo) }}"
                                        class="rounded-circle asp-rt-1-1 rsps-profile mb-2" width="100%" height="100%">
                                </div> --}}
                                <div class="col-12">
                                    <div class="row">
                                        <h4 class="teks-tebal fs-17" style="color: black;">
                                            {{ $organizations->name }}
                                        </h4>
                                    </div>
                                    <div class="row">
                                        <p style="color: #828D99; margin-bottom: 0px;">{{ $organizations->type }} | Interest : {{ $organizations->interest }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row pl-3 mr-3 social-list">
                                <div class="col-md-12 mt-4 no-pd-l">
                                    <p style="color: #828D99;">{{ $organizations->description }}</p>
                                </div>
                                <div class="col-md-12 mt-2 text-center">
                                    @if ($organizations->no_telepon != "")
                                            <a class="p-3" href="https://wa.me/62{{ $organizations->no_telepon }}" target="_blank">
                                                <i class="fa fa-phone"></i>
                                            </a>
                                    @endif
                                    @if ($organizations->website != "")
                                            <a class="p-3" href="{{ $organizations->website }}" target="_blank">
                                                <i class="fab fa-firefox"></i>
                                            </a>
                                    @endif
                                    @if ($organizations->linked != "")
                                            <a class="p-3" href="{{ $organizations->linked }}" target="_blank">
                                                <i class="fab fa-linkedin"></i>
                                            </a>
                                    @endif
                                    @if ($organizations->instagram != "")
                                            <a class="p-3" href="{{ $organizations->instagram }}" target="_blank">
                                                <i class="fab fa-instagram"></i>
                                            </a>
                                    @endif
                                    @if ($organizations->twitter != "")
                                            <a class="p-3" href="{{ $organizations->twitter }}" target="_blank">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                    @endif
                                </div>
                                {{-- @if ($organizations->no_telepon != "")
                                    <div class="col-2">
                                        <a href="https://wa.me/62{{ $organizations->no_telepon }}" target="_blank">
                                            <i class="fa fa-phone"></i>
                                        </a>
                                    </div>
                                @endif
                                @if ($organizations->website != "")
                                    <div class="col-2">
                                        <a href="{{ $organizations->website }}" target="_blank">
                                            <i class="fab fa-firefox"></i>
                                        </a>
                                    </div>
                                @endif
                                @if ($organizations->linked != "")
                                    <div class="col-2">
                                        <a href="#" target="_blank">
                                            <i class="fab fa-linkedin"></i>
                                        </a>
                                    </div>
                                @endif
                                @if ($organizations->instagram != "")
                                    <div class="col-2">
                                        <a href="{{ $organizations->instagram }}" target="_blank">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    </div>
                                @endif
                                @if ($organizations->twitter != "")
                                    <div class="col-2">
                                        <a href="{{ $organizations->twitter }}" target="_blank">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    </div>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab scrollmenu mt-2" style="border: none; border-bottom: 1px solid #F0F1F2;">
                            <button class="tab-btn tablinks-event-list active mr-4 no-pd-l no-pd-r fs-17"
                                onclick="opentabs(event, 'event-list', 'active-event')">Event Aktif</button>
                            <button class="tab-btn tablinks-event-list mr-4 no-pd-l no-pd-r fs-17"
                                onclick="opentabs(event, 'event-list', 'last-event')">Event Lalu</button>
                        </div>

                        <div id="active-event" class="tabcontent-event-list mt-4 ml-3 mb-5" style="display: block; border: none;">
                            
                            @foreach ($events as $event)
                                @if ($event->end_date >= $tanggalSekarang)
                                <div class="bagi bagi-3 list-item mb-2">
                                    <div class="wrap">
                                        <div class="bg-putih bayangan-5 rounded-5 smallPadding">
                                            <a href="{{ route('user.eventDetail',$event->slug) }}"
                                                style="text-decoration: none" class="text-dark pointer">
                                                <div class="img-card-top"
                                                    bg-image="{{ asset('storage/event_assets/' . $event->slug . '/event_logo/thumbnail/' . $event->logo) }}">
                                                </div>
                                                <div class="detail">
                                                    <div class="wrap">
                                                        <div class="teks-upcoming">
                                                            <p class="fs-11 mb-2">{{ $event->execution_type }}</p>
                                                        </div>
                                                        <h5 class="font-inter-header"
                                                            style="max-height: 1.25rem; overflow:hidden; text-overflow: ellipsis;">
                                                            {{ $event->name }}
            
                                                        </h5>
                                                        <li class="d-flex">
                                                            <div class="icon"><i class="fas bi bi-calendar"></i></div>
                                                            <div class="text desc-card">
                                                                {{ Carbon::parse($event->start_date)->isoFormat('D MMMM') }}-{{ Carbon::parse($event->end_date)->isoFormat('D MMMM') }}
                                                            </div>
                                                        </li>
                                                        
                                                        <li class="d-flex">
                                                            <div class="icon"><i class="fas bi bi-person"></i></div>
                                                            @php
                                                                $jumlahAttendes = 0;
                                                            @endphp
                                                            @foreach ($purchases as $purchase)
                                                                @if ($event->id == $purchase->event_id)
                                                                    @php
                                                                        $jumlahAttendes += 1;
                                                                    @endphp
                                                                @endif
                                                            @endforeach
                                                            <div class="text desc-card">{{ $jumlahAttendes }} attendees</div>
                                                        </li>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $countEventNow+=1;
                                @endphp
                                @endif
                            @endforeach
                            @if ($countEventNow == 0)
                                <div class="rata-tengah mt-4">
                                    <img src="{{ asset('images/calendar.png') }}">
                                    <h3>Temukan berbagai event menarik di kotamu</h3>
                                    <p>Adakan berbagai event menarik di AgendaKota</p>
                                </div>
                            @endif
                        </div>
                        <div id="last-event" class="tabcontent-event-list mt-4 ml-3 mb-5" style="display: none; border: none;">
                            @foreach ($events as $event)
                                @if ($event->end_date < $tanggalSekarang)
                                <div class="bagi bagi-3 list-item mb-2">
                                    <div class="wrap">
                                        <div class="bg-putih bayangan-5 rounded-5 smallPadding">
                                            <a href="{{ route('user.eventDetail',$event->slug) }}"
                                                style="text-decoration: none" class="text-dark pointer">
                                                <div class="img-card-top"
                                                    bg-image="{{ asset('storage/event_assets/' . $event->slug . '/event_logo/thumbnail/' . $event->logo) }}">
                                                </div>
                                                <div class="detail">
                                                    <div class="wrap">
                                                        <div class="teks-upcoming">
                                                            <p class="fs-11 mb-2">{{ $event->execution_type }}</p>
                                                        </div>
                                                        <h5 class="font-inter-header"
                                                            style="max-height: 1.25rem; overflow:hidden; text-overflow: ellipsis;">
                                                            {{ $event->name }}
            
                                                        </h5>
                                                        <li class="d-flex">
                                                            <div class="icon"><i class="fas bi bi-calendar"></i></div>
                                                            <div class="text desc-card">
                                                                {{ Carbon::parse($event->start_date)->isoFormat('D MMMM') }}-{{ Carbon::parse($event->end_date)->isoFormat('D MMMM') }}
                                                            </div>
                                                        </li>
                                                        
                                                        <li class="d-flex">
                                                            <div class="icon"><i class="fas bi bi-person"></i></div>
                                                            @php
                                                                $jumlahAttendes = 0;
                                                            @endphp
                                                            @foreach ($purchases as $purchase)
                                                                @if ($event->id == $purchase->event_id)
                                                                    @php
                                                                        $jumlahAttendes += 1;
                                                                    @endphp
                                                                @endif
                                                            @endforeach
                                                            <div class="text desc-card">{{ $jumlahAttendes }} attendees</div>
                                                        </li>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $countEventLast+=1;
                                @endphp
                                @endif
                            @endforeach
                            @if ($countEventLast == 0)
                                <div class="rata-tengah mt-4">
                                    <img src="{{ asset('images/calendar.png') }}">
                                    <h3>Temukan berbagai event menarik di kotamu</h3>
                                    <p>Adakan berbagai event menarik di AgendaKota</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script src="{{ asset('js/base.js') }}"></script>
@endsection

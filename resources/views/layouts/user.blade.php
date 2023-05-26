<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Agendakota</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('js/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">


    <!-- Fevicon -->
    <link rel="shortcut icon" href="{{ asset('images/icon-ak.png') }}">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">



    <script src="https://kit.fontawesome.com/bc29c7987f.js" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @yield('head.dependencies')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/mainUser.css') }}">

</head>

<body>

    @php
        // use ..\App\Http\Controllers\UserController;
        $organization_types = $myData->organization_types;
        $organization_interests = $myData->organization_interests;
        // $isExhibitor = UserController::isExhibitor();
        // dd($isExhibitor);
        if (!isset($organizationID)) {
        } else {
            if (isset($event)) {
                $eventID = $event->id;
            }
        }
    @endphp

    <header>
        <div style="display: flow-root; background-color:white">
            <a href="{{ route('user.homePage') }}" class="mr-2">
                <img src="{{ asset('images/logo.png') }}" class="logo">
            </a>
            <nav class="nav-atas" id="nav-atas" style="display: flex">
                <button id="sidebar-show" style="display: none;" class="btn btn-outline-primer btn-no-pd my-auto"
                    onclick="showSidebar()">
                    <i class="bi bi-layout-sidebar"></i>&nbsp; Sidebar
                </button>
                <button id="sidebar-hidden" style="display: none;" class="btn btn-outline-primer btn-no-pd my-auto"
                    onclick="hiddSidebar()">
                    <i class="bi bi-arrows-angle-expand"></i>&nbsp; Hide Sidebar
                </button>
                @if (isset($isManageEvent))
                    <button class="btn btn-no-pd bg-primer mr-3 my-auto">Event {{ $event->execution_type }}</button>

                    <form id="public-mode" method="POST" action="{{ route('organization.event.publish', [$organizationID, $eventID]) }}"
                        class="d-inline">
                        {{ csrf_field() }}
                        {{-- <button class="btn btn-no-pd bg-primer"><i class="fa fa-paper-plane" aria-hidden="true"></i>
                            Publish</button> --}}

                    </form>

                    <form id="private-mode" method="POST"
                        action="{{ route('organization.event.un_publish', [$organizationID, $eventID]) }}"
                        class="d-inline">
                        {{ csrf_field() }}
                        {{-- <button class="btn btn-no-pd bg-outline-light text-dark ml-3">Un-Publish</button> --}}

                    </form>

                    {{-- Pembaruan UI / UX 31 Agustus 2022 Ubah publish button dengan toogle btn --}}
                    <span class="switch-left">Private</span>
                    <label class="switch my-auto">
                        {{-- Checkbox set checked if public --}}
                        <input id="mode-event" type="checkbox" {{ $event->is_publish == 1 ? 'checked' : '' }}>
                        {{-- slider set active if public --}}
                        <span class="slider round {{ $event->is_publish == 1 ? 'slider-active' : '' }}"></span>
                    </label>
                    <span class="switch-right">Public</span>
                    <a href="{{ route('user.eventDetail', [$event->slug]) }}" class="homepage-links my-auto ml-3">
                        <li class="no-pd"><button class="btn btn-no-pd btn-outline-primer text-dark">Preview</button></li>
                    </a>
                @else
                    @if (Route::currentRouteName() == 'user.myTickets' || Route::currentRouteName() == 'user.detailTicket' || Route::currentRouteName() == 'user.shareTickets' || Route::currentRouteName() == 'user.shareTickets2')
                        <a href="{{ route('user.shareTickets') }}" class="homepage-links">
                            <li><button class="btn btn-no-pd bg-primer">
                                    <i class="fa fa-paper-plane"></i> Share Tickets
                                </button></li>
                        </a>
                    @endif
                    <a href="{{ route('user.homePage') }}" class="homepage-links">
                        <li>Temukan Event</li>
                    </a>
                    <a href="#" class="homepage-links">
                        <li>Bantuan</li>
                    </a>

                @endif

                <!-- ---------------- Menambahkan profile button ------------ -->

                @if (isset($myData))
                    <div class="btn btn-login btn-group my-2 my-sm-0 no-pd-r" style="margin-right: -30px !important">
                        <a class="btn" href="#" role="button" id="profilelink" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false" onclick="showDropDownMenu()">
                            @if ($myData->photo == 'default')
                                <img src="{{ asset('images/profile-user.png') }}"
                                    class="img-fluid rounded-circle profilelink-img" alt="profile">
                            @else
                                <img src="{{ asset('storage/profile_photos/' . $myData->photo) }}"
                                    class="img-fluid rounded-circle profilelink-img" alt="profile">
                            @endif
                        </a>
                    </div>
                @endif

                <div class="btn btn-login btn-group my-2 my-sm-0 py-3 mr-3">
                    @if (isset($myData))
                        <div id="dropdown-menu"
                            class="dropdown-menu dropdown-menu-right mr-4 mt-2 rounded-1 nav-top-profiler"
                            aria-labelledby="profilelink">
                            <div class="dropdown-item mt-2">
                                <div class="row">
                                    <a href="{{ route('user.events') }}"
                                        class="pl-4 text-inter text-decoration-none dropdown-font">Events</a>
                                </div>
                            </div>
                            <div class="dropdown-item mt-2">
                                <div class="row">
                                    <a href="{{ route('user.invitations') }}"
                                        class="pl-4 text-inter text-decoration-none dropdown-font">Invitations</a>
                                </div>
                            </div>
                            <div class="dropdown-item my-2">
                                <div class="row">
                                    <a href="{{ route('user.profile') }}"
                                        class="pl-4 text-inter text-decoration-none dropdown-font">Profile</a>
                                </div>
                            </div>
                            <div class="dropdown-item my-2">
                                <div class="row">
                                    <a href="{{ route('user.logout') }}"
                                        class="pl-4 text-inter text-decoration-none dropdown-font">Logout</a>
                                </div>
                            </div>
                            <div class="dropdown-item my-2">
                                <div class="row">
                                    <a href="{{ route('user.upgradePkg') }}" target="_blank"
                                        class="btn btn-primer pl-4 text-inter text-decoration-none dropdown-font">Upgrade</a>
                                </div>
                            </div>
                            @if (isset($myData->organizations))
                                <p class="pl-4 mt-3 mb-2">ORGANIZATIONS</p>
                                @foreach ($myData->organizations as $organization)
                                    <div class="dropdown-item mb-2">
                                        <a href="{{ route('organization.profilOrganisasi', $organization->id) }}"
                                            class="text-decoration-none">
                                            <div class="row">
                                                <div class="col-md-1">
                                                    <img src="{{ $organization->logo == '' ? asset('storage/organization_logo/default_logo.png') : asset('storage/organization_logo/' . $organization->logo) }}"
                                                        class="rounded-circle organization-icon-dropdown" alt="">
                                                </div>
                                                <div class="col-md dropdown-font">
                                                    {{ $organization->name }}
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        @else
                            <a href="{{ route('user.registerPage') }}"
                                class="btn btn-outline-danger ml-2 mr-2 text-inter">Register</a>

                    @endif


                </div>



                <!-- --------------------------------------------------------- -->

            </nav>
            <nav class="row navbar navbar-expand-lg navbar-light menu-mobile pr-4">
                <button class="navbar-toggler ml-auto col-md bg-light" type="button" data-toggle="collapse"
                    data-target="#nav-mobile">
                    <span class="navbar-toggler-icon text-dark"></span>
                </button>
            </nav>
            <nav class="row navbar navbar-expand-lg navbar-light menu-mobile pr-4 w-100">
                <div class="collapse navbar-collapse rounded-5" id="nav-mobile">
                    @if (isset($isManageEvent))
                        <div class="navbar-nav bg-putih col-md-12 shadow-box rounded-5">
                            <a href="{{ route('organization.profilOrganisasi', $organizationID) }}"
                                class="btn btn-sm bg-primer mt-2 mb-2 w-100 ml-2 rounded-5"><i
                                    class="fas fa-arrow-left"></i>
                                Kembali
                            </a>
                        </div>
                    @endif

                    <div class="navbar-nav bg-putih col-md-12 mt-2 navbar-scroll shadow-box rounded-5">
                        @isset($isManageEvent)
                            <a href="{{ route('organization.event.eventoverview', [$organizationID, $eventID]) }}"
                                class="font-14 nav-item mt-2 mr-2 ml-2 pt-0">Event Overview</a>
                            <a href="{{ route('organization.event.edit', [$organizationID, $eventID]) }}"
                                class="font-14 nav-item mr-2 ml-2">Basic Info</a>
                            {{-- <a href="{{ route('organization.event.site', [$organizationID, $eventID]) }}"
                                class="font-14 nav-item mr-2 ml-2">Landing Site</a> --}}
                            <a href="{{ route('organization.event.qr-event', [$organizationID, $eventID]) }}" 
                                class="font-14 nav-item mr-2 ml-2">QR Event</a>
                            <a href="{{ route('organization.event.handbooks', [$organizationID, $eventID]) }}"
                                class="font-14 nav-item mr-2 ml-2">HandBook</a>
                            <a href="{{ route('organization.event.ticketSelling', [$organizationID, $eventID]) }}"
                                class="font-14 nav-item mr-2 ml-2">Ticket Selling</a>
                            <a href="{{ route('organization.event.ticketSelling.checkinqr', [$organizationID, $eventID]) }}"
                                class="font-14 nav-item mr-2 ml-2">QR Checkin</a>
                            <a href="{{ route('organization.event.receptionist', [$organizationID, $eventID]) }}"
                                class="font-14 nav-item mr-2 ml-2">Receptionist</a>
                            <a href="{{ route('organization.event.rundowns', [$organizationID, $eventID]) }}"
                                class="font-14 nav-item mr-2 ml-2">Rundowns</a>

                            {{-- <a href="{{ route('organization.event.lounge', [$organizationID, $eventID]) }}"
                                    class="font-14 nav-item mr-2 ml-2"> Lounge</a> --}}
                            @if (preg_match('/Seminar/i', $event->category) || preg_match('/Conference/i', $event->category) || preg_match('/Symposium/i',$event->category) || preg_match('/Workshop/i', $event->category) ||  preg_match('/Talkshow/i', $event->category) || preg_match('/Live Music & Concert/i', $event->category) || preg_match('/Show & Festival/i', $event->category))
                                <a href="{{ route('organization.event.speakers', [$organizationID, $eventID]) }}"
                                    class="font-14 nav-item mr-2 ml-2">
                                    @if (preg_match('/Seminar/i', $event->category) || preg_match('/Conference/i', $event->category) || preg_match('/Symposium/i',$event->category) || preg_match('/Workshop/i', $event->category) ||  preg_match('/Talkshow/i', $event->category))
                                    Speakers
                                    @else
                                    Peformers
                                    @endif
                                </a>
                            @endif

                            @if (preg_match('/Stage and Session/i', $event->breakdown))
                                <a href="{{ route('organization.event.sessions', [$organizationID, $eventID]) }}"
                                    class="font-14 nav-item mr-2 ml-2"> Stage & Session</a>
                            @else
                                <a href="{{ route('organization.event.session.config', [$organizationID, $eventID]) }}"
                                    class="font-14 nav-item mr-2 ml-2">Session Config</a>
                            @endif

                            @if (preg_match('/Exihibitors/i', $event->breakdown))
                                <a href="{{ route('organization.event.booth.category', [$organizationID, $eventID]) }}"
                                    class="font-14 nav-item mr-2 ml-2">Booth Category</a>
                                <a href="{{ route('organization.event.exhibitors', [$organizationID, $eventID]) }}"
                                    class="font-14 nav-item mr-2 ml-2"> Exhibitons</a>
                            @endif

                            @if (preg_match('/Sponsors/i', $event->breakdown))
                                <a href="{{ route('organization.event.media', [$organizationID, $eventID]) }}"
                                    class="font-14 nav-item mr-2 ml-2">Media Partner</a>
                            @endif

                            @if (preg_match('/Media Partner/i', $event->breakdown))
                                <a href="{{ route('organization.event.sponsors', [$organizationID, $eventID]) }}"
                                    class="font-14 nav-item mr-2 ml-2">Sponsors</a>
                            @endif

                            <a href="{{ route('organization.event.tickets', [$organizationID, $eventID]) }}"
                                class="font-14 nav-item mr-2 ml-2">Ticket & Pricing</a>

                            @if ($event->execution_type == 'online' || $event->execution_type == 'hybrid')
                                {{-- <a href="{{ route('organization.event.quiz', [$organizationID, $eventID]) }}"
                                    class="font-14 nav-item mr-2 ml-2">Quiz</a> --}}
                                {{-- <a href="#" class="font-14 nav-item mr-2 ml-2">Polling</a> --}}
                            @endif
                            
                        @elseif(isset($streamPage))
                            @if ($paramPage == 'byUser')
                                <a href="{{ route('user.joinStream', [$purchase->id]) }}"
                                    class="font-16 nav-item mr-2 ml-2 mt-2">
                                    Home
                                </a>
                            @elseif ($paramPage == 'byAdmin')
                                <a href="{{ route('organization.event.session.url', [$organizationID, $eventID, $sessionID]) }}"
                                    class="font-16 nav-item mr-2 ml-2 mt-2">
                                    Home
                                </a>
                            @endif

                            <a href="#Receptionist" class="tablinks-stream-menu font-16 nav-item mr-2 ml-2 stream-menu" value="Receptionist"
                                onclick="opentabs(event, 'stream-menu', 'home'); hiddButtonSidebar();">
                                Receptionist
                            </a>
                            <a href="#Handbooks" class="tablinks-stream-menu font-16 nav-item mr-2 ml-2 stream-menu" value="Handbooks"
                                onclick="opentabs(event, 'stream-menu', 'home'); hiddButtonSidebar();">
                                HandBook
                            </a>
                            @if (preg_match('/Stage and Session/i', $event->breakdown))
                                <a id="stage-btn" class="pointer tablinks-stream-menu font-16 nav-item mr-2 ml-2" onclick="
                                                                                        opentabs(event, 'stream-menu', 'streaming');
                                                                                        showButtonSidebar();
                                                                                        ">Streaming
                                </a>
                            @else
                                <a id="stage-btn" class="font-16 pointer tablinks-stream-menu font-16 nav-item mr-2 ml-2" onclick="
                                                                                        opentabs(event, 'stream-menu', 'streaming');
                                                                                        showButtonSidebar();
                                                                                        ">Streaming
                                </a>
                            @endif

                            <a class="font-16 pointer tablinks-stream-menu font-16 nav-item mr-2 ml-2"
                                onclick="opentabs(event, 'stream-menu', 'connections'); hiddButtonSidebar();">Connections
                            </a>
                            @if (preg_match('/Exihibitors/i', $event->breakdown))
                                <a class="font-16 pointer tablinks-stream-menu font-16 nav-item mr-2 ml-2" onclick="
                                                                            opentabs(event, 'stream-menu', 'exhibitions');
                                                                            hiddButtonSidebar();">Exhibitors
                                </a>
                            @endif
                        @else
                            <a href="{{ route('user.selfCheckin') }}" class="font-16 nav-item mr-2 ml-2 mt-2"> QR Checkin</a>
                            <a href="{{ route('user.myTickets') }}" class="font-16 nav-item mr-2 ml-2"> My
                                Tickets</a>
                            <a href="{{ route('user.connections') }}" class="font-16 nav-item mr-2 ml-2"> Connections</a>
                            <a href="{{ route('user.invitations') }}" class="font-16 nav-item mr-2 ml-2"> Invitations</a>
                            <a href="{{ route('user.messages') }}" class="font-16 nav-item mr-2 ml-2"> Messages</a>
                            <a href="{{ route('user.profile') }}" class="font-16 nav-item mr-2 ml-2"> Profile</a>
                            <a id="new-event" class="font-16 nav-item mr-2 ml-2 pointer" href="/buat-event">Create Event</a>
                            <a href="{{ route('user.myorganization') }}" class="font-16 nav-item mr-2 ml-2">
                                Organization</a>
                            @isset($isExhibitor)
                                @if (count($isExhibitor) > 0)
                                    <a href="{{ route('myExhibitions') }}" class="font-16 nav-item mr-2 ml-2">
                                        Manage Your Exhibitions
                                    </a>
                                @endif
                            @endisset
                            @isset($isSpeaker)
                                @if (count($isSpeaker) > 0)
                                    <a href="{{ route('myIsSpeaker') }}" class="font-16 nav-item mr-2 ml-2">
                                        Guest Area
                                    </a>
                                @endif
                            @endisset

                        @endisset

                    </div>
                    <div class="navbar-nav bg-putih col-md-12 shadow-box rounded-5 mt-2">
                        <a class="font-16 nav-item mr-2 ml-2 mt-2 text-center" href="{{ route('user.events') }}">
                            @if ($myData->photo == 'default')
                                <img src="{{ asset('images/profile-user.png') }}"
                                    class="img-fluid rounded-circle profilelink-img" alt="profile"> &nbsp;
                                {{ $myData->name }}
                            @else
                                <img src="{{ asset('storage/profile_photos/' . $myData->photo) }}"
                                    class="img-fluid rounded-circle profilelink-img" alt="profile"> &nbsp;
                                {{ $myData->name }}
                            @endif
                        </a>
                        @if (isset($isManageEvent))
                            <div class="text-center pl-3 mb-2">
                                <button class="btn btn-no-pd bg-primer w-100">Event
                                    {{ $event->execution_type }}</button>
                            </div>
                            <div class="row text-center mb-4">
                                <div class="col-12 text-center">
                                    {{-- Pembaruan UI / UX 31 Agustus 2022 Ubah publish button dengan toogle btn --}}
                                    <span class="switch-left">Private</span>
                                    <label class="switch my-auto">
                                        {{-- Checkbox set checked if public --}}
                                        <input id="mode-event" type="checkbox" {{ $event->is_publish == 1 ? 'checked' : '' }}>
                                        {{-- slider set active if public --}}
                                        <span class="slider round {{ $event->is_publish == 1 ? 'slider-active' : '' }}"></span>
                                    </label>
                                    <span class="switch-right">Public</span>
                                </div>
                                <div class="col-12 text-center">
                                    <a href="{{ route('user.eventDetail', [$event->slug]) }}" class="homepage-links">
                                        <li><button class="btn btn-no-pd btn-outline-light text-dark">Preview</button></li>
                                    </a>
                                </div>
                            </div>
                        @else
                            @if (Route::currentRouteName() == 'user.myTickets' || Route::currentRouteName() == 'user.detailTicket' || Route::currentRouteName() == 'user.shareTickets' || Route::currentRouteName() == 'user.shareTickets2')
                                <div class="pl-3">
                                    <a href="{{ route('user.shareTickets') }}" class="homepage-links">
                                        <button class="btn btn-no-pd bg-primer w-100">
                                            <i class="fa fa-paper-plane"></i> Share Tickets
                                        </button>
                                    </a>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-6 no-pd-l no-pd-r text-center">
                                    <a href="{{ route('user.homePage') }}" class="homepage-links">
                                        <li>Temukan Event</li>
                                    </a>
                                </div>
                                <div class="col-6 text-center">
                                    <a href="#" class="homepage-links">
                                        <li>Bantuan</li>
                                    </a>
                                </div>
                            </div>

                        @endif
                        <a href="{{ route('user.upgradePkg') }}" class="font-16 nav-item mr-2 ml-2 mb-2 text-center btn btn-primer" target="_blank">Upgrade</a>
                        <a href="{{ route('user.logout') }}" class="font-16 nav-item mr-2 ml-2 text-center"><i
                                class="bi bi-box-arrow-left font-16 text-danger"></i>&nbsp; Logout</a>
                        
                    </div>
                </div>
            </nav>
        </div>

        {{-- -------- Parameter List untuk menentukan batas pcakage user ------------------ --}}
        @php
            if(isset($isManageEvent)){
                $organization = \App\Models\Organization::where('id',$organizationID)->first();
                $nowDt = new DateTime();
                $startLimit = new DateTime($organization->user->created_at);
                $different = $startLimit->diff($nowDt);
                $pkgActive = \App\Http\Controllers\PackagePricingController::limitCalculator($organization->user);
                
            }else{
                $nowDt = new DateTime();
                $startLimit = new DateTime($myData->created_at);
                $different = $startLimit->diff($nowDt);
                $pkgActive = \App\Http\Controllers\PackagePricingController::limitCalculator($myData);
                
            }
            
        @endphp

        {{-- ------------------------------------------------------------------------------ --}}


        {{-- -------------- Status paket yang dibeli user -------------------------------- --}}

        @if (isset($isManageEvent))
            <div class="alert {{ $pkgActive == 1 ? 'alert-success' : 'alert-warning' }} no-pd-b no-pd-t" style="line-height: 40px">
                @if ($organization->user->id == $myData->id)
                    {{ $myData->package->name }} Package |
                    {{-- @if ($pkgActive == 0)
                        <div class="d-inline mt-1">
                            <a href="{{ route('user.upgradePkg') }}" class="font-16 nav-item mt-2 ml-2 mb-2 text-center btn btn-primer" target="_blank">Upgrade</a>
                        </div>
                    @endif --}}
                    <div class="d-inline mt-1" style="margin-bottom: unset !important; display: inline!important;">
                        <a href="{{ route('user.upgradePkg') }}" class="font-16 nav-item mt-2 ml-2 mb-2 text-center text-danger" target="_blank">Upgrade</a>
                    </div>
                @else
                    {{ $organization->user->package->name }} Package |
                @endif
            </div>
        @elseif(!isset($streamPage))
            <div class="alert {{ $pkgActive == 1 ? 'alert-success' : 'alert-warning' }} no-pd-b no-pd-t" style="line-height: 40px">
                {{ $myData->package->name }} Package |
                    {{-- @if ($pkgActive == 0)
                        <div class="d-inline mt-1">
                            <a href="{{ route('user.upgradePkg') }}" class="font-16 nav-item mt-2 ml-2 mb-2 text-center text-danger" target="_blank">Upgrade</a>
                        </div>
                    @endif --}}
                    <div class="d-inline mt-1" style="margin-bottom: unset !important; display: inline!important;">
                        <a href="{{ route('user.upgradePkg') }}" class="font-16 nav-item mt-2 ml-2 mb-2 text-center text-danger" target="_blank">Upgrade</a>
                    </div>
            </div>
        @endif

        {{-- ------------------------------------------------------------------------------ --}}


    </header>

    {{-- bg sidebar nav stremaing page --}}
    @if (isset($streamPage))
        <img id="bg-img-sidebar" src="{{ asset('images/bg-streams/main-stage-sidebar.png') }}" alt="" style="position: absolute; z-index: -2; width: 13%; height: 100%; min-height: 831px; top: 64px;">
    @endif
    {{-- ----------------------------- --}}
    <nav class="left nav-atas" style="{{ isset($streamPage) == true ? 'width:11%;' : 'top: 100px;' }}">
        @isset($isManageEvent)
            <div class="menu">
                <div>
                    <div class="wrap">
                        <a href="{{ route('organization.profilOrganisasi', $organizationID) }}"
                            class="btn bg-primer col-md text-light side-nav-text-min"><i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div>
                    <div class="wrap">
                        <a href="{{ route('organization.event.eventoverview', [$organizationID, $eventID]) }}">
                            <li
                                class="{{ Route::currentRouteName() == 'organization.event.eventoverview' ? 'active' : '' }} side-nav-text">
                                <i class="fa fa-home mr-4 col-md-2 row"></i>
                                <p class="d-inline">Event Overview</p>
                            </li>
                        </a>
                    </div>
                </div>
                <div>
                    <div class="wrap">
                        <h4 class="pointer">EVENT DETAILS</h4>
                        <a href="{{ route('organization.event.edit', [$organizationID, $eventID]) }}">
                            <li
                                class="{{ Route::currentRouteName() == 'organization.event.edit' ? 'active' : '' }} side-nav-text">
                                <i class="fa fa-edit mr-4 col-md-2 row"></i>
                                <p class="d-inline">Basic Info </p>
                            </li>
                        </a>
                        <a href="{{ route('organization.event.qr-event', [$organizationID, $eventID]) }}">
                            <li
                                class="{{ Route::currentRouteName() == 'organization.event.qr-event' ? 'active' : '' }} side-nav-text">
                                <i class="fa bi bi-qr-code mr-4 col-md-2 row"></i>
                                <p class="d-inline">QR Event </p>
                            </li>
                        </a>
                        <a href="{{ route('organization.event.tickets', [$organizationID, $eventID]) }}">
                            <li
                                class="{{ Route::currentRouteName() == 'organization.event.tickets' ? 'active' : '' }} side-nav-text">
                                <i class="fa fa-tag mr-4 col-md-2 row"></i>
                                <p class="d-inline">Ticket & Pricing</p>
                            </li>
                        </a>
                        <a href="{{ route('organization.event.handbooks', [$organizationID, $eventID]) }}">
                            <li
                                class="{{ Route::currentRouteName() == 'organization.event.handbooks' ? 'active' : '' }} side-nav-text">
                                <i class="fa fa-file mr-4 col-md-2 row"></i>
                                <p class="d-inline">HandBook</p>
                            </li>
                        </a>
                        {{-- @if (in_array(strtolower($event->category), ['seminar','conference','workshop']))
                            <a href="{{ route('organization.event.certificate', [$organizationID, $eventID]) }}">
                                <li
                                    class="{{ Route::currentRouteName() == 'organization.event.certificate' ? 'active' : '' }} side-nav-text">
                                    <i class="fa fa-certificate mr-4 col-md-2 row"></i>
                                    <p class="d-inline">Certificate</p>
                                </li>
                            </a>
                        @endif
                        <a href="{{ route('organization.event.site', [$organizationID, $eventID]) }}">
                            <li
                                class="{{ Route::currentRouteName() == 'organization.event.site' ? 'active' : '' }} side-nav-text">
                                <i class="fa fa-safari mr-4 col-md-2 row"></i>
                                <p class="d-inline">Landing Site</p>
                            </li>
                        </a> --}}
                        
                    </div>
                </div>
                <div>
                    <div class="wrap">
                        <h4 class="pointer">EVENT REPORTS</h4>
                        
                        <a href="{{ route('organization.event.ticketSelling', [$organizationID, $eventID]) }}">
                            <li
                                class="{{ Route::currentRouteName() == 'organization.event.ticketSelling' ? 'active' : '' }} side-nav-text">
                                <i class="fa bi bi-cash-stack mr-4 col-md-2 row"></i>
                                <p class="d-inline">Ticket Selling</p>
                            </li>
                        </a>
                        <a href="{{ route('organization.event.ticketSelling.checkinqr', [$organizationID, $eventID]) }}">
                            <li
                                class="{{ Route::currentRouteName() == 'organization.event.ticketSelling.checkinqr' ? 'active' : '' }} side-nav-text">
                                <i class="fa bi bi-qr-code-scan mr-4 col-md-2 row"></i>
                                <p class="d-inline">QR Checkin</p>
                            </li>
                        </a>
                    </div>
                </div>

                <div>
                    <div class="wrap">
                        <h4 class="pointer">EVENT SPACE</h4>
                        <a href="{{ route('organization.event.receptionist', [$organizationID, $eventID]) }}">
                            <li
                                class="{{ Route::currentRouteName() == 'organization.event.receptionist' ? 'active' : '' }} side-nav-text">
                                <i class="fa fa-clipboard mr-4 col-md-2 row"></i>
                                <p class="d-inline">Receptionist</p>
                            </li>
                        </a>
                        <a href="{{ route('organization.event.rundowns', [$organizationID, $eventID]) }}">
                            <li
                                class="{{ Route::currentRouteName() == 'organization.event.rundowns' ? 'active' : '' }} side-nav-text">
                                <i class="fa bi bi-calendar mr-4 col-md-2 row"></i>
                                <p class="d-inline">Rundowns</p>
                            </li>
                        </a>

                        @if (preg_match('/Stage and Session/i', $event->breakdown))
                            <a href="{{ route('organization.event.sessions', [$organizationID, $eventID]) }}">
                                <li
                                    class="{{ Route::currentRouteName() == 'organization.event.sessions' ? 'active' : '' }} side-nav-text">
                                    <i class="fa fa-camera mr-4 col-md-2 row"></i>
                                    <p class="d-inline">Stage & Session</p>
                                </li>
                            </a>
                        @else
                            <a href="{{ route('organization.event.session.config', [$organizationID, $eventID]) }}">
                                <li
                                    class="{{ Route::currentRouteName() == 'organization.event.session.config' ? 'active' : '' }} side-nav-text">
                                    <i class="fa fbi bi-gear mr-4 col-md-2 row"></i>
                                    <p class="d-inline">Session Config</p>
                                </li>
                            </a>
                        @endif
                    </div>
                </div>
                <div>
                    <div class="wrap">
                        @if (preg_match('/Seminar/i', $event->category) || preg_match('/Conference/i', $event->category) || preg_match('/Symposium/i',$event->category) || preg_match('/Workshop/i', $event->category) ||  preg_match('/Talkshow/i', $event->category) || preg_match('/Live Music & Concert/i', $event->category) || preg_match('/Show & Festival/i', $event->category) || preg_match('/Exihibitors/i', $event->breakdown) || preg_match('/Sponsors/i', $event->breakdown))

                            <h4 class="pointer">ROLES</h4>
                        @endif

                        @if (preg_match('/Seminar/i', $event->category) || preg_match('/Conference/i', $event->category) || preg_match('/Symposium/i',$event->category) || preg_match('/Workshop/i', $event->category) ||  preg_match('/Talkshow/i', $event->category) || preg_match('/Live Music & Concert/i', $event->category) || preg_match('/Show & Festival/i', $event->category))
                            <a href="{{ route('organization.event.speakers', [$organizationID, $eventID]) }}">
                                <li
                                    class="{{ Route::currentRouteName() == 'organization.event.speakers' ? 'active' : '' }} {{ Route::currentRouteName() == 'organization.event.speaker.create' ? 'active' : '' }} {{ Route::currentRouteName() == 'organization.event.speaker.edit' ? 'active' : '' }} side-nav-text">
                                    <i class="fa fa-microphone-alt mr-4 col-md-2 row"></i>
                                    @if (preg_match('/Seminar/i', $event->category) || preg_match('/Conference/i', $event->category) || preg_match('/Symposium/i',$event->category) || preg_match('/Workshop/i', $event->category) ||  preg_match('/Talkshow/i', $event->category))
                                        <p class="d-inline">Speakers </p>
                                    @else
                                        <p class="d-inline">Peformers</p>
                                    @endif
                                </li>
                            </a>
                        @endif
                        @if (preg_match('/Exihibitors/i', $event->breakdown))
                            <a href="{{ route('organization.event.booth.category', [$organizationID, $eventID]) }}">
                                <li
                                    class="{{ Route::currentRouteName() == 'organization.event.booth.category' ? 'active' : '' }} side-nav-text">
                                    <i class="fa fa-building mr-4 col-md-2 row"></i>
                                    <p class="d-inline">Booth Category</p>
                                </li>
                            </a>
                            <a href="{{ route('organization.event.exhibitors', [$organizationID, $eventID]) }}">
                                <li
                                    class="{{ Route::currentRouteName() == 'organization.event.exhibitors' ? 'active' : '' }} {{ Route::currentRouteName() == 'organization.event.exhibitor.create' ? 'active' : '' }} {{ Route::currentRouteName() == 'organization.event.exhibitor.edit' ? 'active' : '' }} side-nav-text">
                                    <i class="fa fa-chalkboard-teacher mr-4 col-md-2 row"></i>
                                    <p class="d-inline">Exhibitors</p>
                                </li>
                            </a>
                        @endif
                        @if (preg_match('/Sponsors/i', $event->breakdown))
                            <a href="{{ route('organization.event.sponsors', [$organizationID, $eventID]) }}">
                                <li
                                    class="{{ Route::currentRouteName() == 'organization.event.sponsors' ? 'active' : '' }} side-nav-text">
                                    <i class="fa fa-tv mr-4 col-md-2 row"></i>
                                    <p class="d-inline">Sponsors</p>
                                </li>
                            </a>
                        @endif
                        @if (preg_match('/Media Partner/i', $event->breakdown))
                            <a href="{{ route('organization.event.media', [$organizationID, $eventID]) }}">
                                <li
                                    class="{{ Route::currentRouteName() == 'organization.event.media' ? 'active' : '' }} side-nav-text">
                                    <i class="fa bi bi-postcard mr-4 col-md-2 row"></i>
                                    <p class="d-inline">Media Partner</p>
                                </li>
                            </a>
                        @endif
                    </div>
                </div>
                <div>
                    @if ($event->execution_type == 'online' || $event->execution_type == 'hybrid')
                        {{-- <div class="wrap">
                            <h4 class="pointer">ETC</h4> --}}
                            {{-- <a href="#">
                                <li
                                    class="{{ Route::currentRouteName() == 'organization.event.polling' ? 'active' : '' }} side-nav-text">
                                    <i class="fa fa-signal mr-4 col-md-2 row"></i>
                                    <p class="d-inline">Polling</p>
                                </li>
                            </a> --}}
                            {{-- <a href="{{ route('organization.event.quiz', [$organizationID, $eventID]) }}">
                                <li class="{{ Route::currentRouteName() == 'organization.event.quiz' ? 'active' : '' }} ">
                                    <i class="fa fa-question mr-4 col-md-2 row"></i> Quiz
                                </li>
                            </a>
                        </div> --}}
                    @endif
                </div>
                
            </div>
        @else
            
            @if (isset($streamPage))
                <div class="menu navstream my-auto mx-auto bg-white rounded-15">
                    @if ($paramPage == 'byUser')
                        <a href="{{ route('user.joinStream', [$purchase->id]) }}" class="font-16">
                            <li class="text-center">
                                <img class="rounded-5 asp-rt-1-1 bayangan-5 mt-3 mb-3" width="60%"
                                    src="{{ $organization->logo == '' ? asset('storage/organization_logo/default_logo.png') : asset('storage/organization_logo/' . $organization->logo) }}"
                                    alt="">
                            </li>
                        </a>
                    @elseif ($paramPage == 'byAdmin')
                        <a href="{{ route('organization.event.session.url', [$organizationID, $eventID, $sessionID]) }}"
                            class="font-16">
                            <li class="text-center">
                                <img class="rounded-5 asp-rt-1-1 bayangan-5 mt-3 mb-3" width="60%"
                                    src="{{ $organization->logo == '' ? asset('storage/organization_logo/default_logo.png') : asset('storage/organization_logo/' . $organization->logo) }}"
                                    alt="">
                            </li>
                        </a>
                    @endif

                    <a href="#Receptionist" value="Receptionist" class="font-16 stream-menu">
                        <li id="tablinks-stream-menu-Receptionist" class="tablinks-stream-menu text-center" style="color: #4d4c4c;
                        font-size: 13px;"
                            onclick="opentabs(event, 'stream-menu', 'home'); hiddButtonSidebar();">
                            <i class="bi bi-file-person d-block mr-0 pt-2" style="font-size: 22pt;"></i>Receptionist
                        </li>
                    </a>
                    <a href="#Handbooks" class="stream-menu" value="Handbooks">
                        <li id="tablinks-stream-menu-Handbooks" class="tablinks-stream-menu text-center" style="color: #4d4c4c;
                        font-size: 13px;"
                            onclick="opentabs(event, 'stream-menu', 'home'); hiddButtonSidebar();">
                            <i class="fa fa-file d-block mr-0 pt-2" style="font-size: 22pt;"></i> HandBook
                        </li>
                    </a>
                    @if (preg_match('/Stage and Session/i', $event->breakdown))
                        <a class="font-16 pointer">
                            <li id="stage-btn" class="tablinks-stream-menu text-center" style="color: #4d4c4c;
                        font-size: 13px;"
                                onclick="showButtonSidebar();opentabs(event, 'stream-menu', 'streaming');">
                                <i class="bi bi-camera-video d-block mr-0 pt-2" style="font-size: 22pt;"></i> Streaming
                            </li>
                        </a>
                    @else
                        <a class="font-16 pointer">
                            <li id="stage-btn" class="tablinks-stream-menu text-center" style="color: #4d4c4c;
                        font-size: 13px;"
                                onclick="showButtonSidebar();opentabs(event, 'stream-menu', 'streaming');">
                                <i class="bi bi-camera-video d-block mr-0 pt-2" style="font-size: 22pt;"></i> Streaming
                            </li>
                        </a>
                    @endif

                    <a class="font-16 pointer">
                        <li class="tablinks-stream-menu text-center" style="color: #4d4c4c;
                        font-size: 13px;"
                            onclick="opentabs(event, 'stream-menu', 'connections'); hiddButtonSidebar();">
                            <i class="fa fa-lg bi bi-people d-block mr-0 pt-2" style="font-size: 22pt;"></i>Connections
                        </li>
                    </a>
                    @if (preg_match('/Exihibitors/i', $event->breakdown))
                        {{-- <a href="{{ route('user.home.exhibitions', [$eventID, 0]) }}"> --}}
                        <a class="font-16 pointer">
                            <li id="exhibitions-btn" class="tablinks-stream-menu text-center" style="color: #4d4c4c;
                        font-size: 13px;" onclick="
                                                                            opentabs(event, 'stream-menu', 'exhibitions');
                                                                            hiddButtonSidebar();
                                                                            ">
                                <i class="fa fa-chalkboard-teacher d-block mr-0 pt-2" style="font-size: 22pt;"></i> Exhibitors
                            </li>
                        </a>
                    @endif
                    @if (isset($purchase))
                        {{-- <a href="{{ route('user.quizShow',[$purchase->id]) }}" target="popup" 
                            onclick="window.open('{{ route('user.quizShow',[$purchase->id]) }}','popup','width=600,height=600'); return false;" class="font-16">
                            <li class="tablinks-stream-menu pointer text-center" style="color: #4d4c4c;
                        font-size: 13px;">
                                <i class="bi bi-ballon d-block mr-0 pt-2" style="font-size: 22pt;"></i>Quiz
                            </li>
                        </a> --}}
                    @endif
                </div>
            @else
                <div class="menu">
                    <div class="wrap">
                        <h4>
                            GENERAL
                            <a id="new-event" class="btn btn-sm btn-primer rounded-5 ml-2" 
                                style="width: unset" href="/buat-event">
                                Create Event
                            </a>
                        </h4>
                        
                    </div>

                    <a href="{{ route('user.events') }}" class="font-16">
                        <li class="{{ Route::currentRouteName() == 'user.events' ? 'active' : '' }}">
                            <i class="fa fa-lg bi bi-star mr-4 col-md-2 row"></i>Events
                        </li>
                    </a>
                    <a href="{{ route('user.selfCheckin') }}" class="font-16">
                        <li class="{{ Route::currentRouteName() == 'user.selfCheckin' ? 'active' : '' }}">
                            <i class="fa fa-lg bi bi-qr-code-scan mr-4 col-md-2 row"></i>QR Checkin
                        </li>
                    </a>
                    <a href="{{ route('user.myTickets') }}" class="font-16">
                        <li
                            class="{{ Route::currentRouteName() == 'user.myTickets' ? 'active' : '' }} {{ Route::currentRouteName() == 'user.detailTicket' ? 'active' : '' }} {{ Route::currentRouteName() == 'user.shareTickets' ? 'active' : '' }} {{ Route::currentRouteName() == 'user.shareTickets2' ? 'active' : '' }}">
                            <i class="fa fa-lg bi bi-tag mr-4 col-md-2 row"></i>My Tickets
                        </li>
                    </a>
                    <a href="{{ route('user.connections') }}" class="font-16">
                        <li class="{{ Route::currentRouteName() == 'user.connections' ? 'active' : '' }}">
                            <i class="fa fa-lg bi bi-people mr-4 col-md-2 row"></i>Connections
                        </li>
                    </a>
                    <a href="{{ route('user.invitations') }}" class="font-16">
                        <li class="{{ Route::currentRouteName() == 'user.invitations' ? 'active' : '' }}">
                            <i class="fa fa-lg bi bi-inbox mr-4 col-md-2 row"></i>Invitations
                            @if ($totalInvite > 0)
                                <span class="count">{{ $totalInvite }}</span>
                            @endif
                        </li>
                    </a>
                    <a href="{{ route('user.messages') }}" class="font-16">
                        <li class="{{ Route::currentRouteName() == 'user.messages' ? 'active' : '' }}">
                            <i class="fa fa-lg bi bi-chat-left mr-4 col-md-2 row"></i>Messages
                        </li>
                    </a>
                    <a href="{{ route('user.profile') }}" class="font-16">
                        <li class="{{ Route::currentRouteName() == 'user.profile' ? 'active' : '' }}">
                            <i class="fa fa-lg bi bi-person mr-4 col-md-2 row"></i>Profile
                        </li>
                    </a>
                </div>
                <div class="menu organization">
                    <div class="wrap mb-40">
                        <h4>ORGANIZATIONS
                            {{-- <a class="btn btn-sm btn-primer rounded-5 fs-3-0 ke-kanan" data-toggle="modal"
                                data-target="#create_oraganization">
                                <i class="fas fa-plus pointer text-light fs-3-0 icon-no-margin"></i>Create Organisasi
                            </a> --}}
                            <a class="btn btn-sm btn-primer rounded-5 fs-3-0 ke-kanan" 
                                onclick="confirmAddOr()">
                                <i class="fas fa-plus pointer text-light fs-3-0 icon-no-margin"></i>Create Organization
                            </a>
                        </h4>
                    </div>
                    @foreach ($myData->organizations as $organization)
                        <a class="mt-2"
                            href="{{ route('organization.profilOrganisasi', $organization->id) }}">
                            <li class="">
                                <div class="icon"
                                    bg-image="{{ $organization->logo == '' ? asset('storage/organization_logo/default_logo.png') : asset('storage/organization_logo/' . $organization->logo) }}">
                                </div>
                                <div class="text">{{ $organization->name }}</div>
                            </li>
                        </a>
                    @endforeach
                    <div class="wrap">
                        <h4>ORGANIZATIONS INVITE</h4>
                    </div>
                    @foreach ($myData->organizationsTeam as $organizationTeam)
                        <a href="{{ route('organization.profilOrganisasi', $organizationTeam->organizations->id) }}">
                            <li class="">
                                <div class="icon"
                                    bg-image="{{ $organizationTeam->organizations->logo == '' ? asset('storage/organization_logo/default_logo.png') : asset('storage/organization_logo/' . $organizationTeam->organizations->logo) }}">
                                </div>
                                <div class="text">{{ $organizationTeam->organizations->name }}</div>
                            </li>
                        </a>
                    @endforeach

                    @isset($isExhibitor)
                        @if (count($isExhibitor) > 0)
                            <div class="wrap pb-4">
                                <h4>EXHIBITORS INVITE
                                    <a href="{{ route('myExhibitions') }}"
                                        class="btn btn-sm btn-primer rounded-5 fs-3-0 ke-kanan mb-3">
                                        Manage Your Exhibitions
                                    </a>
                                </h4>
                            </div>
                        @endif
                    @endisset
                    @isset($isSpeaker)
                        @if (count($isSpeaker) > 0)
                            <div class="wrap pb-4">
                                <h4>YOU AS GUEST
                                    <a href="{{ route('myIsSpeaker') }}"
                                        class="btn btn-sm btn-primer rounded-5 fs-3-0 ke-kanan mb-3">
                                        Guest Area
                                    </a>
                                </h4>
                            </div>
                        @endif
                    @endisset

                </div>
            @endif
        @endif
            @yield('navigation')
        </nav>


        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab1Id" role="tabpanel"></div>
            <div class="tab-pane fade" id="tab2Id" role="tabpanel"></div>
            <div class="tab-pane fade" id="tab3Id" role="tabpanel"></div>
            <div class="tab-pane fade" id="tab4Id" role="tabpanel"></div>
            <div class="tab-pane fade" id="tab5Id" role="tabpanel"></div>
        </div>

        
        @if (isset($streamPage))
            <div class="content-stream">
                <div id="stream-row" class="row stream-row">
                    <div id="content-stream" class="col main-stream">
                        @yield('contentStream')
                    </div>
                    @include('layouts.chat-stream')
                    <div class="left nav-atas col" style="{{ isset($streamPage) == true ? 'width:11%;' : 'top: 100px;' }}">
                    </div>
                </div>
            </div>
            <a id="btn-chat-stream" href="#" onclick="shoMessage();" class="float">
                <i class="fa bi bi-chat-left-text my-float"></i>
            </a>
        @else
            <div class="content container-cadangan" style="top: 145px">
                @yield('content')
            </div>
        @endif

        <!-- Modal Tambah Organisasi -->
        <div class="bg"></div>
        <div class="popupWrapper" id="create_oraganization">
            <div class="popup" style="min-width: 60%">
                <div class="wrap">
                    <h5>Organisasi Baru
                        <i class="fas fa-times pointer ke-kanan" onclick="hilangPopup('#create_oraganization')"></i>
                    </h5>
                    <div class="wrap pt-3">
                        <form action="{{ route('organization.store') }}" method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div>
                                    @include('admin.partials.alert')
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label class="mb-0">Nama Organisasi</label>
                                        <input type="text" class="box rounded-8 no-bg" name="name" required
                                            value="{{ old('name') }}">
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="">Tipe Organisasi</label>
                                            <select class="form-control rounded-8 no-bg" name="type" id="tipe">
                                                
                                                @foreach ($organization_types as $types)
                                                    <option class="dropdown-slc" value="{{ $types->name }}">
                                                        {{ $types->name }} </option>
                                                @endforeach
                                                <option class="dropdown-slc" value="Other">Lainnya</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="">Tertarik untuk mengadakan</label>
                                            <select class="form-control rounded-8 no-bg" name="interest" id="tertarik">
                                                @foreach ($organization_interests as $interests)
                                                    <option class="dropdown-slc" value="{{ $interests->name }}">
                                                        {{ $interests->name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-2">Tentang Organisasi :</div>
                                    <textarea name="description" class="box rounded-8 no-bg">{{ old('description') }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="submit" class="btn col-md bg-primer mx-wd-3">Buat</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Pilih Organisasi -->
        <div class="bg"></div>
        <div class="popupWrapper" id="select_oraganization">
            <div class="popup" style="mmin-width: 40%">
                <div class="wrap">
                    <h5 class="text-center">Pilih Organisasi
                        <i class="fas fa-times pointer ke-kanan" onclick="hilangPopup('#select_oraganization')"></i>
                    </h5>
                    <div class="wrap pt-3">
                        
                        <div class="">
                            <h4 class="h4-org-popup">MY ORGANIZATIONS</h4>
                        </div>
                        @foreach ($myData->organizations as $organization)
                            <a class="mt-2 link-org-popup"
                                href="{{ route('organization.event.create', [$organization->id]) }}">
                                <li class="li-org-popup">
                                    <div class="icon-org-popup"
                                    style="background-image:url({{ "'" }}{{ $organization->logo == '' ? asset('storage/organization_logo/default_logo.png') : asset('storage/organization_logo/' . $organization->logo) }}{{ "'" }});">
                                    </div>
                                    <div class="text-org-popup">{{ $organization->name }}</div>
                                </li>
                            </a>
                        @endforeach
                        <div class="">
                            <h4 class="h4-org-popup">ORGANIZATIONS INVITE</h4>
                        </div>
                        @foreach ($myData->organizationsTeam as $organizationTeam)
                            <a class="link-org-popup" href="{{ route('organization.event.create', [$organizationTeam->organizations->id]) }}">
                                <li class="li-org-popup">
                                    <div class="icon-org-popup"
                                        style="background-image:url({{ "'" }}{{ $organizationTeam->organizations->logo == '' ? asset('storage/organization_logo/default_logo.png') : asset('storage/organization_logo/' . $organizationTeam->organizations->logo) }}{{ "'" }});">
                                    </div>
                                    <div class="text-org-popup">{{ $organizationTeam->organizations->name }}</div>
                                </li>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
       
        @yield('body_only')
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
                crossorigin="anonymous"></script>
        {{-- <script src="https://code.jquery.com/jquery-3.6.0.slim.js"
                        integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
                integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
        </script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
                integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
        </script>
        @if (isset($useNewDependencies))
            <script src="{{ asset('riyan/js/base.js') }}"></script>
        @else
            <script src="{{ asset('js/base.js') }}"></script>
        @endif
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
                integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
                integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
        </script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
        <script src="{{ asset('js/user/mainUser.js') }}"></script>
        {{-- Sweet alert --}}
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        {{-- <script src="sweetalert2.all.min.js"></script>
        <link rel="stylesheet" href="sweetalert2.min.css"> --}}

        @yield('javascript')
        
        <script>
            var myData = JSON.parse(escapeJson("{{ $myData }}"));
            let oldUserData = JSON.parse(localStorage.getItem('user_data'));
            if (oldUserData != null && myData.id != oldUserData.id) {
                localStorage.removeItem('event_data');
            }
            localStorage.setItem('user_data', JSON.stringify(myData));
            function confirmAddOr() {
                var pkgActive = '{{ $pkgActive }}';
                var pkgActive = parseInt(pkgActive);
                
                var myPkg = <?php echo json_encode($myData->package) ?>;

                var paramCombine = false;
                if(myPkg.organizer_count <= -1){
                    paramCombine = false;
                }else{
                    if(myData.organizations.length >= myPkg.organizer_count){
                        paramCombine = true;
                    }
                }
                
                //console.log(myData, myPkg);
                if(pkgActive == 0 || paramCombine == true){
                    // Batalkan dengan konfirmasi sweet alert
                    var msg = '';
                    if(paramCombine == true){
                        msg = 'Kamu sudah melewati batas paket untuk membuat organisasi baru ';
                    }
                    else{
                        msg = 'Paket yang kamu beli sudah melewati satu bulan / belum dibayar !!!';
                    }
                    Swal.fire({
                        title: 'Error!',
                        text: msg,
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonText: 'Ok',
                        cancelButtonText: "Batal",
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.open("{{ route('user.upgradePkg') }}");
                        }
                    });
                }else{
                    // Munculkan pop up tambah organisasi
                    munculPopup('#create_oraganization');
                }
            }
        </script>
        <script>
            var organizations = <?php echo json_encode($myData->organizations); ?>;
            var organizationsTeam = <?php echo json_encode($myData->organizationsTeam); ?>;
        </script>
        <script>
            function addEvent() {
                if(organizations.length == 0 && organizationsTeam.length == 0){
                    confirmAddOr();
                }else{
                    munculPopup('#select_oraganization');
                }
            }
        </script>
        <script>
            $('#navId a').click(e => {
                e.preventDefault();
                $(this).tab('show');
            });
        </script>

    </body>

    </html>

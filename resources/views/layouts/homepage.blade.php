<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agendakota - Platform Management Event Hybrid, Virtual dan Onsite</title>
    {{-- <title>@yield('title') - Platform Management Event Hybrid, Virtual dan Onsite</title> --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fa/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('js/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/mainUser.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Fevicon -->
    <link rel="shortcut icon" href="{{ asset('images/icon-ak.png') }}">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
    <script src="https://kit.fontawesome.com/466fd0939d.js" crossorigin="anonymous"></script>
    @yield('head.dependencies')
</head>

<body>
    <style>
        /* Remove the jumbotron's default bottom margin */
        .jumbotron {
            margin-bottom: 0;
            background-color: #e5214f; /*#eb597b*/
        }

    </style>

    <!-- Font -->
    <style type="text/css">
        body{
            overflow-x: hidden;
        }
        body h2 {
            font-family: 'Proxima', sans-serif;
            font-weight: 700;
            font-size: 46px;
            line-height: 44px;
        }

        body input {
            font-family: 'Proxima', sans-serif;
            font-weight: 300;
            font-size: 15px !important;
            color: #BDBDBD;
            background: #F8F8F8;
        }

        body p {
            font-family: 'Proxima', sans-serif;
            font-weight: 400;
            font-size: 17px !important;
        }

        header{
            height: unset;
        }

        .navbar-brand img {
            max-height: 40px;
        }

        .navbar-light .navbar-nav .nav-link {
            color: #071c4D;
        }

        .navbar-light .navbar-nav .nav-link.active {
            font-weight: bold;
            color: #071c4D !important;
        }

        .btn-navbar-right {
            margin-top: -10px;
            margin-bottom: -8px;
            margin-right: -16px;
            height: 70px;
            border-radius: 0;
        }

        .tabcontent {
            padding-left: 0px;
            padding-right: 0px;
        }

        .title-home {
            font-family: DM_Sans !important;
            font-style: normal;
            font-weight: 600;
            font-size: 48px;
        }

        .input-icons i {
            position: absolute;
        }

        .icon {
            padding: 10px;
            min-width: 40px;
        }

        .icon-2 {
            padding: 10px;
            padding-top: 8px !important;
            min-width: 40px;
        }

        .search-color {
            background-color: #F0F1F2;
        }

        .rounded-1 {
            border-radius: 18px;
        }

        /* .text-inter {
            font-family: DM_Sans !important;
        } */
        .text-inter {
            font-family: 'Inter', sans-serif !important;
        }
        .dropdown-menu p {
            font-family: DM_Sans !important;
            font-style: normal;
            font-weight: 500;
            font-size: 12px !important;
            color: #BDBDBD;
        }

        #dropdown-menu {
            top: 10px !important;
            left: -70px !important;
        }

        .f-bold {
            font-weight: bold;
        }

        .text-decoration-none {
            text-decoration: none;
        }

        .container-cadangan {
            width: 98%;
        }

        .nav-profiller {
            margin-left: -90%;
            box-shadow: 0px 5px 40px 20px rgba(193, 193, 193, 0.23) !important;
            position: absolute;
            transform: translate3d(0px, 46px, 0px) !important;
            top: 0px;
            left: 0px;
            will-change: transform;
            width: 240px;
        }

        .nav-top-profiler {
            margin-left: 0px;
            box-shadow: 0px 5px 40px 20px rgba(193, 193, 193, 0.23) !important;
            position: absolute;
            transform: translate3d(-150px, 46px, 0px) !important;
            top: 0px;
            left: 0px;
            will-change: transform;
            width: 240px;
        }

        .form-src{
            display: inline-block;
            width: 22%;
        }

        .self-center{
            align-self: center;
        }
        .top-ribbon{
            overflow-x: scroll;
            overflow-y: hidden;
        }
        .top-ribbon .btn-group{
            font-size: 10pt;
        }
        header div::-webkit-scrollbar-thumb {
            background-color: #e3587b;
        }
        #content-section{
            margin-top: 118px;
        }
        /* Responsive */
        @media screen and (max-width: 845px) {
            .form-src {
                display: none !important;
            }
        }

        /* Media Query responsive by Syaifudin */
        /* For large with bootstrap */
        @media (max-width: 991.9px) {
            .form-src-responsive{
                width: 13%;
            }
            /* .top-banner{
                display: none;
            } */
        }
        /* For medium width bootstrap */
        @media (max-width: 767.9px){
            
        }
        /* For popular mobile devices */
        @media (max-width: 390px){
            
        }
    </style>    
    {{-- @if (isset($myData))
        <style>
            #content-section{
                margin-top: 58px;
            }
        </style>
    @endif --}}

    <!-- Navbar -->
    <header>
        {{-- @if (!isset($myData))
            <div class="top-ribbon bg-primer text-light text-right pr-3">
                <div class="btn btn-login btn-group my-2 my-sm-0 mr-3">
                    <a href="https://company.agendakota.id/about-us" class="text-center text-light self-center ml-4">About Agendakota</a>
                    <a href="https://company.agendakota.id/event-creator" class="text-center text-light self-center ml-4">Event Creator</a>
                    <a href="https://community.agendakota.id" class="text-center text-light self-center ml-4">Community</a>
                    <a href="https://company.agendakota.id/news" class="text-center text-light self-center ml-4">Blog</a>
                    <a href="https://company.agendakota.id/contact" class="text-center text-light self-center ml-4">Contact Us</a>
                </div>
            </div>
        @endif --}}
        <div class="top-ribbon bg-primer text-light text-right pr-3">
            <div class="btn btn-login btn-group my-2 my-sm-0 mr-3">
                <a href="https://company.agendakota.id/about-us" class="text-center text-light self-center ml-4">About</a>
                <a href="https://company.agendakota.id/event-creator" class="text-center text-light self-center ml-4">Event Creator</a>
                {{-- <a href="https://company.agendakota.id/pricing" class="text-center text-light self-center ml-4">Pricing</a> --}}
                <a href="https://community.agendakota.id" class="text-center text-light self-center ml-4">Community</a>
                <a href="https://company.agendakota.id/news" class="text-center text-light self-center ml-4">Blog</a>
                <a href="https://company.agendakota.id/contact" class="text-center text-light self-center ml-4">Contact Us</a>
            </div>
        </div>
        <a href="{{ route('user.homePage') }}" class="mr-2">
            <img src="{{ asset('images/logo.png') }}" class="logo">
        </a>
        <form action="{{ route('explore') }}" role="search" id="search-form"
            class="form-src form-src-responsive">
            @csrf
            <div class="input-group-prepend input-icons">
                <i class="fa fa-search icon-2 teks-primer" onclick="search()" style="font-size: 20px;"></i>
                <input type="text" class="box border-0" name="search"
                    style="padding-left: 40px; color: #333; font-weight: 400; font-family: DM_Sans !important; font-size: 12px; height: 35px; background-color: #ecf0f1; margin-top: 0px;"
                    placeholder="Search Events" required value="">
            </div>
        </form>
        <nav class="nav-atas" id="nav-atas">

            <button id="sidebar-show" style="display: none;" class="btn btn-outline-primer btn-no-pd"
                onclick="showSidebar()">
                <i class="bi bi-layout-sidebar"></i>&nbsp; Sidebar
            </button>
            <button id="sidebar-hidden" style="display: none;" class="btn btn-outline-primer btn-no-pd"
                onclick="hiddSidebar()">
                <i class="bi bi-arrows-angle-expand"></i>&nbsp; Hide Sidebar
            </button>
            @if (isset($isManageEvent))
                <button class="btn btn-no-pd bg-primer mr-3">Event {{ $event->execution_type }}</button>

                <form method="POST" action="{{ route('organization.event.publish', [$organizationID, $eventID]) }}"
                    class="d-inline">
                    {{ csrf_field() }}
                    <button class="btn btn-no-pd bg-primer"><i class="fa fa-paper-plane" aria-hidden="true"></i>
                        Publish</button>

                </form>

                <form method="POST"
                    action="{{ route('organization.event.un_publish', [$organizationID, $eventID]) }}"
                    class="d-inline">
                    {{ csrf_field() }}
                    <button class="btn btn-no-pd bg-outline-light text-dark ml-3">Un-Publish</button>

                </form>

                <a href="{{ route('user.eventDetail', [$event->slug]) }}" class="homepage-links">
                    <li><button class="btn btn-no-pd btn-outline-light text-dark">Preview</button></li>
                </a>
            @endif
            <!-- <a>
                <li><a href="{{ route('user.logout') }}" style="text-decoration: none;"
                        class="btn bg-primer text-white"><i class="fa fa-sign-out"></i> Logout</a></li>
            </a> -->

            <!-- ---------------- Menambahkan profile button ------------ -->

            <!-- <form class="profilebar form-inline my-2 my-lg-0 d-none d-md-block ml-auto"> -->
            <a href="/buat-event"
                class="ml-2 mr-2 text-inter self-center text-dark" style="font-size: 10pt; font-weight: bold;"><img width=23px src="{{ asset('images/create-event.png') }}" alt=""> Create Event</a>
            <a href="{{route('explore')}}"
                class="ml-2 mr-2 text-inter self-center text-dark" style="font-size: 10pt; font-weight: bold;"><img width="23px" src="{{ asset('images/explore.png') }}" alt=""> Explore</a>

            @if (isset($myData))
                <a class="ml-4" href="#" role="button" id="profilelink" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    @if ($myData->photo == 'default')
                        <img src="{{ asset('images/profile-user.png') }}" class="img-fluid rounded-circle profilelink-img"
                            alt="profile">
                    @else
                        <img src="{{ asset('storage/profile_photos/' . $myData->photo) }}"
                            class="img-fluid rounded-circle profilelink-img" alt="profile">
                    @endif
                </a>
            @endif

            <div class="btn btn-login btn-group my-2 my-sm-0 mr-3">
                
                @if (isset($myData))
                    {{-- <a class="" href="#" role="button" id="profilelink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        @if ($myData->photo == 'default')
                            <img src="{{ asset('images/icon-ak.png') }}"
                                class="img-fluid rounded-circle profilelink-img" alt="profile">
                        @else
                            <img src="{{ asset('storage/profile_photos/' . $myData->photo) }}"
                                class="img-fluid rounded-circle profilelink-img" alt="profile">
                        @endif
                    </a> --}}
                    <div id="dropdown-menu"
                        class="dropdown-menu dropdown-menu-right mr-4 mt-2 rounded-1 nav-top-profiler"
                        aria-labelledby="profilelink">
                        <div class="dropdown-item mt-2">
                            <div class="row">
                                <a href="{{ route('user.events') }}" target="_blank"
                                    class="pl-4 text-inter text-decoration-none dropdown-font">Events</a>
                            </div>
                        </div>
                        <div class="dropdown-item mt-2">
                            <div class="row">
                                <a href="{{ route('user.invitations') }}" target="_blank"
                                    class="pl-4 text-inter text-decoration-none dropdown-font">Invitations</a>
                            </div>
                        </div>
                        <div class="dropdown-item my-2">
                            <div class="row">
                                <a href="{{ route('user.profile') }}" target="_blank"
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
                                        target="_blak" class="text-decoration-none">
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
                        {{-- <a href="{{ route('user.events') }}"
                        class="ml-2 mr-2 text-inter self-center text-dark"><img width=23px src="{{ asset('images/create-event.png') }}" alt=""> Create Event</a>
                        <a href="{{route('explore')}}"
                                class="ml-2 mr-2 text-inter self-center text-dark"><img width="23px" src="{{ asset('images/explore.png') }}" alt=""> Explore</a> --}}

                        <a href="{{ route('user.registerPage') }}"
                            class="btn btn-outline-danger ml-2 mr-2 text-inter">Register</a>
                        <a href="{{ route('user.loginPage') }}" class="btn btn-primer ml-2 mr-2 text-inter"
                            style="width: unset">Login</a>

                @endif

            </div>



            <!-- --------------------------------------------------------- -->

        </nav>
        {{-- <div class="container-fluid "> --}}
        <nav class="row navbar navbar-expand-lg navbar-light menu-mobile mr-2">
            <button class="navbar-toggler ml-auto col-md bg-light" type="button" data-toggle="collapse"
                data-target="#nav-mobile">
                <span class="navbar-toggler-icon text-dark"></span>
            </button>
        </nav>

        <nav class="row navbar navbar-expand-lg navbar-light menu-mobile pr-4 w-100">
            <div class="collapse navbar-collapse rounded-5" id="nav-mobile">
                @if (isset($isManageEvent) && isset($myData))
                    <div class="navbar-nav bg-putih col-md-12 shadow-box rounded-5">
                        <a href="{{ route('organization.profilOrganisasi', $organizationID) }}"
                            class="btn btn-sm bg-primer mt-2 mb-2 w-100 ml-2 rounded-5"><i
                                class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                @endif

                <div class="navbar-nav bg-putih col-md-12 mt-2 navbar-scroll shadow-box rounded-5">
                    @isset($myData)
                        @isset($isManageEvent)
                            {{-- <a href="{{ route('organization.profilOrganisasi', $organizationID) }}"
                            class="btn btn-sm bg-primer mt-2 mb-3 w-100 ml-2 rounded-5"><i class="fas fa-arrow-left"></i>
                            Kembali
                        </a> --}}
                            <a href="{{ route('organization.event.eventoverview', [$organizationID, $eventID]) }}"
                                class="font-14 nav-item mt-2 mr-2 ml-2 pt-0">Event Overview</a>
                            <a href="{{ route('organization.event.edit', [$organizationID, $eventID]) }}"
                                class="font-14 nav-item mr-2 ml-2">Basic Info</a>
                            <a href="{{ route('organization.event.handbooks', [$organizationID, $eventID]) }}"
                                class="font-14 nav-item mr-2 ml-2">HandBook</a>
                            <a href="{{ route('organization.event.ticketSelling', [$organizationID, $eventID]) }}"
                                class="font-14 nav-item mr-2 ml-2">Ticket Selling</a>
                            <a href="{{ route('organization.event.receptionist', [$organizationID, $eventID]) }}"
                                class="font-14 nav-item mr-2 ml-2">Receptionist</a>
                            <a href="{{ route('organization.event.rundowns', [$organizationID, $eventID]) }}"
                                class="font-14 nav-item mr-2 ml-2">Rundowns</a>

                            {{-- <a href="{{ route('organization.event.lounge', [$organizationID, $eventID]) }}"
                            class="font-14 nav-item mr-2 ml-2"> Lounge</a> --}}
                            @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow' || $event->type == 'Live Music / Muic Conser' || $event->type == 'Show / Festival')
                                <a href="{{ route('organization.event.speakers', [$organizationID, $eventID]) }}"
                                    class="font-14 nav-item mr-2 ml-2">
                                    @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
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
                                <a href="{{ route('organization.event.sponsors', [$organizationID, $eventID]) }}"
                                    class="font-14 nav-item mr-2 ml-2">Sponsors</a>
                            @endif

                            <a href="{{ route('organization.event.tickets', [$organizationID, $eventID]) }}"
                                class="font-14 nav-item mr-2 ml-2">Ticket & Pricing</a>

                            @if ($event->execution_type == 'online')
                                <a href="#" class="font-14 nav-item mr-2 ml-2">Polling</a>
                            @endif
                            {{-- <a href="{{ route('organization.event.quiz', [$organizationID, $eventID]) }}"
                            class="font-14 nav-item mr-2 ml-2">Quiz</a> --}}
                        @else
                            <a href="{{ route('user.myTickets') }}" class="font-16 nav-item mr-2 ml-2 mt-2"> My
                                Tickets</a>
                            <a href="{{ route('user.connections') }}" class="font-16 nav-item mr-2 ml-2"> Connections</a>
                            <a href="{{ route('user.invitations') }}" class="font-16 nav-item mr-2 ml-2"> Invitations</a>
                            <a href="{{ route('user.messages') }}" class="font-16 nav-item mr-2 ml-2"> Messages</a>
                            <a href="{{ route('user.profile') }}" class="font-16 nav-item mr-2 ml-2"> Profile</a>
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
                                        Speaker Area
                                    </a>
                                @endif
                            @endisset

                        @endisset
                    @else
                        <div class="row">
                            {{-- <div class="col-12 text-left">
                                <a href="{{ route('user.events') }}"
                                    class="ml-3 mr-2 text-inter self-center text-dark"><img width=23px src="{{ asset('images/create-event.png') }}" alt=""> Create Event</a>
                            </div>
                            <div class="col-12 text-left">
                                <a href="{{ route('explore') }}"
                                    class="ml-3 mr-2 text-inter self-center text-dark"><img width="23px" src="{{ asset('images/explore.png') }}" alt=""> Explore</a>
                            </div> --}}
                            <div class="col-6 text-center">
                                <a href="{{ route('user.registerPage') }}"
                                    class="btn btn-outline-primer text-center w-100 rounded-5 teks-primer"
                                    style="text-decoration: none; margin-left: 15px;">Register</a>
                            </div>
                            <div class="col-6 text-center">
                                <a href="{{ route('user.loginPage') }}" class="btn btn-primer text-center rounded-5"
                                    style="text-decoration: none;">Login</a>
                            </div>
                        </div>
                    @endisset

                </div>
                <div class="navbar-nav bg-putih col-md-12 shadow-box rounded-5 mt-2">
                    <form action="{{ route('explore') }}" role="search" id="search-form"
                        class="mt-3 ml-3 mr-3">
                        @csrf
                        <div class="input-group-prepend input-icons">
                            <i class="fa fa-search icon-2 teks-primer" onclick="search()" style="font-size: 20px;"></i>
                            <input type="text" class="box border-0" name="search"
                                style="padding-left: 40px; color: #BDBDBD; font-weight: 400; font-family: DM_Sans !important; font-size: 12px; height: 35px; background-color: #F8F8F8; margin-top: 0px;"
                                placeholder="Search Events" required value="">
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12 mt-3">
                            <a href="/buat-event"
                                class="ml-3 mr-2 text-inter self-center text-dark"><img width=23px src="{{ asset('images/create-event.png') }}" alt=""> Create Event</a>
                        </div>
                        <div class="col-12">
                            <a href="{{ route('explore') }}"
                                class="ml-3 mr-2 text-inter self-center text-dark"><img width="23px" src="{{ asset('images/explore.png') }}" alt=""> Explore</a>
                        </div>
                    </div>
                    @isset($myData)
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
                            <div class="text-center pl-3">
                                <button class="btn btn-no-pd bg-primer w-100">Event {{ $event->execution_type }}</button>
                            </div>
                            <div class="row text-center">

                                <div class="col-4 text-center">
                                    <form method="POST"
                                        action="{{ route('organization.event.publish', [$organizationID, $eventID]) }}"
                                        class="d-inline">
                                        {{ csrf_field() }}
                                        <button class="btn btn-no-pd bg-primer ml-3"><i class="fa fa-paper-plane"
                                                aria-hidden="true"></i>
                                            Publish</button>

                                    </form>
                                </div>
                                <div class="col-4 text-center">
                                    <form method="POST"
                                        action="{{ route('organization.event.un_publish', [$organizationID, $eventID]) }}"
                                        class="d-inline text-center">
                                        {{ csrf_field() }}
                                        <button class="btn btn-no-pd bg-outline-light text-dark ml-3">Un-Publish</button>

                                    </form>
                                </div>
                                <div class="col-4 text-center">
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
                    @else
                        <div class="row">
                            <div class="col-6 text-center">
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
                    @endisset
                </div>
            </div>

        </nav>

    </header>

    @yield('content-parent')

    {{-- Width aslinya = 58px + 60px jika ada menu lagi diatasnya --}}
    <div id="content-section">
        @yield('content')
    </div>

    <div class="bg-putih pt-5">
        @include('layouts.footer')
    </div>
    
    <script src="{{ asset('js/base.js') }}"></script>

    @if (isset($myData))
        <!-- Modal Tambah Organisasi -->

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

        <div class="bg"></div>
        <div class="popupWrapper" id="create_oraganization" style="background-color: #00000075;">
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
        <div class="popupWrapper" id="select_oraganization" style="background-color: #00000075;">
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
                            {{-- <a class="mt-2 link-org-popup"
                                href="{{ route('organization.event.create', [$organization->id]) }}"> --}}
                            <a href="{{ route('create-event') }}" class="mt-2 link-org-popup">
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
                            {{-- <a class="link-org-popup" href="{{ route('organization.event.create', [$organizationTeam->organizations->id]) }}"> --}}
                            <a href="{{ route('create-event') }}" class="link-org-popup">
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

        <script>
            function confirmAddOr() {
                var pkgActive = '{{ $myData->package->status }}';
                var pkgActive = parseInt(pkgActive);
                var myData = <?php echo json_encode($myData) ?>;
                var myPkg = <?php echo json_encode($myData->package) ?>;

                var paramCombine = false;
                if(myPkg.organizer_count <= -1){
                    paramCombine = false;
                }else{
                    if(myData.organizations.length >= myPkg.organizer_count){
                        paramCombine = true;
                    }
                }
                
                console.log(myData, myPkg);
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
    @endif

    <script>
        function search() {
            select('#search-form').submit();
        }
    </script>

    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
    </script>
    {{-- Sweet alert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css"> --}}

    @yield('javascript')
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Streaming - Agendakota</title>
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('js/select2.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <!-- Fevicon -->
    <link rel="shortcut icon" href="{{ asset('images/icon-ak.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;600;700&display=swap" rel="stylesheet">

    <script src="https://kit.fontawesome.com/bc29c7987f.js" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @yield('head.dependencies')

    <link rel="stylesheet" href="{{ asset('css/user/newStreamPage.css') }}">
</head>

<body style="background-color: #262b2e;">

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

    <header class="fixed-top">

        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container-fluid">
                <a href="{{ route('user.homePage') }}" class="mr-2 navbar-brand">
                    <img src="{{ asset('images/logo.png') }}" class=" logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="ms-4 collapse navbar-collapse" id="navbarSupportedContent">

                    <div class="navbar-nav me-auto ms-auto mb-2 mb-lg-0 text-center">
                        <button class="btn btn-light fw-bold" style="background-color: aliceblue">
                            {{ $different->invert == 0 ? 'Acara Dimulai Dalam ' : ($now >= $endEvent ? 'Acara Telah Selesai' : 'Acara Telah Dimulai Selama ') }}

                            <a id="countdown1" class="teks-primer"></a>
                        </button>
                    </div>
                    <ul class="navbar-nav side-nav-collapse me-auto mb-2 mb-lg-0">

                        <div id="drop-menu"
                            class="navbar-nav bg-putih ps-3 pe-3 pr-3 col-md-12 mt-2 navbar-scroll shadow-box rounded-5">

                            @if ($paramPage == 'byUser')
                                <a class="home-btn font-16 nav-item mr-2 ml-2 mt-2 pointer">
                                    Home
                                </a>
                            @elseif ($paramPage == 'byAdmin')
                                <a class="home-btn font-16 nav-item mr-2 ml-2 mt-2 pointer">
                                    Home
                                </a>
                            @endif

                            {{-- <a href="#Receptionist" class="tablinks-stream-menu font-16 nav-item mr-2 ml-2 stream-menu"
                                value="Receptionist">
                                Receptionist
                            </a> --}}
                            <a class="handbooks-btn tablinks-stream-menu font-16 nav-item mr-2 ml-2 stream-menu"
                                value="Handbooks">
                                HandBook
                            </a>
                            @if (preg_match('/Stage and Session/i', $event->breakdown))
                                <a class="stage-btn pointer tablinks-stream-menu font-16 nav-item mr-2 ml-2">Streaming
                                </a>
                            @else
                                <a class="stage-btn font-16 pointer tablinks-stream-menu font-16 nav-item mr-2 ml-2">Streaming
                                </a>
                            @endif

                            <a class="connections-btn font-16 pointer tablinks-stream-menu font-16 nav-item mr-2 ml-2">Connections
                            </a>
                            @if (preg_match('/Exihibitors/i', $event->breakdown))
                                <a
                                    class="exhibitions-btn font-16 pointer tablinks-stream-menu font-16 nav-item mr-2 ml-2">Exhibitors
                                </a>
                            @endif


                        </div>
                        <div class="navbar-nav bg-putih ps-3 pe-3 pr-3 col-md-12 shadow-box rounded-5 mt-2">
                            <a class="font-16 nav-item-neg mr-2 ml-2 mt-2 text-center"
                                href="{{ route('user.events') }}">
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

                            <a href="{{ route('user.upgradePkg') }}"
                                class="font-16 nav-item-neg mr-2 ml-2 mb-2 text-center btn btn-primer"
                                target="_blank">Upgrade</a>
                            <a href="{{ route('user.logout') }}" class="font-16 nav-item-neg mr-2 ml-2 text-center"><i
                                    class="bi bi-box-arrow-left font-16 text-danger"></i>&nbsp; Logout</a>

                        </div>


                    </ul>

                    <div id="group-btn-nav-right" class="d-flex">
                        <div class="me-2 d-flex">
                            <button id="sidebar-show" class="btn btn-outline-primer ms-3 my-auto d-none"
                                onclick="showSidebar()">
                                <i class="bi bi-layout-sidebar"></i>&nbsp; Sidebar
                            </button>
                            <button id="sidebar-hidden" class="btn btn-outline-primer ms-3 my-auto d-none"
                                onclick="hiddSidebar()">
                                <i class="bi bi-arrows-angle-expand"></i>&nbsp; Hide Sidebar
                            </button>

                            <a href="{{ route('user.homePage') }}" class="homepage-links my-auto">
                                Temukan Event
                            </a>
                            <a href="#" class="homepage-links my-auto">
                                Bantuan
                            </a>
                        </div>
                        @if (isset($myData))
                            <a class="btn" id="profilelink" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                @if ($myData->photo == 'default')
                                    <img src="{{ asset('images/profile-user.png') }}"
                                        class="rounded-circle profilelink-img" alt="profile">
                                @else
                                    <img src="{{ asset('storage/profile_photos/' . $myData->photo) }}"
                                        class="img-fluid rounded-circle profilelink-img pointer" alt="profile">
                                @endif
                            </a>
                        @endif
                    </div>

                </div>
            </div>

            {{-- Dropdown profile menu --}}
            <div id="profile-menu" class="d-none" style="position: fixed;">

                <div id="dropdown-menu" class="" aria-labelledby="profilelink">
                    <ul>
                        <li>
                            <div class="row">
                                <a href="{{ route('user.events') }}"
                                    class="pl-4 text-inter text-decoration-none dropdown-font">Events</a>
                            </div>
                        </li>
                        <li>
                            <div class="row">
                                <a href="{{ route('user.invitations') }}"
                                    class="pl-4 text-inter text-decoration-none dropdown-font">Invitations</a>
                            </div>
                        </li>
                        <li>
                            <div class="row">
                                <a href="{{ route('user.profile') }}"
                                    class="pl-4 text-inter text-decoration-none dropdown-font">Profile</a>
                            </div>
                        </li>
                        <li>
                            <div class="row">
                                <a href="{{ route('user.logout') }}"
                                    class="pl-4 text-inter text-decoration-none dropdown-font">Logout</a>
                            </div>
                        </li>
                        <div class="row row__mod">
                            <div class="col">
                                <a href="{{ route('user.upgradePkg') }}" target="_blank"
                                    class="btn btn-primer pl-4 text-inter text-decoration-none dropdown-font">Upgrade</a>
                            </div>
                        </div>
                        <li id="bottom-part-profile-menu">
                            @if (isset($myData->organizations))
                                <p class="text-center mt-3 mb-2" style=" color: #717171d1;">ORGANIZATIONS</p>
                                @foreach ($myData->organizations as $organizationInner)
                                    <div class="mb-2">
                                        <a href="{{ route('organization.profilOrganisasi', $organizationInner->id) }}"
                                            class="text-decoration-none">
                                            <div class="row">
                                                <div class="col-md-1">
                                                    <img src="{{ $organizationInner->logo == '' ? asset('storage/organization_logo/default_logo.png') : asset('storage/organization_logo/' . $organizationInner->logo) }}"
                                                        class="rounded-circle organization-icon-dropdown"
                                                        alt="">
                                                </div>
                                                <div class="col-md dropdown-font d-flex">
                                                    <div class="my-auto ms-3">
                                                        {{ $organizationInner->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            @endif

                        </li>
                    </ul>

                </div>

        </nav>

        {{-- -------- Parameter List untuk menentukan batas pcakage user ------------------ --}}
        @php
            $nowDt = new DateTime();
            $startLimit = new DateTime($myData->created_at);
            $different = $startLimit->diff($nowDt);
            $pkgActive = \App\Http\Controllers\PackagePricingController::limitCalculator($myData);
        @endphp

        {{-- ------------------------------------------------------------------------------ --}}


        {{-- -------------- Status paket yang dibeli user -------------------------------- --}}

        <div id="flag-pkg" class="alert {{ $pkgActive == 1 ? 'alert-success' : 'alert-warning' }} pt-0 pb-0"
            style="line-height: 40px">
            {{ $myData->package->name }} Package |
            <div class="d-inline mt-1" style="margin-bottom: unset !important; display: inline!important;">
                <a href="{{ route('user.upgradePkg') }}"
                    class="font-16 nav-item mt-2 ml-2 mb-2 text-center text-danger" target="_blank">Upgrade</a>
            </div>
        </div>

        {{-- ------------------------------------------------------------------------------ --}}


    </header>

    <nav id="side-bar" class="left nav-atas fixed-top"
        style="{{ isset($streamPage) == true ? 'width:9%;' : 'top: 100px;' }} animation: swapInLeft 300ms;">
        <div>
            <div class="menu navstream pt-2 pb-2 my-auto mx-auto bg-white rounded-15">

                @if ($paramPage == 'byUser')
                    <a class="font-16 pointer">
                        <li class="home-btn text-center">
                            <img class="rounded-5 asp-rt-1-1 bayangan-5 mt-3 mb-3" width="60%"
                                src="{{ $organization->logo == '' ? asset('storage/organization_logo/default_logo.png') : asset('storage/organization_logo/' . $organization->logo) }}"
                                alt="">
                        </li>
                    </a>
                @elseif ($paramPage == 'byAdmin')
                    <a class="font-16 pointer">
                        <li class="home-btn text-center">
                            <img class="rounded-5 asp-rt-1-1 bayangan-5 mt-3 mb-3" width="60%"
                                src="{{ $organization->logo == '' ? asset('storage/organization_logo/default_logo.png') : asset('storage/organization_logo/' . $organization->logo) }}"
                                alt="">
                        </li>
                    </a>
                @endif

                {{-- <a href="#Receptionist" value="Receptionist" class="font-16 stream-menu">
                    <li id="tablinks-stream-menu-Receptionist" class="tablinks-stream-menu text-center"
                        style="color: #4d4c4c;
                    font-size: 13px;">
                        <i class="bi bi-file-person d-block mr-0 pt-2" style="font-size: 22pt;"></i>Receptionist
                    </li>
                </a> --}}
                <a class="stream-menu pointer" value="Handbooks">
                    <li class="handbooks-btn tablinks-stream-menu text-center"
                        style="color: #4d4c4c;
                    font-size: 13px;">
                        <i class="fa fa-file d-block mr-0 pt-2" style="font-size: 22pt;"></i> HandBook
                    </li>
                </a>
                @if (preg_match('/Stage and Session/i', $event->breakdown))
                    <a class="font-16 pointer">
                        <li id="stage-btn" class="stage-btn tablinks-stream-menu text-center"
                            style="color: #4d4c4c;
                    font-size: 13px;">
                            <i class="bi bi-camera-video d-block mr-0 pt-2" style="font-size: 22pt;"></i> Streaming
                        </li>
                    </a>
                @else
                    <a class="font-16 pointer">
                        <li id="stage-btn" class="stage-btn tablinks-stream-menu text-center"
                            style="color: #4d4c4c;
                    font-size: 13px;">
                            <i class="bi bi-camera-video d-block mr-0 pt-2" style="font-size: 22pt;"></i> Streaming
                        </li>
                    </a>
                @endif

                <a class="font-16 pointer">
                    <li class="connections-btn tablinks-stream-menu text-center"
                        style="color: #4d4c4c;
                    font-size: 13px;">
                        <i class="fa fa-lg bi bi-people d-block mr-0 pt-2" style="font-size: 22pt;"></i>Connections
                    </li>
                </a>
                @if (preg_match('/Exihibitors/i', $event->breakdown))
                    {{-- <a href="{{ route('user.home.exhibitions', [$eventID, 0]) }}"> --}}
                    <a class="font-16 pointer">
                        <li class="exhibitions-btn tablinks-stream-menu text-center"
                            style="color: #4d4c4c; font-size: 13px;">
                            <i class="fa fa-chalkboard-teacher d-block mr-0 pt-2" style="font-size: 22pt;"></i>
                            Exhibitors
                        </li>
                    </a>
                @endif

            </div>
            <div class="menu navstream pt-2 mt-2 pb-2 my-auto mx-auto bg-white rounded-15" style=" background-color: #e7314f !important;">
                <a type="button" data-bs-toggle="modal" data-bs-target="#scheduleModal"
                    class="font-16 pointer text-center w-100">
                    <li class="schedules-btn tablinks-stream-menu text-center"
                        style="color: #fff; font-size: 13px;">
                        <i class="fa fa-lg bi bi-calendar-week d-block mr-0 pt-2"
                            style="font-size: 22pt;"></i>Schedules
                    </li>
                </a>
                <a type="button" data-bs-toggle="modal" data-bs-target="#sponsorModal"
                    class="font-16 pointer text-center w-100">
                    <li class="sponsors-btn tablinks-stream-menu text-center"
                        style="color: #fff; font-size: 13px;">
                        <i class="fa fa-lg bi bi-easel2 d-block mr-0 pt-2" style="font-size: 22pt;"></i>Sponsors
                    </li>
                </a>
            </div>
        </div>
    </nav>

    {{-- Main Content full page --}}
    <div class="content-stream">
        <div id="stream-row" class="row stream-row" style="display: none; animation: fadeIn 300ms;">
            @yield('HomeStream')
            @yield('ReceptionistList')
            @yield('HandbookList')
            @yield('ContentStream')
            @yield('ConnectionList')
            @yield('ExhibitorList')
            @yield('SponsorList')
            @yield('ScheduleList')
        </div>
    </div>
    {{-- --------------------- --}}

    <div>
        @include('layouts.chat-stream')
    </div>
    @if ($event->twn_url != null)
        <a href="{{$event->twn_url}}" id="btn-photo-booth" class="float-url rounded-5 d-flex" target="_blank">
            <div class="rounded-5">
                <i class="fa bi bi-camera-fill fs-4 text-light"></i>
            </div>
            <b class="ms-2 me-2 my-auto">Photo Booth</b>
        </a>
    @endif
    <a id="btn-chat-stream" style="animation: swapInRight 300ms;" href="#" class="float">
        <i class="fa bi bi-chat-left-text my-float"></i>
    </a>
    <div class="nav-bottom text-center bg-white p-3 w-100">
        <div class="row">
            <div class="col-4">
                <a type="button" data-bs-toggle="modal" data-bs-target="#scheduleModal"
                    class="font-16 pointer text-center w-100">
                    <li class="schedules-btn tablinks-stream-menu text-center"
                        style="color: #4d4c4c; font-size: 13px;">
                        <i class="fa fa-lg bi bi-calendar-week d-block mr-0 pt-2"
                            style="font-size: 22pt;"></i>Schedules
                    </li>
                </a>
            </div>
            <div class="col-4">
                @if (preg_match('/Stage and Session/i', $event->breakdown))
                    <a class="font-16 pointer">
                        <li id="stage-btn" class="stage-btn tablinks-stream-menu text-center"
                            style="color: #4d4c4c !important; background-color: #fff !important;
                    font-size: 13px;">
                            <i class="bi bi-camera-video d-block mr-0 pt-2" style="font-size: 22pt;"></i> Streaming
                        </li>
                    </a>
                @else
                    <a class="font-16 pointer">
                        <li id="stage-btn" class="stage-btn tablinks-stream-menu text-center"
                            style="color: #4d4c4c !important; background-color: #fff !important;
                    font-size: 13px;">
                            <i class="bi bi-camera-video d-block mr-0 pt-2" style="font-size: 22pt;"></i> Streaming
                        </li>
                    </a>
                @endif
            </div>
            <div class="col-4">
                <a type="button" data-bs-toggle="modal" data-bs-target="#sponsorModal"
                    class="font-16 pointer text-center w-100">
                    <li class="sponsors-btn tablinks-stream-menu text-center"
                        style="color: #4d4c4c; font-size: 13px;">
                        <i class="fa fa-lg bi bi-easel2 d-block mr-0 pt-2" style="font-size: 22pt;"></i>Sponsors
                    </li>
                </a>
            </div>
            
        </div>
    </div>

    @yield('body_only')
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
    </script>

    <script src="{{ asset('js/base.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
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
        var organizations = <?php echo json_encode($myData->organizations); ?>;
        var organizationsTeam = <?php echo json_encode($myData->organizationsTeam); ?>;
    </script>

    <script src="{{ asset('js/user/streaming-basic.js') }}"></script>
    <script src="{{ asset('js/user/streamContent.js') }}"></script>

</body>

</html>

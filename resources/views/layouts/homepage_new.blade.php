<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('riyan/css/base/font.css') }}">
    <link rel="stylesheet" href="{{ asset('riyan/boxicons/css/boxicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/new-design/homepage/homepage.css') }}">

    @yield('head.dependencies')
</head>

<body>
    <input type="hidden" id="gcid" value="{{ env('GOOGLE_CLIENT_ID') }}">

    <header class="TopBar flex column item-center">
        {{-- Navtop --}}
        <div class="top-ribbon d-flex">
            <div class="d-flex justify-content-end my-auto ms-auto">
                <a href="https://company.agendakota.id/about-us" class="nav-link text-dark fw-bold y-auto me-3 ms-3"
                    target="_blank">
                    About
                </a>
                <a href="https://company.agendakota.id/news" class="nav-link text-dark fw-bold y-auto me-3 ms-3"
                    target="_blank">
                    Blog
                </a>
                <a href="https://company.agendakota.id/event-creator"
                    class="nav-link text-dark fw-bold y-auto me-3 ms-3" target="_blank">
                    Event Creator
                </a>
                <a href="https://company.agendakota.id/contact" class="nav-link text-dark fw-bold y-auto me-3 ms-3"
                    target="_blank">
                    Contact Us
                </a>
            </div>
        </div>

        {{-- Navbar --}}
        <nav class="navbar navbar-expand-lg bg-body-tertiary bg-origin pt-0 pb-0">
            <div class="container-fluid bg-origin navbar-inner">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('images/logo-mix.png') }}" alt="Logo Agendakota" class="logo">
                </a>
                <button class="navbar-toggler text-light" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasRightMain" aria-controls="offcanvasRightMain">
                    <i class="bx bx-menu-alt-right fs-2"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <form class="d-flex" role="search" action="{{ route('explore') }}">
                        @csrf
                        <input class="form-control me-auto form-search" type="search" placeholder="Cari Event Disini"
                            aria-label="Search" name="search">
                    </form>
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="/buat-event" class="nav-link h-100">
                                <div class="d-flex pl-2 pr-2 text-light h-100">
                                    <i class="bx bx-calendar fs-3 my-auto"></i>
                                    <div class="text ms-1 my-auto">Buat Event</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item ps-3">
                            <a class="nav-link h-100" href="/explore">
                                <div class="d-flex pl-2 pr-2 text-light h-100">
                                    <i class="bx bx-compass fs-3 my-auto"></i>
                                    <div class="text ms-1 my-auto">Jelajah Event</div>
                                </div>
                            </a>
                        </li>
                        @if ($myData == null || $myData == '')
                            <li class="nav-item ps-3">
                                <a class="nav-link btn btn-outline-light bg-origin h-100 rounded-pill btn-navbar"
                                    href="{{ route('user.registerPage') }}">
                                    Register
                                </a>
                            </li>
                            <li class="nav-item ms-2">
                                <a class="btn btn-dark h-100 rounded-pill btn-navbar"
                                    href="{{ route('user.loginPage') }}">
                                    Login
                                </a>
                            </li>
                        @else
                            <li class="nav-item ps-3">
                                <a class="pointer" type="button" data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                    <img class="avatar rounded-circle"
                                        src="{{ $myData->photo == 'default' ? asset('storage/profile_photos/profile-user.png') : asset('storage/profile_photos/' . $myData->photo) }}"
                                        alt="">
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        {{-- Offcnvas user profile --}}
        <div class="offcanvas offcanvas-end offcanvas-profile shadow-lg" tabindex="-1" id="offcanvasRight"
            data-bs-scroll="true" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasRightLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            @if ($myData != null || $myData != '')
                <div class="offcanvas-body">
                    <div class="row">
                        <div class="col-12 d-flex">
                            <img width="80px" height="80px" class="rounded-circle"
                                src="{{ $myData->photo == 'default' ? asset('storage/profile_photos/profile-user.png') : asset('storage/profile_photos/' . $myData->photo) }}"
                                alt=""> <br>
                            <div class="my-auto mx-auto">
                                <h5 class="fw-bold">{{ $myData->name }}</h5>
                                <p class="mb-0">{{ $myData->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row pl-2 pr-2">
                        <div class="col-md-6 mt-3 p-1 text-center">
                            <a href="{{ route('user.events') }}" class="btn btn-nav-canvas shadow w-100">
                                Events
                            </a>
                        </div>
                        <div class="col-md-6 mt-3 p-1 text-center">
                            <a href="{{ route('user.myTickets') }}" class="btn btn-nav-canvas shadow w-100">
                                My Tickets
                            </a>
                        </div>
                        <div class="col-md-6 mt-3 p-1 text-center">
                            <a href="{{ route('user.invitations') }}" class="btn btn-nav-canvas shadow w-100">
                                Invitations
                            </a>
                        </div>
                        <div class="col-md-6 mt-3 p-1 text-center">
                            <a href="{{ route('user.profile') }}" class="btn btn-nav-canvas shadow w-100">
                                Profile
                            </a>
                        </div>
                        <div class="col-12 mt-3">
                            <a href="{{ route('user.upgradePkg') }}" class="btn btn-danger w-100 text-center">
                                Upgrade
                            </a>
                        </div>
                        <div class="col-12 mt-3">
                            <a href="{{ route('user.logout') }}" class="btn btn-outline-danger w-100 text-center">
                                Logout
                            </a>
                        </div>
                        <div class="col-12 mt-4">
                            <a href="{{ route('user.myorganization') }}" class="text-secondary d-flex">
                                <p class="text-secondary">My Organizations</p>
                                <i class='bx bx-right-arrow-alt fs-2 ms-auto me-2'></i>
                            </a>
                        </div>
                        @foreach ($myData->organizations as $org)
                            <div class="col-12 mb-3">
                                <a href="{{ route('organization.profilOrganisasi', [$org->id]) }}" class="text-dark">
                                    <div class="p-3 d-flex shadow btn-nav-canvas rounded-2">
                                        <img width="40px" height="40px"
                                            src="{{ $org->logo == '' ? asset('storage/organization_logo/default_logo.png') : asset('storage/organization_logo/' . $org->logo) }}"
                                            alt="" class="rounded-circle">
                                        <p class="text-dark ms-3 mb-auto mt-auto">{{ $org->name }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        {{-- Ofcanvas mobile sidebar --}}
        <div class="offcanvas offcanvas-end offcanvas-profile shadow-lg" tabindex="-1" id="offcanvasRightMain"
            data-bs-scroll="true" aria-labelledby="offcanvasRightMainLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasRightMainLabel">Navigasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="row w-100">
                    <div class="col-12 pb-4 mb-4">
                        <form class="d-flex" role="search" action="{{ route('explore') }}">
                            <input class="form-control mx-auto form-search shadow btn-nav-canvas" type="search"
                                placeholder="Cari Event Disini" aria-label="Search" name="search">
                        </form>
                    </div>
                    <div class="col-12 pt-3 pb-3">
                        <a href="/buat-event" class="nav-link h-100">
                            <div class="d-flex pl-2 pr-2 text-dark d-flex h-100">
                                <i class="bx bx-calendar fs-3 my-auto ms-auto"></i>
                                <div class="text ms-1 my-auto me-auto">Buat Event</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 pt-3 pb-3">
                        <a class="nav-link h-100" href="/explore">
                            <div class="d-flex pl-2 pr-2 text-dark d-flex h-100">
                                <i class="bx bx-compass fs-3 my-auto ms-auto"></i>
                                <div class="text ms-1 my-auto me-auto">Jelajah Event</div>
                            </div>
                        </a>
                    </div>
                    @if ($myData == null || $myData == '')
                        <div class="col-12 pt-3 text-center">
                            <a class="btn btn-outline-danger h-100 rounded-pill btn-navbar"
                                href="{{ route('user.registerPage') }}">
                                Register
                            </a>
                        </div>
                        <div class="col-12 pt-3 pb-3 text-center">
                            <a class="btn btn-dark h-100 rounded-pill btn-navbar"
                                href="{{ route('user.loginPage') }}">
                                Login
                            </a>
                        </div>
                    @else
                        <div class="col-12 pt-3 pb-3 text-center">
                            <a class="pointer text-dark" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                <img class="avatar rounded-circle"
                                    src="{{ $myData->photo == 'default' ? asset('storage/profile_photos/profile-user.png') : asset('storage/profile_photos/' . $myData->photo) }}"
                                    alt=""> <br>
                                {{ $myData->email }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>


    <div class="content p-1">
        @yield('content')

    </div>
    <footer class="d-flex bg-origin p-5">
        <div class="row w-100 text-light mt-auto">
            <div class="col-md-5 mb-5">
                <img width="244px" src="{{ asset('images/logo-mix.png') }}">
                <div class="Address mt-2 lh-30 mb-2 fs-footer">
                    Koridor Coworking Space, Gedung Siola,<br />
                    Jalan Tunjungan, Surabaya
                </div>
                <a href="mailto:halo@agendakota.id" class="fs-footer">
                    <div class="d-flex">
                        <i class="bx bx-envelope my-auto me-3"></i>
                        <div class="my-auto">halo@agendakota.id</div>
                    </div>
                </a>
                <a href="https://wa.me/6288990079999" class="fs-footer" target="_blank">
                    <div class="d-flex">
                        <i class="bx bx-phone my-auto me-3"></i>
                        <div class="my-auto">+62 889 9007 9999</div>
                    </div>
                </a>
            </div>
            <div class="col-md-7">
                <div class="row w-100">
                    <div class="col-lg-5 mb-5">
                        <h3 class="mb-4 fs-footer-title">Product</h3>
                        <a class="fs-footer" href="https://company.agendakota.id/product/virtual-venue/" target="_blank">
                            Virtual Venue
                        </a>
                        <a class="fs-footer" href="https://company.agendakota.id/product/broadcast-studio/" target="_blank">
                            Broadcast Studio
                        </a>
                        <a class="fs-footer" href="https://company.agendakota.id/product/event-marketing/" target="_blank">
                            Event Marketing 
                        </a>
                        <a class="fs-footer" href="https://company.agendakota.id/product/event-management/" target="_blank">
                            Event Management    
                        </a>
                    </div>
                    <div class="col-lg-4 mb-5">
                        <h3 class="mb-4 fs-footer-title">Solution</h3>
                        <a class="fs-footer" href="https://company.agendakota.id/solutions/virtual-events/" target="_blank">
                            Virtual Event   
                        </a>
                        <a class="fs-footer" href="https://company.agendakota.id/solutions/onsite-events/" target="_blank">
                            Onsite Event    
                        </a>
                        <a class="fs-footer" href="https://company.agendakota.id/solutions/hybrid-events/" target="_blank">
                            Hybrid Event    
                        </a>
                        <a class="fs-footer" href="https://company.agendakota.id/solutions/internal-events/" target="_blank">
                            Internal Event  
                        </a>
                        <a class="fs-footer" href="https://company.agendakota.id/#" target="_blank">
                            In-Person Event 
                        </a>
                    </div>
                    <div class="col-lg-3">
                        <h3 class="mb-4 fs-footer-title">Support</h3>
                        <a class="fs-footer" href="#" target="_blank">
                            FAQ 
                        </a>
                        <a class="fs-footer" href="https://company.agendakota.id/news/" target="_blank">
                            Blog    
                        </a>
                        <a class="fs-footer" href="https://company.agendakota.id/media-partner/" target="_blank">
                            Media Partner   
                        </a>
                        <a class="fs-footer" href="https://company.agendakota.id/help/" target="_blank">
                            Help    
                        </a>
                        <a class="fs-footer" href="https://company.agendakota.id/contact/" target="_blank">
                            Contact 
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
    {{-- <script src="{{ asset('riyan/js/base.js') }}"></script>
    <script src="{{ asset('riyan/js/element.js') }}"></script>
    <script src="{{ asset('riyan/js/Currency.js') }}"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="{{ asset('riyan/js/Authentication.js') }}"></script>
    <script src="{{ asset('riyan/js/moment-with-locale.js') }}"></script>
    <script>
        let isHeaderMenuActive = false;
        let myDataRaw = "{{ $myData }}";
        if (myDataRaw == "") {
            localStorage.removeItem('user_data');
            var myData = null;
        } else {
            var myData = JSON.parse(escapeJson("{{ $myData }}"));
            let oldUserData = JSON.parse(localStorage.getItem('user_data'));
            if (oldUserData != null && myData.id != oldUserData.id) {
                localStorage.removeItem('event_data');
            }
            localStorage.setItem('user_data', JSON.stringify(myData));
        }

        const toggleMenu = () => {
            if (isHeaderMenuActive) {
                select(".TopBar .Header form").style.right = "-155%";
                select(".TopBar .Header .buttons").style.right = "-155%";
            } else {
                select(".TopBar .Header form").style.right = "5%";
                select(".TopBar .Header .buttons").style.right = "0%";
            }
            isHeaderMenuActive = !isHeaderMenuActive;
        }
        const toggleProfileMenu = () => {
            select(".ProfileArea").classList.toggle('active');
        }

        let cardAnimationInterval = {};
        const renderEventCard = (event, target, customClass = '', loaderCount = 5) => {
            cardAnimationInterval[target] = null;
            if (event !== null) {
                clearInterval(cardAnimationInterval[target]);
                let displayDate = event.start_date == event.end_date ?
                    moment(event.start_date).format('DD MMM Y') :
                    moment(event.start_date).format('DD MMM') + " - " + moment(event.end_date).format("DD MMM Y");
                let displayLocation = event.location.substr(0, 25);
                if (event.location.split(displayLocation)[1] != "") {
                    displayLocation += "...";
                }
                let eventName = event.event_name !== undefined ? event.event_name : event.name;
                let displayName = eventName.substr(0, 30);
                if (event.name.split(displayName)[1] != "") {
                    displayName += "...";
                }

                Element("div", {
                        class: `EventCard ${customClass}`,
                    })
                    .render(target, `<a href="/event-detail/${event.slug}" class="text black"><img src="/storage/event_assets/${event.slug}/event_logo/thumbnail/${event.logo}" class="cover" /></a>
            <div class="p-2">
                <a href="/event-detail/${event.slug}" class="text black">
                    <div class="EventInfo">
                        <h4 class="m-0">${displayName}</h4>
                        <div class="flex row item-center h-30 mt-2">
                            <i class="bx bx-calendar"></i>
                            <div class="text small ml-2">${displayDate}</div>
                        </div>
                        <div class="flex row item-center h-30">
                            <i class="bx bx-map"></i>
                            <div class="text small ml-2">${displayLocation}</div>
                        </div>
                        <div class="flex row item-center h-30 mb-1">
                            <i class="bx bx-money"></i>
                            <div class="text small ml-2">${event.price == 0 || event.price == null ? "Gratis" : Currency(event.price).encode()}</div>
                        </div>
                    </div>
                </a>

                <div class="border-top mt-2 pt-1 flex row item-center">
                    <img src="/storage/organization_logo/${event.organizer.logo}" alt="Organizer Logo" class="OrganizerLogo">
                    <div class="text small ml-1">${event.organizer.name}</div>
                </div>
            </div>`)
            } else {
                for (let i = 0; i < loaderCount; i++) {
                    Element("div", {
                            class: `EventCard bordered ${customClass}`,
                        })
                        .render(target, `<div class="bg-grey rounded-top-left rounded-top-right" class="cover" style="height: 120px;"></div>
                <div class="p-2">
                    <div class="EventInfo">
                        <h4 class="m-0">
                            <div style="background: #eee;" class="w-100 h-30"></div>
                        </h4>
                        <div class="flex row item-center h-30 mt-2">
                            <i class="bx bx-calendar"></i>
                            <div class="text small ml-2 w-50">
                                <div style="background: #eee;" class="w-100 h-10"></div>
                            </div>
                        </div>
                        <div class="flex row item-center h-30">
                            <i class="bx bx-map"></i>
                            <div class="text small ml-2 w-50">
                                <div style="background: #eee;" class="w-100 h-10"></div>
                            </div>
                        </div>
                        <div class="flex row item-center h-30 mb-1">
                            <i class="bx bx-money"></i>
                            <div class="text small ml-2 w-50">
                                <div style="background: #eee;" class="w-100 h-10"></div>
                            </div>
                        </div>
                    </div>

                    <div class="border-top mt-2 pt-1 flex row item-center">
                        <div class="OrganizerLogo bg-grey"></div>
                        <div class="text small ml-1 w-70">
                            <div style="background: #eee;" class="w-100 h-10"></div>
                        </div>
                    </div>
                </div>`)
                }
                let opacity = 100;
                let intervalAction = 'decrease';
                cardAnimationInterval[target] = setInterval(() => {
                    if (intervalAction == 'decrease') {
                        opacity -= 10;
                    } else {
                        opacity += 10;
                    }
                    if (opacity == 90) {
                        intervalAction = 'decrease';
                    }
                    if (opacity == 10) {
                        intervalAction = 'increase';
                    }
                    selectAll(`${target} .EventCard`).forEach(card => card.style.opacity = opacity / 100);
                }, 100);
            }
        }
    </script> --}}
    @yield('javascript')

</body>

</html>

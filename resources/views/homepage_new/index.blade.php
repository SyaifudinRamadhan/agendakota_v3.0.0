@extends('layouts.homepage_new')

@section('title', 'Home')

@section('head.dependencies')
    <link rel="stylesheet" href="{{ asset('css/new-design/homepage/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/new-design/skeleton-loading.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div id="pinnedEvent" class="ps-5 pe-5">
        {{-- carousel --}}
        <div id="carousel-content" class="w-100 slider-row">
            <div id="row-carousel" class="row first-pos">
                {{-- <div class="col-5">
                    <a href="">
                        <div class="card card__mod text-bg-primary mb-3" style="max-width: 18rem">
                            <img src="{{ asset('storage/event_assets/bri-konserjudika-di-ngopibareng-pintulangit/event_logo/thumbnail/20220606162535_629dc80f69e87.jpg') }}"
                                alt="">
                        </div>
                    </a>
                </div>
                <div class="col-5">
                    <div class="card card__mod text-bg-primary mb-3" style="max-width: 18rem">
                       <div class="img-5-2-loading"></div>
                    </div>
                </div> --}}
                {{-- Template skeleton loading --}}
            </div>
            <div class="row col-nav-carousel d-none">
                <div class="col-12 d-flex text-center">
                    <button id="nav-carousel-left" class="btn btn-secondary nav-carousel me-auto">
                        <i class='bx bx-left-arrow-alt'></i>
                    </button>
                    <button id="nav-carousel-right" class="btn btn-secondary nav-carousel nav-carousel__right ms-auto">
                        <i class='bx bx-right-arrow-alt'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="recomendEvent" class="ps-5 pe-5">
        <h5 class="title-group">
            Rekomendasi Event
        </h5>
        <div id="carousel-recom-event" class="carousel-recom-event w-100 d-flex">
            {{-- ---------------------------Template card -------------------------------------- --}}
            {{-- <div class="card" style="width: 350px;">
                <img src="{{ asset('storage/event_assets/festival-jazz-ubud-202222-09-22_09-24-52_11/event_logo/thumbnail/20220428205810_626a9d72ce91e.jpg') }}"
                    class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                        card's content.</p>
                    <div class="card-price">
                        Rp. !5.000,-
                    </div>
                    <hr>
                    <div class="d-flex">
                        <img src="{{ asset('storage/organization_logo/default_logo.png') }}" alt="" width="35px"
                            height="35px" class="rounded-circle my-auto">
                        <p class="card-footer my-auto">Organisasi</p>
                    </div>
                </div>
            </div> --}}
            {{-- Template skeleton --}}
            {{-- <div class="card ms-4" style="width: 350px;">
                <div class="img-5-2-loading img-card-loading"></div>
                <div class="card-body">
                    <div class="text-2-loading mb-2"></div>
                    <div class="text-3-loading mb-2"></div>
                    <div class="text-1-loading w-50"></div>
                    <hr>
                    <div class="d-flex">
                        <div class="text-3-loading circle-icon-loading-1 my-auto me-2"></div>
                        <div class="text-1-loading w-50 my-auto"></div>
                    </div>
                </div>
            </div> --}}
            {{-- ------------------------------------------------------------------------------- --}}
        </div>
        <div class="row col-nav-carousel-2 d-none">
            <div class="col-12 d-flex text-center">
                <button id="nav-carousel-2-left" class="btn btn-secondary nav-carousel me-auto">
                    <i class='bx bx-left-arrow-alt'></i>
                </button>
                <button id="nav-carousel-2-right" class="btn btn-secondary nav-carousel nav-carousel__right ms-auto">
                    <i class='bx bx-right-arrow-alt'></i>
                </button>
            </div>
        </div>
    </div>
    <div class="ps-5 pe-5 mt-5">
        <div class="p-4 bg-white rounded-4">
            <h5 class="title-group ms-2">
                Kategori Event
            </h5>
            <div id="category-box" class="d-flex flex-wrap">
                {{-- ---------------------------------------- Template categori box ------------------------- --}}
                <div id="category-box-inner" class="row m-0 w-100">
                    {{-- <div class="col-md-2 mb-4 img-category">
                        <img class="rounded-3" src="{{ asset('images/category/Exhibition.jpg') }}" width="100%"
                            alt="">
                        <div class="title-category">
                            <div class="inner rounded-3 d-flex">
                                <p class="text-light text-center w-100 mt-auto mb-2">
                                    Exhibitions
                                </p>
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="col-md-2 mb-4 img-category">
                        <div class="img-1-1-loading rounded-4"></div>
                    </div> --}}
                </div>
                {{-- --------------------------------------------------------------------------------------- --}}
            </div>
            <div id="type-box" class="d-flex flex-wrap mt-3">
                {{-- <a href="" class="btn btn-white btn-category rounded-pill shadow-lg m-2">Anak, Keluarga</a>+
                <div class="btn-pill-100-px-loading m-2"></div> --}}
            </div>
        </div>
    </div>
    <div class="ps-5 pe-5 mt-5">
        <div class="d-flex">
            <h5 class="title-group me-2">
                Agenda di Kota
            </h5>
            <div class="d-flex position-relative">
                <h5 id="select-city" class="title-group color-origin pointer">
                    -
                </h5>
                <h5 id="btn-select-city" class="title-group color-origin pointer">
                    <i class='bx bx-chevron-up fs-2'></i>
                </h5>
                <div id="cities-box" class="p-2 bg-white float-select rounded-4 shadow-lg d-none">
                    <input type="text" name="" id=""
                        class="form-control input-filter-float rounded-3 shadow" placeholder="Cari kota" onchange="handleChange(event, changeCity)">
                    <div id="list-city" class="mt-3">
                        {{-- <p class="mb-0 border-bottom border-dark-subtle pointer">Surabaya</p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ps-5 pe-5 position-relative">
        <div id="carousel-city-event" class="carousel-recom-event w-100 d-flex">
            {{-- ---------------------------Template card -------------------------------------- --}}
            {{-- <div class="card" style="width: 350px;">
                <img src="{{ asset('storage/event_assets/festival-jazz-ubud-202222-09-22_09-24-52_11/event_logo/thumbnail/20220428205810_626a9d72ce91e.jpg') }}"
                    class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                        card's content.</p>
                    <div class="card-price">
                        Rp. !5.000,-
                    </div>
                    <hr>
                    <div class="d-flex">
                        <img src="{{ asset('storage/organization_logo/default_logo.png') }}" alt=""
                            width="35px" height="35px" class="rounded-circle my-auto">
                        <p class="card-footer my-auto">Organisasi</p>
                    </div>
                </div>
            </div> --}}
            {{-- ------------------------------------------------------------------------------- --}}
        </div>
        <div class="row col-nav-carousel-2 d-none">
            <div class="col-12 d-flex text-center">
                <button id="nav-carousel-3-left" class="btn btn-secondary nav-carousel me-auto">
                    <i class='bx bx-left-arrow-alt'></i>
                </button>
                <button id="nav-carousel-3-right" class="btn btn-secondary nav-carousel nav-carousel__right ms-auto">
                    <i class='bx bx-right-arrow-alt'></i>
                </button>
            </div>
        </div>
    </div>
    <div class="ps-5 pe-5 mt-5">
        <div class="bg-white p-4 text-center rounded-4">
            <div class="row">
                <div class="col-12 text-center title-box mb-4">
                    Menyediakan layanan dan fasilitas untuk berbagai kebutuhan event manajemen yang diselenggarakan secara
                    Virtual, Hybrid dan Offline.
                </div>
                <div class="col-12 d-flex flex-wrap justify-content-center">
                    <div class=" p-2">
                        <img width="207px" src="{{ asset('images/icon-tc.png') }}" alt="">
                        <p class="desc-box mt-2 w-img-box">Sistem Registrasi & Ticketing</p>
                    </div>
                    <div class=" p-2">
                        <img width="207px" src="{{ asset('images/icon-tv-hp.png') }}" alt="">
                        <p class="desc-box mt-2 w-img-box">Virtual Event</p>
                    </div>
                    <div class=" p-2">
                        <img width="207px" src="{{ asset('images/icon-cam-vid.png') }}" alt="">
                        <p class="desc-box mt- w-img-box2">Hybrid Event</p>
                    </div>
                    <div class=" p-2">
                        <img width="207px" src="{{ asset('images/icon-calendar.png') }}" alt="">
                        <p class="desc-box mt-2 w-img-box">Offline Event</p>
                    </div>
                    <div class=" p-2">
                        <img width="207px" src="{{ asset('images/icon-broadcast.png') }}" alt="">
                        <p class="desc-box mt-2 w-img-box">Live Broadcsating System</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ps-5 pe-5 mt-5">
        <div class="d-flex">
            <h5 class="title-group me-2">
                Cek Keseruan
            </h5>
            <div class="d-flex position-relative">
                <h5 id="select-time" class="title-group color-origin pointer">
                    Hari Ini
                </h5>
                <h5 id="btn-select-time" class="title-group color-origin pointer">
                    <i class='bx bx-chevron-up fs-2'></i>
                </h5>
                <div id="times-box" class="p-2 bg-white float-select rounded-4 shadow-lg d-none">
                    <div class="mt-3">
                        <p class="mb-0 border-bottom border-dark-subtle pointer" onclick="changeTime('Hari Ini')">Hari Ini</p>
                        <p class="mb-0 border-bottom border-dark-subtle pointer" onclick="changeTime('Besok')">Besok</p>
                        <p class="mb-0 border-bottom border-dark-subtle pointer" onclick="changeTime('Minggu Ini')">Minggu Ini</p>
                        <p class="mb-0 border-bottom border-dark-subtle pointer" onclick="changeTime('Minggu Depan')">Minggu Depan</p>
                        <p class="mb-0 border-bottom border-dark-subtle pointer" onclick="changeTime('Bulan Ini')">Bulan Ini</p>
                        <p class="mb-0 border-bottom border-dark-subtle pointer" onclick="changeTime('Bulan Depan')">Bulan Depan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ps-5 pe-5 position-relative">
        <div id="carousel-time-event" class="carousel-recom-event w-100 d-flex">
            {{-- ---------------------------Template card -------------------------------------- --}}
            {{-- <div class="card" style="width: 350px;">
                <img src="{{ asset('storage/event_assets/festival-jazz-ubud-202222-09-22_09-24-52_11/event_logo/thumbnail/20220428205810_626a9d72ce91e.jpg') }}"
                    class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                        card's content.</p>
                    <div class="card-price">
                        Rp. !5.000,-
                    </div>
                    <hr>
                    <div class="d-flex">
                        <img src="{{ asset('storage/organization_logo/default_logo.png') }}" alt=""
                            width="35px" height="35px" class="rounded-circle my-auto">
                        <p class="card-footer my-auto">Organisasi</p>
                    </div>
                </div>
            </div> --}}
            {{-- ------------------------------------------------------------------------------- --}}
        </div>
        <div class="row col-nav-carousel-2 d-none">
            <div class="col-12 d-flex text-center">
                <button id="nav-carousel-4-left" class="btn btn-secondary nav-carousel me-auto">
                    <i class='bx bx-left-arrow-alt'></i>
                </button>
                <button id="nav-carousel-4-right" class="btn btn-secondary nav-carousel nav-carousel__right ms-auto">
                    <i class='bx bx-right-arrow-alt'></i>
                </button>
            </div>
        </div>
    </div>
    <div class="ps-5 pe-5 mt-5">
        <div id="carouselBanner" class="carousel slide" data-bs-ride="carousel">
            <div id="banner-images" class="carousel-inner">
                {{-- -------------------------- Template Banner Slide --------------------------------------- --}}
                {{-- <div class="carousel-item active">
                    <a href="">
                        <img src="{{ asset('storage/event_assets/festival-jazz-ubud-202222-09-22_09-24-52_11/event_logo/thumbnail/20220428205810_626a9d72ce91e.jpg') }}"
                            class="d-block w-100" alt="...">
                    </a>
                </div>
                <div class="carousel-item">
                    <div class="img-4-1-loading"></div>
                </div> --}}
                {{-- ---------------------------------------------------------------------------------------- --}}
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselBanner" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselBanner" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <div class="ps-5 pe-5 mt-5 position-relative">
        <div class="title-group">
            Organizer Favorit
        </div>
        <div id="carousel-fav-organizer" class="carousel-recom-event w-100 d-flex">
            {{-- -------------------- Template icon -------------------------------------------- --}}
            {{-- <div class="p-3 org-fav-box">
                <div>
                    <div class="img-1-1-loading circle-icon-loading-relative"></div>
                    <div class="text-2-loading mt-2"></div>
                </div>
            </div>
            <div class="p-3 org-fav-box">
                <a href="">
                    <img width="100%" src="{{ asset('storage/organization_logo/default_logo.png') }}" alt=""
                        class="rounded-circle">
                    <p class="mt-2 text-dark">Orgamisasi Wong Apik</p>
                </a>
            </div> --}}
            {{-- ------------------------------------------------------------------------------- --}}
        </div>
        <div class="row col-nav-carousel-2 d-none" style="opacity: 50%">
            <div class="col-12 d-flex text-center">
                <button id="nav-carousel-5-left" class="btn btn-secondary nav-carousel me-auto">
                    <i class='bx bx-left-arrow-alt'></i>
                </button>
                <button id="nav-carousel-5-right" class="btn btn-secondary nav-carousel nav-carousel__right ms-auto">
                    <i class='bx bx-right-arrow-alt'></i>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="{{ asset('js/new-design/homepage/carousel.js') }}"></script>
    <script src="{{ asset('js/new-design/homepage/carousel-slider.js') }}"></script>
    <script src="{{ asset('js/new-design/view-templating.js') }}"></script>
    <script src="{{ asset('js/new-design/homepage/home-ajax-ctrl.js') }}"></script>
    <script>
        // const getRecommendation = () => {
        //     post("/api/event/recommendation")
        //     .then(res => {
        //         res.events.forEach(event => {
        //             renderEventCard(event, "#RecommendationArea");
        //         })
        //     })
        // }
        // const getByCity = (city = 'Surabaya') => {
        //     select("#byCityArea").innerHTML = "";
        //     renderEventCard(null, "#byCityArea");
        //     post("/api/event/by-city", {
        //         city: city
        //     })
        //     .then(res => {
        //         select("#byCityArea").innerHTML = "";
        //         clearInterval(cardAnimationInterval["#byCityArea"]);
        //         res.events.forEach(event => {
        //             renderEventCard(event, "#byCityArea");
        //         })
        //     })
        // }
        // const getByTimeFrame = (tf = 'this-week') => {
        //     select("#byTimeArea").innerHTML = "";
        //     renderEventCard(null, "#byTimeArea");
        //     post("/api/event/by-time", {
        //         timeframe: tf
        //     })
        //     .then(res => {
        //         select("#byTimeArea").innerHTML = "";
        //         clearInterval(cardAnimationInterval["#byTimeArea"]);
        //         res.events.forEach(event => {
        //             renderEventCard(event, "#byTimeArea");
        //         })
        //     })
        // }
        // const getFeatured = () => {
        //     post("/api/event/featured")
        //     .then(res => {
        //         select("#PinnedEvents").innerHTML = "";
        //         res.events.forEach(event => {
        //             let displayDate = event.start_date == event.end_date ? 
        //                 moment(event.start_date).format('DD MMM Y')
        //             :
        //                 moment(event.start_date).format('DD MMM') + " - " + moment(event.end_date).format("DD MMM Y");
        //             Element("div", {
        //                 class: "item flex relative"
        //             })
        //             .render("#PinnedEvents", `<img src="/storage/event_assets/${event.slug}/event_logo/thumbnail/${event.logo}" alt="">
    //             <div class="item-content flex column justify-end">
    //                 <h3>${event.name}</h3>
    //                 <div class="flex row item-center">
    //                     <div class="flex row item-center shrink-1 grow-1">
    //                         <i class="bx bx-calendar"></i>
    //                         <div class="text white small ml-1">${displayDate}</div>
    //                     </div>
    //                     <div class="flex row item-center shrink-1 grow-1 ml-2">
    //                         <i class="bx bx-map"></i>
    //                         <div class="text white small ml-1">${event.city}, ${event.province}</div>
    //                     </div>
    //                     <div class="flex row item-center shrink-1 grow-1 ml-2">
    //                         <i class="bx bx-money"></i>
    //                         <div class="text white small ml-1">${event.price == 0 || event.price == null ? 'Gratis' : Currency(event.price).encode()}</div>
    //                     </div>
    //                 </div>
    //             </div>`);
        //         })
        //     })
        // }
        // const getFavoriteOrganizers = () => {
        //     post("/api/event/favorite-organizer")
        //     .then(res => {
        //         console.log(res);
        //         // <div class="OrganizerItem flex column grow-1 item-center">
        //         //     <img src="https://pbs.twimg.com/profile_images/949145910352621569/jsBO6L_T_400x400.jpg">
        //         //     <div class="mt-1">Comedy Sunday</div>
        //         // </div>
        //         res.organizers.forEach(organizer => {
        //             Element("div", {
        //                 class: "OrganizerItem flex column grow-1 item-center"
        //             })
        //             .render("#OrganizerAwards #render", `<img src="/storage/organization_logo/${organizer.logo}">
    //             <div class="mt-2">${organizer.name}</div>`);
        //         })
        //     })
        // }
        // getFavoriteOrganizers()

        // getFeatured();
        // getRecommendation();
        // getByCity();
        // getByTimeFrame();
    </script>
@endsection

@extends('layouts.homepage')

@section('title', "Agendakota")

@section('head.dependencies')
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> --}}
<link rel="stylesheet" href="https://npmcdn.com/flickity@2/dist/flickity.css">

<style>
    .logo-homepage{
        width: 100%;
        border-top-left-radius: 6px;
        border-top-right-radius: 6px;
    }
    .city-item .overlay {
        background-color: #00000030;
        color: #fff;
        margin-top: -300px;
        padding-bottom: 20px;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: flex-end;
    }
    .city-item .overlay h4 { 
        margin-top: 0px;width: 100%;
        font-size: 22px;
        font-weight: bold;
    }
    .city-item:hover .overlay { background-color: #00000095; }
    .event-title{
        display: -webkit-box;
        margin: 0 0 10px;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.25;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .middle-content{
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }
    .card-horizontal {
        display: flex;
        flex: 1 1 auto;
    }
    .tinggi-150{
        border-radius: 8px 8px 0px 0px !important;
    }
    .page-link {
        color: #e5214f; /*#eb597b*/
    }
    /* .carousel-item{
        transition: unset;
    } */
    .carousel-cell{
        /* width: 100%; */
        /* height: 100%; */
        width: 90%;
    }
    .img-banner{
        aspect-ratio: 5/1;
    }
   
    @media (min-width: 768px){
        .col-bagi-5{
            max-width: 20%;
            flex: 0 0 20%;
        }
    }
    @media (max-width: 576px) {
        .carousel-cell{
            width: 100%;
        }
        .img-banner{
            aspect-ratio: 5/2;
        }
    }
</style>
@endsection

@section('content')
@php
    use Carbon\Carbon;
@endphp

<div class="container-cadangan">
    <div class="row" style="background-color: #FCFCFC;">
        <div class="wrap" style="margin-top: 0px; margin-bottom: 0px;">
            <div class="row">
                <div class="col-lg-6 align-self-center px-5">
                    <h2 class="mt-4 mb-4 title-home" style="color: #000;font-family: 'Inter', sans-serif !important; line-height: 1.2;">Kini tak ada lagi acara terlewatkan di kotamu</h2>
                    <p>Nonton konser idolamu hingga belajar skill baru kini bisa kamu lakukan hanya dari rumah</p>
                    <div style="height: 30px"></div>
                    <a {{ !isset($myData) ? 'href='.route('user.events') : 'onclick=addEvent()'.' href=#' }}>
                        <button class="bg-primer">Buat Event Sekarang</button>
                    </a>
                </div>
                <div class="col-lg-6 d-lg-block pr-5 top-banner" style="display: none">
                    <img id="backgorund" src="{{ asset('images/depan.png')}}" style=" float:right; width: 500px;height: 500px;"><br />
                </div>
            </div>
        </div>
    </div>

    <div class="tinggi-30"></div>
    <div class="wrap" >
        
        {{-- tab content --}}
        <div class="mt-3">
            <div id="All" class="tabcontent-event-populer row" style="border: none;">
                
                <div class="col-md-6 mb-4">
                    <div class="row">
                        @if ($eventFeatured != null)
                        <div class="col-12">
                            <h4 class="teks-tebal" style="color: #000;font-family: 'Inter', sans-serif;font-weight: 900;">Featured Event</h4>
                        </div>
                        <div class="col-12">
                            <div class="bg-putih rounded bayangan-5 mt-3">
                                <a href="{{route('user.eventDetail',$eventFeatured->slug)}}" style="text-decoration: none;">
                                    
                                    <div style="height: 310px; border-radius: 8px 8px 0px 0px;" bg-image="{{ asset('storage/event_assets/'.$eventFeatured->slug.'/event_logo/thumbnail/'.$eventFeatured->logo) }}"></div>
                                    <h3 class="mt-2" style="padding-left: 5%; padding-right:5%; font-family: 'Inter', sans-serif !important; font-style: normal; color: #000000; font-size: 18px; font-weight: 500;">{{$eventFeatured->name}} - {{ $eventFeatured->city }}</h3>
                                    <div class="mb-2" style="padding-left: 5%; padding-right:5%; font-family: 'Inter', sans-serif;color:#979797; font-size: 12px;">Diadakan oleh <span style="font-weight: bold">{{$eventFeatured->organizer->name}}</span></div>
                                </a>
                                    <div class="mb-1 pb-3" style="padding-left: 5%; padding-right:5%; font-size: 12px; font-family: 'Inter', sans-serif !important; font-style: normal; font-weight: normal; color: #000000;">
                                        <i class="fa fa-calendar"></i> &nbsp;
                                        {{ Carbon::parse($eventFeatured->start_date)->format('d M,') }} {{ Carbon::parse($eventFeatured->start_time)->format('H:i') }} WIB -
                                        {{ Carbon::parse($eventFeatured->end_date)->format('d M,') }} {{ Carbon::parse($eventFeatured->end_time)->format('H:i') }} WIB
                                    </div>
                                    <div id="desc-top" class="mb-1 pb-3" style="padding-left: 5%; padding-right:5%; font-size: 12px; font-family: 'Inter', sans-serif !important; font-style: normal; font-weight: normal; color: #000000;">
                                        <span style="font-size: 15px"></span>
                                    </div>
                                    <div class="mb-1 pb-3" style="padding-left: 5%; padding-right:5%; font-size: 12px; font-family: 'Inter', sans-serif !important; font-style: normal; font-weight: normal; color: #000000;">
                                        <div class="row">
                                            @php
                                                $sessions = $eventFeatured->sessions;
                                                $prices = [];
                                                for($i=0; $i<count($sessions); $i++){
                                                    $tickets = $sessions[$i]->tickets->where('deleted', 0);
                                                    foreach($tickets as $ticket){
                                                        array_push($prices, $ticket->price);
                                                    }
                                                }
                                                sort($prices);
                                            @endphp
                                            @if (count($prices) == 0)
                                                <div class="col-12">
                                                    <span style="font-size: 15px">Ticket Belum Tersedia</span>
                                                </div>
                                            @else
                                                <div class="col-md-6">
                                                    @if($prices[0] == 0)
                                                        <span style="font-size: 15px">
                                                            Harga Mulai dari Gratis
                                                        </span>
                                                    @else
                                                        <span style="font-size: 15px">
                                                            Harga Mulai @currencyEncode($prices[0])
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="{{route('user.eventDetail',$eventFeatured->slug)}}" style="text-decoration: none;" class="btn btn-primer">Beli Tiket</a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        @if (count($events)>0)
                        <div class="col-12">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="teks-tebal" style="color: #000;font-family: 'Inter', sans-serif;font-weight: 900;">Event Populer</h4>
                                </div>
                                <div class="col-sm-4">
                                    <a href="{{route('explore')}}">
                                        <button class="btn btn-default float-right btn-no-pd" style="border: 1px solid lightgray; border-radius:8px; color:#e5214f; /*#EB597B*/">Lihat Semua</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                @foreach ($events as $event)
                                
                                    <div class="col-12">
                                        <div class="mb-0 mt-3">
                                            <div class="card bg-putih rounded bayangan-5">
                                                <div class="card-horizontal">
                                                    <div class="ml-3 mt-3 mb-3 mr-0">
                                                        <div class="img-square-wrapper">
                                                            <img width="110px" height="110px" class="rounded-8" src="{{ asset('storage/event_assets/'.$event->slug.'/event_logo/thumbnail/'.$event->logo) }}" alt="Card image cap" style="object-fit: cover">
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row middle-content">
                                                            @php
                                                                $sessions = $event->sessions;
                                                                $prices = [];
                                                                for($i=0; $i<count($sessions); $i++){
                                                                    $tickets = $sessions[$i]->tickets->where('deleted', 0);
                                                                    foreach($tickets as $ticket){
                                                                        array_push($prices, $ticket->price);
                                                                    }
                                                                }
                                                                sort($prices);
                                                            @endphp
                                                            <div class="col-lg-8 middle-content">
                                                                <a href="{{route('user.eventDetail',$event->slug)}}" style="text-decoration: none;">
                                                                    <p class="event-title text-dark">{{ $event->name }}</p>
                                                                </a>
                                                                <p class="event-title fs-12">
                                                                    <img src="{{ $event->organizer->logo == ''? asset('images/profile-user.png'): asset('storage/organization_logo/' . $event->organizer->logo) }}"
                                                                    style="width: 18px; height: 18px"> &nbsp;{{ $event->organizer->name }}
                                                                </p>
                                                                <p class="event-title fs-12">
                                                                    <i class="fa fa-calendar fs-12"></i> &nbsp;
                                                                    {{ Carbon::parse($event->start_date)->format('d M,') }} {{ Carbon::parse($event->start_time)->format('H:i') }} WIB -
                                                                    {{ Carbon::parse($event->end_date)->format('d M,') }} {{ Carbon::parse($event->end_time)->format('H:i') }} WIB
                                                                </p>
                                                                <p class="event-title fs-12">
                                                                    <i class="fa fa-tag fs-12"></i> &nbsp; 
                                                                    @if (count($prices) > 0)
                                                                        @if($prices[0] == 0)
                                                                            Harga mulai dari Gratis
                                                                        @else
                                                                            Harga mulai dari 
                                                                            @currencyEncode($prices[0])
                                                                        @endif
                                                                    @else
                                                                        Belum Ada
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="col-lg-4 middle-content">
                                                                <div class="middle-content">
                                                                    @if (count($prices) > 0)
                                                                        <a href="{{route('user.eventDetail',$event->slug)}}" style="text-decoration: none;" class="btn btn-primer">Beli</a>
                                                                    @else
                                                                    <a href="#" style="text-decoration: none;" class="btn btn-primer disabled">Beli</a> 
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                
                            @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
            </div>
            {{-- Crousel banner --}}
            @if (count($banners) > 0)
                
                
                <div id="carousel-banner" class="mt-5 mb-5 main-carousel" data-ride="carousel">
                   {{-- Menggunakan plugin flickity untuk carouselnya --}}
                   @foreach ($banners as $item)
                   @php
                       $path = 'storage/banner_image/'.$item->image;
                   @endphp
                        <div class="carousel-cell pointer" onclick="return window.open('{{ $item->url }}');" >
                        
                                <img width="100%" class="img-banner" src="{{ asset($path) }}">
                            
                        </div>
                    @endforeach
                </div>

            @endif
        </div>
        {{-- end tab content --}}

        <div class="row mt-4">
            <div class="col-md">
                <h4 class="teks-tebal" style="color: #000;font-family: 'Inter', sans-serif;font-weight: 900;">Event di Kotamu</h4>
            </div>
            <div class="col-md">
                <a href="{{route('homepage.city')}}">
                    <button class="btn btn-default float-right btn-no-pd" style="border: 1px solid lightgray; border-radius:8px; color:#e5214f; /*#EB597B*/">Lihat Semua</button>
                </a>
            </div>
        </div>
        <div class="row">
            @foreach ($cities as $city)
                <div class="col-md-3 col-bagi-5 city-item mt-3">
                    <a href="{{ route('explore', ['city' => $city->name]) }}">
                        <div>
                            <div class="tinggi-300 rounded-1-0" bg-image="{{ asset('storage/city_image/'.$city->image) }}" object-fit="cover"></div>
                            <div class="tinggi-300 rounded-1-0 overlay rata-tengah">
                                <h4 class="mb-0">{{ $city->name }}</h4>
                                <div class="lebar-100">{{ $city->events }} event</div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-5" style="">
            <div class="row">
                <div class="col-md mb-3">
                    <h4 class="teks-tebal d-inline" style="color: #000;font-family: 'Inter', sans-serif;font-weight: 900;">Event Minggu Ini</h4>
                    {{-- <button id="load-more-week-events" class="btn btn-default bg-primer ml-3 btn-no-pd d-inline" style="border: 1px solid lightgray; border-radius:8px; color:#fff;">Muat Lagi</button> --}}
                </div>
                <div class="col-md">
                    <a href="{{ route('explore', ['start_date='.$dateNow, 'end_date='.$nextWeek]) }}">
                        <button class="btn btn-default float-right btn-no-pd" style="border: 1px solid lightgray; border-radius:8px; color:#e5214f; /*#EB597B*/">Lihat Semua</button>
                    </a>
                </div>
            </div>
            
            {{-- tab --}}
            <div class="tab" style="border: none; border-bottom: 1px solid #F0F1F2;">
                <button class="tablinks-minggu-ini active" onclick="opentabs(event, 'minggu-ini', 'All-minggu-ini')">All</button>
                <button class="tablinks-minggu-ini" onclick="opentabs(event, 'minggu-ini', 'Free-minggu-ini')">Free</button>
                <button class="tablinks-minggu-ini" onclick="opentabs(event, 'minggu-ini', 'Paid-minggu-ini')">Paid</button>
            </div>
            {{-- end tab --}}

            {{-- tab content --}}
            <div class="mt-3">
                <div id="All-minggu-ini" class="tabcontent-minggu-ini row" style=" border: none;">
                    
                </div>
                <div id="Free-minggu-ini" class="tabcontent-minggu-ini row" style="display: none; border: none;">
                    
                </div>
                <div id="Paid-minggu-ini" class="tabcontent-minggu-ini row" style="display: none; border: none;">
                    
                </div>
            </div>
            {{-- end tab content --}}
        </div>

        <div class="mt-5">
            <div class="row">
                <div class="col-md mb-3">
                    <h4 class="teks-tebal d-inline" style="color: #000;font-family: 'Inter', sans-serif; font-weight: 900; ">Event sesuai kategori</h4>
                    {{-- <button id="load-more-events-category" class="btn btn-default bg-primer ml-3 btn-no-pd d-inline" style="border: 1px solid lightgray; border-radius:8px; color:#fff;">Muat Lagi</button> --}}
                </div>
                <div class="col-md">
                    <a href="{{route('explore')}}">
                        <button class="btn btn-default float-right btn-no-pd" style="border: 1px solid lightgray; border-radius:8px; color:#e5214f; /*#EB597B*/">Lihat Semua</button>
                    </a>
                </div>
            </div>
            
                {{-- tab category --}}
            <div class="tab scrollmenu" style="border: none; border-bottom: 1px solid #F0F1F2;">
                
                @foreach ($categorys as $item)
                    <button class="tab-btn tablinks-category {{ $loop->index == 0 ? 'active' : '' }} "  onclick="opentabs(event, 'category', '{{ $item['name'] }}')">{{ $item['name'] }}</button>
                @endforeach
            </div>
            <div class="mt-3">
                @foreach ($categorys as $item)
                    <div id="{{ $item['name'] }}" class="tabcontent-category row" style="{{ $loop->index == 0 ? '' : 'display: none;' }} border: none;">
                        
                    </div>
                @endforeach
                
            </div>
            {{-- end tab --}}
        </div>

        <div class="mt-5">
            <div class="row">
                <div class="col-md">
                    <h4 class="teks-tebal" style="color: #000;font-family: 'Inter', sans-serif; font-weight: 900; ">Kategori Terpopuler</h4>
                </div>
                <div class="col-md">
                    <a href="{{route('user.homepage.allcategory')}}">
                        <button class="btn btn-default float-right btn-no-pd" style="border: 1px solid lightgray; border-radius:8px; color:#e5214f; /*#EB597B*/">Lihat Semua</button>
                    </a>
                </div>
            </div>
            
            <hr>
            <div class="mt-2">
                <div class="row">
                    @foreach ($categorys as $category)
                        <div class="col-md-3 col-bagi-5" style="margin-bottom:3%;">
                            <div>
                                <a href="{{ route('explore', ['category='.$category['name']]) }}" style="text-decoration: none;">
                                    <div class="bg-putih rounded bayangan-5" style="">
                                        <div style="margin-top:2%;">
                                            <div style="background-image: url(&quot;{{asset('images/category/'.$category['photo'])}}&quot;); background-position: center center; background-size: cover; height:125px; border-radius: 8px 8px 0px 0px;"></div>
                                        </div>
                                        <div class="rata-tengah" style="color: #304156; padding:10px; font-size:15px;">
                                            {{$category['name']}}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach  
                </div>
            </div>                     
        </div>

    </div>
</div>
<div class="mt-5 pt-5" style="margin-bottom: 3%; background-image: url({{ asset('images/homepagebawah.png') }})">
    {{-- <img src="{{ asset('images/homepagebawah.png') }}" class="logobawah" style="width:100%;"> --}}
    <h2 style="color: #ffff; margin-left:5%;">Mulai Buat Eventmu</h2>
    <p style="color: #ffff; font-size: 20px; margin-left:5%;">AgendaKota is your virtual venue for delivering all types of online events</p>
    <a href="/events" class="btn btn-default-lg" style=" margin-left:5%;background-color:#ffff; border-radius:8px; color:#e5214f; /*#EB597B*/ margin-bottom: 3%; font-size:20px; margin-top:3%;">Buat Event</a>
</div>
@endsection

@section('javascript')
<script src="{{ asset('js/base.js') }}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> --}}

<script>
    // ------------- Menampilkan deskripsi dari event pada evennt populer 
    // (Mengubah output element <p></p> ber-style font size 13)-------------
    var objTarget = document.querySelector('#desc-top p');
    var textObj = objTarget.innerHTML;
    objTarget.remove()
    document.querySelector('#desc-top span').innerHTML = textObj;
    // ---------------------------------------------------------------------
</script>
<script src="{{ asset('js/homePage.js') }}"></script>
<script src="https://npmcdn.com/flickity@2/dist/flickity.pkgd.js"></script>
<script>
    setDataEventByCategory(<?php echo(json_encode($byCategory)) ?>);
    setDataEventThisWeek(<?php echo(json_encode($event_minggu_ini)) ?>);

    // Setup carousel
    var tagetCarousel = document.querySelector('.main-carousel');
    var carousel = new Flickity(tagetCarousel, {
        cellAlign : 'center',
        contain : false,
        wrapAround : true,
        autoPlay : true,
    });
</script>
<script>
    
    $('#carousel-example').on('slide.bs.carousel', function (e) {
        var $e = $(e.relatedTarget);
        var idx = $e.index();
        var itemsPerSlide = 2;
        var totalItems = $('.carousel-item').length;
    
        if (idx >= totalItems-(itemsPerSlide-1)) {
            var it = itemsPerSlide - (totalItems - idx);
            for (var i=0; i<it; i++) {
                // append slides to end
                if (e.direction=="left") {
                    $('.carousel-item').eq(i).appendTo('.carousel-inner');
                }
                else {
                    $('.carousel-item').eq(0).appendTo('.carousel-inner');
                }
            }
        }
    });    
</script>
@endsection



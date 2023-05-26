@extends('layouts.homepage')

@section('title', "Home Page")

@section('content')
@php
    use Carbon\Carbon;
@endphp

@section('head.dependencies')
<link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
<style>
    .garis-tiket{
        border-top: 2px solid;
    }
    div{
        font-size: 14px;
    }
</style>
@endsection

<div>
    <div style="background-color: #E6286E; ">
        <div class="wrap" style="margin-top: -3%; padding:10px;">
            <div class="col align-self-center px-5">
                <h2 class="mt-5 mb-4 title-home" style=" text-align: center; color: #ffff">
                    @php
                        $categoryIn = false;
                        for($i=0; $i<count($categorys); $i++){
                            if($category == $categorys[$i]->name){
                                $categoryIn = true;
                            }
                        }
                    @endphp
                    @if ($categoryIn == true)
                        {{$category}}
                    @elseif($category == "event-populer")
                        Event Populer
                    @elseif($category == "event-minggu-ini")
                        Event Minggu Ini
                        @php
                            $tanggalAwal = Carbon::now()->toDateString();
                            $tanggalAkhir = Carbon::now()->subDays(-7)->toDateString();
                        @endphp
                    @else
                        Search for "{{$category}}"
                    @endif
                </h2>
                <p class="mt-2" style="text-align:center; color: #ffff" id="target-total-event">
                </p>
            </div>
        </div>
    </div>
    <div class="bagi bagi-2 lebar-20">
        <div class="bg-putih rounded bayangan-5" style="margin-top:-10%; margin-bottom:10%; margin-left:10%;">
            <div style="padding:20px;">
                <form action="{{route('user.explore', [$category, 'pencarian'])}}" method="GET">
                    <div class="form-group">
                        <label for="Date" style="color: #304156;">Date :</label>
                        <div class="input-group mt-1">
                            <span class="input-group-text" id="website"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            <input type="text" name="startDate" id="startDate" onchange="chooseStartDate(this.value)"
                            @if (!isset($tanggalAwal))
                                value = ""
                            @else
                                value = "{{Carbon::parse($tanggalAwal)->format('d M Y')}}"
                            @endif
                            class="form-control" placeholder="Start Date">
                        </div>
                        <div class="input-group mt-1">
                            <span class="input-group-text" id="website"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            <input type="text" name="endDate" id="endDate"
                            @if (!isset($tanggalAkhir))
                                value = ""
                            @else
                                value = "{{Carbon::parse($tanggalAkhir)->format('d M Y')}}"
                            @endif
                            class="form-control" placeholder="End Date" readonly>
                        </div>
                    </div>
                    <div style="">
                        <div class="bagi bagi-2" style="width: 50%;">
                            <a href="{{route('user.explore',[$category, 'bulan-ini'])}}" >
                                <button type="button" class="bg-primer lebar-100 ke-kiri" style="border-radius: 6px; font-size: 15px; padding: 1px;">
                                    Bulan Ini
                                </button>
                            </a>
                        </div>
                        <div class="bagi bagi-2" style="width: 50%">
                            <a href="{{route('user.explore',[$category, 'bulan-depan'])}}" >
                                <button type="button" class="bg-primer lebar-100 ke-kanan" style="border-radius: 6px; font-size: 15px; padding: 1px;">
                                    Bulan Depan
                                </button>
                            </a>
                        </div>
                    </div>
                    <hr style="margin-top: 10%;">
                    <div style="color: #304156;">
                        <label for="">Price :</label><br>
                        <div>
                            <input type="radio" id="semua" name="price" value="semua"
                            @if ($price == "semua")
                                checked
                            @endif
                            >
                            <label for="semua">Semua</label><br>

                            <input type="radio" id="gratis" name="price" value="gratis"
                            @if ($price == "gratis")
                                checked
                            @endif>
                            <label for="gratis">Gratis</label><br>

                            <input type="radio" id="berbayar" name="price" value="berbayar"
                            @if ($price == "berbayar")
                                checked
                            @endif>
                            <label for="berbayar">Berbayar</label><br>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="bg-primer lebar-50" style="border-radius: 6px; margin-left:50%;">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="bagi lebar-80">
        <h4 class="teks-tebal" style="margin-left:3%; padding-bottom:30px; color: #000;font-family: 'Inter', sans-serif;font-weight: 900;">Event Terpopuler</h4>
        @foreach ($events as $event)
            <div class="bagi bagi-3" id="target-event-{{$event->id}}"></div>
        @endforeach
        <div class="d-flex justify-content-center mt-5">
            {{$events->links("pagination::bootstrap-4")}}
        </div>
    </div>
</div>



@endsection

@section('javascript')

<script src="{{ asset('js/base.js') }}"></script>
<script src="{{ asset('js/flatpickr/dist/flatpickr.min.js') }}"></script>

<script>
    flatpickr("#startDate", {
        dateFormat: 'Y-m-d',
        minDate: "{{ date('Y-m-d') }}"
    });

    const chooseStartDate = date => {
        flatpickr("#endDate", {
            dateFormat: 'Y-m-d',
            minDate: date,
        });
    }

    @foreach ( $events as $event )
        var arr = [];
        var total = "";
        var form = "";
        @foreach ($event->sessions as $session )
            @foreach ($session->tickets as $ticket )
                arr.push("{{$ticket->price}}");
            @endforeach
        @endforeach

        var hargaTerendah = arr[0];
        var hargaTertinggi = arr[0];
        for(var i =0; i < arr.length; i++){
            if(arr[i] < hargaTerendah){
                hargaTerendah = arr[i];
            }
            if(arr[i] >= hargaTertinggi){
                hargaTertinggi = arr[i];
            }
        }

        var rupiahHargaTerendah = currencyRupiah(hargaTerendah);
        var rupiahHargaTertinggi = currencyRupiah(hargaTertinggi);

        if(hargaTertinggi == 0){
            total = "Free";
        }else if(hargaTerendah == hargaTertinggi){
            total = "Rp. "+rupiahHargaTerendah;
        }
        else{
            total = "Rp. "+rupiahHargaTerendah+" - Rp. "+rupiahHargaTertinggi;
        }

        form = '<div class="wrap">'+
            '<div class="bg-putih rounded bayangan-5">'+
                '<a href="{{route('user.eventDetail', $event->slug)}}" style="color: black; text-decoration: none;">'+
                '<div class="cover tinggi-150" bg-image="{{ asset('storage/event_assets/'.$event->slug.'/event_logo/thumbnail/'.$event->logo) }}" style="background-image: url(&quot;{{ asset('storage/event_assets/'.$event->slug.'/event_logo/thumbnail/'.$event->logo) }}&quot;); background-position: center center; background-size: cover;"></div>'+
                '<div class="smallPadding">'+
                    '<div class="wrap">'+
                        '<h4>{{ $event->name }}</h4>'+
                        '</a>'+
                        '<div class="mb-2" style="color:#979797;">Diadakan oleh <span style="font-weight: bold">{{$event->organizer->name}}</span></div>'+
                        '<div style="color: #304156;">'+
                            '<i class="fa fa-calendar"></i> &nbsp;'+
                            '{{ Carbon::parse($event->start_date)->format('d M,') }} {{ Carbon::parse($event->start_time)->format('H:i') }} WIB - '+
                            '{{ Carbon::parse($event->end_date)->format('d M,') }} {{ Carbon::parse($event->end_time)->format('H:i') }} WIB'+
                        '</div>'+
                        '<div style="color: #304156; padding-bottom:10px;">'+
                            '<i class="fa fa-tag"></i> &nbsp;'+
                            total+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>';

        var eventID = "{{$event->id}}";
        var price = "{{$price}}";
        // console.log(hargaTertinggi);
        switch(price){
            case "semua" :
                document.getElementById('target-event-'+eventID).innerHTML = form;
                break;
            case "gratis":
                if(hargaTertinggi > 0){
                    document.getElementById('target-event-'+eventID).remove();
                }else{
                    document.getElementById('target-event-'+eventID).innerHTML = form;
                }
                break;
            case "berbayar":
                if(hargaTertinggi == 0){
                    document.getElementById('target-event-'+eventID).remove();
                }else{
                    document.getElementById('target-event-'+eventID).innerHTML = form;
                }
                break;
        }
    @endforeach
    getTotalEvent();
    function getTotalEvent() {
        var arrEvent = [];
        var arrSession = [];

        @foreach ($events as $event)
            var event = document.getElementById('target-event-'+"{{$event->id}}");
            if(event != null){
                arrEvent.push(event);
                @foreach ($event->sessions as $session)
                    arrSession.push("{{$session->id}}");
                @endforeach
            }
        @endforeach

        var total = arrEvent.length+" Events - "+arrSession.length+" Sessions" ;
        document.getElementById('target-total-event').innerHTML = total;
    }
</script>
@endsection

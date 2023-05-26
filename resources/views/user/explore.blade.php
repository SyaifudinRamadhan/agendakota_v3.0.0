@extends('layouts.homepage')

@section('title', "Home Page")

@section('content')
@php
    use Carbon\Carbon;
@endphp

<div class="container-cadangan">
    <div style="background-color: #E6286E;">
        <div class="wrap" style="margin-top: -3%; padding:10px;">
            <div class="col align-self-center px-5">
                <h2 class="mt-5 mb-4 title-home" style=" text-align: center; color: #ffff">Tecnology</h2>
                <p class="mt-2" style="text-align:center; color: #ffff">
                    0 events - 0 sessions
                </p>
            </div>
        </div>
    </div>
    <div class="bagi lebar-30">
        <div class="bg-putih rounded bayangan-5" style="margin-top:-10%; margin-bottom:10%; margin-left:10%;">
            <div style="padding:20px;">
                <div class="form-group">
                    <label for="Date" style="color: #304156;">Date :</label>
                    <div class="input-group mt-1">
                        <span class="input-group-text" id="website"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                        <input type="text" name="date" id="date" class="form-control" value="">
                    </div>
                </div>
                <div class="bagi bagi-2" style="width: 50%">
                    <button type="submit" class="bg-primer lebar-100" style="border-radius: 6px;">Bulan Ini</button>
                </div>
                <div class="bagi bagi-2" style="width: 50%">
                    <button type="submit" class="bg-primer  lebar-100" style="border-radius: 6px;">Bulan Depan</button>
                </div>
                <hr style="margin-top: 10%;">
                <div>
                    Price :
                    <div class="form-group">
                        <input type="radio" name="price_type" id="price_all">
                        <label for="price_all">Semua</label>
                        <input type="radio" name="price_type" id="price_free">
                        <label for="price_free">Gratis</label>
                        <input type="radio" name="price_type" id="price_paid">
                        <label for="price_paid">Berbayar</label>
                    </div>
                </div>
                <hr>
                <div>
                    <label for="" style="color: #304156;">Tampilkan :</label><br>
                    <input type="radio" id="semua" name="semua" value="semua">
                    <label for="semua" style="color: #304156;">Semua</label><br>

                    <input type="radio" id="gratis" name="gratis" value="gratis">
                    <label for="gratis" style="color: #304156;">Event</label><br>

                    <input type="radio" id="berbayar" name="berbayar" value="berbayar">
                    <label for="berbayar" style="color: #304156;">Pembicara / Guest</label><br>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="bagi lebar-60">
        <h4 class="teks-tebal" style="margin-left:3%; padding-bottom:30px; color: #000;font-family: 'Inter', sans-serif;font-weight: 900;">Event Terpopuler</h4>
        @foreach ($events as $event)
        <div class="bagi bagi-2">
            <div class="wrap">
                <div class="bg-putih rounded bayangan-5">
                    <div class="cover tinggi-200" bg-image="{{ asset('storage/event_assets/'.$event->slug.'/'.$event->logo) }}"></div>
                    <div class="smallPadding">
                        <div class="wrap super">
                            <h4>{{ $event->name }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div> --}}
    <div class="bagi lebar-60">
        <h4 class="teks-tebal" style="margin-left:3%; padding-bottom:30px; color: #000;font-family: 'Inter', sans-serif;font-weight: 900;">Event Terpopuler</h4>
        <div id="content-data"></div>
    </div>
</div>



@endsection

@section('javascript')
<script src="{{ asset('js/base.js') }}"></script>
<script>
</script>
@endsection

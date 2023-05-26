@extends('layouts.homepage')

@section('title', "City")

@section('head.dependencies')
<style>
    body { background-color: #ecf0f1;color: #333; }
    .overlay {
        background-color: #00000030;
        color: #fff;
        margin-top: -300px;
        padding-bottom: 20px;
        border-radius: 15px;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        align-items: flex-end;
        cursor: pointer;
    }
    .item:hover .overlay {
        background-color: #00000095;
    }
</style>
@endsection

@section('content')
<div class="smallPadding" style="margin-top: -30px;">
    <div class="wrap rata-tengah">
        <h3>All Cities in Indonesia</h3>
        <div class="tinggi-30"></div>
        @foreach ($cities as $city)
            <div class="bagi bagi-5 item">
                <div class="wrap">
                    <a href="{{ route('explore', ['city' => $city->name]) }}">
                        <div class="bg-putih rounded-2-0 bordered rata-kiri">
                            <div object-fit="cover" class="cover tinggi-300 rounded-2-0" bg-image="{{ asset('storage/city_image/'.$city->image) }}"></div>
                            <div class="overlay tinggi-300">
                                <h4 class="lebar-100 rata-tengah">{{ $city->name }}</h4>
                                <div>{{ $city->events }} events</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection



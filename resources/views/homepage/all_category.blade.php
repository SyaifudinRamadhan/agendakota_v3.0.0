@extends('layouts.homepage')

@section('title', "All Category")

@section('content')
@php
    use Carbon\Carbon;
@endphp

@section('head.dependencies')

@endsection
    <div class="wrap">
        @foreach ($categorys as $category)
            <div class="bagi bagi-5 " style="margin-bottom:3%;">
                <div class="wrap">
                    <a href="{{ route('explore', ['category='.$category['name']]) }}" style="text-decoration: none;">
                        <div class="bg-putih rounded bayangan-5" style="">
                            <div style="margin-top:2%;">
                                <div style="background-image: url(&quot;{{asset('images/category/'.$category['photo'])}}&quot;); background-position: center center; background-size: cover; height:125px;"></div>
                            </div>
                            <div class="rata-tengah" style="color: #304156; padding:10px; font-size:20px;">
                                {{$category['name']}}
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endsection

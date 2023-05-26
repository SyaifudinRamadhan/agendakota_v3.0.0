@extends('layouts.admin')

@section('title', $user->name)

@php
use Carbon\Carbon;
@endphp

@section('head.dependencies')
<style>
    .card a {
        text-decoration: none;
    }

    .img-fluid {
        width: 200px;
        height: 200px;
        border-radius: 200px;
        border-style: solid;
        border-width: 5px;
        border-color: #eb597b9c;
    }

    .gradient-top {
        background-image: linear-gradient(to top, #eb597b, #f194aa8a, white);
        padding: 100px;
    }

    .gradient-top .card {
        padding: 50px;
        background-color: rgba(240, 248, 255, 0.5);
    }

</style>
@stop

@section('content')

<h2>{{ $user->name }}</h2>
<a href="{{ route('admin.user') }}" class="btn bg-primer float-right d-inline font-inter"
    style="text-decoration: none;"><i class="fa fa-arrow-left"></i> Kembali</a>

<div class="col-md dashboard-item mt-5 mb-3" data-aos="fade-down">
    <div class="row" data-aos="fade-up">
        <div class="col-md-3  text-center justify-content-center mx-auto">
            @if ($user->photo == 'default')
                <img src="{{ asset('images/profile-user.png') }}" class="mx-auto text-center img-fluid shadow-sm">
            @else
                <img src="{{ asset('storage/profile_photo/' . $user->photo) }}" class="mx-auto text-center img-fluid shadow-sm">
            @endif
        </div>
        <div class="col-md-9 font-inter text-inter">
            <div class="">
                <h1 class="btn btn-lg bg-primer text-white mt-2">{{ $user->name }}</h3>
            </div>

            <a class="btn btn-sm bg-dark text-white mr-1"><i class="fa fa-envelope"></i>
                {{ $user->email }}</a>
            <a class="btn btn-sm bg-secondary text-white mr-1 ml-1"><i class="fa fa-phone-alt"></i>
                {{ $user->phone }}</a>
            <p class="border-primer text-dark mt-3">{{ $user->bio }}</p>
        </div>
    </div>
</div>

<div class="gradient-top">
    <div class="card">
        <div class="row mt-3">
            <div class="form-group col-md-4">
                <h5><i class="fab fa-linkedin mr-2 text-primary"></i> Linkedin</h5>
                <p class="text-secondary">{{ $user->linkedin_profile }}</p>
            </div>
            <div class="form-group col-md-4">
                <h5><i class="fab fa-instagram mr-2 text-danger"></i> Instagram</h5>
                <p class="text-secondary">{{ $user->instagram_profile }}</p>
            </div>
            <div class="form-group col-md-4">
                <h5><i class="fa fa-twitter-square mr-2 text-primary"></i> Twitter</h5>
                <p class="text-secondary">{{ $user->twitter_profile }}</p>
            </div>
        </div>
    </div>
</div>



{{-- <a class="d-flex justify-content-center mt-3" href="/"><img src="{{ asset('images/logo.png') }}" class="lebar-50"></a> --}}

<div class="mt-5 pb-3"
    style="width: 100%; height: 15px; border-bottom: 1px solid black; text-align: center;bottom:0 auto;position: relative;">
    <a class="text-secondary pt-2 bg-white pr-3 pl-3">AgendaKota.id</a>
</div>


@endsection

@extends('layouts.admin')

@section('title', $organization->name)

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

<h2>{{ $organization->name }}</h2>
<p class="teks-transparan mb-4 d-inline">{{ $organization->description }}</p>
<a href="{{ route('admin.organizer') }}" class="btn bg-primer float-right d-inline font-inter"
    style="text-decoration: none;"><i class="fa fa-arrow-left"></i> Kembali</a>

<div class="col-md dashboard-item mt-5 mb-3" data-aos="fade-down">
    <div class="row" data-aos="fade-up">
        <div class="col-md-3  text-center justify-content-center mx-auto">
            @if ($organization->logo == '')
                <img src="{{ asset('storage/organization_logo/default_logo.png') }}" class="mx-auto text-center img-fluid shadow-sm" alt="">
            @else
                <img src="{{ asset('storage/organization_logo/' . $organization->logo) }}"
                    class="mx-auto text-center img-fluid shadow-sm" alt="">
            @endif            
        </div>
        <div class="col-md-9 font-inter text-inter">
            <div class="">
                <h1 class="btn btn-lg bg-primer text-white mt-2">{{ $organization->name }}</h3>
            </div>
            
            <a class="btn btn-sm bg-dark text-white mr-1"><i class="fa fa-info-circle"></i>
                {{ $organization->type }}</a>
            <a class="btn btn-sm bg-secondary text-white mr-1 ml-1"><i class="fa fa-tags"></i>
                {{ $organization->interest }}</a>
            <p class="border-primer text-dark mt-3">{{ $organization->description }}</p>
        </div>
    </div>
</div>

<div class="gradient-top">
    <div class="card">
        <div class="row mt-3">
            <div class="form-group col-md-4">                
                <h5><i class="fa fa-envelope mr-2"></i> Email</h5>
                <p class="text-secondary">{{ $organization->email }}</p>
            </div>
            <div class="form-group col-md-4">
                <h5><i class="fa fa-phone-square mr-2 text-success"></i> Telp</h5>
                <p class="text-secondary">{{ $organization->no_telepon }}</p>
            </div>
            <div class="form-group col-md-4">
                <h5><i class="fa fa-building mr-2"></i> Created</h5>
                <p class="text-secondary">{{ Carbon::parse($organization->created_at)->format('d F Y') }}</p>
            </div>
        </div>

        <div class="row mt-3">
            <div class="form-group col-md-3">
                <h5><i class="fab fa-linkedin mr-2 text-primary"></i> Linkedin</h5>
                <p class="text-secondary">{{ $organization->linked }}</p>
            </div>
            <div class="form-group col-md-3">
                <h5><i class="fab fa-instagram mr-2 text-danger"></i> Instagram</h5>
                <p class="text-secondary">{{ $organization->instagram }}</p>
            </div>
            <div class="form-group col-md-3">
                <h5><i class="fa fa-twitter-square mr-2 text-primary"></i> Twitter</h5>
                <p class="text-secondary">{{ $organization->twitter }}</p>
            </div>
            <div class="form-group col-md-3">
                <h5><i class="fa fa-globe mr-2 text-dark"></i> Website</h5>
                <p class="text-secondary">{{ $organization->website }}</p>
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

@extends('layouts.stream')

@section('title', 'Stream')

@section('head.style')
    <style>
        .thumb-sponsor {
            width: 100px;
            height: 100px;
            border-radius: 100px;
        }

    </style>
@endsection

@php
use Carbon\Carbon;
$tanggalSekarang = Carbon::now()->toDateString();
@endphp

{{-- @section('profile-menu')
  
@endsection --}}

@section('content')
    @include('admin.partials.alert')

    <div class="col-md mb-5 my-5 pt-5 mt-5 text-center">        
        Konten Zoom
    </div>




@endsection

@section('content2')
    @include('stream.chat')
@endsection


@extends('layouts.admin')

@section('title', 'Event Organizer')

@section('head.dependencies')

@section('content')
<h2>Event Organizer</h2>
<p class="teks-transparan mb-4 d-inline">Ringkasan data Event Organizer AgendaKota.id</p>

<div class="col-md dashboard-item mt-5 mb-3">
    {{-- <div class="wrap"> --}}
        <table class="table table-bordered" id="organizer-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th> 
                    <th>Email</th>                   
                    <th>Action</th>                    
                </tr>
            </thead>
        </table>
    {{-- </div> --}}
</div>


{{-- <a class="d-flex justify-content-center mt-3" href="/"><img src="{{ asset('images/logo.png') }}" class="lebar-50"></a> --}}

<div class="mt-5 pb-3"
    style="width: 100%; height: 15px; border-bottom: 1px solid black; text-align: center;bottom:0 auto;position: relative;">
    <a class="text-secondary pt-2 bg-white pr-3 pl-3">AgendaKota.id</a>
</div>


@endsection




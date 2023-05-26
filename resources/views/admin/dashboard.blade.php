@extends('layouts.admin')

@section('title', 'Dashboard')

@section('head.dependencies')
<style>
    .picture {
        display: inline-block;
        width: 100px;
        height: 100px;
        border-radius: 100px;
        margin-top: -50px;
    }

    .dashboard-item {
        margin-top: 70px;
        text-decoration: none !important;
    }

    .dashboard-item a:link {
        text-decoration: none !important;
    }

    .dashboard-item h3 {
        font-size: 18px;
    }

    .dashboard-item .from {
        margin-top: 20px;
    }

    .dashboard-item .from p {
        line-height: 25px;
        font-size: 13px;
        margin: 0px;
        text-decoration: none !important;
    }

    .dashboard-item .from h4 {
        font-size: 15px;
        margin: 0px;
        margin-top: 2px;
        font-family: DM_SansBold !important;
    }

</style>
@endsection

@section('content')
<h2>Dashboard</h2>
<p class="teks-transparan mb-4">Ringkasan statistik Agendakota.id saat ini</p>



<div class="bagi bagi-3 dashboard-item rata-tengah mt-5 mb-3">
    <div class="wrap">
        <div class="bayangan-5 rounded">
            <div class="col-md bg-primer h-25"></div>
            <div class="wrap row col-md">
                <h1 class="pt-3 ml-3"><i class="fa fa-user teks-primer"></i></h1>
                <div class="wrap rata-kiri mb-0 text-dark">
                    <p class="mb-0">Total User</p>
                    <h3 class="mb-0 teks-transparan">{{ count($users) }}</h3>
                </div>
            </div>
            <div class="rata-kiri from smallPadding bg-primer">
                <div class="wrap">User terbaru</p>
                    @if (count($users) != 0)
                        <h4>{{ $users->last()->name }}</h4>
                    @else
                        <h4>Tidak ada data</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bagi bagi-3 dashboard-item rata-tengah mt-5 mb-3">
    <div class="wrap">

        <div class="bayangan-5 rounded">
            <div class="col-md bg-primer h-25"></div>
            <div class="wrap row col-md">
                <h1 class="pt-3 ml-3"><i class="fa fa-building teks-primer"></i></h1>
                <div class="wrap rata-kiri mb-0 teks-dark">
                    <p class="mb-0">Total Event Organizer</p>
                    <h3 class="mb-0 teks-transparan">{{ count($organization) }}</h3>
                </div>
            </div>
            <div class="rata-kiri from smallPadding bg-primer">
                <div class="wrap">Event Organizer terbaru</p>
                    @if (count($organization) != 0)
                        <h4>{{ $organization->last()->name }}</h4>
                    @else
                        <h4>Tidak ada data</h4>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<div class="bagi bagi-3 dashboard-item rata-tengah mt-5 mb-3">
    <div class="wrap">

        <div class="bayangan-5 rounded">
            <div class="col-md bg-primer h-25"></div>
            <div class="wrap row col-md">
                <h1 class="pt-3 ml-3"><i class="fa fa-tag teks-primer"></i></h1>
                <div class="wrap rata-kiri mb-0 text-dark">
                    <p class="mb-0">Total Event</p>
                    <h3 class="mb-0 teks-transparan">{{ count($events) }}</h3>
                </div>
            </div>
            <div class="rata-kiri from smallPadding bg-primer">
                <div class="wrap">Event terbaru</p>
                    @if (count($events) != 0)
                        <h4>{{ $events->last()->name }}</h4>
                    @else
                        <h4>Tidak ada data</h4>
                    @endif
                </div>
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

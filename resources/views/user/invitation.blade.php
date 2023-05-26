@extends('layouts.user')

@section('title', "Invitations")

@section('content')
@php
    use Carbon\Carbon;
@endphp

<link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
<style type="text/css">
    .d-inline-block{
        display: inline-block;
    }
    .d-content{
        display: contents;
    }
    .d-block{
        display: block;
    }
    .d-none{
        display: none;
    }
    .font-img{
        font-size: 165px;
        opacity: 50%;
    }
    .square {
        float: right;
        text-align: center;
        width: 50px;
        height: 50px;
        margin-top: 30px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;
        margin-right: 10px;
    }
    .square i {
        line-height: 50px;
        color: #ccc;
    }
    .square.square.active {
        border-color: var(--primaryColor);
    }
    .square.active i {
        color: var(--primaryColor);
    }
    .table-mode {
        height: calc(100% - 141px);
    }
    .table-block {
        border: none;
        overflow-x: auto;
        height: 100%;
    }
    .p-table{
        vertical-align: middle;
        display: table-cell;
    }
</style>

@include('admin.partials.alert')
    
    <div class="">
        <div class="bagi bagi-2 mb-3">
            <h2>Invitations</h2>
            <p class="teks-transparan mb-3">Kamu diundang untuk menghadiri event ini</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-4">
            <div class="square toggle-view-button" mode="table" onclick="toggleView(this)">
                <i class="fa fa-bars"></i>
            </div>
            <div class="square active toggle-view-button" mode="card" onclick="toggleView(this)">
                <i class="fa fa-th-large"></i>
            </div>
        </div>
    </div>

    <div id="card-mode" class="d-block">
        @if(count($invitations) == 0)
            <div class="mt-4 rata-tengah">
                <i class="bi bi-inbox teks-primer font-img"></i>
                <h3>Bergabung Event dengan Teman - temanmu</h3>
                <p>Temukan undangan event dari teman - temanmu</p>
                
            </div>
        @else
            @foreach ($invitations as $invitation)
                <div class="bagi bagi-3">
                    <div class="wrap">
                        <div class="event-item grid">
                            <div class="cover" bg-image="{{ asset('storage/event_assets/'.$invitation->events->slug.'/event_logo/thumbnail/'.$invitation->events->logo)}}"></div>
                            <div class="detail smallPadding">
                                <div class="wrap">
                                    @if (isset($invitation->tickets))
                                        @if ($invitation->tickets->price == 0)
                                            <div class="label bg-hijau rounded">
                                                <i class="fas fa-tags"></i> FREE
                                            </div>
                                        @else
                                            <div class="label bg-hijau rounded">
                                                <i class="fas fa-tags"></i> @currencyEncode($invitation->tickets->price)
                                            </div>

                                        @endif

                                    @endif

                                    {{-- <div class="label bg-hijau rounded">
                                        <i class="fas fa-tags"></i> FREE
                                    </div> --}}

                                    <h4>{{$invitation->events->name}}</h4>

                                    <div class="teks-transparan organizer">oleh <b>{{$invitation->events->organizer->name}}</b></div>
                                    <div class="date">
                                        <i class="fas fa-calendar"></i> &nbsp;
                                        {{ Carbon::parse($invitation->events->start_date)->format('d M,') }} {{ Carbon::parse($invitation->events->start_time)->format('H:i') }} WIB -
                                        {{ Carbon::parse($invitation->events->end_date)->format('d M,') }} {{ Carbon::parse($invitation->events->end_time)->format('H:i') }} WIB
                                    </div>

                                    <hr size="1" color="#ddd" class="mt-3 mb-2" />

                                    <div class="invitation">
                                        @if (isset($invitation->ticket_id))
                                            <div class="teks-kecil teks-transparan">Kamu telah diundang dan dibelikan tiket oleh :</div>
                                        @else
                                            <div class="teks-kecil teks-transparan">Kamu diundang oleh :</div>
                                        @endif
                                        
                                        @if ($invitation->senders->photo == "default")
                                            <div class="picture d-inline-block" bg-image="{{ asset('images/profile-user.png') }}"></div>
                                        @else
                                            <div class="picture d-inline-block" bg-image="{{ asset('storage/profile_photos/'.$invitation->senders->photo) }}"></div>
                                        @endif
                                        <div class="lebar-60 info">
                                            <b>{{$invitation->senders->name}}</b>

                                        </div>
                                        @if ($invitation->response == "Ignore")
                                            <div class="wrap">
                                                <span class="teks-kecil">
                                                    Event Ditolak
                                                </span>
                                                <a href="{{route('user.invitationsDelete',$invitation->id)}}">
                                                    <button class="mt-2 teks-primer">Hapus Invitation</button>
                                                </a>
                                            </div>
                                        @elseif($invitation->response == "Accept")
                                            @if (isset($invitation->ticket_id))
                                                <div class="wrap">
                                                    <span class="teks-kecil">
                                                        Event Diterima dan Tiket Telah Ditambahkan Di My Ticket
                                                    </span>
                                                    <a href="{{route('user.invitationsDelete',$invitation->id)}}">
                                                        <button class="mt-2 teks-primer">Hapus Invitation</button>
                                                    </a>
                                                </div>

                                            @else
                                                <div class="wrap">
                                                    <span class="teks-kecil">
                                                        Event Diterima Silahkan Klik <a href="{{route('user.eventDetail',$invitation->events->slug)}}">Disini</a> Untuk Membeli Tiket
                                                    </span>
                                                    <a href="{{route('user.invitationsDelete',$invitation->id)}}">
                                                        <button class="mt-2 teks-primer">Hapus Invitation</button>
                                                    </a>
                                                </div>
                                            @endif
                                        @else
                                            <div class="bagi bagi-2">
                                                <div class="wrap">
                                                    <a href="{{route('user.invitationsIgnore',$invitation->id)}}">
                                                        <button class="teks-merah">Ignore</button>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="bagi bagi-2">
                                                <div class="wrap">
                                                    <a href="{{route('user.invitationsAccept',$invitation->id)}}">
                                                        <button class="teks-hijau">Accept</button>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div id="table-mode" class="d-none table-responsive">
        @if(count($invitations) == 0)
            <div class="mt-4 rata-tengah">
                <i class="bi bi-inbox teks-primer font-img"></i>
                <h3>Bergabung Event dengan Teman - temanmu</h3>
                <p>Temukan undangan event dari teman - temanmu</p>
            </div>
        @else
            <table class="table table-borderless">
                <thead>
                <tr>
                    <th scope="col">Event Name&emsp;&emsp;&emsp;&emsp;</th>
                    <th scope="col">Time&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                    <th scope="col">Inviter&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                    <th scope="col">Action&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                </tr>
                </thead>
                <tbody class="mt-2">
                    @foreach ($invitations as $invitation)
                        <tr>
                            <td>
                                <p class="fontBold font-weight-bold p-table">{{$invitation->senders->name}}</p>
                            </td>
                            <td>
                                <p class="p-table">
                                    {{ Carbon::parse($invitation->events->start_date)->format('d M,') }} {{ Carbon::parse($invitation->events->start_time)->format('H:i') }} WIB -
                                    {{ Carbon::parse($invitation->events->end_date)->format('d M,') }} {{ Carbon::parse($invitation->events->end_time)->format('H:i') }} WIB
                                </p>
                            </td>
                            <td>
                                <p class="teks-primer p-table">{{$invitation->senders->name}}</p>
                            </td>
                            <td>
                                        @if ($invitation->response == "Ignore")
                                            <div class="wrap">
                                                
                                                <a href="{{route('user.invitationsDelete',$invitation->id)}}">
                                                    <button class="mt-2 p-table teks-primer">Hapus Invitation</button>
                                                </a>
                                            </div>
                                        @elseif($invitation->response == "Accept")
                                            @if (isset($invitation->ticket_id))
                                                <div class="wrap">
                                                    <a href="{{route('user.invitationsDelete',$invitation->id)}}">
                                                        <button class="mt-2 p-table teks-primer">Hapus Invitation</button>
                                                    </a>
                                                </div>

                                            @else
                                                <div class="wrap">
                                                    <span class="teks-kecil p-table">
                                                        <a href="{{route('user.eventDetail',$invitation->events->slug)}}">Disini</a> Untuk Membeli Tiket
                                                    </span>
                                                    <a href="{{route('user.invitationsDelete',$invitation->id)}}">
                                                        <button class="mt-2 p-table teks-primer">Hapus Invitation</button>
                                                    </a>
                                                </div>
                                            @endif
                                        @else
                                            <div class="bagi bagi-2">
                                                <div class="wrap">
                                                    <a href="{{route('user.invitationsIgnore',$invitation->id)}}">
                                                        <button class="teks-merah p-table">Ignore</button>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="bagi bagi-2">
                                                <div class="wrap">
                                                    <a href="{{route('user.invitationsAccept',$invitation->id)}}">
                                                        <button class="teks-hijau p-table">Accept</button>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection

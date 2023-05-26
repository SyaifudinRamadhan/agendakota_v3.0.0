@extends('layouts.user')

@section('title', "My Tickets")

@php
    use Carbon\Carbon;
@endphp

@section('head.dependencies')
<link rel="stylesheet" href="{{ asset('riyan/css/base/column.css') }}">
<link rel="stylesheet" href="{{ asset('riyan/css/base/font.css') }}">
<link rel="stylesheet" href="{{ asset('riyan/css/base/color.css') }}">
<style>
    .tab-item.active {
        border-bottom: 3px solid var(--primary) !important;
        color: var(--primary);
        font-weight: 700;
    }
    .HalfCircle {
        width: 50px;
        height: 25px;
        background-color: #fff;
        border-top-left-radius: 35px;
        border-top-right-radius: 35px; /* 100px of height + 10px of border */
        border: 1px solid #ddd;
        border-bottom: 0;
    }
    .TicketDisplay {
        border: 1px solid #ddd;
        height: auto;
        position: relative;
        padding: 20px 45px;
        border-radius: 10px;
        margin: 15px;
        flex-basis: 40%;
    }
    .TicketDisplay.active {
        border: 1px solid var(--primary);
    }
    .TicketDisplay.active .HalfCircle { border-color: var(--primary); }
    .TicketDisplay .HalfCircle {
        position: absolute;
        top: 70px;
    }
    .TicketDisplay .LeftCircle {
        left: -14px;
        transform: rotate(90deg);
    }
    .TicketDisplay .RightCircle {
        right: -14px;
        transform: rotate(270deg);
    }
    .TicketDisplay .info {
        border-bottom: 1px dashed #ddd;
        padding-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="w-100">
    <h2>My Tickets</h2>
    <span class="teks-transparan">Temukan Semua Tiket Event</span>
</div>

<div class="flex row item-center w-50 ml-1">
    <div class="tab-item pl-3 border-bottom flex grow-1 h-40 mt-3 pointer active" target="all">All</div>
    <div class="tab-item pl-3 border-bottom flex grow-1 h-40 mt-3 pointer" target="free">Free</div>
    <div class="tab-item pl-3 border-bottom flex grow-1 h-40 mt-3 pointer" target="paid">Paid</div>
</div>

<div class="h-20"></div>
<div class="tab-content m-0 active" key="all">
    <div class="flex row item-center wrap">
        @foreach ($purchases as $purchase)
            @php
                $ticket = $purchase->ticket;
                $session = $ticket->session;
                $displayDate = "";
                if ($session->start_date == $session->end_date) {
                    $displayDate = Carbon::parse($session->start_date)->isoFormat('DD MMM Y');
                } else {
                    $displayDate = Carbon::parse($session->start_date)->isoFormat('DD MMM') ." - ".
                    Carbon::parse($session->end_date)->isoFormat('DD MMM Y');
                }
            @endphp
            <a href="{{ route('user.myTickets.detail', $purchase->id) }}" class="TicketDisplay text black flex basis-3 column " id="ticket_{{ $ticket->id }}">
                <div class="HalfCircle LeftCircle"> </div>
                <div class="HalfCircle RightCircle"> </div>
                <div class="info">
                    <h4 class="m-0">{{ $ticket->name }}</h4>
                    <div class="text muted small mt-1">{{ $purchase->event->name }}</div>
                </div>
                <div class="flex row item-center mt-2">
                    <div class="flex column grow-1">
                        <div class="price text small bold primary">@currencyEncode($ticket->price)</div>
                        <div class="text small muted mt-05">
                            {{ $displayDate }}
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
<div class="tab-content m-0" key="free">
    <div class="flex row item-center wrap">
        @foreach ($purchases as $purchase)
            @php
                $ticket = $purchase->ticket;
                $session = $ticket->session;
                $displayDate = "";
                if ($session->start_date == $session->end_date) {
                    $displayDate = Carbon::parse($session->start_date)->isoFormat('DD MMM Y');
                } else {
                    $displayDate = Carbon::parse($session->start_date)->isoFormat('DD MMM') ." - ".
                    Carbon::parse($session->end_date)->isoFormat('DD MMM Y');
                }
            @endphp
            @if ($ticket->price == 0)
                <a href="{{ route('user.myTickets.detail', $purchase->id) }}" class="TicketDisplay text black flex basis-3 column " id="ticket_{{ $ticket->id }}">
                    <div class="HalfCircle LeftCircle"> </div>
                    <div class="HalfCircle RightCircle"> </div>
                    <div class="info">
                        <h4 class="m-0">{{ $ticket->name }}</h4>
                        <div class="text muted small mt-1">{{ $purchase->event->name }}</div>
                    </div>
                    <div class="flex row item-center mt-2">
                        <div class="flex column grow-1">
                            <div class="price text small bold primary">@currencyEncode($ticket->price)</div>
                            <div class="text small muted mt-05">
                                {{ $displayDate }}
                            </div>
                        </div>
                    </div>
                </a>
            @endif
        @endforeach
    </div>
</div>
<div class="tab-content m-0" key="paid">
    <div class="flex row item-center wrap">
        @foreach ($purchases as $purchase)
            @php
                $ticket = $purchase->ticket;
                $session = $ticket->session;
                $displayDate = "";
                if ($session->start_date == $session->end_date) {
                    $displayDate = Carbon::parse($session->start_date)->isoFormat('DD MMM Y');
                } else {
                    $displayDate = Carbon::parse($session->start_date)->isoFormat('DD MMM') ." - ".
                    Carbon::parse($session->end_date)->isoFormat('DD MMM Y');
                }
            @endphp
            @if ($ticket->price > 0)
                <a href="{{ route('user.myTickets.detail', $purchase->id) }}" class="TicketDisplay text black flex basis-3 column " id="ticket_{{ $ticket->id }}">
                    <div class="HalfCircle LeftCircle"> </div>
                    <div class="HalfCircle RightCircle"> </div>
                    <div class="info">
                        <h4 class="m-0">{{ $ticket->name }}</h4>
                        <div class="text muted small mt-1">{{ $purchase->event->name }}</div>
                    </div>
                    <div class="flex row item-center mt-2">
                        <div class="flex column grow-1">
                            <div class="price text small bold primary">@currencyEncode($ticket->price)</div>
                            <div class="text small muted mt-05">
                                {{ $displayDate }}
                            </div>
                        </div>
                    </div>
                </a>
            @endif
        @endforeach
    </div>
</div>

@endsection
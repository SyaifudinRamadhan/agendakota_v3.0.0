@extends('layouts.user')

@section('title', "My Tickets")

@php
    use Carbon\Carbon;
@endphp

@section('head.dependencies')
<link rel="stylesheet" href="{{ asset('riyan/boxicons/css/boxicons.min.css') }}">
<link rel="stylesheet" href="{{ asset('riyan/css/base/column.css') }}">
<link rel="stylesheet" href="{{ asset('riyan/css/base/font.css') }}">
<link rel="stylesheet" href="{{ asset('riyan/css/base/color.css') }}">
<style>
    .flex {
        margin: 0px;
    }
</style>
@endsection

@section('content')
<div class="flex row item-center">
    <a href="{{ route('user.myTickets') }}" class="text primary">
        <i class="bx bx-left-arrow-alt text bigger mr-2"></i>
    </a>
    <h3 class="m-0">Detail Tiket</h3>
</div>

<div class="bordered rounded-more p-4 mt-4 flex row wrap">
    <div class="flex column grow-1 basis-3 text center item-center">
        <img 
            src="data:image/png;base64,{{BarCode2::getBarcodePNG('contoh', 'QRCODE', 8, 8)}}"
            class="w-50 squarize use-height"
        />
        <button class="bg-none mt-4 text small p-1 pl-4 pr-4 rounded h-40">
            <i class="bx bx-download mr-2"></i>
            Download E-Ticket
        </button>
    </div>
    <div class="flex column grow-1 basis-3 ml-4 mr-4">
        <div class="text muted small">Nama Event</div>
        <div class="text">{{ $event->name }}</div>

        <div class="text muted small mt-4">Tanggal</div>
        <div class="text">
            {{ Carbon::parse($purchase->ticket->session->start_date)->isoFormat('DD MMM') }}
            -
            {{ Carbon::parse($purchase->ticket->session->end_date)->isoFormat('DD MMM Y') }},
            {{ Carbon::parse($purchase->ticket->session->start_time)->format('H:i') }}
        </div>

        <div class="text muted small mt-4">Hosted by</div>
        <div class="text">{{ $event->organizer->name }}</div>
    </div>
    <div class="flex column grow-1 basis-3">
        <div class="text muted small">Nama Tiket</div>
        <div class="text">{{ $purchase->ticket->name }}</div>

        <div class="text muted small mt-4">Harga Tiket</div>
        <div class="text">@currencyEncode($purchase->price)</div>

        <div class="text muted small mt-4">Status Pembayaran</div>
        <div class="flex row">
            <div class="PaymentBadge text small p-2 pl-3 pr-3 mt-1 rounded transparent {{ $purchase->payment->status == 1 ? 'bg-green' : 'bg-red' }}">
                {{ $purchase->payment->status == 1 ? 'Paid' : 'Unpaid' }}
            </div>
        </div>
    </div>

    <div class="w-100 border-top pt-4 mt-4 text muted">
        {{ $purchase->ticket->description }}
    </div>
</div>

<div class="bordered rounded-more p-4 mt-4 flex row item-center">
    <div class="flex column grow-1 basis-3">
        <div class="text small muted">Nama Pembeli</div>
        <div>{{ $purchase->buyer->name }}</div>

        @if ($purchase->send_from != $purchase->user_id)
            <div class="text small muted mt-3">Ticket Holder</div>
            <div>{{ $purchase->holder->name }}</div>
        @endif
    </div>
    <div class="flex column grow-1 basis-3">
        <div class="text small muted">Email</div>
        <div>{{ $purchase->buyer->email }}</div>

        @if ($purchase->send_from != $purchase->user_id)
            <div class="mt-3">{{ $purchase->holder->email }}</div>
        @endif
    </div>
    <div class="flex column grow-1 basis-3">
        <div class="text small muted">No. Telepon</div>
        <div>{{ $purchase->buyer->phone }}</div>

        @if ($purchase->send_from != $purchase->user_id)
            <div class="mt-3">{{ $purchase->holder->phone }}</div>
        @endif
    </div>
</div>
@endsection
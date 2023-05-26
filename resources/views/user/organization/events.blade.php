@extends('layouts.user')

@section('title', "Event ".$organization->name)

@php
    use Carbon\Carbon;
@endphp

@section('head.dependencies')
<style>
    table {
        border: 1px solid #ddd;
        border-radius: 6px;
    }
</style>
@endsection

@section('content')
    <h2>Events
        <a href="{{ route('organization.event.create', [$organizationID]) }}">
            <button class="ke-kanan pointer btn bg-primer font-inter">
                <i class="fas fa-plus"></i> Buat Event Baru
            </button>
        </a>
    </h2>

    @include('admin.partials.alert')
    @if ($organization->events->count() == 0)
        <h3>Tidak ada event</h3>
    @else
        @foreach ($events as $event)
            <div class="bagi bagi-3">
                <div class="wrap">
                    <div class="bg-putih rounded bayangan-5">
                        <div class="logo" bg-image="{{ asset('storage/event_logo/'.$event->logo) }}"></div>
                        <h3 Style="padding:5%;">
                            <a href="{{ route('organization.event.sponsors', [$organization->id, $event->id]) }}" class="teks-primer" style="text-decoration: none;">
                                {{$event->name}}
                            </a>
                        </h3>
                        <div Style="padding:5%;">
                            {{ Carbon::parse($event->start_date)->format('d M, Y') }} {{ Carbon::parse($event->start_time)->format('H:i') }} -

                            {{ Carbon::parse($event->end_date)->format('d M, Y') }} {{ Carbon::parse($event->end_time)->format('H:i') }}
                            @if ($event->is_publish == 1)
                            <p>Status: Publish </p>
                            @else
                            <p>Status :Belum Publish</p>
                            <p>Silahkan Buat Tiket Untuk Publish Event</p>
                            @endif
                        </div>

                        <div class="bagi bagi-2 mt-2">
                            <div class="wrap">
                                <button class="lebar-100 hijau">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        <div class="bagi bagi-2 mt-2">
                            <div class="wrap">
                                <form action="{{route('organization.event.delete' , ([$organizationID, $event->id]))}}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button class="lebar-100 merah"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach
    @endif
@endsection

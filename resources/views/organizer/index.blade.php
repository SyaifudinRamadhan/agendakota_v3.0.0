@extends('layouts.user')

@section('title', "AgendaKota")

@section('head.dependencies')
<style>
    .status {
        padding: 6px;
        border-radius: 90px;
        display: inline-block;
        vertical-align: top;
        margin-top: 4px;
        margin-right: 5px;
    }
</style>
@endsection

@section('content')
    <h2>Events</h2>
    
    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="cover rounded tinggi-65 bagi lebar-25" bg-image="{{ asset('images/turing.jpg') }}"></div>
                    <div class="mt-2 bagi ml-2">
                        <b>Touring Pakai Boeing</b>
                    </div>
                </td>
                <td>
                    31 Des - 25 Jan
                </td>
                <td>
                    <div class="status bg-hijau"></div> Active
                </td>
                <td>
                    <a href="{{ route('organizer.event') }}">
                        <button class="primer"><i class="fas fa-eye"></i></button>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
@endsection
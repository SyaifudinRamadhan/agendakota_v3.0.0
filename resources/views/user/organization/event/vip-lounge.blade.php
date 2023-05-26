@extends('layouts.user')

@section('title', "VIP Lounge")

@section('head.dependencies')
   <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
@endsection

@section('content')
@php
    use Carbon\Carbon;
@endphp

<div class="bagi bagi-2" style="margin-top:-1%;">
    <h2 style=" color: #304156; font-size:32px;">VIP Lounge</h2>
    <h4 style="color: #979797; font-size:14; margin-top:-2%;">Manage VIP sessions and participants</h4>
</div>

@include('admin.partials.alert')

<table class="table table-borderless">
    <thead>
        <tr>
            <th>Session Name</th>
            <th>Timeline</th>   
            <th>Jumlah Meja</th>
            <th>Kursi per Meja</th>
            <th>Action</th>
        </tr>
    </thead>
                   
    <tbody class="mt-2">
        @foreach($sessions as $session)
            <tr>
                <td>{{ $session->title }}</td>
                <td>{{ Carbon::parse($session->start_date)->format('d M Y') }} - {{ Carbon::parse($session->start_end)->format('d M Y') }}</td>

                <form action="{{ route('organization.event.vipLounge.save',[$organizationID, $event->id]) }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="sessionID" value="{{ $session->id }}">
                    <td>
                        <input type="number" class="box no-bg" name="tableCount" value="{{count($session->vipLounge) == 0 ? 0 : $session->vipLounge[0]->table_count}}">
                    </td>
                    <td>
                        <input type="number" class="box no-bg" name="chairTable" value="{{count($session->vipLounge) == 0 ? 0 : $session->vipLounge[0]->chair_table}}">
                    </td>
                    <td>
                        <button type="submit" class="btn bg-primer btn-no-pd">
                            Simpan
                        </button>
                    </td>
                </form>

            </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('javascript')
<script>
</script>
@endsection

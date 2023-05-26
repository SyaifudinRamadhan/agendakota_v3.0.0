@extends('layouts.user')

@section('title', "Lounge")

@section('head.dependencies')
    <style>
    </style>
@endsection

@section('content')
@php
    use Carbon\Carbon;
@endphp

<h2 style=" color: #304156; font-size:32px; margin-top:-1%;">Lounge</h2>
<h4 class="mt-2 mb-2" style="color: #979797; font-size:14;">Manage Lounge where participants can network and start a conversation</h4>

@include('admin.partials.alert')

<form action="{{ route('organization.event.lounge.store', [$organizationID, $eventID]) }}" method="post">
    {{csrf_field()}}
    <div class="row">
        <div class="col-md-6" style="margin-top:5%;">
            <div class="mt-2" style="color: #304156"><b>Jumlah Meja :</b></div>
            <input type="text" class="box no-bg" name="tableCount" value="{{count($lounge) == 0 ? 0 : $lounge[0]->table_count}}">
        </div>
        <div class="col-md-6" style="margin-top:5%;">
            <div class="mt-2" style="color: #304156"><b>Kursi per-Meja :</b></div>
            <input type="text" class="box no-bg" name="tableChair" value="{{count($lounge) == 0 ? 0 : $lounge[0]->chair_table}}">
        </div>
    </div>

    <button type="submit" class="primer mt-5" style="border-radius:12px;">Simpan</button>
</form>

@endsection

@section('javascript')
<script>
</script>
@endsection

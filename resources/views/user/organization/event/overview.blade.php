@extends('layouts.user')

@section('title', "Event Overview")

@section('content')
<h1>Event Overview</h1>
<form action="#" method="POST" enctype="multipart/form-data" class="mt-4">
    {{ csrf_field() }}
    <div class="mt-2">Nama event :</div>
    <input type="text" class="box" value="{{ $event->name }}">
    <div class="mt-2">Deskripsi event :</div>
    <textarea name="description" class="box">{{ $event->description }}</textarea>
</form>
@endsection
@extends('layouts.auth')

@section('title', "forgotPassword")

@section('content')
    <form action="{{ route('user.forgotPassword') }}" method="POST" class="mt-3 mb-5">
        @if ($errors->count() > 0)
            @foreach ($errors->all() as $err)
                <div class="bg-merah-transparan rounded p-2">
                    {{ $err }}
                </div>
            @endforeach
        @elseif ( (session('berhasil')))
            <div class="bg-hijau-transparan rounded p-2">
                {{session('berhasil')}}
            </div>
        @endif
        {{ csrf_field() }}
        <input type="hidden" name="redirectTo" value="{{ $redirectTo }}">
        <div class="mt-2">Masukkan Email Anda :</div>
        <input type="email" class="box" name="email" required>

        <button class="mt-3 bg-primer">Kirim kode melalui email</button>
    </form>
@endsection

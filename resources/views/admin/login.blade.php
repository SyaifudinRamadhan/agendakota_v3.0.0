@extends('layouts.auth')

@section('title', 'Login')

<nav class="nav justify-content-right">
    <a class="nav-link active" href="/"><img src="{{ asset('images/logo.png') }}" class="lebar-25"></a>
</nav>

<style>
    
</style>

@section('content')
<div class="form-login mx-auto">
    <div class="text-center justify-content-center mx-auto mb-5">
        <a class="nav-link active" href="/"><img src="{{ asset('images/logo.png') }}" class="lebar-50"></a>
        <h1 class="text-dark">Selamat Datang</h1>        
        <p>Silahkan Masuk</p>
    </div>
    <div class="mt-5">        
        <div style="width: 100%; height: 20px; border-bottom: 1px solid black; text-align: center">
            <a class="text-secondary pt-2 bg-white pr-3 pl-3">AgendaKota.id</a>
        </div>
        <form action="{{ route('admin.login') }}" method="POST" class="mt-3 mb-5">
            @if ($errors->count() > 0)
                @foreach ($errors->all() as $err)
                    <div class="bg-merah-transparan rounded p-2">
                        {{ $err }}
                    </div>
                @endforeach
            @elseif ( (session('berhasil')))
                <div class="bg-hijau-transparan rounded p-2">
                    {{ session('berhasil') }}
                </div>
            @endif
            {{ csrf_field() }}
            <input type="hidden" name="redirectTo" value="{{ $redirectTo }}">            
            <input type="email" class="box" name="email" placeholder="Email" required>            
            <input type="password" class="box" name="password" placeholder="Password" required>
            {{-- <a href="{{ route('user.forgotPasswordPage') }}" class="teks-primer float-right mt-2">Forget password?</a> --}}
            <div class="row mt-5">
                <button class="mt-3 col-md bg-primer">Masuk</button>
            </div>
        </form>
    </div>
</div>
@endsection

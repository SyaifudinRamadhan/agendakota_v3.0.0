@extends('layouts.auth')

@section('head.dependencies')
    <style>
        #illustration {
            position: fixed;
            top: 0px;
            left: 0px;
            bottom: 0px;
        }

        .g_id_signin iframe {
            margin: auto !important;
            margin-top: 20px !important;
        }
    </style>
@endsection

@section('title', 'Register')
<div id="illustration">
    <span class="text-register">Daftar untuk melihat berbagai event menarik</span>
    <img src="{{ url('/images/registerkanan.png') }}" class="img-register">
</div>
<div class="container-register">
    @section('content')
        <div class="text-center justify-content-center mx-auto mb-5">
            <h1 class="text-dark">Sign up to get started!</h1>
            <p class="mt-4 mb-0">Already registered? <a href="{{ route('user.loginPage') }}">Login</a> </p>
        </div>
        <form action="{{ route('user.register') }}" method="POST" class="mt-3 mb-5">
            @if ($errors->count() > 0)
                @foreach ($errors->all() as $err)
                    <div class="bg-merah-transparan rounded p-2">
                        {{ $err }}
                    </div>
                @endforeach
            @endif
            {{ csrf_field() }}
            <input type="text" class="box" name="name" placeholder="Nama" required value="{{ old('name') }}">
            {{-- -------Untuk melakukan register dengan pembelian package--------- --}}
            @if (isset($packageID))
                <input type="hidden" name="pkgID" value="{{ $packageID }}">
            @else
                <input type="hidden" name="pkgID" value="1">
            @endif
            {{-- ------------------------------------------------------------------ --}}
            @if (isset($organizationID))
                <input type="hidden" class="box" name="organizationID" value="{{ $organizationID }}">
            @endif
            @if (isset($ticketID))
                <div class="mt-2">Email Address</div>
                <input type="email" class="box" name="email" required value="{{ $email }}" readonly>
                <input type="hidden" class="box" name="ticketID" required value="{{ $ticketID }}">
                <input type="hidden" class="box" name="event" required value="{{ $event_name }}">
                <input type="hidden" class="box" name="senderID" required value="{{ $senderID }}">
                <input type="hidden" name="purchaseID" required="" value="{{ $purchase }}">
            @else
                <input type="email" class="box" name="email" placeholder="Email" required
                    value="{{ old('email') }}">
            @endif

            @if (isset($ticketBuyed))
                @foreach ($ticketBuyed as $ticket)
                    <input type="hidden" name="ticket[]" value="{{ $ticket }}">
                @endforeach
            @endif
            <input type="password" class="box" name="password" placeholder="Password" required>
            <input type="password" class="box" name="repeat_password" placeholder="Repeat password" required>

            <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" name="" id="" value="checkedValue" required
                    checked>
                Saya setuju terhadap Privacy Policy dan Persyaratan umum AgendaKota
            </div>

            <div class="mt-3 w-100">
                <div style="width: 304px" class="mx-auto">
                    {!! NoCaptcha::display() !!}
                    {!! NoCaptcha::renderJs() !!}
                </div>
            </div>

            <div id="errorFromApi" class="bg-merah transparan p-3 rounded mt-2 d-none">
                asdasd
            </div>
            <div class="mt-3 mb-3">
                <button class="mt-3 col-md bg-primer">Daftar</button>
            </div>
            <div style="width: 100%; height: 15px; border-bottom: 1px solid black; text-align: center">
                <a class="text-secondary pt-2 bg-white pr-3 pl-3">atau</a>
            </div>

            <div class="w-100 d-flex social-button">
                <a href="{{ route('user.loginGooglePage', ['provider' => 'google']) }}"
                    class="btn btn-outline-danger mx-auto mt-4 p-2 rounded-4 w-100">
                    <img src="/riyan/images/icon-google.png" alt="Google">
                    Google
                </a>
            </div>
            <div class="h-40">&nbsp;</div>
        </form>
    </div>

@endsection

@section('javascript')

@endsection

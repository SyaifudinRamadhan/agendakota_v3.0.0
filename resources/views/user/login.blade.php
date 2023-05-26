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
            <h1 class="text-dark">Masuk</h1>

            @if (isset($ticketBuyed))
                <p>Not registered? <a
                        href="{{ route('user.registerPage', [http_build_query(['myArray' => $ticketBuyed])]) }}">Create
                        an Account</a> </p>
            @else
                <p>Not registered? <a href="{{ route('user.registerPage') }}">Create an Account</a> </p>
            @endif
        </div>
        <div class="mt-5">
            <div style="width: 100%; height: 20px; border-bottom: 1px solid black; text-align: center">
                <a class="text-secondary pt-2 bg-white pr-3 pl-3">AgendaKota.id</a>
            </div>
            <form action="{{ route('user.login') }}" method="POST" class="mt-3 mb-5">
                @include('admin.partials.alert')

                {{ csrf_field() }}
                @if (isset($ticketBuyed))
                    @foreach ($ticketBuyed as $ticket)
                        <input type="hidden" name="ticket[]" value="{{ $ticket }}">
                    @endforeach
                @endif

                @if ($navTo !== null)
                    <input type="hidden" name="navTo" value="{{ $navTo }}">
                @endif

                <input type="hidden" name="redirectTo" value="{{ $redirectTo }}">

                <input type="email" class="box" name="email" placeholder="Email" required>
                <input type="password" class="box" name="password" placeholder="Password" required>
                <a href="{{ route('user.forgotPasswordPage') }}" class="teks-primer float-right mt-2">Forget password?</a>

                <div class="mt-5 w-100">
                    <div style="width: 304px" class="mx-auto">
                        {!! NoCaptcha::display() !!}
                        {!! NoCaptcha::renderJs() !!}
                    </div>
                </div>

                {{-- <div id="g_id_onload"
		class="d-none"
                data-client_id="{{ env('GOOGLE_CLIENT_ID') }}"
                data-login_uri="{{ env('GOOGLE_REDIRECT_URI') }}"
                data-auto_prompt="false">
            </div> --}}

                <div class="row mt-3">
                    <button class="mt-3 col-md bg-primer">Masuk</button>
                </div>
                {{-- <div class="d-none g_id_signin" style="margin-top: 10px"
                data-type="standard"
                data-size="large"
                data-theme="outline"
                data-text="sign_in_with"
                data-shape="rectangular"
                data-logo_alignment="left">
            </div> --}}
                <div style="width: 100%; height: 15px; border-bottom: 1px solid black; text-align: center">
                    <a class="text-secondary pt-2 bg-white pr-3 pl-3 mt-3">atau</a>
                </div>
                <div class="w-100 d-flex social-button">
                    <a href="{{ route('user.loginGooglePage', ['provider' => 'google']) }}"
                        class="btn btn-outline-danger mx-auto mt-4 p-2 rounded-4 w-100">
                        <img src="/riyan/images/icon-google.png" alt="Google">
                        Google
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.cjs.min.js"></script>
    <script>
        // function parseJwt (token) {
        //     var base64Url = token.split('.')[1];
        //     var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
        //     var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
        //         return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        //     }).join(''));

        //     return JSON.parse(jsonPayload);
        // }
        // const handleCredentialResponse = (response) => {
        //     let myData = parseJwt(response.credential);
        //     window.open(`{{ route('user.loginWithGoogle') }}/${btoa(myData.email)}`);
        // }
        // window.onload = () => {
        //     console.log("{{ env('GOOGLE_CLIENT_ID') }}");
        //     google.accounts.id.initialize({
        //         client_id: "{{ env('GOOGLE_CLIENT_ID') }}",
        //         callback: handleCredentialResponse
        //     });
        //     //google.accounts.id.prompt();
        // }
    </script>
@endsection

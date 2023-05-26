@extends('layouts.auth')

@section('title', "forgotPassword")

@section('content')
    <form action="{{ route('user.updatePassword') }}" method="POST" class="mt-3 mb-5">
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
        <input type="hidden" name="email" value="{{ $email }}">
        <div class="mt-2">Masukkan Password Baru :</div>
        <input type="password" class="box" name="passwordBaru" id="password1" required>
        <div class="mt-2">Masukkan Kembali Password Anda :</div>
        <input type="password" class="box" name="reenterPassword" id="password2" required oninput="check()"> <br>
        <p style="color: red; display: none;" id="alert">Password tidak sesuai</p>

        <button class="mt-3 bg-primer" id="button">Reset Password</button>
    </form>
@endsection

@section('javascript')
    <script type="text/javascript">
      function check(){
        console.log("masuk function");
        if(document.getElementById("password1").value != document.getElementById("password2").value){
          document.getElementById("alert").style.display = 'block';
          document.getElementById("button").setAttribute('disabled',true);
        }
        else{
          console.log("masuk else");
          document.getElementById("alert").style.display = 'none';
          document.getElementById("button").removeAttribute("disabled");
        }
      }
    </script>
@endsection

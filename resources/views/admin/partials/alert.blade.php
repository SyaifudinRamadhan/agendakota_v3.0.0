<div class="smallPadding">
    @if (session('berhasil'))
        <div class="alert alert-success" role="alert">
            {{session('berhasil')}}
        </div>
    @elseif(session('gagal'))
        {{-- <div class="alert alert-danger" role="alert" style="height: 25px; font-size:20px;"> --}}
        <div class="alert alert-danger" role="alert">
            {{session('gagal')}}
        </div>
    @elseif ($errors->count())
        {{-- <div class="bg-merah-transparan rounded" style="height: 25px; font-size:20px;"> --}}

         @foreach ($errors->all() as $message)
         
            <div class="alert alert-danger" role="alert">
              {{$message}}
            </div>    
                
        @endforeach

    @endif
</div>

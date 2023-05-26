@section('content2')


    <div class="header-chat font-inter teks-inter">
        <p class="col-md text-center">{{$event->name}}</p>
    </div>

    <div class="messages-kanan col-md mt-3">        
        <div class="messages-kanan-detil col-md">
            @for ($i = 0; $i < 3; $i++)

                <div class="chat-masuk mt-2 mb-2">
                    <div class="bg-white row col-md">
                        <div class="rata-kiri">
                            <img class="card-img-top img-chat" src="{{ asset('images/profile-user.png') }}" alt="">
                        </div>
                        <div class="rata-kiri col-md">
                            <p class="mb-0 d-inline">Agendakota</p>
                            <p class="teks-transparan teks-kecil float-right">1 Jam yang lalu</p>
                            <p class="mb-0 teks-tipis">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor nostrum atque accusamus amet perferendis aliquam, nam magni quae! Molestiae alias ducimus tempore omnis quis magnam ipsam, porro quae dolor deleniti!</p>
                        </div>
                    </div>
                </div>               

            @endfor

        </div>
        <div class="messages-kanan-kirim bg-white">
            <div class="form-group row">
                <input class="col-md-8 border-0" type="text" class="form-control" name="" id=""
                    placeholder="Tuliskan pesanmu di sini">
                <button class="btn col-md bg-white"><i class="fa fa-send text-dark"></i></button>
            </div>
        </div>
    </div>


@endsection

@extends('layouts.user')

@section('title', "Ticket Details")

@section('head.dependencies')
    <style>
        .teks-finished{
            color:#BDBDBD;
            width: 95px;
            height: 25px;
            margin-top: -6%;
            margin-left: 3%;
        }
        .teks-finished p {
            font-size: 14px;
        }
        .teks-upcoming{
            color:#e5214f; /*#EB597B*/
            width: 95px;
            height: 25px;
            margin-top: -6%;
            margin-left: 3%;
        }
        .teks-upcoming p {
            font-size: 14px;
        }
        .teks-happening{
            background: #e5214f; /*#EB597B*/
            border-radius: 25px;
            color: #FFFFFF;
            width: 95px;
            height: 25px;
            margin-top: -2%;
            margin-left: 3%;
        }
        .teks-happening p {
            font-size: 14px;
            margin-top: -0px;
            margin-left: 10px;
            padding: 2px;
        }

        .ticket {            
            margin-top: 3%;
            position: relative;
            border: 1.5pt solid #E1E5E8;
            display: inline-block;
            padding: 2em 2em;
            width: 100%;
            height: 100%;
            border-radius: 10px;

        }                

        .garis-tiket{
            border-top: 2px solid;            
        }

        .price{
            font-size: 15px;
            color:#e5214f; /*#EB597B*/
            margin-top: -5%;
        }
        .ticket-name{
            font-size: 20px;
            color:black;
            text-transform: uppercase;
        }
        .btn-download{
            width: 60%;
            height: 60%;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .btn-ticket-detail-1{
            width: calc(50% - 1rem);
        }
        .btn-ticket-detail-2{
            width: calc(100% - 1rem);
        }
        .img-qrcode{
            width: 60%;
            height: 60%;
        }
        .img-barcode{
            width: 100%;
            height: 100%;
        }
        .overflow-wrap{
            overflow-wrap: anywhere;
        }
    </style>

@endsection

@section('content')
@php
    use Carbon\Carbon;
    setlocale(LC_ALL, 'id_ID');
    Carbon::setLocale('id');
    $tanggalsekarang= Carbon::now()->toDateString();
@endphp

    <div class="mb-3">
        <h2>My Tickets</h2>
        <div>
            <span class="teks-transparan">Temukan Semua Tiket Event</span>
            <a href="/myTickets"><button class="bg-primer float-right"><i class="fa fa-arrow-left"></i> Kembali</button></a>
        </div>
    </div>    

    <div class="tab scrollmenu" style="border: none; border-bottom: 1px solid #F0F1F2;">
        <button class="tab-btn tablinks-myTickets active" onclick="opentabs(event, 'myTickets', 'QR')">QR</button>
        <button class="tab-btn tablinks-myTickets"  onclick="opentabs(event, 'myTickets', 'Barcode')">Barcode</button>        
    </div>

    <div id="QR" class="tabcontent-myTickets" style="display: block; border: none;">
        <div class="mt-5">
            <div class="row">
                <div class="col-8">
                    <h3 class="mb-0">Ticket Details</h3>
                </div>
                <div class="col-4">
                   
                    <p class="text-right text-danger">Status : {{ $purchases[0]->tempFor->share_to == $myData->email ? 'This is Mine' : (isset($purchases[0]->invitation->id) ? 'Waiting Accept Invitation from '.$purchases[0]->tempFor->share_to : 'Waiting User Register from '.$purchases[0]->tempFor->share_to ) }}</p>
                </div>
            </div>
            <?php 

            //dd($purchases, $myData->id, $id);

             ?>
            @forelse ($purchases as $purchase)
                <div class="mt-0 justify-content-between">
                    
                    <div class="ticket col-md" id="pilih-ticket" ticket-id="">  

                        <div class="bagi bagi-3 text-center">
                            <div class="d-flex">
                                <h4 class="mx-auto">{{$purchase->events->name}}</h4>                                  
                            </div>  
                            <span class="teks-kecil">{{$invoiceID}}</span>                        
                            <div class="d-flex justify-content-center mt-2 mb-2">
                                <img src="data:image/png;base64,{{BarCode2::getBarcodePNG($invoiceID.'-'.$myData->id, 'QRCODE', 8,8)}}" class="img-qrcode" alt="barcode" /> 
                            </div>                              
                            <a href="{{route('user.printTicket', [$id, 'qrcode'])}}" target="_blank">
                                <button class="btn btn-outline-danger my-auto mx-auto btn-download"><i class="fa fa-download teks-primer"></i><span class="teks-primer"> Download Ticket</span></button>
                            </a>                          
                        </div>   

                        <div class="bagi bagi-3 mb-0">
                            <label class="text-secondary mb-1">Nama Tiket</label>
                            <b class="d-flex mb-5">{{$purchase->tickets->name}}</b>   
                            {{-- @dd($purchase->tickets->session->start_date, $purchase->tickets->session, $purchase->tickets) --}}
                            <label class="text-secondary mb-1">Tanggal</label>
                            <b class="d-flex mb-5">{{ Carbon::parse($purchase->tickets->session->start_date)->isoFormat('D MMMM,') }} {{ Carbon::parse($purchase->tickets->session->start_time)->format('H:i') }} WIB -
                                {{ Carbon::parse($purchase->tickets->session->end_date)->isoFormat('D MMMM,') }} {{ Carbon::parse($purchase->tickets->session->end_time)->format('H:i') }} WIB</b>   

                            <label class="text-secondary mb-1">Hosted By</label>
                            <b class="d-flex mb-5">{{$purchase->events->organizer->name}}</b>   
                        </div>

                        <div class="bagi bagi-3 mb-0">
                            <label class="text-secondary mb-1">Kode Invoice</label>
                            <b class="d-flex mb-5">{{$invoiceID}}</b>                                                           

                            <label class="text-secondary mb-1">Metode Pembayaran</label>
                            <b class="d-flex mb-5">Transfer via Midtrans</b>   

                            <label class="text-secondary mb-1">Status</label>
                            @if($purchase->payment->pay_state == 'Terbayar')
                                <div class="alert alert-success text-success alert-dismissible fade show col-md-2 pt-1 pb-1 pl-3 mb-5" role="alert">
                                    <strong>Paid</strong>
                                </div>
                            @else
                                <div class="alert alert-danger text-success alert-dismissible fade show col-md-2 pt-1 pb-1 pl-3 mb-5" role="alert">
                                    <strong>Belum Dibayar</strong>
                                </div>
                                @if($purchase->user_id == $purchase->send_from)
                                    <button class="btn btn-outline-danger text-danger" id="pay-button">Bayar Sekarang</button>
                                    <!-- <p class="text-danger text-center"> Silahkan tunggu pembayarannya dilunasi temanmu </p> -->
                                @else
                                    <p class="text-danger text-center"> Silahkan tunggu pembayarannya dilunasi temanmu </p>
                                @endif
                            @endif
                        </div>
                                                                
                        <div class="mt-0">
                            <hr class="garis-tiket" style="color: lightgray;"/>                            
                            <p class="text-secondary mt-3">
                                {{$purchase->tickets->description}}
                            </p>
                            <p class="text-danger">
                                
                                Catatan : Tombol join meet akan muncul setelah pembayaran tuntas atau status ticket milik pribadi
                                @if ($purchase->tickets->deleted == 1)
                                    <br><br>( Tiket ini sudah dihapus oleh organizer / mungkin dibatalkan. Silahkan menghubungi 
                                    receptionist event atau email organizer berikut untuk konfirmasi 
                                    <a href="mailto:{{ $purchase->tickets->session->event->organizer->user->email }}">Kirim Email</a> / 
                                    <a href="https://wa.me/{{ $purchase->tickets->session->event->organizer->no_telepon }}">
                                        {{ $purchase->tickets->session->event->organizer->no_telepon }}
                                    </a> )
                                @endif
                            </p>
                            @if($purchase->events->execution_type == 'offline')
                                <b>Acara ini bersifat offline</b>
                            @endif
                        </div>

                        <div class="mt-0">
                            <hr class="garis-tiket" style="color: lightgray;"/>      
                            @if($purchase->payment->pay_state == 'Terbayar' && ($purchase->events->execution_type == 'online' || $purchase->events->execution_type == 'hybrid'))
                                @if($purchase->tempFor->share_to == $myData->email)
                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <a class="text-secondary mt-3" href="{{route('user.joinStream', [$purchase->id])}}">
                                            <button class="btn btn-outline-primary ml-2 mr-1 w-100 text-center">
                                                Join Stream
                                            </button> 
                                        </a>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <a href="{{route('user.eventDetail',[$purchase->events->slug])}}" class="text-primary">
                                            <button class="btn btn-outline-danger ml-1 mr-2 w-100 text-center">
                                                Detail Event
                                            </button>
                                        </a>
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-md-12 mt-2">
                                        <a href="{{route('user.eventDetail',[$purchase->events->slug])}}" class="text-primary">
                                            <button class="btn btn-outline-danger ml-1 mr-2 w-100 text-center">
                                                Detail Event
                                            </button>
                                        </a>
                                    </div>
                                </div>
                                @endif
                            
                            @else
                            <div class="row">
                                <div class="col-md-12 mt-2">
                                    <a href="{{route('user.eventDetail',[$purchase->events->slug])}}" class="text-primary">
                                        <button class="btn btn-outline-danger ml-1 mr-2 w-100 text-center">
                                            Detail Event
                                        </button>
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="mt-1">
                            
                        </div>

                    </div>

                    <div class="ticket col-md pt-3" id="pilih-ticket" ticket-id="">  

                        <div class="bagi bagi-3">
                            <label class="text-secondary mb-1">Nama</label>
                            <b class="d-flex">{{$myData->name}}</b>                                                           
                        </div> 

                        <div class="bagi bagi-3">
                            <label class="text-secondary mb-1">Email</label>
                            <b class="d-flex">{{$myData->email}}</b> 
                        </div> 
                        
                        <div class="bagi bagi-3">
                            <label class="text-secondary mb-1">No. Telpon</label>
                            <b class="d-flex">{{$myData->phone}}</b> 
                        </div> 

                    </div>
                </div>
            @empty
                <div class="rata-tengah">
                    <img src="{{asset('images/calendar.png')}}">
                    <h3>Mulai Bergabung di event - event menarik</h3>
                    <p>Temukan berbagai event menarik di AgendaKota</p>
                    <a href="{{route('user.homePage')}}">
                        <button class="bg-primer">
                            Temukan Event
                        </button>
                    </a>
                </div>
            @endforelse
        </div>
    </div> 

    <div id="Barcode" class="tabcontent-myTickets" style="display: none; border: none;">
        <div class="mt-5">
            <div class="row">
                <div class="col-8">
                    <h3 class="mb-0">Ticket Details</h3>
                </div>
                <div class="col-4">
                   
                    <p class="text-right text-danger">Status : {{ $purchases[0]->tempFor->share_to == $myData->email ? 'Punya Pribadi' : (isset($purchases[0]->invitation->id) ? 'Menunggu Accept Invitation dari '.$purchases[0]->tempFor->share_to : 'Menunggu User Register dari '.$purchases[0]->tempFor->share_to ) }}</p>
                </div>
            </div>
            @forelse ($purchases as $purchase)
                <div class="mt-0 justify-content-between">
                    
                    <div class="ticket col-md" id="pilih-ticket" ticket-id="">  

                        <div class="bagi bagi-3 text-center">
                            <div class="d-flex">
                                <h4 class="mx-auto">{{$purchase->events->name}}</h4>                                  
                            </div>  
                                               
                            <div class="d-flex justify-content-center mt-2 mb-2">
                                <img src="data:image/png;base64,{{BarCode1::getBarcodePNG($invoiceID.'-'.$myData->id, 'C39',2,100)}}" class="img-barcode" alt="barcode" />                                
                            </div>  
                            <span class="justify-content-center d-flex mb-2 text-small overflow-wrap">{{$invoiceID}}</span>     
                            
                            <a href="{{route('user.printTicket', [$id, 'barcode'])}}" target="_blank">
                                <button class="btn btn-outline-danger my-auto mx-auto btn-download"><i class="fa fa-download teks-primer"></i><span class="teks-primer"> Download Ticket</span></button>
                            </a>                      
                        </div>   

                        <div class="pl-5 bagi bagi-3 mb-0">
                            <label class="text-secondary mb-1">Nama Tiket</label>
                            <b class="d-flex mb-5">{{$purchase->tickets->name}}</b>   
                            
                            <label class="text-secondary mb-1">Tanggal</label>
                            <b class="d-flex mb-5">{{ Carbon::parse($purchase->events->start_date)->format('d M,') }} {{ Carbon::parse($purchase->events->start_time)->format('H:i') }} WIB -
                                {{ Carbon::parse($purchase->events->end_date)->format('d M,') }} {{ Carbon::parse($purchase->events->end_time)->format('H:i') }} WIB</b>   

                            <label class="text-secondary mb-1">Hosted By</label>
                            <b class="d-flex mb-5">{{$purchase->events->organizer->name}}</b>   
                        </div>

                        <div class="pl-5 bagi bagi-3 mb-0">
                            <label class="text-secondary mb-1">Kode Invoice</label>
                            <b class="d-flex mb-5 overflow-wrap">{{$invoiceID}}</b>                                                           

                            <label class="text-secondary mb-1">Metode Pembayaran</label>
                            <b class="d-flex mb-5">Transfer via Midtrans</b>   

                            <label class="text-secondary mb-1">Status</label>
                            @if($purchase->payment->pay_state == 'Terbayar')
                                <div class="alert alert-success text-success alert-dismissible fade show col-md-2 pt-1 pb-1 pl-3 mb-5" role="alert">
                                    <strong>Paid</strong>
                                </div>
                            @else
                                <div class="alert alert-danger text-success alert-dismissible fade show col-md-2 pt-1 pb-1 pl-3 mb-5" role="alert">
                                    <strong>Belum Dibayar</strong>
                                </div>
                                 <button class="btn btn-outline-danger text-danger" id="pay-button">Bayar Sekarang</button>
                            @endif
                            
                        </div>
                                                                
                        <div class="mt-0">
                            <hr class="garis-tiket" style="color: lightgray;"/>                            
                            <p class="text-secondary mt-3">
                                {{$purchase->tickets->description}}
                            </p>
                            <p class="text-danger">
                                
                                Catatan : Tombol join meet akan muncul setelah pembayaran tuntas atau status ticket milik pribadi
                            </p>
                        </div>

                        <div class="mt-0">
                            <hr class="garis-tiket" style="color: lightgray;"/>      
                            @if($purchase->payment->pay_state == 'Terbayar' && ($purchase->events->execution_type == 'online' || $purchase->events->execution_type == 'hybrid'))
                                @if($purchase->tempFor->share_to == $myData->email)
                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <a class="text-secondary mt-3" href="{{route('user.joinStream', [$purchase->id])}}">
                                            <button class="btn btn-outline-primary ml-2 mr-1 w-100 text-center">
                                                Join Stream
                                            </button> 
                                        </a>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <a href="{{route('user.eventDetail',[$purchase->events->slug])}}" class="text-primary">
                                            <button class="btn btn-outline-danger ml-1 mr-2 w-100 text-center">
                                                Detail Event
                                            </button>
                                        </a>
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-md-12 mt-2">
                                        <a href="{{route('user.eventDetail',[$purchase->events->slug])}}" class="text-primary">
                                            <button class="btn btn-outline-danger ml-1 mr-2 w-100 text-center">
                                                Detail Event
                                            </button>
                                        </a>
                                    </div>
                                </div>
                                @endif
                            
                            @else
                            <div class="row">
                                <div class="col-md-12 mt-2">
                                    <a href="{{route('user.eventDetail',[$purchase->events->slug])}}" class="text-primary">
                                        <button class="btn btn-outline-danger ml-1 mr-2 w-100 text-center">
                                            Detail Event
                                        </button>
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="ticket col-md pt-3" id="pilih-ticket" ticket-id="">  

                        <div class="bagi bagi-3">
                            <label class="text-secondary mb-1">Nama</label>
                            <b class="d-flex">{{$myData->name}}</b>                                                           
                        </div> 

                        <div class="bagi bagi-3">
                            <label class="text-secondary mb-1">Email</label>
                            <b class="d-flex">{{$myData->email}}</b> 
                        </div> 
                        
                        <div class="bagi bagi-3">
                            <label class="text-secondary mb-1">No. Telpon</label>
                            <b class="d-flex">{{$myData->phone}}</b> 
                        </div> 

                    </div>
                </div>
            @empty
                <div class="rata-tengah">
                    <img src="{{asset('images/calendar.png')}}">
                    <h3>Mulai Bergabung di event - event menarik</h3>
                    <p>Temukan berbagai event menarik di AgendaKota</p>
                    <a href="{{route('user.homePage')}}">
                        <button class="bg-primer">
                            Temukan Event
                        </button>
                    </a>
                </div>
            @endforelse
        </div>
    </div>  

<script type="text/javascript">
    document.getElementById("pay-button").onclick = function(){
        
        console.log('{{$purchase->token_trx}}');
        snap.pay('{{ $purchase->token_trx }}', {

                onSuccess: function(result){
                    /* You may add your own implementation here */
                    alert('Pembayaranmu sudah kami terima');
                },
                onPending: function(result){
                    /* You may add your own implementation here */
                    alert("Mohon selesaikan pembayarannya segera");
                },
                onError: function(result){
                    /* You may add your own implementation here */
                    alert("Pembayaranmu gagal!"); console.log(result);
                },
                onClose: function(){
                    /* You may add your own implementation here */
                    alert('Kamu menutup Pop-Up pembayaran sebelum selesai');
                }
        }); // Replace it with your transaction token



    }
</script>

@endsection








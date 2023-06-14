<link rel="stylesheet" type="text/css" href="{{ asset('css/user/cartTicket.css') }}">
<style>
    @media only screen and (max-width: 420px){
        .btn-pay{
            width: 100%;
        }
    }
</style>
{{-- ============== SNADBOX / TEST MODE =============================== --}}
<script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ $CLIENT_KEY }}"></script>

{{-- =============== PRODUCTION MODE =========================== --}}
{{--    <script type="text/javascript"
            src="https://app.midtrans.com/snap/snap.js"
            data-client-key="{{ $CLIENT_KEY }}"></script> --}}

@php
    use Carbon\Carbon;
    use App\Models\Invitation;
    $tanggalsekarang= Carbon::now()->toDateString();
@endphp

    <div class="">
        <div class="bagi bagi-2 mb-3 ml-3">
            <h4>Pay Your Tickets</h4>
            <span class="teks-transparan">Tuntaskan Tagihan Pembayaran Ticket Eventmu</span>
        </div>

        @include('admin.partials.alert')
        
        <div class="row">
            
            @foreach($payments as $payment)
                <div class="col-12 mb-4">
                    <div class="bg-putih rounded-5 w-100 bayangan-5 p-4 pl-5 pr-5">
                        <h5>No Transaksi : {{$payment->order_id}}</h5>
                        <div class="row">
                        @foreach($payment->purchases as $purchase)

                        @if(!isset($purchase->tempFor))
                                <script type="text/javascript">
                                    console.log('{{route('user.shareTickets2', [http_build_query(array('myArray' => $payment->id))])}}');
                                    window.location.replace('{{route('user.shareTickets2', [http_build_query(array('myArray' => $payment->id))])}}');
                                </script>
                        @endif
                            <div class="ticket-2 col-lg-9 strip text-left mb-3">
                                <div>
                                    
                                    <h1 class="ticket-name mt-1">
                                        <b><?= $purchase->tickets->name ?></b>
                                    </h1>
                                    <p class="fs-16 mb-0">
                                        <i class="fa bi bi-star mr-3"></i><?= $purchase->tickets->session->event->name ?>
                                    </p>
                                    <p class="float-right text-right fs-16 text-danger">Give To : <br>
                                        <input class="box rounded-5 no-bg bayangan-5" type="email" name="gifts[]" value="<?= isset($purchase->tempFor->share_to) ? $purchase->tempFor->share_to : '' ?>" placeholder="Email recepient" readonly>
                                    </p>
                                    <input type="hidden" name="purchases[]" value="<?= $purchase->id ?>">
                                    <input type="hidden" name="payments[]" value="<?= $payment->id ?>">
                                    
                                    <p class="fs-14">
                                        <i class="fas fa-calendar mr-3"></i>
                                        <?= Carbon::parse($purchase->tickets->session->start_date)->format('d M,') ?> <?= Carbon::parse($purchase->tickets->session->start_time)->format('H:i') ?> WIB -
                                        <?= Carbon::parse($purchase->tickets->session->end_date)->format('d M,') ?> <?= Carbon::parse($purchase->tickets->session->end_time)->format('H:i') ?> WIB
                                    </p>
                                    <p class="teks-primer fs-17 mb-0">
                                        
                                        <b>
                                            <?php if($purchase->tickets->price == 0){ ?>
                                                Gratis
                                            <?php }else{ ?>
                                                @currencyEncode($purchase->tickets->price)
                                            <?php } ?>
                                        </b>
                                    </p>
                                    
                                 </div>
                            </div>
                            <div class="ticket-3 col-lg-3 p-5 strip-2 mb-3">
                                <label class="no-pd text-center w-100">
                                    <h4 style="font-size: 18px">
                                        <?= $purchase->tickets->session->event->name ?>
                                        <br>
                                        <p class="fs-14 mt-3">
                                            <?= Carbon::parse($purchase->tickets->session->event->start_date)->format('d M Y') ?> -
                                            <?= Carbon::parse($purchase->tickets->session->event->end_date)->format('d M Y') ?>
                                        </p>
                                    </h4>
                                    
                                </label>
                            </div>
                        @endforeach
                            <div class="text-right mt-5 pb-1 col-md-12">
                                <a href="{{ route('user.cancelPay',[$payment->order_id]) }}">
                                    <button class="btn btn-danger mb-2 btn-pay">Batalkan</button>
                                </a>
                                <button class="btn bg-primer mb-2 btn-pay" onclick="payClik(this)" value="<?= $payment->token_trx ?>">Bayar Sekarang</button>
                            </div>
                        </div>
                        
                    </div>
                </div>
            @endforeach
        </div>
        
    </div>

<script type="text/javascript">
    function payClik (evt){
        var token = evt.value;
        console.log(token);
        snap.pay(token, {

                onSuccess: function(result){
                    /* You may add your own implementation here */
                    // alert('Pembayaranmu sudah kami terima');
                    Swal.fire({
                        title: 'Selamat !!!',
                        text: 'Pembayaran kamu sudah kami terima',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });
                },
                onPending: function(result){
                    /* You may add your own implementation here */
                    // alert("Mohon selesaikan pembayarannya segera");
                    Swal.fire({
                        title: 'Peringatan !!!',
                        text: 'Segera selesaikan pembayaranmu',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                },
                onError: function(result){
                    /* You may add your own implementation here */
                    // alert("Pembayaranmu gagal!"); console.log(result);
                    Swal.fire({
                        title: 'Error !!!',
                        text: 'Pembayaranmu gagal',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                },
                onClose: function(){
                    /* You may add your own implementation here */
                    // alert('Kamu menutup Pop-Up pembayaran sebelum selesai');
                    Swal.fire({
                        title: 'Peringatan !!!',
                        text: 'Pop up pembayaran telah ditutup. Segera selesaikan pembayaran',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                }
        }); // Replace it with your transaction token



    }
</script>
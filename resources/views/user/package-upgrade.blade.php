@extends('layouts.homepage')
{{-- Masih belum selesai sempurna, masih da part no 4 belum dibuat di page ini --}}
@section('title', 'Pricing')
@section('head.dependencies')
    <link rel="stylesheet" href="{{ asset('css/user/packagePage.css') }}">
    <link rel='stylesheet' id='axil-svg-icon-css'  href='https://agendakota.id/wp-content/themes/keystroke/assets/svg-icon/style.css?ver=1.0.5' type='text/css' media='all' />
    {{-- <link rel='stylesheet' id='axil-style-css'  href='https://agendakota.id/wp-content/themes/keystroke/assets/css/style.css?ver=1.0.5' type='text/css' media='all' /> --}}
    <style>
        header{
            height: unset;
        }
        .table td{
            border: 0px;
        }
        .top-ribbon .btn-group{
            margin-top: 0.5rem!important;
            margin-bottom: 0.5rem!important;
        }
        #content-section {
            margin-top: 150px !important;
        }
    </style>
@endsection

{{-- ============== SNADBOX / TEST MODE =============================== --}}
<script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ $CLIENT_KEY }}"></script>

{{-- =============== PRODUCTION MODE =========================== --}}
{{-- <script type="text/javascript"
        src="https://app.midtrans.com/snap/snap.js"
        data-client-key="{{ $CLIENT_KEY }}"></script> --}}

@section('content')
@php
    use Carbon\Carbon;
@endphp
    <div class="container pt-3" style="max-width: 1390px">
        <h3 class="text-center">Detail Pembelian Sebelumnya</h3>
        {{-- Detail transaksi user dan pembelian paket sebelumnya --}}
        <div class="col-12">
            <div class="bagi bagi-2 w-100 list-item mb-2">
                <div class="mt-3">
                    <div class="bg-putih bayangan-5 rounded-5 smallPadding">
                        <div class="detail">
                            <div class="row pl-3 pr-3 pt-3 pb-3">
                                <div class="col-lg-8 pl-4 pr-4 mb-4">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td style="padding-top: 10px !important; padding: unset;"
                                                class="text-secondary">Status</td>
                                            <td style="padding-top: 10px !important; padding: unset;">
                                                {{-- <span class="btn no-pd bg-secondary text-light">Waiting</span> --}}
                                                @if ($myData->pkg_status == 0)
                                                    <span class="btn no-pd bg-danger text-light">Inactive</span>
                                                @elseif($myData->pkg_status == 1)
                                                    <span class="btn no-pd bg-success text-light">Active</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 10px !important; padding: unset;"
                                                class="text-secondary">Waktu Pembelian</td>
                                            <td style="padding-top: 10px !important; padding: unset;">
                                                {{ Carbon::parse($myPaymentPkg->created_at)->format('d-m-Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 10px !important; padding: unset;"
                                                class="text-secondary">Nama Paket</td>
                                            <td style="padding-top: 10px !important; padding: unset;">
                                                {{ $myData->package->name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 10px !important; padding: unset;"
                                                class="text-secondary">Masa Berlaku</td>
                                                @php
                                                    $activeTime = new DateTime($myData->created_at);
                                                    $lastPaid = \App\Models\PackagePayment::where('user_id',$myData->id)->where('status',1)->orderBy('id','DESC')->first();
                                                    if($lastPaid->nominal == $myData->package->price){
                                                        // Tambahkan created_at +30 hari
                                                        $activeTime->modify('+30 day');
                                                    }else{
                                                        // Tambahkan +365 hari
                                                        $activeTime->modify('+365 day');
                                                    }
                                                @endphp
                                            <td style="padding-top: 10px !important; padding: unset;">
                                            @if ($myData->package->price == 0 && $myData->package->price_in_year == 0)
                                                Unlimited
                                            @else
                                                {{$activeTime->format('d-m-Y H:i:s')}}
                                            @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-lg-4 pl-3 pr-3 mb-4">
                                    <div class="bayangan-5 pl-2 pr-2 pt-2 pb-3">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td style="padding-top: unset !important; padding: unset;"
                                                    class="fs-12">{{ $myData->packagePayments[count($myData->packagePayments)-1]->nominal == $myData->package->price ? 'Paket Bulanan' : 'Paket Tahunan' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 2px !important; padding: unset;"
                                                    class="fs-18 mt-1">Rp. {{ \App\Http\Controllers\CurrencyController::index($myData->packagePayments[count($myData->packagePayments)-1]->nominal) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 10px !important; padding: unset;">
                                                    {{-- <span class="btn btn-secondary text-center w-100"
                                                            style="border-radius: 4px">PROSES</span> --}}
                                                    @if ($myPaymentPkg->status == 1)
                                                        @if ($isPkgActive == 0)
                                                            <form class="w-100" action="{{ route('user.upgradePkg.update') }}" method="post">
                                                                @csrf
                                                                <button class="btn btn-danger text-center w-100"
                                                                style="border-radius: 4px">Beli Lagi</button>
                                                            </form>
                                                        @else
                                                            <span class="btn btn-success text-center w-100"
                                                                style="border-radius: 4px">SELESAI</span>
                                                        @endif
                                                    @elseif($myPaymentPkg->status == 0)
                                                        <button class="btn btn-danger text-center w-100"
                                                            style="border-radius: 4px" onclick="payClik(this)"
                                                            value="<?= $myPaymentPkg->token_trx ?>">BAYAR</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="top mt-5">
            <div class="toggle-btn">
                <span style="margin: 0.8em;">Bulanan</span>
                <label class="switch">
                    <input type="checkbox" id="checbox" onclick="check()" ; />
                    <span class="slider round"></span>
                </label>
                <span style="margin: 0.8em;">Tahunan</span>
            </div>
        </div>

        <div class="package-container mb-5">

            @foreach ($pkgList as $item)
            <form action="{{ route('user.upgradePkg.update') }}" method="post">
                @csrf
                <div class="packages mb-3" style="background: {{ $item->name == 'Basic' ? '#eb597b' : '#fff' }};color: {{ $item->name == 'Basic' ? '#fff' : '#444'}};box-shadow: 1px 1px 5px 1px #ddd">
                    <h1 class="mt-4">{{ $item->name }}</h1>
                    <p>{{ $item->description }}</p>
                    <h2 class="text1">Rp. {{ App\Http\Controllers\CurrencyController::index($item->price) }}</h2>
                    <h2 class="text2">Rp.
                        {{ App\Http\Controllers\CurrencyController::index($item->price_in_year) }}</h2>
                    <input class="{{ base64_encode('typeSelect') }}" type="hidden" name="{{ base64_encode('selectType==') }}" value="">
                    <input type="hidden" name="{{ base64_encode('idPkgUsr==--') }}" value="{{ base64_encode($item->id) }}">
                    <ul class="list">
                        <li class="first">{{ $item->organizer_count <= -1 ? 'Unlimited' : $item->organizer_count }} organizer</li>
                        <li>{{ $item->event_same_time <= -1 ? 'Unlimited' : $item->event_same_time }} event same time per organizer</li>
                        <li>{{ $item->ticket_commission*100 }}% Ticket Commission</li>
                        <li>{{ $item->sponsor_count <= -1 ? 'Unlimited' : $item->sponsor_count }} Sponsors</li>
                        <li>{{ $item->partner_media_count <= -1 ? 'Unlimited' : $item->partner_media_count }} Media Partners</li>
                        <li>{{ $item->session_count <= -1 ? 'Unlimited' : $item->session_count }} Sessions</li>
                        <li>{{ $item->exhibitor_count <= -1 ? 'Unlimited' : $item->exhibitor_count }} Booth &amp; Exhibitor</li>
                        <li>{{ $item->max_attachment }} MB File Upload (per file)</li>
                        @if ($item->custom_link == 1)
                            <li>Custom Event Link</li>
                        @endif
                        @if ($item->report_download)
                            <li>Download Report</li>
                        @endif
                        
                    </ul>
                    {{-- <button class="btn button2">Pilih Paket</button> --}}
                    <button class="{{ $item->name == 'Basic' ? 'bg-putih' : 'bg-primer' }}">Pilih Paket</button>
                </div>
            </form>
            @endforeach


        </div>
        <script src="{{ asset('js/user/packagePage.js') }}"></script>
        <script type="text/javascript">

           
            function payClik (evt){
                var token = evt.value;
                console.log(evt);
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
    @endsection

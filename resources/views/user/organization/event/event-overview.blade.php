@extends('layouts.user')



@section('title', 'Event Overview')

@section('head.dependencies')
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/overviewPage.css') }}">
    <style>
        #custom_slug {
            border: none;
            padding: 0px;
        }
        #custom_slug:focus {
            box-shadow: none;
        }
        #aktEvents {
            padding-right: 0px;
        }
    </style>
@endsection

@section('content')
    @php
    use Carbon\Carbon;
    @endphp


    <div class="container-cadangan">
        @include('admin.partials.alert')
        <div class="row">

            <div class="col-xl-4 mt-2 mb-5">

                <div class="card-box-lg bg-putih rounded card-radius">
                    <i class="fa fa-user card-icon" aria-hidden="true"></i>
                    <p class="teks-tebal card-text-2">Attendees</p>
                    <p class="teks-tebal card-1-attdn card-text-4">{{ count($purchases) }}</p>
                    <p id="persentase-pengunjung" class="card-text-1">20% dari Pendaftar</p>
                </div>


                <div class="card-box-lg bg-putih rounded mt-divid-card card-radius">
                    <i class="fa fa-tag card-icon" aria-hidden="true"></i>
                    <p class="teks-tebal card-text-2">Ticket Sales</p>
                    <p class="teks-tebal card-text-3" id="total-penjualan">IDR 104.340.000</p>
                    <p id="penjualan-tiket" class="card-text-1">20 Tiket Terjual dari 100</p>
                </div>


                <div id="card-sales-type" class="card-box-lg card-no-height bg-putih rounded mt-divid-card card-radius">
                    <i class="fa fa-tag card-icon" aria-hidden="true"></i>
                    <div class="teks-tebal card-text-2">Ticket Sales by Type</div>
                    @foreach ($eventdetails as $event)
                        @foreach ($event->sessions as $session)
                            @forelse ($session->tickets as $ticket)
                                <p class="teks-tebal title-ticket">{{ $ticket->name }}</p>
                                <div class="bagi bagi-2" style="width:50%;">
                                    <p class="teks-tebal price-ticket">
                                        @if ($ticket->price == 0)
                                            Free
                                        @else
                                            @currencyEncode($ticket->price)
                                        @endif
                                    </p>
                                </div>
                                <div class="bagi bagi-2" style="width:50%;">
                                    <p class="teks-tebal text-progressbar" id="proggresbar-tiket-{{ $ticket->id }}"></p>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" id="progressbar-{{ $ticket->id }}" role="progressbar"
                                        aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"
                                        style="background-color:#e5214f; /*#EB597B*/"></div>
                                </div>
                            @empty
                                <p class="text-notif">
                                    <span class="mt-4">Anda Belum Membuat Tiket Pada Session
                                        {{ $session->title }}</span>
                                </p>
                            @endforelse
                        @endforeach
                    @endforeach
                    <p class="text-notif fs-14 rata-tengah">
                        <a class="teks-primer"
                            href="{{ route('organization.event.tickets', [$organization->id, $event->id]) }}">Lihat
                            Semua</a>
                    </p>
                </div>
            </div>


            <div class="col-xl-8 mt-2 mb-5">
                <div class="card-box-lg card-link bg-putih rounded card-radius">
                    <i class="fa fa-link card-icon" aria-hidden="true"></i>
                    <div class="teks-tebal card-text-2">Event Link</div>
                    <!-- <div class="bagi bagi-2 mt-5" style="width:70%; margin-left:20px;">
                                    <input type="text" class="box" name="eventlink" style="" placeholder="https://eventname.agendakota.id">
                                </div>
                                <div class="bagi bagi-2" style="width:20%; margin-top:-7%; margin-left:2%;;">
                                    <button class="primer lebar-95 mt-5" style="border-radius:8px;">Copy</button>
                                </div> -->
                    <div class="row input-link">
                        <div class="col-7">
                            <input id="link-event" type="text" class="box mt-0" name="eventlink" style=""
                                placeholder="https://eventname.agendakota.id"
                                value="{{ count($customLink) == 0 ? URL::to('/event-detail/' . $event->slug) : 'akt.events/'.$customLink[0]->slug_custom }}">
                        </div>
                        <div class="col-3">
                            <button class="primer" style="border-radius:8px;" onclick="copyButton('link-event');">Copy</button>
                        </div>
                    </div>

                    <div class="card-box-lg bg-putih rounded card-no-height card-radius input-link-2">
                        <div class="teks-tebal"
                            style="color: #304156; padding-left:20px; font-size: 14pt; padding-top:20px;">Custom Event
                            Link</div>
                        <div class="mt-1" style="color: #828D99; padding-left:20px; font-size: 12pt;">Undang
                            audience ke eventmu dengan link unik!</div>
                        <form action="{{ route('organization.event.customlink', [$organizationID, $event->id]) }}"
                            method="POST">
                            @csrf
                            @if ($isPkgActive == 0 || $organization->user->package->custom_link == 0)
                                <div class="bagi bagi-2" style="width:60%; margin-left:20px; margin-bottom:0%;">
                                    <p class="text-danger">
                                        *Fitur ini belum tersedia untuk paketmu / kamu belum bayar
                                    </p>
                                </div>
                            @else
                                <div class="bagi bagi-2" style="width:60%; margin-left:20px; margin-bottom:0%;">
                                    <div class="input-group mt-3">
                                        <span class="input-group-text no-border no-bg" id="aktEvents">akt.events/</span>
                                        <!-- Update By Rian (perlu di teliti lagi. Masih kacau) -->
                                        {{-- <input type="text" name="custom_slug" id="custom_slug" class="form-control" value="{{ $event->custom_slug }}" required> --}}
                                        <!-- --------------------------------------------------- -->
                                        <input type="text" name="customLink" id="custom_slug" class="form-control" value="{{ count($customLink) == 0 ? '' : $customLink[0]->slug_custom }}" required>
                                    </div>
                                </div>
                                <div class="bagi bagi-2" style="width:30%; margin-left:2%; margin-bottom:0%;">
                                    <button type="submit" class="btn primer"
                                        style="margin-top:7%; width:100%;"><b>Terapkan</b></button>
                                </div>
                            @endif
                            <!--  ---------------- Jangan buat kolom baru. Sudah ada tabel lain sempurnakan ---------- -->
                            {{-- <div class="teks-primer teks-kecil" style="padding: 25px;padding-top: 10px;">
                                <span class="pointer" onclick="copyText('https:\/\/akt.events/{{ $event->custom_slug }}', this)">Copy</span>
                            </div> --}}
                        </form>

                    </div>
                </div>
                <div class="card-box-lg mt-divid-card card-no-height card-no-border">
                    <div id="heigh-same" class="row" style="margin-top: 0px;">
                        <div class="col-lg-6 mb-2">
                            <div class="card-box-lg bg-putih rounded card-radius">
                                <i class="fa fa-user card-icon" aria-hidden="true"></i>
                                <p class="teks-tebal card-text-2">Total Sponsor</p>
                                <p class="teks-tebal card-text-4 card-1-attdn">{{ count($event->sponsors) }}</p>
                                <p id="sponsor-tiket" class="card-text-1">{{ count($event->sponsors) }} Sponsor di
                                    event</p>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="card-box-lg bg-putih rounded card-radius">
                                <i class="fa fa-user card-icon" aria-hidden="true"></i>
                                <p class="teks-tebal card-text-2">Total Exhibitors</p>
                                <p class="teks-tebal card-text-4 card-1-attdn">{{ count($event->exhibitors) }}</p>
                                <p id="exhibitor-tiket" class="card-text-1">{{ count($event->exhibitors) }}
                                    Exhibitor di event</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- <div class="bagi bagi-2" style="width:30%; margin-left:10%">
            <div class="wrap">
                <div class="bg-putih rounded" style="width: 300%; margin-top:15%; border: 1px solid lightgrey;">
                    <i class="fa fa-link" aria-hidden="true" style="color: #304156; padding:20px; font-size: 14pt;"></i>
                    <div class="teks-tebal" style="color: #304156; margin-left:10%; margin-top:-7%; font-size: 14pt;">
                        Event Link</div>
                    <div class="bagi bagi-2" style="width:70%; margin-left:20px;">
                        <input type="text" class="box" name="eventlink" style=""
                            placeholder="https://eventname.agendakota.id">
                    </div>
                    <div class="bagi bagi-2" style="width:20%; margin-top:-7%; margin-left:2%;;">
                        <button class="primer lebar-95 mt-5" style="border-radius:8px;">Copy</button>
                    </div>

                    <div class="bg-putih rounded" style="margin:5%; border: 1px solid lightgrey;">
                        <div class="teks-tebal"
                            style="color: #304156; padding-left:20px; font-size: 14pt; padding-top:20px;">Custom Event Link
                        </div>
                        <div class="mt-1" style="color: #828D99; padding-left:20px; font-size: 12pt;">Undang
                            audience ke eventmu dengan link unik!</div>
                        <div class="bagi bagi-2" style="width:60%; margin-left:20px; margin-bottom:5%;">
                            <input type="text" class="box" name="eventlink" style=""
                                placeholder="https://customlink.com">
                        </div>
                        <div class="bagi bagi-2" style="width:30%; margin-left:2%; margin-bottom:8%;">
                            <button class="btn btn-default"
                                style="border-radius:8px; color:#e5214f; /*#EB597B*/ margin-top:7%; width:100%;"><b>Terapkan</b></button>
                        </div>
                    </div>
                </div>
                @foreach ($eventdetails as $event)
                    <div class="mt-4">
                        <div class="bagi bagi-2">
                            <div class="bg-putih rounded" style="width: 280%; height:120px; border: 1px solid lightgrey;">
                                <i class="fa fa-user" aria-hidden="true"
                                    style="color: #304156; padding:20px; font-size: 14pt;"></i>
                                <div class="teks-tebal"
                                    style="color: #304156; margin-left:20%; margin-top:-17%; font-size: 14pt;">Total Sponsor
                                </div>
                                <div class="teks-tebal rata-tengah"
                                    style="color: #304156; padding-left:20px; padding-top:20px;font-size:32pt;">
                                    {{ count($event->sponsors) }}</div>
                            </div>
                        </div>
                        <div class="bagi bagi-2">
                            <div class="bg-putih rounded"
                                style="margin-left: 220%;width: 280%; height:120px; border: 1px solid lightgrey;">
                                <i class="fa fa-user" aria-hidden="true"
                                    style="color: #304156; padding:20px; font-size: 14pt;"></i>
                                <div class="teks-tebal"
                                    style="color: #304156; margin-left:20%; margin-top:-17%; font-size: 14pt;">Total
                                    Exhibitors</div>
                                <div class="teks-tebal rata-tengah"
                                    style="color: #304156; padding-left:20px; padding-top:20px;font-size:32pt;">
                                    {{ count($event->exhibitors) }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div> --}}

@endsection

@section('javascript')
    <script>
        function copyButton(idTarget) {
            /* Get the text field */
            var copyText = document.getElementById(idTarget);
            console.log(copyText.value);
            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */

            /* Copy the text inside the text field */
            navigator.clipboard.writeText(copyText.value);

            /* Alert the copied text */
            // alert("Copied the text: " + copyText.value);
            Swal.fire({
                title: 'Berhasil !!!',
                text: 'Link telah dicopy',
                icon: 'success',
                confirmButtonText: 'Ok'
            });
        }
    </script>
    <script>
        const copyText = (text, button) => {
            console.log(text);
            navigator.clipboard.writeText(text);
            button.innerText = "Link berhasil disalin!";
            setTimeout(() => {
                button.innerText = "Copy";
            }, 1900);
        }

        //To control height two card separated
        var height = document.getElementById('card-sales-type').offsetHeight;
        height += 21.333;
        var custom = height - 145;
        var targetSameHeight = document.getElementById('heigh-same');
        targetSameHeight.style.marginTop = custom + "px";

        pengunjung()

        function pengunjung() {
            var tiketQtty = [];
            var penjualan = [];
            var quantitiyTIketAwal = [];
            var arrTiketID = [];
            var arrPembelianTIket = [];

            @foreach ($eventdetails as $event)
                @foreach ($event->sessions as $session)
                    @foreach ($session->tickets as $ticket)
                        tiketQtty.push("{{ $ticket->quantity }}");
                        quantitiyTIketAwal.push("{{ $ticket->start_quantity }}");
                        arrTiketID.push("{{ $ticket->id }}");
                    @endforeach
                @endforeach
            @endforeach
            @foreach ($purchases as $purchase)
                penjualan.push("{{ $purchase->price }}");
                arrPembelianTIket.push("{{ $purchase->ticket_id }}");
            @endforeach

            //pengunjung (Pembuat asal masih salah)
            var totalTIket = 0;
            for (let i = 0; i < tiketQtty.length; i++) {
                totalTIket += parseInt(tiketQtty[i]);
            }

            var pengunjung = "{{ count($purchases) }}";
            var persentasePengunjung = (pengunjung / totalTIket) * 100;
            if (Number.isInteger(persentasePengunjung) == false) {
                persentasePengunjung = persentasePengunjung.toFixed(3);
            }
            var text = persentasePengunjung + "% dari " + totalTIket + " Pendaftar";
            document.getElementById('persentase-pengunjung').innerHTML = text;

            //penjualan
            var totalPenjualan = 0;
            for (let i = 0; i < penjualan.length; i++) {
                totalPenjualan += parseInt(penjualan[i]);
            }

            var rupiahTotalPenjualan = currencyRupiah(totalPenjualan);
            document.getElementById('total-penjualan').innerHTML = "IDR " + rupiahTotalPenjualan;
            document.getElementById('penjualan-tiket').innerHTML = pengunjung + " Tiket Terjual Dari " + totalTIket;


            //tiket
            for (let i = 0; i < arrTiketID.length; i++) {
                var pembelianTIket = 0;
                for (let j = 0; j < arrPembelianTIket.length; j++) {
                    if (arrTiketID[i] == arrPembelianTIket[j]) {
                        pembelianTIket += 1;
                    }
                }
                document.getElementById('proggresbar-tiket-' + arrTiketID[i]).innerHTML = pembelianTIket + " / " +
                    tiketQtty[i];

                //progressbar
                persentaseTiket = (pembelianTIket / tiketQtty[i]) * 100;
                document.getElementById('progressbar-' + arrTiketID[i]).style.width = persentaseTiket + "%";
            }
        }


    </script>
@endsection

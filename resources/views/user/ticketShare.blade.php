@extends('layouts.user')

@section('title', 'Share Tickets')

@section('head.dependencies')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/myTicketPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/cartTicket.css') }}">
@endsection

@section('content')
    @php
    use Carbon\Carbon;
    use App\Models\Invitation;
    $tanggalsekarang = Carbon::now()->toDateString();
    @endphp



    <div class="">
        <div class="bagi bagi-2 mb-3">
            <h2>Share Tickets</h2>
            <span class="teks-transparan">Bagi Bagikan Tiket Eventmu ke Temanmu</span>
        </div>
        @include('admin.partials.alert')
        {{-- Mode ini adalah mode ketika rentetan alur pembayaran page unpaid-lanjutkan pembayaran --}}
        @if ($mode == 'singlePay')
            <form action="{{ route('user.saveShare') }}" method="POST">
                <div class="row">
                    {{ csrf_field() }}
                    @foreach ($payments as $payment)
                        <div class="col-12">
                            <div class="bg-putih rounded-5 w-100 bayangan-5 p-4 pl-5 pr-5">
                                <h5>{{ $payment->order_id }}</h5>
                                <div class="row">
                                    @foreach ($payment->purchases as $purchase)
                                        <div class="ticket-2 col-lg-9 strip text-left mb-3">
                                            <div>

                                                <h1 class="ticket-name mt-1">
                                                    <b><?= $purchase->tickets->name ?></b>
                                                </h1>
                                                <p class="fs-16 mb-0">
                                                    <i
                                                        class="fa bi bi-star mr-3"></i><?= $purchase->tickets->session->event->name ?>
                                                </p>
                                                <p class="float-right text-right fs-16 text-danger">Give To : <br>
                                                    <input class="box rounded-5 no-bg bayangan-5" type="email"
                                                        name="gifts[]"
                                                        value="<?= $purchase->user_id == $myData->id ? $myData->email : $purchase->users->email ?>"
                                                        placeholder="Email recepient" required>
                                                </p>
                                                <input type="hidden" name="purchases[]" value="<?= $purchase->id ?>">
                                                <input type="hidden" name="payments[]" value="<?= $payment->id ?>">

                                                <p class="fs-14">
                                                    <i class="fas fa-calendar mr-3"></i>
                                                    <?= Carbon::parse($purchase->tickets->session->start_date)->format('d M,') ?>
                                                    <?= Carbon::parse($purchase->tickets->session->start_time)->format('H:i') ?>
                                                    WIB -
                                                    <?= Carbon::parse($purchase->tickets->session->end_date)->format('d M,') ?>
                                                    <?= Carbon::parse($purchase->tickets->session->end_time)->format('H:i') ?>
                                                    WIB
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
                                                        <?= Carbon::parse($purchase->tickets->session->event->start_date)->format('d M Y') ?>
                                                        -
                                                        <?= Carbon::parse($purchase->tickets->session->event->end_date)->format('d M Y') ?>
                                                    </p>
                                                </h4>

                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-center lebar-100">
                                    <a href="{{ route('user.cancelPay',[$payment->order_id]) }}" class="btn btn-danger lebar-50 rounded-8">
                                        Batalkan
                                    </a>
                                </div>
                                
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="float-right text-right mt-5 pb-5">
                    <button class="btn bg-primer" type="submit" name="submit">Lanjutkan Pembayaran</button>
                </div>
            </form>
        {{-- Ini adalah page share ticket setelah ticket terbeli --}}
        @else
            <div class="row">
                @foreach ($purchases as $purchase)
                    @php
                        $purchaseTime = $purchase->tickets->session->start_date;
                        $purchaseTimeEnd = $purchase->tickets->session->end_date;
                    @endphp
                    @if ($purchaseTime > $tanggalsekarang || ($purchaseTime <= $tanggalsekarang && $purchaseTimeEnd >= $tanggalsekarang))
                        @if ($purchase->tempFor != null && $purchase->tickets->deleted == 0)
                            @if ($purchase->tempFor->share_to == $myData->email )
                                <div class="col-12">
                                    <div class="bg-putih rounded-5 w-100 bayangan-5 p-4 pl-5 pr-5 mb-4">
                                        <form action="{{ route('user.changeShare') }}" method="POST">
                                            {{ csrf_field() }}
                                            <div class="row">

                                                <div class="ticket-2 col-lg-9 strip text-left mb-3">
                                                    <div>
                                                        <h1 class="ticket-name mt-1">
                                                            <b><?= $purchase->tickets->name ?></b>
                                                        </h1>
                                                        <p class="fs-16 mb-0">
                                                            <i
                                                                class="fa bi bi-star mr-3"></i><?= $purchase->tickets->session->event->name ?>
                                                        </p>
                                                        <p class="float-right text-right fs-16 text-danger">
                                                            <button class="btn teks-primer btn-no-pd">Share Now !</button>
                                                            <br>
                                                            <input type="hidden" name="purchaseID"
                                                                value="<?= $purchase->id ?>" required>
                                                            <input class="box rounded-5 no-bg bayangan-5" type="email"
                                                                name="sendTo" value="<?= $purchase->tempFor->share_to ?>"
                                                                placeholder="Email recepient" required>

                                                        </p>

                                                        <input type="hidden" name="purchases[]"
                                                            value="<?= $purchase->id ?>">

                                                        <p class="fs-14">
                                                            <i class="fas fa-calendar mr-3"></i>
                                                            <?= Carbon::parse($purchase->tickets->session->start_date)->format('d M,') ?>
                                                            <?= Carbon::parse($purchase->tickets->session->start_time)->format('H:i') ?>
                                                            WIB -
                                                            <?= Carbon::parse($purchase->tickets->session->end_date)->format('d M,') ?>
                                                            <?= Carbon::parse($purchase->tickets->session->end_time)->format('H:i') ?>
                                                            WIB
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
                                                        <h4>
                                                            <?= $purchase->tickets->session->event->name ?>
                                                            <br>
                                                            <p class="fs-14 mt-3">
                                                                <?= Carbon::parse($purchase->tickets->session->event->start_date)->format('d M Y') ?>
                                                                -
                                                                <?= Carbon::parse($purchase->tickets->session->event->end_date)->format('d M Y') ?>
                                                            </p>
                                                        </h4>

                                                    </label>

                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                @endforeach

                @foreach ($purchases as $purchase)
                    @php
                        $purchaseTime = $purchase->tickets->session->start_date;
                        $purchaseTimeEnd = $purchase->tickets->session->end_date;
                    @endphp
                    @if ($purchaseTime > $tanggalsekarang || ($purchaseTime <= $tanggalsekarang && $purchaseTimeEnd >= $tanggalsekarang))
                        @if ($purchase->tempFor != null && $purchase->tickets->deleted == 0)
                            @if ($purchase->tempFor->share_to != $myData->email)
                                <div class="col-12">
                                    <div class="bg-putih rounded-5 w-100 bayangan-5 p-4 pl-5 pr-5 mb-4">
                                        @if (isset($purchase->invitation))
                                            <h6>Waiting Accept Invitation by Recepient</h6>
                                        @else
                                            <h6>Waiting User Register</h6>
                                        @endif
                                        <form action="{{ route('user.changeShare') }}" method="POST">
                                            <div class="row">
                                                {{ csrf_field() }}
                                                <div class="ticket-2 col-lg-9 strip text-left mb-3">
                                                    <div>
                                                        <h1 class="ticket-name mt-1">
                                                            <b><?= $purchase->tickets->name ?></b>
                                                        </h1>
                                                        <p class="fs-16 mb-0">
                                                            <i
                                                                class="fa bi bi-star mr-3"></i><?= $purchase->tickets->session->event->name ?>
                                                        </p>
                                                        <p class="float-right text-right fs-16 text-danger">
                                                            @if (isset($purchase->invitation))
                                                                <button class="btn teks-primer btn-no-pd">Share Now
                                                                    !</button>
                                                            @else
                                                                <button disabled="" class="btn teks-primer btn-no-pd">Share
                                                                    Now
                                                                    !</button>
                                                            @endif

                                                            <br>

                                                            <input type="hidden" name="purchaseID"
                                                                value="<?= $purchase->id ?>" required>
                                                            <input class="box rounded-5 no-bg bayangan-5" type="email"
                                                                name="sendTo" value="<?= $purchase->tempFor->share_to ?>"
                                                                placeholder="Email recepient" required>

                                                        </p>

                                                        <input type="hidden" name="purchases[]"
                                                            value="<?= $purchase->id ?>">

                                                        <p class="fs-14">
                                                            <i class="fas fa-calendar mr-3"></i>
                                                            <?= Carbon::parse($purchase->tickets->session->start_date)->format('d M,') ?>
                                                            <?= Carbon::parse($purchase->tickets->session->start_time)->format('H:i') ?>
                                                            WIB -
                                                            <?= Carbon::parse($purchase->tickets->session->end_date)->format('d M,') ?>
                                                            <?= Carbon::parse($purchase->tickets->session->end_time)->format('H:i') ?>
                                                            WIB
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
                                                        <h4>
                                                            <?= $purchase->tickets->session->event->name ?>
                                                            <br>
                                                            <p class="fs-14 mt-3">
                                                                <?= Carbon::parse($purchase->tickets->session->event->start_date)->format('d M Y') ?>
                                                                -
                                                                <?= Carbon::parse($purchase->tickets->session->event->end_date)->format('d M Y') ?>
                                                            </p>
                                                        </h4>

                                                    </label>

                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                @endforeach

            </div>

        @endif
    </div>

@endsection

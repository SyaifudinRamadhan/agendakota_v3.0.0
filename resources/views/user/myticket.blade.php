@extends('layouts.user')

@section('title', 'My Tickets')

@section('head.dependencies')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/myTicketPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
@endsection

@section('content')
    @php
        use Carbon\Carbon;
        use App\Models\Invitation;
        $tanggalsekarang = Carbon::now()->toDateString();
    @endphp

    <div class="">
        <div class="bagi bagi-2 mb-3">
            <h2>My Tickets</h2>
            <span class="teks-transparan">Temukan Semua Tiket Event</span>
        </div>

    </div>

    <div class="tab scrollmenu" style="border: none; border-bottom: 1px solid #F0F1F2;">
        @if (count($carts) > 0)
            <button class="tab-btn tablinks-myTickets active" onclick="openTabs(event, 'myTickets', 'cart')">Ticket
                Cart</button>
        @endif

        @if (count($payments) > 0 && count($carts) == 0)
            <button class="tab-btn tablinks-myTickets active"
                onclick="openTabs(event, 'myTickets', 'notPay')">Unpaid</button>
        @elseif(count($payments) > 0 && count($carts) > 0)
            <button class="tab-btn tablinks-myTickets" onclick="openTabs(event, 'myTickets', 'notPay')">Unpaid</button>
        @endif

        @if (count($payments) == 0 && count($carts) == 0)
            <button class="tab-btn tablinks-myTickets active" onclick="openTabs(event, 'myTickets', 'All')">All</button>
        @else
            <button class="tab-btn tablinks-myTickets" onclick="openTabs(event, 'myTickets', 'All')">All</button>
        @endif
        <button class="tab-btn tablinks-myTickets" onclick="openTabs(event, 'myTickets', 'Free')">Free</button>
        <button class="tab-btn tablinks-myTickets" onclick="openTabs(event, 'myTickets', 'Paid')">Paid</button>
        <button class="tab-btn tablinks-myTickets" onclick="openTabs(event, 'myTickets', 'For-Friends')">For
            Friends</button>
    </div>

    <div id="card-mode" class="d-block">
        @if (count($carts) > 0)
            <div id="cart" class="cart tabcontent-myTickets" style="display: block; border: none;">
                <div class="mt-4">
                    @include('user.cart-ticket')
                </div>
            </div>
        @endif

        @if (count($payments) > 0 && count($carts) == 0)
            <div id="notPay" class="notPay tabcontent-myTickets" style="display: block; border: none;">
                <div class="mt-4">
                    @include('user.notPay')
                </div>
            </div>
        @elseif(count($payments) > 0 && count($carts) > 0)
            <div id="notPay" class="notPay tabcontent-myTickets" style="display: none; border: none;">
                <div class="mt-4">
                    @include('user.notPay')
                </div>
            </div>
        @endif
        <!-- <div id="cart" class="All tabcontent-myTickets" style="display: block; border: none;">
                <div class="mt-4">

                </div>
            </div> -->

        @if (count($payments) == 0 && count($carts) == 0)
            <div id="All" class="All tabcontent-myTickets pb-5" style="display: block; border: none;">
            @else
                <div id="All" class="All tabcontent-myTickets pb-5" style="display: none; border: none;">
        @endif
        <div class="mt-4">
            @forelse ($purchases as $purchase)

                @if (!isset($purchase->tempFor))
                    <script type="text/javascript">
                        console.log('{{ route('user.shareTickets2', [http_build_query(['myArray' => $purchase->payment->id])]) }}');
                        window.location.replace('{{ route('user.shareTickets2', [http_build_query(['myArray' => $purchase->payment->id])]) }}');
                    </script>
                @endif

                @if ($purchase->payment->pay_state == 'Terbayar')
                    <div class="bagi bagi-3 row-ticket mb-4">
                        <a href="{{ $purchase->users->name == $myData->name ? '/detailTicket/' . $purchase->id : '#' }}">
                            <div class="ticket" id="pilih-ticket" ticket-id="">
                                <div style="display: flex;">
                                    @if ($purchase->events->start_date > $tanggalsekarang)
                                        <i class="fas fa-tag teks-primer mr-2 mt-1"></i>
                                        <div class="teks-upcoming">
                                            <p class="teks-tebal" style="font-size: 75%!important; margin-top: 3px;">
                                                Upcoming</p>
                                        </div>
                                    @elseif ($purchase->events->start_date <= $tanggalsekarang && $purchase->events->end_date >= $tanggalsekarang)
                                        <i class="fas fa-tag teks-primer mr-2 mt-1"></i>
                                        <div class="teks-happening">
                                            <p class="teks-tebal">Happening</p>
                                        </div>
                                    @elseif ($purchase->events->start_date < $tanggalsekarang)
                                        <i class="fas fa-tag teks-primer mr-2 mt-1"></i>
                                        <div class="teks-finished">
                                            <p class="teks-tebal">Finished</p>
                                        </div>
                                    @endif

                                    @php
                                        $totalInviteUser = Invitation::where('ticket_id', $purchase->tickets->id)
                                            ->where('sender', $purchase->send_from)
                                            ->get();
                                        
                                        if (count($totalInviteUser) > 0) {
                                            //stage second
                                            if ($purchase->user_id != $purchase->send_from) {
                                                if ($purchase->send_from == $myData->id) {
                                                    echo '
                                                    <div class="teks-send-to ml-1" >
                                                        <p class="teks-tebal" style="font-size: 75%!important; margin-top: 3px;">' .
                                                        $purchase->users->name .
                                                        '</p>
                                                    </div>
                                                ';
                                                }
                                            }
                                        }
                                        
                                    @endphp

                                </div>

                                <h1 class="ticket-name mt-1">
                                    <b>{{ $purchase->tickets->name }}</b>
                                </h1>

                                <h3 class="price mt-0"><b>
                                        @if ($purchase->tickets->price == 0)
                                            Gratis
                                        @else
                                            <p class="font-16">@currencyEncode($purchase->tickets->price)</p>
                                        @endif
                                    </b></h3>
                                <p class="teks-transparan teks-kecil desc-ticket" style="position: absolute">
                                    {{ $purchase->tickets->description }}</p>
                                <div class="mt-5">
                                    <hr class="garis-tiket" style="color: lightgray;" />
                                    {{-- <a href="{{route('organization.event.session.zoomLink',[$purchase->events->organizer->id,$purchase->events->id,$purchase->tickets->session_id])}}"> --}}
                                    <a class="bawah-garis" href="">
                                        <p class="teks-gelap mb-0 teks-bawah-garis font-weight-bold">
                                            {{ $purchase->events->name }}</p>
                                    </a>
                                    <p class="teks-transparan teks-kecil">Diadakan Oleh
                                        <b>{{ $purchase->events->organizer->name }}</b></p>
                                </div>
                            </div>
                        </a>

                    </div>
                @endif
            @empty
                <div class="rata-tengah">
                    <i class="bi bi-tag teks-primer font-img"></i>
                    <h3>Mulai Bergabung di event - event menarik</h3>
                    <p>Temukan berbagai event menarik di AgendaKota</p>
                    <a href="{{ route('user.homePage') }}">
                        <button class="bg-primer">
                            Temukan Event
                        </button>
                    </a>
                </div>
            @endforelse
        </div>
    </div>
    <div id="Free" class="Free tabcontent-myTickets" style="display: none; border: none;">
        <div class="mt-4">
            @forelse ($purchases as $purchase)
                @if ($purchase->payment->pay_state == 'Terbayar')
                    @if ($purchase->tickets->price == 0)
                        <div class="bagi bagi-3 row-ticket mb-4">
                            <a href="{{ $purchase->users->name == $myData->name ? '/detailTicket/' . $purchase->id : '#' }}">
                                <div class="ticket" id="pilih-ticket" ticket-id="">

                                    <div style="display: flex;">
                                        @if ($purchase->events->start_date > $tanggalsekarang)
                                            <i class="fas fa-tag teks-primer mr-2 mt-1"></i>
                                            <div class="teks-upcoming">
                                                <p class="teks-tebal" style="font-size: 75%!important; margin-top: 3px;">
                                                    Upcoming</p>
                                            </div>
                                        @elseif ($purchase->events->start_date <= $tanggalsekarang && $purchase->events->end_date >= $tanggalsekarang)
                                            <i class="fas fa-tag teks-primer mr-2 mt-1"></i>
                                            <div class="teks-happening">
                                                <p class="teks-tebal">Happening</p>
                                            </div>
                                        @elseif ($purchase->events->start_date < $tanggalsekarang)
                                            <i class="fas fa-tag teks-primer mr-2 mt-1"></i>
                                            <div class="teks-finished">
                                                <p class="teks-tebal">Finished</p>
                                            </div>
                                        @endif

                                        @php
                                            $totalInviteUser = Invitation::where('ticket_id', $purchase->tickets->id)
                                                ->where('sender', $purchase->send_from)
                                                ->get();
                                            
                                            if (count($totalInviteUser) > 0) {
                                                //stage second
                                                if ($purchase->user_id != $purchase->send_from) {
                                                    if ($purchase->send_from == $myData->id) {
                                                        echo '
                                                            <div class="teks-send-to ml-1" >
                                                                <p class="teks-tebal" style="font-size: 75%!important; margin-top: 3px;">' .
                                                            $purchase->users->name .
                                                            '</p>
                                                            </div>
                                                        ';
                                                    }
                                                }
                                            }
                                            
                                        @endphp
                                    </div>

                                    <h1 class="ticket-name mt-1">
                                        <b>{{ $purchase->tickets->name }}</b>
                                    </h1>

                                    <h3 class="price mt-0"><b>
                                            @if ($purchase->tickets->price == 0)
                                                Gratis
                                            @else
                                                <p class="font-16">@currencyEncode($purchase->tickets->price)</p>
                                            @endif
                                        </b></h3>
                                    <p class="teks-transparan teks-kecil desc-ticket" style="position: absolute">
                                        {{ $purchase->tickets->description }}</p>
                                    <div class="mt-5">
                                        <hr class="garis-tiket" style="color: lightgray;" />
                                        {{-- <a href="{{route('organization.event.session.zoomLink',[$purchase->events->organizer->id,$purchase->events->id,$purchase->tickets->session_id])}}"> --}}
                                        <a class="bawah-garis" href="">
                                            <p class="teks-gelap mb-0 teks-bawah-garis font-weight-bold">
                                                {{ $purchase->events->name }}</p>
                                        </a>
                                        <p class="teks-transparan teks-kecil">Diadakan Oleh
                                            <b>{{ $purchase->events->organizer->name }}</b></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endif
            @empty
                <div class="rata-tengah">
                    <i class="bi bi-tag teks-primer font-img"></i>
                    <h3>Mulai Bergabung di event - event menarik</h3>
                    <p>Temukan berbagai event menarik di AgendaKota</p>
                    <a href="{{ route('user.homePage') }}">
                        <button class="bg-primer">
                            Temukan Event
                        </button>
                    </a>
                </div>
            @endforelse
        </div>
    </div>
    <div id="Paid" class="Paid tabcontent-myTickets" style="display: none; border: none;">
        <div class="mt-4">
            @forelse ($purchases as $purchase)
                @if ($purchase->payment->pay_state == 'Terbayar')
                    @if ($purchase->tickets->price > 0)
                        <div class="bagi bagi-3 row-ticket mb-4">
                            <a
                                href="{{ $purchase->users->name == $myData->name ? '/detailTicket/' . $purchase->id : '#' }}">
                                <div class="ticket" id="pilih-ticket" ticket-id="">

                                    <div style="display: flex;">
                                        @if ($purchase->events->start_date > $tanggalsekarang)
                                            <i class="fas fa-tag teks-primer mr-2 mt-1"></i>
                                            <div class="teks-upcoming">
                                                <p class="teks-tebal" style="font-size: 75%!important; margin-top: 3px;">
                                                    Upcoming</p>
                                            </div>
                                        @elseif ($purchase->events->start_date <= $tanggalsekarang && $purchase->events->end_date >= $tanggalsekarang)
                                            <i class="fas fa-tag teks-primer mr-2 mt-1"></i>
                                            <div class="teks-happening">
                                                <p class="teks-tebal">Happening</p>
                                            </div>
                                        @elseif ($purchase->events->start_date < $tanggalsekarang)
                                            <i class="fas fa-tag teks-primer mr-2 mt-1"></i>
                                            <div class="teks-finished">
                                                <p class="teks-tebal">Finished</p>
                                            </div>
                                        @endif

                                        @php
                                            $totalInviteUser = Invitation::where('ticket_id', $purchase->tickets->id)
                                                ->where('sender', $purchase->send_from)
                                                ->get();
                                            
                                            if (count($totalInviteUser) > 0) {
                                                //stage second
                                                if ($purchase->user_id != $purchase->send_from) {
                                                    if ($purchase->send_from == $myData->id) {
                                                        echo '
                                                            <div class="teks-send-to ml-1" >
                                                                <p class="teks-tebal" style="font-size: 75%!important; margin-top: 3px;">' .
                                                            $purchase->users->name .
                                                            '</p>
                                                            </div>
                                                        ';
                                                    }
                                                }
                                            }
                                            
                                        @endphp
                                    </div>

                                    <h1 class="ticket-name mt-1">
                                        <b>{{ $purchase->tickets->name }}</b>
                                    </h1>

                                    <h3 class="price mt-0"><b>
                                            @if ($purchase->tickets->price == 0)
                                                Gratis
                                            @else
                                                <p class="font-16">@currencyEncode($purchase->tickets->price)</p>
                                            @endif
                                        </b></h3>
                                    <p class="teks-transparan teks-kecil desc-ticket" style="position: absolute">
                                        {{ $purchase->tickets->description }}</p>
                                    <div class="mt-5">
                                        <hr class="garis-tiket" style="color: lightgray;" />
                                        {{-- <a href="{{route('organization.event.session.zoomLink',[$purchase->events->organizer->id,$purchase->events->id,$purchase->tickets->session_id])}}"> --}}
                                        <a class="bawah-garis" href="">
                                            <p class="teks-gelap mb-0 teks-bawah-garis font-weight-bold">
                                                {{ $purchase->events->name }}</p>
                                        </a>
                                        <p class="teks-transparan teks-kecil">Diadakan Oleh
                                            <b>{{ $purchase->events->organizer->name }}</b></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endif
            @empty
                <div class="rata-tengah">
                    <i class="bi bi-tag teks-primer font-img"></i>
                    <h3>Mulai Bergabung di event - event menarik</h3>
                    <p>Temukan berbagai event menarik di AgendaKota</p>
                    <a href="{{ route('user.homePage') }}">
                        <button class="bg-primer">
                            Temukan Event
                        </button>
                    </a>
                </div>
            @endforelse
        </div>
    </div>
    <div id="For-Friends" class="For-Friends tabcontent-myTickets" style="display: none; border: none;">
        <div class="mt-4">
            @forelse ($purchases as $purchase)
                @if ($purchase->payment->pay_state == 'Terbayar')
                    @php
                        $paramView = false;
                        
                        $totalInviteUser = Invitation::where('ticket_id', $purchase->tickets->id)
                            ->where('sender', $purchase->send_from)
                            ->get();
                        
                        if (count($totalInviteUser) > 0) {
                            //stage second
                            if ($purchase->user_id != $purchase->send_from) {
                                if ($purchase->send_from == $myData->id) {
                                    $paramView = true;
                                }
                            }
                        }
                        
                    @endphp

                    @if ($paramView == true)
                        <div class="bagi bagi-3 row-ticket mb-4">
                            <a
                                href="{{ $purchase->users->name == $myData->name ? '/detailTicket/' . $purchase->id : '#' }}">
                                <div class="ticket" id="pilih-ticket" ticket-id="">

                                    <div style="display: flex;">
                                        @if ($purchase->events->start_date > $tanggalsekarang)
                                            <i class="fas fa-tag teks-primer mr-2 mt-1"></i>
                                            <div class="teks-upcoming">
                                                <p class="teks-tebal" style="font-size: 75%!important; margin-top: 3px;">
                                                    Upcoming</p>
                                            </div>
                                        @elseif ($purchase->events->start_date <= $tanggalsekarang && $purchase->events->end_date >= $tanggalsekarang)
                                            <i class="fas fa-tag teks-primer mr-2 mt-1"></i>
                                            <div class="teks-happening">
                                                <p class="teks-tebal">Happening</p>
                                            </div>
                                        @elseif ($purchase->events->start_date < $tanggalsekarang)
                                            <i class="fas fa-tag teks-primer mr-2 mt-1"></i>
                                            <div class="teks-finished">
                                                <p class="teks-tebal">Finished</p>
                                            </div>
                                        @endif

                                        <div class="teks-send-to ml-1">
                                            <p class="teks-tebal" style="font-size: 75%!important; margin-top: 3px;">
                                                {{ $purchase->users->name }}</p>
                                        </div>


                                    </div>

                                    <h1 class="ticket-name mt-1">
                                        <b>{{ $purchase->tickets->name }}</b>
                                    </h1>

                                    <h3 class="price mt-0"><b>
                                            @if ($purchase->tickets->price == 0)
                                                Gratis
                                            @else
                                                <p class="font-16">@currencyEncode($purchase->tickets->price)</p>
                                            @endif
                                        </b></h3>
                                    <p class="teks-transparan teks-kecil desc-ticket" style="position: absolute">
                                        {{ $purchase->tickets->description }}</p>
                                    <div class="mt-5">
                                        <hr class="garis-tiket" style="color: lightgray;" />
                                        {{-- <a href="{{route('organization.event.session.zoomLink',[$purchase->events->organizer->id,$purchase->events->id,$purchase->tickets->session_id])}}"> --}}
                                        <a class="bawah-garis" href="">
                                            <p class="teks-gelap mb-0 teks-bawah-garis font-weight-bold">
                                                {{ $purchase->events->name }}</p>
                                        </a>
                                        <p class="teks-transparan teks-kecil">Diadakan Oleh
                                            <b>{{ $purchase->events->organizer->name }}</b></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endif
            @empty
                <div class="rata-tengah">
                    <i class="bi bi-tag teks-primer font-img"></i>
                    <h3>Mulai Bergabung di event - event menarik</h3>
                    <p>Temukan berbagai event menarik di AgendaKota</p>
                    <a href="{{ route('user.homePage') }}">
                        <button class="bg-primer">
                            Temukan Event
                        </button>
                    </a>
                </div>
            @endforelse
        </div>
    </div>
    </div>

@endsection

<script>
    // bindDivWithImage();
    // const toggleView = btn => {
    //     let mode = btn.getAttribute('mode');
    //     selectAll(".toggle-view-button").forEach(button => {
    //         button.classList.remove('active');
    //     });
    //     btn.classList.add('active');
    //     selectAll(".list-item").forEach(item => {
    //         if (mode == "list") {
    //             item.classList.add("is-list-mode");
    //         }else {
    //             item.classList.remove("is-list-mode");
    //         }
    //     })
    // }
</script>

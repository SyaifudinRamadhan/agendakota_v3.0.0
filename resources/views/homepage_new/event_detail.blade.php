@extends('layouts.homepage_new')

@section('title', "Event Detail")

@php
    use Carbon\Carbon;
@endphp

@section('head.dependencies')
<link rel="stylesheet" href="{{ asset ('css/detail.css') }}">
@endsection
    
@section('content')
<input type="hidden" id="event" value="{{ $event }}">
<div id="ContentControls" class="flex row item-center justify-center bg-white ScrollableArea" style="height: 60px;">
    <div class="tab-item flex grow-1 justify-center item-center border-bottom h-50 pointer" target="Overview">Overview</div>
    <div class="tab-item flex grow-1 justify-center item-center border-bottom h-50 pointer" target="Schedule">Schedule</div>
    <div class="tab-item flex grow-1 justify-center item-center border-bottom h-50 pointer" target="Tickets">Tickets</div>
    <div class="tab-item flex grow-1 justify-center item-center border-bottom h-50 pointer" target="Speakers">Speakers</div>
    <div class="tab-item flex grow-1 justify-center item-center border-bottom h-50 pointer" target="Sponsors">Sponsors</div>
    <div class="tab-item flex grow-1 justify-center item-center border-bottom h-50 pointer" target="Booths">Booths</div>
</div>
<section id="Upper" class="bg-primary transparent p-2 flex row">
    <div class="CoverArea" bg-image="/storage/event_assets/{{ $event->slug }}/event_logo/thumbnail/{{ $event->logo }}"></div>
    <div class="flex column grow-1 ml-2 InfoArea">
        <div class="bg-white rounded p-3 EventInfo">
            <h2 class="mt-0">{{ $display['event_name'] }}</h2>

            <div class="flex row item-center h-40">
                <div style="width: 40px"><i class="bx bx-calendar"></i></div>
                @if ($event->start_date == $event->end_date)
                    <div class="text">{{ Carbon::parse($event->start_date)->isoFormat('D MMM Y') }}</div>
                @else
                    <div class="text">
                        {{ Carbon::parse($event->start_date)->isoFormat('D MMM') }}
                        -
                        {{ Carbon::parse($event->end_date)->isoFormat('D MMM Y') }}
                    </div>
                @endif
            </div>
            <div class="flex row item-center h-40">
                <div style="width: 40px"><i class="bx bx-time"></i></div>
                <div class="text">
                    {{ Carbon::parse($event->start_time)->format('H:i') }} 
                    - 
                    {{ Carbon::parse($event->end_time)->format('H:i') }}  WIB
                </div>
            </div>
            <div class="flex row item-center h-40">
                <div style="width: 40px"><i class="bx bx-map"></i></div>
                <div class="text">
                    {!! $event->location !!}
                </div>
            </div>

            <div class="flex row item-center h-50 mt-2">
                <div class="w-50 flex row item-center pointer" onclick="modal('#ShareModal').show()">
                    <i class="bx bx-share text primary"></i>
                    <div class="text ml-2 primary">Bagikan Event</div>
                </div>
                <div class="flex row item-center justify-end grow-1">
                    <a href="#">
                        <i class="bx bxl-facebook ml-2 text big primary"></i>
                    </a>
                    <a href="#">
                        <i class="bx bxl-twitter ml-2 text big primary"></i>
                    </a>
                    <a href="#">
                        <i class="bx bxl-whatsapp ml-2 text big primary"></i>
                    </a>
                </div>
            </div>
        </div>

        <button class="primary w-100 rounded-max mt-2" onclick="scrollToTickets()">Beli Tiket</button>
    </div>
</section>

<section id="TheContent" class="flex row p-4 bg-white">
    <div class="w-70 pt-2 Content">
        <div class="flex row item-center ScrollableArea" style="height: 60px;">
            <div class="tab-item flex grow-1 border-bottom h-40 pl-2 pointer active" target="Overview">Overview</div>
            <div class="tab-item flex grow-1 border-bottom h-40 pl-2 pointer" target="Schedule">Schedule</div>
            <div class="tab-item flex grow-1 border-bottom h-40 pl-2 pointer" target="Tickets">Tickets</div>
            <div class="tab-item flex grow-1 border-bottom h-40 pl-2 pointer" target="Speakers">
                @if ($event->type == "Seminar" || $event->type == "Talkshow" || $event->type == "Conference" || $event->type == "Workshop" || $event->type == "Symposium")
                    Speakers
                @else
                    Performers
                @endif
            </div>
            @if (preg_match('/Sponsors/i', $event->breakdown))
                <div class="tab-item flex grow-1 border-bottom h-40 pl-2 pointer" target="Sponsors">Sponsors</div>
            @endif
            @if (preg_match('/Exihibitors/i', $event->breakdown))
                <div class="tab-item flex grow-1 border-bottom h-40 pl-2 pointer" target="Booths">Booths</div>
            @endif
        </div>

        <div class="tab-content pt-2 active " key="Overview">
            {!! $event->description !!}
        </div>

        <div class="tab-content pt-2" key="Schedule">
            @php
                $iSessions = 0;
            @endphp
            @foreach ($sessions as $date => $session)
                <div
                    tab-level="1"
                    class="tab-item border-bottom h-50 flex row item-center justify-center {{ $iSessions === 0 ? 'active' : '' }}" 
                    target="session_{{ $session->id }}">
                        {{ Carbon::parse($date)->isoFormat('DD MMM Y') }} 
                    </div>

                <div class="tab-content {{ $iSessions === 0 ? 'active' : '' }}" tab-level="1" key="session_{{ $session->id}}">
                    <div class="Session flex row item-center mt-2">
                        <div class="SessionPointer bordered rounded-max"></div>
                        <div class="flex column grow-1 ml-2" style="flex: 25%">
                            <h4>{{ $session->title }}</h4>
                            <div>{{ $session->start_time }} - {{ $session->end_time }}</div>
                        </div>
                        <div class="flex column grow-1 ml-1 lh-25" style="flex-basis: 70%">
                            {!! $session->description !!}
                        </div>
                        {{-- <div class="flex row justify-center grow-1 ml-1 text center item-center" style="flex-basis: 25%">
                            <img 
                                src="https://www.wwe.com/f/styles/wwe_large/public/all/2019/10/RAW_06202016rf_1606--3d3997f53e6f3e9277cd5a67fbd8f31f.jpg" 
                                class="h-70 squarize use-height rounded-max"
                            >
                            <div class="text bold mt-2">John Cena</div>
                            <div class="text muted small mt-05">Wrestler at WWE</div>
                        </div> --}}
                    </div>
                </div>
                @php
                    $iSessions++;
                @endphp
            @endforeach
        </div>

        <div class="tab-content" key="Tickets">
            <div class="flex row wrap item-center">
                @foreach ($event->sessions as $session)
                    @foreach ($session->tickets as $ticket)
                        <div class="TicketDisplay flex column grow-1" id="ticket_{{ $ticket->id }}">
                            <div class="HalfCircle LeftCircle"> </div>
                            <div class="HalfCircle RightCircle"> </div>
                            <div class="info">
                                <h4 class="m-0">{{ $ticket->name }}</h4>
                                <div class="text muted small mt-1">{{ $ticket->description }}</div>
                            </div>
                            <div class="flex row item-center mt-2">
                                <div class="flex column grow-1">
                                    <div class="price text small bold primary">@currencyEncode($ticket->price)</div>
                                    <div class="text small muted mt-05">
                                        {{ Carbon::parse($ticket->session->start_date)->isoFormat('DD MMM Y') }}
                                    </div>
                                </div>
                                <div class="w-20 flex row item-center">
                                    <div class="bordered text primary rounded h-30 flex row item-center justify-center squarize use-height pointer" onclick="setQuantity('decrease', '{{ $ticket }}')">
                                        -
                                    </div>
                                    <div class="ml-2 mr-2" id="ticket_quantity_{{ $ticket->id }}">0</div>
                                    <div class="bordered text primary rounded h-30 flex row item-center justify-center squarize use-height pointer" onclick="setQuantity('increase', '{{ $ticket }}')">
                                        +
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>

            <button class="w-100 primary mt-2" onclick="buyTicket()">Beli Tiket</button>
        </div>
        
        <div class="tab-content" key="Speakers">
            @if ($event->speakers->count() == 0)
                <p>Tidak ada data</p>
            @else
                <div class="flex row wrap">
                    @foreach ($event->speakers as $speaker)
                        <div class="SpeakerItem flex column grow-1 bordered rounded p-2 m-2 w-30">
                            <div class="flex row item-center">
                                <img 
                                    src="https://www.wwe.com/f/styles/wwe_large/public/all/2019/10/RAW_06202016rf_1606--3d3997f53e6f3e9277cd5a67fbd8f31f.jpg"
                                    class="photo"
                                >
                                <div class="flex column grow-1 ml-2">
                                    <div class="text bold">{{ $speaker->name }}</div>
                                    <div class="text muted small mt-05">{{ $speaker->job }} - {{ $speaker->company }}</div>
                                </div>
                            </div>
                            <div class="flex row item-center justify-end border-top mt-2 pt-2">
                                <a href="#">
                                    <i class="bx bxl-instagram"></i>
                                </a>
                                <a href="#">
                                    <i class="bx bxl-linkedin"></i>
                                </a>
                                <a href="#">
                                    <i class="bx bxl-instagram"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
        </div>

        <div class="tab-content" key="Booths">
            <div class="flex row wrap item-center">
                @for ($i = 0; $i < 13; $i++)
                    <div class="BoothItem m-2 bordered flex column grow-1 relative" style="flex-basis: 25%;">
                        <img src="{{ asset('images/category/Atraction%20&%20Themepark.jpg') }}" class="BoothCover">
                        <div class="text center BoothIcon">
                            <img src="https://company.agendakota.id/wp-content/uploads/2022/03/cropped-logoApp.png">
                        </div>

                        <div class="p-2 pt-1 text center">
                            <div class="text bold">Agendakota</div>
                            <div class="text small muted mt-05">Media Partner</div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <div class="tab-content" key="Sponsors">
            <div class="flex row wrap">
                @foreach ($event->sponsors->sortBy('priority') as $sponsor)
                    <div class="flex column grow-1 p-1 mt-1" style="flex-basis: 32%;max-width: 33.33%;">
                        <div class="bordered rounded pt-1 pb-1">
                            <div class="bg-primary p-1 w-50 text small" style="border-top-right-radius: 99px;border-bottom-right-radius: 99px;">
                                {{ $sponsor->type }}
                            </div>
                            <img src="{{ asset('storage/event_assets/' . $event->slug . '/sponsor_logo/' . $sponsor->logo) }}" class="w-100 squarize rectangle mt-2" alt="">
                            <div class="p-2 pb-1">
                                <a href="{{ $sponsor->website }}" target="_blank" class="text primary">
                                    See About Sponsor
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="OrganizerArea bordered rounded ml-4 p-3 flex column grow-1 bg-white squarize rectangle" style="max-width: 450px;">
        <div class="flex row item-center">
            <img src="https://agendakota.id/storage/organization_logo/logo%20agenda%20kota.png" class="squarize use-height h-50 rounded-max" alt="">
            <div class="ml-2">
                <div class="text muted small">Diselenggarakan oleh</div>
                <div class="text bold big mt-05">{{ $event->organizer->name }}</div>
            </div>
        </div>
        <div class="mt-2">
            {{ $event->organizer->description }}
            <div class="flex row item-center justify-center border-top mt-2 pt-2">
                <div class="flex row item-center grow-1">
                    @if ($organizer->instagram != "")
                        <a href="{{ $organizer->instagram }}" class="bordered rounded h-30 squarize use-height flex row item-center justify-center text primary">
                            <i class="bx bxl-instagram"></i>
                        </a>
                    @endif
                    @if ($organizer->twitter != "")
                        <a href="{{ $organizer->twitter }}" class="bordered rounded h-30 squarize use-height flex row item-center justify-center text primary ml-1">
                            <i class="bx bxl-twitter"></i>
                        </a>
                    @endif
                    @if ($organizer->linked != "")
                        <a href="{{ $organizer->linked }}" class="bordered rounded h-30 squarize use-height flex row item-center justify-center text primary ml-1">
                            <i class="bx bxl-linkedin"></i>
                        </a>
                    @endif
                    @if ($organizer->website != "")
                        <a href="{{ $organizer->website }}" class="bordered rounded h-30 squarize use-height flex row item-center justify-center text primary ml-1">
                            <i class="bx bx-globe"></i>
                        </a>
                    @endif
                </div>
                {{-- <a href="{{ route('user.organizationDetail', $event->organizer->slug) }}" class="text small primary" target="_blank">
                    See More Details
                </a> --}}
            </div>
        </div>
    </div>
</section>

<div class="modal" id="ShareModal">
    <div class="modal-body">
        <div class="modal-content">
            <div class="flex row item-center mb-2">
                <h3 class="text m-0 flex grow-1">Bagikan Event</h3>
                <div class="bg-grey rounded-max h-30 squarize use-height flex item-center justify-center pointer" onclick="modal('#ShareModal').hide()">
                    <i class="bx bx-x"></i>
                </div>
            </div>
            <div class="p-2 bordered rounded flex row item-center">
                <div class="text small primary flex grow-1 mr-1" id="url"></div>
                <div class="pointer" onclick="shareEvent('copy')">
                    <i class="bx bx-copy"></i>
                </div>
            </div>

            <div class="flex row item-center justify-center mt-2">
                <div onclick="shareEvent('facebook')" class="m-1 h-40 squarize use-height rounded-max flex item-center justify-center pointer" style="background: #4267B2">
                    <i class="bx bxl-facebook text white"></i>
                </div>
                <div onclick="shareEvent('twitter')" class="m-1 h-40 squarize use-height rounded-max flex item-center justify-center pointer" style="background: #1DA1F2">
                    <i class="bx bxl-twitter text white"></i>
                </div>
                <div onclick="shareEvent('whatsapp')" class="m-1 h-40 squarize use-height rounded-max flex item-center justify-center pointer" style="background: #25D366">
                    <i class="bx bxl-whatsapp text white"></i>
                </div>
                <div onclick="shareEvent('linkedin')" class="m-1 h-40 squarize use-height rounded-max flex item-center justify-center pointer" style="background: #0077b5">
                    <i class="bx bxl-linkedin text white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="ReviewModal">
    <div class="modal-body">
        <div class="modal-content">
            <div class="flex row item-center mb-2">
                <h3 class="text m-0 flex grow-1">Review Tiketmu</h3>
                <div class="bg-grey rounded-max h-30 squarize use-height flex item-center justify-center pointer" onclick="modal('#ReviewModal').hide()">
                    <i class="bx bx-x"></i>
                </div>
            </div>

            <div class="text mb-2 small">Tuliskan alamat email yang akan menerima tiketmu. Jika untuk kamu sendiri tulis alamat emailmu
                (<span id="myEmail"></span>)
            </div>
            <div id="render"></div>

            <button onclick="checkoutTicket()" class="primary mt-2 w-100">Checkout</button>
        </div>
    </div>
</div>

@include('partials.CreateEvent.LoginModal')
@endsection

@section('javascript')
<script>
    let organizerArea = select(".OrganizerArea");
    let HeaderScrollEffect = screen.width > 480 ? 530 : 640;
    let ticketCarts = [];

    window.addEventListener('scroll', e => {
        let scroll = window.scrollY;
        if (scroll > HeaderScrollEffect) {
            select("#ContentControls").classList.add('active');
            select(".TopBar").style.top = "-40px";
        } else {
            select("#ContentControls").classList.remove('active');
            select(".TopBar").style.top = "0px";
        }
        if (screen.width > 480) {
            if (scroll > 480) {
                organizerArea.style.position = "fixed";
                organizerArea.style.right = "2.5%";
                organizerArea.style.top = "150px";
                organizerArea.style.width = "380px";
            } else {
                organizerArea.style.position = "static";
                organizerArea.style.width = "300px";
            }
        }
    })

    let event = JSON.parse(select("input#event").value);
    let eventURL = document.URL;
    select("#ShareModal #url").innerText = eventURL;

    const shareEvent = (to) => {
        let text = `Ikut event ${event.name} yuk! ${eventURL}`;
        var params ="menubar=no,toolbar=no,status=no,width=570,height=570,top=100,left=100";
        if (to == 'copy') {
            copyText(text);
        } else {
            let target = '';
            if (to == 'facebook') {
                target = "https://web.facebook.com/sharer.php?u=" + encodeURI(eventURL);
            } else if (to == 'whatsapp') {
                target = "https://api.whatsapp.com/send?phone=&text=" + text;
            } else if (to == 'twitter') {
                target = "https://twitter.com/intent/tweet?url=" + encodeURI(eventURL) + "&text=" + text.split(eventURL)[0];
            } else if (to == "linkedin") {
                target = "https://linkedin.com/sharing/share-offsite/?url=" + encodeURI(eventURL);
            }
            window.open(target, "NewWindow", params);
        }
    }
    const isTicketExists = (ticketID) => {
        let isFound = false
        ticketCarts.forEach((ticket, t) => {
            if (ticketID == ticket.id) {
                isFound = t;
            }
        });
        return isFound;
    }
    const setQuantity = (action, ticket) => {
        ticket = JSON.parse(ticket);
        let ticketExistence = isTicketExists(ticket.id);
        if (ticketExistence !== false) {
            let theTicket = ticketCarts[ticketExistence];
            if (action == 'increase' && theTicket.quantity + 1 <= 5) {
                theTicket['quantity'] += 1;
            } else if (action == 'decrease' && theTicket.quantity - 1 >= 0) {
                theTicket['quantity'] -= 1;
            }
        } else {
            if (action == 'increase') {
                ticketCarts.push({
                    id: ticket.id,
                    give_to: [],
                    ticket,
                    quantity: 1
                })
            }
        }
        ticketCarts.forEach(tick => {
            select(`#ticket_quantity_${tick.id}`).innerText = tick.quantity;
            if (tick.quantity != 0) {
                select(`#ticket_${tick.id}`).classList.add('active');
            } else {
                ticketCarts.splice(ticketExistence, 1)
                select(`#ticket_${tick.id}`).classList.remove('active');
            }
        })
    }

    state = {
        google_action: 'login'
    };
    const customCallback = user => {
        console.log(user);
    }
    select("#modalLogin form").setAttribute('onsubmit', 'login(event, customCallback)')

    const renderTicketToReview = () => {
        select("#ReviewModal #render").innerHTML = "";
        select("#ReviewModal #myEmail").innerText = myData.email;
        ticketCarts.forEach(cart => {
            let toRender = `<div>Tiket <b>${cart.ticket.name}</b></div>`;
            for (let i = 1; i <= cart.quantity; i++) {
                toRender += `<div class="group">
                    <input type="text" oninput="changeGiveTo(this)" ticket-id="${cart.ticket.id}" input-index="${i - 1}" name="give_to" id="ticket_${cart.ticket.id}_${i}" placeholder="Masukkan alamat email penerima" />
                    <label for="ticket_${cart.ticket.id}_${i}">Tiket ke-${i} untuk :</label>
                </div>`;
            }
            Element("div")
            .render("#ReviewModal #render", toRender);
        })
    }
    const changeGiveTo = input => {
        let ticketID = input.getAttribute('ticket-id');
        let ticketIndex = isTicketExists(ticketID);
        let inputIndex  = input.getAttribute('input-index');
        ticketCarts[ticketIndex].give_to[inputIndex] = input.value;
    }
    const buyTicket = () => {
        console.log(ticketCarts);
        if (myData == null) {
            modal("#modalLogin").show();
        } else {
            modal("#ReviewModal").show();
            renderTicketToReview();
        }
    }
    const checkoutTicket = () => {
        post(`/api/event/${event.id}/ticket/checkout`, {
            token: myData.token,
            tickets: ticketCarts,
            event_id: event.id
        })
        .then(res => {
            // console.log(res);
            if (res.payment.payment_link != null) {
                window.location = res.payment.payment_link;
            } else {
                window.location = '/my-tickets';
            }
        })
    }
    const scrollToTickets = () => {
        scrollTo(".tab-content[key='Tickets']");
        select(".tab-item[target='Tickets']").click();
    }
</script>
@endsection
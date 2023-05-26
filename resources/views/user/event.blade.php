@extends('layouts.user')

@section('title', 'My Events')

@section('head.dependencies')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
@endsection

@section('content')
    @php
    use Carbon\Carbon;
    $tanggalsekarang = Carbon::now()->toDateString();
    @endphp
    <div class="bagi bagi-2 mb-3">
        <h2>Events</h2>
        <span class="text-secondary">Temukan semua event yang kamu hadiri</span>
    </div>
    <div class="row">

        <div class="tab col-md-8 scrollmenu" style="border: none; border-bottom: 1px solid #F0F1F2;">
            <button class="tab-btn tablinks-event active" onclick="openTabs(event, 'event', 'All')">All</button>
            <button class="tab-btn tablinks-event" onclick="openTabs(event, 'event', 'Upcoming')">Upcoming</button>
            <button class="tab-btn tablinks-event" onclick="openTabs(event, 'event', 'Happening')">Happening</button>
            <button class="tab-btn tablinks-event" onclick="openTabs(event, 'event', 'Finished')">Finished</button>
        </div>
        <div class="col-md-4">
            <div class="square toggle-view-button" mode="table" onclick="toggleView(this)">
                <i class="fa fa-bars"></i>
            </div>
            <div class="square active toggle-view-button" mode="card" onclick="toggleView(this)">
                <i class="fa fa-th-large"></i>
            </div>
        </div>
    </div>


    <div id="card-mode" class="d-block">
        <div class="All tabcontent-event" style="display: block; border: none;">
            <div class="mt-4">
                @forelse ($purchases as $purchase)
                    @php
                        $status = "";
                        if ($purchase->events->start_date > $tanggalsekarang) {
                            $status = "Upcoming";
                        } else if ($purchase->events->start_date <= $tanggalsekarang && $purchase->events->end_date >= $tanggalsekarang) {
                            $status = 'Happening';
                        } else if ($purchase->events->start_date < $tanggalsekarang) {
                            $status = 'Finished';
                        }
                    @endphp
                    <div class="bagi bagi-3 list-item">
                        <div class="wrap">
                            <div class="event-item grid">
                                <div class="img-card-top"
                                    bg-image="{{ asset('storage/event_assets/' . $purchase->events->slug . '/event_logo/thumbnail/' . $purchase->events->logo) }}">
                                </div>
                                <div class="detail smallPadding">
                                    <div class="wrap">
                                        <div class="wrap">
                                            <div class="teks-{{ strtolower($status) }}">
                                                <p class="teks-tebal">{{ $status }}</p>
                                            </div>
                                        </div>
                                        <h4 class="title-card-event" style="position: relative">
                                            {{ $purchase->events->name }}</h4>
                                        <div class="desc-card-event teks-transparan organizer">Diadakan oleh
                                            <b>{{ $purchase->events->organizer->name }}</b></div>
                                        <div class="date date-card-event">
                                            <i class="fas fa-calendar"></i> &nbsp;
                                            {{ Carbon::parse($purchase->events->start_date)->format('d M,') }}
                                            {{ Carbon::parse($purchase->events->start_time)->format('H:i') }} WIB -
                                            {{ Carbon::parse($purchase->events->end_date)->format('d M,') }}
                                            {{ Carbon::parse($purchase->events->end_time)->format('H:i') }} WIB
                                        </div>
                                        @if ($status == "Finished" && $purchase->events->certificate != null)
                                            <button class="mt-2 w-100" onclick="generateCertificate('{{ $purchase->events->certificate }}', {{ json_encode($purchase->events->slug) }})">Download Certificate</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rata-tengah">
                        <img src="{{ asset('images/calendar.png') }}">
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
        <div class="Upcoming tabcontent-event" style="display: none; border: none;">
            <div class="mt-4">
                @forelse ($purchases as $purchase)
                    @if ($purchase->events->start_date > $tanggalsekarang)
                        <div class="bagi bagi-3 list-item">
                            <div class="wrap">
                                <div class="event-item grid">
                                    <div class="img-card-top"
                                        bg-image="{{ asset('storage/event_assets/' . $purchase->events->slug . '/event_logo/thumbnail/' . $purchase->events->logo) }}">
                                    </div>
                                    <div class="detail smallPadding">
                                        <div class="wrap">
                                            <div class="wrap">
                                                <div class="teks-upcoming">
                                                    <p class="teks-tebal">Upcoming</p>
                                                </div>
                                            </div>
                                            <h4 class="title-card-event">{{ $purchase->events->name }}</h4>
                                            <div class="desc-card-event teks-transparan organizer">Diadakan oleh
                                                <b>{{ $purchase->events->organizer->name }}</b></div>
                                            <div class="date date-card-event">
                                                <i class="fas fa-calendar"></i> &nbsp;
                                                {{ Carbon::parse($purchase->events->start_date)->format('d M,') }}
                                                {{ Carbon::parse($purchase->events->start_time)->format('H:i') }} WIB -
                                                {{ Carbon::parse($purchase->events->end_date)->format('d M,') }}
                                                {{ Carbon::parse($purchase->events->end_time)->format('H:i') }} WIB
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="rata-tengah">
                        <img src="{{ asset('images/calendar.png') }}">
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
        <div class="Happening tabcontent-event" style="display: none; border: none;">
            <div class="mt-4">
                @forelse ($purchases as $purchase)
                    @if ($purchase->events->start_date <= $tanggalsekarang && $purchase->events->end_date >= $tanggalsekarang)
                        <div class="bagi bagi-3 list-item">
                            <div class="wrap">
                                <div class="event-item grid">
                                    <div class="img-card-top"
                                        bg-image="{{ asset('storage/event_assets/' . $purchase->events->slug . '/event_logo/thumbnail/' . $purchase->events->logo) }}">
                                    </div>
                                    <div class="detail smallPadding">
                                        <div class="wrap">
                                            <div class="wrap">
                                                <div class="teks-happening">
                                                    <p class="teks-tebal">Happening</p>
                                                </div>
                                            </div>
                                            <h4 class="title-card-event">{{ $purchase->events->name }}</h4>
                                            <div class="desc-card-event teks-transparan organizer">Diadakan oleh
                                                <b>{{ $purchase->events->organizer->name }}</b></div>
                                            <div class="date date-card-event">
                                                <i class="fas fa-calendar"></i> &nbsp;
                                                {{ Carbon::parse($purchase->events->start_date)->format('d M,') }}
                                                {{ Carbon::parse($purchase->events->start_time)->format('H:i') }} WIB -
                                                {{ Carbon::parse($purchase->events->end_date)->format('d M,') }}
                                                {{ Carbon::parse($purchase->events->end_time)->format('H:i') }} WIB
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="rata-tengah">
                        <img src="{{ asset('images/calendar.png') }}">
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
        <div class="Finished tabcontent-event" style="display: none; border: none;">
            <div class="mt-4">
                @forelse ($purchases as $purchase)
                    @if ($purchase->events->start_date < $tanggalsekarang)
                        <div class="bagi bagi-3 list-item">
                            <div class="wrap">
                                <div class="event-item grid">
                                    <div class="img-card-top"
                                        bg-image="{{ asset('storage/event_assets/' . $purchase->events->slug . '/event_logo/thumbnail/' . $purchase->events->logo) }}">
                                    </div>
                                    <div class="detail smallPadding">
                                        <div class="wrap">
                                            <div class="wrap">
                                                <div class="teks-finished">
                                                    <p class="teks-tebal">Finished</p>
                                                </div>
                                            </div>
                                            <h4 class="title-card-event">{{ $purchase->events->name }}</h4>
                                            <div class="desc-card-event teks-transparan organizer">Diadakan oleh
                                                <b>{{ $purchase->events->organizer->name }}</b></div>
                                            <div class="date date-card-event">
                                                <i class="fas fa-calendar"></i> &nbsp;
                                                {{ Carbon::parse($purchase->events->start_date)->format('d M,') }}
                                                {{ Carbon::parse($purchase->events->start_time)->format('H:i') }} WIB -
                                                {{ Carbon::parse($purchase->events->end_date)->format('d M,') }}
                                                {{ Carbon::parse($purchase->events->end_time)->format('H:i') }} WIB
                                            </div>
                                            @if ($purchase->events->certificate != null)
                                                <button class="mt-2 w-100" onclick="generateCertificate('{{ $purchase->events->certificate }}', {{ json_encode($purchase->events->slug) }})">Download Certificate</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="rata-tengah">
                        <img src="{{ asset('images/calendar.png') }}">
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

    <div id="table-mode" class="table-mode d-none">
        <div class="All tabcontent-event table-block" style="display: block; border: none;">

            @if (count($purchases) == 0)
                <div class="mt-4 rata-tengah">
                    <img src="{{ asset('images/calendar.png') }}">
                    <h3>Mulai Bergabung di event - event menarik</h3>
                    <p>Temukan berbagai event menarik di AgendaKota</p>
                    <a href="{{ route('user.homePage') }}">
                        <button class="bg-primer">
                            Temukan Event
                        </button>
                    </a>
                </div>
            @else
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">Event Name</th>
                            <th scope="col">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                            <th scope="col">Host&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                            <th scope="col">Time&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody class="mt-2">
                        @foreach ($purchases as $purchase)
                            <tr>
                                <td><img class="img-table"
                                        src="{{ asset('storage/event_assets/' . $purchase->events->slug . '/event_logo/thumbnail/' . $purchase->events->logo) }}">
                                </td>
                                <td class="fontBold font-weight-bold">{{ $purchase->events->name }}</td>
                                <td>{{ $purchase->events->organizer->name }}</td>
                                <td>
                                    {{ Carbon::parse($purchase->events->start_date)->format('d M,') }}
                                    {{ Carbon::parse($purchase->events->start_time)->format('H:i') }} WIB -
                                    {{ Carbon::parse($purchase->events->end_date)->format('d M,') }}
                                    {{ Carbon::parse($purchase->events->end_time)->format('H:i') }} WIB
                                </td>
                                <td>
                                    @if ($purchase->events->start_date > $tanggalsekarang)
                                        <div class="table-teks-upcoming">
                                            <p class=" teks-tebal">Upcoming</p>
                                        </div>
                                    @elseif ($purchase->events->start_date <= $tanggalsekarang && $purchase->events->end_date >= $tanggalsekarang)
                                        <div class="table-teks-happening">
                                            <p class=" teks-tebal">Happening</p>
                                        </div>
                                    @elseif ($purchase->events->start_date < $tanggalsekarang)
                                        <div class="table-teks-finished">
                                            <p class="teks-tebal">Finished</p>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="Upcoming tabcontent-event table-block" style="display: none; border: none;">
            @if (count($purchases) == 0)
                <div class="mt-4 rata-tengah">
                    <img src="{{ asset('images/calendar.png') }}">
                    <h3>Mulai Bergabung di event - event menarik</h3>
                    <p>Temukan berbagai event menarik di AgendaKota</p>
                    <a href="{{ route('user.homePage') }}">
                        <button class="bg-primer">
                            Temukan Event
                        </button>
                    </a>
                </div>
            @else
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">Event Name</th>
                            <th scope="col">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                            <th scope="col">Host&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                            <th scope="col">Time&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody class="mt-2">
                        @foreach ($purchases as $purchase)
                            @if ($purchase->events->start_date > $tanggalsekarang)
                                <tr>
                                    <td><img class="img-table"
                                            src="{{ asset('storage/event_assets/' . $purchase->events->slug . '/event_logo/thumbnail/' . $purchase->events->logo) }}">
                                    </td>
                                    <td class="fontBold font-weight-bold">{{ $purchase->events->name }}</td>
                                    <td>{{ $purchase->events->organizer->name }}</td>
                                    <td>
                                        {{ Carbon::parse($purchase->events->start_date)->format('d M,') }}
                                        {{ Carbon::parse($purchase->events->start_time)->format('H:i') }} WIB -
                                        {{ Carbon::parse($purchase->events->end_date)->format('d M,') }}
                                        {{ Carbon::parse($purchase->events->end_time)->format('H:i') }} WIB
                                    </td>
                                    <td>
                                        <div class="table-teks-upcoming">
                                            <p class="teks-tebal">Upcoming</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="Happening tabcontent-event table-block" style="display: none; border: none;">
            @if (count($purchases) == 0)
                <div class="mt-4 rata-tengah">
                    <img src="{{ asset('images/calendar.png') }}">
                    <h3>Mulai Bergabung di event - event menarik</h3>
                    <p>Temukan berbagai event menarik di AgendaKota</p>
                    <a href="{{ route('user.homePage') }}">
                        <button class="bg-primer">
                            Temukan Event
                        </button>
                    </a>
                </div>
            @else
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">Event Name</th>
                            <th scope="col">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                            <th scope="col">Host&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                            <th scope="col">Time&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody class="mt-2">
                        @foreach ($purchases as $purchase)
                            @if ($purchase->events->start_date <= $tanggalsekarang && $purchase->events->end_date >= $tanggalsekarang)
                                <tr>
                                    <td><img class="img-table"
                                            src="{{ asset('storage/event_assets/' . $purchase->events->slug . '/event_logo/thumbnail/' . $purchase->events->logo) }}">
                                    </td>
                                    <td class="fontBold font-weight-bold">{{ $purchase->events->name }}</td>
                                    <td>{{ $purchase->events->organizer->name }}</td>
                                    <td>
                                        {{ Carbon::parse($purchase->events->start_date)->format('d M,') }}
                                        {{ Carbon::parse($purchase->events->start_time)->format('H:i') }} WIB -
                                        {{ Carbon::parse($purchase->events->end_date)->format('d M,') }}
                                        {{ Carbon::parse($purchase->events->end_time)->format('H:i') }} WIB
                                    </td>
                                    <td>
                                        <div class="table-teks-happening">
                                            <p class="teks-tebal">Happening</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="Finished tabcontent-event table-block" style="display: none; border: none;">
            @if (count($purchases) == 0)
                <div class="mt-4 rata-tengah">
                    <img src="{{ asset('images/calendar.png') }}">
                    <h3>Mulai Bergabung di event - event menarik</h3>
                    <p>Temukan berbagai event menarik di AgendaKota</p>
                    <a href="{{ route('user.homePage') }}">
                        <button class="bg-primer">
                            Temukan Event
                        </button>
                    </a>
                </div>
            @else
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">Event Name</th>
                            <th scope="col">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                            <th scope="col">Host&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                            <th scope="col">Time&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody class="mt-2">
                        @foreach ($purchases as $purchase)
                            @if ($purchase->events->start_date < $tanggalsekarang)
                                <tr>
                                    <td><img class="img-table"
                                            src="{{ asset('storage/event_assets/' . $purchase->events->slug . '/event_logo/thumbnail/' . $purchase->events->logo) }}">
                                    </td>
                                    <td class="fontBold font-weight-bold">{{ $purchase->events->name }}</td>
                                    <td>{{ $purchase->events->organizer->name }}</td>
                                    <td>
                                        {{ Carbon::parse($purchase->events->start_date)->format('d M,') }}
                                        {{ Carbon::parse($purchase->events->start_time)->format('H:i') }} WIB -
                                        {{ Carbon::parse($purchase->events->end_date)->format('d M,') }}
                                        {{ Carbon::parse($purchase->events->end_time)->format('H:i') }} WIB
                                    </td>
                                    <td>
                                        <div class="table-teks-finished">
                                            <p class="teks-tebal">Finished</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

@endsection

@section('javascript')
<script src="{{ asset('js/meme.js') }}"></script>
<script>
    const generateCertificate = (data, slug) => {
        data = JSON.parse(data);
        let position = data.name_position.split('|');
        let theFont = `${data.font_size}px ${data.font_family}`;
        if (data.font_weight != "normal") {
            theFont = `${data.font_weight.toLowerCase()} ${theFont}`;
        }
        let templateSource = `{{ asset('storage/event_assets') }}/${slug}/event_certificates/${data.filename}`;

        let meme = new MemeJS({
            width: 3508,
            height: 2480
        });
        meme.setTemplate(templateSource);
        
        meme.addText({
            text: "Nama Peserta",
            position: {
                x: position[0],
                y: position[1]
            },
            font: theFont
        });

        meme.download("Certificate");
    }
</script>
@endsection
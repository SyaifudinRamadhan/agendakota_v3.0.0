@extends('layouts.user')

@section('title', 'Session')

@section('head.dependencies')
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/sessionsPage.css') }}">
@endsection

{{-- -------- Parameter List untuk menentukan batas pcakage user ------------------ --}}
@php
    $nowDt = new DateTime();
    $startLimit = new DateTime($organization->user->created_at);
    $different = $startLimit->diff($nowDt);
    $pkgActive = \App\Http\Controllers\PackagePricingController::limitCalculator($organization->user);
    
@endphp
{{-- ------------------------------------------------------------------------------ --}}


@php
    use Carbon\Carbon;
    use Carbon\CarbonPeriod;
    $period = CarbonPeriod::create($event->start_date, $event->end_date);
    $loopSession = 0;
@endphp

@section('content')
    <div class="mb-2">
        <div class="row">
            <div class="col-lg-7 mb-4">
                <h2 style="margin-top: -3%; color: #304156; font-size:32px">Stage & Session</h2>
                <h4 class="mt-2" style="color: #979797; font-size:14">Create and manage sessions for event stage
                </h4>
                {{-- <div class="teks-transparan">{{ $event->name }}</div> --}}
            </div>
            <div class="col-lg-5 pl-0">
                <button type="button" class="bg-primer mt-0 btn-add btn-no-pd mt-2 float-right mr-2 mb-4"
                    onclick="confirmMyPkg()">
                    <i class="fa fa-plus"></i> Session
                </button>
                <div class="square toggle-view-button" mode="table" onclick="toggleView(this)">
                    <i class="fa fa-bars"></i>
                </div>
                <div class="square active toggle-view-button" mode="card" onclick="toggleView(this)">
                    <i class="fa fa-th-large"></i>
                </div>

            </div>
        </div>
    </div>

    @include('admin.partials.alert')
    {{-- Notifikasi jika ada waktu yang tidak sesuai antara session dan event --}}
    @if (count($notMatchConfig) > 0 || count($sessionRundownDelData) > 0)
        <div class="smallPadding">
            <div class="alert alert-danger" role="alert">
                Terdeteksi beberapa sesi melewati interval waktu dari pelaksanaan event.
            </div>
            @if (count($sessionRundownDelData) > 0)
                <div class="alert alert-danger" role="alert">
                    Terdeteksi beberapa sesi kehilangan data rundown waktu.
                </div>
            @endif
        </div>
        <div id="notice" class="card mb-3 mt-1">
            <div class="card-header bg-danger text-light">
                <div class="row w-100">
                    <div class="col-8 text-left">
                        <h5>*PERINGATAN</h5>
                    </div>
                    <div id="close" class="col-4 text-right">
                        <a class="pointer">
                            <i class="bi bi-caret-up-fill text-light"></i> Tutup
                        </a>
                    </div>
                    <div id="open" class="col-4 text-right d-none">
                        <a class="pointer">
                            <i class="bi bi-caret-down-fill text-light"></i> Buka
                        </a>
                    </div>
                </div>
            </div>
            <div id="body-card" class="card-body">
                <h5 class="card-title">Segera perbaiki ke-tidak cocokan tanggal dan waktu sesi berikut ini :</h5>
                @foreach ($notMatchConfig as $notMatch)
                    <p class="text-danger">
                        *{{ $notMatch->title }}
                        ->{{ '(' .
                            Carbon::parse($notMatch->start_date)->format('d M,') .
                            ' ' .
                            Carbon::parse($notMatch->start_time)->format('H:i') .
                            'WIB -' .
                            Carbon::parse($notMatch->end_date)->format('d M,') .
                            ' ' .
                            Carbon::parse($notMatch->end_time)->format('H:i') .
                            'WIB)' }}
                    </p>
                @endforeach
                <hr>
                @foreach ($sessionRundownDelData as $item)
                    <p class="text-danger">*{{ $item->title }} sesi, mulai atau akhir dari rundown sesi telah dihapus</p>
                @endforeach
                {{-- <p class="text-danger">
                    Mengubah tannggal dan waktu event pada event yang memiliki stage and session, 
                    kamu diwajibkan untuk mengubah data tanggal dan waktu di rundown dan stage & session jika 
                    sudah pernah dibuat
                </p> --}}
            </div>
        </div>
    @endif

    <div id="card-mode" class="d-block">
        @if (count($sessions) == 0)
            <div class="mt-4 rata-tengah">
                <i class="bi bi-camera font-img teks-primer"></i>
                <h3>Mulai Membuat Stage & Session untuk Eventmu</h3>
                <p>Adakan berbagai event menarik di AgendaKota</p>
            </div>
        @else
            <div class="row">
                @foreach ($sessions as $session)
                    <div class="col-lg-4 mb-4">
                        <div class="bg-putih bayangan-5 lebar-100 box-card">
                            <div class="teks-tebal" style="padding:5%;">
                                <div class="row">
                                    <div class="col-9 text-overflow">
                                        {{ $session->title }}
                                    </div>
                                    <div class="col-3">
                                        <i class="fa fa-ellipsis-h pointer dropdownToggle" data-id="{{ $session->id }}"
                                            style=" color: #C4C4C4; font-size:20pt; float: right;" aria-hidden="true"></i>

                                    </div>
                                </div>

                                <div id="Dropdown{{ $session->id }}" class="dropdown-content">
                                    <a>
                                        <span class="teks-hijau pointer" onclick='edit(<?= json_encode($session) ?>)'>
                                            <i class="fas fa-edit"></i> Edit
                                        </span>
                                    </a>
                                    <a href="{{ route('organization.event.session.delete', [$organizationID, $event->id, $session->id]) }}"
                                        onclick="onDelete({{ $sessionBuyed[$loopSession] }})">
                                        <span class="teks-merah pointer">
                                            <i class="fas fa-trash"></i> Delete
                                        </span>
                                    </a>
                                    @if ($event->execution_type == 'online' || $event->execution_type == 'hybrid')
                                        <a target="_blank"
                                            href="{{ route('organization.event.session.url', [$organizationID, $event->id, $session->id]) }}">
                                            Join Stream</a>
                                        @if (preg_match('/rtmp-stream-key/i', $session->link))
                                            <a href="{{ route('organization.event.studio-stream', [$organizationID, $event->id, $session->id]) }}"
                                                class="btn btn-primary mt-3 text-light">
                                                Start Streaming
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="card-detail">
                                <div class="text-space-card text-overflow">
                                    <i class="fa fa-calendar card-icon"
                                        aria-hidden="true"></i><span>{{ Carbon::parse($session->start_date)->format('d M,') }}
                                        {{ Carbon::parse($session->start_time)->format('H:i') }} WIB -
                                        {{ Carbon::parse($session->end_date)->format('d M,') }}
                                        {{ Carbon::parse($session->end_time)->format('H:i') }} WIB</span>
                                </div>

                                <div class="text-overflow text-space-card">
                                    <i class="fa fa-users card-icon" aria-hidden="true"></i><span
                                        id="partisipan-{{ $session->id }}"></span>
                                </div>

                                @if ($event->execution_type == 'online' || $event->execution_type == 'hybrid')
                                    <div class="text-overflow">
                                        <i class="fa bi bi-camera-video-fill card-icon" aria-hidden="true"></i><span>
                                            <a
                                                href="{{ route('organization.event.session.url', [$organizationID, $event->id, $session->id]) }}">Going
                                                to Stream</a>
                                        </span>
                                        <div>
                                            @if (preg_match('/rtmp-stream-key/i', $session->link))
                                                <a href="{{ route('organization.event.studio-stream', [$organizationID, $event->id, $session->id]) }}"
                                                    class="btn btn-primary mt-3 text-light">
                                                    Start Streaming
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                <br><br>
                                <h4 class="card-desc">
                                    {{ $session->description }}</h4>
                            </div>
                        </div>
                    </div>
                    @php
                        $loopSession += 1;
                    @endphp
                @endforeach
            </div>
        @endif
    </div>

    <div id="table-mode" class="table-mode d-none table-responsive">
        @if (count($sessions) == 0)
            <div class="mt-4 rata-tengah">
                <i class="bi bi-camera font-img teks-primer"></i>
                <h3>Mulai Membuat Stage & Session untuk Eventmu</h3>
                <p>Adakan berbagai event menarik di AgendaKota</p>
            </div>
        @else
            <h3>
                <input type="search" placeholder="Search..." class="float-right form-control search-input"
                    style="width: unset" data-table="session-list" />
            </h3>
            <table class="table table-borderless session-list">
                <thead>
                    <tr>
                        <th>Tanggal Mulai&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Tanggal Selesai&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Judul&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th><i class="fas fa-clock"></i> Waktu / Jam&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                        </th>
                        @if ($event->execution_type == 'online' || $event->execution_type == 'hybrid')
                            <th>Streaming Link / Start Streaming&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        @endif
                        <th>Deskripsi&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        {{-- <th>Tampil Di Overview</th> --}}
                        <th>Action&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                    </tr>
                </thead>
                <tbody class="mt-2">
                    @php
                        $loopSession = 0;
                    @endphp
                    @foreach ($sessions as $session)
                        <tr>
                            <td>{{ Carbon::parse($session->start_date)->format('d M Y') }}</td>
                            <td>{{ Carbon::parse($session->start_end)->format('d M Y') }}</td>
                            <td>{{ $session->title }}</td>
                            <td>
                                {{ Carbon::parse($session->start_time)->format('H:i') }} -
                                {{ Carbon::parse($session->end_time)->format('H:i') }}
                            </td>
                            @if ($event->execution_type == 'online' || $event->execution_type == 'hybrid')
                                <td>
                                    <a
                                        href="{{ route('organization.event.session.url', [$organizationID, $event->id, $session->id]) }}">{{ substr($session->link, 0, 20) . '...' }}</a>
                                    @if (preg_match('/rtmp-stream-key/i', $session->link))
                                        <a href="{{ route('organization.event.studio-stream', [$organizationID, $event->id, $session->id]) }}"
                                            class="btn btn-primary mt-3">
                                            Start Streaming
                                        </a>
                                    @endif
                                </td>
                            @endif
                            <td>
                                {{ $session->description }}
                            </td>
                            {{-- <td>
                            @if ($session->overview == '0')
                                Tidak
                            @else
                                Ya
                            @endif
                        </td> --}}
                            <td>
                                <span class="teks-hijau pointer" onclick='edit(<?= json_encode($session) ?>)'>
                                    <i class="fas fa-edit"></i>
                                </span>

                                &nbsp;
                                <a href="{{ route('organization.event.session.delete', [$organizationID, $event->id, $session->id]) }}"
                                    class="teks-merah pointer" onclick="onDelete({{ $sessionBuyed[$loopSession] }})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @php
                            $loopSession += 1;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="bg"></div>
    <div class="popupWrapper" id="addSession">
        <div style="top: 70px" class="pl-2 pr-2">

        </div>
        <div class="lebar-50 popup rounded-5">
            <h3><i class="fas bi bi-x-circle-fill op-5 pr-3 mt-3 ke-kanan pointer"
                    onclick="hilangPopup('#addSession')"></i>
            </h3>
            <div class="pl-5 pr-5">

                <h3 class="mt-5 rata-tengah">Buat Session Baru</h3>
                <div class="wrap">

                    <form action="{{ route('organization.event.session.store', [$organizationID, $event->id]) }}"
                        method="POST">
                        {{ csrf_field() }}
                        <div class="mt-2">Judul Sesi :</div>
                        <input type="text" class="box no-bg" name="title" value="{{ old('title') }}">
                        <div class="row mt_-1">
                            <div class="col-xl-6 no-pd-l">
                                <div class="row">
                                    <div class="col-md-12 no-pd-l mt-3">
                                        <div class="mt-2">Mulai :</div>
                                        <select name="start_rundown" class="box no-bg" required id="date"
                                            oninvalid="this.setCustomValidity('Harap Pilih Tanggal Yang Ada di List')"
                                            oninput="setCustomValidity('')">
                                            <option value="{{ old('start_rundown') }}">
                                                {{ count($rundowns) > 0 && old('start_rundown') !== null
                                                    ? $rundowns[0]->where('id', old('start_rundown'))->first()->name .
                                                        ' : ' .
                                                        $rundowns[0]->where('id', old('start_rundown'))->first()->start_date .
                                                        '->' .
                                                        $rundowns[0]->where('id', old('start_rundown'))->first()->start_time
                                                    : '' }}
                                            </option>
                                            @foreach ($rundowns as $rundown)
                                                <option value="{{ $rundown->id }}">
                                                    {{ $rundown->name . ' : ' . $rundown->start_date . '->' . $rundown->start_time }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                            {{-- <div class="col-xl-2 no-pd-l mt-5 text-center justify-content-center d-flex">
                                <div class="col-md-4 mt-2 no-pd-l text-center pt-1"> s.d. </div>
                            </div> --}}
                            <div class="col-xl-6 no-pd-l">
                                <div class="row">
                                    <div class="col-md-12 no-pd-l mt-3">
                                        <div class="mt-2">Selesai :</div>
                                        <select name="end_rundown" class="box no-bg" required id="date"
                                            oninvalid="this.setCustomValidity('Harap Pilih Tanggal Yang Ada di List')"
                                            oninput="setCustomValidity('')">
                                            <option value="{{ old('end_rundown') }}">
                                                {{ count($rundowns) > 0 && old('end_rundown') !== null
                                                    ? $rundowns[0]->where('id', old('end_rundown'))->first()->name .
                                                        ' : ' .
                                                        $rundowns[0]->where('id', old('end_rundown'))->first()->end_date .
                                                        '->' .
                                                        $rundowns[0]->where('id', old('end_rundown'))->first()->end_time
                                                    : '' }}
                                            </option>
                                            @foreach ($rundowns as $rundown)
                                                <option value="{{ $rundown->id }}">
                                                    {{ $rundown->name . ' : ' . $rundown->end_date . '->' . $rundown->end_time }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @if ($event->execution_type == 'online' || $event->execution_type == 'hybrid')
                            <div id="add-popup">
                                <div class="mt-2">Streaming Options :</div>
                                <select class="custom-select" id="stream-option" name="streamOption">
                                    <option selected value="0">--- Belum ada yang dipilih ---</option>
                                    <option value="1">Streaming satu arah (RTMP)</option>
                                    <option value="2">Video Conference</option>
                                    <option value="3">Zoom Embed</option>
                                    <option value="4">YouTube Embed</option>
                                </select>
                                <input type="url" class="box no-bg" name="link" id="link"
                                    style="display: none"
                                    oninvalid="this.setCustomValidity('Harap Masukkan URL Dengan Benar')"
                                    value="{{ old('link') }}" oninput="setCustomValidity('')"
                                    placeholder="Link Zoom atau link Youtube">
                            </div>
                        @endif
                        <div class="mt-2">Deskripsi Session :</div>
                        <textarea name="description" class="box no-bg" placeholder="Deskripsi Session">{{ old('description') }}</textarea>

                        <div class="mt-2">
                            <div class="form-check d-none">
                                <input class="form-check-input no-bg" checked type="checkbox" value="1"
                                    id="defaultCheck1" name="overview" {{ old('overview') == '' ? '' : 'checked' }}>
                                <label class="form-check-label" for="defaultCheck1">
                                    Tampil Di Overview Event Detail
                                </label>
                            </div>
                        </div>
                        <div class="lebar-100 rata-tengah">
                            <button class="bg-primer mt-3">Buat Session</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="popupWrapper" id="editSession">
        <div class="lebar-50 popup rounded-5">
            <h3><i class="fas bi bi-x-circle-fill op-5 ke-kanan pr-3 mt-3 pointer"
                    onclick="hilangPopup('#editSession')"></i></h3>
            <div class="pl-5 pr-5">
                <h3 class="mt-5 rata-tengah">Edit Session</h3>
                <div class="wrap">

                    <form action="{{ route('organization.event.session.update', [$organizationID, $event->id]) }}"
                        method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="session_id" id="sessionID">
                        <div class="mt-2">Judul Sesi :</div>
                        <input type="text" class="box no-bg" name="title" id="title">

                        <div class="row mt_-1">
                            <div class="col-xl-6 no-pd-l">
                                <div class="row">
                                    <div class="col-md-12 no-pd-l mt-3">
                                        <div class="mt-2">Mulai :</div>
                                        <select name="start_rundown" class="box no-bg" required id="start_date"
                                            oninvalid="this.setCustomValidity('Harap Pilih Tanggal Yang Ada di List')"
                                            oninput="setCustomValidity('')">
                                            <option value=""></option>
                                            @foreach ($rundowns as $rundown)
                                                <option value="{{ $rundown->id }}">
                                                    {{ $rundown->name . ' : ' . $rundown->start_date . '->' . $rundown->start_time }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                            {{-- <div class="col-xl-2 no-pd-l mt-5 text-center justify-content-center d-flex">
                                <div class="mt-2 pt-1 text-center" style="font-size: 25pt;"> s.d. </div>
                            </div> --}}
                            <div class="col-xl-6 no-pd-l">
                                <div class="row">
                                    <div class="col-md-12 no-pd-l mt-3">
                                        <div class="mt-2">Selesai :</div>
                                        <select name="end_rundown" class="box no-bg" required id="end_date"
                                            oninvalid="this.setCustomValidity('Harap Pilih Tanggal Yang Ada di List')"
                                            oninput="setCustomValidity('')">
                                            <option value=""></option>
                                            @foreach ($rundowns as $rundown)
                                                <option value="{{ $rundown->id }}">
                                                    {{ $rundown->name . ' : ' . $rundown->end_date . '->' . $rundown->end_time }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @if ($event->execution_type == 'online' || $event->execution_type == 'hybrid')
                            <div id="edit-popup">
                                <div class="mt-2">Streaming Options :</div>
                                <select class="custom-select" id="stream-option" name="streamOption">
                                    <option selected value="0">--- Belum ada yang dipilih ---</option>
                                    <option value="1">Streaming satu arah (RTMP)</option>
                                    <option value="2">Video Conference</option>
                                    <option value="3">Zoom Embed</option>
                                    <option value="4">YouTube Embed</option>
                                </select>
                                <input type="url" class="box no-bg" name="link" id="link"
                                    style="display: none"
                                    oninvalid="this.setCustomValidity('Harap Masukkan URL Dengan Benar')"
                                    oninput="setCustomValidity('')"
                                    placeholder="Link Zoom atau link Youtube">
                            </div>
                        @endif

                        <div class="mt-2">Deskripsi Session :</div>
                        <textarea name="description" id="description" class="box no-bg" placeholder="Deskripsi Session"></textarea>

                        <div class="mt-2">
                            <div class="form-check d-none">
                                <input class="form-check-input" type="checkbox" checked value="1"
                                    id="defaultCheck2" name="overview">
                                <label class="form-check-label" for="defaultCheck2">
                                    Tampil Di Overview Event Detail
                                </label>
                            </div>
                        </div>
                        <div class="lebar-100 rata-tengah">
                            <button class="bg-primer mt-3">Update Session</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

    <script>
        let rundowns = {!! json_encode($rundowns) !!};
        let state = {
            isOptionOpened: false,
        }
        document.querySelector('#add-popup #stream-option').addEventListener('change', (evt) => {
            selectTypeStream(evt, 'add-popup')
        } )
        document.querySelector('#edit-popup #stream-option').addEventListener('change', (evt) => {
            selectTypeStream(evt, 'edit-popup')
        })
        document.addEventListener("click", e => {
            selectAll(".dropdown-content").forEach(dropdown => {
                dropdown.classList.remove('show');
            });


            let target = e.target;
            if (target.classList.contains('dropdownToggle')) {
                let id = target.getAttribute('data-id');
                document.getElementById("Dropdown" + id).classList.toggle("show");
                state.isOptionOpened = true;
            } else {
                state.isOptionOpened = false;
            }
        });

        const edit = (session) => {

            if (rundowns.length == 0) {
                Swal.fire({
                    title: 'Informasi',
                    text: 'Kamu belum membuat rundown satupun. Untuk mengubah sesi silahkan buat rundown terlebih dahulu !!!',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Ok',
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.replace(
                            "{{ route('organization.event.rundowns', [$organizationID, $event->id]) }}");
                    }
                });
            } else {
                console.log(session);
                var data = session;
                // data = JSON.parse(session);
                select("#editSession #sessionID").value = data.id;
                select("#editSession #title").value = data.title;
                if (data.event.execution_type == 'online' || data.event.execution_type == 'hybrid') {
                    // select("#editSession #link").value = data.link;
                    if (data.link.match(/rtmp-stream-key/gi)) {
                        document.querySelector('#edit-popup #stream-option').value = '1'
                    } else if (data.link.match(/webrtc-video-conference/gi)) {
                        document.querySelector('#edit-popup #stream-option').value = '2'
                    } else if (data.link.match(/us04web.zoom.us/gi)) {
                        document.querySelector('#edit-popup #stream-option').value = '3'
                        document.querySelector("#editSession #link").style.display = 'unset'
                        document.querySelector("#editSession #link").value = data.link;
                    } else {
                        document.querySelector('#edit-popup #stream-option').value = '4'
                        document.querySelector("#editSession #link").style.display = 'unset'
                        document.querySelector("#editSession #link").value = data.link;
                    }
                }
                if (data.overview == 1) {
                    select("#editSession #defaultCheck2").checked = true;
                } else {
                    select("#editSession #defaultCheck2").checked = false;
                }
                // speakers1.val([speakers.name]).trigger("change");
                try {
                    select(`#editSession #start_date option[value='${data.start_rundown_id}']`).selected = true;
                    select(`#editSession #end_date option[value='${data.end_rundown_id}']`).selected = true;
                    munculPopup("#editSession");
                } catch (error) {
                    console.log(error);
                    select(`#editSession #start_date option[value='']`).selected = true;
                    select(`#editSession #end_date option[value='']`).selected = true;
                    // alert("Hapus Session Ini dan Buat Session Baru, Tanggal Session Mendahului Tanggal Event");
                    Swal.fire({
                        title: 'Peringatan !!!',
                        text: 'Segera perbaiki pemilihan awal dan akhir rundown dari sesi ini',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        munculPopup("#editSession");
                    });
                }
                select("#editSession #description").value = data.description;
            }
        }

        var purchase = [];
        @foreach ($purchases as $purchase)
            purchase.push("{{ $purchase->tickets->session_id }}");
        @endforeach

        @foreach ($sessions as $session)
            var sessionID = "{{ $session->id }}";
            var partisipan = 0;
            var targetText = "";

            for (var i = 0; i < purchase.length; i++) {
                if (sessionID == purchase[i]) {
                    partisipan += 1;
                }
            } //
            console.log(partisipan);
            targetText = partisipan + " Participants";
            document.getElementById("partisipan-" + sessionID).innerHTML = targetText;
        @endforeach
    </script>
    <script>
        function selectTypeStream(event, parentId) {
            let selected = event.target.value
            if (selected == 3 || selected == 4) {
                document.querySelector(`#${parentId} #link`).style.display = 'unset'
            } else {
                document.querySelector(`#${parentId} #link`).style.display = 'none'
            }
        }
        // Sweet alert delete
        function onDelete(ticketBuyed) {
            event.preventDefault(); // prevent form submit
            var urlToRedirect = event.currentTarget.getAttribute('href');

            Swal.fire({
                title: "Apakah kamu yakin ?",
                text: "Sesi ini memiliki tiket yang terjual sejumlah " + ticketBuyed +
                    ", tiket kamu yang terhubung dari sesi ini akan terhapus",
                type: "warning",
                icon: "warning",
                dangerMode: true,
                showCancelButton: true,
                confirmButtonText: "Ya, hapus",
                cancelButtonText: "Batal",
                closeOnConfirm: false,
                closeOnCancel: false
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(urlToRedirect);
                    window.location.replace(urlToRedirect); // submitting the form when user press yes
                } else {
                    Swal.fire("Dibatalkan", "Sesi batal dihapus", "info");
                }
            });
        }

        function confirmMyPkg() {
            var pkgActive = '{{ $pkgActive }}';
            var pkgActive = parseInt(pkgActive);
            var thisSession = <?php echo json_encode($sessions); ?>;
            var myPkg = <?php echo json_encode($organization->user->package); ?>;
            // var rundowns = {{ json_encode($rundowns) }};  
            console.log(thisSession, myPkg);
            var paramCombine = false;
            // paramCombine adalah parameter untuk cek apakah unlimited / sudah tercapai batasnya ?
            if (myPkg.session_count <= -1) {
                paramCombine = false;
            } else {
                if (thisSession.length >= myPkg.session_count) {
                    paramCombine = true;
                }
            }
            if (pkgActive == 0 || paramCombine == true) {
                // Batalkan dengan konfirmasi sweet alert
                var msg = '';
                if (paramCombine == true) {
                    msg = 'Kamu sudah melewati batas paket untuk membuat session event baru';
                } else {
                    msg = 'Paket yang kamu beli sudah melewati satu bulan / belum dibayar !!!';
                }
                Swal.fire({
                    title: 'Error!',
                    text: msg,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'Ok',
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.open("{{ route('user.upgradePkg') }}");
                    }
                });
            } else {
                // Cek apakah rundowns nya kosong ?
                if (rundowns.length == 0) {
                    Swal.fire({
                        title: 'Informasi',
                        text: 'Kamu belum membuat rundown satupun. Untuk membuat sesi silahkan buat rundown terlebih dahulu !!!',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Ok',
                        cancelButtonText: "Batal",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.replace(
                                "{{ route('organization.event.rundowns', [$organizationID, $event->id]) }}");
                        }
                    });
                } else {
                    // Munculkan pop up tambah organisasi
                    munculPopup('#addSession');
                }
            }
        }
    </script>
    <script src="{{ asset('js/user/searchTable.js') }}"></script>
    <script src="{{ asset('js/user/cardMinimize.js') }}"></script>
    <script>
        try {
            cardAction('#notice');
        } catch (error) {
            console.log('Tidak ada notice');
        }
    </script>
@endsection

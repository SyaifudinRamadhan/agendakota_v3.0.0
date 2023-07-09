@extends('layouts.user')

@section('title', 'Session')

@section('head.dependencies')
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/sessionsPage.css') }}">
@endsection

@php
    use Carbon\Carbon;
    use Carbon\CarbonPeriod;
    use App\Models\SessionSpeaker;
    $period = CarbonPeriod::create($event->start_date, $event->end_date);
@endphp

@section('content')
    <div class="mb-3">
        <div class="row">
            <div class="col-md-7">
                <h2 style="margin-top: -3%; color: #304156; font-size:32px">Session Config</h2>
                <h4 class="mt-2" style="color: #979797; font-size:14">Manage your event config</h4>
                {{-- <div class="teks-transparan">{{ $event->name }}</div> --}}
            </div>

        </div>
    </div>

    @include('admin.partials.alert')

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
                    <div class="col-12">
                        <form action="{{ route('organization.event.session.update', [$organizationID, $event->id]) }}"
                            method="POST">
                            <div class="row">
                                <div class="col-lg-6">
                                    {{ csrf_field() }}
                                    @if ($event->execution_type == 'online' || $event->execution_type == 'hybrid')
                                        {{-- <div class="mt-2">Streaming Link :</div>
                                        <input type="url" class="box no-bg" name="link" id="link" required
                                            oninvalid="this.setCustomValidity('Harap Masukkan URL Dengan Benar')"
                                            oninput="setCustomValidity('')" placeholder="Link Zoom atau link Youtube"> --}}
                                        <div id="edit-popup">
                                            <div class="mt-2">Streaming Options :</div>
                                            <select class="custom-select" id="stream-option" name="streamOption">
                                                <option selected value="0">--- Belum ada yang dipilih ---</option>
                                                <option value="1">Streaming satu arah (RTMP)</option>
                                                <option value="2">Video Conference</option>
                                                <!-- <option value="3">Zoom Embed</option> -->
                                                <!-- <option value="4">YouTube Embed</option> -->
                                            </select>
                                            <input type="url" class="box no-bg" name="link" id="link"
                                                style="display: none"
                                                oninvalid="this.setCustomValidity('Harap Masukkan URL Dengan Benar')"
                                                oninput="setCustomValidity('')" placeholder="Link Zoom atau link Youtube">
                                        </div>
                                    @endif

                                    <div class="mt-2">Deskripsi Session :</div>
                                    <textarea name="description" id="description" class="box no-bg" placeholder="Deskripsi Session"></textarea>

                                    <div class="mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                id="defaultCheck2" name="overview">
                                            <label class="form-check-label" for="defaultCheck2">
                                                Tampil Di Overview Event Detail
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="lebar-100 rata-tengah">
                                        <button class="bg-primer mt-3">Update Session</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @endforeach
                @if ($event->execution_type == 'online' || $event->execution_type == 'hybrid')
                    <div class="col-12">
                        <a target="_blank" class="text-center"
                            href="{{ route('organization.event.session.url', [$organizationID, $event->id, $sessions[0]->id]) }}">
                            <button class="btn mt-3 w-100">Join Stream</button></a>
                    </div>
                    @if (preg_match('/rtmp-stream-key/i', $session->link))
                        <a href="{{ route('organization.event.studio-stream', [$organizationID, $event->id, $sessions[0]->id]) }}"
                            class="btn btn-primary mt-3 text-light w-100 text-center">
                            Start Streaming
                        </a>
                    @endif
                @endif
            </div>
        @endif
    </div>

@endsection

@section('javascript')
    <script src="{{ asset('js/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script>
        
        function formatText(icon) {

            var eventSlug = '{{ $event->slug }}';
            var url = 'storage/event_assets/' + eventSlug + '/speaker_photos/' + $(icon.element).data('icon');
            console.log(eventSlug);
            console.log(location.origin + '/' + url);
            return $('<span><img class="rounded-circle mr-3" style="width: 50px; height: 50px;" src="' + location.origin +
                '/' + url + '"/>' + icon.text + '</span>');
        };

        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: "Select a Speakers",
                allowClear: true,
                templateResult: formatText
            });
        });

        let state = {
            isOptionOpened: false,
        }
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
        let flatpickrOptions = {
            dateFormat: 'H:i',
            noCalendar: true,
            enableTime: true,
            time_24hr: true
        }
        flatpickr("#startTime", flatpickrOptions);

        const chooseStartTime = time => {
            select("#endTime").value = time;
            flatpickrOptions['minDate'] = time;
            flatpickr("#endTime", flatpickrOptions);
        }

        // const edit = (session, SessionSpeakers) => {

        data = JSON.parse('<?php echo $sessions[0]; ?>');
        speakers = JSON.parse('<?php echo $SessionSpeakers; ?>');
        // console.log(data);
        let selectedSpeakers = [];

        if (data.overview == 1) {
            select("#defaultCheck2").checked = true;
        } else {
            select("#defaultCheck2").checked = false;
        }
        // speakers1.val([speakers.name]).trigger("change");

        try {
            select("#description").value = (data.description.split('<p>')[1]).split('</p>')[0];
        } catch (error) {
            select("#description").value = data.description;
        }
        try {
            console.log(data);
            // select("#link").value = data.link;
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
            var speakers1 = $("#editspeaker").select2();
            speakers.forEach(speaker => {
                if (data.id == speaker.session_id) {
                    // console.log("data sama");
                    selectedSpeakers.push(speaker.speaker_id);
                }
            });

            speakers1.val(selectedSpeakers).trigger("change");
        } catch (error) {
            console.log(error);
        }
        // }
        document.querySelector('#edit-popup #stream-option').addEventListener('change', (evt) => {
            selectTypeStream(evt, 'edit-popup')
        })

        function selectTypeStream(event, parentId) {
            let selected = event.target.value
            if (selected == 3 || selected == 4) {
                document.querySelector(`#${parentId} #link`).style.display = 'unset'
            } else {
                document.querySelector(`#${parentId} #link`).style.display = 'none'
            }
        }
    </script>
@endsection

@extends('layouts.user')

@section('title', 'Rundowns')

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
    <div class="mb-2">
        <div class="row">
            <div class="col-md-7 mb-4">
                <h2 style="margin-top: -3%; color: #304156; font-size:32px">Rundown</h2>
                <h4 class="mt-2" style="color: #979797; font-size:14">Create and manage rundows for your event</h4>
                {{-- <div class="teks-transparan">{{ $event->name }}</div> --}}
            </div>
            <div class="col-md-5 pl-0">
                <button type="button" class="bg-primer mt-0 btn-add btn-no-pd mt-2 float-right mr-2 mb-4"
                    onclick="munculPopup('#addRundown')">
                    <i class="fa fa-plus"></i> Buat Rundown
                </button>

            </div>
        </div>
    </div>

    @include('admin.partials.alert')

    @if (count($notMatchConfig) > 0)
        <div class="smallPadding">
            <div class="alert alert-danger" role="alert">
                    Terdeteksi beberapa rundown melewati interval waktu dari pelaksanaan event.
            </div>
        </div>
        <div id="notice" class="card mb-3 mt-2">
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
                <h5 class="card-title">Segera perbaiki ke-tidak cocokan tanggal dan waktu rundown berikut ini :</h5>
                @foreach ($notMatchConfig as $notMatch)
                    <p class="text-danger">
                        *Rundown ->{{ '('.Carbon::parse($notMatch->start_date)->format('d M,').' '.
                        Carbon::parse($notMatch->start_time)->format('H:i') .'WIB -'.
                        Carbon::parse($notMatch->end_date)->format('d M,') .' '.
                        Carbon::parse($notMatch->end_time)->format('H:i')  .'WIB)' }}
                    </p>
                @endforeach
                {{-- <p class="text-danger">
                    Mengubah tannggal dan waktu event pada event yang memiliki stage and session, 
                    kamu diwajibkan untuk mengubah data tanggal dan waktu di rundown dan stage & session jika 
                    sudah pernah dibuat
                </p> --}}
            </div>
        </div>
    @endif

    <div id="guide" class="card mb-3 mt-1">
        <div class="card-header bg-primer">
            <div class="row w-100">
                <div class="col-6 text-left">
                    Petunjuk
                </div>
                <div id="close" class="col-6 text-right">
                    <a class="pointer">
                        <i class="bi bi-caret-up-fill text-light"></i> Tutup
                    </a>
                </div>
                <div id="open" class="col-6 text-right d-none">
                    <a class="pointer">
                        <i class="bi bi-caret-down-fill text-light"></i> Buka
                    </a>
                </div>
            </div>
        </div>
        <div id="body-card" class="card-body">
            <h5 class="card-title">Untuk Memilih Tanggal dan Waktu</h5>
            <p class="card-text">1. Sebelum membuat tiket, pengisian yang benar adalah dimulai dari rundown dahulu, kemudian membuat session (jika stage & session kamu centang. Jika tidak, kamu bisa mengatur konfigurasi di Config Session), kemudian membuat ticket </p>
            <p class="card-text">2. Tanggal dan waktu, tidak boleh kurang atau lebih dari batas pelaksanaan event </p>
            <p class="card-text">3. Pengisian tanggal waktu harus dimulai dari tanggal mulai dan waktu mulai dahulu</p>
            <p class="card-text">3. Jika dalam pop up edit ada start date (tanggal mulai) yang kosong (hanya mode
                mobile), artinya tanggal tersebut melewati batas dari tanggal mulai eventnya</p>
        </div>
    </div>

    @if (count($rundowns) > 0)
        <h3>
            <input type="search" placeholder="Search..." class="float-right form-control search-input" style="width: unset"
                data-table="rundown-list" />
        </h3>
    @endif

    <div id="table-mode" class="table-mode table-responsive">
        @if (count($rundowns) == 0)
            <div class="mt-4 rata-tengah">
                <i class="bi bi-calendar font-img teks-primer"></i>
                <h3>Mulai Rundown untuk Eventmu</h3>
                <p>Adakan berbagai event menarik di AgendaKota</p>
            </div>
        @else
            <div id="dummy-table" class="table-responsive mt-2" style="height: 10px">
                <table class="table table-borderless rundown-list">
                    <thead>
                        <tr>
                            <th style="min-width: 130px"></th>
                            <th style="min-width: 150px"></th>
                            <th style="min-width: 150px"></th>
                            <th style="min-width: 150px">
                            </th>
                            <th style="min-width: 130px"></th>
                            @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
                                <th style="min-width: 130px"></th>
                            @endif
                            <th style="min-width: 100px"></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="real-table" class="table-responsive">
                <table class="table table-borderless rundown-list">
                    <thead>
                        <tr>
                            <th style="min-width: 130px">Nama Acara</th>
                            <th style="min-width: 150px">Tanggal Mulai</th>
                            <th style="min-width: 150px">Tanggal Selesai</th>
                            <th style="min-width: 150px"><i
                                    class="fas fa-clock"></i>
                            </th>
                            <th style="min-width: 130px">Deskripsi</th>
                            @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
                                <th style="min-width: 130px">Speakers</th>
                            @endif
                            <th style="min-width: 100px">Action</th>
                        </tr>
                    </thead>
                    <tbody class="mt-2">
                        @foreach ($rundowns as $rundown)
                        {{-- @dump($rundown) --}}
                            <tr>
                                <td>{{ $rundown->name }}</td>
                                <td>{{ Carbon::parse($rundown->start_date)->format('d M Y') }}</td>
                                <td>{{ Carbon::parse($rundown->end_date)->format('d M Y') }}</td>
                                <td>
                                    {{ Carbon::parse($rundown->start_time)->format('H:i') }} -
                                    {{ Carbon::parse($rundown->end_time)->format('H:i') }}
                                </td>
                                <td>
                                    {!! $rundown->description !!}
                                </td>
                                @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
                                    <td>
                                        @foreach (SessionSpeaker::where('rundown_id', $rundown->id)->get() as $S_Speaker)
                                            {{ $S_Speaker->speaker->name . ', ' }}
                                        @endforeach
                                    </td>
                                @endif
                                <td>
                                    <span onclick='edit(<?= json_encode($rundown) ?>,<?= json_encode($SessionSpeakers) ?>);'
                                        class="teks-hijau pointer">
                                        <i class="fas fa-edit"></i>
                                    </span>

                                    &nbsp;
                                    <a href="{{ route('organization.event.rundown.delete', [$organizationID, $event->id, $rundown->id]) }}"
                                        class="teks-merah pointer" onclick="onDelete()">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex float-right">
                {{ $rundowns->links() }}
            </div>
        @endif
    </div>

    <div class="bg"></div>
    <div class="popupWrapper" id="addRundown">
        <div class="lebar-50 popup rounded-5">
            <h3><i class="fas bi bi-x-circle-fill op-5 mt-3 pr-3 ke-kanan pointer" onclick="hilangPopup('#addRundown')"></i>
            </h3>
            <div class="pl-5 pr-5">
                <h3 class="mt-5 rata-tengah">Buat Rundown Baru</h3>
                <div class="wrap">

                    <form action="{{ route('organization.event.rundown.store', [$organizationID, $event->id]) }}"
                        method="POST">
                        {{ csrf_field() }}
                        <div class="mt-2">
                            Nama Acara
                        </div>
                        <input type="text" class="box no-bg lebar-100" name="name" id="name"
                            value="{{ old('name') }}" required>
                        <div class="row mt_-1">

                            <div class="col-12 no-pd-l">
                                <div class="row">
                                    <div class="col-md-6 no-pd-l mt-3">
                                        <div class="mt-2">Mulai :</div>
                                        <input type="text" class="box no-bg lebar-100" name="start_date" id="startDate"
                                            value="{{ old('start_date') }}" onchange="chooseStartDate(this.value)"
                                            required>
                                    </div>
                                    <div class="col-md-6 no-pd-l mt-3">
                                        <div class="mt-2">Selesai :</div>
                                        <input type="text" class="box no-bg lebar-100" name="end_date" id="endDate"
                                            value="{{ old('end_date') }}"
                                            required>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-xl-5 no-pd-l">
                                <div class="row">
                                    <div class="col-md-7 no-pd-l mt-3">
                                        <div class="mt-2">Mulai :</div>

                                        <input type="text" class="box no-bg lebar-100" name="start_date" id="startDate"
                                            value="{{ old('start_date') }}" onchange="chooseStartDate(this.value)"
                                            required>
                                    </div>
                                    <div class="col-md-5 no-pd-l mt-5">
                                        <input type="text" class="box no-bg" id="startTime" name="start_time"
                                            value="{{ old('start_time') }}" onchange="chooseStartTime(this.value)"
                                            required oninvalid="this.setCustomValidity('Data Ini Tidak Boleh Kosong')"
                                            oninput="setCustomValidity('')">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 no-pd-l mt-5 text-center justify-content-center d-flex">
                                <div class="col-md-4 mt-2 no-pd-l text-center pt-1" style="font-size: 25pt;"> &gt; </div>
                            </div>
                            <div class="col-xl-5 no-pd-l">
                                <div class="row">
                                    <div class="col-md-7 no-pd-l mt-3">
                                        <div class="mt-2">Selesai :</div>
                                        <input type="text" class="box no-bg lebar-100" name="end_date" id="endDate"
                                            value="{{ old('end_date') }}" onchange="chooseEndDate(this.value)" required
                                            readonly>
                                    </div>
                                    <div class="col-md-5 no-pd-l mt-5">
                                        <input type="text" class="box no-bg" id="endTime" name="end_time" required
                                            readonly value="{{ old('end_time') }}"
                                            oninvalid="this.setCustomValidity('Data Ini Tidak Boleh Kosong')"
                                            oninput="setCustomValidity('')">
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                        @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow' || $event->type == 'Live Music / Muic Conser' || $event->type == 'Show / Festival')
                            @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
                            <div class="mt-2">Speakers :</div>
                            @else
                            <div class="mt-2">Peformers</div>
                            @endif
                            <div id="speakers">
                                <select class="box no-bg js-example-basic-multiple" id="addspeaker" name="speakers[]"
                                    multiple="multiple">
                                    @forelse ($speakers as $speaker)
                                        <option value="{{ $speaker->id }}" data-icon="{{ $speaker->photo }}">
                                            {{ $speaker->name }}</option>
                                    @empty
                                        <option value="" disabled> 
                                            @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
                                            Anda Belum Membuat Speaker
                                            @else
                                            Anda Belum Mengatur Peformers
                                            @endif
                                        </option>
                                    @endforelse
                                </select>
                            </div>
                        @endif
                        <div class="mt-2">Deskripsi Rundown :</div>
                        <textarea name="description" class="box no-bg desc"
                            placeholder="Deskripsi Rundown">{{ old('description') }}</textarea>

                        <div class="lebar-100 rata-tengah">
                            <button class="bg-primer mt-3">Buat Rundown</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($rundowns as $rundown)
        <div class="popupWrapper" id="editRundown{{ $rundown->id }}">
            <div class="lebar-50 popup rounded-5">
                <h3><i class="fas bi bi-x-circle-fill op-5 mt-3 pr-3 ke-kanan pointer"
                        onclick="hilangPopup('#editRundown{{ $rundown->id }}')"></i></h3>
                <div class="pl-5 pr-5">
                    <h3 class="mt-5 rata-tengah">Edit Rundown</h3>
                    <div class="wrap">

                        <form action="{{ route('organization.event.rundown.store', [$organizationID, $event->id]) }}"
                            method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="update" value="1">
                            <input id="idRundown" type="hidden" name="id" value="{{ $rundown->id }}">
                            <div class="mt-2">
                                Nama Acara
                            </div>
                            <input type="text" class="box no-bg lebar-100" name="name" id="name"
                                value="{{ $rundown->name }}" required>
                            <div class="row mt_-1">
                                <div class="col-12 no-pd-l">
                                    <div class="row">
                                        <div class="col-md-6 no-pd-l mt-3">
                                            <div class="mt-2">Mulai :</div>
                                            <input type="text" class="box no-bg lebar-100" name="start_date" id="startDate{{ $rundown->id }}"
                                                value="{{ $rundown->start_date.' '.$rundown->start_time }}" onchange="chooseStartDate(this.value, {{ json_encode($rundown->id) }})"
                                                required>
                                        </div>
                                        <div class="col-md-6 no-pd-l mt-3">
                                            <div class="mt-2">Selesai :</div>
                                            <input type="text" class="box no-bg lebar-100" name="end_date" id="endDate{{ $rundown->id }}"
                                                value="{{ $rundown->end_date.' '.$rundown->end_time }}"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-xl-5 no-pd-l">
                                    <div class="row">
                                        <div class="col-md-7 no-pd-l mt-3">
                                            <div class="mt-2">Mulai :</div>
                                            <input type="text" class="box no-bg lebar-100" name="start_date" id="startDate"
                                                onchange="chooseStartDate(this.value)" required
                                                value="{{ $rundown->start_date }}">
                                        </div>
                                        <div class="col-md-5 no-pd-l mt-5">
                                            <input type="text" class="box no-bg" id="startTime" name="start_time"
                                                onchange="chooseStartTime(this.value)" required
                                                oninvalid="this.setCustomValidity('Data Ini Tidak Boleh Kosong')"
                                                oninput="setCustomValidity('')" value="{{ $rundown->start_time }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-2 no-pd-l mt-5 text-center justify-content-center d-flex">
                                    <div class="mt-2 pt-1 text-center" style="font-size: 25pt;"> &gt; </div>
                                </div>
                                <div class="col-xl-5 no-pd-l">
                                    <div class="row">
                                        <div class="col-md-7 no-pd-l mt-3">
                                            <div class="mt-2">Selesai :</div>
                                            <input type="text" class="box no-bg lebar-100" name="end_date" id="endDate"
                                                value="{{ $rundown->end_date }}" onchange="chooseEndDate(this.value)"
                                                required readonly>
                                        </div>
                                        <div class="col-md-5 no-pd-l mt-5">
                                            <input type="text" class="box no-bg" id="endTime" name="end_time" required
                                                value="{{ $rundown->end_time }}" readonly
                                                oninvalid="this.setCustomValidity('Data Ini Tidak Boleh Kosong')"
                                                oninput="setCustomValidity('')">
                                        </div>
                                    </div>
                                </div> --}}
                            </div>

                            @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow' || $event->type == 'Live Music / Muic Conser' || $event->type == 'Show / Festival')
                                @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
                                <div class="mt-2">Speakers :</div>
                                @else
                                <div class="mt-2">Peformers</div>
                                @endif
                                {{-- <div id="speakers">
                                    <select class="box no-bg js-example-basic-multiple"
                                        id="editspeaker{{ $rundown->id }}" name="speakers[]" multiple="multiple">
                                        @forelse ($speakers as $speaker)
                                            <option value="{{ $speaker->id }}" data-icon="{{ $speaker->photo }}">
                                                {{ $speaker->name }}</option>
                                        @empty
                                            <option value="" disabled>
                                                @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
                                                Anda Belum Membuat Speaker
                                                @else
                                                Anda Belum Mengatur Peformers
                                                @endif
                                            </option>
                                        @endforelse
                                    </select>
                                </div> --}}
                                <div id="speakers">
                                    <select class="box no-bg js-example-basic-multiple" id="editspeaker{{ $rundown->id }}" name="speakers[]"
                                        multiple="multiple">
                                        @forelse ($speakers as $speaker)
                                            <option value="{{ $speaker->id }}" data-icon="{{ $speaker->photo }}">
                                                {{ $speaker->name }}</option>
                                        @empty
                                            <option value="" disabled> 
                                                @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
                                                Anda Belum Membuat Speaker
                                                @else
                                                Anda Belum Mengatur Peformers
                                                @endif
                                            </option>
                                        @endforelse
                                    </select>
                                </div>
                            @endif

                            <div class="mt-2">Deskripsi Rundown :</div>
                            <textarea name="description" id="description" class="box no-bg desc"
                                placeholder="Deskripsi Rundown">{{ $rundown->description }}</textarea>

                            <div class="lebar-100 rata-tengah">
                                <button class="bg-primer mt-3">Update Rundown</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('javascript')
    <script src="{{ asset('js/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
     <script src="https://cdn.ckeditor.com/ckeditor5/27.0.0/classic/ckeditor.js"></script> 
    <script>

        ClassicEditor.defaultConfig = {
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    '|',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'undo',
                    'redo'
                ]
            },
            language: 'en'
        };

        ClassicEditor.create(document.querySelector('.desc'));
        ClassicEditor.create(document.querySelector('#description'));
        

        // ------------------- Metode baru ---------------------------------------
        
        flatpickr("#startDate", {
            enableTime: true,
            time_24hr: true,
            dateFormat: 'Y-m-d H:i',
            minDate: '<?= $event->start_date." ".$event->start_time ?>',
            maxDate: '<?= $event->end_date." ".$event->end_time ?>'
        });

        flatpickr("#endDate", {
            enableTime: true,
            time_24hr: true,
            dateFormat: 'Y-m-d H:i',
            minDate: '<?= $event->start_date." ".$event->start_time ?>',
            maxDate: '<?= $event->end_date." ".$event->end_time ?>'
        });

        const chooseStartDate = (date, edit = null) => {
            console.log(date);
            var idTarget = "#endDate";
            if(edit != null){
                idTarget += edit;
            }
            flatpickr(idTarget, {
                enableTime: true,
                time_24hr: true,
                dateFormat: 'Y-m-d H:i',
                minDate: date,
                maxDate: '<?= $event->end_date." ".$event->end_time ?>'
            });
        }

        // -----------------------------------------------------------------------

        var rundowns = <?php echo json_encode($rundowns) ?>.data;
        var i = 0;
        <?php for($i=0; $i<count($rundowns); $i++) {?>
            // ------------------- Metode baru ---------------------------------------
        
            flatpickr("#startDate"+rundowns[i].id, {
                enableTime: true,
                time_24hr: true,
                dateFormat: 'Y-m-d H:i',
                minDate: '<?= $event->start_date." ".$event->start_time ?>',
                maxDate: '<?= $event->end_date." ".$event->end_time ?>'
            });

            flatpickr("#endDate"+rundowns[i].id, {
                enableTime: true,
                time_24hr: true,
                dateFormat: 'Y-m-d H:i',
                minDate: rundowns[i].start_date+' '+rundowns[i].start_time,
                maxDate: '<?= $event->end_date." ".$event->end_time ?>'
            });
            i += 1;
            // -----------------------------------------------------------------------

        <?php } ?>

        function formatText(icon) {

            var eventSlug = '{{ $event->slug }}';
            var url = 'storage/event_assets/' + eventSlug + '/speaker_photos/' + $(icon.element).data('icon');
            console.log(eventSlug);
            console.log(location.origin + '/' + url);
            return $('<span><img class="rounded-circle mr-3" style="width: 50px; height: 50px;" src="' + location.origin +
                '/' + url + '"/>' + icon.text + '</span>');
        };
   
        // var speakers1 = $("#editspeaker").select2();
        const edit = (rundown, SessionSpeakers) => {
            data = rundown;
            speakers = SessionSpeakers;
            // speakers1.val([speakers.name]).trigger("change");

            // try {
            //     select('#editRundown #startDate').value = data.start_date;
            //     select('#editRundown #endDate').value = data.end_date;
            //     munculPopup("#editRundown");
            // } catch (error) {
            //     alert("Hapus Rundown Ini dan Buat Rundown Baru, Tanggal Rundown Mendahului Tanggal Event");
            // }
            // select('#editRundown #idRundown').value = data.id;
            // select("#editRundown #description").value = data.description;
            // select("#editRundown #startTime").value = data.start_time;
            // select("#editRundown #endTime").value = data.end_time;
            // console.log( select('#editRundown #startDate').value, select("#editRundown #startTime").value);
            
            var speakers1 = $("#editspeaker" + data.id).select2();
            
            // ------- Menmpilkan list speaker ----------------
            $('.js-example-basic-multiple').select2({
                placeholder: "Select a Speakers",
                allowClear: true,
                templateResult: formatText
            });
            // ------------------------------------------------

            console.log("#editspeaker" + data.id,speakers1);
            console.log(data);
            munculPopup("#editRundown" + data.id);
            let selectedSpeakers = [];
            speakers.forEach(speaker => {
                if (data.id == speaker.rundown_id) {
                    // console.log("data sama");
                    selectedSpeakers.push(speaker.speaker_id);
                }
            });
            // console.log(speakers1);
            // console.log(SessionSpeakers);
            speakers1.val(selectedSpeakers).trigger("change");

        }

        $(document).ready(function() {
            console.log($('.js-example-basic-multiple'));
            $('.js-example-basic-multiple').select2({
                placeholder: "Select a Speakers",
                allowClear: true,
                templateResult: formatText
            });
        });

        /* ----------------------- Metode lama ---------------------------
        flatpickr("#startDate", {
            dateFormat: 'Y-m-d',
            minDate: "{{ $event->start_date }}",
            // disableMobile: "true"
        });

        const chooseStartDate = date => {
            flatpickr("#endDate", {
                dateFormat: 'Y-m-d',
                minDate: date,
            });
        }

        let flatpickrOptions = {
            dateFormat: 'H:i',
            noCalendar: true,
            enableTime: true,
            time_24hr: true,
            // disableMobile: "true"
        }
        flatpickr("#startTime", flatpickrOptions);

        const chooseStartTime = time => {
            select("#endTime").value = time;
            flatpickrOptions['minDate'] = time;
            flatpickr("#endTime", flatpickrOptions);
        }

        const chooseEndDate = date => {
            var startDate = select('#startDate').value;
            var endDate = select('#endDate').value;
            if (startDate == endDate) {
                var time = select('#startTime').value;
                select("#endTime").value = time;
                flatpickrOptions['minDate'] = time;
                flatpickr("#endTime", flatpickrOptions);
                console.log(time);
            }
        }
        -------------------------------------------------------------------------------- */

        // Sweet alert delete
        function onDelete() {
            event.preventDefault(); // prevent form submit
            var urlToRedirect = event.currentTarget.getAttribute('href');
            
                    Swal.fire({
                        title: "Apakah kamu yakin ?",
                        text: "Rundown ini mungkin terhubung ke data sesi, kamu harus memeriksa perubahan di halaman 'Stage & Session'",
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
                            window.location.replace(urlToRedirect);         // submitting the form when user press yes
                        } else {
                            Swal.fire("Dibatalkan", "Rundown batal dihapus", "info");
                        }
                    });
        }

        // function onDelete(ev) {
        //     ev.preventDefault();
        //     var urlToRedirect = ev.currentTarget.getAttribute('href'); //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
        //     console.log(urlToRedirect); // verify if this is the right URL
        //     Swal.fire({
        //         title: "Are you sure?",
        //         text: "Once deleted, you will not be able to recover this imaginary file!",
        //         icon: "warning",
        //         buttons: true,
        //         dangerMode: true,
        //         })
        //         .then((willDelete) => {
        //         // redirect with javascript here as per your logic after showing the alert using the urlToRedirect value
        //         if (willDelete) {
        //             Swal.fire("Poof! Your imaginary file has been deleted!", {
        //                 icon: "success",
        //             });
        //         } else {
        //             Swal.fire("Your imaginary file is safe!");
        //         }
        //     });
        // }
    </script>
    <script src="{{ asset('js/user/searchTable.js') }}"></script>
    <script src="{{ asset('js/tableTopScroll.js') }}"></script>
    <script src="{{ asset('js/user/cardMinimize.js') }}"></script>
    <script>
        try {
            cardAction('#notice');
        } catch (error) {
            console.log('Tidak ada notice')
        }
        cardAction('#guide');
    </script>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buat Event - Agendakota</title>
    <link rel="stylesheet" href="{{ asset('riyan/css/base/font.css') }}">
    <link rel="stylesheet" href="{{ asset('riyan/css/base/column.css') }}">
    <link rel="stylesheet" href="{{ asset('riyan/css/base/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('riyan/css/base/color.css') }}">
    <link rel="stylesheet" href="{{ asset('riyan/css/base/form.css') }}">
    <link rel="stylesheet" href="{{ asset('riyan/css/base/button.css') }}">
    <link rel="stylesheet" href="{{ asset('riyan/boxicons/css/boxicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/createEvent.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/createEvent.mobile.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css">
</head>
<body>

<input type="hidden" id="myData" value="{{ $myData }}">
    
<div class="footer flex row item-center justify-center">
    <div class="footer-info flex column grow-1">
        <h3 class="m-0">Buat Event</h3>
        <p class="m-0">Klik berikutnya untuk melanjutkan</p>
    </div>
    <div class="buttons flex row item-center justify-end">
        <button class="mr-1 prev d-none" onclick="previousScreen()">kembali</button>
        <button class="primary btn-next" onclick="nextScreen()" id="next">Berikutnya</button>
    </div>
</div>

<div class="content flex column item-center">
    <div class="screen-item flex row item-start justify-center w-100" id="tipeEvent">
        <div class="w-55 flex column pr-4 part-left" style="border-right: 3px solid var(--primary)">
            <input type="hidden" id="event_types" value="{{ json_encode($types) }}">
            <h2 class="mb-4 mt-0">Pilih Kategori Event</h2>
            <div class="flex row justify-space-evenly gap-20 wrap w-100 event-type-area">
                <input type="hidden" id="event_types" value="{{ json_encode($types) }}">
                @foreach ($types as $key => $type)
                    <div class="event-type-item pointer bordered rounded p-2 flex row shrink-1 item-center" event-type="{{ base64_encode($type['name']) }}" onclick="selectType(this, '{{ $key }}', '{{ json_encode($type) }}')">
                        <div>
                            <img src="{{ asset('images/event_types/'.$type['image']) }}" alt="">
                        </div>
                        <div>
                            <div class="event-type-name">{{ $type['name'] }}</div>
                            @if ($type['synonim'] != "")
                                <div class="text small-2 mt-05 muted event-type-description">{{ $type['synonim'] }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div> 
        </div>
        <div class="flex column grow-1 ml-4 w-45 part-right">
            <h2 class="mb-2 mt-0">Pilih Topik</h2>
            <div class="flex row item-center wrap">
                @foreach ($topics as $topic)
                <div 
                    class="bordered rounded pointer p-1 pl-2 pr-2 mr-1 mb-1 topic-item" 
                    topic="{{ base64_encode($topic) }}" 
                    onclick="chooseTopic('{{ $topic }}', this)"
                >
                    {{ $topic }}
                </div>
            @endforeach
            </div>
        </div>
    </div>

    <div class="screen-item column item-center justify-center d-none w-100" id="basicInfo">
        <div class="flex row w-100 mt-2 top-area">
            <div class="flex column grow-1 left">
                <div class="relative">
                    <input type="file" name="cover" id="cover" onchange="inputFile(this, {
                        preview: '#UploadArea'
                    })">
                    <div id="UploadArea" class="flex row item-center justify-center">
                        <div class="flex">
                            <i class="bx bx-image-add text primary" style="font-size: 100px"></i>
                        </div>
                        <div class="flex column ml-2">
                            <h2 class="mt-0">Upload Event Banner</h2>
                            <div class="upload_description">Rasio Aspek 5:2, Maksimal 2MB</div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="flex row w-100 mt-3">
            <div class="flex column w-65 mr-4">
                <div id="EventTitle" contenteditable="true" class="mt-2" onclick="clickEventName(this)" oninput="typing('event_name', this)">Nama Event</div>
                <textarea name="tagline" id="tagline" placeholder="Event Tagline" oninput="typing('tagline', this)" required></textarea>
            </div>
            <div class="flex column grow-1">
                <div class="BoxInput flex row item-center bordered rounded p-3 pointer" onclick="modal('#modalLocation').show()" id="location">
                    <div class="flex column item-center">
                        <i class="bx bx-map" style="font-size: 28px"></i>
                        <div class="text small-2 mt-05">Lokasi</div>
                    </div>
                    <div class="ml-2 lh-30" id="location_display">Pilih Lokasi</div>
                </div>
                <div class="BoxInput flex column justify-center bordered rounded p-3 pointer mt-2" onclick="modal('#modalDateTime').show()" id="location">
                    <div class="flex row item-center">
                        <i class="bx bx-calendar" style="font-size: 28px;"></i>
                        <div class="text ml-2" id="date_display">Pilih Tanggal</div>
                    </div>
                    <div class="flex row item-center mt-2">
                        <i class="bx bx-time" style="font-size: 28px;"></i>
                        <div class="text ml-2" id="time_display">Pilih Waktu</div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="mt-4 bordered rounded p-3 flex row item-center w-100 bottom-area">
            <div class="h-50 bg-white rounded-max squarize use-height" bg-image="{{ asset('storage/organization_logo/default_logo.png') }}" id="organizer_logo_display"></div>
            <div class="flex column ml-2 grow-1">
                <div class="text small muted cursor-default">Diselenggarakan Oleh</div>
                <div class="text bold mt-05 cursor-default" id="organizer_name_display">Nama Organizer</div>
            </div>
            <div>
                <span class="pointer text bold primary" id="chooseOrganizer" onclick="modal('#modalOrganizer').show()">
                    Pilih Organizer
                </span>
            </div>
        </div>

        <div class="w-100 mt-4">
            <div class="bordered rounded p-3">
                <div class="flex row item-center">
                    <div target="advancedSettings" class="tab-item flex grow-1 justify-center border-bottom pointer h-40 active">Pengaturan Lanjutan</div>
                    <div target="eventDescription" class="tab-item flex grow-1 justify-center border-bottom pointer h-40">Deskripsi Event</div>
                </div>
                <div class="tab-content mt-3 active" key="advancedSettings">
                    <div class="flex row item-center">
                        <div class="w-50 flex column">
                            <div class="text bold big setting-name">Visibilitas</div>
                            <div class="text muted mt-05 setting-description">Bagaimana eventmu akan berlangsung</div>
                        </div>
                        <div class="flex column grow-1">
                            <div class=" bordered primary rounded p-05 flex row visibility_type_area">
                                <div class="visibility_type text center rounded pointer flex item-center justify-center grow-1 h-40 active" visibility="public" onclick="chooseVisibility('public', this)">
                                    Public
                                </div>
                                <div class="visibility_type text center rounded pointer flex item-center justify-center grow-1 h-40" visibility="private" onclick="chooseVisibility('private', this)">
                                    Private
                                </div>
                            </div>
                            <div class="text right mt-1 small muted" id="visibilityDescription">Event kamu akan ditampilkan untuk umum. Semua pengguna Agendakota dapat mengikuti eventmu.</div>
                        </div>
                    </div>
                    <div class="flex row item-center mt-4 execution-area settings-area">
                        <div class="w-40 flex column part-left-2">
                            <div class="text bold big setting-name flex row">Penyelenggaraan
                                <div 
                                    class="help-icon h-20 squarize use-height bg-grey rounded-max flex row item-center justify-center text small ml-05 pointer" 
                                    onclick="help('#modalExecutionType')">?</div>
                            </div>
                            <div class="text muted mt-05 setting-description">Bagaimana eventmu akan berlangsung</div>
                        </div>
                        <div class="flex column grow-1">
                            <div class=" bordered primary rounded p-05 flex row execution_type_area">
                                <div class="execution_type text center rounded pointer flex item-center justify-center grow-1 h-40 active" execution-type="offline" onclick="chooseExecution('offline', this)">
                                    Offline
                                </div>
                                <div class="execution_type text center rounded pointer flex item-center justify-center grow-1 h-40" execution-type="hybrid" onclick="chooseExecution('hybrid', this)">
                                    Hybrid
                                </div>
                                <div class="execution_type text center rounded pointer flex item-center justify-center grow-1 h-40" execution-type="online" onclick="chooseExecution('online', this)">
                                    Online
                                </div>
                            </div>
                            <div class="text center mt-1 small muted part-right-2" id="executionTypeDescription">Kamu memiliki venue dan segala persiapannya dan peserta harus datang ke eventmu</div>
                        </div>
                    </div>
                    <div class="d-none row item-center mt-4 breakdown-section">
                        <div class="w-45 flex column" id="BreakdownLeft">
                            <div class="text bold big setting-name flex row">Breakdown
                                <div 
                                    class="help-icon h-20 squarize use-height bg-grey rounded-max flex row item-center justify-center text small ml-05 pointer" 
                                    onclick="help('#modalBreakdown')">?</div>
                            </div>
                            <div class="text muted mt-05 setting-description">Informasi tambahan yang sesuai dengan kebutuhanmu</div>
                        </div>
                        <div class="flex row wrap justify-space-between gap-20 grow-1 breakdown-area">
                            <div class="flex row item-center bordered rounded p-2 breakdown-item active" breakdown="Stage and Session" onclick="selectBreakdown('Stage and Session', this)">
                                <div class="flex grow-1">
                                    Stage & Sessions
                                </div>
                                <i class="bx bx-camera-movie"></i>
                            </div>
                            <div class="flex row item-center bordered rounded p-2 breakdown-item" breakdown="Sponsors" onclick="selectBreakdown('Sponsors', this)">
                                <div class="flex grow-1">
                                    Sponsors
                                </div>
                                <i class="bx bx-tv"></i>
                            </div>
                            <div class="flex row item-center bordered rounded p-2 breakdown-item" breakdown="Exhibitors" onclick="selectBreakdown('Exhibitors', this)">
                                <div class="flex grow-1">
                                    Exhibitors
                                </div>
                                <i class="bx bx-chalkboard"></i>
                            </div>
                            <div class="flex row item-center bordered rounded p-2 breakdown-item" breakdown="Media Partner" onclick="selectBreakdown('Media Partner', this)">
                                <div class="flex grow-1">
                                    Media Partner
                                </div>
                                <i class="bx bx-id-card"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content mt-3" key="eventDescription">
                    <div class="label-text flex row item-center mb-1">
                        <i class="bx bx-file"></i>
                        <div class="text">Deskripsi Event</div>
                    </div>
                    <div id="EventDescription"></div>
                    <div class="label-text flex row item-center mb-1 mt-3">
                        <i class="bx bx-file"></i>
                        <div class="text">Syarat & Ketentuan</div>
                    </div>
                    <div id="SnK"></div>
                </div>
            </div>
            
            <div class="h-50"></div>
        </div>
    </div>

    <div class="screen-item column item-start justify-center d-none column" style="width: 85%" id="ticketing">
        <h2 class="text w-100 mb-1">Buat Tiket</h2>
        <div class="mb-2">Siapkan dan atur penjualan tiket untuk eventmu</div>

        <div class="flex row justify-center w-100 ticket-area">
            <div class="ticket" onclick="addTicket('gratis')">
                <div class="HalfCircle LeftCircle"></div>
                <div class="HalfCircle TopCircle"></div>
                <div class="HalfCircle BottomCircle"></div>
                <div class="barcode">
                    @for ($i = 0; $i < 18; $i++)
                        <div class="barcode-line {{ $i % 3 == 0 ? 'bold' : '' }}">
                        </div>
                    @endfor
                </div>
                <div class="detail flex row wrap item-center">
                    <div class="flex grow-1">
                        <div class="flex column grow-1">
                            <div class="text small muted w-100 label">TIKET</div>
                            <div class="text bold bigger mt-05 w-100 ticket_name">Gratis</div>
                        </div>
                    </div>
                    <div class="flex row item-center justify-center outer-add">
                        <div class="add-button rounded-max flex row item-center justify-center">
                            <i class="bx bx-plus text bold big"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ticket" onclick="addTicket('Berbayar')">
                <div class="HalfCircle LeftCircle"></div>
                <div class="HalfCircle TopCircle"></div>
                <div class="HalfCircle BottomCircle"></div>
                <div class="barcode">
                    @for ($i = 0; $i < 18; $i++)
                        <div class="barcode-line {{ $i % 3 == 0 ? 'bold' : '' }}">
                        </div>
                    @endfor
                </div>
                <div class="detail flex row wrap item-center">
                    <div class="flex grow-1">
                        <div class="flex column grow-1">
                            <div class="text small muted w-100 label">TIKET</div>
                            <div class="text bold bigger mt-05 w-100 ticket_name">Berbayar</div>
                        </div>
                    </div>
                    <div class="flex row item-center justify-center outer-add">
                        <div class="add-button rounded-max flex row item-center justify-center">
                            <i class="bx bx-plus text bold big"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ticket" onclick="addTicket('suka-suka')">
                <div class="HalfCircle LeftCircle"></div>
                <div class="HalfCircle TopCircle"></div>
                <div class="HalfCircle BottomCircle"></div>
                <div class="barcode">
                    @for ($i = 0; $i < 18; $i++)
                        <div class="barcode-line {{ $i % 3 == 0 ? 'bold' : '' }}">
                        </div>
                    @endfor
                </div>
                <div class="detail flex row wrap item-center">
                    <div class="flex grow-1">
                        <div class="flex column grow-1">
                            <div class="text small muted w-100 label">TIKET</div>
                            <div class="text bold bigger mt-05 w-100 ticket_name">Donasi</div>
                        </div>
                    </div>
                    <div class="flex row item-center justify-center outer-add">
                        <div class="add-button rounded-max flex row item-center justify-center">
                            <i class="bx bx-plus text bold big"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h2 id="renderedTicketTitle" class="d-none">Ticket</h2>
        <div id="renderTicketArea" class="flex row item-center"></div>
    </div>

    <div class="screen-item d-none column item-center justify-center" id="loading">
        <img src="{{ asset('images/preparation.gif') }}" alt="Preparation">
        <h2>Memproses Eventmu...</h2>
        <div class="load-wrapp">
            <div class="load-3">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="gcid" value="{{ env('GOOGLE_CLIENT_ID') }}">
<div id="g_id_onload"
    data-client_id="{{ env('GOOGLE_CLIENT_ID') }}"
    data-login_uri="https://your.domain/your_login_endpoint"
    data-auto_prompt="false">
</div>

@include('partials.CreateEvent.LoginModal')
@include('partials.CreateEvent.RegisterModal')
@include('partials.CreateEvent.OtpModal')
@include('partials.CreateEvent.ErrorModal')

@include('partials.CreateEvent.TopicModal')
@include('partials.CreateEvent.LocationModal')
@include('partials.CreateEvent.ChooseOrganizerModal', ['ableToCreateOrganizer' => false])
@include('partials.CreateEvent.TicketModal')
@include('partials.CreateEvent.DateTimeModal')

@include('partials.CreateEvent.Help.ExecutionTypeModal')
@include('partials.CreateEvent.Help.Breakdown')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"></script>

{{-- <script src='https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.js'></script>
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script> --}}

<script src="https://accounts.google.com/gsi/client" async defer></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

<script src="{{ asset('riyan/js/base.js') }}"></script>
<script src="{{ asset('riyan/js/Currency.js') }}"></script>
<script src="{{ asset('riyan/js/element.js') }}"></script>
<script>
    localStorage.removeItem('user_data')
    let myDataFromBackend = JSON.parse(escapeJson("{{ json_encode($myData) }}"));
    if(myDataFromBackend != null){
        localStorage.setItem('user_data', JSON.stringify(myDataFromBackend));
    }
    // let myData = JSON.parse(localStorage.getItem('user_data'));
    // let myDataFromBackend = JSON.parse(escapeJson("{{ json_encode($myData) }}"));
    // if (myData.token != myDataFromBackend.token) {
    //     localStorage.setItem('user_data', JSON.stringify(myDataFromBackend));
    //     window.location.reload()
    // }
    // if (myData != null && JSON.parse("{{ json_encode($myData) }}") == null) {
    //     localStorage.removeItem('user_data');
    //     modal("#modalLogin").show();
    // }
</script>
<script src="{{ asset('riyan/js/CreateEvent.js') }}"></script>
<script src="{{ asset('riyan/js/CreateEvent/Ticket.js') }}"></script>
<script src="{{ asset('riyan/js/Authentication.js') }}"></script>

</body>
</html>
@extends('layouts.user')

@section('title', 'Tiket')

@section('head.dependencies')
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/ticketPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/main.css') }}">
@endsection

@section('content')
    
    <div class="row">
        <div class="col-md-7">
            <h2>Ticket & Pricing</h2>
            <h5 class="teks-transparan">{{ $event->name }}</h5>
        </div>
            
        <div class="col-md-5">
            <button id="addBtn" class="btn btn-no-pd btn-add ke-kanan pointer primer font-inter">
            <i class="fas fa-plus"></i> Tambah Tiket
        </button>
        </div>
    </div>
    
    <div class="tab scrollmenu" style="border: none">
        <button class="tab-btn tablinks-event-ticket active" onclick="opentabs(event, 'event-ticket', 'All')">All</button>
        <button class="tab-btn tablinks-event-ticket" onclick="opentabs(event, 'event-ticket', 'Free')">Free</button>
        <button class="tab-btn tablinks-event-ticket" onclick="opentabs(event, 'event-ticket', 'Paid')">Paid</button> 
    </div>

    <div id="All" class="tabcontent-event-ticket mt-2" style="display: block; border: none;">

        @include('admin.partials.alert')
        @php
            use App\Models\Purchase;

            $countEvent = 0;
            $countSession = 0;

            if(count($event->sessions) > 0){
                $countSession = count($event->sessions);

                for($i=0; $i<$countSession; $i++){
                    $countEvent++;
                }
            }


        @endphp

        <div class="row d-flex">
        @if($countSession == 0)
            <div class="col-md text-center">
                <i class="bi bi-tags font-img teks-primer"></i>
                <h5>Tidak ada session untuk dibuat tiket</h5>
                <h6>Atur sessionmu dulu untuk bisa membuat ticket!</h6>
            </div>
        @else
            @if($countEvent == 0)
                <div class="col-md text-center">
                    <i class="bi bi-tags font-img teks-primer"></i>
                    <h5>Tidak ada Tiket Satupun</h5>
                    <h6>Tambahkan Sekarang!</h6>
                </div>

            @else
                @foreach ($event->sessions as $session)
                        @php
                            $i = 0;
                        @endphp
                        
                        @foreach ($session->tickets as $ticket)

                            @if ($ticket->deleted == 0)
                                
                            <div class="col-md-4 mt-3 testing">
                                <div class="ticket">
                                    <span class="float-right teks-mini mt-0 btn-card">
                                        <a href="{{ route('organization.event.ticket.delete', [$organizationID, $eventID, $ticket->id]) }}"
                                            class="teks-primer float-right pl-4 mb-3"
                                            onclick="onDelete({{ count($ticket->purchases) }})">
                                            <i class="fas fa-trash fs-icon"></i>
                                        </a>
                                        <i class="teks-primer pointer fas fa-edit fs-icon float-right"
                                            onclick='edit(<?= json_encode($ticket) ?>)'></i>
                                    </span>
                                    <h1 class="mb-0 ticket-title pointer" onclick='popupDetail(<?= json_encode($ticket) ?>,<?= json_encode($ticket->session) ?>)'>
                                        <b>{{ $ticket->name }}</b>
                                    </h1>
                                    
                                    @if ($ticket->price == 0)
                                        <p class="teks-primer fs-14 mb-0">Gratis</p>
                                    @else
                                        <p class="teks-primer fs-14 mb-0">@currencyEncode($ticket->price)</p>
                                    @endif
                                    <hr class="garis-tiket" />


                                        @php
                                            $purchasesObj = Purchase::where('ticket_id', $ticket->id)->get();

                                            $purchaseObj = [];
                                            for($j = 0; $j< count($purchasesObj); $j++){
                                                if($purchasesObj[$j]->payment->pay_state == 'Terbayar'){
                                                    array_push($purchaseObj, $purchasesObj[$j]);
                                                }
                                            }
                                        @endphp
                                        <p class="text-secondary mt-0 float-right fs-13" id="jumlah">
                                            Tiket Tersisa :
                                            {{ $ticket->quantity }} </p>

                                        @php
                                            $valuenow = $ticket->quantity == 0 ? 100 : (count($purchaseObj) / $ticket->quantity) * 100;
                                            $valuemax = $ticket->quantity;
                                        @endphp
                                        <p class="text-secondary mt-0 mb-0 fs-13">Terjual :</p>
                                        <h6 id="penjualan" class="fs-14">
                                           Rp. {{count($purchaseObj) * $ticket->price}}
                                        </h6>

                                        <div class="progress" style="height: 5%">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $valuenow }}%; background-color:#EB597B" aria-valuenow="{{ $valuenow }}" aria-valuemin="0" aria-valuemax="{{ $valuemax }}"></div>
                                        </div>
                                    </div>
                                </div>

                                @endif

                        @endforeach
                        
                        @php
                            $i++;
                        @endphp

                @endforeach
            @endif
                                
        @endif
        </div>
    </div>

    
    <div id="Free" class="tabcontent-event-ticket mt-2" style="display:none; border: none">
        <div class="row d-flex">
        @if($countSession == 0)
            <div class="col-md text-center">
                <i class="bi bi-tags font-img teks-primer"></i>
                <h5>Tidak ada session untuk dibuat tiket</h5>
                <h6>Atur sessionmu dulu untuk bisa membuat ticket!</h6>
            </div>
        @else
            @if($countEvent == 0)
                <div class="col-md text-center">
                    <i class="bi bi-tags font-img teks-primer"></i>
                    <h5>Tidak ada Tiket Satupun</h5>
                    <h6>Tambahkan Sekarang!</h6>
                </div>

            @else
                @foreach ($event->sessions as $session)
                        @php
                            $i = 0;
                        @endphp
                        
                        @foreach ($session->tickets as $ticket)
                        @if($ticket->price == 0 && $ticket->deleted == 0)
                            <div class="col-md-4 mt-3">
                                <div class="ticket">
                                    <span class="float-right teks-mini mt-0 btn-card">
                                        <a href="{{ route('organization.event.ticket.delete', [$organizationID, $eventID, $ticket->id]) }}"
                                            class="teks-primer float-right pl-4 mb-3"
                                            onclick="onDelete({{ count($ticket->purchases) }})">
                                            <i class="fas fa-trash fs-icon"></i>
                                        </a>
                                        <i class="teks-primer pointer fas fa-edit fs-icon float-right"
                                            onclick='edit(<?= json_encode($ticket) ?>)'></i>
                                    </span>
                                    <h1 class="mb-0 ticket-title pointer" onclick='popupDetail(<?= json_encode($ticket) ?>,<?= json_encode($ticket->session) ?>)'>
                                        <b>{{ $ticket->name }}</b>
                                    </h1>
                                    @if ($ticket->price == 0)
                                        <p class="teks-primer fs-14 mb-0">Gratis</p>
                                    @else
                                        <p class="teks-primer fs-14 mb-0">@currencyEncode($ticket->price)</p>
                                    @endif
                                    <hr class="garis-tiket" />


                                        @php
                                            $purchasesObj = Purchase::where('ticket_id', $ticket->id)->get();

                                            $purchaseObj = [];
                                            for($j = 0; $j< count($purchasesObj); $j++){
                                                if($purchasesObj[$j]->payment->pay_state == 'Terbayar'){
                                                    array_push($purchaseObj, $purchasesObj[$j]);
                                                }
                                            }
                                        @endphp
                                        <p class="text-secondary mt-0 float-right fs-13" id="jumlah">
                                            Tiket Tersisa :
                                            {{ $ticket->quantity }} </p>

                                        @php
                                            $valuenow = $ticket->quantity == 0 ? 100 : (count($purchaseObj) / $ticket->quantity) * 100;
                                            $valuemax = $ticket->quantity;
                                        @endphp
                                        <p class="text-secondary mt-0 mb-0 fs-13">Terjual :</p>
                                        <h6 id="penjualan" class="fs-14">
                                           Rp. {{count($purchaseObj) * $ticket->price}}
                                        </h6>

                                        <div class="progress" style="height: 5%">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $valuenow }}%; background-color:#EB597B" aria-valuenow="{{ $valuenow }}" aria-valuemin="0" aria-valuemax="{{ $valuemax }}"></div>
                                        </div>
                                </div>
                                
                            </div>
                        
                        @endif
                        @endforeach

                        @php
                            $i++;
                         @endphp
                @endforeach
            @endif
        @endif
        </div>
    </div>
    {{-- paid --}}
    <div id="Paid" class="tabcontent-event-ticket mt-2" style="display:none; border: none">
        <div class="row d-flex">
        @if($countSession == 0)
            <div class="col-md text-center">
                <i class="bi bi-tags font-img teks-primer"></i>
                <h5>Tidak ada session untuk dibuat tiket</h5>
                <h6>Tambahkan Sekarang!</h6>
            </div>
        @else
            @if($countEvent == 0)
                <div class="col-md text-center">
                    <i class="bi bi-tags font-img teks-primer"></i>
                    <h5>Tidak ada Tiket Satupun</h5>
                    <h6>Tambahkan Sekarang!</h6>
                </div>

            @else
                @foreach ($event->sessions as $session)
                        @php
                            $i = 0;
                        @endphp
                        
                        @forelse ($session->tickets as $ticket)
                        
                        @if($ticket->price > 0 && $ticket->deleted == 0)
                            <div class="col-md-4 mt-3">
                                <div class="ticket">
                                    <span class="float-right teks-mini mt-0 btn-card">
                                        <a href="{{ route('organization.event.ticket.delete', [$organizationID, $eventID, $ticket->id]) }}"
                                            class="teks-primer float-right pl-4 mb-3"
                                            onclick="onDelete({{ count($ticket->purchases) }})">
                                            <i class="fas fa-trash fs-icon"></i>
                                        </a>
                                        <i class="teks-primer pointer fas fa-edit fs-icon float-right"
                                            onclick='edit(<?= json_encode($ticket) ?>)'></i>
                                    </span>
                                    <h1 class="mb-0 ticket-title pointer" onclick='popupDetail(<?= json_encode($ticket) ?>,<?= json_encode($ticket->session) ?>)'>
                                        <b>{{ $ticket->name }}</b>
                                    </h1>
                                    @if ($ticket->price == 0)
                                        <p class="teks-primer fs-14 mb-0">Gratis</p>
                                    @else
                                        <p class="teks-primer fs-14 mb-0">@currencyEncode($ticket->price)</p>
                                    @endif
                                    <hr class="garis-tiket" />


                                        @php
                                            $purchasesObj = Purchase::where('ticket_id', $ticket->id)->get();

                                            $purchaseObj = [];
                                            for($j = 0; $j< count($purchasesObj); $j++){
                                                if($purchasesObj[$j]->payment->pay_state == 'Terbayar'){
                                                    array_push($purchaseObj, $purchasesObj[$j]);
                                                }
                                            }
                                        @endphp
                                        <p class="text-secondary mt-0 float-right fs-13" id="jumlah">
                                            Tiket Tersisa :
                                            {{ $ticket->quantity }} </p>

                                        @php
                                            $valuenow = $ticket->quantity == 0 ? 100 : (count($purchaseObj) / $ticket->quantity) * 100;
                                            $valuemax = $ticket->quantity;
                                        @endphp
                                        <p class="text-secondary mt-0 mb-0 fs-13">Terjual :</p>
                                        <h6 id="penjualan" class="fs-14">
                                           Rp. {{count($purchaseObj) * $ticket->price}}
                                        </h6>

                                        <div class="progress" style="height: 5%">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $valuenow }}%; background-color:#EB597B" aria-valuenow="{{ $valuenow }}" aria-valuemin="0" aria-valuemax="{{ $valuemax }}"></div>
                                        </div>
                                </div>
                            </div>
                        
                        @endif
                        @endforeach

                        @php
                            $i++;
                        @endphp

                @endforeach
            @endif
        @endif
        </div>
    </div>

    <div class="bg"></div>
    <div class="popupWrapper" id="addTicket">
        <div class="popup rounded-5">
            <h3><i class="fas bi bi-x-circle-fill op-5 mt-3 pr-3 ke-kanan pointer" onclick="hilangPopup('#addTicket')"></i></h3>
            <div class="wrap-popup">
                <h3 class="lebar-100 rata-tengah">Tambah Tiket</h3>
                <form action="{{ route('organization.event.ticket.store', [$organizationID, $event->id]) }}"
                    method="POST">
                    {{ csrf_field() }}
                     <div class="row">
                        <div class="col-md-12">
                            <div class="mt-2">Sesi Awal :</div>
                            <select name="session_id" class="box" required>
                                <option value="">-- Pilih Sesi --</option>
                                @foreach ($event->sessions as $session)
                                    @if ($session->deleted == 0)
                                        <option value="{{ $session->id }}">{{ $session->title }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="mt-2">Sesi Akhir :</div>
                            <select name="end_session_id" class="box" required>
                                <option value="">-- PILIH SESI --</option>
                                @foreach ($event->sessions as $session)
                                    <option value="{{ $session->id }}">{{ $session->title }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>
                    
                    <div class="mt-2">Nama Tiket :</div>
                    <input type="text" class="form-control box" name="name" id="name" required value="{{old('name')}}">
                    <div class="form-group mt-2">
                        <label for="">Jumlah Tiket :</label>
                        <input type="number" class="form-control box" name="quantity" id="quantity" min="1" required>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="">Jenis Tiket :</label>
                            <select class="form-control box" name="type_price" id="tipe" onchange="getval(this);">
                                <option value="gratis">Gratis</option>
                                <option value="Berbayar">Berbayar</option>
                                <option value="suka-suka">Bayar Suka Suka</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Harga :</label>
                            <input type="number" class="form-control box" name="price" id="price" min="{{ config('agendakota')["min_transfer"] }}" value="0" readonly
                                required>
                        </div>
                    </div>
                    <div class="mt-2">Deskripsi Tiket</div>
                    <textarea name="description" id="" class="box" required placeholder="Deskripsi eventmu">{{old('description')}}</textarea>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mt-2">Tanggal Mulai Penjualan :</div>
                            <input type="text" class="box no-bg" name="start_date" id="startDate" required readonly
                                onchange="chooseStartDate(this.value)">
                        </div>
                        <div class="col-md-6">
                            <div class="mt-2">Tanggal Berakhir Penjualan :</div>
                            <input type="text" class="box no-bg" name="end_date" id="endDate" required readonly>
                        </div>
                    </div>
                    <div class="lebar-100 rata-tengah">
                        <button class="primer mt-50-btn-popup" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="popupWrapper" id="editTicket">
        <div class="popup rounded-5">
            <h3><i class="fas bi bi-x-circle-fill op-5 mt-3 pr-3 ke-kanan pointer" onclick="hilangPopup('#editTicket')"></i></h3>
            <div class="wrap-popup">
                <h3 class="lebar-100 rata-tengah">Edit Tiket</h3>
                <form action="{{ route('organization.event.ticket.update', [$organizationID, $eventID]) }}"
                    method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="ticket_id" id="ticketID">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mt-2">Sesi Awal :</div>
                            <select id="list-session" name="session_id" class="box" required>
                                <option value="">-- Pilih Sesi --</option>
                                @foreach ($event->sessions as $session)
                                    @if ($session->deleted == 0)
                                        <option value="{{ $session->id }}">{{ $session->title }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-2">Nama Tiket :</div>
                    <input type="text" class="form-control box" name="name" id="name" required>
                    <div class="form-group mt-2">
                        <label for="">Jumlah Tiket :</label>
                        <input type="number" class="form-control box" name="quantity" id="quantity" min="1" required>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="">Jenis Tiket :</label>
                            <select id="selected" class="form-control box" name="type_price" id="tipe" onchange="getval(this);">
                                
                                <option value="gratis">Gratis</option>
                                <option value="Berbayar">Berbayar</option>
                                <option value="suka-suka">Bayar Suka Suka</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Harga :</label>
                            <input type="number" class="form-control box" name="price" id="price" min="{{ config('agendakota')["min_transfer"] }}" value="0"
                                required>
                        </div>
                    </div>
                    <div class="mt-2">Deskripsi Tiket</div>
                    <textarea name="description" class="box" required id="description"></textarea>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mt-2">Tanggal Mulai Penjualan :</div>
                            <input type="text" class="box no-bg" name="start_date" id="startDate" required readonly
                                onchange="chooseStartDate(this.value)">
                        </div>
                        <div class="col-md-6">
                            <div class="mt-2">Tanggal Berakhir Penjualan :</div>
                            <input type="text" class="box no-bg" name="end_date" id="endDate" required readonly>
                        </div>
                    </div>
                    <div class="lebar-100 rata-tengah mt-50-btn-popup">
                        <button class=" mt-2 primer">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="popupWrapper" id="detailTicket">
        <div class="popup rounded-5">
            <h3><i class="fas bi bi-x-circle-fill op-5 mt-3 pr-3 ke-kanan pointer" onclick="hilangPopup('#detailTicket')"></i></h3>
            <div class="wrap-popup">
                <h3 class="lebar-100 rata-tengah">Detail Tiket </h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mt-2">Session Awal :</div>
                        <input disabled type="text" class="form-control box no-bg" name="session" id="session" required>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="mt-2">Session Akhir :</div>
                        <input disabled type="text" class="form-control box no-bg" name="end_session" id="endSession" required>
                    </div> --}}
                </div>
                       
                        
                    <div class="mt-2">Nama Tiket :</div>
                    <input disabled type="text" class="form-control box no-bg" name="name" id="name" required>
                    <div class="form-group mt-2">
                        <label for="">Jumlah Tiket :</label>
                        <input disabled type="number" class="form-control box no-bg" name="quantity" id="quantity" min="1" required>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="">Jenis Tiket :</label>
                            <select disabled class="form-control box no-bg" name="" id="tipe" onchange="getval(this);">
                                <option id="selected"></option>
                                <option value="gratis">Gratis</option>
                                <option value="reguler">Berbayar</option>
                                <option value="suka-suka">Bayar Suka Suka</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Harga :</label>
                            <input disabled type="number" class="form-control box no-bg" name="price" id="price" min="1" value="0"
                                required>
                        </div>
                    </div>
                    <div class="mt-2">Deskripsi Tiket</div>
                    <textarea disabled name="description" class="box" id="description"></textarea>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mt-2">Tanggal Mulai Penjualan :</div>
                            <input disabled type="text" class="box no-bg" name="start_date" id="startDate" required readonly
                            onchange="chooseStartDate(this.value)">
                        </div>
                        <div class="col-md-6">
                            <div class="mt-2">Tanggal Berakhir Penjualan :</div>
                            <input disabled type="text" class="box no-bg" name="end_date" id="endDate" required readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="{{ asset('js/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script>
        let sessions = {!! json_encode($event->sessions->where('deleted',0)) !!}
        let flatpickrOptions = {
            dateFormat: 'Y-m-d',
        }
        flatpickr("#startDate", flatpickrOptions);

        const chooseStartDate = date => {
            flatpickrOptions['minDate'] = date;
            flatpickr("#endDate", flatpickrOptions);
        }

        function addTicket() {
            if(sessions.length == 0){
                Swal.fire({
                    title: 'Informasi',
                    text: 'Kamu belum membuat sesi satupun. Untuk membuat tiket silahkan buat sesi terlebih dahulu !!!',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Ok',
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if(result.isConfirmed){
                        window.location.replace("{{ route('organization.event.sessions',[$organizationID, $event->id]) }}");
                    }
                });
            }else{
                munculPopup('#addTicket');
            }
        } 

        document.getElementById('addBtn').addEventListener('click', function () {
           addTicket(); 
        });

        const edit = data => {
            // data = JSON.parse(data);

            let flatpickrOptions = {
                dateFormat: 'Y-m-d',
                defaultDate: data.start_date,
            }
            flatpickr("#startDate", flatpickrOptions);

            munculPopup("#editTicket");
            try {
                select(`#editTicket #list-session option[value='${data.session_id}']`).selected = true;
            } catch (error) {
                console.log(error)
            }
            select("#editTicket #ticketID").value = data.id;
            select("#editTicket #name").value = data.name;
            select("#editTicket #price").value = data.price;
            select("#editTicket #quantity").value = data.quantity;
            select("#editTicket #startDate").value = data.start_date;
            select("#editTicket #endDate").value = data.end_date;
            select("#editTicket #description").value = data.description;
            if (data.price == 0) {
                select(`#editTicket #selected option[value="gratis"]`).selected = true;
                select("#editTicket #price").readOnly = true;
            }else{
                if(data.type_price == 1){
                    select(`#editTicket #selected option[value="Berbayar"]`).selected = true;
                }else if(data.type_price == 2){
                    select(`#editTicket #selected option[value="suka suka"]`).selected = true;
                }
            }
        }

        function popupDetail (data, session) {
            // data = JSON.parse(data);
            // session = JSON.parse(session);

            let flatpickrOptions = {
                dateFormat: 'Y-m-d',
                defaultDate: data.start_date,
            }
            flatpickr("#startDate", flatpickrOptions);

            // endSession = JSON.parse(endSession);
            munculPopup("#detailTicket");
            select("#detailTicket #session").value = session.title;
            // select("#detailTicket #endSession").value = endSession.title;
            select("#detailTicket #name").value = data.name;
            select("#detailTicket #price").value = data.price;
            select("#detailTicket #quantity").value = data.quantity;
            select("#detailTicket #startDate").value = data.start_date;
            select("#detailTicket #endDate").value = data.end_date;
            select("#detailTicket #description").value = data.description;
            if (data.price == 0) {
                select("#detailTicket #selected").text = "Gratis";
            }else{
                if(data.type_price == 1){
                    select("#detailTicket #selected").text = "Berbayar";
                }else if(data.type_price == 2){
                    select("#detailTicket #selected").text = "Bayar Suka Suka";
                }
            }
        }

        function getval(sel) {
            if (sel.value == "Berbayar" || sel.value == "suka-suka") {
                select("#addTicket #price").readOnly = false;
                select("#editTicket #price").readOnly = false;
                select("#detailTicket #price").readOnly = false;
            } else {
                select("#addTicket #price").value = 0;
                select("#addTicket #price").readOnly = true;

                select("#editTicket #price").value = 0;
                select("#editTicket #price").readOnly = true;

                select("#detailTicket #price").value = 0;
                select("#detailTicket #price").readOnly = true;

            }
        }

        // Sweet alert delete
        function onDelete(ticketBuyed) {
            event.preventDefault(); // prevent form submit
            var urlToRedirect = event.currentTarget.getAttribute('href');
            
                    Swal.fire({
                        title: "Apakah kamu yakin ?",
                        text: "Tiket ini sudah terjual sejumlah "+ticketBuyed+" tiket",
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
                            Swal.fire("Dibatalkan", "Tiket batal dihapus", "info");
                        }
                    });
        }
    </script>
@endsection

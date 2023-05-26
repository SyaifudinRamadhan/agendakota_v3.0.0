@extends('layouts.user')

@section('title', 'Ticket Selling')

@section('head.dependencies')
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/sessionsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/ticketSellPage.css') }}">
@endsection

{{-- -------- Parameter List untuk menentukan batas pcakage user ------------------ --}}
@php
    $pkgActive = \App\Http\Controllers\PackagePricingController::limitCalculator($organization->user);
@endphp
{{-- ------------------------------------------------------------------------------ --}}


@section('content')
    @php
        use Carbon\Carbon;
        use App\Models\UserCheckin;
        $tanggalSekarang = Carbon::now()->toDateString();
    @endphp

    <div class="">
        <div class="row">
            <div class="col-md-7 mb-4">
                <h2 style="margin-top: -3%; color: #304156; font-size:32px">Ticket Selling</h2>
                <h4 class="teks-transparan">{{ $event->name }}</h4>
            </div>

        </div>
    </div>

    @include('admin.partials.alert')

    @if (count($tickets) == 0)

        <div class="mt-4 rata-tengah">
            <i class="fa fa-tags font-img teks-primer mb-4"></i>
            <h3>Belum ada ticket terjual untuk Eventmu</h3>
            <p>Adakan berbagai event menarik di AgendaKota</p>
        </div>
    @else
        <div id="guide" class="card mb-3">
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
                <h5 class="card-title">Untuk Melakukan Check-In Peserta Silahkan Ikuti Petunjuk Berikut</h5>
                <p class="card-text">1. Gunakan search kolom untuk mencari data pembelian peserta berdasar invoiceID
                    nya</p>
                <p class="card-text">2. Tombol dalam kolom CheckIn, sebagai tombol untuk check-in peserta</p>
                <p class="card-text">3. Jika tombol berwarna hijau, klik tombolnya untuk melakukan check-in</p>
                <p class="card-text">4. Jika tombol berwarna abu-abu, artinya kamu sudah pernah men-check-in atas nama
                    peserta tersebut</p>
                <p class="card-text">5. Jika ingin mendownload data pembelian & check-in ticket, silahkan klik tombol
                    di bawah ini</p>
                @if ($pkgActive == 0 || $organization->user->package->report_download == 0)
                    <p class="text-danger">*Report tidak tersedia</p>
                @else
                    <a href="{{ route('organization.event.ticketSelling.download', [$organizationID, $event->id]) }}"
                        class="btn btn-success">Download XLS</a>
                @endif
                {{-- <p class="card-text text-danger">*(Tombol ini & tombol check-in masih belum berfungsi masih proses)</p> --}}
                <a href="{{ route('organization.event.ticketSelling.checkinqr', [$organizationID, $event->id]) }}"
                    class="btn btn-warning">QR Checkin</a>
            </div>
        </div>
        <h3>
            <input type="search" placeholder="Search..." class="float-right form-control search-input"
                data-table="customers-list" />
        </h3>
        <div id="dummy-table" class="table-responsive mt-2" style="height: 10px">
            <table class="table table-borderless customers-list">
                <thead>
                    <tr>
                        <th style="min-width: 180px"></th>
                        <th style="min-width: 290px"></th>
                        <!-- <th>Diberikan Ke- </th>
                                            <th>E-Mail penerima</th> -->
                        <th style="min-width: 250px"></th>
                        <th style="min-width: 250px"></th>
                        <th style="min-width: 200px"></th>
                        <th style="min-width: 250px"></th>
                        <th style="min-width: 100px"></th>
                        <th style="min-width: 100px"></th>
                    </tr>
                </thead>
            </table>
        </div>
        <div id="real-table" class="table-responsive">
            <table class="table table-borderless customers-list">
                <thead>
                    <tr>
                        <th style="min-width: 180px">Pembeli</th>
                        {{-- <th style="min-width: 290px">E-Mail
                            pembeli</th> --}}
                        <!-- <th>Diberikan Ke- </th>
                                            <th>E-Mail penerima</th> -->
                        <th style="min-width: 250px">InvoiceID</th>
                        {{-- <th style="min-width: 250px">Ticket</th>
                        <th style="min-width: 200px">Harga</th>
                        <th style="min-width: 250px">Tanggal Beli</th>
                        <th style="min-width: 100px">Terbayar</th> --}}
                        <th style="min-width: 100px">CheckIn</th>
                        <th style="min-width: 100px">Detail</th>
                    </tr>
                </thead>

                <tbody class="mt-2">
                    {{-- Variabel $tickets diambil dari model purchase bukan ticket --}}
                    @foreach ($tickets as $ticket)
                        <tr>
                            {{-- <td>{{ $ticket->users->name }}</td>
                            <td>{{ $ticket->users->email }}</td> --}}
                            <td>{{ $ticket->users->name }}</td>
                            {{-- <td>{{ $ticket->users->email }}</td> --}}
                            <td>{{ $ticket->payment->order_id }}</td>
                            {{-- <td>{{ $ticket->tickets->name }}</td>
                            <td>@currencyEncode($ticket->tickets->price)</td>
                            <td>{{ $ticket->created_at }}</td>
                            <td>
                                @if ($ticket->payment->pay_state == 'Terbayar')
                                    <div class="text-payed text-center">
                                        <i class="bi bi-check text-light fs-20"></i>
                                    </div>
                                @else
                                    <div class="text-no-pay text-center">
                                        <i class="bi bi-x text-light fs-20"></i>
                                    </div>
                                @endif
                            </td> --}}
                            <td>
                                @php
                                    $checkin = UserCheckin::where('purchase_id', $ticket->id)->get();
                                @endphp
                                @if (count($checkin) == 0)
                                    <form
                                        action="{{ route('organization.event.ticketSelling.checkin', [$organizationID, $event->id]) }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="purchase" value="{{ base64_encode($ticket->id) }}">
                                        <button class="btn btn-no-pd btn-success" type="submit">
                                            Checkin
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-no-pd btn-secondary">
                                        Checkin
                                    </button>
                                @endif
                            </td>
                            <td>
                                <!-- Button trigger modal -->
                                <a class="btn btn-primary text-light" type="button" class="btn btn-primary"
                                    data-toggle="modal" data-target="#detailModal{{$ticket->id}}">Detail</a>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="detailModal{{$ticket->id}}" tabindex="-1" role="dialog"
                            aria-labelledby="modal{{$ticket->id}}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal{{$ticket->id}}">Detail</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <div class="form-group">
                                                <label>Email pembeli</label>
                                                <input type="email" class="form-control w-100" aria-describedby="emailHelp"
                                                    placeholder="Enter email" readonly
                                                    value="{{ $ticket->users->email }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Nama Tiket</label>
                                                <input type="text" class="form-control w-100"
                                                    value="{{ $ticket->tickets->name }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Harga</label>
                                                <input type="text" class="form-control w-100" value="@currencyEncode($ticket->tickets->price)" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Tanggal Beli</label>
                                                <input type="text" class="form-control w-100"
                                                    value="{{ $ticket->created_at }}" readonly>
                                            </div>
                                            <div class="form-group d-flex">
                                                <label>Status</label>
                                                @if ($ticket->payment->pay_state == 'Terbayar')
                                                    <a class="btn btn-success ml-auto">Terbayar</a>
                                                @else
                                                    <a class="btn btn-secondary ml-auto">Belum terbayar</a>
                                                @endif
                                            </div>
                                            <div class="form-check">
                                                @if (count($checkin) == 0)
                                                    <input type="checkbox" class="form-check-input" checked>
                                                    <label class="form-check-label">Checkin</label>
                                                @else
                                                    <input type="checkbox" class="form-check-input">
                                                    <label class="form-check-label">Checkin</label>
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <a type="button" class="btn btn-secondary text-light"
                                            data-dismiss="modal">Close</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>


        <div class="d-flex float-right">
            {{ $tickets->links() }}
        </div>

    @endif



@endsection

@section('javascript')
    <script src="{{ asset('js/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script type="text/javascript">
        function formatText(icon) {

            var url = 'storage/profile_photos/' + $(icon.element).data('icon');
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
    </script>
    <script type="text/javascript" src="{{ asset('js/user/searchTable.js') }}"></script>
    <script src="{{ asset('js/tableTopScroll.js') }}"></script>
    <script src="{{ asset('js/user/cardMinimize.js') }}"></script>
    <script>
        cardAction('#guide');
    </script>
@endsection

@extends('layouts.admin')

@section('title', 'Packages')

@section('head.dependencies')
    <style>
        .picture {
            display: inline-block;
            width: 100px;
            height: 100px;
            border-radius: 100px;
            margin-top: -50px;
        }

        .dashboard-item {
            margin-top: 70px;
            text-decoration: none !important;
        }

        .dashboard-item a:link {
            text-decoration: none !important;
        }

        .dashboard-item h3 {
            font-size: 18px;
        }

        .dashboard-item .from {
            margin-top: 20px;
        }

        .dashboard-item .from p {
            line-height: 25px;
            font-size: 13px;
            margin: 0px;
            text-decoration: none !important;
        }

        .dashboard-item .from h4 {
            font-size: 15px;
            margin: 0px;
            margin-top: 2px;
            font-family: DM_SansBold !important;
        }

    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.2/font/bootstrap-icons.css">
@endsection

@section('content')
    @include('admin.partials.alert')
    @php
        use Carbon\Carbon;
    @endphp
    <h2>Data Paket User</h2>
    <p class="teks-transparan mb-4">Ringkasan paket yang dimiliki user Agendakota.id saat ini</p>
    <div class="row">
        <div class="col-12">
            <div class="float-right text-right">
                <button onclick="munculPopup('#add')" class="btn btn-primer">Tambah Paket</button>
            </div>
        </div>
    </div>
    <div class="mt-3">
        <h3>
            <input type="search" placeholder="Search..." class="float-right form-control search-input"
                style="width: unset" data-table="payments-list" />
        </h3>
        <div class="table-responsive">
            
            <table id="payments-table" class="table table-borderless payments-list">
                <thead>
                    <tr>
                        <th>Nama&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Deskripsi&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Batas Organisasi&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Event di waktu yang sama</i>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                        </th>
                        <th>Komisi Ticket&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Jumlah Sesi&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Jumlah Sponsor&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Jumlah Exhibitor&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Jumlah Media Partner&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Custom Link&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Download Laporan&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Maks. File Upload&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Harga Bulanan&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Harga Tahunan&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($packages as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->organizer_count <= -1 ? 'Unlimited' : $item->organizer_count }} Organisasi</td>
                            <td>{{ $item->event_same_time <= -1 ? 'Unlimited' : $item->event_same_time }} Event</td>
                            <td>{{ $item->ticket_commission * 100 }} %</td>
                            <td>{{ $item->session_count <= -1 ? 'Unlimited' : $item->session_count }} Sesi</td>
                            <td>{{ $item->sponsor_count <= -1 ? 'Unlimited' : $item->sponsor_count }} Sponsor</td>
                            <td>{{ $item->exhibitor_count <= -1 ? 'Unlimited' : $item->exhibitor_count }} Exhibitor</td>
                            <td>{{ $item->partner_media_count <= -1 ? 'Unlimited' : $item->partner_media_count }} Media Partner</td>
                            
                            <td>
                                @if($item->custom_link == 1) 
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    <i class="fa fa-times text-danger"></i>
                                @endif
                            </td>
                            <td>
                                @if($item->report_download == 1) 
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    <i class="fa fa-times text-danger"></i>
                                @endif
                            </td>
                            <td>{{ $item->max_attachment }} Mb</td>
                            <td>@currencyEncode($item->price)</td>
                            <td>@currencyEncode($item->price_in_year)</td>
                            <td>
                                <a class="btn btn-warning mb-2" onclick="munculPopup('#change{{ $item->id }}')">Edit</a>
                                <a class="btn btn-danger mb-2" onclick="return confirm('Apakah kamu yakin ingin menghapusnya ?')" href="{{ route('admin.package.delete',[$item->id]) }}">Hapus</a>
                            </td>
                        </tr>

                        {{-- PopUp mengubah data paket paket user --}}

                        <div class="bg"></div>
                        <div class="popupWrapper" id="change{{ $item->id }}">
                            <div class="popup">
                                <div class="wrap">
                                    <h4>Edit Paket {{ $item->name }}
                                        <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#change{{ $item->id }}')"></i>
                                    </h4>
                                    <form action="{{ route('admin.package.update',[$item->id]) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                                        {{ csrf_field() }}
                                        <div class="bagi lebar-100 pl-3 pr-3">
                                            <div>Nama :</div>
                                            <input type="text" class="box" name="name" value="{{ $item->name }}" required>
                                            <div class="mt-2">Deskripsi :</div>
                                            <textarea name="description" id="" class="box" required>{{ $item->description }}</textarea>
                                            <div class="mt-2">Jumlah Organisasi (Unlimited isi dengan -1) :</div>
                                            <input type="text" class="box" name="organizer_count" value="{{ $item->organizer_count }}" required>
                                            <div class="mt-2">Event di Waktu Sama (Unlimited isi dengan -1) :</div>
                                            <input type="text" class="box" name="event_same_time" value="{{ $item->event_same_time }}" required>
                                            <div class="mt-2">Komisi Tiket :</div>
                                            <input type="text" class="box w-50" name="ticket_commission" value="{{ $item->ticket_commission * 100 }}" required>%
                                            <div class="mt-2">Jumlah Sesi (Unlimited isi dengan -1) :</div>
                                            <input type="text" class="box" name="session_count" value="{{ $item->session_count }}" required>
                                            <div class="mt-2">Jumlah Sponsor (Unlimited isi dengan -1) :</div>
                                            <input type="text" class="box" name="sponsor_count" value="{{ $item->sponsor_count }}" required>
                                            <div class="mt-2">Jumlah Exhibitor (Unlimited isi dengan -1) :</div>
                                            <input type="text" class="box" name="exhibitor_count" value="{{ $item->exhibitor_count }}" required>
                                            <div class="mt-2">Jumlah Media Partner (Unlimited isi dengan -1) :</div>
                                            <input type="text" class="box" name="partner_media_count" value="{{ $item->partner_media_count }}" required>
                                            <div class="mt-2">Maks. File Upload :</div>
                                            <input type="text" class="box" name="max_attachment" value="{{ $item->max_attachment }}" required>
                                            <div class="mt-2">Harga Bulanan :</div>
                                            <input type="number" class="box" name="price" value="{{ $item->price }}" required>
                                            <div class="mt-2">Harga Tahunan :</div>
                                            <input type="number" class="box" name="price_in_year" value="{{ $item->price_in_year }}" required>
                                            <div class="mt-3 mb-2">
                                                <input id="report{{ $item->id }}" type="checkbox" name="report_download" {{ $item->report_download == 1 ? 'checked' : '' }} value="{{ $item->report_download }}"> <label for="report{{ $item->id }}">Download Laporan</label>
                                            </div>
                                            <div class="mt-1 mb-2">
                                                <input id="linkcs{{ $item->id }}" type="checkbox" name="custom_link" {{ $item->custom_link == 1 ? 'checked' : '' }} value="{{ $item->custom_link }}"> <label for="linkcs{{ $item->id }}">Custom Link</label>
                                            </div>
                                            <button class="bg-primer mt-2 lebar-100">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- <a class="d-flex justify-content-center mt-3" href="/"><img src="{{ asset('images/logo.png') }}" class="lebar-50"></a> --}}

    <div class="mt-5 pb-3"
        style="width: 100%; height: 15px; border-bottom: 1px solid black; text-align: center;bottom:0 auto;position: relative;">
        <a class="text-secondary pt-2 bg-white pr-3 pl-3">AgendaKota.id</a>
    </div>

    {{-- PopUp mengubah data paket paket user --}}

    <div class="bg"></div>
    <div class="popupWrapper" id="add">
        <div class="popup">
            <div class="wrap">
                <h4>Tambah Paket
                    <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#add')"></i>
                </h4>
                <form action="{{ route('admin.package.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                    {{ csrf_field() }}
                    <div class="bagi lebar-100 pl-3 pr-3">
                        <div>Nama :</div>
                        <input type="text" class="box" name="name" required>
                        <div class="mt-2">Deskripsi :</div>
                        <textarea name="description" id="" class="box" required></textarea>
                        <div class="mt-2">Jumlah Organisasi (Unlimited isi dengan -1) :</div>
                        <input type="text" class="box" name="organizer_count" required>
                        <div class="mt-2">Event di Waktu Sama (Unlimited isi dengan -1) :</div>
                        <input type="text" class="box" name="event_same_time" required>
                        <div class="mt-2">Komisi Tiket :</div>
                        <input type="text" class="box w-50" name="ticket_commission" required>%
                        <div class="mt-2">Jumlah Sesi (Unlimited isi dengan -1) :</div>
                        <input type="text" class="box" name="session_count" required>
                        <div class="mt-2">Jumlah Sponsor (Unlimited isi dengan -1) :</div>
                        <input type="text" class="box" name="sponsor_count" required>
                        <div class="mt-2">Jumlah Exhibitor (Unlimited isi dengan -1) :</div>
                        <input type="text" class="box" name="exhibitor_count" required>
                        <div class="mt-2">Jumlah Media Partner (Unlimited isi dengan -1) :</div>
                        <input type="text" class="box" name="partner_media_count" required>
                        <div class="mt-2">Maks. File Upload :</div>
                        <input type="text" class="box" name="max_attachment" required>
                        <div class="mt-2">Harga Bulanan :</div>
                        <input type="number" class="box" name="price">
                        <div class="mt-2">Harga Tahunan :</div>
                        <input type="number" class="box" name="price_in_year" required>
                        <div class="mt-3 mb-2">
                            <input id="reportadd" type="checkbox" name="report_download" value="1" > <label for="reportadd">Download Laporan</label>
                        </div>
                        <div class="mt-1 mb-2">
                            <input id="linkcsadd" type="checkbox" name="custom_link" value="1" > <label for="linkcsadd">Custom Link</label>
                        </div>
                        <button class="bg-primer mt-2 lebar-100">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/user/searchTable.js') }}"></script>
    <script src="{{ asset('js/user/paginationTable.js') }}"></script>
    <script>
        $(document).ready(function() {
            paginate('#payments-table', 'payments', 10);
        });
    </script>
@endsection

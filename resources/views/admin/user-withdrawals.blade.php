@extends('layouts.admin')

@section('title', 'Withdraw Report')

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
    @php
        use Carbon\Carbon;
    @endphp
    <h2>Data Permintaan Penarikan</h2>
    <p class="teks-transparan mb-4">Ringkasan Keuangan Agendakota.id saat ini</p>
    <div class="row">
        <div class="col">
            <div class="d-flex float-right mb-4">
                @if (isset($pageMain))
                    <a href="{{ route('admin.finance-report.withdrawals') }}">
                        <button class="btn btn-primer btn-no-pd">All Withdraw</button>
                    </a>
                @else
                    <button class="btn btn-primer btn-no-pd" onclick="history.back()">Kembali</button>
                @endif
            </div>
        </div>
    </div>
    <div>
        <h3>
            <input type="search" placeholder="Search..." class="float-right form-control search-input"
                style="width: unset" data-table="withdrawals-list" />
        </h3>
        <div class="table-responsive">
            
            <table id="withdrawals-table" class="table table-borderless withdrawals-list">
                <thead>
                    <tr>
                        <th>Username&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>E-Mail&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Penarikan Selesai&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Penarikan Gagal&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Penarikan Menunggu&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Aksi&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $counter = 0;
                    @endphp
                    @foreach ($users as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $data[$counter]['accepted'] }}</td>
                            <td>{{ $data[$counter]['rejected'] }}</td>
                            <td>{{ $data[$counter]['waiting'] }}</td>
                            <td>
                                <a href="{{ route('admin.finance-report.withdraw-userid',[$item->id]) }}">
                                    <button class="btn btn-no-pd btn-success">
                                        Lihat Semua Withdraw
                                    </button>
                                </a>
                            </td>
                        </tr>
                        @php
                            $counter+=1;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex float-right">
            {{ $users->links() }}
        </div>
    </div>

    {{-- <a class="d-flex justify-content-center mt-3" href="/"><img src="{{ asset('images/logo.png') }}" class="lebar-50"></a> --}}

    <div class="mt-5 pb-3"
        style="width: 100%; height: 15px; border-bottom: 1px solid black; text-align: center;bottom:0 auto;position: relative;">
        <a class="text-secondary pt-2 bg-white pr-3 pl-3">AgendaKota.id</a>
    </div>

    <script src="{{ asset('js/user/searchTable.js') }}"></script>
    <script src="{{ asset('js/user/paginationTable.js') }}"></script>
    <script>
        $(document).ready(function() {
            paginate('#withdrawals-table', 'withdrawals', 10);
        });
    </script>
@endsection

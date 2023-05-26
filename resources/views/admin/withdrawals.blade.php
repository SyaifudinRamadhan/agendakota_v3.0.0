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
                    <a href="{{ route('admin.finance-report.withdraw-user') }}">
                        <button class="btn btn-primer btn-no-pd">Group By User</button>
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
                        <th>Nama Event&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th><i
                                class="fas fa-clock"></i> Waktu Pengajuan&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                        </th>
                        <th>Organisasi&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>E-Mail&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Bank Mitra&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Rekening&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Nominal&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Status&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Aksi&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($withdrawals as $item)
                        <tr>
                            <td>{{ $item->event->name }}</td>
                            <td>
                                {{ Carbon::parse($item->created_at)->format('d M Y') }}
                            </td>
                            <td>{{ $item->event->organizer->name }}</td>
                            <td>{{ $item->user->email }}</td>
                            <td>{{ $item->bank_name }}</td>
                            <td>{{ $item->account_number }}</td>
                            <td>@currencyEncode($item->nominal)</td>
                            <td>
                                @if ($item->status == 'waiting')
                                    <span class="btn no-pd bg-secondary text-light">Waiting</span>
                                @elseif($item->status == 'accepted')
                                    <span class="btn no-pd bg-success text-light">Accepted</span>
                                @elseif($item->status == 'rejected')
                                    <span class="btn no-pd bg-danger text-light">Rejected</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->status == 'waiting')
                                    <form class="mb-2" action="{{ route('admin.finance-report.verify') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <input type="hidden" name="command" value="1">
                                        <button type="submit" class="btn btn-success btn-no-pd">Accept</button>
                                    </form>
                                    <form class="mb-2" action="{{ route('admin.finance-report.verify') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <input type="hidden" name="command" value="0">
                                        <button type="submit" class="btn btn-danger btn-no-pd">Reject</button>
                                    </form>
                                @elseif($item->status == 'accepted')
                                   <button class="btn btn-success btn-no-pd disabled mb-2">Accept</button>
                                   <button class="btn btn-danger btn-no-pd disabled mb-2">Reject</button>
                                @elseif($item->status == 'rejected')
                                    <button class="btn btn-success btn-no-pd disabled mb-2">Accept</button>
                                    <button class="btn btn-danger btn-no-pd disabled mb-2">Reject</button>
                                @endif
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex float-right">
            {{ $withdrawals->links() }}
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

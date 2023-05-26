@extends('layouts.admin')

@section('title', 'Package Selling')

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
    <h2>Data Keuangan</h2>
    <p class="teks-transparan mb-4">Ringkasan Penjualan Paket Agendakota.id saat ini</p>



    <div class="bagi bagi-3 dashboard-item rata-tengah mt-5 mb-3">
        <div class="wrap">
            <div class="bayangan-5 rounded">
                <div class="col-md bg-primer h-25"></div>
                <div class="wrap row col-md">
                    <h1 class="pt-3 ml-3"><i class="fa bi bi-coin teks-primer"></i></h1>
                    <div class="wrap rata-kiri mb-0 text-dark">
                        <p class="mb-0">Total Pendapatan</p>
                        <h3 class="mb-0 teks-transparan">@currencyEncode($pkg_paid)</h3>
                    </div>
                </div>
                <div class="rata-kiri from smallPadding bg-primer">
                    <div class="wrap">Pemasukan terbaru</p>
                        @if ($new_income != null)
                            <h4>Dari order id {{ $new_income->order_id }}</h4>
                        @else
                            <h4>Tidak ada data</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bagi bagi-3 dashboard-item rata-tengah mt-5 mb-3">
        <div class="wrap">

            <div class="bayangan-5 rounded">
                <div class="col-md bg-primer h-25"></div>
                <div class="wrap row col-md">
                    <h1 class="pt-3 ml-3"><i class="fa bi bi-file-earmark-text teks-primer"></i></h1>
                    <div class="wrap rata-kiri mb-0 teks-dark">
                        <p class="mb-0">Total Gagal Bayar</p>
                        <h3 class="mb-0 teks-transparan">@currencyEncode($pkg_failed)</h3>
                    </div>
                </div>
                <div class="rata-kiri from smallPadding bg-primer">
                    <div class="wrap">Pembelian Terbaru</p>
                        @if ($new_fail_in != null)
                            <h4>Dari order id {{ $new_fail_in->order_id }}</h4>
                        @else
                            <h4>Tidak ada data</h4>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="bagi bagi-3 dashboard-item rata-tengah mt-5 mb-3">
        <div class="wrap">

            <div class="bayangan-5 rounded">
                <div class="col-md bg-primer h-25"></div>
                <div class="wrap row col-md">
                    <h1 class="pt-3 ml-3"><i class="fa bi bi-check2-square teks-primer"></i></h1>
                    <div class="wrap rata-kiri mb-0 text-dark">
                        <p class="mb-0">Total Pembelian</p>
                        <h3 class="mb-0 teks-transparan">{{ $count_pkg_all }}</h3>
                    </div>
                </div>
                <div class="rata-kiri from smallPadding bg-primer">
                    <div class="wrap">Pembelian terbaru</p>
                        @if (count($package_payments) != 0)
                            <h4>order ID {{ $package_payments[count($package_payments)-1]->order_id }}</h4>
                        @else
                            <h4>Tidak ada data</h4>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    

    <div>
        <div class="table-responsive">
            <h3>
                <input type="search" placeholder="Search..." class="float-right form-control search-input"
                    style="width: unset" data-table="payments-list" />
            </h3>
            <table id="payments-table" class="table table-borderless payments-list">
                <thead>
                    <tr>
                        <th>Nama paket&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th><i
                                class="fas fa-clock"></i>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                        </th>
                        <th>E-mail&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Harga&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Status&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($package_payments as $item)
                        <tr>
                            <td>{{ $item->package->name }}</td>
                            <td>
                                {{ Carbon::parse($item->created_at)->format('d-m-Y') }}
                            </td>
                            <td>{{ $item->user->email }}</td>
                            <td>@currencyEncode($item->nominal)</td>
                            
                            <td>{{ $item->status == 1 ? 'Terbayar' : 'Belum dibayar' }}</td>
                        </tr>
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

    <script src="{{ asset('js/user/searchTable.js') }}"></script>
    <script src="{{ asset('js/user/paginationTable.js') }}"></script>
    <script>
        $(document).ready(function() {
            paginate('#payments-table', 'payments', 10);
        });
    </script>
@endsection

@extends('layouts.admin')

@section('title', 'Finance Report')

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
    <p class="teks-transparan mb-4">Ringkasan Keuangan Agendakota.id saat ini</p>



    <div class="bagi bagi-3 dashboard-item rata-tengah mt-5 mb-3">
        <div class="wrap">
            <div class="bayangan-5 rounded">
                <div class="col-md bg-primer h-25"></div>
                <div class="wrap row col-md">
                    <h1 class="pt-3 ml-3"><i class="fa bi bi-coin teks-primer"></i></h1>
                    <div class="wrap rata-kiri mb-0 text-dark">
                        <p class="mb-0">Total Pemasukan</p>
                        <h3 class="mb-0 teks-transparan">@currencyEncode($totalIncome)</h3>
                    </div>
                </div>
                <div class="rata-kiri from smallPadding bg-primer">
                    <div class="wrap">Pemasukan terbaru</p>
                        @if ($totalIncome != 0)
                            <h4>Dari order id {{ $allPayment->last()->order_id }}</h4>
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
                        <p class="mb-0">Total Penarikan</p>
                        <h3 class="mb-0 teks-transparan">{{ count($totalWithdraw) }}</h3>
                    </div>
                </div>
                <div class="rata-kiri from smallPadding bg-primer">
                    <div class="wrap">Withdraw Terbaru</p>
                        @if (count($totalWithdraw) != 0)
                            <h4>{{ $totalWithdraw->last()->event->name }}</h4>
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
                        <p class="mb-0">Penarikan Selesai</p>
                        <h3 class="mb-0 teks-transparan">{{ count($withdrawAccept) }}</h3>
                    </div>
                </div>
                <div class="rata-kiri from smallPadding bg-primer">
                    <div class="wrap">Withdraw terbaru</p>
                        @if (count($withdrawAccept) != 0)
                            <h4>{{ end($withdrawAccept)->event->name }}</h4>
                        @else
                            <h4>Tidak ada data</h4>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- -------------------------------------------------------------- --}}

    <div class="bagi bagi-3 dashboard-item rata-tengah mt-5 mb-3">
        <div class="wrap">
            <div class="bayangan-5 rounded">
                <div class="col-md bg-primer h-25"></div>
                <div class="wrap row col-md">
                    <h1 class="pt-3 ml-3"><i class="fa bi bi-x-circle teks-primer"></i></h1>
                    <div class="wrap rata-kiri mb-0 text-dark">
                        <p class="mb-0">Penarikan Gagal</p>
                        <h3 class="mb-0 teks-transparan">{{ count($withdrawDisaccept) }}</h3>
                    </div>
                </div>
                <div class="rata-kiri from smallPadding bg-primer">
                    <div class="wrap">Penarikan terbaru</p>
                        @if (count($withdrawDisaccept) != 0)
                            <h4>{{ end($withdrawDisaccept)->event->name }}</h4>
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
                    <h1 class="pt-3 ml-3"><i class="fa bi bi-credit-card teks-primer"></i></h1>
                    <div class="wrap rata-kiri mb-0 teks-dark">
                        <p class="mb-0">Penarikan Terbaru</p>
                        <h3 class="mb-0 teks-transparan">{{ count($withdrawWaiting) }}</h3>
                    </div>
                </div>
                <div class="rata-kiri from smallPadding bg-primer">
                    <div class="wrap">Withdraw Terbaru</p>
                        @if (count($withdrawWaiting) != 0)
                            <h4>{{ end($withdrawWaiting)->event->name }}</h4>
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
                    <h1 class="pt-3 ml-3"><i class="fa bi bi-envelope-paper teks-primer"></i></h1>
                    <div class="wrap rata-kiri mb-0 text-dark">
                        <p class="mb-0">Penarikan Disetujui</p>
                        <h3 class="mb-0 teks-transparan">@currencyEncode($valueWithdrawAccept)</h3>
                    </div>
                </div>
                <div class="rata-kiri from smallPadding bg-primer">
                    <div class="wrap">Withdraw terbaru</p>
                        @if (count($withdrawAccept) != 0)
                            <h4>{{ end($withdrawAccept)->event->name }}</h4>
                        @else
                            <h4>Tidak ada data</h4>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ------------------------------------------------------------ --}}

    <div class="bagi bagi-3 dashboard-item rata-tengah mt-5 mb-3">
        <div class="wrap">
            <div class="bayangan-5 rounded">
                <div class="col-md bg-primer h-25"></div>
                <div class="wrap row col-md">
                    <h1 class="pt-3 ml-3"><i class="fa bi bi-envelope-x teks-primer"></i></h1>
                    <div class="wrap rata-kiri mb-0 text-dark">
                        <p class="mb-0">Penarikan Gagal</p>
                        <h3 class="mb-0 teks-transparan">@currencyEncode($valueWithdrawInverse)</h3>
                    </div>
                </div>
                <div class="rata-kiri from smallPadding bg-primer">
                    <div class="wrap">Pemasukan terbaru</p>
                        @if (count($withdrawDisaccept) != 0)
                            <h4>{{ end($withdrawDisaccept)->event->name }}</h4>
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
                    <h1 class="pt-3 ml-3"><i class="fa bi bi-envelope-plus teks-primer"></i></h1>
                    <div class="wrap rata-kiri mb-0 teks-dark">
                        <p class="mb-0">Penarikan Terbaru</p>
                        <h3 class="mb-0 teks-transparan">@currencyEncode($valueWithdrawWaiting)</h3>
                    </div>
                </div>
                <div class="rata-kiri from smallPadding bg-primer">
                    <div class="wrap">Withdraw Terbaru</p>
                        @if (count($withdrawWaiting) != 0)
                            <h4>{{ end($withdrawWaiting)->event->name }}</h4>
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
                    <h1 class="pt-3 ml-3"><i class="fa bi bi-currency-exchange teks-primer"></i></h1>
                    <div class="wrap rata-kiri mb-0 text-dark">
                        <p class="mb-0">Dana Belum Ditarik</p>
                        <h3 class="mb-0 teks-transparan">@currencyEncode($valueResidual)</h3>
                    </div>
                </div>
                <div class="rata-kiri from smallPadding bg-primer">
                    <div class="wrap">Dana kotor</p>
                        @if ($valueResidual != 0)
                            <h4>@currencyEncode($valueResidual)</h4>
                        @else
                            <h4>Tidak ada data</h4>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ----------------------------------------------------------- --}}

    <div class="bagi bagi-3 dashboard-item rata-tengah mt-5 mb-3">
        <div class="wrap">

            <div class="bayangan-5 rounded">
                <div class="col-md bg-primer h-25"></div>
                <div class="wrap row col-md">
                    <h1 class="pt-3 ml-3"><i class="fa bi bi-currency-dollar teks-primer"></i></h1>
                    <div class="wrap rata-kiri mb-0 text-dark">
                        <p class="mb-0">Profit Agendakota</p>
                        <h3 class="mb-0 teks-transparan">@currencyEncode($realProfit)</h3>
                    </div>
                </div>
                <div class="rata-kiri from smallPadding bg-primer">
                    <div class="wrap">Keuntungan bersih</p>
                        @if ($realProfit != 0)
                            <h4>@currencyEncode($realProfit) </h4>
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
                        <th>Nama Event&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th><i
                                class="fas fa-clock"></i>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                        </th>
                        <th>Jumlah Transaksi&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Pemasukan&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Profit User&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Profit &emsp;&emsp;&emsp;&emsp;</th>
                        <th>Status penarikan&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paymentEvents as $item)
                        <tr>
                            <td>{{ $item['event']->name }}</td>
                            <td>
                                {{ Carbon::parse($item['event']->start_date)->format('d M Y') . ' - ' . Carbon::parse($item['event']->end_date)->format('d M Y') }}
                            </td>
                            <td>{{ count($item['data']) }}</td>
                            <td>@currencyEncode($item['value'])</td>
                            @php
                                // $nett = (float)$item['value'] - ((float)$item['value']*config('agendakota')['profit']);
                                // ----------- Ubah nilai potongan sesuai config package user by event --------------------------------------------
                                $profitPercentage = $item['event']->organizer->user->package->ticket_commission;
                                $nett = (float) $item['value'] - (float) $item['value'] * $profitPercentage;
                                if ($nett > config('agendakota')['min_transfer'] + config('agendakota')['profit+']) {
                                    $nett -= config('agendakota')['profit+'];
                                }
                                $profit = $nett;
                                $profitAg = $item['value'] - $nett;
                                
                                // $thisWithdraw = \App\Models\UserWithdrawalEvent::where('event_id', $item['event']->id)
                                //     ->where('status', 'accepted')
                                //     ->orWhere('status', 'waiting')
                                //     ->get();
                                // // Mengatasi kesalapahaman pengguna / mengunci data profit jika sudah pernah terjadi withdraw dari user
                                // // Jika tidak dikunci nilai profit bisa berubah ubah sendiri sesuai nilai pensetasi paket jika diubah ubah
                                // if (count($thisWithdraw) == 1) {
                                //     $profit = $item['value'] - (float) $thisWithdraw[0]->nominal;
                                // }
                                
                                $withdrawAcc = \App\Models\UserWithdrawalEvent::where('event_id', $item['event']->id)
                                    ->where('status', 'accepted')
                                    ->get();
                                $withdrawWait = \App\Models\UserWithdrawalEvent::where('event_id', $item['event']->id)
                                    ->where('status', 'waiting')
                                    ->get();
                                if (count($withdrawAcc) == 0 && count($withdrawWait) == 0) {
                                    $profit = $profit;
                                } else {
                                    if (count($withdrawAcc) == 0) {
                                        $profit = (float) $withdrawWait[0]->nominal;
                                        $profitAg = $item['value'] - (float) $withdrawWait[0]->nominal;
                                    } else {
                                        $profit = (float) $withdrawAcc[0]->nominal;
                                        $profitAg = $item['value'] - (float) $withdrawWait[0]->nominal;
                                    }
                                }
                                
                            @endphp
                            <td>@currencyEncode($profit)</td>
                            <td>@currencyEncode($profitAg)</td>
                            <td>
                                @if (count($withdrawAcc) == 0 && count($withdrawWait) == 0)
                                    Belum ditarik
                                @else
                                    @if (count($withdrawAcc) == 0)
                                        Menunggu Acc
                                    @else
                                        Sudah ditarik
                                    @endif
                                @endif
                            </td>
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

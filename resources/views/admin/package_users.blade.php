@extends('layouts.admin')

@section('title', 'Manage User Pkg')

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

    <div>
        <h3>
            <input type="search" placeholder="Search..." class="float-right form-control search-input"
                style="width: unset" data-table="payments-list" />
        </h3>
        <div class="table-responsive">
            
            <table id="payments-table" class="table table-borderless payments-list">
                <thead>
                    <tr>
                        <th>Nama&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>E-Mail&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Nama Paket&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th><i
                                class="fas fa-clock"> Masa Aktif</i>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                        </th>
                        <th>Status Paket&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Aksi&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usersData as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->package->name }}</td>
                            @php
                                $activeTime = new DateTime($item->created_at);
                                $lastPaid = \App\Models\PackagePayment::where('user_id',$item->id)->where('status',1)->orderBy('id','DESC')->first();
                                if($lastPaid->nominal == $item->package->price){
                                    // Tambahkan created_at +30 hari
                                    $activeTime->modify('+30 day');
                                }else{
                                    // Tambahkan +365 hari
                                    $activeTime->modify('+365 day');
                                }
                            @endphp
                            <td>
                                {{ Carbon::parse($activeTime)->format('d-m-Y') }}
                            </td>
                            <td>{{ $item->pkg_status == 1 ? 'Active' : 'Inactive' }}</td>
                            
                            <td>
                                <a class="btn btn-warning mb-2" onclick="munculPopup('#change{{ $item->id }}')">Ubah Masa Aktif</a>
                                <a class="btn btn-success mb-2" href="{{ route('admin.package.user-detail',[$item->id]) }}">Lihat Detail</a>
                            </td>
                        </tr>

                        {{-- PopUp mengubah masa aktif paket user --}}

                        <div class="bg"></div>
                        <div class="popupWrapper" id="change{{ $item->id }}">
                            <div class="popup">
                                <div class="wrap">
                                    <h4>Ubah Masa Aktif {{ $item->email }}
                                        <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#change{{ $item->id }}')"></i>
                                    </h4>
                                    <form action="{{ route('admin.package.change-active') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                                        {{ csrf_field() }}
                                        <div class="bagi lebar-100 pl-3 pr-3">
                                            <div>Masa Aktif Baru {Hari}:</div>
                                            <input type="hidden" name="user_id" value="{{ $item->id }}">
                                            <input type="number" class="box" name="interval" required>
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

    <script src="{{ asset('js/user/searchTable.js') }}"></script>
    <script src="{{ asset('js/user/paginationTable.js') }}"></script>
    <script>
        $(document).ready(function() {
            paginate('#payments-table', 'payments', 10);
        });
    </script>
@endsection

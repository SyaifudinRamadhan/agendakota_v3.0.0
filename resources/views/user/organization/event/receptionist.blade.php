@extends('layouts.user')

@section('title', 'Handbook Event')

@section('head.dependencies')
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/sessionsPage.css') }}">
@endsection

@section('content')
    @php
    use Carbon\Carbon;
    $tanggalSekarang = Carbon::now()->toDateString();
    @endphp

    <div class="">
        <div class="row">
            <div class="col-md-7 mb-4">
                <h2 style="margin-top: -3%; color: #304156; font-size:32px">Receptionist</h2>
                <h4 class="teks-transparan">{{ $event->name }}</h4>
            </div>
            <div class="col-md-5 pl-0">
                <button id="add-rcp" type="button" class="bg-primer mt-0 btn-add btn-no-pd float-right mr-2 mb-4"
                >
                    <i class="fa bi bi-person fs-20"></i> Tambah
                </button>

            </div>
        </div>
    </div>

    @include('admin.partials.alert')

    @if (count($receptionists) == 0)

        <div class="mt-4 rata-tengah">
            <i class="fa fa-clipboard font-img teks-primer mb-4"></i>
            <h3>Mulai Membuat Receptionist untuk Eventmu</h3>
            <p>Adakan berbagai event menarik di AgendaKota</p>
        </div>
    @else
        <div class="table-responsive">
            <h3>
                <input type="search" placeholder="Search..." class="float-right form-control search-input" style="width: unset"
                    data-table="receptionist-list" />
            </h3>
            <table class="table table-borderless receptionist-list">
                <thead>
                    <tr>
                        <th>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Nama&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>E-Mail&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th>Action&emsp;&emsp;&emsp;&emsp;</th>
                    </tr>
                </thead>

                <tbody class="mt-2">
                    @foreach ($receptionists as $receptionist)
                        <tr>
                            <td>
                                @if ($receptionist->user->photo == 'default')
                                    <img class="img-table" src="{{ asset('storage/profile_photos/profile-user.png') }}">
                                @else
                                    <img class="img-table"
                                        src="{{ asset('storage/profile_photos/' . $receptionist->user->photo) }}">
                                @endif
                            </td>
                            <td>
                                {{ $receptionist->user->name }}
                            </td>
                            <td>
                                {{ $receptionist->user->email }}
                            </td>
                            <td>
                                <form
                                    action="{{ route('organization.event.receptionist.delete', [$organizationID, $event->id]) }}"
                                    method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="ID" value="{{ $receptionist->id }}">
                                    <button type="submit" class="teks-merah pointer btn bg-putih">
                                        <i class="fas fa-trash fs-20"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex float-right">
            {{ $receptionists->links() }}
        </div>
    @endif

    <!-- Modal PopUp -->

    <div class="bg"></div>
    <div class="popupWrapper" id="addReceptionist">
        <div class="lebar-50 popup rounded-5">
            <h3><i class="fas bi bi-x-circle-fill op-5 mt-3 pr-3 ke-kanan pointer" onclick="hilangPopup('#addReceptionist')"></i></h3>
            <div class="pl-5 pr-5">
                <h3 class="mt-5 rata-tengah">Tambah Receptionist</h3>
                <div class="mt-2">List Anggota Team :</div>
                <form action="{{ route('organization.event.receptionist.store', [$organizationID, $event->id]) }}"
                    method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="eventId" value="{{ $event->id }}">
                    <div id="teams">
                        <select class="box no-bg js-example-basic-multiple lebar-100" name="receptionist[]"
                            multiple="multiple">
                            <option value="{{ $myData->id }}"
                                data-icon="{{ $myData->photo == 'default' ? 'profile-user.png' : $myData->photo }}">
                                {{ $myData->name }}</option>
                            @forelse ($teams as $team)
                                <option value="{{ $team->users->id }}"
                                    data-icon="{{ $team->users->photo == 'default' ? 'profile-user.png' : $team->users->photo }}">
                                    {{ $team->users->name }}</option>
                            @empty
                                <option value="" disabled> Anda Belum Membuat Team</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="lebar-100 rata-tengah">
                        <button type="submit" class="btn bg-primer mt-3 mb-4">
                            Tambahkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- End Modal PopUp -->

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

        function addReceptionist() {
            Swal.fire({
                title: "Informasi",
                text: "Untuk menambah resepsionis, pastikan kamu sudah memiliki anggota tim di organisasimu",
                type: "info",
                icon: "info",
                dangerMode: true,
                showCancelButton: true,
                confirmButtonText: "Ok. Lanjutkan",
                cancelButtonText: "Belum Punya Tim",
                closeOnConfirm: false,
                closeOnCancel: false
            }).then((result) => {
                if (result.isConfirmed == false) {
                    window.location.replace("{{ route('organization.profilOrganisasi',[$organizationID,'to=team']) }}");
                }else{
                    munculPopup('#addReceptionist');
                }
            });
        }

        document.getElementById('add-rcp').addEventListener('click', function() {
           addReceptionist(); 
        });
    </script>
    <script src="{{ asset('js/user/searchTable.js') }}"></script>

@endsection

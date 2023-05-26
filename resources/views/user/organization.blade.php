@extends('layouts.user')

@section('title', 'Organization')

@section('head.dependencies')
    <style>
        .picture {
            display: inline-block;
            width: 100px;
            height: 100px;
            border-radius: 100px;
            margin-top: -50px;
        }

        .connection-item {
            margin-top: 70px;
        }

        .connection-item h3 {
            font-size: 18px;
        }

        .connection-item .socmed a {
            color: #444;
        }

        .connection-item .socmed a:hover {
            color: #e5214f; /*#eb597b*/
        }

        .connection-item .socmed li {
            list-style: none;
            display: inline-block;
            margin: 0px 5px;
            font-size: 18px;
        }

        .connection-item .from {
            background-color: #ecf0f1;
            margin-top: 20px;
        }

        .connection-item .from p {
            line-height: 25px;
            font-size: 13px;
            margin: 0px;
        }

        .connection-item .from h4 {
            font-size: 15px;
            margin: 0px;
            margin-top: 2px;
            font-family: DM_SansBold !important;
        }

        .none-link {
            text-decoration: none !important;
            text-decoration-color: black !important;
        }

    </style>
@endsection


@section('content')
    <h2>Organization</h2>
    <div class="row pb-5">
        <p class="teks-transparan col-lg-9">Daftar Organisasi yang Anda kelola</p>
        <a class="btn col-lg-3 col bg-primer text-light ml-auto" onclick="confirmAddOr()">
            <i class="fas fa-plus pointer text-light"></i> Buat Organisasi Baru
        </a>
    </div>

    @include('admin.partials.alert')
    
    @foreach ($organization as $data)

        <div class="bagi bagi-4 connection-item rata-tengah mt-5 mb-3">
            <a href="{{ route('organization.profilOrganisasi', $data->id) }}" class="none-link">
                <div class="wrap">
                    <div class="bayangan-5 rounded">
                        
                        @if ($data->logo == '')
                            <img src="{{ asset(asset('storage/organization_logo/default_logo.png')) }}" class="picture">
                        @else
                            <img src="{{ asset('storage/organization_logo/'.$data->logo) }}" class="picture">
                        @endif
                        <div class="wrap">
                            <h3>{{ $data->name }}</h3>
                            <div class="socmed">
                                <a href="{{ $data->linked }}" target="_blank">
                                    <li><i class="fab fa-linkedin"></i></li>
                                </a>
                                <a href="{{ $data->instagram }}" target="_blank">
                                    <li><i class="fab fa-instagram"></i></li>
                                </a>
                                <a href="{{ $data->twitter }}" target="_blank">
                                    <li><i class="fab fa-twitter"></i></li>
                                </a>
                            </div>
                        </div>
                        
                        <div class="text-center w-100 pb-3">
                            <a type="button" class="btn btn-danger text-light" data-toggle="modal" data-target="#delModal{{$data->id}}" style="position: relative; z-index: 4">
                                Delete
                            </a>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="delModal{{$data->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel{{$data->id}}" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel{{$data->id}}">Konfirmasi Hapus Organisasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form method="POST" action="{{route('organization.delete', $data->id)}}" class="mt-4">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="org-name">Ketikkan nama organisasi berikut <b>{{ $data->name }}</b> untuk konfirmasi penghapusan</label>
                            <input type="text" class="form-control" name='org_name' id="org-name" placeholder="Nama organisasi">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>

            </div>
            </div>
        </div>

    @endforeach
    
    @if (count($organizationTeam) > 0)
        <p class="teks-transparan mt-4 mb-2">Daftar Organisasi yang Kamu Ikuti</p>
    @endif
    @foreach ($organizationTeam as $data)

        <div class="bagi bagi-4 connection-item rata-tengah mt-5 mb-3">
            <a href="{{ route('organization.profilOrganisasi', $data->organizations->id) }}" class="none-link">
                <div class="wrap">
                    <div class="bayangan-5 rounded">
                        @if ($data->organizations->logo == '')
                            <img src="{{ asset(asset('storage/organization_logo/default_logo.png')) }}" class="picture">
                        @else
                            <img src="{{ asset('storage/organization_logo/'.$data->organizations->logo) }}" class="picture">
                        @endif
                        <div class="wrap">
                            <h3>{{ $data->organizations->name }}</h3>
                            <div class="socmed">
                                <a href="{{ $data->organizations->linked }}" target="_blank">
                                    <li><i class="fab fa-linkedin"></i></li>
                                </a>
                                <a href="{{ $data->organizations->instagram }}" target="_blank">
                                    <li><i class="fab fa-instagram"></i></li>
                                </a>
                                <a href="{{ $data->organizations->twitter }}" target="_blank">
                                    <li><i class="fab fa-twitter"></i></li>
                                </a>
                            </div>
                        </div>
                        <div class="rata-kiri from smallPadding pb-3">                                                            
                            
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach

@endsection

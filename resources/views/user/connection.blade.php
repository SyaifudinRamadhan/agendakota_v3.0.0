@extends('layouts.user')

@section('title', 'Connections')

@php
// $datas = [
//     [
//         'name' => "Riyan Satria",
//         'photo' => "riyan.jpg"
//     ],
//     [
//         'name' => "Riyan Satria",
//         'photo' => "riyan.jpg"
//     ],
//     [
//         'name' => "Riyan Satria",
//         'photo' => "riyan.jpg"
//     ],
//     [
//         'name' => "Riyan Satria",
//         'photo' => "riyan.jpg"
//     ]
// ];
@endphp

@section('head.dependencies')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/connectionPage.css') }}">
@endsection

@section('content')

    <div class="bagi bagi-2 mb-3">
        <h2>Connections</h2>
        <p class="teks-transparan mb-4">Temukan Koneksi dari Event yang telah kamu hadiri</p>
    </div>
    <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-4">
            <div class="square toggle-view-button" mode="table" onclick="toggleView(this)">
                <i class="fa fa-bars"></i>
            </div>
            <div class="square active toggle-view-button" mode="card" onclick="toggleView(this)">
                <i class="fa fa-th-large"></i>
            </div>
        </div>
    </div>

    <div id="card-mode" class="d-block">
        @if (count($connection) == 0)
            <div class="mt-5">
                <div class="mt-5 rata-tengah">
                    <i class="bi bi-people teks-primer font-img"></i>
                    <h3>Bertemu dengan orang - orang baru!</h3>
                    <p>Temukan koneksi lewat berbagai event menarik</p>
                </div>
            </div>
        @else
            @foreach ($connection as $con_id)
                @foreach ($users as $user)
                    @if ($user->id == $con_id->connection_id)
                        <div class="bagi bagi-3 connection-item mt-2">
                            <div class="wrap">
                                <div class="bayangan-5 rounded">

                                    @if ($user->photo == 'default')
                                        <img src="{{ asset('images/profile-user.png') }}" class="picture">
                                    @else
                                        <img src="{{ asset('storage/profile_photos/' . $user->photo) }}"
                                            class="picture">
                                    @endif
                                    <div class="wrap ml-24">
                                        <h3>{{ $user->name }}</h3>
                                        <p class="teks-transparan">
                                            @if (count($user->organizations) > 0)
                                                {{ $user->organizations[0]->name }}
                                            @else
                                                No Organization
                                            @endif
                                        </p>
                                    </div>
                                    <div class="rata-kiri from smallPadding">
                                        <div class="">
                                            <p class="teks-transparan">Dari Event</p>
                                            @if ($con_id->from_id == '0')
                                                <h4>Kenalan</h4>
                                            @else
                                                @foreach ($events as $data_event)
                                                    @if ($con_id->from_id == $data_event->id)
                                                        <h4>{{ $data_event->name }}</h4>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div class="socmed socmed-box ml-24">
                                        <a href="{{ $user->linkedin_profile }}">
                                            <li><i class="fab fa-linkedin"></i></li>
                                        </a>
                                        <a href="{{ $user->instagram_profile }}">
                                            <li><i class="fab fa-instagram"></i></li>
                                        </a>
                                        <a href="{{ $user->twitter_profile }}">
                                            <li><i class="fab fa-twitter"></i></li>
                                        </a>
                                    </div>
                                    <div class="btn-message-box">
                                        <a class="btn btn-shadow teks-primer"
                                            href="{{ route('user.messages', ['msgTo' => $user->id]) }}">
                                            <i class="fa bi bi-chat-left-text mr-2"></i>Message
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        @endif
    </div>
    <div id="table-mode" class="d-none table-responsive">
        @if (count($connection) == 0)
            <div class="mt-5">
                <div class="mt-5 rata-tengah">
                    <i class="bi bi-people teks-primer font-img"></i>
                    <h3>Bertemu dengan orang - orang baru!</h3>
                    <p>Temukan koneksi lewat berbagai event menarik</p>
                </div>
            </div>
        @else
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th scope="col">User Name</th>
                        <th scope="col">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                        <th scope="col">Oraganisasi&emsp;&emsp;</th>
                        <th scope="col">Dari Event&emsp;&emsp;</th>
                        <th scope="col">&emsp;&emsp;&emsp;</th>
                        <th scope="col">&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                    </tr>
                </thead>
                <tbody class="mt-2">
                    @foreach ($connection as $con_id)
                        @foreach ($users as $user)
                            @if ($user->id == $con_id->connection_id)
                                <tr>
                                    <td>
                                        @if ($user->photo == 'default')
                                            <img src="{{ asset('images/profile-user.png') }}" class="picture-table">
                                        @else
                                            <img src="{{ asset('storage/profile_photos/' . $user->photo) }}"
                                                class="picture-table">
                                        @endif
                                    </td>
                                    <td style="width: 100px">
                                        <p class="fontBold font-weight-bold p-no-margin">{{ $user->name }}</p>
                                    </td>
                                    <td>
                                        @if (count($user->organizations) == 0)
                                            <p class="fontBold font-weight-bold teks-primer p-no-margin">No Oragnizations
                                            </p>
                                        @endif

                                        @foreach ($user->organizations as $userOrganization)
                                            <p class="fontBold font-weight-bold teks-primer p-no-margin">
                                                {{ $userOrganization->name }}</p>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($con_id->from_id == '0')
                                            <p class="fontBold font-weight-bold p-no-margin">Kenalan</p>
                                        @else
                                            @foreach ($events as $data_event)
                                                @if ($con_id->from_id == $data_event->id)
                                                    <p class="fontBold font-weight-bold p-no-margin">
                                                        {{ $data_event->name }}</p>
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-shadow teks-primer" href="">
                                            <i class="fa bi bi-chat-left-text mr-2"></i>Message
                                        </a>
                                    </td>
                                    <td>
                                        <i class="fa fa-ellipsis-h pointer dropdownToggle" data-id="{{ $con_id->id }}"
                                            style=" color: #C4C4C4; font-size:20pt; float: right;" aria-hidden="true"></i>
                                    </td>
                                    <div id="Dropdown{{ $con_id->id }}" class="dropdown-content ml-auto mr-auto" style="min-width: 180px">
                                        <a class="mt-3" href="{{ $user->linkedin_profile }}">
                                            <i class="fab fa-lg fa-linkedin d-content"></i>
                                            <p class="fontBold d-inline-block font-weight-bold">Linkedin</p>
                                        </a>
                                        <a class="" href="{{ $user->instagram_profile }}">
                                            <i class="fab fa-lg fa-instagram  d-content"> </i>
                                            <p class="fontBold d-inline-block font-weight-bold">Instagram</p>
                                        </a>
                                        <a class="" href="{{ $user->twitter_profile }}">
                                            <i class="fab fa-lg fa-twitter d-content"> </i>
                                            <p class="fontBold d-inline-block font-weight-bold">Twitter</p>
                                        </a>
                                    </div>
                                </tr>

                                
                            @endif
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection

<script src="{{ asset('js/user/connectionPage.js') }}"></script>

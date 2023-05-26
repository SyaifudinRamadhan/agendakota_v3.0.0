@extends('layouts.user')

@section('title', 'Messages')

@php
use Carbon\Carbon;
@endphp

@section('head.dependencies')
    <link rel="stylesheet" href="{{ asset('css/user/messagesPage.css') }}">
    <style>
        .wrapper {
            top: 155px;
            height: calc(100% - 155px);
        }
        .wrapper-mobile{
            top: 160px;
            height: calc(100% - 160px);
        }
    </style>
@endsection

@section('content')

    <div class="row row-content">
        <div class="messages-kiri col-md-5">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('gagal'))
                <div class="alert alert-danger alert-dismissible fade show mb-3">{{ session('gagal') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="mb-4">
                <h2 class="teks-tebal">Messages</h2>
                <div class="row">
                    <p class="teks-transparan mb-4 col-md-8 font-16">Temukan informasi dan pesan baru</p>
                    <button id="add-msg" class="float-right btn bg-primer font-inter col-md-4 btn-oval h-100" data-toggle="modal"
                        data-target="#add_koneksi"><i class="fa fa-plus"></i> Pesan
                        Baru</button>
                </div>
            </div>
            <input type="hidden" id="msg-click" value="{{ $clickForm }}">
            <div class="messages-kiri-detil col-md">
                @foreach ($connection as $con_id)
                    @foreach ($user as $users)
                        @if ($users->id == $con_id->connection_id)
                            <div id="{{ $users->id }}" class="chat-list mt-2 mb-2" data-id="{{ $users->id }}" data-nama="{{ $users->name }}" data-email="{{ $users->email }}" data-photo="{{ $users->photo == 'default' ? asset('images/profile-user.png') : asset('storage/profile_photos/'.$users->photo) }}" data-aos="fade-down">
                                <div class="mb-0">
                                    <div class="row col-md pt-2 ml-1">
                                        @if ($users->photo == 'default')
                                            <img class="card-img-top img-profile" src="{{ asset('images/profile-user.png') }}"
                                            alt="">
                                        @else
                                            <img class="card-img-top img-profile" src="{{ asset('storage/profile_photos/'.$users->photo) }}"
                                            alt="">
                                        @endif
                                        <p class="card-title ml-2 mb-0 font-18">{{ $users->name }}</p>
                                        {{-- <p class="ml-auto teks-transparan teks-kecil d-flex pt-1">10.46</p> --}}
                                    </div>
                                    <div class="col-md d-inline" id="notif{{ $users->id }}">
                                        <p class="teks-transparan d-inline font-16 id-user" id="latest{{ $users->id }}"
                                            data-id="{{ $users->id }}"></p>
                                        {{-- {{ Str::limit('Lorem ipsum dolor sit amet consectetur adipisicing elit', 40) }}</p> --}}
                                        {{-- <p class="float-right">1</p> --}}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach

            </div>
        </div>

        <div class="col-md-7 content-no-msg justify-content-center d-flex">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-dots icon-no-msg" viewBox="0 0 16 16">
                  <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                  <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9.06 9.06 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.437 10.437 0 0 1-.524 2.318l-.003.011a10.722 10.722 0 0 1-.244.637c-.079.186.074.394.273.362a21.673 21.673 0 0 0 .693-.125zm.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6c0 3.193-3.004 6-7 6a8.06 8.06 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a10.97 10.97 0 0 0 .398-2z"/>
                </svg>
                <div>
                    <h4 class="text-center text-no-msg">Kamu belum membuka pesan chat di daftar kontak sebelah kiri.</h4>
                </div>
            </div>
        </div>

    </div>


    <!-- Modal -->
    <div class="modal fade" id="add_koneksi" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-5">
                <div class="modal-header mx-auto">
                    <h5 class="modal-title ">Tambah Pesan Baru</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('message.tambah.user') }}" method="post">
                        @csrf
                        <div class="form-group col-md-12 mx-auto">
                            <select type="text" name="search" id="search" class="search form-control col-md-12"
                                placeholder="Masukkan Nama atau Email" required></select>
                            <input type="hidden" id="connection_id" name="connection_id" value="" hidden>
                            <button type="submit" class="btn bg-primer col-md mt-3 rounded-5">Tambahkan</button>
                        </div>
                </div>
                <div class="modal-footer">
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('body_only')
    <!-- <input type="checkbox" id="check"> <label class="chat-btn" for="check"> <i class="fa fa-commenting-o comment"></i> <i class="fa fa-close close"></i> </label> -->
    <div class="wrp-set wrapper" style="display: none">
        <div class="header-chat">
            <div class="rata-kiri d-inline">
               <img class="card-img-top img-profile2" src="{{ asset('images/profile-user.png') }}" alt="">
            </div>
            <div class="d-inline ms-3 me-5 rata-kiri box-name-msg">
                <p class="mb-0 nama-pengirim black-text"></p>
                <p class="teks-kecil d-flex email-pengirim black-text"></p>
            </div>
            <i class="bi bi-x-circle text-danger text-right close-msg-btn"></i>
        </div>

        <div id="msgBox" data-id="0" class="messages-kanan-detil col-md">

        </div>
        <div class="messages-kanan-kirim bg-white">
            <form class="form-chat" method="post">
                @csrf
                @method('get')

                <div class="form-group">
                    <div class="input-group">
                        <input class="input-chat tulis-pesan form-control mt-2 rounded-5" type="text" name="message" id="message"placeholder="Tuliskan pesanmu di sini">
                        <input class="hidden receiver" type="text" name="receiver" id="receiver" value="receiver" hidden>
                        <a class="btn bg-primer text-light mt-2 tombol-kirim rounded-5 simpan disabled ">
                            <i class="fa fa-send"></i>
                            Kirim
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>



@endsection

@section('javascript')
   
    <script src="{{ asset('js/user/messagesPage.js') }}"></script>
@endsection

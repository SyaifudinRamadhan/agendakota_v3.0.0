@extends('layouts.homepage')

@section('title', 'Meet Exhibitions')

@section('head.dependencies')
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/mainUser.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/handbookPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/eventDetailPage.css') }}">

@endsection

@section('content')

    <style>
        #zmmtg-root {
            height: 100%;
            position: fixed;
            top: 60px;
            /* display: none; */
        }

        .zmmtg-root-att {
            width: 80% !important;
            left: 20% !important;
            background-color: black;
            margin-top: 100px;
        }

        .meeting-client {
            height: 100%;
            position: fixed;
            top: 60px;
        }

        .meeting-client-add {
            width: 80%;
            left: 20%;
        }

        .meeting-client-inner {
            height: calc(100% - 60px);
            position: fixed;
            top: 60px;
        }

        .meeting-client-inner-add {
            width: 80%;
            left: 20%;
        }

        .sharee-container {
            /* position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center; */
            margin-left: 25%;
        }

        .chat-container__chat-control {
            display: none;
        }

        .chat-box__chat-textarea {
            font-size: 15px;
        }

        /* .undefined{
        display: none;
    } */
        .more-button {
            display: none;
        }

        .footer__leave-btn-container {
            display: none;
        }

        .participants-item__item-layout {
            padding-top: 15px;
            padding-bottom: 15px;
        }

        .dialog-rename-container {
            display: block !important;
        }

        /* .zm-modal-legacy{
        display: none;
    } */
        .zm-modal.zm-modal-legacy {
            border-radius: 4px;
            -webkit-box-shadow: 0 5px 15px rgb(0 0 0 / 50%);
            box-shadow: 17px 20px 15px 20px rgb(0 0 0);
            border: 1px solid rgb(0 0 0 / 50%);
            padding: 0;
        }

        .participants-item__buttons {
            display: block;
        }

        .left {
            position: fixed;
            top: 60px;
            left: 0px;
            bottom: 0px;
            width: 20%;
            border-right: 1px solid #eaeaea;
            overflow: auto;
            padding-bottom: 15px;
        }

        .footer-item{
            display: none;
        }

        .bottom{
            display: none;
        }

    </style>

    <div class="left nav-atas">
        <div class="pl-5 pr-5 mt-5 mb-5">
            <div class="row">
                <div class="col-md text-left">
    
                    <h4>
                        <img src="{{ asset('storage/event_assets/' . $exhibitor->event->slug . '/exhibitors/exhibitor_logo/' . $exhibitor->logo) }}"
                            class="rounded-circle asp-rt-1-1" width="58px" height="58px">
                        {{ $exhibitor->name }}
                    </h4>
                    <span class="text-secondary">{{ $exhibitor->event->name }} Exhibitions</span>
    
                </div>
                <div class="col-md pl-0 text-right">
                    <a>
                        <button type="button" class="bg-primer mt-0 btn-add btn-no-pd pl-3 pr-3 float-right mr-2 mb-4"
                            onclick="history.back()">
                            <i class="fas fa-arrow-left fs-20"></i>
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
                @foreach ($exhibitor->products as $prd)
                    <div class="col-xl-6 mb-4">

                        <div class="bg-putih rounded-5 bayangan-5">
                            <a href="{{ $prd->url }}">
                                <div class="tinggi-150 rata-tengah">
                                    <img src="{{ $prd->image }}" width="100%" height="100%" alt="">
                                </div>
                            </a>
                            <div class="">
                                <div class="wrap pb-1">
                                    <a href="{{ $prd->url }}">
                                        <h6 class="detail font-inter-header mt-2 text-dark"
                                            style="text-decoration: none">{{ $prd->name }}

                                        </h6>
                                    </a>
                                    <div class="row">
                                        <p class="teks-transparan fs-normal detail">
                                            {{ $prd->price }}</p>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
        </div>
    </div>

    
    {{-- @dd($exhibitors[1]->products) --}}
    <div id="streaming" class="content container-cadangan d-block mt-3">
        <input type="hidden" value="{{ $meet_id }}" id="meeting_number" name="meeting_number">
        <input type="hidden" value="{{ $meet_pwd }}" id="meeting_pwd" name="meeting_pwd">
        <input type="hidden" value="0" id="meeting_role" name="meeting_role">
        <input type="hidden" value="{{ $email_peserta }}" id="meeting_email" name="meeting_email">
        <input type="hidden" value="en-US" id="meeting_lang" name="meeting_lang">
        <input type="hidden" value="English" id="meeting_china" name="meeting_china">
        <input type="hidden" value="{{ $nama_peserta }}" id="display_name" name="display_name">
        <input type="hidden" value="{{ $url_leave }}" id="url_leave" name="url_leave">

        <script src="https://source.zoom.us/1.9.0/lib/vendor/react.min.js"></script>
        <script src="https://source.zoom.us/1.9.0/lib/vendor/react-dom.min.js"></script>
        <script src="https://source.zoom.us/1.9.0/lib/vendor/redux.min.js"></script>
        <script src="https://source.zoom.us/1.9.0/lib/vendor/redux-thunk.min.js"></script>
        <script src="https://source.zoom.us/1.9.0/lib/vendor/lodash.min.js"></script>
        <script src="https://source.zoom.us/zoom-meeting-1.9.0.min.js"></script>

        <script src="{{ asset('js/zoom-js/tool.js') }}"></script>
        <script src="{{ asset('js/zoom-js/vconsole.min.js') }}"></script>
        <script src="{{ asset('js/zoom-js/meeting.js') }}"></script>
        <script>
            var API_KEY = "5Ktt2Z3fQTyn0wSVVTKpXg";
            var API_SECRET = "S4psQKnlyJWkXEzgP3H9fyEJ5ryft3tPCJlM";
        </script>
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
                crossorigin="anonymous"></script>

        <script src="{{ asset('js/user/exhibitorStream.js') }}"></script>
        <script>
            setInterval(() => {
                detectAuto();
            }, 500);
        </script>

        <div id="stream-blank" class="text-center mt-5" style="display: none">
            <h4>Exhibitor Belum Memasuki Room Meet</h4>
            <h6>Silahkan tunggu sebentar, dan reload ulang halaman ini untuk bisa masuk room meet</h6>
        </div>
    </div>
    {{-- <div id="addProduct" style="display: none">
        <div class="bg" style="display: block !important"></div>
        <div class="popupWrapper" style="display: block !important">
            <div class="lebar-50 popup rounded-5 pl-5 pr-5">
                <h3 class="mt-5 rata-tengah">Upload Produk
                    <i class="fas bi bi-x-circle-fill op-5 ke-kanan pointer" onclick="hilangPopup('#addProduct')"></i>
                </h3>
                <p class="teks-transparan rata-tengah mt-0 mb-0 no-pd-t">
                    Unggah url online shop produk yang akan kamu pamerkan
                </p>
                <p class="teks-transparan rata-tengah mt-0 mb-0 no-pd-t">
                    (NB: Gunakan URL online shop shopee, tokopedia, ataupun bukalapak.)
                </p>
                <form class="text-left mt-4" action="{{ route('productSave', [$eventID]) }}" method="POST">
                    @csrf
                    Inputkan link detail produknya satu persatu. Ex : <a
                        href="https://shopee.co.id/bonkyo-Wireless-Optical-Mouse-Dan-Minimalism-MSE6-i.159093915.3717413132?sp_atk=3cf3fd21-966c-48ec-9bf4-14905e1b54a4">Klik
                        Untuk lihat</a>
                    <input type="url" name="url" class="mb-3 box bg-putih rounded-5"
                        placeholder="URL shopee / tokopedia / bukalapak">
                    <div class="lebar-100 rata-tengah mb-5">
                        <button type="submit" class="btn bg-primer lebar-40">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    <!-- End Modal PopUp -->
    <input id="err_code_join" type="hidden" name="err_code_join" value="">
    <input id="join_status" type="hidden" name="join_status" value="">
    <input id="meet_status" type="hidden" name="meet_status" value="">
@endsection

@section('javascript')
    <script src="{{ asset('js/base.js') }}"></script>
@endsection

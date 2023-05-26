@extends('layouts.user')

@section('title', 'Handbook Event')

@section('head.dependencies')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/handbookPage.css') }}">

@section('content')
    @php
    use Carbon\Carbon;
    $tanggalSekarang = Carbon::now()->toDateString();
    @endphp

    <div class="">
        <div class="row">
            <div class="col-lg-7 mb-4">
                <h2 style="margin-top: -3%; color: #304156; font-size:32px">Handbook</h2>
                <h4 class="teks-transparan">{{ $event->name }}</h4>
            </div>
            <div class="col-lg-5 pl-0">
                <button type="button" class="bg-primer mt-0 btn-add btn-no-pd float-right mr-2 mb-4"
                    onclick="munculPopup('#addSession')">
                    <i class="fa bi bi-cloud-arrow-up fs-20"></i> Upload
                </button>
                <div class="square toggle-view-button" mode="table" onclick="toggleView(this)">
                    <i class="fa fa-bars"></i>
                </div>
                <div class="square active toggle-view-button" mode="card" onclick="toggleView(this)">
                    <i class="fa fa-th-large"></i>
                </div>

            </div>
        </div>
    </div>

    @include('admin.partials.alert')

    <div class="tab scrollmenu" style="border: none">
        <button class="tab-btn tablinks-event-handbook active" onclick="openTabs(event, 'event-handbook', 'photo')">Photo</button>
        <button class="tab-btn tablinks-event-handbook" onclick="openTabs(event, 'event-handbook', 'video')">Video</button>
        <button class="tab-btn tablinks-event-handbook" onclick="openTabs(event, 'event-handbook', 'documents')">Documents</button>
    </div>

    <!-- Belum tersentuh -->
    <div id="card-mode" class="d-block mt-5">
        @if (count($handbooks) == 0)
            <div class="mt-4 rata-tengah">
                <i class="fa fa-file font-img teks-primer mb-4"></i>
                <h3>Mulai Membuat Handbook untuk Eventmu</h3>
                <p>Adakan berbagai event menarik di AgendaKota</p>
            </div>
        @else
            <div class="photo tabcontent-event-handbook mt-3" style="display: block; border: none;">
                <div class="row">
                    @foreach ($handbooks as $handbook)
                        @if ($handbook->type_file == 'photo')
                            <div class="col-lg-4 mb-4">
                                <div class="bg-putih rounded-5 bayangan-5">
                                    <a
                                        href="{{ asset('storage/event_assets/' . $event->slug . '/event_handbooks/' . $handbook->file_name) }}">
                                        <div class="tinggi-150"
                                            bg-image="{{ asset('storage/event_assets/' . $event->slug . '/event_handbooks/' . $handbook->file_name) }}">
                                        </div>
                                    </a>
                                    <div class="">
                                        <div class="wrap pb-1">
                                            <h6 class="detail font-inter-header mt-2">{{ $handbook->file_name }}

                                            </h6>
                                            <p class="teks-transparan fs-normal detail">Uploaded
                                                {{ Carbon::parse($handbook->created_at)->format('d M,') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="video tabcontent-event-handbook mt-3" style="display: none; border: none;">
                <div class="row">
                    @foreach ($handbooks as $handbook)
                        @if ($handbook->type_file == 'video')
                            <div class="col-lg-4 mb-4">
                                <div class="bg-putih rounded-5 bayangan-5">
                                    <div class="tinggi-150">
                                        <iframe class="lebar-100 rounded-15-top" height="100%"
                                            src="{{ $handbook->file_name }}"></iframe>
                                    </div>
                                    <div class="">
                                        <div class="wrap pb-1">
                                            <h6 class="detail font-inter-header mt-2">{{ $handbook->file_name }}

                                            </h6>
                                            <p class="teks-transparan fs-normal detail">Uploaded
                                                {{ Carbon::parse($handbook->created_at)->format('d M,') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="documents tabcontent-event-handbook mt-3" style="display: none; border: none;">
                <div class="row">
                    @foreach ($handbooks as $handbook)
                        @if ($handbook->type_file == 'documents')
                            <div class="col-lg-4 mb-4">
                                <a
                                    href="{{ asset('storage/event_assets/' . $event->slug . '/event_handbooks/' . $handbook->file_name) }}">
                                    <div class="bg-putih rounded-5 bayangan-5">
                                        <div class="tinggi-150 rata-tengah">
                                            <i class="fa bi bi-file-earmark-text mt-5 teks-primer text-icon-2"></i>
                                        </div>
                                        <div class="">
                                            <div class="wrap pb-1">
                                                <h6 class="detail font-inter-header mt-2">{{ $handbook->file_name }}

                                                </h6>
                                                <p class="teks-transparan fs-normal detail">Uploaded
                                                    {{ Carbon::parse($handbook->created_at)->format('d M,') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

        @endif
    </div>

    <div id="table-mode" class="table-mode d-none">
        @if (count($handbooks) == 0)
            <div class="mt-4 rata-tengah">
                <i class="fa fa-file font-img teks-primer mb-4"></i>
                <h3>Mulai Membuat Handbook untuk Eventmu</h3>
                <p>Adakan berbagai event menarik di AgendaKota</p>
            </div>
        @else
            <div class="photo tabcontent-event-handbook table-block" style="display: block; border: none;">
                <h3>
                    <input type="search" placeholder="Search..." class="float-right form-control search-input" style="width: unset"
                        data-table="image-list" />
                </h3>
                <div id="dummy-table" class="table-responsive" style="height: 10px">
                    <table class="table table-borderless image-list">
                        <thead>
                            <tr>
                                <th style="min-width: 100px"></th>
                                <th style="min-width: 200px"></th>
                                <th style="min-width: 200px"></th>
                                <th style="min-width: 100px"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div id="real-table" class="table-responsive">
                    <table class="table table-borderless image-list">
                        <thead>
                            <tr>
                                <th style="min-width: 100px"></th>
                                <th style="min-width: 200px">File Name</th>
                                <th style="min-width: 200px">Uploaded</th>
                                <th style="min-width: 100px">Action</th>
                            </tr>
                        </thead>
                        <tbody class="mt-2">
                            @foreach ($handbooks as $handbook)
                                @if ($handbook->type_file == 'photo')
                                    <tr>
                                        <td><img class="img-table"
                                                src="{{ asset('storage/event_assets/' . $event->slug . '/event_handbooks/' . $handbook->file_name) }}">
                                        </td>
                                        <td class="fontBold font-weight-bold">
                                            <a
                                                href="{{ asset('storage/event_assets/' . $event->slug . '/event_handbooks/' . $handbook->file_name) }}">{{ $handbook->file_name }}</a>
                                        </td>
                                        <td>Uploaded {{ Carbon::parse($handbook->created_at)->format('d M,') }}</td>
                                        <td>
                                            <form
                                                action="{{ route('organization.event.handbooks.delete', [$organizationID, $event->id]) }}"
                                                method="POST">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="typeFile" value="photo">
                                                <input type="hidden" name="ID" value="{{ $handbook->id }}">
                                                <button type="submit" class="teks-merah pointer btn bg-putih">
                                                    <i class="fas fa-trash fs-20"></i>
                                                </button>
                                            </form>
    
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="video tabcontent-event-handbook table-block" style="display: none; border: none;">
                <h3>
                    <input type="search" placeholder="Search..." class="float-right form-control search-input" style="width: unset"
                        data-table="video-list" />
                </h3>
                <div id="dummy-table1" class="table-responsive" style="height: 10px">
                    <table class="table table-borderless image-list">
                        <thead>
                            <tr>
                                <th style="min-width: 100px"></th>
                                <th style="min-width: 200px"></th>
                                <th style="min-width: 200px"></th>
                                <th style="min-width: 100px"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div id="real-table1" class="table-responsive">
                    <table class="table table-borderless video-list">
                        <thead>
                            <tr>
                                <th style="min-width: 100px"></th>
                                <th style="min-width: 200px">URL YouTube (embed url)</th>
                                <th style="min-width: 200px">Uploaded</th>
                                <th style="min-width: 100px">Action</th>
                            </tr>
                        </thead>
    
                        <tbody class="mt-2">
                            @foreach ($handbooks as $handbook)
                                @if ($handbook->type_file == 'video')
                                    <tr>
                                        <td><iframe class="rounded-5" src="{{ $handbook->file_name }}"></iframe></td>
                                        <td class="fontBold font-weight-bold">{{ $handbook->file_name }}</td>
                                        <td>Uploaded {{ Carbon::parse($handbook->created_at)->format('d M,') }}</td>
                                        <td>
                                            <form
                                                action="{{ route('organization.event.handbooks.delete', [$organizationID, $event->id]) }}"
                                                method="POST">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="typeFile" value="video">
                                                <input type="hidden" name="ID" value="{{ $handbook->id }}">
                                                <button type="submit" class="teks-merah pointer btn bg-putih">
                                                    <i class="fas fa-trash fs-20"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
    
                    </table>
                </div>
            </div>
            <div class="documents tabcontent-event-handbook table-block" style="display: none; border: none;">
                <h3>
                    <input type="search" placeholder="Search..." class="float-right form-control search-input" style="width: unset"
                        data-table="file-list" />
                </h3>
                <div id="dummy-table2" class="table-responsive" style="height: 10px">
                    <table class="table table-borderless image-list">
                        <thead>
                            <tr>
                                <th style="min-width: 200px"></th>
                                <th style="min-width: 200px"></th>
                                <th style="min-width: 100px"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div id="real-table2" class="table-responsive">
                    <table class="table table-borderless file-list">
                        <thead>
                            <tr>
                                <th style="min-width: 200px">File Name</th>
                                <th style="min-width: 200px">Uploaded</th>
                                <th style="min-width: 100px">Action</th>
                            </tr>
                        </thead>
    
                        <tbody class="mt-2">
                            @foreach ($handbooks as $handbook)
                                @if ($handbook->type_file == 'documents')
                                    <tr>
                                        <td class="fontBold font-weight-bold">
                                            <a href="">
                                                {{ $handbook->file_name }}
                                            </a>
                                        </td>
                                        <td>Uploaded {{ Carbon::parse($handbook->created_at)->format('d M,') }}</td>
                                        
                                        <td>
                                            <form
                                                action="{{ route('organization.event.handbooks.delete', [$organizationID, $event->id]) }}"
                                                method="POST">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="typeFile" value="documents">
                                                <input type="hidden" name="ID" value="{{ $handbook->id }}">
                                                <button type="submit" class="teks-merah pointer btn bg-putih">
                                                    <i class="fas fa-trash fs-20"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
    
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal PopUp -->
    <div class="bg"></div>
    <div class="popupWrapper" id="addSession">
        <div class="lebar-50 popup rounded-5">
            <h3><i class="fas bi bi-x-circle-fill op-5 mt-3 pr-3 ke-kanan pointer" onclick="hilangPopup('#addSession')"></i></h3>
            <div class="pl-5 pr-5">
                <h3 class="mt-5 rata-tengah">Upload File</h3>
                <p class="teks-transparan rata-tengah mt-0 mb-0 no-pd-t">Unggah file foto, video atau dokumen untuk eventmu
                </p>
                <div class="wrap mt-0 ml-0 mr-0">

                    <div class="tab scrollmenu rata-tengah mb-3" style="border: none">
                        <button class="tab-btn tablinks-upload-handbook active"
                            onclick="opentabs(event, 'upload-handbook', 'photo')">Photo</button>
                        <button class="tab-btn tablinks-upload-handbook"
                            onclick="opentabs(event, 'upload-handbook', 'video')">Video</button>
                        <button class="tab-btn tablinks-upload-handbook"
                            onclick="opentabs(event, 'upload-handbook', 'documents')">Documents</button>
                    </div>

                    <div id="photo" class="tabcontent-upload-handbook" style="display: block;">
                        <form action="{{ route('organization.event.handbooks.store', [$organizationID, $event->id]) }}"
                            method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <input type="file" name="photo" class="d-none" id="photo-up" accept="image/jpg, image/png, image/jpeg"
                                onchange="uploadPhoto(this, {{ json_encode($organization->user->package->max_attachment) }})">
                            <input type="hidden" name="uploadType" value="photo">

                            <label id="inputPhotoArea" for="photo-up" class="lebar-100">
                                <div class="lebar-100 rounded-5 h-10 uploadArea uploadArea-hb pl-2 pr-2">
                                    <i class="fa fa-image icon-up-area"></i><br>
                                    <span class="teks-up-area1">
                                        Upload Photo PNG, JPG max size {{ $organization->user->package->max_attachment }} Mb
                                    </span><br>
                                    <br>
                                    <span class="bg-primer btn btn-no-pd">
                                        Browse File
                                    </span>
                                </div>
                            </label>
                            <div id="previewPhotoArea" class="d-none bagi lebar-100">
                                <img id="photoPreview" class="rounded-5" width="100%">
                                <br />
                                <span class="btn btn-no-pd lebar-100 mt-3 mb-3 pointer bg-merah"
                                    onclick="removePhotoPreview()">Hapus</span>

                            </div>
                            <div class="lebar-100 rata-tengah">
                                <button type="submit" class="btn bg-primer lebar-40">Simpan</button>
                            </div>
                        </form>
                    </div>

                    <div id="video" class="tabcontent-upload-handbook" style="display: none;">
                        <form action="{{ route('organization.event.handbooks.store', [$organizationID, $event->id]) }}"
                            method="POST">
                            {{ csrf_field() }}

                            <input type="hidden" name="uploadType" value="video">

                            <div class="lebar-100 rounded-5 h-10 uploadArea uploadArea-hb uploadArea-video pl-2 pr-2">
                                <input type="url" name="video_0" oninput="uploadVideo(this)"
                                    class="mb-3 box bg-putih rounded-5" placeholder="URL Youtube Only">
                                <input id="video_link_insert" type="hidden" name="video">
                                <iframe id="videoPreview" height="60%" width="90%" class="no-border rounded ml-3"></iframe>
                            </div>
                            <div class="lebar-100 rata-tengah">
                                <button type="submit" class="btn bg-primer mt-2 lebar-40">Simpan</button>
                            </div>
                        </form>
                    </div>

                    <div id="documents" class="tabcontent-upload-handbook" style="display: none;">
                        <form action="{{ route('organization.event.handbooks.store', [$organizationID, $event->id]) }}"
                            method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <input type="file" accept="application/pdf" name="document" class="d-none" id="doc-up"
                                onchange="uploadDoc(this, {{ json_encode($organization->user->package->max_attachment) }})">
                            <input type="hidden" name="uploadType" value="documents">

                            <label id="label" for="doc-up" class="lebar-100">
                                <div class="lebar-100 rounded-5 h-10 uploadArea uploadArea-hb pl-2 pr-2">
                                    <i class="fa fa-file icon-up-area"></i><br>
                                    <span id="file-name-ori" class="teks-up-area1">
                                        Upload File PDF Only max size {{ $organization->user->package->max_attachment }} Mb
                                    </span>
                                    <span id="file-name" class="teks-up-area1 d-none">

                                    </span>
                                    <br>
                                    <span id="btnDelDoc" class="btn btn-no-pd lebar-40 mt-3 mb-3 pointer bg-merah d-none"
                                        onclick="removeDocPreview()">Hapus</span>
                                    <br>
                                    <span id="browse" class="bg-primer btn btn-no-pd">
                                        Browse File
                                    </span>
                                </div>
                            </label>
                            <div class="lebar-100 rata-tengah">
                                <button type="submit" class="btn bg-primer lebar-40">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- End Modal PopUp -->

    <script type="text/javascript">
        const uploadVideo = (input, isEdit = null) => {
            var urlInput = input.value.split('watch?v=');

            var idVideo = "";

            if (urlInput.length == 1) {
                //Berrati memakai youtu.be atau tidak sesuai sama sekali
                urlInput = urlInput[0].split('youtu.be/');
                if (urlInput.length > 1) {
                    idVideo = urlInput[1];
                } else {
                    urlInput = urlInput[0].split('/embed/');
                    // console.log(urlInput);
                    if (urlInput.length > 1) {
                        idVideo = urlInput[1];
                    }
                }
            } else {
                idVideo = urlInput[1];
            }

            if (idVideo == "") {
                document.getElementById('video_link_insert').value = idVideo;
            } else {
                document.getElementById('video_link_insert').value = 'https://www.youtube.com/embed/' + idVideo;
            }
            console.log(idVideo);
            // let file = input.files[0];
            // console.log(file);
            // let reader = URL.createObjectURL(file);

            if (isEdit == null) {
                document.querySelector("#videoPreview").src = 'https://www.youtube.com/embed/' + idVideo;
            } else {
                document.querySelector("#videoPreview").src = 'https://www.youtube.com/embed/' + idVideo;
            }
        }

        const uploadPhoto = (input, maxSize, isEdit = null) => {
            let file = input.files[0];
            maxSize = maxSize * 1024;
            let fileSize = file.size / 1024;
            if(fileSize > maxSize){
                Swal.fire({
                    title: 'Error!',
                    text: 'Maksimal file upload '+(maxSize/1024)+' Mb',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function () {
                    removePhotoPreview();
                });
            }else{
                let reader = new FileReader();
                reader.readAsDataURL(file);

                reader.addEventListener("load", function() {
                    if (isEdit == null) {
                        let preview = select("#photoPreview");
                        select("#inputPhotoArea").classList.add('d-none');
                        select("#previewPhotoArea").classList.remove('d-none');
                        preview.setAttribute('src', reader.result);
                    }
                });
            }
        }

        const removePhotoPreview = (isEdit = null) => {
            if (isEdit == null) {
                select("input[type='file']").value = "";
                select("#inputPhotoArea").classList.remove('d-none');
                select("#previewPhotoArea").classList.add('d-none');
            }
        }

        const uploadDoc = (input, maxSize) => {
            let file = input.files[0];
            maxSize = maxSize * 1024;
            let fileSize = file.size / 1024;
            if(fileSize > maxSize){
                Swal.fire({
                    title: 'Error!',
                    text: 'Maksimal file upload '+(maxSize/1024)+' Mb',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function () {
                    removeDocPreview();
                });
            }else{
                console.log(file.name);

                document.getElementById('file-name-ori').classList.add('d-none');
                var setTag = document.getElementById('file-name');
                setTag.classList.remove('d-none');
                setTag.innerHTML = file.name;
                document.getElementById('btnDelDoc').classList.remove('d-none');
                document.getElementById('browse').classList.add('d-none');
                document.getElementById('label').htmlFor = '';
            }
        }

        const removeDocPreview = () => {

            document.getElementById('file-name-ori').classList.remove('d-none');
            var setTag = document.getElementById('file-name');
            setTag.classList.add('d-none');
            document.getElementById('btnDelDoc').classList.add('d-none');
            document.getElementById('browse').classList.remove('d-none');
            document.getElementById('label').htmlFor = 'doc-up';

        }
    </script>
    <script src="{{ asset('js/tableTopScroll.js') }}"></script>
    <script src="{{ asset('js/user/searchTable.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            scrollTable('dummy-table', 'real-table');
            scrollTable('dummy-table1', 'real-table1');
            scrollTable('dummy-table2', 'real-table2');
        });
    </script>
@endsection

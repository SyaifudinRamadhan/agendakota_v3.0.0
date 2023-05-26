@extends('layouts.user')

@section('title', 'My Exhibitions')

@section('head.dependencies')
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/exhibitorPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/handbookPage.css') }}">

@endsection

@section('content')

    @php
        use Carbon\Carbon;
        $tanggalSekarang = Carbon::now()->toDateString();
        
    @endphp

    <div class="">
        <div class="bagi mb-3">
            <h2>Your Event Exhibition</h2>
            <span class="text-secondary">Temukan semua event pameranmu</span>
        </div>
    </div>

    <div class="tab scrollmenu mb-2" style="border: none; border-bottom: 1px solid #F0F1F2;">
        <button id="setup" class="tab-btn tablinks-exh active"
            onclick="openTabs(event, 'exh', 'exhibitionstp')">Exhibition
            Setup</button>
        <button id="handbook" class="tab-btn tablinks-exh" onclick="openTabs(event, 'exh', 'handbooks')">Handbooks</button>
        <button id="scrapper" class="tab-btn tablinks-exh" onclick="openTabs(event, 'exh', 'boothprd')">Booth
            Products</button>
        <button id="sessions" class="tab-btn tablinks-exh" onclick="openTabs(event, 'exh', 'sessions')">Sessions</button>
    </div>

    @include('admin.partials.alert')

    <div class="exhibitionstp tabcontent-exh" style="display: block; border: none;">
        <div class="container-cadangan mt-4">
            <form action="{{ route('myExhibitionUpdate', [$organizationID, $eventID, $exhibitor->id]) }}" method="POST"
                enctype="multipart/form-data">
                <div class="row">
                    @csrf
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-lg-4">

                                {{-- logo --}}
                                <div class="wrap">
                                    <input type="hidden" name="execution_type" value="{{ $event->execution_type }}">
                                    <div id="inputLogoArea" class="bagi lebar-100 mt-1 d-none">
                                        <input type="file" accept="image/jpg, image/png, image/jpeg" id="input-logo"
                                            class="box no-bg" name="logo"
                                            onchange="uploadLogo(this,{{ json_encode($event->organizer->user->package->max_attachment) }})"
                                            oninvalid="this.setCustomValidity('Harap Upload Gambar')"
                                            oninput="setCustomValidity('')">
                                        <label for="input-logo" class="lebar-100">
                                            <div class="uploadArea font-inter-header rounded-5" style="color:#E6286E;">
                                                <div class="img-cover-up">
                                                    <img class="img-cover" src="{{ asset('images/photo.png') }}">
                                                </div>
                                                Upload Company Logo
                                                <br>
                                                <span
                                                    style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 12px; color: #E6286E;">
                                                    200x200px PNG, JPG
                                                    {{ $event->organizer->user->package->max_attachment }} Mb
                                                </span>
                                            </div>
                                        </label>
                                    </div>
                                    <div id="previewLogoArea" class="bagi lebar-100">
                                        <img id="logoPreview"
                                            src="{{ asset('storage/event_assets/' . $slug->slug . '/exhibitors/exhibitor_logo/' . $exhibitor->logo) }}"
                                            class="rounded-5 asp-rt-1-1" width="100%">
                                        <br />
                                        <span class="btn btn-no-pd lebar-100 mt-3 pointer bg-merah"
                                            onclick="removeLogoPreview(1)">Hapus</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-8">
                                <div class="mt-2" style="padding-top: 3%; color: #304156;">Company Name :</div>
                                <input type="text" class="box no-bg" name="name" value="{{ $exhibitor->name }}"
                                    placeholder="Your Company Name">

                                <div class="mt-2" style="padding-top: 3%; color: #304156;">Email Address :</div>
                                <input type="text" readonly class="box no-bg disabled" name="email"
                                    value="{{ $exhibitor->email }}" placeholder="youremail@company.com">
                            </div>
                        </div>

                        <div class="mt-2 font-inter-header" style="padding-top: 3%; color: #304156;">Kategori Booth:</div>
                        <select name="category" class="box no-bg">
                            @if ($exhibitor->category)
                                <option value="{{ $exhibitor->category }}">{{ $exhibitor->category }}</option>
                            @else
                                <option value="{{ old('category') }}">
                                    {{ old('category') == '' ? '-- PILIH KATEGORI --' : old('category') }}</option>
                            @endif
                            @foreach ($categories as $category)
                                <option>{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <div class="mt-2 font-inter-header" style="padding-top: 3%; color: #304156;">Address :</div>
                        <input type="text" class="box no-bg" name="address" value="{{ $exhibitor->address }}"
                            placeholder="Alamat Perusahaan Anda">

                        <div class="mt-2 font-inter-header" style="padding-top: 3%; color: #304156;">Telp :</div>
                        <input type="text" maxlength="13" class="box no-bg" name="phone"
                            value="{{ $exhibitor->phone }}" placeholder="No. Telp Perusahaan Anda">

                        <h2 class="font-inter-header" style="padding-top: 5%; color: #304156;">Media sosial</h2>

                        <div class="form-group font-inter-header" style="padding-top: 3%;">
                            <label for="instagram">Instagram :</label>
                            <div class="input-group mt-1">
                                <span class="input-group-text" id="instagram"><i class="fa fa-instagram"
                                        aria-hidden="true"></i></span>
                                <input type="text" name="instagram" id="instagram" class="form-control"
                                    placeholder="https://www.instagram.com/yourprofile"
                                    aria-label="https://www.instagram.com/yourprofile"
                                    aria-describedby="https://www.instagram.com/yourprofile"
                                    value="{{ $exhibitor->instagram }}">
                            </div>
                        </div>

                        <div class="form-group font-inter-header">
                            <label for="linkedin" style="color: #304156;">Linkedin :</label>
                            <div class="input-group mt-1">
                                <span class="input-group-text" id="linkedin"><i class="fa fa-linkedin"
                                        aria-hidden="true"></i></span>
                                <input type="text" name="linkedin" id="linkedin" class="form-control"
                                    placeholder="https://www.Linkedin.com/yourprofile"
                                    aria-label="https://www.Linkedin.com/yourprofile"
                                    aria-describedby="https://www.Linkedin.com/yourprofile"
                                    aria-label="https://www.Linkedin.com/yourprofil" value="{{ $exhibitor->linkedin }}">
                            </div>
                        </div>

                        <div class="form-group font-inter-header">
                            <label for="twitter" style="color: #304156;">Twitter :</label>
                            <div class="input-group mt-1">
                                <span class="input-group-text" id="twitter"><i class="fa fa-twitter"
                                        aria-hidden="true"></i></span>
                                <input type="text" name="twitter" id="twitter" class="form-control"
                                    placeholder="https://www.twitter.com/yourprofile"
                                    aria-label="https://www.twitter.com/yourprofile"
                                    aria-describedby="https://www.twitter.com/yourprofile"
                                    aria-label="https://www.twitter.com/yourprofil" value="{{ $exhibitor->twitter }}">
                            </div>
                        </div>

                        <div class="form-group font-inter-header">
                            <label for="website" style="color: #304156;">Website :</label>
                            <div class="input-group mt-1">
                                <span class="input-group-text" id="website"><i class="fa fa-paper-plane"
                                        aria-hidden="true"></i></span>
                                <input type="text" name="website" id="website" class="form-control"
                                    placeholder="https://yourwebsite.com" aria-label="https://yourwebsite.com"
                                    aria-describedby="https://yourwebsite.com" aria-label="https://yourwebsite.com"
                                    value="{{ $exhibitor->website }}">
                            </div>
                        </div>


                        <div class="font-inter virtual-booth" style="color: #304156; margin-top:25%;">
                            <h2 class="font-inter-header" style="color: #304156;">Virtual Booth</h2>
                            <?php
                            
                            $v_booth = $exhibitor->virtual_booth;
                            
                            ?>
                            <label class="label-checkbox font-inter" style="margin-top: 20px;">Enable Virtual Booth
                                <input type="checkbox" name="virtual_booth" id="enableVirtual"
                                    style=" width: 23px;height:23px;margin-top:3%;" value="1"
                                    {{ $v_booth == 1 ? 'checked' : '' }}>

                                <span class="checkmark font-inter"></span>
                            </label>
                        </div>

                        <div class="row" style="margin-top:3%;">
                            <div class="col-12 font-inter-header">
                                <div class="row">
                                    <div class="col-9 mt-2" style=" color: #304156;">Booth Link (Zoom Link) :</div>
                                    <div class="col-3"></div>
                                </div>
                                <div class="row">
                                    <div class="col-9">
                                        <input type="text" id="copy_inputan" class="box no-bg" name="booth_link"
                                            value="{{ $exhibitor->booth_link }}"
                                            placeholder="Your Booth Link. Zoom only">
                                    </div>
                                    <div class="col-3">
                                        <button type="button" id="copy" onclick="copy_text()"
                                            class="box no-bg bg-primer lebar-100">Copy</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-3 font-inter-header">

                            </div>
                        </div>

                        <div class="mt-2 font-inter-header" style="padding-top: 3%; color: #304156;">Deskripsi :</div>
                        <textarea rows="10" class="box no-bg" name="description" placeholder="Deskripsi singkat tentang perusahaan">{{ $exhibitor->description }}</textarea>

                        <div class="mt-2" style="padding-top: 3%; color: #304156;"><b>Background Image :</b>
                        </div>
                        <div class="mt-2" style="color: #979797;">Audience will see this image while enter exhibition
                            page</div>

                        {{-- background image --}}
                        <div id="inputBoothArea" class="bagi lebar-100 mt-1 d-none">
                            <input type="file" accept="image/jpg, image/png, image/jpeg" id="input-bg-booth"
                                class="box no-bg" name="booth_image"
                                onchange="uploadFotoBooth(this,{{ json_encode($event->organizer->user->package->max_attachment) }})"
                                oninvalid="this.setCustomValidity('Harap Upload Gambar')" oninput="setCustomValidity('')">
                            <label for="input-bg-booth" class="lebar-100">
                                <div class="uploadAreaBg rounded-5 asp-rt-8-3" style="color:#E6286E;">
                                    <div>
                                        <img src="{{ asset('images/photo.png') }}">
                                    </div>
                                    Upload Background Image
                                    <br>
                                    <span
                                        style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 12px; color: #E6286E;">
                                        1920x720 PNG, JPG {{ $event->organizer->user->package->max_attachment }} Mb
                                    </span>
                                </div>
                            </label>
                        </div>
                        <div id="previewBoothArea" class="bagi lebar-100">
                            <img id="boothPreview"
                                src="{{ asset('storage/event_assets/' . $slug->slug . '/exhibitors/exhibitor_booth_image/' . $exhibitor->booth_image) }}"
                                class="rounded-5 asp-rt-8-3" width="100%"><br />
                            <span class="btn btn-no-pd lebar-100 mt-3 pointer bg-merah"
                                onclick="removeBoothPreview(1)">hapus</span>
                        </div>

                        <div class="mt-2" style="padding-top: 3%; color: #304156;"><b>Welcome Video :</b>
                        </div>
                        <div class="mt-2" style="color: #979797;">Audience will see this video while enter exhibition
                            page</div>

                        <br>
                        <!-- <input type="text"  class="box no-bg" name="video" value="{{ $exhibitor->video }}" placeholder="Masukkan Link Video"> -->

                        <div id="inputVideoArea" class="bagi lebar-100 mt-1 d-none">
                            <input type="url" id="input-file-video" class="box no-bg no-bg" name="video_0"
                                oninput="uploadVideo(this)" oninvalid="this.setCustomValidity('Harap Upload Video')"
                                oninput="setCustomValidity('')"
                                placeholder="URL video YouTube ex(https://www.youtube.com/watch?v=8RpCLKoa3_A atau https://www.youtube.com/embed/8RpCLKoa3_A)">

                            <input type="hidden" id="video_link_insert_1" name="video_1"
                                value="{{ $exhibitor->video }}">

                            <input type="hidden" id="video_link_insert" name="video"
                                value="{{ $exhibitor->video }}">
                        </div>
                        <div id="previewVideoArea" class="bagi lebar-100 mt-4">
                            <iframe id="videoPreview" class="rounded-5 asp-rt-8-3" src="{{ $exhibitor->video }}"
                                width="100%" controls></iframe>
                            <br />
                            <span class="btn btn-no-pd lebar-100 mt-3 pointer bg-merah"
                                onclick="removeVideoPreview(1)">hapus</span>
                        </div>


                        <br>
                        <div class="">
                            <?php
                            $ovr_view = $exhibitor->overview;
                            ?>
                            <label class="label-checkbox font-inter"
                                style="margin-top: 20px;font-weight: normal !important;"> Tampil Di Overview Event Detail
                                <input class="form-check-input" type="checkbox" id="overview" name="overview"
                                    value="1" {{ $ovr_view == 1 ? 'checked' : '' }}>

                                <span class="checkmark font-inter"></span>
                            </label>
                        </div>

                        <button id="btn-save-1" type="submit" class="bg-primer mt-3"
                            style="border-radius: 6px;">Simpan</button>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-3 font-inter-header">
                        <button id="btn-save-2" type="submit"
                            class="bg-primer mt-3 lebar-100 btn btn-no-pd btn-add">Simpan</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <div class="handbooks tabcontent-exh mt-5" style="display: none; border: none;">
        <div class="">
            <div class="row">
                <div class="col-md-6 pl-0 text-left">
                    <button type="button" class="bg-primer mt-0 btn-add btn-no-pd float-left mr-2 mb-4"
                        onclick="munculPopup('#addHandbook')">
                        <i class="fa bi bi-cloud-arrow-up fs-20"></i> Upload File
                    </button>
                    <div class="square toggle-view-button float-left" mode="table" onclick="toggleView(this)">
                        <i class="fa fa-bars"></i>
                    </div>
                    <div class="square active toggle-view-button float-left" mode="card" onclick="toggleView(this)">
                        <i class="fa fa-th-large"></i>
                    </div>

                </div>
                <div class="col-md-6 scrollmenu mb-2 tab float-right text-right" style="border: none">
                    <button class="tab-btn tablinks-event-handbook active"
                        onclick="openTabs(event, 'event-handbook', 'photo')">Photo</button>
                    <button class="tab-btn tablinks-event-handbook"
                        onclick="openTabs(event, 'event-handbook', 'video')">Video</button>
                    <button class="tab-btn tablinks-event-handbook"
                        onclick="openTabs(event, 'event-handbook', 'documents')">Documents</button>
                </div>

            </div>
        </div>

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

                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th></th>
                                <th>File Name</th>
                                <th>Uploaded</th>
                                <th>Action</th>
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
                                                action="{{ route('myHandbookDelete', [$organizationID, $eventID, $exhibitor->id]) }}"
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
                <div class="video tabcontent-event-handbook table-block" style="display: none; border: none;">

                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th></th>
                                <th>URL YouTube (embed url)</th>
                                <th>Uploaded</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody class="mt-2">
                            @foreach ($handbooks as $handbook)
                                @if ($handbook->type_file == 'video')
                                    <tr>
                                        <td><iframe class="rounded-5" src="{{ $handbook->file_name }}"></iframe>
                                        </td>
                                        <td class="fontBold font-weight-bold">{{ $handbook->file_name }}</td>
                                        <td>Uploaded {{ Carbon::parse($handbook->created_at)->format('d M,') }}</td>

                                        <td>
                                            <form
                                                action="{{ route('myHandbookDelete', [$organizationID, $eventID, $exhibitor->id]) }}"
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
                <div class="documents tabcontent-event-handbook table-block" style="display: none; border: none;">

                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Uploaded</th>
                                <th>Action</th>
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
                                                action="{{ route('myHandbookDelete', [$organizationID, $eventID, $exhibitor->id]) }}"
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
            @endif
        </div>

        <!-- Modal PopUp -->
        <div class="bg"></div>
        <div class="popupWrapper" id="addHandbook">
            <div class="lebar-50 popup rounded-5 pl-5 pr-5">
                <h3 class="mt-5 rata-tengah">Upload File
                    <i class="fas bi bi-x-circle-fill op-5 ke-kanan pointer" onclick="hilangPopup('#addHandbook')"></i>
                </h3>
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
                        <form action="{{ route('myHandbookStore', [$organizationID, $eventID, $exhibitor->id]) }}"
                            method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <input type="file" accept="image/jpg, image/png, image/jpeg" name="photo"
                                class="d-none" id="photo-up" accept="image/*"
                                onchange="uploadPhoto(this,{{ json_encode($event->organizer->user->package->max_attachment) }})">
                            <input type="hidden" name="uploadType" value="photo">

                            <label id="inputPhotoArea" for="photo-up" class="lebar-100">
                                <div class="lebar-100 rounded-5 h-10 uploadArea uploadArea-hb pl-2 pr-2"
                                    style="padding: 50px 0px !important;">
                                    <i class="fa fa-image icon-up-area"></i><br>
                                    <span class="teks-up-area1">
                                        Upload Photo PNG, JPG max size
                                        {{ $event->organizer->user->package->max_attachment }} Mb
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
                        <form action="{{ route('myHandbookStore', [$organizationID, $eventID, $exhibitor->id]) }}"
                            method="POST">
                            {{ csrf_field() }}

                            <input type="hidden" name="uploadType" value="video">

                            <div class="lebar-100 rounded-5 h-10 uploadArea uploadArea-hb uploadArea-video pl-2 pr-2">
                                <input type="url" name="video_0" oninput="uploadVideoBook(this)"
                                    class="mb-3 box bg-putih rounded-5" placeholder="URL Youtube Only">
                                <input id="video_link_insert_book" type="hidden" name="video">
                                <iframe id="videoPreviewBook" height="60%" class="no-border rounded ml-3"></iframe>
                            </div>
                            <div class="lebar-100 rata-tengah">
                                <button type="submit" class="btn bg-primer mt-2 lebar-40">Simpan</button>
                            </div>
                        </form>
                    </div>

                    <div id="documents" class="tabcontent-upload-handbook" style="display: none;">
                        <form action="{{ route('myHandbookStore', [$organizationID, $eventID, $exhibitor->id]) }}"
                            method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <input type="file" accept="application/pdf" name="document" class="d-none"
                                id="doc-up"
                                onchange="uploadDoc(this, {{ json_encode($event->organizer->user->package->max_attachment) }})">
                            <input type="hidden" name="uploadType" value="documents">

                            <label id="label" for="doc-up" class="lebar-100">
                                <div class="lebar-100 rounded-5 h-10 uploadArea uploadArea-hb pl-2 pr-2"
                                    style="padding: 50px 0px !important;">
                                    <i class="fa fa-file icon-up-area"></i><br>
                                    <span id="file-name-ori" class="teks-up-area1">
                                        Upload File PDF Only max {{ $event->organizer->user->package->max_attachment }} Mb
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


        <!-- End Modal PopUp -->

        <script type="text/javascript">
            const uploadVideoBook = (input, isEdit = null) => {
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
                    console.log(idVideo+' kosong');
                    document.getElementById('video_link_insert_book').value = idVideo;
                } else {
                    document.getElementById('video_link_insert_book').value = 'https://www.youtube.com/embed/' + idVideo;
                    console.log(idVideo);
                }
                // let file = input.files[0];
                // console.log(file);
                // let reader = URL.createObjectURL(file);

                if (isEdit == null) {
                    document.querySelector("#videoPreviewBook").src = 'https://www.youtube.com/embed/' + idVideo;
                } else {
                    document.querySelector("#videoPreviewBook").src = 'https://www.youtube.com/embed/' + idVideo;
                }
            }

            const uploadPhoto = (input, maxSize, isEdit = null) => {
                let file = input.files[0];
                maxSize = maxSize * 1024;
                let fileSize = file.size / 1024;
                if (fileSize > maxSize) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Maksimal file upload ' + (maxSize / 1024) + ' Mb',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    }).then(function() {
                        removePhotoPreview();
                    });
                } else {
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
                if (fileSize > maxSize) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Maksimal file upload ' + (maxSize / 1024) + ' Mb',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    }).then(function() {
                        removeDocPreview();
                    });
                } else {
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
    </div>
    <div class="boothprd tabcontent-exh" style="display: none; border: none;">
        <div class="row">
            <div class="col-md pl-0 text-right">
                <button type="button" class="bg-primer mt-0 btn-add btn-no-pd float-right mr-2 mb-4"
                    onclick="munculPopup('#addProduct')">
                    <i class="fa bi bi-cloud-arrow-up fs-20"></i> Upload Produk
                </button>
            </div>
        </div>
        <div class="d-block">
            @if (count($boothProducts) == 0)
                <div class="mt-4 rata-tengah">
                    <i class="bi bi-bag font-img teks-primer"></i>
                    <h3>Exhibitor Produk Masih Kosong</h3>
                    <p>Adakan berbagai event menarik di AgendaKota</p>
                </div>
            @else
                <div class="row">
                    @foreach ($boothProducts as $prd)
                        <div class="col-lg-3 mb-4">

                            <div class="bg-putih rounded-5 bayangan-5">
                                <a href="{{ $prd->url }}">
                                    <div class="tinggi-150 rata-tengah">
                                        <img src="{{ asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_products/'.$prd->image) }}" height="100%" alt="">
                                    </div>
                                </a>
                                <div class="">
                                    <div class="wrap pb-1">
                                        <div class="row">
                                            <div class="col-9">
                                                <a href="{{ $prd->url }}">
                                                    <h6 class="detail font-inter-header text-dark"
                                                        style="text-decoration: none">Lihat Detail
        
                                                    </h6>
                                                </a>
                                            </div>
                                            <div class="col-3">
                                                <a href="{{ route('productDelete', [$eventID, $prd->id]) }}">
                                                    <i class="fas fa-trash pointer text-danger fs-18"
                                                        style=" color: #C4C4C4; font-size:20pt; float: right; z-index: 2"
                                                        aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div id="addProduct" style="display: none">
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
                    <form class="text-left mt-4" enctype="multipart/form-data" action="{{ route('productSave2', [$eventID]) }}" method="POST">
                        @csrf
                        <div id="inputPrdImg" class="bagi lebar-100 mt-1">
                            <input type="file" accept="image/jpg, image/png, image/jpeg" id="input-img-product"
                                class="box no-bg" name="prd_img"
                                onchange="uploadFotoProduct(this,{{ json_encode($event->organizer->user->package->max_attachment) }})"
                                oninvalid="this.setCustomValidity('Harap Upload Gambar')" oninput="setCustomValidity('')">
                            <label for="input-img-product" class="lebar-100">
                                <div class="uploadAreaBg rounded-5 asp-rt-4-3" style="color:#E6286E;">
                                    <div>
                                        <img src="{{ asset('images/photo.png') }}">
                                    </div>
                                    Upload Background Image
                                    <br>
                                    <span
                                        style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 12px; color: #E6286E;">
                                        4:3 PNG, JPG {{ $event->organizer->user->package->max_attachment }} Mb
                                    </span>
                                </div>
                            </label>
                        </div>
                        <div id="previewPrdArea" class="bagi lebar-100 d-none">
                            <img id="prdPreview"
                                src=""
                                class="rounded-5 asp-rt-4-3" width="100%"><br />
                            <span class="btn btn-no-pd lebar-100 mt-3 pointer bg-merah"
                                onclick="removeFotoProductPreview(1)">hapus</span>
                        </div>
                        Inputkan link detail produknya satu persatu. Ex : <a
                            href="https://shopee.co.id/bonkyo-Wireless-Optical-Mouse-Dan-Minimalism-MSE6-i.159093915.3717413132?sp_atk=3cf3fd21-966c-48ec-9bf4-14905e1b54a4">Klik
                            Untuk lihat</a>
                        <input type="url" name="url" class="mb-3 box bg-putih rounded-5"
                            placeholder="URL shopee (Bukan produk paket diskon) / tokopedia / bukalapak">
                        <div class="lebar-100 rata-tengah mb-5">
                            <button type="submit" class="btn bg-primer lebar-40">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- End Modal PopUp -->
    </div>
    <div class="sessions tabcontent-exh" style="display: none; border: none;">
        <div class="d-block">
            @if (count($event->sessions) == 0)
                <div class="mt-4 rata-tengah">
                    <i class="bi bi-camera font-img teks-primer"></i>
                    <h3>Session Masih Kosong</h3>
                    <p>Adakan berbagai event menarik di AgendaKota</p>
                </div>
            @else
                <div class="row">
                    @foreach ($event->sessions as $session)
                        @if ($session->deleted == 0)
                            <div class="col-lg-4 mb-4">
                                <div class="bg-putih bayangan-5 lebar-100 box-card">
                                    <div class="teks-tebal" style="padding:5%;">
                                        <div class="row">
                                            <div class="col-9 text-overflow">
                                                {{ $session->title }}
                                            </div>
                                            {{-- <div class="col-3">
                                        <i class="fa fa-ellipsis-h pointer dropdownToggle" data-id="{{ $session->id }}" style=" color: #C4C4C4; font-size:20pt; float: right;" aria-hidden="true"></i>
        
                                    </div> --}}
                                        </div>
                                        {{-- 
                                        <div id="Dropdown{{ $session->id }}" class="dropdown-content">
                                            <a>
                                                <span class="teks-hijau pointer" onclick="edit('{{ $session }}')">
                                                    <i class="fas fa-edit"></i> Edit
                                                </span>
                                            </a>
                                            <a href="{{ route('organization.event.session.delete', [$organizationID, $event->id, $session->id]) }}"
                                                onclick="confirm('Apakah anda yakin ingin menghapus session ini?')">
                                                <span class="teks-merah pointer">
                                                    <i class="fas fa-trash"></i> Delete
                                                </span>
                                            </a>
                                            @if ($event->execution_type == 'online' || $event->execution_type == 'hybrid')
                                                <a target="_blank"
                                                    href="{{ route('organization.event.session.url', [$organizationID, $event->id, $session->id]) }}">Masuk
                                                    Zoom</a>
                                            @endif
                                        </div> --}}
                                    </div>
                                    <div class="card-detail">
                                        <div class="text-space-card text-overflow">
                                            <i class="fa fa-calendar card-icon"
                                                aria-hidden="true"></i><span>{{ Carbon::parse($session->start_date)->format('d M,') }}
                                                {{ Carbon::parse($session->start_time)->format('H:i') }} WIB -
                                                {{ Carbon::parse($session->end_date)->format('d M,') }}
                                                {{ Carbon::parse($session->end_time)->format('H:i') }} WIB</span>
                                        </div>

                                        @if ($event->execution_type == 'online' || $event->execution_type == 'hybrid')
                                            <div class="text-overflow">
                                                <i class="fa bi bi-camera-video-fill card-icon"
                                                    aria-hidden="true"></i><span>
                                                    <a href="{{ route('streamSpecial', [$session->id, '0']) }}">Going to
                                                        Stream</a>
                                                </span>
                                            </div>
                                        @endif
                                        <br><br>
                                        <h4 class="card-desc">
                                            {{ $session->description }}</h4>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection


@section('javascript')
    <script>
        document.addEventListener(
            "DOMContentLoaded",
            function() {
                var fromPage = '<?php echo session('from'); ?>';
                if (fromPage != "") {
                    document.getElementById(fromPage).click();
                }
            });
        const uploadLogo = (input, maxSize, isEdit = null) => {
            let file = input.files[0];
            maxSize = maxSize * 1024;
            let fileSize = file.size / 1024;
            if (fileSize > maxSize) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Maksimal file upload ' + (maxSize / 1024) + ' Mb',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    removeLogoPreview();
                });
            } else {
                let reader = new FileReader();
                reader.readAsDataURL(file);

                reader.addEventListener("load", function() {
                    if (isEdit == null) {
                        let preview = select("#logoPreview");
                        select("#inputLogoArea").classList.add('d-none');
                        select("#previewLogoArea").classList.remove('d-none');
                        preview.setAttribute('src', reader.result);
                    } else {
                        let preview = select("#editExhibitor #logoPreview");
                        select("#editExhibitor #inputLogoArea").classList.add('d-none');
                        select("#editExhibitor #previewLogoArea").classList.remove('d-none');
                        preview.setAttribute('src', reader.result);
                    }
                });
            }
        }
        const uploadFotoBooth = (input, maxSize, isEdit = null) => {
            let file = input.files[0];
            maxSize = maxSize * 1024;
            let fileSize = file.size / 1024;
            if (fileSize > maxSize) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Maksimal file upload ' + (maxSize / 1024) + ' Mb',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    removeBoothPreview();
                });
            } else {
                let reader = new FileReader();
                reader.readAsDataURL(file);

                reader.addEventListener("load", function() {
                    if (isEdit == null) {
                        let preview = select("#boothPreview");
                        select("#inputBoothArea").classList.add('d-none');
                        select("#previewBoothArea").classList.remove('d-none');
                        preview.setAttribute('src', reader.result);
                    } else {
                        let preview = select("#editExhibitor #boothPreview");
                        select("#editExhibitor #inputBoothArea").classList.add('d-none');
                        select("#editExhibitor #previewBoothArea").classList.remove('d-none');
                        preview.setAttribute('src', reader.result);
                    }
                });
            }
        }
        const uploadFotoProduct = (input, maxSize, isEdit = null) => {
            let file = input.files[0];
            maxSize = maxSize * 1024;
            let fileSize = file.size / 1024;
            if (fileSize > maxSize) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Maksimal file upload ' + (maxSize / 1024) + ' Mb',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    removeBoothPreview();
                });
            } else {
                let reader = new FileReader();
                reader.readAsDataURL(file);

                reader.addEventListener("load", function() {
                    if (isEdit == null) {
                        let preview = select("#prdPreview");
                        select("#inputPrdImg").classList.add('d-none');
                        select("#previewPrdArea").classList.remove('d-none');
                        preview.setAttribute('src', reader.result);
                    } else {
                        let preview = select("#editExhibitor #prdPreview");
                        select("#editExhibitor #inputPrdImg").classList.add('d-none');
                        select("#editExhibitor #previewPrdArea").classList.remove('d-none');
                        preview.setAttribute('src', reader.result);
                    }
                });
            }
        }
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
                var oldUrl = document.getElementById('video_link_insert_1').value;
                document.getElementById('video_link_insert').value = oldUrl;
            } else {
                document.getElementById('video_link_insert').value = 'https://www.youtube.com/embed/' + idVideo;
            }
            console.log(idVideo);
            // let file = input.files[0];
            // console.log(file);
            // let reader = URL.createObjectURL(file);

            if (isEdit == null) {
                let preview = select("#videoPreview");
                // select("#inputVideoArea").classList.add('d-none');
                select("#previewVideoArea").classList.remove('d-none');
                // preview.setAttribute('src', reader);
                document.querySelector("iframe").src = 'https://www.youtube.com/embed/' + idVideo;
            } else {
                let preview = select("#videoPreview");
                // select("#editExhibitor #inputVideoArea").classList.add('d-none');
                select("#previewVideoArea").classList.remove('d-none');
                // preview.setAttribute('src', reader);
                document.querySelector("iframe").src = 'https://www.youtube.com/embed/' + idVideo;
            }
        }

        // logo
        const removeLogoPreview = (isEdit = null) => {
            if (isEdit == null) {
                select("#inputLogoArea input[type='file']").value = "";
                select("#inputLogoArea").classList.remove('d-none');
                select("#previewLogoArea").classList.add('d-none');
            } else {
                // select("input[type='file']").value = "";
                select("#inputLogoArea").classList.remove('d-none');
                select("#previewLogoArea").classList.add('d-none');
            }
        }

        // booth
        const removeBoothPreview = (isEdit = null) => {
            if (isEdit == null) {
                select("#inputBoothArea input[type='file']").value = "";
                select("#inputBoothArea").classList.remove('d-none');
                select("#previewBoothArea").classList.add('d-none');
            } else {
                // select("input[type='file']").value = "";
                select("#inputBoothArea").classList.remove('d-none');
                select("#previewBoothArea").classList.add('d-none');
            }
        }
        const removeFotoProductPreview = (isEdit = null) => {
            if (isEdit == null) {
                select("#inputPrdImg input[type='file']").value = "";
                select("#inputPrdImg").classList.remove('d-none');
                select("#previewPrdArea").classList.add('d-none');
            } else {
                // select("input[type='file']").value = "";
                select("#inputPrdImg").classList.remove('d-none');
                select("#previewPrdArea").classList.add('d-none');
            }
        }
        const removeVideoPreview = (isEdit = null) => {
            if (isEdit == null) {
                // select("input[type='file']").value = "";
                select("#inputVideoArea").classList.remove('d-none');
                select("#previewVideoArea").classList.add('d-none');
            } else {
                // select("input[type='file']").value = "";
                select("#inputVideoArea").classList.remove('d-none');
                select("#previewVideoArea").classList.add('d-none');
            }
        }

        function copy_text() {
            var copy = document.getElementById('copy_inputan');
            copy.select();
            console.log(copy.select());
            document.execCommand("copy");
            Swal.fire({
                title: 'Berhasil !!!',
                text: 'Link telah dicopy',
                icon: 'success',
                confirmButtonText: 'Ok'
            });
        }
    </script>


@endsection

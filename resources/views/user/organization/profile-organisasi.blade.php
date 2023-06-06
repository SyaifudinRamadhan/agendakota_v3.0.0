@extends('layouts.user')

@section('title', 'Profile')

@section('head.dependencies')
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/profileOrganizationPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/cardAccount.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/connectionPage.css') }}">


    <style>
        .container-cadangan{
            top: 110px !important;
        }
        #inputBannerArea input{
            display: none;
        }
        #inputBannerArea .uploadArea{
            padding: unset !important;
            margin-top: unset !important;
            margin-left: unset;
            height: unset;
            min-height: 140px;  
            max-height: 232px;
            /* aspect-ratio: 5/1 !important; */
            background-color: #ecf0f1 !important;
            display: flex;
        }
        #inputBannerArea .uploadArea .content-banner{
            margin: auto
        }
        #previewBannerArea img{
            aspect-ratio: 5/1 !important;
            width: 100%;
        }
    </style>


@endsection

@php
    use Carbon\Carbon;
    use App\Models\Organization;
    use App\Models\UserWithdrawalEvent;
    // dd(config('agendakota'));
    $timeZone = new DateTimeZone('Asia/Jakarta');

    $now = new DateTime();
    // $endEvent = new DateTime($startSession->end_date . ' ' . $startSession->end_time, new DateTimeZone('Asia/Jakarta'));
    // $paginateController = new App\Http\Controllers\PaginateArrayController();
@endphp
@section('content')

    @include('admin.partials.alert')


    <input type="hidden" id="auto-tab" value="{{ $openTab }}">

    <div class="tab scrollmenu" style="border: none">

        <button id="event-tab" class="tablinks-organisasi active"
            onclick="opentabs(event, 'organisasi', 'Events')">Events</button>
        @if ($role == 'member')
        @else
            <button id="profile-tab" class="tab-btn tablinks-organisasi"
                onclick="opentabs(event, 'organisasi', 'Profile')">Profile</button>
            <button id="team-tab" class="tab-btn tablinks-organisasi"
                onclick="opentabs(event, 'organisasi', 'Team')">Team</button>
            <button id="billing-tab" class="tab-btn tablinks-organisasi"
                onclick="opentabs(event, 'organisasi', 'Billing')">Billing</button>
            <a href="{{route('user.myorganization')}}">
                <button id="delete-tab" class="tab-btn tablinks-organisasi">Organizer</button>
            </a>
            {{-- <button id="delete-tab" class="tab-btn tablinks-organisasi"
                onclick="opentabs(event, 'organisasi', 'Delete')">Delete</button> --}}
        @endif
    </div>
    {{-- <div class="wrap"> --}}
    <div id="Events" class="tabcontent-organisasi" style="display: block; border: none;">
        <div class="row mt-4">
            <div class="col-lg-7" style="">
                <h2 style="color: #304156; font-size:32pX;font-family: 'Inter', sans-serif;">Events</h2>
                <h5 class="teks-transparan mb-4">Temukan semua event yang diadakan oleh organisasimu
                </h5>
                {{-- <h4 style="color: #979797; margin-top:-1%;font-family: 'RobotoLight';font-weight: bold;">Temukan semua event yang diadakan oleh organisasimu</h4> --}}
            </div>
            <div class="col-lg-5">
                <a href="{{ route('create-event') }}">
                    <button type="button" class="btn bg-primer mt-0 btn-float-r">
                        <i class="fa fa-plus" style="color: #fff;"></i> Buat Event
                    </button>
                </a>
                {{-- <button type="button" onclick="munculPopup('#createEvent')" class="btn bg-primer mt-0 btn-float-r">
                    <i class="fa fa-plus" style="color: #fff;"></i> Buat Event
                </button> --}}
                <div class="square-inner toggle-view-button table-button" mode="table" onclick="toggleView(this)">
                    <i class="fa fa-bars"></i>
                </div>
                <div class="square-inner active toggle-view-button card-button" mode="card" onclick="toggleView(this)">
                    <i class="fa fa-th-large"></i>
                </div>
            </div>
        </div>

        <div id="card-mode" class="d-block">
            @if (!isset($eventsView))
                <div class="rata-tengah mt-4">
                    <img src="{{ asset('images/calendar.png') }}">
                    <h3>Mulai membuat berbagai event menarik</h3>
                    <p>Adakan berbagai event menarik di AgendaKota</p>
                </div>
            @else
                @php
                    $i = 0;
                @endphp
                @forelse ($eventsView as $event)
                    <div class="bagi bagi-3 list-item mb-2">
                        <div class="wrap">
                            <div class="bg-putih bayangan-5 rounded-5 smallPadding">
                                <a href="{{ route('organization.event.rundowns', [$organizationID, $event->id]) }}"
                                    style="text-decoration: none" class="text-dark pointer">
                                    
                                    <div class="img-card-top"
                                        bg-image="{{ asset('storage/event_assets/' . $event->slug . '/event_logo/thumbnail/' . $event->logo) }}">
                                    </div>
                                    <div class="detail">
                                        <div class="wrap">
                                            <div class="text-right float-right text-danger">
                                                <a href="{{ route('organization.event.delete',[$organizationID, $event->id]) }}" onclick="confirmDelete(event, {{ json_encode($event) }})"><i
                                                    class="fas fa-trash fs-icon text-danger"></i>
                                                </a>
                                            </div>
                                            <div class="teks-upcoming">
                                                <p class="fs-11 mb-2">{{ $event->execution_type }}</p>
                                            </div>
                                            
                                            <h5 class="font-inter-header"
                                                style="max-height: 1.5rem; overflow:hidden; text-overflow: ellipsis;">
                                                {{ $event->name }}

                                            </h5>
                                            <li class="d-flex">
                                                <div class="icon"><i class="fas bi bi-calendar"></i></div>
                                                <div class="text desc-card">
                                                    {{ Carbon::parse($event->start_date)->isoFormat('D MMMM') }}-{{ Carbon::parse($event->end_date)->isoFormat('D MMMM') }}
                                                </div>
                                            </li>
                                            <li class="d-flex">
                                                <div class="icon">
                                                    <i class="fa bi bi-tag" aria-hidden="true">
                                                    </i>
                                                </div>
                                                <div class="font-inter desc-card"
                                                    id="target-harga-tiket-{{ $event->id }}">Tidak Ada
                                                    Tiket</div>
                                            </li>
                                            <li class="d-flex">
                                                <div class="icon"><i class="fas bi bi-person"></i></div>
                                                @php
                                                    $jumlahAttendes = 0;
                                                @endphp
                                                @foreach ($purchases as $purchase)
                                                    @if ($event->id == $purchase->event_id)
                                                        @php
                                                            $jumlahAttendes += 1;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                <div class="text desc-card">{{ $jumlahAttendes }} attendees</div>
                                            </li>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    @php
                        $i++;
                    @endphp
                @empty
                    <div class="rata-tengah mt-4">
                        <img src="{{ asset('images/calendar.png') }}">
                        <h3>Mulai membuat berbagai event menarik</h3>
                        <p>Adakan berbagai event menarik di AgendaKota</p>
                    </div>
                @endforelse
            @endif
        </div>
        <div id="table-mode" class="table-mode d-none">
            @if (!isset($eventsView))
                <div class="rata-tengah mt-4">
                    <img src="{{ asset('images/calendar.png') }}">
                    <h3>Mulai membuat berbagai event menarik</h3>
                    <p>Adakan berbagai event menarik di AgendaKota</p>
                </div>
            @else
                @if (count($eventsView) == 0)
                    <div class="rata-tengah mt-4">
                        <img src="{{ asset('images/calendar.png') }}">
                        <h3>Mulai membuat berbagai event menarik</h3>
                        <p>Adakan berbagai event menarik di AgendaKota</p>
                    </div>
                @else
                    {{-- @php
                        $eventsTable = $paginateController->paginate($events, $perPage = 1);
                    @endphp --}}
                    <div class="w-100 d-inline-block mt-3 mb-2">
                        <input type="search" placeholder="Search..." class="float-right form-control search-input"
                            style="width: unset" data-table="event-list" />
                    </div>
                    <div class="table-responsive">
                        <table id="events-table" class="table table-borderless event-list">
                            <thead>
                                <tr>
                                    <th scope="col">Event Name</th>
                                    <th scope="col">
                                        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                                    </th>
                                    <th scope="col">Date&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                    <th scope="col">Ticket Price&emsp;&emsp;&emsp;</th>
                                    <th scope="col">Attendees&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody class="mt-2">
                                @foreach ($eventsView as $event)
                                    <tr>
                                        <td><a
                                                href="{{ route('organization.event.rundowns', [$organizationID, $event->id]) }}">
                                                <img class="img-table"
                                                    src="{{ asset('storage/event_assets/' . $event->slug . '/event_logo/thumbnail/' . $event->logo) }}">
                                            </a></td>
                                        <td class="fontBold font-weight-bold">
                                            <div class="teks-upcoming">
                                                <p class="fs-11 mb-2">{{ $event->execution_type }}</p>
                                            </div>
                                            {{ $event->name }}
                                        </td>
                                        <td>
                                            {{ Carbon::parse($event->start_date)->isoFormat('D MMMM') }} -
                                            {{ Carbon::parse($event->end_date)->isoFormat('D MMMM') }}
                                        </td>
                                        <td id="target-harga-tiket2-{{ $event->id }}" class="price-table">Not Set
                                        </td>
                                        <td>
                                            @php
                                                $jumlahAttendes = 0;
                                            @endphp
                                            @foreach ($purchases as $purchase)
                                                @if ($event->id == $purchase->event_id)
                                                    @php
                                                        $jumlahAttendes += 1;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            {{ $jumlahAttendes }} attendees
                                        </td>
                                        <td>
                                            <a href="{{ route('organization.event.delete',[$organizationID, $event->id]) }}" onclick="confirmDelete(event, {{ json_encode($event) }})"><i
                                                class="fas fa-trash fs-icon text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- <div class="d-flex float-right">
                        {{ $eventsTable->links() }}
                    </div>
                    @dump($eventsTable->links()) --}}
                @endif
            @endif
        </div>
    </div>
    @if ($role != 'member')
        <div id="Profile" class="tabcontent-organisasi" style="display: none; border: none;">
            @foreach ($organizations as $organization)
                <form action="{{ route('organization.update', $organizationID) }}" class="lebar-100" method="POST"
                    enctype="multipart/form-data">
                    <div class="row">
                        <div class="bagi bagi-2 mt-4" style="width: 70%">
                            <h2 style="color: #304156; font-size:32px;">Profil</h2>
                            <h5 class="teks-transparan mb-4" style="margin-bottom: 40px !important; ">Lengkapi Profil Organisasi
                            </h5>
                            {{ csrf_field() }}
                            <div class="mt-2"></div>
                            <section class="">                                                     
                                <!-- Upload area -->
                                <div id="inputLogoAreaProfile" class="bagi mt-1 d-none ext-box-img-up">
                                    <img class="rounded-circle img-up" for="upload-logo"
                                        src="{{ asset('images/profile-user.png') }}"
                                        style="width: 80px;height: 80px; margin-top:30px;">
                                    <input type="file" accept="image/jpg, image/png, image/jpeg" class="box no-bg field-up-img" name="logo" id="upload-logo"
                                        onchange="uploadLogo(this,{{ json_encode($myData->package->max_attachment) }},1)">
                                    <div class="uploadArea" style="color:#E6286E;">
                                        <p class="p-up-img">Upload Photo <i
                                                class="fa bi bi-camera icon-up-img text-secondary"></i></p>
                                    </div>
                                    <div class="uploadArea trash-btn bg-secondary" style="color:#E6286E;">
                                        <p class="p-up-img"><i class="fa bi bi-trash3 text-secondary"></i></p>
                                    </div>
                                    <p class="desc-photo-up" for="uploadPhoto">Upload Photo (Maks {{ $myData->package->max_attachment }}Mb) dan persegi</p>
                                </div>
                                <div id="previewLogoAreaProfile" class="bagi mt-1 ext-box-img-up">
                                    @if ($organization->logo == '')
                                        <img id="logoPreviewProfile" class="rounded-circle img-up" for="upload-logo"
                                            src="{{ $organization->logo == '' ? asset('storage/organization_logo/default_logo.png') : asset('storage/organization_logo/' . $organization->logo) }}"
                                            style="width: 80px;height: 80px; margin-top:30px;">
                                    @else
                                        <img id="logoPreviewProfile" class="rounded-circle img-up" for="upload-logo"
                                            src="{{ $organization->logo == '' ? asset('storage/organization_logo/default_logo.png') : asset('storage/organization_logo/' . $organization->logo) }}"
                                            style="width: 80px;height: 80px; margin-top:30px;">
                                    @endif
                                    <input type="file" disabled class="box no-bg field-up-img" name="logo" id="upload-logo">
                                    <div class="uploadArea bg-secondary" style="color:#E6286E;">
                                        <p class="p-up-img">Upload Photo <i
                                                class="fa bi bi-camera icon-up-img text-secondary"></i></p>
                                    </div>
                                    <div class="uploadArea trash-btn" onclick="removePreview()" style="color:#E6286E;">
                                        <p class="p-up-img"><i class="fa bi bi-trash3 text-secondary"></i></p>
                                    </div>
                                    <p class="desc-photo-up">Upload Photo (Maks {{ $myData->package->max_attachment }}Mb) dan persegi</p>
                                </div>
                            </section>
    
                            <div class="" style="margin-top: 34px !important;">Nama Organisasi :</div>
                            <input type="text" class="box no-bg" name="name" value="{{ $organization->name }}">
    
                            <div class="row mt-3">
                                <div class="form-group col-md-6">
                                    <label for="">Tipe Organisasi</label>
                                    <select class="form-control no-bg box" name="type" id="tipe">
                                        <option selected value="{{ $organization->type }}">{{ $organization->type }}</option>
                                        @foreach ($organization_types as $types)
                                            <option value="{{ $types->name }}">
                                                {{ $types->name }} </option>
                                        @endforeach
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Tertarik untuk mengadakan</label>
                                    <select class="form-control no-bg box" name="interest" id="tertarik">
                                        @foreach ($organization_interests as $interests)
                                            <option value="{{ $interests->name }}"
                                                {{ $interests->name == $organization->interest ? 'selected="selected"' : '' }}>
                                                {{ $interests->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
    
                            <div class="row">
                                {{-- name input file img = img_banner --}}
                                <div class="col-12">
                                    <label for="">Banner Info</label>
                                </div>
                                <div class="col-12 lebar-100 mt-1 {{ $organization->banner_img == null || $organization->banner_img == '' ? '' : 'd-none' }}" id="inputBannerArea">
                                    {{-- <input type="hidden" name="execution_type" value="online"> --}}
                                    <input id="banner" type="file" accept="image/jpg, image/png, image/jpeg" name="banner" class="box" onchange="chooseFileBanner(this, {{ json_encode($organization->user->package->max_attachment) }})">
                                    <label class="w-100 rounded-8 uploadArea asp-rt-5-2" style="color: #E6286E;" for="banner">
                                        <div class="content-banner">
                                            <i class="fa fa-image" style="font-size: 36px; color: #e5214f; /*#EB597B*/"></i><br>
                                            <span
                                                style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 14px; color: #e5214f; /*#EB597B*/">
                                                Upload Banner Informasi max size {{ $organization->user->package->max_attachment }} Mb
                                            </span><br>
                                            <span
                                                style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 12px; color: #E6286E;">
                                                Recommended 1160 x 232px, With Aspect Ratio(5:1), PNG, JPG
                                            </span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-12 lebar-100 rata-tengah rounded-8 {{ $organization->banner_img == null || $organization->banner_img == '' ? 'd-none' : '' }}" id="previewBannerArea">
                                    <img src="{{ asset('storage/organization_logo/'.$organization->banner_img) }}" class="rounded-8 img-preview" id="bannerPreview" /><br><br>
                                    <span class="pointer bg-merah lebar-100 btn" onclick="removePreviewBanner()">hapus</span>
                                </div>
                            </div>
                            <div class="mt-4">Deskripsi</div>
                            <textarea rows="10" class="box no-bg" name="description" id="description" style="padding-top: 3%;"
                                required>{{ $organization->description }}</textarea>
    
                            <div class="mt-2">Email :</div>
                            <input type="email" class="box no-bg" name="email" value="{{ $organization->email }}"
                                required>
    
                            <div class="form-group" style="padding-top: 3%;">
                                <label for="telp">No. Telp :</label>
                                <div class="input-group mt-1">
                                    {{-- <span class="input-group-text medsos no-bg" id="telp">+62</span> --}}
                                    <input type="text" class="form-control no-bg add-form-control" name="notelepon" id="telp"
                                        placeholder="Minim 10 digit (0xxxxxxxxx)" minlength="10" value="{{ $organization->no_telepon }}"
                                        maxlength="15" required>
                                </div>
                            </div>
    
                            <div class="mt-5 lebar-100">
                                <h2 style="padding-top: 5%;">Media sosial</h2>
                                <div class="form-group" style="padding-top: 3%;">
                                    <label for="instagram">Instagram :</label>
                                    <div class="input-group mt-1">
                                        <span class="input-group-text medsos" id="instagram"><i class="fa fa-instagram"
                                                aria-hidden="true"></i></span>
                                        <input type="url" name="instagram" id="instagram"
                                            class="form-control no-bg add-form-control"
                                            placeholder="https://www.instagram.com/yourprofile"
                                            aria-label="https://www.instagram.com/yourprofile"
                                            aria-describedby="https://www.instagram.com/yourprofile"
                                            value="{{ $organization->instagram }}">
                                    </div>
                                </div>
    
                                <div class="form-group">
                                    <label for="linkedin">LinkedIn :</label>
                                    <div class="input-group mt-1">
                                        <span class="input-group-text medsos" id="linkedin"><i class="fa fa-linkedin"
                                                aria-hidden="true"></i></span>
                                        <input type="url" name="linkedin" id="linkedin"
                                            class="form-control no-bg add-form-control"
                                            placeholder="https://linkedin/yourprofile" aria-label="https://linkedin/yourprofile"
                                            aria-describedby="https://linkedin/yourprofile"
                                            value="{{ $organization->linked }}">
                                    </div>
                                </div>
    
                                <div class="form-group">
                                    <label for="twitter">Twitter :</label>
                                    <div class="input-group mt-1">
                                        <span class="input-group-text medsos" id="twitter"><i class="fa fa-twitter"
                                                aria-hidden="true"></i></span>
                                        <input type="url" name="twitter" id="twitter"
                                            class="form-control no-bg add-form-control"
                                            placeholder="https://www.twitter.com/yourprofile"
                                            aria-label="https://www.twitter.com/yourprofile"
                                            aria-describedby="https://www.twitter.com/yourprofile"
                                            aria-label="https://www.twitter.com/yourprofil"
                                            value="{{ $organization->twitter }}">
                                    </div>
                                </div>
    
                                <div class="form-group">
                                    <label for="website">Website :</label>
                                    <div class="input-group mt-1">
                                        <span class="input-group-text medsos" id="website"><i class="fa fa-paper-plane"
                                                aria-hidden="true"></i></span>
                                        <input type="url" name="website" id="website"
                                            class="form-control no-bg add-form-control" placeholder="https://yourwebsite.com"
                                            aria-label="https://yourwebsite.com" aria-describedby="https://yourwebsite.com"
                                            aria-label="https://yourwebsite.com" value="{{ $organization->website }}">
                                    </div>
                                </div>
                            </div>
                            <!-- <button type="submit" class="bg-primer mt-3 lebar-100" style="border-radius: 6px;">Simpan</button> -->
                            <button type="submit" class="bg-primer mt-3 mb-3 submit-btn btn-submit-2 ml-0"><i
                                    class="far fa-save mr-1"></i> Simpan</button>
    
    
    
                        </div>
                        <div id="btn-save-2" class="bagi bagi-2 float-right mr-5 wd-20">
                            <button type="submit" class="bg-primer mt-3 submit-btn btn-submit-2"><i
                                    class="far fa-save mr-1"></i> Simpan</button>
                        </div>
                    </div>
                </form>
            @endforeach

        </div>

        <div id="Team" class="tabcontent-organisasi" style="display: none; border: none;">
            <div class="row mt-4">
                <div class="col-md-7">
                    <h2 style="color: #304156; font-size:32px;">Team</h2>
                    <h5 class="teks-transparan mb-4">Temukan member dari organisasimu</h5>
                </div>
                <div class="col-md-5">
                    <button type="button" class="bg-primer mt-0 font-inter btn-float-r"
                        onclick="munculPopup('#invite_members')">
                        <i class="fa fa-plus"></i> Invite
                    </button>
                    <div class="square-inner toggle-view-button table-button" mode="table" onclick="toggleView(this)">
                        <i class="fa fa-bars"></i>
                    </div>
                    <div class="square-inner active toggle-view-button card-button" mode="card" onclick="toggleView(this)">
                        <i class="fa fa-th-large"></i>
                    </div>
                </div>
            </div>

            <div id="card-mode" class="d-block">
                @if (count($teams) == 0)
                    <div class="rata-tengah mt-4">
                        <i class="bi bi-people teks-primer font-img"></i>
                        <h3>Mulai membuat team di event eventmu</h3>
                        <p>Adakan berbagai event menarik di AgendaKota</p>
                    </div>
                @else
                    @foreach ($teams as $team)
                        <div class="bagi bagi-3 list-item mb-2">
                            <div class="wrap">
                                <div class="bg-putih rounded bayangan-5">
                                    <div class="" style=" padding: 5%;">
                                        <div class="d-flex">
                                            @if ($team->users->photo == 'default')
                                                <div class="picture mr-auto"
                                                    bg-image="{{ asset('images/profile-user.png') }}">
                                                </div>
                                            @else
                                                <div class="picture mr-auto"
                                                    bg-image="{{ asset('storage/profile_photo/' . $team->users->photo) }}">
                                                </div>
                                            @endif
                                            <div>
                                                <form method="POST" action="{{route('organization.team.delete', [$organizationID, $team->id])}}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger mt-4 mr-3 text-light" aria-label="Hapus anggota tim ?"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                        <p class="teks-tebal mb-0">
                                            {{ $team->users->name }}
                                        </p>
                                        <p class="teks-tipis">
                                            @php
                                                
                                                $objOrganization = Organization::where('user_id', $team->users->id)->first();
                                                
                                                if ($objOrganization == '' || $objOrganization == null) {
                                                    echo '-';
                                                } else {
                                                    echo $objOrganization->name;
                                                }
                                                
                                            @endphp
                                        </p>
                                        <p class="teks-tipis-bio">
                                            {{ $team->users->bio }}

                                            @if ($team->users->bio == '' || $team->users->bio == null)
                                                -
                                            @endif
                                        </p>
                                        <div class="ic-mail" style="font-size: 15pt; color:lightgray;">
                                            <a href="mailto: {{ $team->users->email }}"
                                                style="font-size:16pt; color:grey;">
                                                <i class="fa fa-envelope-o"></i>
                                            </a>
                                            <a href="{{ $team->users->linkedin }}" style="font-size:16pt; color:grey;">
                                                <i class="fa fa-linkedin"></i>
                                            </a>
                                            <a href="{{ $team->users->instagram_profile }}"
                                                style="font-size:16pt; color:grey;">
                                                <i class="fa fa-instagram"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach

                @endif

            </div>

            <div id="table-mode" class="table-mode d-none">
                @if (count($teams) == 0)
                    <div class="rata-tengah mt-4">
                        <i class="bi bi-people teks-primer font-img"></i>
                        <h3>Mulai membuat team di event eventmu</h3>
                        <p>Adakan berbagai event menarik di AgendaKota</p>
                    </div>
                @else
                    {{-- @php
                        $teamsTable = $paginateController->paginate($teams, $perPage = 1);
                    @endphp --}}
                    <div class="w-100 d-inline-block mt-3 mb-2">
                        <input type="search" placeholder="Search..." class="float-right form-control search-input"
                            style="width: unset" data-table="team-list" />
                    </div>
                    <div class="table-responsive">
                        <table id="teams-table" class="table table-borderless team-list">
                            <thead>
                                <tr>
                                    <th scope="col">Member Name</th>
                                    <th scope="col">
                                        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                                    </th>
                                    <th scope="col">From&emsp;&emsp;&emsp;&emsp;</th>
                                    <th scope="col">
                                        Bio&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                                    </th>
                                    <th scope="col">&emsp;&emsp;&emsp;&emsp;</th>
                                </tr>
                            </thead>
                            <tbody class="mt-2">
                                @foreach ($teams as $team)
                                    <tr>
                                        <td>
                                            @if ($team->users->photo == 'default')
                                                <img class="img-table img-table-square"
                                                    src="{{ asset('images/profile-user.png') }}">
                                            @else
                                                <img class="img-table"
                                                    src="{{ asset('storage/profile_photo/' . $team->users->photo) }}">
                                            @endif
                                        </td>
                                        <td>
                                            <p class="teks-tebal mb-0">
                                                {{ $team->users->name }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="teks-tipis">
                                                @php
                                                    
                                                    $objOrganization = Organization::where('user_id', $team->users->id)->first();
                                                    
                                                    if ($objOrganization == '' || $objOrganization == null) {
                                                        echo 'Belum berorganisasi';
                                                    } else {
                                                        echo 'Dari ' . $objOrganization->name;
                                                    }
                                                    
                                                @endphp
                                            </p>
                                        </td>
                                        <td>
                                            <p class="teks-tipis">
                                                {{ $team->users->bio }}

                                                @if ($team->users->bio == '' || $team->users->bio == null)
                                                    -
                                                @endif
                                            </p>
                                        </td>
                                        <td>
                                            <i class="fa fa-ellipsis-h pointer dropdownToggle"
                                                data-id="{{ $team->id }}"
                                                style=" color: #C4C4C4; font-size:20pt; float: right;"
                                                aria-hidden="true"></i>
                                        </td>
                                        <div id="Dropdown{{ $team->id }}" class="dropdown-content ml-auto mr-auto"
                                            style="min-width: 180px">
                                            <a class="mt-3" href="mailto: {{ $team->users->email }}">
                                                <i class="fa fa-lg fa-envelope-o d-content"></i>
                                                <p class="fontBold d-inline-block font-weight-bold"> E-Mail</p>
                                            </a>
                                            <a class="" href="{{ $team->users->linkedin }}">
                                                <i class="fab fa-lg fa-linkedin  d-content"> </i>
                                                <p class="fontBold d-inline-block font-weight-bold"> LinkedIn</p>
                                            </a>
                                            <a class="" href="{{ $team->users->instagram_profile }}">
                                                <i class="fab fa-lg fa-instagram d-content"> </i>
                                                <p class="fontBold d-inline-block font-weight-bold"> Instagram</p>
                                            </a>
                                        </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- <div class="d-flex float-right">
                        {{ $teamsTable->links() }}
                    </div> --}}
                @endif
            </div>

        </div>

        <div id="Billing" class="tabcontent-organisasi" style="display: none; border: none;">

            <div class="row mt-4">
                <div class="col-lg-7" style="">
                    <h2 style="color: #304156; font-size:32pX;font-family: 'Inter', sans-serif;">Billing</h2>
                    <h5 class="teks-transparan mb-4">Temukan informasi keuangan event eventmu
                    </h5>
                    {{-- <h4 style="color: #979797; margin-top:-1%;font-family: 'RobotoLight';font-weight: bold;">Temukan semua event yang diadakan oleh organisasimu</h4> --}}
                </div>
                <div class="col-lg-5 tab scrollmenu float-right mb-4 text-right" style="border: none">
                    {{-- <div > --}}
                    <button id="selling-tab" class="tab-btn tablinks-billing active"
                        onclick="opentabs(event, 'billing', 'selling')">Selling</button>
                    <button id="account-bank-tab" class="tab-btn tablinks-billing"
                        onclick="opentabs(event, 'billing', 'account')">Bank
                        Account</button>
                    <button id="withdrawals-tab" class="tab-btn tablinks-billing"
                        onclick="opentabs(event, 'billing', 'withdrawals')">Withdrawals</button>
                    {{-- </div> --}}
                </div>
            </div>

            <h5>Total Pendapatan</h5>
            @php 
                // $wdFinish = $myData->withdrawals->where('organization_id', $organizationID)->where('status', 'accepted');
                // $totalNominal = $totalNominal - $wdFinish;
            @endphp
            <h6>@currencyEncode($totalNominal),00</h6>

            <div id="selling" class="tabcontent-billing" style="display: block; border: none;">
                <div class="w-100 d-inline-block mt-3 mb-2">
                    <input type="search" placeholder="Search..." class="float-right form-control search-input"
                        style="width: unset" data-table="payments-list" />
                </div>
                <div class="table-responsive">
                    <table id="payments-table" class="table table-borderless payments-list">
                        <thead>
                            <tr>
                                <th>Nama Event&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                <th><i
                                        class="fas fa-clock"></i>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                                </th>
                                <th>Jumlah Transaksi&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                <th>Nominal Kotor&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                <th>Nominal Bersih&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                <th>Ajukan Penarikan&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                            </tr>
                        </thead>

                        <tbody class="mt-2">
                            @foreach ($dataPayments as $paymentData)
                                <tr>
                                    <td>{{ $paymentData['event']->name }}</td>
                                    <td>
                                        {{ Carbon::parse($paymentData['event']->start_date)->format('d M Y') }} -
                                        {{ Carbon::parse($paymentData['event']->end_date)->format('d M Y') }}
                                    </td>
                                    <td>
                                        {{ count($paymentData['data']) }}
                                    </td>
                                    <td>
                                        @currencyEncode($paymentData['value']),00
                                    </td>
                                    @php
                                        $endEvent = new DateTime($paymentData['event']->end_date . ' ' . $paymentData['event']->end_time, new DateTimeZone('Asia/Jakarta'));
                                        $withdrawAcc = UserWithdrawalEvent::where('event_id', $paymentData['event']->id)->where('status', 'accepted')->get();
                                        $withdrawWaiting = UserWithdrawalEvent::where('event_id', $paymentData['event']->id)->where('status', 'waiting')->get();
                                    @endphp
                                    <td>
                                        @currencyEncode($paymentData['trueNominal']),00
                                    </td>
                                    @if ($now > $endEvent)
                                        @if (count($withdrawAcc) == 0 &&
                                            count($withdrawWaiting) == 0)
                                            <td>
                                                {{-- <form action="#" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="event_id"
                                                        value="{{ $paymentData['event']->id }}">
                                                    <button type="submit" class="btn btn-success btn-no-pd">Ajukan</button>
                                                </form> --}}
                                                <button class="btn btn-success btn-no-pd"
                                                    onclick="selectSelling({{ $paymentData['event']->id }}); munculPopup('#add_withdrawal_single');">Ajukan</button>
                                            </td>
                                        @else
                                            <td>
                                                <button class="btn btn-secondary btn-no-pd">Ajukan</button>
                                            </td>
                                        @endif
                                    @else
                                        <td>
                                            <button class="btn btn-secondary btn-no-pd">Ajukan</button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                        @if (count($dataPayments) == 0)
                            <div class="rata-tengah mt-4">
                                <i class="bi bi-calendar teks-primer font-img"></i>
                                <h3>Eventmu Masih Kosong</h3>
                                <p>Adakan berbagai event menarik di AgendaKota</p>
                            </div>
                        @endif
                    </table>
                </div>
                {{-- <div class="d-flex float-right">
                    {{ $dataPayments->links() }}
                </div> --}}
                {{-- @dump((int)ceil(count($dataPayments)/10)) --}}
            </div>
            <div id="withdrawals" class="tabcontent-billing" style="display: none; border: none;">
                <div class="text-right">
                    <button class="btn btn-primer float-right mt-3 mb-4" style="width: unset"
                        onclick="munculPopup('#add_withdrawal')">
                        <i class="fa fa-plus" style="color: #fff;"></i> Withdrawal
                    </button>
                </div>
                {{-- // Access Eloquent with where testing
                @dump($myData->withdrawals->where('organization_id', $organizationID)) --}}
                @if (count($myData->withdrawals->where('organization_id', $organizationID)) == 0)
                    <div class="rata-tengah mt-4">
                        <i class="bi bi-card-checklist teks-primer font-img"></i>
                        <h3>Kamu Belum Pernah Menarik Penghasilan</h3>
                        <p>Adakan berbagai event menarik di AgendaKota</p>
                    </div>
                @else
                    <div class="w-100 d-inline-block mt-3 mb-2">
                        <input type="search" placeholder="Search..." class="float-right form-control search-input"
                            style="width: unset" data-table="withdrawals-list" />
                    </div>
                    <div class="table-responsive">
                        <table id="withdrawals-table" class="table table-borderless withdrawals-list">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-clock"></i> Tanggal
                                        Pengajuan&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                                    </th>
                                    <th>Rekening&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                    <th>Nama Bank&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                    <th>Nominal (Rupiah)&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                    <th>Status&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                    <th>Aksi&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                </tr>
                            </thead>

                            <tbody class="mt-2">
                                @foreach ($myData->withdrawals->where('organization_id', $organizationID) as $withdrawal)
                                    <tr>
                                        <td>
                                            {{ Carbon::parse($withdrawal->created_at)->format('d M Y H:i') }}
                                        </td>
                                        <td>
                                            {{ $withdrawal->account_number }}
                                        </td>
                                        <td>
                                            {{ $withdrawal->bank_name }}
                                        </td>
                                        <td>
                                            @currencyEncode($withdrawal->nominal),00
                                        </td>
                                        <td>
                                            @if ($withdrawal->status == 'accepted')
                                                <span class="bg-success text-light rounded-5 pl-3 pr-3">Accepted</span>
                                            @elseif ($withdrawal->status == 'waiting')
                                                <span class="bg-dark text-light rounded-5 pl-3 pr-3">Waiting</span>
                                            @elseif ($withdrawal->status == 'rejected')
                                                <span class="bg-danger text-light rounded-5 pl-3 pr-3">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('organization.withdraw.detail', [$organizationID, $withdrawal->id]) }}"
                                                class="text-dark" style="text-decoration: none;">
                                                <i class="bi bi-eye"></i> Lihat Detail
                                            </a>
                                            @if ($withdrawal->status == 'waiting')
                                                <form class="mt-2"
                                                    action="{{ route('organization.withdraw.delete', [$organizationID]) }}"
                                                    method="post">
                                                    @csrf
                                                    <input type="hidden" name="idWithdraw" value="{{ $withdrawal->id }}">
                                                    <button type="submit" class="btn btn-danger btn-no-pd"><i
                                                            class="bi bi-arrow-left-circle"></i> Batalkan</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div id="account" class="tabcontent-billing" style="display: none; border: none;">
                <div class="text-right d-inline-block w-100 mt-3 mb-2">
                    <button class="btn btn-primer float-right" style="width: unset" onclick="munculPopup('#add_VA')">
                        <i class="fa fa-plus" style="color: #fff;"></i> Account
                    </button>
                </div>
                @if (count($myData->bankAccounts) == 0)
                    <div class="rata-tengah mt-4">
                        <i class="bi bi-credit-card teks-primer font-img"></i>
                        <h3>Kamu Belum Punya Rekening Terdaftar</h3>
                        <p>Adakan berbagai event menarik di AgendaKota</p>
                    </div>
                @else
                    <div class="row">
                        @foreach ($myData->bankAccounts as $bankAccount)
                            {{-- Pembaruan model card --}}
                            <div class="col-md-4 list-item mb-3">
                                <div class="">
                                    <div class="card-account">
                                        <div class="img-avatar">
                                            <img src="{{ asset('images/bank-logo/' . $bankAccount->bank_name . '.png') }}"
                                                alt="">
                                        </div>
                                        <div class="row">
                                            <div class="col-12" style="height: 80px">
                                                <div class="portada">
                                                    <form class="d-inline"
                                                        action="{{ route('organization.bankaccount.delete', [$organizationID]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="idAccount"
                                                            value="{{ $bankAccount->id }}">
                                                        <button type="submit" class="btn mt-2 mr-2"><i
                                                                class="fas fa-trash fs-icon"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                @if ($bankAccount->status == 0)
                                                    <div class="title text-danger">
                                                        Inactive

                                                    </div>
                                                @else
                                                    <div class="title text-success">
                                                        Active
                                                        
                                                    </div>
                                                @endif

                                                <h5>{{ $bankAccount->bank_name }}</h5>

                                                <div class="desc">
                                                    <h6 class="font-inter-header">{{ $bankAccount->account_number }}
                                                    </h6>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- -------------------- --}}

                            {{-- <div class="bagi bagi-3 list-item mb-2">
                                <div class="wrap">
                                    <div class="bg-putih bayangan-5 rounded-5 smallPadding">
                                        <div class="detail">
                                            <div class="wrap">
                                                <div class="text-right">
                                                    <form
                                                        action="{{ route('organization.bankaccount.delete', [$organizationID]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="idAccount"
                                                            value="{{ $bankAccount->id }}">
                                                        <button type="submit" class="btn"><i
                                                                class="fas fa-trash fs-icon"></i></button>
                                                    </form>
                                                </div>
                                                <h5 class="font-inter-header">{{ $bankAccount->bank_name }}

                                                </h5>
                                                <h6 class="font-inter-header">{{ $bankAccount->account_number }}

                                                </h6>
                                                <li class="d-flex">
                                                    <div class="text desc-card">
                                                        Status : {{ $bankAccount->status == 0 ? 'Inactive' : 'Active' }}
                                                    </div>
                                                </li>
                                                <li class="d-flex">
                                                    <div class="icon"><i class="fas bi bi-calendar"></i></div>
                                                    <div class="text desc-card">
                                                        {{ Carbon::parse($bankAccount->created_at)->isoFormat('D MMMM') }}
                                                    </div>
                                                </li>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div id="Delete" class="tabcontent-organisasi" style="display: none; border: none;">
            <form method="POST" action="{{route('organization.delete', $organization->id)}}" class="mt-4">
                @csrf
                <div class="form-group">
                  <label for="org-name">Ketikkan nama organisasi berikut <b>{{ $organization->name }}</b> untuk konfirmasi penghapusan</label>
                  <input type="text" class="form-control" name='org_name' id="org-name" placeholder="Nama organisasi">
                </div>
               
                <button type="submit" class="btn btn-danger">Delete Organization</button>
              </form>
        </div>
    @endif


    <!-- Pop Up Wrapper -->

    <div class="bg"></div>
    <div class="popupWrapper" id="invite_members">
        <div class="popup bayangan-5 rounded-5 pl-4 pr-4 pt-4 pb-4 lebar-50">

            <h3 class="font-inter-header" style="text-align: center;">Invite Members
                <i class="fas bi bi-x-circle-fill op-5 ke-kanan pointer" onclick="hilangPopup('#invite_members')"></i>
            </h3>
            <div class="wrap">
                <form action="{{ route('organization.invite-teams', [$organizationID]) }}" method="POST">
                    @csrf
                    <span class="font-inter mt-2 text-left" style="font-style: normal">Email Member</span>
                    <input type="text" class="box mb-4" name="member" required value="{{ old('member') }}">
                    <div class="text-center">
                        <button class="primer mt-2" style="border-radius: 8px;">Kirim
                            Undangan</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    {{-- -------> Pop Up Add Event Pilihan (Model lama) <-----------
    <div class="bg"></div>
    <div class="popupWrapper" id="createEvent">
        <div class="popup bayangan-5 rounded-5 pl-4 pr-4 pt-4 pb-4 lebar-70">
            <h3>
                <i class="fas bi bi-x-circle-fill op-5 ke-kanan pointer" onclick="hilangPopup('#createEvent')"></i>
            </h3>
            <div class="wrap">
                <h3 class="rata-tengah mb-4">Pilih Jenis Event

                </h3>
                <div class="rata-tengah">
                    <br>
                    <hr>
                    <a href="{{ route('organization.event.create', [$organizationID]) }}"
                        style="text-decoration:none;color: #fff;" class="font-inter">
                        <button type="button" class="btn bg-primer mt-0 lebar-50">
                            Online Event
                        </button>
                    </a>
                    <br>
                    <br>
                    ----- Atau -----
                    <br>
                    <br>
                    <a href="{{ route('organization.event.create2', [$organizationID]) }}"
                        style="text-decoration:none;color: #fff;" class="font-inter">
                        <button type="button" class="btn bg-primer mt-0 lebar-50">
                            Offline Event
                        </button>
                    </a>

                </div>
            </div>
        </div>
    </div> --}}

    <div class="bg"></div>
    <div class="popupWrapper" id="add_VA">
        <div class="popup bayangan-5 rounded-5 pl-4 pr-4 pt-4 pb-4 lebar-50">

            <h3 class="font-inter-header" style="text-align: center;">Tambah Rekening
                <i class="fas bi bi-x-circle-fill op-5 ke-kanan pointer" onclick="hilangPopup('#add_VA')"></i>
            </h3>
            <div class="wrap">
                <form action="{{ route('organization.bankaccount.store', [$organizationID]) }}" method="POST">
                    @csrf
                    <span class="font-inter mt-2 text-left" style="font-style: normal">Nama Bank</span>
                    <select required name="bankName" class="box">
                        @foreach (config('agendakota')['bank_list'] as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                    <span class="font-inter mt-2 text-left" style="font-style: normal">Rekening</span>
                    <input type="text" class="box mb-4" name="account" maxlength="212" required
                        value="{{ old('account') }}">
                    <div class="text-center">
                        <button class="primer mt-2" style="border-radius: 8px;">Simpan</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div class="bg"></div>
    <div class="popupWrapper" id="add_withdrawal">
        <div class="popup bayangan-5 rounded-5 pl-4 pr-4 pt-4 pb-4" style="width: 90%">

            <h3 class="font-inter-header" style="text-align: center;">Buat Withdraw
                <i class="fas bi bi-x-circle-fill op-5 ke-kanan pointer" onclick="hilangPopup('#add_withdrawal')"></i>
            </h3>
            <div class="wrap">
                <form action="{{ route('organization.withdraw.store', [$organizationID]) }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table id="payments-select-table" class="table table-borderless payments-list">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nama Event&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                    <th><i
                                            class="fas fa-clock"></i>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                                    </th>
                                    <th>Jumlah Transaksi&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                    <th>Nominal Kotor&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                    <th>Nominal Bersih&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                </tr>
                            </thead>

                            <tbody class="mt-2">
                                @foreach ($dataPayments as $paymentData)
                                    @php
                                        $endEvent = new DateTime($paymentData['event']->end_date . ' ' . $paymentData['event']->end_time, new DateTimeZone('Asia/Jakarta'));
                                        // $trueNominal = (float) $paymentData['value'] - (float) $paymentData['value'] * config('agendakota')['profit'];
                                        // --------- Ganti potongan dari config 2.5% menjadi sesuai config pricing -------------------------
                                        $trueNominal = (float) $paymentData['value'] - (float) $paymentData['value'] * $myData->package->ticket_commission;
                                        if ($trueNominal > (config('agendakota')['min_transfer']+config('agendakota')['profit+'])) {
                                            $trueNominal = $trueNominal - config('agendakota')['profit+'];
                                        }
                                    @endphp
                                    @if ($now > $endEvent &&
                                        (count(
                                            UserWithdrawalEvent::where('event_id', $paymentData['event']->id)->where('status', 'accepted')->get(),
                                        ) == 0 &&
                                            count(
                                                UserWithdrawalEvent::where('event_id', $paymentData['event']->id)->where('status', 'waiting')->get(),
                                            ) == 0))
                                        <tr>
                                            <td>
                                                <label class="label-checkbox font-inter"
                                                    style="margin-top: -10px;font-weight: normal !important;">
                                                    <input class="form-check-input" type="checkbox" name="event_select[]"
                                                        value="{{ $paymentData['event']->id }}">

                                                    <span class="checkmark font-inter"
                                                        style="border: 1px solid #000 !important;"></span>
                                                </label>
                                            </td>
                                            <td>{{ $paymentData['event']->name }}</td>
                                            <td>
                                                {{ Carbon::parse($paymentData['event']->start_date)->format('d M Y') }} -
                                                {{ Carbon::parse($paymentData['event']->end_date)->format('d M Y') }}
                                            </td>
                                            <td>
                                                {{ count($paymentData['data']) }}
                                            </td>
                                            <td>
                                                @currencyEncode($paymentData['value']),00
                                            </td>

                                            <td>
                                                @currencyEncode($trueNominal),00
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        @if (count($dataPayments) == 0)
                            <div class="rata-tengah mt-4">
                                <i class="bi bi-credit-card teks-primer font-img"></i>
                                <h5>Pendapatan Yang Bisa Diajukan Masih Kosong</h5>
                                <p>Adakan berbagai event menarik di AgendaKota</p>
                            </div>
                        @else
                            @if (count($myData->bankAccounts->where('status', 1)) == 0)
                                <div class="rata-tengah mt-4">
                                    <h5>Belum Ada No. Rekening / Billing Account yang Aktif</h5>
                                </div>
                            @else
                                <div class="mt-2" style="padding-top: 3%;">No. Rekening :</div>
                                <select name="account" class="box" required>
                                    @foreach ($myData->bankAccounts->where('status', 1) as $bankAccount)
                                        <option value="{{ $bankAccount->id }}">
                                            {{ $bankAccount->account_number . ' -->' . $bankAccount->bank_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-success">Ajukan Pencairan</button>
                                </div>
                            @endif
                        @endif

                    </div>
                </form>
            </div>

        </div>
    </div>

    {{-- Pop Up add withdraw one by one in selling table button --}}
    <div class="bg"></div>
    <div class="popupWrapper" id="add_withdrawal_single">
        <div class="popup bayangan-5 rounded-5 pl-4 pr-4 pt-4 pb-4" style="width: 90%">

            <h3 class="font-inter-header" style="text-align: center;">Buat Withdraw
                <i class="fas bi bi-x-circle-fill op-5 ke-kanan pointer"
                    onclick="hilangPopup('#add_withdrawal_single')"></i>
            </h3>
            <div class="wrap">
                <form action="{{ route('organization.withdraw.store', [$organizationID]) }}" method="POST">
                    @csrf
                    <input id="eventID" type="hidden" name="event_select[]" value="">
                    @if (count($myData->bankAccounts->where('status', 1)) == 0)
                        <div class="rata-tengah mt-4">
                            <h5>Belum Ada No. Rekening / Billing Account yang Aktif</h5>
                        </div>
                    @else
                        <div class="mt-2" style="padding-top: 3%;">No. Rekening :</div>
                        <select name="account" class="box" required>
                            @foreach ($myData->bankAccounts->where('status', 1) as $bankAccount)
                                <option value="{{ $bankAccount->id }}">
                                    {{ $bankAccount->account_number . ' -->' . $bankAccount->bank_name }}</option>
                            @endforeach
                        </select>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-success">Ajukan Pencairan</button>
                        </div>
                    @endif
                </form>
            </div>

        </div>
    </div>

    <!-- End Pop Up wrapper -->

    {{-- </div> --}}
@endsection

@section('javascript')
    <script>
        // Auto open tab after update data and from url "to" param
        document.addEventListener(
            "DOMContentLoaded",
            function() {
                var fromPage = '<?php echo session('from'); ?>';
                if (fromPage != "") {
                    document.getElementById('billing-tab').click();
                    document.getElementById(fromPage).click();
                }

                var toPage = document.getElementById('auto-tab').value;
                if(toPage != null){
                    document.getElementById(toPage+'-tab').click();
                }
            });

        // Tab button function click
        let state = {
            isOptionOpened: false,
        }

        const toggleViewInner = btn => {
            let mode = btn.getAttribute('mode');
            selectAll(".toggle-view-button").forEach(button => {
                button.classList.remove('active');
            });
            btn.classList.add('active');
            selectAll(".list-item").forEach(item => {
                if (mode == "list") {
                    item.classList.add("is-list-mode");
                } else {
                    item.classList.remove("is-list-mode");
                }
            })
        }

        document.addEventListener("click", e => {
            selectAll(".dropdown-content").forEach(dropdown => {
                dropdown.classList.remove('show');
            });

            let target = e.target;
            console.log(target);
            if (target.classList.contains('dropdownToggle')) {
                console.log('disana')
                let id = target.getAttribute('data-id');
                document.getElementById("Dropdown" + id).classList.toggle("show");
                state.isOptionOpened = true;
            } else {
                state.isOptionOpened = false;
            }
        });

        const getSelectedBreakdown = () => {
            let selectedBreakdown = [];
            selectAll(".breakdowns").forEach(breakdown => {
                if (breakdown.classList.contains('active')) {
                    let breakdownType = breakdown.getAttribute('breakdown-type');
                    selectedBreakdown.push(breakdownType);
                }
            });
        }

        selectAll(".breakdowns").forEach(breakdown => {
            breakdown.addEventListener("click", e => {
                let target = e.currentTarget;
                target.classList.toggle('active');
                getSelectedBreakdown();
            })
        })

        const uploadLogo = (input,maxSize,isEdit = null) => {
            let file = input.files[0];
            maxSize = maxSize * 1024;
            let fileSize = file.size / 1024;
            if(fileSize > maxSize){
                Swal.fire({
                    title: 'Error!',
                    text: 'File maksimal '+(maxSize/1024)+' Mb',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function(){
                    removePreview();
                });
            }else{
                let reader = new FileReader();
                reader.readAsDataURL(file);

                reader.addEventListener("load", function() {
                    if (isEdit == null) {
                        let preview = select("#logoPreviewProfile");
                        select("#inputLogoAreaProfile").classList.add('d-none');
                        select("#previewLogoAreaProfile").classList.remove('d-none');
                        preview.setAttribute('src', reader.result);
                    } else {
                        let preview = select("#logoPreviewProfile");
                        select("#inputLogoAreaProfile").classList.add('d-none');
                        select("#previewLogoAreaProfile").classList.remove('d-none');
                        preview.setAttribute('src', reader.result);
                    }
                });
            } 
        }

        const removePreview = () => {
            select("#inputLogoAreaProfile").classList.remove('d-none');
            select("#previewLogoAreaProfile").classList.add('d-none');
        }

        // --------------- Upload banner info ---------------------
        const chooseFileBanner = (input,maxSize) => {
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
                    removePreview();
                });
            }else{
                let reader = new FileReader();
                let preview = select("#bannerPreview");
                reader.readAsDataURL(file);

                reader.addEventListener("load", function() {
                    select("#inputBannerArea").classList.add('d-none');
                    select("#previewBannerArea").classList.remove('d-none');
                    preview.setAttribute('src', reader.result);
                });
            }
        }
        const removePreviewBanner = () => {
            select("#inputBannerArea").classList.remove('d-none');
            select("#previewBannerArea").classList.add('d-none');
        }
        // --------------------------------------------------------

        @foreach ($events as $event)
            var arr = [];
            var total = "";
            @foreach ($event->sessions as $session)
                @foreach ($session->tickets as $ticket)
                    arr.push("{{ $ticket->price }}");
                @endforeach
            @endforeach

            var hargaTerendah = arr[0];
            var hargaTertinggi = arr[0];
            for (var i = 0; i < arr.length; i++) {
                if (arr[i] < hargaTerendah) {
                    hargaTerendah = arr[i];
                }
                if (arr[i] >= hargaTertinggi) {
                    hargaTertinggi = arr[i];
                }
            }

            var rupiahHargaTerendah = currencyRupiah(hargaTerendah);
            var rupiahHargaTertinggi = currencyRupiah(hargaTertinggi);

            if (hargaTertinggi == 0) {
                total = "Free";
            } else if (hargaTerendah == hargaTertinggi) {
                total = "Rp. " + rupiahHargaTerendah;
            } else {
                total = "Rp. " + rupiahHargaTerendah + " - Rp. " + rupiahHargaTertinggi;
            }

            var eventID = "{{ $event->id }}";
            document.getElementById('target-harga-tiket-' + eventID).innerHTML = '&nbsp;' + total;
            document.getElementById('target-harga-tiket2-' + eventID).innerHTML = total;
        @endforeach

        function selectSelling(eventID) {
            document.getElementById('eventID').value = eventID;
        }

        function confirmDelete(evt,eventData) {
            console.log(eventData);
            var status = 'TERPUBLIKASIKAN';
            if(eventData.is_publish == 0){
                status = 'belum dipublikasikan';
            }
            evt.preventDefault(); // prevent form submit
            var urlToRedirect = event.currentTarget.getAttribute('href');
            
                    Swal.fire({
                        title: "Apakah kamu yakin ?",
                        text: "Status event ini "+status+" dengan jumlah tiket terjual "+eventData.purchase.length+" buah tiket",
                        type: "warning",
                        icon: "warning",
                        dangerMode: true,
                        showCancelButton: true,
                        confirmButtonText: "Ya, hapus",
                        cancelButtonText: "Batal",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            console.log(urlToRedirect);
                            window.location.replace(urlToRedirect);         // submitting the form when user press yes
                        } else {
                            Swal.fire("Dibatalkan", "Event batal dihapus", "info");
                        }
                    });
        }
    </script>
    <script src="{{ asset('js/user/searchTable.js') }}"></script>
    <script src="{{ asset('js/user/paginationTable.js') }}"></script>
    <script>
        $(document).ready(function() {
            paginate('#payments-table', 'payments', 10);
            paginate('#events-table', 'events', 10);
            paginate('#teams-table', 'teams', 10);
            paginate('#withdrawals-table', 'withdrawals', 10);
        });
    </script>
@endsection

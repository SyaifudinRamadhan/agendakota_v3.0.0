@extends('layouts.user')

@section('title', "Media Partner")

@section('head.dependencies')
<link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/sponsorPage.css') }}">
@endsection

{{-- -------- Parameter List untuk menentukan batas pcakage user ------------------ --}}
@php
    $nowDt = new DateTime();
    $startLimit = new DateTime($organization->user->created_at);
    $different = $startLimit->diff($nowDt);
    $pkgActive = \App\Http\Controllers\PackagePricingController::limitCalculator($organization->user);
    
@endphp
{{-- ------------------------------------------------------------------------------ --}}

@section('content')
<div class="row">
    <div class="col-md-7">
        <h2>Media Partner</h2>
        <h4 class="mt-3" style="color: #979797; font-size:14">Create and manage media partner for your event</h4>
    </div>
    <div class="col-md-5">
        <button class="btn ke-kanan primer font-inter" onclick="confirmMyPkg()">
            <i class="fas fa-plus"></i> Media Partner
        </button>
    </div>
</div>

@include('admin.partials.alert')

@if(count($event->media_partners) == 0)
    <div class="mt-5 rata-tengah">
        <i class="fa bi bi-postcard font-img teks-primer mb-3"></i>
        <h3>Tambahkan media partner untuk Eventmu</h3>
        <p>Adakan berbagai event menarik di AgendaKota</p>
    </div>
@else
    @foreach ($event->media_partners as $media_partner)
        <div class="bagi bagi-3 mt-4">
            <div class="wrap">
                <div class="bg-putih rounded-5 bayangan-5">
    
                    <div class="label bg-primer lable-title">{{ $media_partner->type }}</div>
                    
                    <i class="fa fa-ellipsis-h pointer dropdownToggle bagi-bagi-2 pt-2 pr-3 pl-1" data-id="{{ $media_partner->id }}" style=" color: #C4C4C4; font-size:20pt; float: right;" aria-hidden="true"></i>
                    <div id="Dropdown{{$media_partner->id}}" class="dropdown-content rounded-5">
                       
                        <a class="lebar-100 pointer" onclick='edit(<?= json_encode($media_partner) ?>, <?= json_encode($event) ?>)'>
                            <span class="teks-hijau"><i class="fas fa-edit"></i> Edit</span>
                        </a>
                        
                        <form action="{{ route('organization.event.sponsor.delete', [$organizationID, $event->id, $media_partner->id]) }}" method="POST">
                            @csrf
                            @method('delete')
                            <input type="hidden" name="media" value="1">
                            <button class=" lebar-100 pointer rata-kiri no-pd-l no-border" type="submit">
                                <span class="teks-merah ml-4"><i class="fas fa-trash ml-1"></i> Delete</span>
                            </button>
                        </form>
                       
                    </div>

                    <div class="smallPadding">
                        <div class="wrap">
                            <div class="row">
                                <div class="col-12">
                                    <div class="w-100 asp-rt-5-2" bg-image="{{ asset('storage/event_assets/'.$event->slug.'/media_logo/'.$media_partner->logo) }}"></div>
                                </div>
                                {{-- <div class="col-xl">
                                    <h3 class="sponsor-title mt-2">{{ $media_partner->name }}</h3>
                                </div> --}}
                            </div>
                            <div class=" mt-2">
                                <a href="{{ $media_partner->website }}" target="_blank" class="teks-primer">
                                    See About Media
                                </a>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

<div class="bg"></div>
<div class="popupWrapper" id="addSponsor">
    <div class="popup rounded-5 lebar-70">
        <div class="wrap">
            <h3 class="rata-tengah mb-4">Media Partner Baru
                <i class="fas bi bi-x-circle-fill op-5 ke-kanan pointer" onclick="hilangPopup('#addSponsor')"></i>
            </h3>
            <form action="{{ route('organization.event.sponsor.store', [$organization->id, $event->id]) }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="media" value="1">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="wrap">
                            <div id="inputLogoArea">
                                <input type="file" accept="image/jpg, image/png, image/jpeg" id="input-file" class="box" name="logo" onchange="uploadLogo(this,{{ json_encode($organization->user->package->max_attachment) }})" required oninvalid="this.setCustomValidity('Harap Upload Gambar ')" oninput="setCustomValidity('')">
                                <!-- <div class="uploadArea">Upload Logo</div> -->
                                <label for="input-file" class="lebar-100">
                                    <div class="uploadArea font-inter-header rounded-5">
                                        <div class="img-cover-up">
                                            <img class="img-cover" src="{{asset('images/photo.png')}}">
                                        </div>
                                        Upload Logo
                                        <br>
                                        <span style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 12px; color: #E6286E;">
                                            Rasio 5 : 2 PNG, JPG max {{ $organization->user->package->max_attachment }} Mb
                                        </span>
                                    </div>
                                </label>
                            </div>
                            <div id="previewLogoArea" class="d-none">
                                <img id="logoPreview" class="rounded-5 img-preview"><br />
                                <span class="btn lebar-100 mt-3 pointer teks-merah" onclick="removePreview()">hapus</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="wrap">
                            <div class="mt-2">Tipe Media :</div>
                            <select name="type" class="box no-bg" required oninvalid="this.setCustomValidity('Harap Pilih Tipe Media Yang Ada Di List')" oninput="setCustomValidity('')">
                                <option value="">-- Pilih Tipe Media --</option>
                                @foreach ($sponsorTypes as $type)
                                    <option>{{ $type }}</option>
                                @endforeach
                            </select>
                            <div class="mt-2">Nama :</div>
                            <input type="text" class="box no-bg" name="name" required oninvalid="this.setCustomValidity('Data Ini Tidak Boleh Kosong')" oninput="setCustomValidity('')">
                            <div class="mt-2">Website URL :</div>
                            <input type="url" class="box no-bg" name="website" required oninvalid="this.setCustomValidity('Data Ini Tidak Boleh Kosong')" oninput="setCustomValidity('')" placeholder="https;//..." value="https://">
                        </div>
                    </div>
                </div>
                <div class="lebar-100 rata-tengah">
                    <button class="primer lebar-40 mt-2">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="popupWrapper" id="editSponsor">
    <div class="popup rounded-5 lebar-70">
        <div class="wrap">
            <h3 class="rata-tengah mb-4">Edit Media Partner
                 <i class="fas bi bi-x-circle-fill op-5 ke-kanan pointer" onclick="hilangPopup('#editSponsor')"></i>
            </h3>
            <form action="{{ route('organization.event.sponsor.update', [$organization->id, $event->id]) }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="media" value="1">
                <input type="hidden" name="media_id" id="sponsorID">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="wrap">
                            <div id="inputLogoArea" class="d-none">
                                <input type="file" accept="image/jpg, image/png, image/jpeg" id="input-file-2" class="box" name="logo" onchange="uploadLogo(this,{{ json_encode($organization->user->package->max_attachment) }},1)" oninvalid="this.setCustomValidity('Harap Upload Gambar ')" oninput="setCustomValidity('')">
                                <label for="input-file-2" class="lebar-100">
                                    <div class="uploadArea font-inter-header rounded-5">
                                        <div class="img-cover-up">
                                            <img class="img-cover" src="{{asset('images/photo.png')}}">
                                        </div>
                                        Upload Logo
                                        <br>
                                        <span style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 12px; color: #E6286E;">
                                            Rasio 5 : 2 PNG, JPG max {{ $organization->user->package->max_attachment }} Mb
                                        </span>
                                    </div>
                                </label>
                            </div>
                            <div id="previewLogoArea">
                                <img id="logoPreview" class="img-preview rounded-5"><br />
                                <span class="pointer teks-merah btn lebar-100 mt-3" onclick="removePreview(1)">hapus</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="wrap">
                            <div class="mt-2">Tipe Sponsor :</div>
                            <select id="type" name="type" class="box no-bg" required required oninvalid="this.setCustomValidity('Harap Pilih Tipe Sponsor Yang Ada Di List')" oninput="setCustomValidity('')">
                                <option value="">-- Pilih Tipe Media--</option>
                                @foreach ($sponsorTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                            <div class="mt-2">Nama :</div>
                            <input type="text" class="box no-bg" id="name" name="name" required oninvalid="this.setCustomValidity('Data Ini Tidak Boleh Kosong')" oninput="setCustomValidity('')">
                            <div class="mt-2">Website URL :</div>
                            <input type="url" class="box no-bg" id="website" name="website" required oninvalid="this.setCustomValidity('Data Ini Tidak Boleh Kosong')" oninput="setCustomValidity('')" placeholder="https;//...">
                        </div>
                    </div>
                </div>
                <div class="lebar-100 rata-tengah">
                    <button type="submit" class="lebar-40 primer mt-2">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>

    let state = {
        isOptionOpened: false,
    }

    document.addEventListener("click", e => {
        selectAll(".dropdown-content").forEach(dropdown => {
            dropdown.classList.remove('show');
        });


        let target = e.target;
        if (target.classList.contains('dropdownToggle')) {
            let id = target.getAttribute('data-id');
            document.getElementById("Dropdown"+id).classList.toggle("show");
            state.isOptionOpened = true;
        }else {
            state.isOptionOpened = false;
        }
    });

    const uploadLogo = (input,maxSize,isEdit = null) => {
        let file = input.files[0];
        maxSize = maxSize * 1024;
        let fileSize = file.size / 1024;
        if(fileSize > maxSize){
            Swal.fire({
                title: 'Error!',
                text: 'Maksimal upload file '+(maxSize/1024)+' Mb',
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        }else{
            let reader = new FileReader();
            reader.readAsDataURL(file);

            reader.addEventListener("load", function() {
                if (isEdit == null) {
                    console.log('no isEdit'+isEdit);
                    let preview = select("#logoPreview");
                    select("#inputLogoArea").classList.add('d-none');
                    select("#previewLogoArea").classList.remove('d-none');
                    preview.setAttribute('src', reader.result);
                }else {
                    console.log('in isEdit'+isEdit);
                    let preview = select("#editSponsor #logoPreview");
                    select("#editSponsor #inputLogoArea").classList.add('d-none');
                    select("#editSponsor #previewLogoArea").classList.remove('d-none');
                    preview.setAttribute('src', reader.result);
                }
            });
        }
    }
    const removePreview = (isEdit = null) => {
        if (isEdit == null) {
            select("input[type='file']").value = "";
            select("#inputLogoArea").classList.remove('d-none');
            select("#previewLogoArea").classList.add('d-none');
        }else {
            select("#editSponsor input[type='file']").value = "";
            select("#editSponsor #inputLogoArea").classList.remove('d-none');
            select("#editSponsor #previewLogoArea").classList.add('d-none');
        }
    }
    const edit = (data, event = null) => {
        
        munculPopup("#editSponsor");
        select("#editSponsor #sponsorID").value = data.id;
        select("#editSponsor #name").value = data.name;
        select("#editSponsor #website").value = data.website;
        select(`#editSponsor #type option[value='${data.type}']`).selected = true;
        select("#editSponsor #logoPreview").setAttribute('src', `{{ asset('storage/event_assets/${event.slug}/media_logo/${data.logo}') }}`);
    }

    function confirmMyPkg() {
        var pkgActive = '{{ $pkgActive }}';
        var pkgActive = parseInt(pkgActive);
        var thisEvent = <?php echo json_encode($event) ?>;
        var myPkg = <?php echo json_encode($organization->user->package) ?>;
                
        console.log(thisEvent, myPkg);
        var paramCombine = false;
        // paramCombine adalah parameter untuk cek apakah unlimited / sudah tercapai batasnya ?
        if(myPkg.sponsor_count <= -1){
            paramCombine = false;
        }else{
            if(thisEvent.media_partners.length >= myPkg.partner_media_count){
                paramCombine = true;
            }
        }
        if(pkgActive == 0 || paramCombine == true){
            // Batalkan dengan konfirmasi sweet alert
            var msg = '';
            if(paramCombine == true){
                msg = 'Kamu sudah melewati batas paket untuk membuat media partner baru';
            }
            else{
                msg = 'Paket yang kamu beli sudah melewati satu bulan / belum dibayar !!!';
            }
            Swal.fire({
                title: 'Error!',
                text: msg,
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Ok',
                cancelButtonText: "Batal",
            }).then((result) => {
                if(result.isConfirmed){
                    window.open("{{ route('user.upgradePkg') }}");
                }
            });
        }else{
            // Munculkan pop up tambah organisasi
            munculPopup('#addSponsor')
        }
    }
</script>
@endsection

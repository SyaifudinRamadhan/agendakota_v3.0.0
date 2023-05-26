@extends('layouts.user')

@section('title', "Exhibitor")

@section('head.dependencies')
<link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/exhibitorPage.css') }}">
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
        <h2 style="margin-top: -1%; color: #304156; font-size:32px">Exhibitors</h2>
        <h4 style="color: #979797; font-size:14" class="mt-2">Add and manage exhibitors in your event</h4>
    </div>
    <div class="col-md-5">
        {{-- <a href="{{route('organization.event.exhibitor.create',[$organizationID,$event->id])}}">
            <button class="btn-no-pd btn-add btn bg-primer mt-0 font-inter" >
                <i  style="color: #fff;" class="fa fa-plus"></i>
                Tambah
            </button>
        </a> --}}
        {{-- ------ Pembaruan. Saat tombol di klik konformasi apakah paket user sudah aktif ? dan belum mencapai batas ? --}}
        <a onclick="confirmMyPkg()">
            <button class="btn-no-pd btn-add btn bg-primer mt-0 font-inter" >
                <i  style="color: #fff;" class="fa fa-plus"></i>
                Tambah
            </button>
        </a>
        {{-- ----------------------------------------------------------------------------------------------------------- --}}
    </div>
</div>

@include('admin.partials.alert')

@forelse ($exhibitors as $exhibitor)
    <div class="bagi bagi-3" style="margin-top:2%; padding-left: 15px; padding-right: 15px;">
        <div class="bg-putih rounded-5 bayangan-5" style="width: 100%;">
            <div class="wrap">
                <img src="{{ asset('storage/event_assets/'.$event->slug.'/exhibitors/exhibitor_logo/'.$exhibitor->logo) }}" class="rounded-circle asp-rt-1-1 mt-4" width="58px" height="58px">
                <i class="fa fa-ellipsis-h pointer dropdownToggle mt-3 mr-1" data-id="{{ $exhibitor->id }}" style=" color: #C4C4C4; font-size:20pt; float: right;" aria-hidden="true"></i>
                
                   
                    <div id="Dropdown{{$exhibitor->id}}" class="dropdown-content rounded-5">
                        <a href="{{route('organization.event.exhibitor.edit', [$organizationID, $event->id, $exhibitor->id])}}"  class="no-pd">
                           <button class="teks-hijau pointe lebar-100 pl-4 rata-kiri no-border">
                                <i class="fas fa-edit"></i> Edit
                           </button>
                        </a>
                       <!--  <a
                        href="{{route('organization.event.exhibitor.delete' , ([$organizationID, $event->id,$exhibitor->id]))}}"
                        onclick="confirm('Yakin ingin menghapus session ini?')">
                        <span class="teks-merah pointer">
                            <i class="fas fa-trash"></i> Delete
                        </span>
                        </a> -->
                        <form action="{{route('organization.event.exhibitor.delete' , ([$organizationID, $event->id,$exhibitor->id]))}}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" onclick="deleteConfirm(event)" class="teks-merah pointer lebar-100 pl-4 rata-kiri no-border">
                                <i class="fas fa-trash"></i> Delete
                            </button>

                           <!--  <span type="submit" onclick="confirm('Yakin ingin menghapus session ini?')" class="teks-merah pointer" style="margin-left: 15px; margin-bottom: 20px;">
                                <i class="fas fa-trash"></i> Delete
                            </span> -->
                        </form>
                    </div>
                

                <div class="title-exhibitor pt-3 ml-1">
                    {{ $exhibitor->name }}
                </div>
                <div style="padding-left: 10px; margin-top:-1%; color: #C4C4C4; font-size:10pt;">

                </div>
                <div class="teks-primer font-info mt-2 ml-2">
                    @if ($exhibitor->virtual_booth == 1)
                        <i class="fa fa-check pr-2 teks-primer" aria-hidden="true"></i>Virtual Booth<span ></span>
                    @else
                        <i class="fas fa-times pr-2 teks-primer" aria-hidden="true"></i>Nothing Virtual Booth<span ></span>
                    @endif

                </div>
                <div class="teks-primer font-info mt-2 ml-2 pb-4">
                    @if ($exhibitor->overview == 1)
                        <i class="fa fa-check pr-2 teks-primer" aria-hidden="true"></i>Show In Overview Event<span ></span>
                    @else
                        <i class="fas fa-times pr-2 teks-primer" aria-hidden="true"></i>Not Show In Overview Event<span ></span>
                    @endif

                </div>
            </div>
        </div>
    </div>
@empty
    <img class="partial-image" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zOnN2Z2pzPSJodHRwOi8vc3ZnanMuY29tL3N2Z2pzIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiIgeD0iMCIgeT0iMCIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMiA1MTIiIHhtbDpzcGFjZT0icHJlc2VydmUiIGNsYXNzPSIiPjxnPjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0ibTQ5NiA0ODJoLTExM3YtMTYzaDMzYzguMjg0IDAgMTUtNi43MTYgMTUtMTVzLTYuNzE2LTE1LTE1LTE1aC05di0xNDZoOWM4LjI4NCAwIDE1LTYuNzE2IDE1LTE1di04M2MwLTI0LjgxMy0yMC4xODctNDUtNDUtNDVoLTI2MGMtMjQuODEzIDAtNDUgMjAuMTg3LTQ1IDQ1djgzYzAgOC4yODQgNi43MTYgMTUgMTUgMTVoOXYxNDZoLTljLTguMjg0IDAtMTUgNi43MTYtMTUgMTVzNi43MTYgMTUgMTUgMTVoMzN2MTYzaC0xMTNjLTguMjg0IDAtMTUgNi43MTYtMTUgMTVzNi43MTYgMTUgMTUgMTVoNDgwYzguMjg0IDAgMTUtNi43MTYgMTUtMTVzLTYuNzE2LTE1LTE1LTE1em0tMzg1LTQzN2MwLTguMjcxIDYuNzI5LTE1IDE1LTE1aDI2MGM4LjI3MSAwIDE1IDYuNzI5IDE1IDE1djY4Yy0xNC4xNzYgMC0yNzUuNzgyIDAtMjkwIDB6bTI0IDk4aDI0MnYxNDZjLTcuODY5IDAtMjkuNDk1IDAtMzkuNzE2IDAtNy42MjItMTkuODk4LTIyLjQzOC0zNi44MjgtNDIuMzA0LTQ2Ljc2NyAyMC44NDQtMzAuODcyLTEuMjItNzMuMjMzLTM4Ljk4LTczLjIzMy0zNy44MTggMC01OS43ODQgNDIuNDIxLTM4Ljk3OSA3My4yMzMtMTkuODM0IDkuOTIzLTM0LjY2OSAyNi44MzUtNDIuMzA0IDQ2Ljc2Ny0xMy40ODUgMC0yOC4wODIgMC0zOS43MTYgMHYtMTQ2em0xMDQgNzNjMC05LjM3NCA3LjYyNi0xNyAxNy0xN3MxNyA3LjYyNiAxNyAxNy03LjYyNiAxNy0xNyAxNy0xNy03LjYyNi0xNy0xN3ptMTcgNDdjMTkuNTUyIDAgMzcuNDQ3IDEwLjI0NCA0Ny43OCAyNmgtOTUuNTZjMTAuMzMzLTE1Ljc1NiAyOC4yMjgtMjYgNDcuNzgtMjZ6bS05NyAyMTl2LTE2M2gxOTR2MTYzeiIgZmlsbD0iI2ViNTk3YiIgZGF0YS1vcmlnaW5hbD0iIzAwMDAwMCIgc3R5bGU9IiIgY2xhc3M9IiI+PC9wYXRoPjxwYXRoIGQ9Im0yMDggODdoOTZjOC4yODQgMCAxNS02LjcxNiAxNS0xNXMtNi43MTYtMTUtMTUtMTVoLTk2Yy04LjI4NCAwLTE1IDYuNzE2LTE1IDE1czYuNzE2IDE1IDE1IDE1eiIgZmlsbD0iI2ViNTk3YiIgZGF0YS1vcmlnaW5hbD0iIzAwMDAwMCIgc3R5bGU9IiIgY2xhc3M9IiI+PC9wYXRoPjxwYXRoIGQ9Im0zMDQgMzUzaC05NmMtOC4yODQgMC0xNSA2LjcxNi0xNSAxNXY2NGMwIDguMjg0IDYuNzE2IDE1IDE1IDE1aDk2YzguMjg0IDAgMTUtNi43MTYgMTUtMTV2LTY0YzAtOC4yODQtNi43MTYtMTUtMTUtMTV6bS0xNSA2NGgtNjZ2LTM0aDY2eiIgZmlsbD0iI2ViNTk3YiIgZGF0YS1vcmlnaW5hbD0iIzAwMDAwMCIgc3R5bGU9IiIgY2xhc3M9IiI+PC9wYXRoPjwvZz48L2c+PC9zdmc+" />
    <div class="rata-tengah">
        <h3>Tidak Ada Exhibitor Untuk Saat ini</h3>
        <h4>Buat Sekarang!</h4>
    </div>
@endforelse


<!-- {{-- tambah --}}
<div class="bg"></div>
{{-- <div class="popupWrapper" id="addExhibitor">
    <div class="popup" style="width: 70%;">
        <div class="wrap">
            <h3>Tambah Exhibitor Baru
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#addExhibitor')"></i>
            </h3>
            <form action="{{ route('organization.event.exhibitor.store', [$organizationID, $event->id]) }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="bagi lebar-40">
                    <div class="wrap">

                        {{-- logo --}}
                        <div id="inputLogoArea">
                            <input type="file" class="box" name="logo" onchange="uploadLogo(this)" required oninvalid="this.setCustomValidity('Harap Upload Gambar')" oninput="setCustomValidity('')">
                            <div class="uploadArea">Upload Logo</div>
                        </div>
                        <div id="previewLogoArea" class="d-none">
                            <img id="logoPreview" class="partial-image" style="width: 200px;height: 200px; margin-top:30px;"><br />
                            <span class="pointer teks-merah ke-kanan" onclick="removeLogoPreview()">hapus</span>
                        </div>

                    </div>
                </div>
                <div class="bagi lebar-60">
                    <div class="wrap">
                        <div class="mt-2">Nama :</div>
                        <input type="text" class="box" name="name" required value="{{old('name')}}" oninvalid="this.setCustomValidity('Data Ini Tidak Boleh Kosong')" oninput="setCustomValidity('')">
                        <div class="mt-2">Website URL :</div>
                        <input type="url" class="box" name="website" required value="{{old('website')}}" oninvalid="this.setCustomValidity('Harap Masukkan Url Dengan Benar')" oninput="setCustomValidity('')"> --}}

                        {{-- photo booth --}}
                        <div id="inputBoothArea" >
                            <input type="file" class="box" name="booth_image" onchange="uploadFotoBooth(this)" required oninvalid="this.setCustomValidity('Harap Upload Gambar')" oninput="setCustomValidity('')">
                            <div class="uploadArea">Upload Foto Booth</div>
                        </div>
                        <div id="previewBoothArea" class="d-none">
                            <img id="boothPreview" style="width: 200px;height: 200px; margin-top:30px;"><br />
                            <span class="pointer teks-merah" onclick="removeBoothPreview()">hapus</span>
                        </div>

                        <div class="mt-2">Nomor Telepon/HP :</div>
                        <input type="number" class="box" name="phone" required maxlength="12" value="{{old('phone')}}" oninvalid="this.setCustomValidity('Data Ini Tidak Boleh Kosong')" oninput="setCustomValidity('')">


                        <div class="mt-2">Alamat :</div>
                        <input type="text" class="box" name="address" required value="{{old('address')}}" oninvalid="this.setCustomValidity('Data Ini Tidak Boleh Kosong')" oninput="setCustomValidity('')">
                        <div class="mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="overview" name="overview">
                                <label class="form-check-label" for="defaultCheck1">
                                  Tampil Di Overview Event Detail
                                </label>
                              </div>
                        </div>
                    </div>
                </div>

                <button class="primer lebar-100 mt-2">Submit</button>
            </form>
        </div>
    </div>
</div> --}}

{{-- edit --}}
{{-- <div class="popupWrapper" id="editExhibitor">
    <div class="popup" style="width: 70%;">
        <div class="wrap">
            <h3>Edit Exhibitor
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#editExhibitor')"></i>
            </h3>
            <form action="{{ route('organization.event.exhibitor.update', [$organizationID, $event->id]) }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="exhibitor_id" id="exhibitorID">
                <div class="bagi lebar-40">
                    <div class="wrap">
                        <div id="inputLogoArea" class="d-none">
                            <input type="file" class="box" name="logo" id="logo" onchange="uploadLogo(this, 1)">
                            <div class="uploadArea">Upload Logo</div>
                        </div>
                        <div id="previewLogoArea" >
                            <img id="logoPreview" style="width: 200px;height: 200px; margin-top:30px;"><br />
                            <span class="pointer teks-merah" onclick="removeLogoPreview(1)">hapus</span>
                        </div>
                    </div>
                </div>
                <div class="bagi lebar-60">
                    <div class="wrap">
                        <div class="mt-2">Nama :</div>
                        <input type="text" class="box" name="name" id="name" required oninvalid="this.setCustomValidity('Data Ini Tidak Boleh Kosong')" oninput="setCustomValidity('')">
                        <div class="mt-2">Website URL :</div>
                        <input type="url" class="box" name="website" id="website"  required oninvalid="this.setCustomValidity('Harap Masukkan URL Dengan Benar')" oninput="setCustomValidity('')">
                        <div id="inputBoothArea" class="d-none">
                            <input type="file" class="box" name="booth_image" id="booth_image" onchange="uploadFotoBooth(this, 1)">
                            <div class="uploadArea">Upload Foto Booth :</div>
                        </div>
                        <div id="previewBoothArea">
                            <img id="boothPreview" style="width: 200px;height: 200px; margin-top:30px;"><br />
                            <span class="pointer teks-merah" onclick="removeBoothPreview(1)">hapus</span>
                        </div>
                        <div class="mt-2">Nomor Telepon/HP :</div>
                        <input type="number" class="box" name="phone" id="phone"required maxlength="12"  oninvalid="this.setCustomValidity('Harap Masukkan URL Dengan Benar')" oninput="setCustomValidity('')">
                        <div class="mt-2">Alamat :</div>
                        <input type="text" class="box" name="address" id="address"required  oninvalid="this.setCustomValidity('Harap Masukkan URL Dengan Benar')" oninput="setCustomValidity('')">

                        <div class="mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="overview" name="overview">
                                <label class="form-check-label" for="defaultCheck1">
                                  Tampil Di Overview Event Detail
                                </label>
                              </div>
                        </div>
                    </div>
                </div>
                <button class="lebar-100 primer mt-2">Update</button>
            </form>
        </div>
    </div>
</div> --}} -->
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
    const uploadLogo = (input, isEdit = null) => {
        let file = input.files[0];
        let reader = new FileReader();
        reader.readAsDataURL(file);

        reader.addEventListener("load", function() {
            if (isEdit == null) {
                let preview = select("#logoPreview");
                select("#inputLogoArea").classList.add('d-none');
                select("#previewLogoArea").classList.remove('d-none');
                preview.setAttribute('src', reader.result);
            }else {
                let preview = select("#editExhibitor #logoPreview");
                select("#editExhibitor #inputLogoArea").classList.add('d-none');
                select("#editExhibitor #previewLogoArea").classList.remove('d-none');
                preview.setAttribute('src', reader.result);
            }
        });
    }
    const uploadFotoBooth = (input, isEdit = null) => {
        let file = input.files[0];
        let reader = new FileReader();
        reader.readAsDataURL(file);

        reader.addEventListener("load", function() {
            if (isEdit == null) {
                let preview = select("#boothPreview");
                select("#inputBoothArea").classList.add('d-none');
                select("#previewBoothArea").classList.remove('d-none');
                preview.setAttribute('src', reader.result);
            }else {
                let preview = select("#editExhibitor #boothPreview");
                select("#editExhibitor #inputBoothArea").classList.add('d-none');
                select("#editExhibitor #previewBoothArea").classList.remove('d-none');
                preview.setAttribute('src', reader.result);
            }
        });
    }

    const removeLogoPreview = (isEdit = null) => {
    // logo
        if (isEdit == null) {
            select("input[type='file']").value = "";
            select("#inputLogoArea").classList.remove('d-none');
            select("#previewLogoArea").classList.add('d-none');
        }else {
            select("#editExhibitor input[type='file']").value = "";
            select("#editExhibitor #inputLogoArea").classList.remove('d-none');
            select("#editExhibitor #previewLogoArea").classList.add('d-none');
        }
    }

    // booth
    const removeBoothPreview = (isEdit = null) => {
        if (isEdit == null) {
            select("input[type='file']").value = "";
            select("#inputBoothArea").classList.remove('d-none');
            select("#previewBoothArea").classList.add('d-none');
        }else {
            select("#editExhibitor input[type='file']").value = "";
            select("#editExhibitor #inputBoothArea").classList.remove('d-none');
            select("#editExhibitor #previewBoothArea").classList.add('d-none');
        }
    }

    const edit = (data, event, isEdit) => {
        data = JSON.parse(data);
        event = JSON.parse(event);
        munculPopup("#editExhibitor");

        if (isEdit == null) {
            select("input[type='file']").value = "";
            select("#inputBoothArea").classList.add('d-none');
            select("#previewBoothArea").classList.remove('d-none');
            select("#inputLogoArea").classList.add('d-none');
            select("#previewLogoArea").classList.remove('d-none');
        }else {
            select("#editExhibitor input[type='file']").value = "";
            select("#editExhibitor #inputBoothArea").classList.add('d-none');
            select("#editExhibitor #previewBoothArea").classList.remove('d-none');
            select("#editExhibitor #inputLogoArea").classList.add('d-none');
            select("#editExhibitor #previewLogoArea").classList.remove('d-none');
        }

        select("#editExhibitor #exhibitorID").value = data.id;
        select("#editExhibitor #name").value        = data.name;
        select("#editExhibitor #website").value     = data.website;
        select("#editExhibitor #phone").value       = data.phone;
        if(data.overview == 1){
            select("#editExhibitor #overview").checked = true;
        }else{
            select("#editExhibitor #overview").checked = false;
        }
        select("#editExhibitor #address").value     = data.address;
        select("#editExhibitor #logoPreview").setAttribute('src', `{{ asset('storage/event_assets/${event.slug}/exhibitors/exhibitor_logo/${data.logo}') }}`);
        select("#editExhibitor #boothPreview").setAttribute('src', `{{ asset('storage/event_assets/${event.slug}/exhibitors/exhibitor_booth_image/${data.booth_image}') }}`);
    }

    const buat =  (data, event = null) => {
        data = JSON.parse(data);
        event = JSON.parse(event);
        munculPopup("#addExhibitor");
    }

    function confirmMyPkg() {
        var pkgActive = '{{ $pkgActive }}';
        var pkgActive = parseInt(pkgActive);
        var thisEvent = <?php echo json_encode($event) ?>;
        var myPkg = <?php echo json_encode($organization->user->package) ?>;
                
        console.log(thisEvent, myPkg);
        var paramCombine = false;
        // paramCombine adalah parameter untuk cek apakah unlimited / sudah tercapai batasnya ?
        if(myPkg.exhibitor_count <= -1){
            paramCombine = false;
        }else{
            if(thisEvent.exhibitors.length >= myPkg.exhibitor_count){
                paramCombine = true;
            }
        }
        if(pkgActive == 0 || paramCombine == true){
            // Batalkan dengan konfirmasi sweet alert
            var msg = '';
            if(paramCombine == true){
                msg = 'Kamu sudah melewati batas paket untuk membuat exhibitor baru';
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
            // redirectkan ke route tambah exhibitor
            window.location.replace("{{route('organization.event.exhibitor.create',[$organizationID,$event->id])}}");
        }
    }

    // Delete confirm by form action (not by url)
    function deleteConfirm(evt) {
        evt.preventDefault(); // prevent form submit
        var form = evt.target.form; // storing the form

        Swal.fire({
        title: 'Apakah kamu yakin ?',
        text: "Kamu akan menghapus akun exhibitor ini.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })
    }
</script>
@endsection

@extends('layouts.user')

@section('title', "Edit Exhibitor")

@section('head.dependencies')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/exhibitorPage.css') }}">

@endsection

@section('content')
@include('admin.partials.alert')


<div class="container-cadangan">
    <div class="row">
        <div class="col-md-7">
            <h2 style="color: #304156;">Edit Exhibitor</h2>
        </div>
        <div class="col-md-5">
            <a href="{{ route('organization.event.exhibitors', [$organizationID, $eventID]) }}" id="back-btn" class="btn btn-primer ke-kanan rounded-5"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
   <form action="{{route('organization.event.exhibitor.update',[$organizationID,$eventID, $exhibitor->id])}}" method="POST" enctype="multipart/form-data">
        <div class="row">
        @csrf
            <div class="col-md-8">
                <div class="row">
                    <div class="col-lg-4">

                        {{-- logo --}}
                        <div class="wrap">
                            <input type="hidden" name="execution_type" value="{{ $event->execution_type }}">
                            <div id="inputLogoArea" class="bagi lebar-100 mt-1 d-none">
                                <input type="file" accept="image/jpg, image/png, image/jpeg" id="input-logo" class="box no-bg" name="logo" onchange="uploadLogo(this, {{ json_encode($organization->user->package->max_attachment) }})" oninvalid="this.setCustomValidity('Harap Upload Gambar')" oninput="setCustomValidity('')">
                                <label for="input-logo" class="lebar-100">
                                    <div class="uploadArea font-inter-header rounded-5" style="color:#E6286E;">
                                        <div class="img-cover-up">
                                            <img class="img-cover" src="{{asset('images/photo.png')}}">
                                        </div>
                                        Upload Company Logo
                                        <br>
                                        <span style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 12px; color: #E6286E;">
                                            200x200px PNG, JPG max {{ $organization->user->package->max_attachment }} Mb
                                        </span>
                                    </div>
                                </label>
                            </div>
                            <div id="previewLogoArea" class="bagi lebar-100">
                                <img id="logoPreview" src="{{asset('storage/event_assets/'.$slug->slug.'/exhibitors/exhibitor_logo/'.$exhibitor->logo)}}" class="rounded-5 asp-rt-1-1" width="100%">
                                <br />
                                <span class="btn btn-no-pd lebar-100 mt-3 pointer bg-merah" onclick="removeLogoPreview(1)">Hapus</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="mt-2" style="padding-top: 3%; color: #304156;">Company Name :</div>
                        <input type="text"  class="box no-bg" name="name" value="{{$exhibitor->name}}" placeholder="Your Company Name">

                        <div class="mt-2" style="padding-top: 3%; color: #304156;">Email Address :</div>
                        <input type="text"  class="box no-bg" name="email" value="{{$exhibitor->email}}" placeholder="youremail@company.com">
                    </div>
                </div>

                <div class="mt-2 font-inter-header" style="padding-top: 3%; color: #304156;">Kategori Booth:</div>
                    <select name="category" class="box no-bg">
                        @if ($exhibitor->category)
                            <option value="{{$exhibitor->category}}">{{$exhibitor->category}}</option>
                        @else
                            <option value="{{old('category')}}">{{old('category') == '' ? '-- PILIH KATEGORI --' : old('category')}}</option>
                        @endif
                        @foreach ($categories as $category)
                            <option>{{ $category->name }}</option>
                        @endforeach
                    </select>

                <div class="mt-2 font-inter-header" style="padding-top: 3%; color: #304156;">Address :</div>
                <input type="text"  class="box no-bg" name="address" value="{{$exhibitor->address}}" placeholder="Alamat Perusahaan Anda">

                <div class="mt-2 font-inter-header" style="padding-top: 3%; color: #304156;">Telp :</div>
                <input type="text" maxlength="13" class="box no-bg" name="phone" value="{{$exhibitor->phone}}" placeholder="No. Telp Perusahaan Anda">

                <h2 class="font-inter-header" style="padding-top: 5%; color: #304156;">Media sosial</h2>

                <div class="form-group font-inter-header" style="padding-top: 3%;">
                    <label for="instagram">Instagram :</label>
                    <div class="input-group mt-1">
                        <span class="input-group-text" id="instagram"><i class="fa fa-instagram" aria-hidden="true"></i></span>
                        <input type="text" name="instagram" id="instagram" class="form-control" placeholder="https://www.instagram.com/yourprofile" aria-label="https://www.instagram.com/yourprofile" aria-describedby="https://www.instagram.com/yourprofile" value="{{$exhibitor->instagram}}">
                    </div>
                </div>

                <div class="form-group font-inter-header">
                    <label for="linkedin" style="color: #304156;">Linkedin :</label>
                    <div class="input-group mt-1">
                        <span class="input-group-text" id="linkedin"><i class="fa fa-linkedin" aria-hidden="true"></i></span>
                        <input type="text" name="linkedin" id="linkedin"class="form-control" placeholder="https://www.Linkedin.com/yourprofile" aria-label="https://www.Linkedin.com/yourprofile" aria-describedby="https://www.Linkedin.com/yourprofile" aria-label="https://www.Linkedin.com/yourprofil" value="{{$exhibitor->linkedin}}">
                    </div>
                </div>

                <div class="form-group font-inter-header">
                    <label for="twitter" style="color: #304156;">Twitter :</label>
                    <div class="input-group mt-1">
                        <span class="input-group-text" id="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></span>
                        <input type="text" name="twitter" id="twitter"class="form-control" placeholder="https://www.twitter.com/yourprofile" aria-label="https://www.twitter.com/yourprofile" aria-describedby="https://www.twitter.com/yourprofile" aria-label="https://www.twitter.com/yourprofil" value="{{$exhibitor->twitter}}">
                    </div>
                </div>

                <div class="form-group font-inter-header">
                    <label for="website" style="color: #304156;">Website :</label>
                    <div class="input-group mt-1">
                        <span class="input-group-text" id="website"><i class="fa fa-paper-plane" aria-hidden="true"></i></span>
                        <input type="text" name="website" id="website" class="form-control" placeholder="https://yourwebsite.com" aria-label="https://yourwebsite.com" aria-describedby="https://yourwebsite.com" aria-label="https://yourwebsite.com" value="{{$exhibitor->website}}">
                    </div>
                </div>
       
                    <div class="font-inter virtual-booth" style="color: #304156; margin-top:25%;">
                        <h2 class="font-inter-header" style="color: #304156;">Virtual Booth</h2>
                        <?php 

                            $v_booth = $exhibitor->virtual_booth;

                        ?>
                        <label class="label-checkbox font-inter" style="margin-top: 20px;">Enable Virtual Booth
                            <input type="checkbox" name="virtual_booth"  id="enableVirtual" style=" width: 23px;height:23px;margin-top:3%;" value="1" {{ $v_booth == 1 ? 'checked' : '' }}>
                    
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
                                    <input type="text" id="copy_inputan" class="box no-bg" name="booth_link" value="{{$exhibitor->booth_link}}" placeholder="Your Booth Link, Zoom only">
                                </div>
                                <div class="col-3">
                                    <button type="button" id="copy" onclick="copy_text()" class="box no-bg bg-primer lebar-100">Copy</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-3 font-inter-header">
                            
                        </div>
                    </div>

                    <div class="mt-2 font-inter-header" style="padding-top: 3%; color: #304156;">Deskripsi :</div>
                    <textarea rows="10" class="box no-bg" name="description" placeholder="Deskripsi singkat tentang perusahaan">{{$exhibitor->description}}</textarea>

                    <div class="mt-2" style="padding-top: 3%; color: #304156;"><b>Background Image :</b></div>
                    <div class="mt-2" style="color: #979797;">Audience will see this image while enter exhibition page</div>

                    {{-- background image --}}
                    <div id="inputBoothArea" class="bagi lebar-100 mt-1 d-none">
                        <input type="file" accept="image/jpg, image/png, image/jpeg" id="input-bg-booth" class="box no-bg" name="booth_image" onchange="uploadFotoBooth(this,{{ json_encode($organization->user->package->max_attachment) }})" oninvalid="this.setCustomValidity('Harap Upload Gambar')" oninput="setCustomValidity('')">
                        <label for="input-bg-booth" class="lebar-100">
                            <div class="uploadAreaBg rounded-5 asp-rt-8-3" style="color:#E6286E;">
                                <div>
                                    <img src="{{asset('images/photo.png')}}">
                                </div>
                                Upload Background Image
                                <br>
                                <span style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 12px; color: #E6286E;">
                                    1920x720 PNG, JPG max size {{ $organization->user->package->max_attachment }} Mb
                                </span>
                            </div>
                        </label>
                    </div>
                    <div id="previewBoothArea" class="bagi lebar-100">
                        <img id="boothPreview" src="{{asset('storage/event_assets/'.$slug->slug.'/exhibitors/exhibitor_booth_image/'.$exhibitor->booth_image)}}" class="rounded-5 asp-rt-8-3" width="100%"><br />
                        <span class="btn btn-no-pd lebar-100 mt-3 pointer bg-merah" onclick="removeBoothPreview(1)">hapus</span>
                    </div>

                    <div class="mt-2" style="padding-top: 3%; color: #304156;"><b>Welcome Video :</b></div>
                    <div class="mt-2" style="color: #979797;">Audience will see this video while enter exhibition page</div>

                    <br>
                    <!-- <input type="text"  class="box no-bg" name="video" value="{{$exhibitor->video}}" placeholder="Masukkan Link Video"> -->

                    <div id="inputVideoArea" class="bagi lebar-100 mt-1 d-none">
                        <input type="url" id="input-file-video" class="box no-bg no-bg" name="video_0" oninput="uploadVideo(this)" oninvalid="this.setCustomValidity('Harap Upload Video')" oninput="setCustomValidity('')" placeholder="URL video YouTube ex(https://www.youtube.com/watch?v=8RpCLKoa3_A atau https://www.youtube.com/embed/8RpCLKoa3_A)">

                        <input type="hidden" id="video_link_insert_1" name="video_1" value="{{$exhibitor->video}}">

                        <input type="hidden" id="video_link_insert" name="video" value="{{$exhibitor->video}}">
                        <!-- <label for="input-file-video" class="lebar-100">
                            <div class="uploadAreaBg rounded-5 asp-rt-8-3" style="color:#E6286E;">
                                <div>
                                    <i class="fa bi bi-cloud-arrow-up font-img teks-primer"></i>
                                </div>
                                Upload Welcome Video
                                <br>
                                <span style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 12px; color: #E6286E;">
                                    1920x720 MP4
                                </span>
                            </div>
                        </label> -->
                    </div>
                    <div id="previewVideoArea" class="bagi lebar-100 mt-4">
                        <iframe id="videoPreview" class="rounded-5 asp-rt-8-3" src="{{$exhibitor->video}}" width="100%" controls></iframe>
                        <br />
                        <span class="btn btn-no-pd lebar-100 mt-3 pointer bg-merah" onclick="removeVideoPreview(1)">hapus</span>
                    </div>
                

                <br>
                <div class="">
                    <?php 
                        $ovr_view = $exhibitor->overview;
                     ?>
                    <label class="label-checkbox font-inter" style="margin-top: 20px;font-weight: normal !important;">  Tampil Di Overview Event Detail
                        <input class="form-check-input" type="checkbox" id="overview" name="overview" value="1" {{ $ovr_view == 1 ? 'checked' : '' }}>
                            
                        <span class="checkmark font-inter"></span>
                    </label>
                </div>

                <div class="text-center">
                    <button id="btn-save-1" type="submit" class="bg-primer mt-3" style="border-radius: 6px;">Simpan</button>
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-3 font-inter-header">
                <button id="btn-save-2" type="submit" class="bg-primer mt-3 lebar-100 btn btn-no-pd btn-add">Simpan</button>
            </div>
        </div>

    </form>
</div>
@endsection

@section('javascript')
<script>
    
    const uploadLogo = (input, maxSize,isEdit = null) => {
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
                removeLogoPreview();
            });
        }else{
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
    }
    const uploadFotoBooth = (input,maxSize,isEdit = null) => {
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
                removeBoothPreview();
            });
        }else{
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
    }

    const uploadVideo = (input, isEdit = null) => {
        var urlInput = input.value.split('watch?v=');
        
        var idVideo = "";
        
        if(urlInput.length == 1){
            //Berrati memakai youtu.be atau tidak sesuai sama sekali
            urlInput = urlInput[0].split('youtu.be/');
            if(urlInput.length > 1){
                idVideo = urlInput[1];
            }else{
                urlInput = urlInput[0].split('/embed/');
                // console.log(urlInput);
                if(urlInput.length > 1){
                    idVideo = urlInput[1];
                }
            }
        }else{
            idVideo = urlInput[1];
        }

        if(idVideo == ""){
            var oldUrl = document.getElementById('video_link_insert_1').value;
            document.getElementById('video_link_insert').value = oldUrl;
        }else{
            document.getElementById('video_link_insert').value = 'https://www.youtube.com/embed/'+idVideo;
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
                document.querySelector("iframe").src = 'https://www.youtube.com/embed/'+idVideo;
            }else {
                let preview = select("#videoPreview");
                // select("#editExhibitor #inputVideoArea").classList.add('d-none');
                select("#previewVideoArea").classList.remove('d-none');
                // preview.setAttribute('src', reader);
                document.querySelector("iframe").src = 'https://www.youtube.com/embed/'+idVideo;
            }
    }

    // logo
    const removeLogoPreview = (isEdit = null) => {
        if (isEdit == null) {
            select("#inputLogoArea input[type='file']").value = "";
            select("#inputLogoArea").classList.remove('d-none');
            select("#previewLogoArea").classList.add('d-none');
        }else {
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
        }else {
            // select("input[type='file']").value = "";
            select("#inputBoothArea").classList.remove('d-none');
            select("#previewBoothArea").classList.add('d-none');
        }
    }
    const removeVideoPreview = (isEdit = null) => {
        if (isEdit == null) {
            // select("input[type='file']").value = "";
            select("#inputVideoArea").classList.remove('d-none');
            select("#previewVideoArea").classList.add('d-none');
        }else {
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
        // alert("Text berhasil dicopy");
        Swal.fire({
                title: 'Berhasil !!!',
                text: 'Link telah dicopy',
                icon: 'success',
                confirmButtonText: 'Ok'
            });
    }
</script>
@endsection

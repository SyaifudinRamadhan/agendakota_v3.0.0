@extends('layouts.user')

@section('title', "Edit Speaker")

@section('head.dependencies')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/speakersPage.css') }}">

@endsection

@section('content')
@include('admin.partials.alert')
<div class="container-cadangan">
    <div class="row">
        <div class="col-md-7">
            <h2 style="color: #304156;">
                @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
                Edit Speaker
                @else
                Edit Peformers
                @endif
            </h2>
        </div>
        <div class="col-md-5">
            <a href="{{ route('organization.event.speakers', [$organizationID, $eventID]) }}" id="back-btn" class="btn btn-primer ke-kanan rounded-5"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <form action="{{route('organization.event.speaker.update',[$organizationID,$eventID,$speaker->id])}}" method="POST" enctype="multipart/form-data">
        <div class="row">
        @csrf
            <div class="col-md-8">
                <div class="row">
                    <div class="col-lg-4 mt-3">

                        {{-- logo --}}
                        <div class="wrap">
                            <div id="inputLogoArea" class="d-none bagi lebar-100 mt-1">
                                <input type="file" accept="image/jpg, image/png, image/jpeg" id="input-file" class="box no-bg" name="photo" onchange="uploadLogo(this, {{ json_encode($organization->user->package->max_attachment) }})" oninvalid="this.setCustomValidity('Harap Upload Gambar')" oninput="setCustomValidity('')">
                                <label for="input-file" class="lebar-100">
                                    <div class="uploadArea font-inter rounded-5" style="color:#E6286E;">
                                        <div class="img-cover-up">
                                            <img class="rounded-5 img-cover" src="{{asset('images/photo.png')}}">
                                        </div>
                                        @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
                                        Upload Speaker Photo
                                        @else
                                        Upload Guest Photo
                                        @endif
                                        <br>
                                        <span style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 12px; color: #E6286E; padding: 5px;">
                                            200x200px PNG, JPG Max {{ $organization->user->package->max_attachment }} Mb
                                        </span>
                                    </div>
                                </label>
                            </div>
                            <div id="previewLogoArea" class="bagi lebar-100">
                                <img id="logoPreview" src="{{asset('storage/event_assets/'.$slug->slug.'/speaker_photos/'.$speaker->photo)}}" width="100%" class="rounded-5 asp-rt-1-1"><br />
                                <span class="btn pointer bg-merah btn-no-pd lebar-100 mt-3" onclick="removeLogoPreview()">Hapus</span>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-lg-8 mt-3">
                        <div class="mt-2" style="padding-top: 3%; color: #304156;">
                            @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
                            Speaker Name :
                            @else
                            Guest Name :
                            @endif
                        </div>
                        <input type="text"  class="box no-bg" name="name" value="{{$speaker->name}}" placeholder="Your Company Name">

                        <div class="mt-2 font-inter" style="padding-top: 3%; color: #304156;">Email Address :</div>
                        <input type="text"  class="box no-bg" name="email" value="{{$speaker->email}}" placeholder="youremail@company.com">
                    </div>
                </div>

                <div class="mt-2 font-inter " style="padding-top: 3%; color: #304156;">Company/Organization :</div>
                <input type="text"  class="box no-bg" name="company" value="{{$speaker->company}}" placeholder="Perusahaan atau Organisasi">

                <div class="mt-2 font-inter" style="padding-top: 3%; color: #304156;">Designation :</div>
                <input type="text"  class="box no-bg" name="job" value="{{$speaker->job}}" placeholder="Pekerjaan">

                <h2 style="padding-top: 5%; color: #304156;">Media sosial</h2>

                <div class="form-group font-inter" style="padding-top: 3%;">
                    <label for="instagram">Instagram :</label>
                    <div class="input-group mt-1">
                        <span class="input-group-text" id="instagram"><i class="fa fa-instagram" aria-hidden="true"></i></span>
                        <input type="text" name="instagram" id="instagram" class="form-control" placeholder="https://www.instagram.com/yourprofile" aria-label="https://www.instagram.com/yourprofile" aria-describedby="https://www.instagram.com/yourprofile" value="{{$speaker->instagram}}">
                    </div>
                </div>

                <div class="form-group font-inter">
                    <label for="linkedin" style="color: #304156;">Linkedin :</label>
                    <div class="input-group mt-1">
                        <span class="input-group-text" id="linkedin"><i class="fa fa-linkedin" aria-hidden="true"></i></span>
                        <input type="text" name="linkedin" id="linkedin"class="form-control" placeholder="https://www.Linkedin.com/yourprofile" aria-label="https://www.Linkedin.com/yourprofile" aria-describedby="https://www.Linkedin.com/yourprofile" aria-label="https://www.Linkedin.com/yourprofil" value="{{$speaker->linkedin}}">
                    </div>
                </div>

                <div class="form-group font-inter">
                    <label for="twitter" style="color: #304156;">Twitter :</label>
                    <div class="input-group mt-1">
                        <span class="input-group-text" id="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></span>
                        <input type="text" name="twitter" id="twitter"class="form-control" placeholder="https://www.twitter.com/yourprofile" aria-label="https://www.twitter.com/yourprofile" aria-describedby="https://www.twitter.com/yourprofile" aria-label="https://www.twitter.com/yourprofil" value="{{$speaker->twitter}}">
                    </div>
                </div>

                <div class="form-group font-inter">
                    <label for="website" style="color: #304156;">Website :</label>
                    <div class="input-group mt-1">
                        <span class="input-group-text" id="website"><i class="fa fa-paper-plane" aria-hidden="true"></i></span>
                        <input type="text" name="website" id="website" class="form-control" placeholder="https://yourwebsite.com" aria-label="https://yourwebsite.com" aria-describedby="https://yourwebsite.com" aria-label="https://yourwebsite.com" value="{{$speaker->website}}">
                    </div>
                </div>
                <div class="mt-2">
                    <div class="form-check d-none">
                        <input class="form-check-input" type="checkbox" value="1" id="defaultCheck1" name="overview"
                        @if ($speaker->overview == 1)
                                checked
                        @endif>
                        <label class="form-check-label" for="defaultCheck1">
                          Tampil Di Overview Event Detail
                        </label>
                      </div>
                </div>
                <div class="text-center">
                    <button id="btn-save-1" type="submit" class="bg-primer mt-3" style="border-radius: 6px;">Simpan</button>
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-3">
                <button id="btn-save-2" type="submit" class="bg-primer mt-3 lebar-100 btn-no-pd btn-add">Simpan</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('javascript')
<script>
    const uploadLogo = (input,maxSize,isEdit = null) => {
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


    // logo
    const removeLogoPreview = (isEdit = null) => {
        select("input[type='file']").value = "";
        select("#inputLogoArea").classList.remove('d-none');
        select("#previewLogoArea").classList.add('d-none');
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

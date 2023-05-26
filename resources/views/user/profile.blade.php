@extends('layouts.user')

@section('title', "Profile")

@section('head.dependencies')

<link rel="stylesheet" type="text/css" href="{{asset('css/user/profilePage.css')}}">
@endsection

@section('content')
@include('admin.partials.alert')
    <div class="bagi bagi-2 main-width">
        @foreach ($users as $user)
        <form action="{{route('user.updateProfile',$user->id)}}" class="lebar-100" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div>
                <h2>Profil</h2>
                <p class="teks-transparan mb-4-0">Lengkapi profil agar lebih dikenali saat Event</p>
            </div>
            <div class="">
                <!-- Upload area -->
                <div id="inputPhotoAreaProfile" class="bagi mt-1 d-none ext-box-img-up">
                    <img class="rounded-circle img-up" for="uploadPhoto" src="{{ asset('images/profile-user.png') }}" style="width: 80px;height: 80px; margin-top:30px;">
                    <input type="file" accept="image/png, image/jpeg" class="box no-bg field-up-img" name="photo" id="uploadPhoto" onchange="uploadPhotoProfile(this, {{ json_encode($myData->package->max_attachment) }},1)">
                    <div class="uploadArea" style="color:#E6286E;">
                        <p class="p-up-img">Upload Photo <i class="fa bi bi-camera icon-up-img text-secondary"></i></p>
                    </div>
                    <div class="uploadArea trash-btn bg-secondary" style="color:#E6286E;">
                        <p class="p-up-img"><i class="fa bi bi-trash3 text-secondary"></i></p>
                    </div>
                    <p class="desc-photo-up" for="uploadPhoto">Upload Photo (Maks {{ $myData->package->max_attachment }}Mb) dan persegi</p>
                    <!-- <div class="uploadArea" style="color:#E6286E;">
                        <i class="fa fa-image" style="font-size: 36px; color: #e5214f; /*#EB597B*/"></i><br>
                        <span style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 14px; color: #e5214f; /*#EB597B*/">
                            Upload Photo (Maks 2Mb)  
                        </span><br>

                    </div> -->
                    
                </div>
                <div id="previewPhotoAreaProfile" class="bagi mt-1 ext-box-img-up">
                    @if ($user->photo == "default")
                        <img id="photoPreviewProfile" class="rounded-circle img-up" for="uploadPhoto" src="{{ asset('images/profile-user.png') }}" style="width: 80px;height: 80px; margin-top:30px;">
                    @else
                        <img id="photoPreviewProfile" class="rounded-circle img-up" for="uploadPhoto" src="{{ asset('storage/profile_photos/'.$user->photo) }}" style="width: 80px;height: 80px; margin-top:30px;">
                    @endif
                    <input type="file" disabled class="box no-bg field-up-img" name="photo" id="uploadPhoto">
                    <div class="uploadArea bg-secondary" style="color:#E6286E;">
                        <p class="p-up-img">Upload Photo <i class="fa bi bi-camera icon-up-img text-secondary"></i></p>
                    </div>
                    <div class="uploadArea trash-btn" onclick="removePreview()" style="color:#E6286E;">
                        <p class="p-up-img"><i class="fa bi bi-trash3 text-secondary"></i></p>
                    </div>
                    <p class="desc-photo-up">Upload Photo (Maks {{ $myData->package->max_attachment }}Mb) dan persegi</p>
                    <!-- <span class="pointer bg-primer col-md-4 mt-2" style="border: none; padding: 5px 8px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; border-radius: 4px;" onclick="removePreview()"><i class="fa fa-trash"></i> Hapus</span> -->
                </div>
                <!-- ------------ -->
            </div>
            <div class="mt-2-0">Full Name :</div>
            <input type="text" class="box no-bg" name="name" placeholder="Nama lengkap anda" value="{{$user->name}}" required>

            <div class="mt-2">Headline :</div>
            <input type="text" class="box no-bg" name="headline" placeholder="Deskripsi singkat tentang anda" value="{{$user->headline}}" required>

            <div class="mt-2">Bio :</div>
            <textarea rows="10" class="box no-bg" name="bio" required>{{$user->bio}}</textarea>

            <label for="telp">No. Telp :</label>
            <div class="input-group mt-1">
                <span class="input-group-text medsos no-bg" id="telp">+62</span>
                <input type="text" name="phone" id="phone" class="form-control" value="{{$user->phone}}" maxlength="13" minlength="11" required>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="wrap ml-0">
                    <div class="mt-2">Email :</div>
                    <input type="text" class="box no-bg" name="email" placeholder="Email anda" value="{{$user->email}}" readonly>
                </div>
            </div>
            <div class="col-md-6 no-pd-l">
                <div class="wrap">
                    <div class="mt-2">Password :</div>
                    <button class="button btn-outline-primer teks-primer" type="button" class="mt-1"  onclick="munculPopup('#editPassword')">Ubah Password</button>
                </div>
            </div>
        </div>


        <h2 class="pt-5-0">Media sosial</h2>
        <div class="form-group pt-3-0">
            <label for="instagram">Instagram :</label>
            <div class="input-group mt-1">
                <span class="input-group-text medsos" id="instagram"><i class="fa fa-instagram" aria-hidden="true"></i></span>
                <input type="url" name="instagram" id="instagram" class="form-control" placeholder="https://www.instagram.com/yourprofile" aria-label="https://www.instagram.com/yourprofile" aria-describedby="https://www.instagram.com/yourprofile" value="{{ $user->instagram_profile }}">
            </div>
        </div>

        <div class="form-group">
            <label for="InputLinkedin">LinkedIn :</label>
            <div class="input-group mt-1">
                <span class="input-group-text medsos" for="InputLinkedin"><i class="fa fa-linkedin" aria-hidden="true"></i></span>
                <input type="url" name="linkedin" id="linkedin" class="form-control" placeholder="https://linkedin/yourprofile" aria-label="https://linkedin/yourprofile" aria-describedby="https://linkedin/yourprofile" value="{{$user->linkedin_profile}}">
            </div>
        </div>

        <div class="form-group">
            <label for="InputTwitter">Twitter :</label>
            <div class="input-group mt-1">
                <span class="input-group-text medsos" id="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></span>
                <input type="url" name="twitter" id="InputTwitter"class="form-control" placeholder="https://www.twitter.com/yourprofile" aria-label="https://www.twitter.com/yourprofile" aria-describedby="https://www.twitter.com/yourprofile" aria-label="https://www.twitter.com/yourprofil" value="{{$user->twitter_profile}}">
            </div>
        </div>

        <button type="submit" class="bg-primer mt-3 mb-3 submit-btn btn-submit-2 ml-0" ><i class="far fa-save mr-1"></i> Simpan</button>
      
    </div>

    <div id="btn-save-2" class="bagi bagi-2 float-right mr-5 wd-20">
        <button type="submit" class="bg-primer mt-3 submit-btn btn-submit-2" ><i class="far fa-save mr-1"></i> Simpan</button>
    </div>
    </form>

    <div class="bg"></div>
    <div class="popupWrapper" id="editPassword">
        <div class="popup lebar-70 rounded-5">
            <div class="wrap">
                <h3 class="align-2">Edit Password
                    <i class="fas bi bi-x-circle-fill op-5 ke-kanan pointer" onclick="hilangPopup('#editPassword')"></i>
                </h3>
                <form action="{{route('user.updateProfilePassword',$user->id)}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="wrap">
                        <div>Password Lama :</div>
                        <input type="password" class="box no-bg" name="password_lama" placeholder="Masukkan Password Lama Anda">
                    </div>
                    <div class="wrap">
                        <div>Password Baru :</div>
                        <input type="password" class="box no-bg" name="password_baru" placeholder="Masukkan Password Baru Anda">
                    </div>
                    <div class="wrap">
                        <div>Konfirmasi Password Baru :</div>
                        <input type="password" class="box no-bg" name="repassword_baru" placeholder="Masukkan Kembali Password Baru Anda">
                    </div>

                    <div class="wrap text-center">
                        <button type="submit" class="bg-primer submit-btn-left">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endsection

@section('javascript')
<script type="text/javascript" src="{{asset('js/user/profilePage.js')}}"></script>
@endsection

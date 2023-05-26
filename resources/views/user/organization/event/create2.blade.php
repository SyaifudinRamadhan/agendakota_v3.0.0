@extends('layouts.user')

@section('title', "Buat Event Baru")

@section('head.dependencies')

<link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('css/user/organization/eventDashboard/createPage.css')}}">
@endsection

@section('content')
    <h2>Buat Event Baru</h2>
    <p class="mb-4 teks-transparan">untuk {{ $organization->name }}</p>
    @include('admin.partials.alert')

   
    <form action="{{ route('organization.event.store', $organizationID) }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="contentbar">
            <div>
                <div class="mt-2"></div>
                <div class="bagi lebar-100 mt-1" id="inputLogoArea">
                    <input type="hidden" name="execution_type" value="offline">
                    <input type="file" name="logo" class="box" onchange="chooseFile(this)">
                    <div class="uploadArea" style="color: #E6286E;">
                        <i class="fa fa-image" style="font-size: 36px; color: #e5214f; /*#EB597B*/"></i><br>
                        <span style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 14px; color: #e5214f; /*#EB597B*/">
                            Upload Event Banner
                        </span><br>
                        <span style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 12px; color: #E6286E;">
                            Recommended 1500x600px, With Aspect Ratio(5:2), PNG, JPG
                        </span>
                    </div>
                </div>
                <div class="bagi lebar-100 rata-tengah d-none" id="previewLogoArea">
                    <img class="img-preview" id="logoPreview" /><br><br>
                    <span class="pointer bg-merah lebar-100 btn-del-img-preview" onclick="removePreview()">hapus</span>
                </div>
            </div>
            <div>
                <div class="mt-2" style="padding-top: 3%;">Judul Event :</div>
                <input type="text" class="box" name="name" value="{{old('name')}}" maxlength="20" required>
            </div>
            

                <div id="input-time" class="row mt-2" style="padding-top: 3%;">
                    <div class="col-xl-5">
                        <div class="row">
                            <div class="col-7">
                                <div class="row">
                                    <div class="col-12 text-note">
                                        Tanggal Mulai :
                                    </div>
                                    <div class="col-12 align-bottom">
                                        <input type="text" class="box mt-0 no-bg lebar-100" name="start_date" id="startDate" value="{{old('start_date')}}" onchange="chooseStartDate(this.value)" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-12 no-pd-l text-note">Jam Mulai :</div>
                                    <div class="col-12 no-pd-l align-bottom">
                                        <input type="text" class="box mt-0 no-bg lebar-100" name="start_time" id="startTime" value="{{old('start_time')}}"  onchange="chooseStartTime(this.value)" required readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <div class="row">
                            <div class="col-12 text-note"></div>
                            <div class="col-12">
                                <div id="arc-div" class="mt-2 text-center" style="font-size: 20pt;"> &gt; </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="row">
                            <div class="col-7 align-bottom">
                                <div class="row">
                                    <div class="col-12 text-note">Tanggal Berakhir :</div>
                                    <div class="col-12 align-bottom">
                                        <input type="text"class="box mt-0 no-bg lebar-100" name="end_date" id="endDate" value="{{old('end_date')}}"  required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-12 text-note no-pd-l">Jam Berakhir :</div>
                                    <div class="col-12 no-pd-l align-bottom">
                                        <input type="text"class="box mt-0 no-bg lebar-100" name="end_time" id="endTime" value="{{old('end_time')}}" required readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

            <div>
                <div class="mt-2" style="padding-top: 3%;">Deskripsi :</div>
                <textarea name="description" id="description" class="box">{{old('description')}}</textarea>
            </div>
            <div>
                <div class="mt-2" style="padding-top: 3%;">Kategori Event :</div>
                <select name="category" class="box">
                    <option value="{{old('category')}}">{{ (old('category') == '') ? '-- Pilih Kategori --' : old('category') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row mt-5">
                <div class="col-md"> Tipe Event :
                    <select name="type" class="box">
                        <option value="{{old('type')}}">{{ (old('type') == '') ? '-- Pilih Tipe --' : old('type') }}</option>
                        @foreach ($types as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md"> Event Punchline :
                    <input type="text"  class="box" name="punchline" placeholder="Vivat!" value="{{old('punchline')}}">
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md"> Provinsi :
                    <select name="province" class="box" onchange="chooseProvince(this)">
                        <option value="{{old('province')}}">{{ (old('province') == '') ? '-- Pilih Provinsi --' : old('province') }}</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province['province'] }}" data="{{ $province['province_id'] }}">{{ $province['province'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md"> Kabupaten / Kota :
                    <select name="city" class="box" id="city_select">
                        <option value="{{old('city')}}">{{ (old('city') == '') ? '-- Pilih Kota --' : old('city') }}</option>
                    </select>
                </div>
            </div>
            <div>
                <div class="mt-2" style="padding-top: 3%;">Lokasi :</div>
                <textarea name="location" id="location" class="box">{{old('location')}}</textarea>
            </div>

            <div>
                <h2 style="padding-top: 5%;">Media sosial</h2>
                <div class="form-group" style="padding-top: 3%;">
                    <label for="instagram">Instagram :</label>
                    <div class="input-group mt-1">
                        <span class="input-group-text medsos" id="instagram"><i class="fa fa-instagram" aria-hidden="true"></i></span>
                        <input type="text" name="instagram" id="instagram" class="form-control" placeholder="https://www.instagram.com/yourprofile" aria-label="https://www.instagram.com/yourprofile" aria-describedby="https://www.instagram.com/yourprofile" value="{{old('instagram')}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="twitter">Twitter :</label>
                    <div class="input-group mt-1">
                        <span class="input-group-text medsos" id="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></span>
                        <input type="text" name="twitter" id="twitter"class="form-control" placeholder="https://www.twitter.com/yourprofile" aria-label="https://www.twitter.com/yourprofile" aria-describedby="https://www.twitter.com/yourprofile" aria-label="https://www.twitter.com/yourprofil" value="{{old('twitter')}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="website">Website :</label>
                    <div class="input-group mt-1">
                        <span class="input-group-text medsos" id="website"><i class="fa fa-paper-plane" aria-hidden="true"></i></span>
                        <input type="text" name="website" id="website" class="form-control" placeholder="https://yourwebsite.com" aria-label="https://yourwebsite.com" aria-describedby="https://yourwebsite.com" aria-label="https://yourwebsite.com" value="{{old('website')}}">
                    </div>
                </div>
            </div>
            <button type="submit" class="bg-primer mt-3 mb-4 btn-submit-2" style="border-radius:6px;"><i class="fa fa-paper-plane" aria-hidden="true"></i> Publish</button>


            <!-- <button class="lebar-100 hijau mt-3">Buat</button> -->
        </div>
    </form>
@endsection

@section('javascript')
<script src="{{ asset('js/flatpickr/dist/flatpickr.min.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/27.0.0/classic/ckeditor.js"></script>
<script>

    // var iconArc = document.getElementById('arc-div');
    // var rowInTime = document.getElementById('input-time').offsetWidth;

    // console.log(rowInTime);
    // if(rowInTime <= 510){
    //     console.log(rowInTime);
    //     iconArc.classList.add('d-none');
    // }
    
    ClassicEditor.defaultConfig = {
      toolbar: {
        items: [
          'heading',
          '|',
          'bold',
          'italic',
          '|',
          'bulletedList',
          'numberedList',
          '|',
          'undo',
          'redo'
        ]
      },
      language: 'en'
    };

    ClassicEditor.create( document.querySelector('#description' ) );
    ClassicEditor.create( document.querySelector('#location' ) );

    flatpickr("#startDate", {
        dateFormat: 'Y-m-d',
        minDate: "{{ date('Y-m-d') }}"
    });

    flatpickr("#startTime", {
        dateFormat: 'H:i',
        noCalendar: true,
        time_24hr: true,
        enableTime: true,
    });

    const chooseStartDate = date => {
        flatpickr("#endDate", {
            dateFormat: 'Y-m-d',
            minDate: date,
        });
    }
    const chooseStartTime = time => {
        flatpickr("#endTime", {
            dateFormat: 'H:i',
            minDate: time,
            noCalendar: true,
            time_24hr: true,
            enableTime: true,
        });
    }

    // const getSelectedBreakdown = () => {
    //     let selectedBreakdown = [];
    //     selectAll(".breakdowns").forEach(breakdown => {
    //         if (breakdown.classList.contains('active')) {
    //             let breakdownType = breakdown.getAttribute('breakdown-type');
    //             selectedBreakdown.push(breakdownType);
    //             document.getElementById(breakdownType).checked = true;
    //         }
    //     });
    // }

    // selectAll(".breakdowns").forEach(breakdown => {
    //     breakdown.addEventListener("click", e => {
    //         let target = e.currentTarget;
    //         target.classList.toggle('active');
    //         let breakdownType = breakdown.getAttribute('breakdown-type');
    //         document.getElementById(breakdownType).checked = false;
    //         getSelectedBreakdown();
    //     })
    // })

    const chooseFile = input => {
        let file = input.files[0];
        let reader = new FileReader();
        let preview = select("#logoPreview");
        reader.readAsDataURL(file);

        reader.addEventListener("load", function() {
            select("#inputLogoArea").classList.add('d-none');
            select("#previewLogoArea").classList.remove('d-none');
            preview.setAttribute('src', reader.result);
        });
    }
    const removePreview = () => {
        select("#inputLogoArea").classList.remove('d-none');
        select("#previewLogoArea").classList.add('d-none');
    }

    function httpGet(theUrl)
        {
            console.log(theUrl);
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
            xmlHttp.send( null );
            // console.log(JSON.parse(xmlHttp.responseText));
            return JSON.parse(xmlHttp.responseText);
        }

        const chooseProvince = (evt) => {
        var valSelected = evt.options[evt.selectedIndex].getAttribute('data');;
        console.log(valSelected);

        var arrCity = httpGet(window.location.origin+"/city/"+valSelected);

        //Create and append select list
        var selectList = document.getElementById("city_select");

        var options = document.querySelectorAll('#city_select option');
        options.forEach(o => o.remove());
        //Create and append the options
        for (var i = 0; i < arrCity.length; i++) {
            var option = document.createElement("option");
            option.setAttribute("value", arrCity[i].city_name);
            option.text = arrCity[i].city_name;
            // console.log(arrCity[i]);
            selectList.appendChild(option);
        }
    }
</script>
@endsection

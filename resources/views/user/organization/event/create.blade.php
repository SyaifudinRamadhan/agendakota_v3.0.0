@extends('layouts.user')

@section('title', 'Buat Event Baru')

@section('head.dependencies')

    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/createPage.css') }}">
@endsection

@section('content')

     <style>
        .select2-results__option{
            background-color: unset;
            color: unset;
        }
    </style>

    <div class="ml-3 mr-3">
        <h2>Buat Event Baru</h2>
        <p class="mb-4 teks-transparan">untuk {{ $organization->name }}</p>
        @include('admin.partials.alert')
    </div>


    <form action="{{ route('organization.event.store', $organizationID) }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="contentbar">
            <div>
                <div class="mt-2"></div>
                <div class="bagi lebar-100 mt-1" id="inputLogoArea">
                    {{-- <input type="hidden" name="execution_type" value="online"> --}}
                    <input id="banner" type="file" accept="image/jpg, image/png, image/jpeg" name="logo" class="box" onchange="chooseFile(this, {{ json_encode($organization->user->package->max_attachment) }})">
                    <label class="w-100 rounded-8 uploadArea asp-rt-5-2" style="color: #E6286E;" for="banner">
                        <i class="fa fa-image" style="font-size: 36px; color: #e5214f; /*#EB597B*/"></i><br>
                        <span
                            style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 14px; color: #e5214f; /*#EB597B*/">
                            Upload Event Banner max size {{ $organization->user->package->max_attachment }} Mb
                        </span><br>
                        <span
                            style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 12px; color: #E6286E;">
                            Recommended 1500x600px, With Aspect Ratio(5:2), PNG, JPG
                        </span>
                    </label>
                </div>
                <div class="bagi lebar-100 rata-tengah d-none rounded-8" id="previewLogoArea">
                    <img class="rounded-8 img-preview" id="logoPreview" /><br><br>
                    <span class="pointer bg-merah lebar-100 btn-del-img-preview" onclick="removePreview()">hapus</span>
                </div>
            </div>
            <div>
                <div class="mt-2" style="padding-top: 3%;">Judul Event :</div>
                <input required type="text" class="box" name="name" value="{{ old('name') }}" maxlength="191" required>
            </div>
            <!-- <div>
                    <div class="bagi bagi-5" style="padding-top: 3%;">
                        <div class="mt-2">Tanggal Mulai :</div>
                        <input type="text" class="box" name="start_date" id="startDate" onchange="chooseStartDate(this.value)" required value="{{ old('start_date') }}">
                    </div>
                    <div class="bagi bagi-5" style="padding-left: 3%; padding-top: 3%;">
                        <div class="mt-2">Jam Mulai :</div>
                        <input type="text" class="box" name="start_time" id="startTime" onchange="chooseStartTime(this.value)" required readonly value="{{ old('start_time') }}">
                    </div>
                    <div class="bagi bagi-5" style="text-align:center; padding-top:2%;">
                        <div class="mt-5" style="font-size: 20pt;"> &gt; </div>
                    </div>
                    <div class="bagi bagi-5" style="padding-top:3%;">
                        <div class="mt-2">Tanggal Berakhir :</div>
                        <input type="text" class="box" name="end_date" id="endDate" required readonly value="{{ old('end_date') }}">
                    </div>
                    <div class="bagi bagi-5" style=" padding-left: 3%; padding-top: 3%;">
                        <div class="mt-2">Jam Berakhir :</div>
                        <input type="text" class="box" name="end_time" id="endTime" required readonly value="{{ old('end_time') }}">
                    </div>

                    {{-- <div class="mt-2" style="padding-top: 3%;">Zona Waktu :</div>
                <select name="category" class="box">
                    <option value="">(GMT+ 07:00) Jakarta</option>
                </select> --}}
                </div> -->

            <div id="input-time" class="row mt-2" style="padding-top: 3%;">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-12 text-note">
                                    Tanggal & Waktu Mulai :
                                </div>
                                <div class="col-12 align-bottom">
                                    <input type="text" class="box mt-0 no-bg lebar-100" name="start_date" id="startDate"
                                        onchange="chooseStartDate(this.value)"
                                        required value="{{ old('start_date') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-12 text-note">
                                    Tanggal & Waktu Akhir :
                                </div>
                                <div class="col-12 align-bottom">
                                    <input type="text" class="box mt-0 no-bg lebar-100" name="end_date" id="endDate"
                                        onchange="chooseEndDate(this.value)"
                                        required value="{{ old('end_date') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-xl-5">
                    <div class="row">
                        <div class="col-7">
                            <div class="row">
                                <div class="col-12 text-note">
                                    Tanggal Mulai :
                                </div>
                                <div class="col-12 align-bottom">
                                    <input required type="text" class="box mt-0 no-bg lebar-100" name="start_date" id="startDate"
                                        value="{{ old('start_date') }}" onchange="chooseStartDate(this.value)" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="row">
                                <div class="col-12 no-pd-l text-note">Jam Mulai :</div>
                                <div class="col-12 no-pd-l align-bottom">
                                    <input required type="text" class="box mt-0 no-bg lebar-100" name="start_time" id="startTime"
                                        value="{{ old('start_time') }}" onchange="chooseStartTime(this.value)" required
                                        readonly>
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
                                    <input required type="text" class="box mt-0 no-bg lebar-100" name="end_date" id="endDate"
                                        value="{{ old('end_date') }}" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="row">
                                <div class="col-12 text-note no-pd-l">Jam Berakhir :</div>
                                <div class="col-12 no-pd-l align-bottom">
                                    <input required type="text" class="box mt-0 no-bg lebar-100" name="end_time" id="endTime"
                                        value="{{ old('end_time') }}" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

            </div>

            <div>
                <div class="mt-2" style="padding-top: 3%;">Deskripsi :</div>
                <textarea name="description" id="description" class="box">{{ old('description') }}</textarea>
            </div>

            <div>
                <div class="mt-2" style="padding-top: 3%;">Syarat dan Ketentuan :</div>
                <textarea rows="10" class="box no-bg" id="snk" name="snk" placeholder="Syarat dan ketentuan event !!!">{{ old('snk') }}</textarea>
            </div>

            <div>
                <div class="mt-2" style="padding-top: 3%;">Kategori Event :</div>
                <input id="category-form" type="hidden" name="category" value="{{ old('category') }}">
                <select id="multi-select" class="js-example-basic-multiple w-100 box no-bg" name="states[]" multiple="multiple">
                    @foreach ($types as $type)
                        <option value="{{ $type['name'] }}">{{ $type['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row mt-5">
                <div class="col-md"> Topik Event :
                    <input id="type-form" type="hidden" name="type" value="{{ old('type') }}">
                    <select id="multi-select-type" class="js-example-basic-multiple-2 w-100 box no-bg" name="states[]" multiple="multiple">
                        @foreach ($topics as $topic)
                            <option value="{{ $topic }}">{{ $topic }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md"> Event Punchline :
                    <input type="text" class="box" name="punchline" placeholder="Vivat!"
                        value="{{ old('punchline') }}">
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md"> Provinsi :
                    <select required name="province" class="box" onchange="chooseProvince(this)">
                        <option value="{{ old('province') }}">
                            {{ old('province') == '' ? '-- Pilih Provinsi --' : old('province') }}</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province['province'] }}" data="{{ $province['province_id'] }}">
                                {{ $province['province'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md"> Kabupaten / Kota :
                    <select required name="city" class="box" id="city_select">
                        <option value="{{ old('city') }}">{{ old('city') == '' ? '-- Pilih Kota --' : old('city') }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12">
                    Jenis Event :
                    <select onchange="selectMode(this)" name="execution_type" required class="box">
                        <option value="{{ old('execution_type') }}">
                            {{ old('execution_type') == '' ? '-- Pilih Jenis Event --' : old('execution_type') }}</option>
                        <option value="online">Online</option>
                        <option value="offline">Offline</option>
                        <option value="hybrid">Hybrid</option>
                    </select>

                    <div id="note" class="card mb-3" style="display: none">
                        <div class="card-header bg-primer">
                            Catatan
                        </div>
                        <div class="card-body">
                            <p class="card-text">Harap masukkan detail lokasi event jika kamu memilih event hybrid ataupun offline.</p>
                        </div>
                    </div>

                    <div class="mt-3" id="location-box" style="display: none">
                        <div class="mt-2" style="padding-top: 3%;">Lokasi / Alamat :</div>
                        <textarea name="location" id="location" class="box" style="display: none">{{ old('location') }}</textarea>
                    </div>
                </div>
                {{-- <div class="col-12">
                    <button class="w-100 button btn-outline-primer teks-primer" type="button" class="mt-1"  onclick="munculPopup('#eventLocation')">Sistem Event</button>
                </div>
                <div class="bg"></div>
                <div class="popupWrapper" id="eventLocation">
                    <div class="popup lebar-70 rounded-5">
                        <div class="wrap">
                            <h3 class="align-2">Sistem Event
                                <i class="fas bi bi-x-circle-fill op-5 ke-kanan pointer" onclick="hilangPopup('#eventLocation')"></i>
                            </h3>
                            <div class="">
                                <div class="card mb-3">
                                        <div class="card-header bg-primer">
                                            Petunjuk
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">Harap masukkan detail lokasi event jika kamu memilih event hybrid ataupun offline.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="wrap">
                                    <select onchange="selectMode(this)" name="execution_type" required class="box">
                                        <option value="{{ old('execution_type') }}">
                                            {{ old('execution_type') == '' ? '-- Pilih Tipe --' : old('execution_type') }}</option>
                                        <option value="online">Online</option>
                                        <option value="offline">Offline</option>
                                        <option value="hybrid">Hybrid</option>
                                    </select>
                                </div>
                                <div class="mt-3" id="location-box" style="display: none">
                                    <div class="mt-2" style="padding-top: 3%;">Lokasi / Alamat :</div>
                                    <textarea name="location" id="location" class="box" style="display: none">{{ old('location') }}</textarea>
                                </div>
                        </div>
                    </div>
                </div> --}}
            </div>
            <div>
                <div class="mt-2" style="padding-top: 5%;">Breakdown Event :</div>
                <div class="row">
                    <div class="col-lg-6 mb-2">
                        <div class="wrapper breakdowns {{ old('breakdowns') == null ? '' : (in_array('Stage and Session', old('breakdowns')) == true ? 'active' : '') }}"
                            breakdown-type="stage-session">
                            <input type="checkbox" name="breakdowns[]" value="Stage and Session" id="stage-session"
                                class="check-breakdown"
                                {{ old('breakdowns') == null ? '' : (in_array('Stage and Session', old('breakdowns')) == true ? 'checked' : '') }}>
                            <div class="bagi text-breakdown">
                                Stage & Sessions
                            </div>
                            <div class="bagi icon-breakdown" id="icon">
                                <i class="fa bi bi-camera-video"></i>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-6 mb-2">
                        <div class="wrapper breakdowns {{ old('breakdowns') == null ? '' : (in_array('Lounge', old('breakdowns')) == true ? 'active' : '') }}"  breakdown-type="lounge">
                            <input type="checkbox" name="breakdowns[]" value="Lounge" id="lounge" class="check-breakdown" {{ old('breakdowns') == null ? '' : (in_array('Lounge', old('breakdowns')) == true ? 'checked' : '') }}>
                            <div class="bagi  text-breakdown">
                                Lounge
                            </div>
                            <div class="bagi icon-breakdown" display="inline-block"id="icon">
                                <i class="fa fa-hand"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-2">
                        <div class="wrapper breakdowns {{ old('breakdowns') == null ? '' : (in_array('VIP Lounge', old('breakdowns')) == true ? 'active' : '') }}"  breakdown-type="vip-lounge">
                            <input type="checkbox" name="breakdowns[]" value="VIP Lounge" id="vip-lounge" class="check-breakdown" {{ old('breakdowns') == null ? '' : (in_array('VIP Lounge', old('breakdowns')) == true ? 'checked' : '') }}>
                            <div class="bagi text-breakdown">
                                VIP Lounge
                            </div>
                            <div class="bagi icon-breakdown" id="icon">
                                <i class="fa bi bi-mic"></i>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-lg-6 mb-2">
                        <div class="wrapper breakdowns {{ old('breakdowns') == null ? '' : (in_array('Sponsors', old('breakdowns')) == true ? 'active' : '') }}"
                            breakdown-type="sponsors">
                            <input type="checkbox" name="breakdowns[]" value="Sponsors" id="sponsors"
                                class="check-breakdown"
                                {{ old('breakdowns') == null ? '' : (in_array('Sponsors', old('breakdowns')) == true ? 'checked' : '') }}>
                            <div class="bagi text-breakdown">
                                Sponsors
                            </div>
                            <div class="bagi icon-breakdown" id="icon">
                                <i class="fa fa-tv"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-2">
                        <div class="wrapper breakdowns {{ old('breakdowns') == null ? '' : (in_array('Exihibitors', old('breakdowns')) == true ? 'active' : '') }}"
                            breakdown-type="exihibitors">
                            <input type="checkbox" name="breakdowns[]" value="Exihibitors" id="exihibitors"
                                class="check-breakdown"
                                {{ old('breakdowns') == null ? '' : (in_array('Exihibitors', old('breakdowns')) == true ? 'checked' : '') }}>
                            <div class="bagi text-breakdown">
                                Exihibitors
                            </div>
                            <div class="bagi icon-breakdown" id="icon">
                                <i class="fa fa-chalkboard-teacher"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-2">
                        <div class="wrapper breakdowns {{ old('breakdowns') == null ? '' : (in_array('Media Partner', old('breakdowns')) == true ? 'active' : '') }}"
                            breakdown-type="media-partner">
                            <input type="checkbox" name="breakdowns[]" value="Media Partner" id="media-partner"
                                class="check-breakdown"
                                {{ old('breakdowns') == null ? '' : (in_array('Media Partner', old('breakdowns')) == true ? 'checked' : '') }}>
                            <div class="bagi text-breakdown">
                                Media Partner
                            </div>
                            <div class="bagi icon-breakdown" id="icon">
                                <i class="fa bi bi-postcard"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h2 style="padding-top: 5%;">Media sosial</h2>
                <div class="form-group" style="padding-top: 3%;">
                    <label for="instagram">Instagram :</label>
                    <div class="input-group mt-1">
                        <span class="input-group-text medsos" id="instagram"><i class="fa fa-instagram"
                                aria-hidden="true"></i></span>
                        <input type="text" name="instagram" id="instagram" class="form-control"
                            placeholder="https://www.instagram.com/yourprofile"
                            aria-label="https://www.instagram.com/yourprofile"
                            aria-describedby="https://www.instagram.com/yourprofile" value="{{ old('instagram') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="twitter">Twitter :</label>
                    <div class="input-group mt-1">
                        <span class="input-group-text medsos" id="twitter"><i class="fa fa-twitter"
                                aria-hidden="true"></i></span>
                        <input type="text" name="twitter" id="twitter" class="form-control"
                            placeholder="https://www.twitter.com/yourprofile"
                            aria-label="https://www.twitter.com/yourprofile"
                            aria-describedby="https://www.twitter.com/yourprofile"
                            aria-label="https://www.twitter.com/yourprofil" value="{{ old('twitter') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="facebook">Facebook :</label>
                    <div class="input-group mt-1">
                        <span class="input-group-text medsos" id="facebook"><i class="fa fa-facebook"
                                aria-hidden="true"></i></span>
                        <input type="text" name="facebook" id="facebook" class="form-control"
                            placeholder="https://www.facebook.com/yourprofile"
                            aria-label="https://www.facebook.com/yourprofile"
                            aria-describedby="https://www.facebook.com/yourprofile"
                            aria-label="https://www.facebook.com/yourprofil" value="{{ old('facebook') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="website">Website :</label>
                    <div class="input-group mt-1">
                        <span class="input-group-text medsos" id="website"><i class="fa fa-paper-plane"
                                aria-hidden="true"></i></span>
                        <input type="text" name="website" id="website" class="form-control"
                            placeholder="https://yourwebsite.com" aria-label="https://yourwebsite.com"
                            aria-describedby="https://yourwebsite.com" aria-label="https://yourwebsite.com"
                            value="{{ old('website') }}">
                    </div>
                </div>
            </div>
            <button type="submit" class="bg-primer mt-3 mb-4 btn-submit-2" style="border-radius:6px;"><i
                    class="fa fa-paper-plane" aria-hidden="true"></i> Publish</button>


            <!-- <button class="lebar-100 hijau mt-3">Buat</button> -->
        </div>
    </form>
@endsection

@section('javascript')
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/27.0.0/classic/ckeditor.js"></script>
    <script>
        // var iconArc = document.getElementById('arc-div');
        // var rowInTime = document.getElementById('input-time').offsetWidth;

        // console.log(rowInTime);
        // if (rowInTime <= 510) {
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

        ClassicEditor.create(document.querySelector('#description'));
        ClassicEditor.create(document.querySelector('#snk'));
        ClassicEditor.create( document.querySelector('#location' ) );

        flatpickr("#startDate", {
            enableTime: true,
            time_24hr: true,
            dateFormat: 'Y-m-d H:i',
        });

        flatpickr("#endDate", {
            enableTime: true,
            time_24hr: true,
            dateFormat: 'Y-m-d H:i',
        });

        /*------------------- Metode lama ---------------------------
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
        ------------------------------------------------------------ */
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                maximumSelectionLength: 2
            });
            $('.js-example-basic-multiple-2').select2({
                maximumSelectionLength: 3
            });
        });

        const setMultiListValue = (targetID, multiSelectForm, delimiter) => {
            let dataString = document.querySelector(targetID).value.split(delimiter)
            console.log(dataString)
            $(multiSelectForm).val(dataString)
            $(multiSelectForm).on('select2:select', function (e) {
                let theSelection = e.params.data.id;
                let dataString = document.querySelector(targetID).value.split(delimiter)
                dataString.push(theSelection)
                document.querySelector(targetID).value = dataString.join(delimiter)
            });

            $(multiSelectForm).on('select2:unselect', function (e) {
                let theSelection = e.params.data.id;
                console.log(theSelection, e, 'unselect')
                let dataString = document.querySelector(targetID).value.split(delimiter)
                dataString = dataString.filter(item => item !== theSelection)
                document.querySelector(targetID).value = dataString.join(delimiter)
            });

            $(multiSelectForm).on('select2:clear', function (e) {
                let theSelection = e.params.data.id;
                console.log(theSelection, e, 'clear');
                document.querySelector(targetID).value = ''
            });

        }

        setMultiListValue('#category-form','#multi-select', ',')
        setMultiListValue('#type-form','#multi-select-type', '&')

        // ------------------- Metode baru ---------------------------------------
        
        const chooseStartDate = date => {
            console.log(date);
            flatpickr("#endDate", {
                enableTime: true,
                time_24hr: true,
                dateFormat: 'Y-m-d H:i',
                minDate: date,
            });
        }

        const chooseEndDate = date => {
            console.log(date);
            flatpickr("#startDate", {
                enableTime: true,
                time_24hr: true,
                dateFormat: 'Y-m-d H:i',
            });
        }

        // -----------------------------------------------------------------------


        const getSelectedBreakdown = () => {
            let selectedBreakdown = [];
            selectAll(".breakdowns").forEach(breakdown => {
                if (breakdown.classList.contains('active')) {
                    let breakdownType = breakdown.getAttribute('breakdown-type');
                    selectedBreakdown.push(breakdownType);
                    document.getElementById(breakdownType).checked = true;
                }
            });
        }

        selectAll(".breakdowns").forEach(breakdown => {
            breakdown.addEventListener("click", e => {
                let target = e.currentTarget;
                target.classList.toggle('active');
                let breakdownType = breakdown.getAttribute('breakdown-type');
                document.getElementById(breakdownType).checked = false;
                getSelectedBreakdown();
            })
        })

        const chooseFile = (input,maxSize) => {
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
                let preview = select("#logoPreview");
                reader.readAsDataURL(file);

                reader.addEventListener("load", function() {
                    select("#inputLogoArea").classList.add('d-none');
                    select("#previewLogoArea").classList.remove('d-none');
                    preview.setAttribute('src', reader.result);
                });
            }
        }
        const removePreview = () => {
            select("#inputLogoArea").classList.remove('d-none');
            select("#previewLogoArea").classList.add('d-none');
        }

        function httpGet(theUrl) {
            console.log(theUrl);
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open("GET", theUrl, false); // false for synchronous request
            xmlHttp.send(null);
            // console.log(JSON.parse(xmlHttp.responseText));
            return JSON.parse(xmlHttp.responseText);
        }

        const chooseProvince = (evt) => {
            var valSelected = evt.options[evt.selectedIndex].getAttribute('data');;
            console.log(valSelected);

            var arrCity = httpGet(window.location.origin + "/city/" + valSelected);

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
        function selectMode(object) {
            var value = object.value;
            console.log(value);
            if(value != "online" && value != ""){
                document.getElementById('location-box').style.display = 'unset';
                document.getElementById('note').style.display = 'unset';
            }else{
                document.getElementById('location-box').style.display = 'none';
                document.getElementById('note').style.display = 'none';
            }
        }
    </script>
@endsection

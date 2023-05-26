@extends('layouts.user')

@section('title', 'Kategori Booth')

<style type="text/css">
    .table {
        margin-top: 4px;
    }

    table td,
    table th {
        border-bottom: 0px !important;
        border-top: 0px !important;
        padding-top: 44px !important;
    }

    table td {
        vertical-align: middle !important;
        overflow-wrap: anywhere;
    }

    table thead th {
        color: #B0B0B0;
    }

    .img-table {
        width: 99.75px;
        height: 63.73px;
        border-radius: 9px;
    }

    .table-mode {
        height: calc(100% - 141px);
    }

    .table-block {
        border: none;
        overflow-x: auto;
        height: 100%;
    }
</style>

@section('content')

    @include('admin.partials.alert')
    <div class="row">
        <div class="col-md-7">
            <h3>Kategori Booth</h3>
        </div>
        <div class="col-md-5">
            <button class="btn-no-pd btn-add btn bg-primer" onclick="munculPopup('#addCategory')">
                <i class="fas fa-plus mr-1"></i> Kategori Baru
            </button>
        </div>
    </div>


    <div class="tinggi-50"></div>
    <div class="table-responsive">
        <table class="table table-borderless">
            <thead>
                <tr>
                    <td>Icon</td>
                    <th>Kategori</th>
                    <th>Prioritas</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>
                            <img class="asp-rt-1-1" style="max-width: 50px" src="{{$category->icon == 'default_icon.png' ? (asset('images/default_icon.png')) : (asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_cat_icon/'.$category->icon))}}" alt="">
                        </td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->priority }}</td>
                        <td class="text-center">
                            <button class="btn btn-no-pd mr-2 ml-2 mb-2 float-left hijau rounded-5 no-border"
                                onclick='edit(<?= json_encode($category) ?>)'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-no-pd mr-2 ml-2 float-left merah rounded-5 no-border"
                                onclick='deleteCategory(<?= json_encode($category) ?>)'>
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg"></div>
    <div class="popupWrapper" id="addCategory">
        <div class="popup rounded-5">
            <div class="wrap">
                <h4 class="rata-tengah mt-4">Buat Kategori Baru
                    <i class="fas bi bi-x-circle-fill op-5 ke-kanan pointer" onclick="hilangPopup('#addCategory')"></i>
                </h4>
                <form action="{{ route('organization.event.booth.category.store', [$organizationID, $eventID]) }}"
                    method="POST" class="wrap" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="wrap">
                        <div id="inputLogoArea">
                            <input type="file" accept="image/jpg, image/png, image/jpeg" id="input-file" class="box"
                                name="icon"
                                onchange="uploadIcon(this, 'addCategory')"
                                required oninvalid="this.setCustomValidity('Harap Upload Gambar ')"
                                oninput="setCustomValidity('')" style="max-width: 200px;">
                            <!-- <div class="uploadArea">Upload Logo</div> -->
                            <label for="input-file" class="lebar-100">
                                <div class="uploadArea font-inter-header rounded-5 asp-rt-1-1 mx-auto" style="max-width: 200px;">
                                    <div class="img-cover-up">
                                        <img class="img-cover" src="{{ asset('images/photo.png') }}">
                                    </div>
                                    Upload Icon
                                    <br>
                                    <span
                                        style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 12px; color: #E6286E;">
                                        Rasio 1 : 1 PNG or JPG
                                    </span>
                                </div>
                            </label>
                        </div>
                        <div id="previewLogoArea" class="d-none text-center">
                            <img id="logoPreview" class="rounded-5 img-preview asp-rt-1-1"><br />
                            <span class="btn lebar-100 mt-3 pointer teks-merah" onclick="removePreview('addCategory')">hapus</span>
                        </div>
                    </div>
                    <div class="mt-2">Nama Kategori :</div>
                    <input type="text" class="box no-bg" name="name" required>
                    <div class="mt-2">Prioritas :</div>
                    <input type="number" class="box no-bg" name="priority" min="1" required>
                    <div class="teks-kecil mt-2">untuk mengurutkan booth ketika ditampilkan</div>

                    <div class="lebar-100 rata-tengah">
                        <button type="submit" class="lebar-40 mt-4 primer">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="popupWrapper" id="editCategory">
        <div class="popup rounded-5">
            <div class="wrap">
                <h4 class="rata-tengah mt-4">Ubah Kategori Booth
                    <i class="fas bi bi-x-circle-fill op-5 ke-kanan pointer" onclick="hilangPopup('#editCategory')"></i>
                </h4>
                <form action="{{ route('organization.event.booth.category.update', [$organizationID, $eventID]) }}"
                    method="POST" class="wrap" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" id="pub-path-default" value="{{asset('images/default_icon.png')}}">
                    <input type="hidden" id="pub-path" value="{{asset('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_cat_icon/')}}">
                    <div class="wrap">
                        <div id="inputLogoArea" class="d-none">
                            <input type="file" accept="image/jpg, image/png, image/jpeg" id="input-file-edit" class="box"
                                name="icon"
                                onchange="uploadIcon(this, 'editCategory')"
                                oninput="setCustomValidity('')" style="max-width: 200px;">
                            <!-- <div class="uploadArea">Upload Logo</div> -->
                            <label for="input-file-edit" class="lebar-100">
                                <div class="uploadArea font-inter-header rounded-5 asp-rt-1-1 mx-auto" style="max-width: 200px;">
                                    <div class="img-cover-up">
                                        <img class="img-cover" src="{{ asset('images/photo.png') }}">
                                    </div>
                                    Upload Icon
                                    <br>
                                    <span
                                        style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 12px; color: #E6286E;">
                                        Rasio 1 : 1 PNG or JPG
                                    </span>
                                </div>
                            </label>
                        </div>
                        <div id="previewLogoArea" class="text-center">
                            <img id="logoPreview" class="rounded-5 img-preview asp-rt-1-1"><br />
                            <span class="btn lebar-100 mt-3 pointer teks-merah" onclick="removePreview('editCategory')">hapus</span>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <div class="mt-2">Nama Kategori :</div>
                    <input type="text" class="box no-bg" id="name" name="name" required>
                    <div class="mt-2">Prioritas :</div>
                    <input type="number" class="box no-bg" id="priority" name="priority" min="1" required>
                    <div class="teks-kecil mt-2">untuk mengurutkan booth ketika ditampilkan</div>

                    <div class="lebar-100 rata-tengah">
                        <button type="submit" class="lebar-40 mt-4 primer">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="popupWrapper" id="deleteCategory">
        <div class="popup">
            <div class="wrap">
                <h4>Hapus Kategori
                    <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#deleteCategory')"></i>
                </h4>
                <form action="{{ route('organization.event.booth.category.delete', [$organizationID, $eventID]) }}"
                    method="POST" class="wrap">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id">
                    Yakin ingin menghapus kategori <span id="name"></span> ?

                    <button class="lebar-100 mt-4 primer">Ya, hapus</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        const uploadIcon = (input, parentID, maxSize, isEdit = null) => {
            let file = input.files[0];
            maxSize = maxSize * 1024;
            let fileSize = file.size / 1024;
            let reader = new FileReader();
            reader.readAsDataURL(file);

            reader.addEventListener("load", function() {
                if (isEdit == null) {
                    console.log('no isEdit' + parentID);
                    let preview = select('#'+parentID+" #logoPreview");
                    select('#'+parentID+" #inputLogoArea").classList.add('d-none');
                    select('#'+parentID+" #previewLogoArea").classList.remove('d-none');
                    preview.setAttribute('src', reader.result);
                } else {
                    console.log('in isEdit' + isEdit);
                    let preview = select("#editCategory #logoPreview");
                    select("#editCategory #inputLogoArea").classList.add('d-none');
                    select("#editCategory #previewLogoArea").classList.remove('d-none');
                    preview.setAttribute('src', reader.result);
                }
            });
        }

        const removePreview = (parentID, isEdit = null) => {
            console.log('no isEdit' + parentID);
            if (isEdit == null) {
                select('#'+parentID+" input[type='file']").value = "";
                select('#'+parentID+" #inputLogoArea").classList.remove('d-none');
                select('#'+parentID+" #previewLogoArea").classList.add('d-none');
            } else {
                select("#editCategory input[type='file']").value = "";
                select("#editCategory #inputLogoArea").classList.remove('d-none');
                select("#editCategory #previewLogoArea").classList.add('d-none');
            }
        }

        const edit = data => {
            // data = JSON.parse(data);
            munculPopup("#editCategory");
            select("#editCategory #id").value = data.id;
            select("#editCategory #name").value = data.name;
            select("#editCategory #priority").value = data.priority;
            if(data.icon == 'default_icon.png'){
                let path = select('#editCategory #pub-path-default').value;
                select('#editCategory #logoPreview').src = path;
            }else{
                let pubPath = select('#editCategory #pub-path').value;
                select('#editCategory #logoPreview').src = pubPath+'/'+data.icon;
            }
        }

        const deleteCategory = data => {
            // data = JSON.parse(data);
            munculPopup("#deleteCategory")
            select("#deleteCategory #id").value = data.id;
            select("#deleteCategory #name").innerText = data.name;
        }
    </script>
@endsection

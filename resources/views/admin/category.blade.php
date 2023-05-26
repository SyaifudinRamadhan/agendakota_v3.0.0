@extends('layouts.admin')

@section('title', "Kategori Event")
    
@section('content')
<div class="rata-kanan mb-4">
    <button class="btn btn-success pl-4 pr-4" onclick="munculPopup('#addCategory')">
        <i class="fas fa-plus mr-2"></i> Baru
    </button>
</div>

@if ($message != "")
    <div class="bg-hijau-transparan mb-4 p-4 rounded">
        {{ $message }}
    </div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>Cover</th>
            <th>Nama Kategori</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
            <tr>
                <td>
                    <div class="tinggi-100" bg-image="{{ asset('images/category/'.$category->name.'.jpg') }}"></div>
                </td>
                <td>{{ $category->name }}</td>
                <td>
                    {{-- <button class="rounded hijau" onclick="edit('{{ $category }}')">
                        <i class="fas fa-edit"></i> Edit
                    </button> --}}
                    <button class="rounded merah" onclick="hapus('{{ $category }}')">
                        <i class="fas fa-trash mr-2"></i> Hapus
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="bg"></div>
<div class="popupWrapper" id="addCategory">
    <div class="popup">
        <div class="wrap">
            <h5>Tambah Kategori Baru
                <i class="fas fa-times pointer ke-kanan" onclick="hilangPopup('#addCategory')"></i>
            </h5>
            <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data" class="wrap super">
                {{ csrf_field() }}
                <div class="mt-2">Nama Kategori :</div>
                <input type="text" class="box" name="name" required>
                <div class="mt-2">Cover :</div>
                <input type="file" class="box" name="cover" onchange="inputFile(this, '#coverPreview')" required>
                <div id="coverPreview" class="uploadArea"><i class="fas fa-upload mr-2"></i> UPLOAD FILE</div>

                <button class="lebar-100 hijau mt-3">Simpan</button>
            </form>
        </div>
    </div>
</div>

{{-- <div class="popupWrapper" id="editCategory">
    <div class="popup">
        <div class="wrap">
            <h5>Ubah Kategori
                <i class="fas fa-times pointer ke-kanan" onclick="hilangPopup('#editCategory')"></i>
            </h5>
            <form action="{{ route('admin.category.update') }}" method="POST" enctype="multipart/form-data" class="wrap super">
                {{ csrf_field() }}
                <input type="hidden" name="id" id="id">
                <div class="mt-2">Nama Kategori :</div>
                <input type="text" class="box" id="name" name="name" required>
                
                <div class="mt-2">Ubah Cover :</div>
                <input type="file" class="box" name="cover" onchange="inputFile(this, '#coverPreview')">
                <div id="coverPreview" class="uploadArea"><i class="fas fa-upload mr-2"></i> UPLOAD FILE</div>
                <div class="rata-tengah mt-2 teks-kecil">
                    biaran kosong jika tidak ingin mengubah cover
                </div>

                <button class="lebar-100 hijau mt-3">Simpan</button>
            </form>
        </div>
    </div>
</div> --}}

<div class="popupWrapper" id="deleteCategory">
    <div class="popup">
        <div class="wrap">
            <h5>Hapus Kategori
                <i class="fas fa-times pointer ke-kanan" onclick="hilangPopup('#deleteCategory')"></i>
            </h5>
            <div class="wrap pt-3">
                Yakin ingin menghapus kategori <span id="name"></span>?
                <div class="bagi bagi-2 mt-3">
                    <a href="{{ route('admin.category.delete') }}">
                        <button class="lebar-100 merah">Ya, hapus</button>
                    </a>
                </div>
                <div class="bagi bagi-2 mt-3" >
                    <button class="lebar-100 no-border" onclick="hilangPopup('#deleteCategory')">batalkan</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    const edit = data => {
        data = JSON.parse(data);
        munculPopup("#editCategory");
        select("#editCategory #id").value = data.id;
        select("#editCategory #name").value = data.name;
    }
    const hapus = data => {
        data = JSON.parse(data);
        munculPopup("#deleteCategory");
        select("#deleteCategory #name").innerText = data.name;
        select("#deleteCategory a").href = `{{ route('admin.category.delete') }}/${data.id}`;
    }
</script>
@endsection
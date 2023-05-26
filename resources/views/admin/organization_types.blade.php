@extends('layouts.admin')

@section('title', "Tipe Organisasi")

@section('content')
<h2 class="ke-kiri">Tipe Organisasi</h2>
<button class="ke-kanan" onclick="munculPopup('#add')">Add New</button>

<div class="tinggi-100"></div>

@if ($types->count() == 0)
    <h4 class="rata-tengah">Tidak ada data</h4>
@else
    <table>
        <thead>
            <tr>
                <th class="lebar-15"><i class="fas fa-image"></i></th>
                <th>Name</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($types as $type)
                <tr>
                    <td>
                        <div class="tinggi-100 rounded" bg-image="{{ asset('storage/organization_type/'.$type->icon) }}"></div>
                    </td>
                    <td>
                        {{ $type->name }}
                        <div class="teks-kecil">{{ $type->count }} user</div>
                    </td>
                    <td>
                        <a href="{{ route('admin.user.organizationType.delete', $type->id) }}" class="bg-merah-transparan rounded p-2 pl-3 pr-3" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<div class="bg"></div>
<div class="popupWrapper" id="add">
    <div class="popup">
        <div class="wrap">
            <h4>Tambah Tipe Organisasi
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#add')"></i>
            </h4>
            <form action="{{ route('admin.user.organizationType.store') }}" method="POST" enctype="multipart/form-data" class="wrap">
                {{ csrf_field() }}
                <div class="rata-tengah">
                    <div class="bagi lebar-50">
                        <input type="file" name="image" id="image" onchange="inputFile(this, '.uploadArea')" required>
                        <div class="uploadArea">
                            <i class="fas fa-upload teks-besar"></i><br />
                            <div class="mt-2">Pilih Icon</div>
                        </div>
                    </div>
                </div>
                <div class="mt-2">Nama Tipe :</div>
                <input type="text" class="box" name="name" id="name" required>

                <button class="primer lebar-100 mt-4">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.agent')

@section('title', "Produk")
    
@section('content')
<div class="rata-kanan mb-2">
    <button class="primer" onclick="munculPopup('#addProduct')">
        <i class="fas fa-plus mr-1"></i> Baru
    </button>
</div>

@if ($message != "")
    <div class="bg-hijau-transparan rounded p-2 mb-2">
        {{ $message }}
    </div>
@endif

<table>
    <thead>
        <tr>
            <th>Produk</th>
            <th>Stok</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>
                    {{ $product->name }}
                    <div class="mt-1 teks-kecil teks-transparan">@currencyEncode($product->price)</div>
                </td>
                <td>{{ $product->stock }}</td>
                <td>
                    <span class="bg-hijau pointer rounded p-1 pl-2 pr-2 mr-2" onclick="edit('{{ $product }}')">
                        <i class="fas fa-edit"></i>
                    </span>
                    <span class="bg-merah pointer rounded p-1 pl-2 pr-2" onclick="hapus('{{ $product }}')">
                        <i class="fas fa-trash"></i>
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="bg"></div>
<div class="popupWrapper" id="addProduct">
    <div class="popup">
        <div class="wrap">
            <h3>Tambah Produk Baru
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#addProduct')"></i>
            </h3>
            <form action="{{ route('agent.product.store') }}" class="wrap" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="mt-2">Nama Produk :</div>
                <input type="text" class="box" id="name" name="name" required>
                <div class="mt-2">Deskripsi :</div>
                <textarea name="description" id="description" class="box" required></textarea>
                <div class="mt-2">Harga :</div>
                <input type="number" class="box" name="price" id="price" required>
                <div class="mt-2">Stok :</div>
                <input type="number" class="box" name="stock" id="stock" required>
                <div class="mt-2">Gambar :</div>
                <input type="file" class="box" name="image" required onchange="inputFile(this, '#addProduct .uploadArea')">
                <div class="uploadArea">-- UPLOAD FILE --</div>

                <button class="lebar-100 mt-3 primer">Tambahkan</button>
            </form>
        </div>
    </div>
</div>

<div class="popupWrapper" id="editProduct">
    <div class="popup">
        <div class="wrap">
            <h3>Edit Produk
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#editProduct')"></i>
            </h3>
            <form action="{{ route('agent.product.update') }}" class="wrap" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" id="id" name="id">
                <div class="mt-2">Nama Produk :</div>
                <input type="text" class="box" id="name" name="name" required>
                <div class="mt-2">Deskripsi :</div>
                <textarea name="description" id="description" class="box" required></textarea>
                <div class="mt-2">Harga :</div>
                <input type="number" class="box" name="price" id="price" required>
                <div class="mt-2">Stok :</div>
                <input type="number" class="box" name="stock" id="stock" required>
                <div class="mt-2">Ganti Gambar :</div>
                <input type="file" class="box" name="image" onchange="inputFile(this, '#editProduct .uploadArea')">
                <div class="uploadArea">
                    <div class="teks-kecil teks-transparan">biarkan kosong jika tidak ingin mengganti gambar</div>
                </div>

                <button class="lebar-100 mt-3 primer">Ubah Produk</button>
            </form>
        </div>
    </div>
</div>

<div class="popupWrapper" id="deleteProduct">
    <div class="popup">
        <div class="wrap">
            <h3>Hapus Produk
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#deleteProduct')"></i>
            </h3>
            <form action="{{ route('agent.product.delete') }}" class="wrap" method="POST">
                {{ csrf_field() }}
                <input type="hidden" id="id" name="id">
                Yakin ingin menghapus produk <span id="name"></span>?

                <div class="bagi bagi-2 mt-3">
                    <button class="merah no-border lebar-100">Ya, hapus</button>
                </div>
                <div class="bagi bagi-2 mt-3">
                    <button type="button" onclick="hilangPopup('#deleteHandout')" class="no-border lebar-100">batalkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    const edit = data => {
        data = JSON.parse(data);
        munculPopup("#editProduct");
        select("#editProduct #id").value = data.id;
        select("#editProduct #name").value = data.name;
        select("#editProduct #description").value = data.description;
        select("#editProduct #price").value = data.price;
        select("#editProduct #stock").value = data.stock;
    }

    const hapus = data => {
        data = JSON.parse(data);
        munculPopup("#deleteProduct");
        select("#deleteProduct #id").value = data.id;
        select("#deleteProduct #name").innerText = data.name;
    }
</script>
@endsection
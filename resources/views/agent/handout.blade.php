@extends('layouts.agent')

@section('title', "Handout")

@section('head.dependencies')
<style>
input[type=file] {
    height: 50px;
    opacity: 1;
}
</style>
@endsection

@section('content')
<div class="rata-kanan">
    <button class="primer" onclick="munculPopup('#addHandout')">
        <i class="fas fa-plus mr-2"></i> Baru
    </button>
</div>

<div class="tinggi-50"></div>

<table class="table">
    <thead>
        <tr>
            <th>Tipe</th>
            <th>File</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($handouts as $item)
            <tr>
                <td>{{ $item->type }}</td>
                <td>{{ $item->content }}</td>
                <td>
                    <span class="bg-merah p-1 pl-2 pr-2 rounded pointer" onclick="hapus('{{ $item }}')">
                        <i class="fas fa-trash"></i>
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="bg"></div>
<div class="popupWrapper" id="addHandout">
    <div class="popup">
        <div class="wrap">
            <h5>Tambahkan Handout
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#addHandout')"></i>
            </h5>
            <form action="{{ route('agent.handout.store') }}" method="POST" enctype="multipart/form-data" class="wrap">
                {{ csrf_field() }}
                <div class="mt-2">Tipe :</div>
                <select name="type" class="box" required>
                    <option value="">-- PILIH TIPE FILE --</option>
                    <option value="image">Gambar</option>
                    <option value="video">Video</option>
                    <option value="document">Dokumen (PDF, DOCX)</option>
                </select>

                <div class="mt-2">Upload File :</div>
                <input type="file" class="box" name="content" required>

                <button class="lebar-100 mt-4 primer">Tambahkan</button>
            </form>
        </div>
    </div>
</div>

<div class="popupWrapper" id="deleteHandout">
    <div class="popup">
        <div class="wrap">
            <h3>Hapus Handout
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#deleteHandout')"></i>
            </h3>
            <form action="{{ route('agent.handout.delete') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="id" id="id">
                Yakin ingin menghapus handout ini?
                
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
    const hapus = data => {
        data = JSON.parse(data);
        munculPopup("#deleteHandout");
        select("#deleteHandout #id").value = data.id;
    }
</script>
@endsection
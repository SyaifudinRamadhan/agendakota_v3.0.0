@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
@include('admin/partials/alert')
<div class="row">
    <h1 class="text-center mt-5 mb-5 col-md-12">Buat Halaman Statis Baru</h1>    
</div>
<form action=" {{ route('admin.page.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="form-group col-md-10">
            <input type="text" class="form-control" name="txtTitle" placeholder="Masukkan Nama Halaman"
                value="{{ old('txtTitle') }}">
        </div>
        <div class="form-group col-md">
            <button type="submit" class="btn bg-primer col-md">Submit</button>
        </div>
    </div>
    <div class="form-group mt-3">
        <label>Isi Halaman</label>
        <textarea name="konten" id="konten" class="form-control ckeditor">{{ old('konten') }}</textarea>
    </div>
</form>

@endsection

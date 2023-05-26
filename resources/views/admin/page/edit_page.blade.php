@extends('layouts.admin')

@section('title', 'Edit Page')

@section('content')
@include('admin/partials/alert')
<h1 class="text-center mt-5 mb-5">Edit Halaman</h1>
<form action=" {{ route('admin.page.update') }}" method="POST">
    @csrf
    <div class="row">
        <input type="text" name="id" id="id" value="{{$page->id}}" hidden>
        <div class="form-group col-md-10">
            <input type="text" class="form-control" name="txtTitle" placeholder="Masukkan Nama Halaman"
                value="{{ $page->title }}">
        </div>
        <div class="form-group col-md">
            <button type="submit" class="btn bg-primer col-md">Submit</button>
        </div>
    </div>
    <div class="form-group mt-3">
        <label>Isi Halaman</label>
        <textarea name="konten" id="konten" class="form-control ckeditor">{{ $page->body }}</textarea>
    </div>
</form>
@endsection

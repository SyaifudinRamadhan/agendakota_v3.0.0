@extends('layouts.admin')

@section('title', "FAQ Create")

@section('content')
    <div class="card">
        <div class="card-body">
            <h1 class="text-center">Buat FAQ Baru</h1>
            @include('admin/partials/alert')
            <form action=" {{ route('admin.faq.store')}}" method="POST">
                @csrf
                <div class="form-group" >
                    <label>Pertanyaan</label>
                    <input type="text" class="form-control" name="pertanyaan"  placeholder="Masukkan Title" value="{{old('pertanyaan')}}">
                </div>
                <div class="form-group">
                    <label>Jawaban</label>
                    <textarea name="jawaban" class="form-control" id="jawaban" cols="30" rows="10">{{old('jawaban')}}</textarea>
                </div>
                <button class="primer" type="submit" style="float: right;">Simpan</button><a type="button" href="{{route('admin.faq.index')}}" class="btn btn-light" style="padding: 12px 25px; height:50px; color:#e5214f; /*#eb597b*/ border:1px solid #ddd; transition:0.4s; float:right;margin-right:25px">Kembali</a>
            </form>
        </div>
    </div>
@endsection

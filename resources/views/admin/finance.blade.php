@extends('layouts.admin')

@section('title', 'Finance')

@section('head.dependencies')

@if(session('success'))
    <a class="alert alert-success">{{session('success')}}</a>
@endif

@section('content')

@include('admin.partials.alert')

<h2>Finance</h2>
<p class="teks-transparan mb-4 d-inline">Ringkasan data Finance AgendaKota.id</p>
<button class="btn btn-sm bg-primer float-right d-inline font-inter" data-toggle="modal" data-target="#tambah_finance"><i
    class="fa fa-plus"></i> Tambah</button>

<div class="col-md dashboard-item mt-5 mb-3">
    {{-- <div class="wrap"> --}}
        <table class="table table-bordered" id="finance-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>                    
                </tr>
            </thead>
        </table>
    {{-- </div> --}}
</div>


{{-- <a class="d-flex justify-content-center mt-3" href="/"><img src="{{ asset('images/logo.png') }}" class="lebar-50"></a> --}}

<div class="mt-5 pb-3"
    style="width: 100%; height: 15px; border-bottom: 1px solid black; text-align: center;bottom:0 auto;position: relative;">
    <a class="text-secondary pt-2 bg-white pr-3 pl-3">AgendaKota.id</a>
</div>


<!-- Modal -->
<div class="modal fade" id="tambah_finance" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center mx-auto">Tambah Admin Baru</h5>
            </div>
            <div class="modal-body p-5">
                <form action="{{ route('admin.finance.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder=""
                            aria-describedby="helpId" required value="{{old('name')}}">
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder=""
                            aria-describedby="helpId" required value="{{old('email')}}">
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder=""
                            aria-describedby="helpId" required value="{{old('password')}}">
                    </div>
                    <div class="form-group">
                        <label for="">Ulangi Password</label>
                        <input type="password" name="repeat_password" id="repeat-password" class="form-control"
                            placeholder="" aria-describedby="helpId" required value="{{old('repeat_password')}}">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn bg-primer col-md">Tambahkan</button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="edit_finance" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center mx-auto">Edit Data</h5>
            </div>
            <div class="modal-body p-5">
                <form id="form_edit" action="{{ route('admin.finance.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" name="name" id="edit-name" class="form-control" placeholder=""
                            aria-describedby="helpId" required>
                        <input type="text" name="id" id="edit-id" hidden>
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="text" name="email" id="edit-email" class="form-control" placeholder=""
                            aria-describedby="helpId" required>
                    </div>
                    {{-- <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="password" id="edit-password" class="form-control" placeholder=""
                            aria-describedby="helpId" required>
                    </div>                     --}}
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn bg-primer col-md">Terapkan</button>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script>
    $(document).ready(function() {

        $(document).on('click', ".edit", function() {
            $(this).addClass('clicked');
            var id = $('.clicked').attr('data-id');             

            $.ajax({
                    url: "{{ route('admin.finance.edit') }}?id=" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        $('#edit-id').val(data.id);
                        $('#edit-name').val(data.name);
                        $('#edit-email').val(data.email);                                              
                    }
                });                         
        })       
    });
</script>

@endsection




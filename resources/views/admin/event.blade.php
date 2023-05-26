@extends('layouts.admin')

@section('title', 'Event')

@section('head.dependencies')

@section('content')

@include('admin.partials.alert')

<h2>Event</h2>
<p class="teks-transparan mb-4 d-inline">Ringkasan data Event AgendaKota.id</p>
{{-- <button class="btn btn-sm bg-primer float-right d-inline font-inter"><i class="fa fa-plus"></i> Tambah</button> --}}

<div class="col-md dashboard-item mt-5 mb-3">
    {{-- <div class="wrap"> --}}
        <table class="table table-bordered table-responsive" id="event-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th> 
                    <th>Category</th>
                    <th>Organizer</th>
                    <th>Email Organizer</th>
                    <th>Status</th>                   
                    <th style="min-width: 250px;">Action</th>                    
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


{{-- Modal Edit --}}
<div class="modal fade" id="edit_event" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center mx-auto">Edit Data</h5>
            </div>
            <div class="modal-body p-5">
                <form id="form_edit" action="{{ route('admin.event.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" name="name" id="edit-name" class="form-control" placeholder=""
                            aria-describedby="helpId" required>
                    </div>
                 
                    <div class="mt-2">Kategori Event :</div>
                    <select name="category" class="box">
                        <option id="edit-category" value=""></option>
                       
                        @foreach ($categories as $category)
                            <option value="{{$category}}" >{{ $category }}</option>
                        @endforeach
                    </select>
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
    // $(document).on('click', ".edit", function() {        
    //     var id = $(this).attr('data-id');

    //     $.ajax({
    //         url: "{{ route('admin.event.dataedit') }}?idEventEdit=" + id,
    //         type: "GET",
    //         dataType: "JSON",
    //         success: function(data) {
    //             $('#edit-id').val(data.id);
    //             $('#edit-name').val(data.name);      
    //             $('#edit-category').val(data.category);
    //             $('#edit-category').text(data.category);      
    //         }
    //     });
    // })
</script>
@endsection



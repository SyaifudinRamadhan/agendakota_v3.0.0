@extends('layouts.admin')

@section('title', 'Statis Page')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="">
            <div class="row">
                <div class="col d-flex">
                    <h1 class="text-center" style="padding-left: 2%;">List Halaman </h1>
                </div>
                <div class="col">
                    <a href="{{ route('admin.page.create') }}" class="btn btn-outline-danger btn-md float-right">Tambah
                        Halaman Baru</a>
                </div>
            </div>
        </div>
        @include('admin/partials/alert')

        <div class="mt-3">
            <table class="table table-hover" id="HalamanTable" name="HalamanTable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama</th>                        
                        <th scope="col">Action</th>
                    </tr>
                <tbody>
                    @foreach ($show as $show)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>
                                {{ substr($show->title, 0, 25) . '...' }}
                            </td>                           
                            <td style="width: 30%;">
                                <a type="button" href="{{ route('homepage.page', $show->slug) }}" target="_blank"
                                    class="btn btn-outline-secondary mr-2">View</a>
                                <a type="button" href="{{ route('admin.page.edit', $show->id) }}"
                                    class="btn btn-outline-primary mr-2">Edit</a>
                                <a type="button" class="btn btn-outline-danger ml-2" data-toggle="modal"
                                    data-target="#deleteshow-{{ $show->id }}">
                                    Delete
                                </a>

                                <!-- Modal Delete-->
                                <div class="modal fade" id="deleteshow-{{ $show->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Hapus Halaman</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action=" {{ route('admin.page.destroy', $show->id) }}" method="POST">
                                                @csrf                                                
                                                <div class="modal-body">
                                                    <strong>Apakah Anda Yakin Ingin Menghapus Halaman ini?</strong>
                                                </div>
                                                <div class="modal-footer">
                                                    <button data-dismiss="modal" aria-hidden="true">Cancel</button>
                                                    <button class="primer" type="submit">Hapus</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </thead>
            </table>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script>
    $(document).ready(function() {
        $('#HalamanTable').DataTable();
    });
</script>

@endsection

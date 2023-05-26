@extends('layouts.admin')

@section('title', "FAQ Create")

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="">
                <div class="row">
                    <div class="col d-flex">
                        <h1 class="text-center" style="padding-left: 2%;">List FAQ </h1>
                    </div>
                    <div class="col">
                        <a href="{{route('admin.faq.create')}}" class="btn btn-outline-danger btn-md float-right">Tambah Pertanyaan Baru</a>
                        <a href="{{route('homepage.faq')}}" target="_blank" class="btn btn-outline-success btn-md float-right mr-2 ml-2"><i class="fa fa-eye"></i> Lihat Halaman</a>
                    </div>
                </div>
            </div>
            @include('admin/partials/alert')

            <div class="mt-3">
                <table class="table table-hover" id="faqTable" name="faqTable">
                    <thead>
                        <tr>
                          <th scope="col">No</th>
                          <th scope="col">Pertanyaan</th>
                          <th scope="col">Jawaban</th>
                          <th scope="col">Action</th>
                        </tr>
                        <tbody>
                            @foreach ($faq as $faq)
                            <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        {{ substr($faq->pertanyaan,0,25)."..." }}
                                    </td>
                                    <td>
                                        {{ substr($faq->jawaban,0,50)."..." }}
                                    </td>
                                    <td style="width: 20%;">
                                        <a type="button" href="{{route('admin.faq.edit', $faq->id)}}" class="btn btn-outline-primary mr-2">Edit</a>
                                        <a type="button" class="btn btn-outline-danger ml-2" data-toggle="modal" data-target="#deleteFaq-{{$faq->id}}">
                                            Delete
                                        </a>

                                            <!-- Modal Delete-->
                                            <div class="modal fade" id="deleteFaq-{{$faq->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Hapus Pertanyaan</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                        <form action=" {{ route('admin.faq.destroy', $faq->id)}}" method="POST">
                                                            @csrf                                                            
                                                            <div class="modal-body">
                                                                <strong>Apakah Anda Yakin Ingin Menghapus Pertanyaan ini?</strong>
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
    $(document).ready( function () {
    $('#faqTable').DataTable();
} );
</script>

@endsection

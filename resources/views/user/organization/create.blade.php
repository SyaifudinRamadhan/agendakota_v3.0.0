@extends('layouts.user')

@section('title', "Buat Organizer Baru")

@section('content')
    <h2>Buat Organizer Baru</h2>
    @include('admin.partials.alert')
    <form action="{{ route('organization.store') }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="mt-2">Nama Organizer :</div>
        <input type="text" class="box" name="name" required value="{{old('name')}}">
        {{-- <div class="mt-2">Logo :</div>
        <input type="file" class="box" name="logo" required> --}}
        <div class="bagi lebar-30 mt-2" id="inputLogoArea">
            <div>Logo :</div>
            <input type="file" name="logo" class="box" onchange="chooseFile(this)">
            <div class="uploadArea">Upload Logo</div>
            <span style="font-family: DM_Sans !important; font-style: normal; font-weight: 500; font-size: 12px; color: #E6286E;">
                Recommended 200x200px, Or Aspect Ratio(1:1), PNG, JPG
            </span>
        </div>
        <div class="bagi lebar-70 mt-2 d-none" id="previewLogoArea">
            <div>Logo :</div>
            <img id="logoPreview" /><br /><br />
            <span class="pointer bg-merah" style="border: none; padding: 5px 8px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; border-radius: 4px;" onclick="removePreview()">hapus</span>
        </div>
        <div class="mt-2">Penjelasan singkat :</div>
        <textarea name="description" class="box">{{old('description')}}</textarea>

        <button class="primer mt-3">Buat</button>
    </form>
@endsection

@section('javascript')
<script>
    const chooseFile = input => {
        let file = input.files[0];
        let reader = new FileReader();
        let preview = select("#logoPreview");
        reader.readAsDataURL(file);

        reader.addEventListener("load", function() {
            select("#inputLogoArea").classList.add('d-none');
            select("#previewLogoArea").classList.remove('d-none');
            preview.setAttribute('src', reader.result);
        });
    }
    const removePreview = () => {
        select("#inputLogoArea").classList.remove('d-none');
        select("#previewLogoArea").classList.add('d-none');
    }
</script>
@endsection

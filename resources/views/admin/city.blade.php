@extends('layouts.admin')

@section('title', "Cities")

@section('head.dependencies')
<style>
    .overlay {
        background-color: #00000040;
        color: #fff;
        text-align: center;
        margin-top: -300px;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        align-items: flex-end;
    }
    .overlay h4 { margin-top: 0px;width: 100%; }
    .controls {
        opacity: 0.01;
        background-color: #00000080;
    }
    .item:hover .controls {
        opacity: 1;
    }
</style>
@endsection
    
@section('content')
<h2 class="ke-kiri">Cities</h2>
<button class="ke-kanan" onclick="munculPopup('#add')">Add New</button>

<div class="tinggi-100"></div>
@foreach ($cities as $city)
    <div class="bagi bagi-4 item">
        <div class="wrap">
            <div class="tinggi-300 rounded-1-0" bg-image="{{ asset('storage/city_image/'.$city->image) }}"></div>
            <div class="tinggi-300 rounded-1-0 overlay">
                <h4 class="mb-0">{{ $city->name }}</h4>
                <div class="controls lebar-100 corner-bottom-left corner-bottom-right pt-3 pb-3">
                    <a href="{{ route('admin.city.priority', [$city->id, 'increase']) }}">
                        <span class="pointer teks-putih fas fa-arrow-left mr-4"></span>
                    </a>
                    {{-- <span class="pointer teks-putih fas fa-edit mr-1"></span> --}}
                    <a href="{{ route('admin.city.delete', $city->id) }}" onclick="return confirm('Are you sure to delete this city?')">
                        <span class="pointer teks-merah fas fa-trash ml-1"></span>
                    </a>
                    <a href="{{ route('admin.city.priority', [$city->id, 'decrease']) }}">
                        <span class="pointer teks-putih fas fa-arrow-right ml-4"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endforeach

<div class="bg"></div>
<div class="popupWrapper" id="add">
    <div class="popup">
        <div class="wrap">
            <h4>Add New City
                <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#add')"></i>
            </h4>
            <form action="{{ route('admin.city.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                {{ csrf_field() }}
                <div class="bagi lebar-40 b">
                    <input type="file" class="box" name="image" required onchange="inputFile(this, '#uploadArea')">
                    <div id="uploadArea" class="rata-tengah bg-abu rounded uploadArea">
                        Click to choose file
                    </div>
                </div>
                <div class="bagi lebar-60 pl-3 pr-3">
                    <div>Name :</div>
                    <input type="text" class="box" name="name" required>
                    <button class="bg-primer mt-2 lebar-100">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    // 
</script>
@endsection
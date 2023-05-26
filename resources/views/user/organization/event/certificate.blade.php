@extends('layouts.user')

@section('title', "Certificate")

@php
    $certificate = $event->certificate;
    $pos = null;
    if ($certificate != null) {
        $pos = explode("|", $certificate->name_position);
    }
    $families = ['Arial','Calibri','Times'];
    $weight = ['Bold','Normal','Italic'];
@endphp

@section('head.dependencies')
<style>
    .area {
        width: 100%;
        border: 1px solid #ddd;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        align-content: center;
        display: flex;
        border-radius: 6px;
    }
</style>
@endsection
    
@section('content')
<form class="flex row wrap" method="POST" enctype="multipart/form-data" action="{{ route('organization.event.certificate.store', [$organizationID, $event->id]) }}">
    {{ csrf_field() }}
    <div class="grow-1 w-50 pr-4">
        <div>Template :</div>
        <input type="file" name="file" id="file" class="box" onchange="inputFile(this, '#area')">
        @if ($certificate != null)
            <div class="area squarize rectangle uploadArea" id="area" bg-image="{{ asset('storage/event_assets/'.$event->slug.'/event_certificates/'.$certificate->filename) }}">
                <div style="font-size: 22px;width: 100%;"></div>
            </div>
        @else
            <div class="area squarize rectangle uploadArea" id="area">
                <div style="font-size: 22px;width: 100%;" class="mb-1">
                    <i class="fa fa-upload"></i>
                </div>
                Upload Certificate
            </div>
        @endif
        <div class="teks-transparan teks-kecil mt-1">
            Recommended Resolution : 3508px x 2480px
        </div>
    </div>
    <div class="grow-1 w-50">
        <div class="bagi bagi-2">
            <div class="mt-1">Name Position (X) :</div>
            <input type="text" class="box bg-putih" id="position_x" name="position_x" value="{{ $pos != null ? $pos[0] : '' }}" required>
        </div>
        <div class="bagi bagi-2">
            <div class="mt-1">Name Position (Y) :</div>
            <input type="text" class="box bg-putih" id="position_y" name="position_y" value="{{ $pos != null ? $pos[1] : '' }}" required>
        </div>
        <div class="bagi bagi-3 mt-2">
            <div class="mt-1">Font Family :</div>
            <select name="font_family" id="fontFamily" class="box">
                @foreach ($families as $family)
                    <option {{ ($certificate != null && $family == $certificate->font_family) ? 'selected="selected"' : '' }}>{{ $family }}</option>
                @endforeach
            </select>
        </div>
        <div class="bagi bagi-3 mt-2">
            <div class="mt-1">Size (px) :</div>
            <input type="number" name="font_size" id="fontSize" class="box bg-putih" value="{{ $certificate != null ? $certificate->font_size : '' }}" required>
        </div>
        <div class="bagi bagi-3 mt-2">
            <div class="mt-1">Font Style :</div>
            <select name="font_weight" id="fontWeight" class="box">
                @foreach ($weight as $weight)
                    <option {{ ($certificate != null && $weight == $certificate->font_weight) ? 'selected="selected"' : '' }}>{{ $weight }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <button class="primer w-100 mt-4">Save Changes</button>
    @if ($event->certificate != null)
        <button type="button" onclick="generatePreview()" class="w-100 mt-2">Preview</button>
    @endif
</form>
@endsection

@section('javascript')
<script src="{{ asset('js/meme.js') }}"></script>
<script>
    const generatePreview = () => {
        let posX = select("#position_x").value;
        let posY = select("#position_y").value;
        let fontSize = select("#fontSize").value;
        let fontFamily = select("#fontFamily").value;
        let fontStyle = select("#fontWeight").value;
        let filename = "{{ $event->certificate == null ? '' : $certificate->filename }}";

        let theFont = `${fontSize}px ${fontFamily}`;
        if (fontStyle != "normal") {
            theFont = `${fontStyle.toLowerCase()} ${theFont}`
        }

        let meme = new MemeJS({
            width: 3508,
            height: 2480,
        });

        meme.setTemplate(`{{ asset('storage/event_assets/'.$event->slug.'/event_certificates/') }}/${filename}`);
        meme.addText({
            text: "Nama Peserta",
            position: {
                x: posX,
                y: posY
            },
            font: theFont
        });
        meme.download('Preview');
    }
</script>
@endsection
@extends('layouts.user')

@section('title', "Landing Site")

@section('content')
@if ($message != "")
    <div class="bg-hijau-transparan mb-3 p-3 rounded">
        {{ $message }}
    </div>
@endif
@if ($event->site == null)
    <h3 id="no_site">
        This event have no landing site yet. 
        <a href="#" onclick="creating()">
            Create One!
        </a>
    </h3>
@else
    <form action="{{ route('organization.event.site.update', [$organizationID, $event->id]) }}" id="update" method="POST">
        {{ csrf_field() }}
        <div class="mt-1">Domain :</div>
        <input type="text" class="box mb-3 bg-putih w-50" name="domain" value="{{ $site->domain }}" required>
        <br />
        <div class="bagi bagi-2">
            <div class="mt-2">Site Title :</div>
            <input type="text" class="box bg-putih" name="site_title" value="{{ $site->site_title }}" required>
        </div>
        <div class="bagi bagi-2">
            <div class="mt-2">Tagline :</div>
            <input type="text" class="box bg-putih" name="tagline" value="{{ $site->tagline }}" required>
        </div>

        <div class="mt-3">Meta Description :</div>
        <textarea name="meta_description" class="box">{{ $site->meta_description }}</textarea>

        <button class="primer rounded mt-3">Update</button>
    </form>
@endif

<form action="{{ route('organization.event.site.create', [$organizationID, $event->id]) }}" id="create" class="d-none" method="POST">
    {{ csrf_field() }}
    <h4>Create Landing Site</h4>
    <div class="mt-4">Domain for your site :</div>
    <input type="text" class="box bg-putih w-50" id="domain" name="domain" required>
    <br />
    <button class="primer mt-3 rounded">Create</button>
</form>

@endsection

@section('javascript')
<script>
    const creating = () => {
        select("h3#no_site").style.display = "none";
        select("form#create").classList.remove('d-none');
    }
</script>
@endsection
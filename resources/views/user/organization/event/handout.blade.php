@extends('layouts.user')

@section('title', "Handout")

@section('head.dependencies')
<style>

    .laravel-embed__responsive-wrapper { position: relative; height: 0; overflow: hidden; max-width: 100%; }
    .laravel-embed__fallback { background: rgba(0, 0, 0, 0.15); color: rgba(0, 0, 0, 0.7); display: flex; align-items: center; justify-content: center; }
    .laravel-embed__fallback,
    .laravel-embed__responsive-wrapper iframe,
    .laravel-embed__responsive-wrapper object,
    .laravel-embed__responsive-wrapper embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }

</style>
@endsection

@section('content')
<h2>Handout
    <button class="ke-kanan primer" onclick="munculPopup('#addHandout')">
        <i class="fas fa-cloud-upload-alt" class="wrap"></i> Upload File
    </button>
</h2>
<div class="teks-transparan" style="margin-top: -20px; margin-bottom: 30px">{{ $event->name }}</div>
<div class="rata-tengah">
    @include('admin.partials.alert')
</div>

{{-- tabs --}}
<div class="tab" style="border: none">
    <button class="tablinks-event-handout active" onclick="opentabs(event, 'event-handout', 'Photo')">Photo</button>
    <button class="tablinks-event-handout" onclick="opentabs(event, 'event-handout', 'Video')">Video</button>
    <button class="tablinks-event-handout" onclick="opentabs(event, 'event-handout', 'Documents')">Documents</button>
</div>
<div id="Photo" class="tabcontent-event-handout" style="display:block; border: none">
    @forelse($getFoto as $handout)
        <div class="bagi bagi-3">
            <div class="wrap">
                <div class="bg-putih rounded bayangan-5">
                    <div class="logo" bg-image="{{ asset('storage/event_assets/'.$event->slug.'/event_handouts/'.$handout->value) }}"></div>
                    <div class="label bg-primer">Upload : {{ $handout->created_at }}</div>
                    <div class="bagi bagi-2 mt-2">
                        <div class="wrap">
                            <button class="lebar-100 hijau" onclick="edit('{{ $handout }}', '{{ $event }}', 1, 0)">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bagi bagi-2 mt-2">
                        <div class="wrap">
                            <form action="{{route('organization.event.handout.delete',[$organizationID, $event->id, $handout->id])}}" method="POST">
                                @csrf
                                @method('delete')
                                <button class="lebar-100 merah"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
    <img class="partial-image" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zOnN2Z2pzPSJodHRwOi8vc3ZnanMuY29tL3N2Z2pzIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiIgeD0iMCIgeT0iMCIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMiA1MTIiIHhtbDpzcGFjZT0icHJlc2VydmUiIGNsYXNzPSIiPjxnPjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGc+PHBhdGggZD0ibTIwNi42MDUgNDE2LjYwNWMtNS44NTkgNS44NTktMTUuMzUyIDUuODU5LTIxLjIxMSAwbC0xOS4zOTQtMTkuMzk0LTgzLjc4OSA4My43ODloMzQ3LjU3OGwtMTQzLjc4OS0xNDMuNzg5eiIgZmlsbD0iI2ViNTk3YiIgZGF0YS1vcmlnaW5hbD0iIzAwMDAwMCIgc3R5bGU9IiIgY2xhc3M9IiI+PC9wYXRoPjxjaXJjbGUgY3g9IjE2NiIgY3k9IjI4NiIgcj0iMTUiIGZpbGw9IiNlYjU5N2IiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIHN0eWxlPSIiIGNsYXNzPSIiPjwvY2lyY2xlPjxwYXRoIGQ9Im0xNTUuMzk1IDM2NS4zOTVjNS44NTktNS44NTkgMTUuMzUyLTUuODU5IDIxLjIxMSAwbDE5LjM5NCAxOS4zOTQgNzkuMzk1LTc5LjM5NWM1Ljg1OS01Ljg1OSAxNS4zNTItNS44NTkgMjEuMjExIDBsMTU0LjM5NCAxNTQuMzk1di0yNjMuNzg5YzAtOC4yOTEtNi43MDktMTUtMTUtMTVoLTM2MGMtOC4yOTEgMC0xNSA2LjcwOS0xNSAxNXYyNjMuNzg5em0xMC42MDUtMTI0LjM5NWMyNC44MTQgMCA0NSAyMC4xODYgNDUgNDVzLTIwLjE4NiA0NS00NSA0NS00NS0yMC4xODYtNDUtNDUgMjAuMTg2LTQ1IDQ1LTQ1eiIgZmlsbD0iI2ViNTk3YiIgZGF0YS1vcmlnaW5hbD0iIzAwMDAwMCIgc3R5bGU9IiIgY2xhc3M9IiI+PC9wYXRoPjwvZz48cGF0aCBkPSJtNDk3IDkxaC0zNjFjLTguMjkxIDAtMTUgNi43MDktMTUgMTV2NDVoMzE1YzI0LjgxNCAwIDQ1IDIwLjE4NiA0NSA0NXYxOTVoMTZjOC4yOTEgMCAxNS02LjcwOSAxNS0xNXYtMjcwYzAtOC4yOTEtNi43MDktMTUtMTUtMTV6IiBmaWxsPSIjZWI1OTdiIiBkYXRhLW9yaWdpbmFsPSIjMDAwMDAwIiBzdHlsZT0iIiBjbGFzcz0iIj48L3BhdGg+PHBhdGggZD0ibTkxIDEwNmMwLTI0LjgxNCAyMC4xODYtNDUgNDUtNDVoMjU1di0xNWMwLTguMjkxLTYuNzA5LTE1LTE1LTE1aC0zNjFjLTguMjkxIDAtMTUgNi43MDktMTUgMTV2MjcwYzAgOC4yOTEgNi43MDkgMTUgMTUgMTVoMTZ2LTEzNWMwLTI0LjgxNCAyMC4xODYtNDUgNDUtNDVoMTV6IiBmaWxsPSIjZWI1OTdiIiBkYXRhLW9yaWdpbmFsPSIjMDAwMDAwIiBzdHlsZT0iIiBjbGFzcz0iIj48L3BhdGg+PC9nPjwvZz48L3N2Zz4=" />
    <div class="rata-tengah">
        <h3>Tidak ada Event Handout Tipe Foto</h3>
        <h4>Buat Sekarang!</h4>
    </div>
    @endforelse
</div>

<div id="Video" class="tabcontent-event-handout" style="display:none; border: none">
    @forelse ($getVideo as $handout)
        <div class="bagi bagi-3">
            <div class="wrap">
                <div class="bg-putih rounded bayangan-5">
                    @if (strstr($handout->value,"youtube"))
                        <x-embed url="{{$handout->value}}" style="padding-bottom: 0% !important;"/>
                        <div class="label bg-primer">Upload : {{ $handout->created_at }}</div>
                    @else
                    <a href="{{$handout->value}}" target="_blank">
                        <img class="partial-image" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zOnN2Z2pzPSJodHRwOi8vc3ZnanMuY29tL3N2Z2pzIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiIgeD0iMCIgeT0iMCIgdmlld0JveD0iMCAwIDQ3Ny44NjcgNDc3Ljg2NyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTEyIDUxMiIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+Cgk8Zz4KCQk8cGF0aCBkPSJNMjM4LjkzMywwQzEwNi45NzQsMCwwLDEwNi45NzQsMCwyMzguOTMzczEwNi45NzQsMjM4LjkzMywyMzguOTMzLDIzOC45MzNzMjM4LjkzMy0xMDYuOTc0LDIzOC45MzMtMjM4LjkzMyAgICBDNDc3LjcyNiwxMDcuMDMzLDM3MC44MzQsMC4xNDEsMjM4LjkzMywweiBNMzM5LjU1NywyNDYuNTQ2Yy0xLjY1NCwzLjMxOC00LjM0Myw2LjAwOC03LjY2Miw3LjY2MnYwLjA4NUwxOTUuMzYyLDMyMi41NiAgICBjLTguNDMyLDQuMjEzLTE4LjY4MiwwLjc5NC0yMi44OTYtNy42MzhjLTEuMTk4LTIuMzk3LTEuODE1LTUuMDQzLTEuOC03LjcyMlYxNzAuNjY3Yy0wLjAwNC05LjQyNiw3LjYzMy0xNy4wNywxNy4wNTktMTcuMDc1ICAgIGMyLjY1MS0wLjAwMSw1LjI2NiwwLjYxNSw3LjYzNywxLjhsMTM2LjUzMyw2OC4yNjdDMzQwLjMzMSwyMjcuODYzLDM0My43NjIsMjM4LjExLDMzOS41NTcsMjQ2LjU0NnoiIGZpbGw9IiNlYjU5N2IiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIHN0eWxlPSIiPjwvcGF0aD4KCTwvZz4KPC9nPgo8ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8L2c+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjwvZz4KPGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPC9nPgo8ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8L2c+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjwvZz4KPGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPC9nPgo8ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8L2c+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjwvZz4KPGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPC9nPgo8ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8L2c+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjwvZz4KPGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPC9nPgo8ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8L2c+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjwvZz4KPGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPC9nPgo8L2c+PC9zdmc+" />

                        </a>
                            <div class="label bg-primer">Upload : {{ $handout->created_at }}</div>
                    @endif
                    <div class="bagi bagi-2 mt-2">
                        <div class="wrap">
                            <button class="lebar-100 hijau" onclick="edit('{{ $handout }}', '{{ $event }}', 2)">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bagi bagi-2 mt-2">
                        <div class="wrap">
                            <form action="{{route('organization.event.handout.delete',[$organizationID, $event->id, $handout->id])}}" method="POST">
                                @csrf
                                @method('delete')
                                <button class="lebar-100 merah"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
    <img class="partial-image" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zOnN2Z2pzPSJodHRwOi8vc3ZnanMuY29tL3N2Z2pzIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiIgeD0iMCIgeT0iMCIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMiA1MTIiIHhtbDpzcGFjZT0icHJlc2VydmUiIGNsYXNzPSIiPjxnPjxwYXRoIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgZD0ibTIyNiAyMDguMDd2OTUuODZsNzYuNjk5LTQ3LjkzeiIgZmlsbD0iI2ViNTk3YiIgZGF0YS1vcmlnaW5hbD0iIzAwMDAwMCIgc3R5bGU9IiIgY2xhc3M9IiI+PC9wYXRoPjxwYXRoIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgZD0ibTc1IDQ1MWgzNjJjNDEuMzUzIDAgNzUtMzMuNjQ3IDc1LTc1di0yNDBjMC00MS4zNTMtMzMuNjQ3LTc1LTc1LTc1aC0zNjJjLTQxLjM1MyAwLTc1IDMzLjY0Ny03NSA3NXYyNDBjMCA0MS4zNTMgMzMuNjQ3IDc1IDc1IDc1em0xMjEtMjcwYzAtNS40NDkgMi45NTktMTAuNDc0IDcuNzM0LTEzLjEyNSA0LjcxNy0yLjYzNyAxMC41OTEtMi41MDUgMTUuMjIuNDFsMTIwIDc1YzQuMzggMi43MzkgNy4wNDYgNy41NDQgNy4wNDYgMTIuNzE1cy0yLjY2NiA5Ljk3Ni03LjA0NiAxMi43MTVsLTEyMCA3NWMtMi40MzIgMS41MjMtNS4xODUgMi4yODUtNy45NTQgMi4yODUtMi41MDUgMC01LjAxLS42My03LjI2Ni0xLjg3NS00Ljc3NS0yLjY1MS03LjczNC03LjY3Ni03LjczNC0xMy4xMjV6IiBmaWxsPSIjZWI1OTdiIiBkYXRhLW9yaWdpbmFsPSIjMDAwMDAwIiBzdHlsZT0iIiBjbGFzcz0iIj48L3BhdGg+PC9nPjwvc3ZnPg==" />
        <div class="rata-tengah">
            <h3>Tidak ada Event Handout Tipe Video</h3>
            <h4>Buat Sekarang!</h4>
        </div>
    @endforelse
</div>

<div id="Documents" class="tabcontent-event-handout" style="display:none; border: none">
    @forelse ($getDokumen as $handout)
        <div class="bagi bagi-3">
            <div class="wrap">
                <div class="bg-putih rounded bayangan-5">
                    <img class="partial-image" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zOnN2Z2pzPSJodHRwOi8vc3ZnanMuY29tL3N2Z2pzIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiIgeD0iMCIgeT0iMCIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMiA1MTIiIHhtbDpzcGFjZT0icHJlc2VydmUiIGNsYXNzPSIiPjxnPjxwYXRoIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgZD0ibTEwNiA1MTJoMzAwYzI0LjgxNCAwIDQ1LTIwLjE4NiA0NS00NXYtMzE3aC0xMDVjLTI0LjgxNCAwLTQ1LTIwLjE4Ni00NS00NXYtMTA1aC0xOTVjLTI0LjgxNCAwLTQ1IDIwLjE4Ni00NSA0NXY0MjJjMCAyNC44MTQgMjAuMTg2IDQ1IDQ1IDQ1em02MC0zMDFoMTgwYzguMjkxIDAgMTUgNi43MDkgMTUgMTVzLTYuNzA5IDE1LTE1IDE1aC0xODBjLTguMjkxIDAtMTUtNi43MDktMTUtMTVzNi43MDktMTUgMTUtMTV6bTAgNjBoMTgwYzguMjkxIDAgMTUgNi43MDkgMTUgMTVzLTYuNzA5IDE1LTE1IDE1aC0xODBjLTguMjkxIDAtMTUtNi43MDktMTUtMTVzNi43MDktMTUgMTUtMTV6bTAgNjBoMTgwYzguMjkxIDAgMTUgNi43MDkgMTUgMTVzLTYuNzA5IDE1LTE1IDE1aC0xODBjLTguMjkxIDAtMTUtNi43MDktMTUtMTVzNi43MDktMTUgMTUtMTV6bTAgNjBoMTIwYzguMjkxIDAgMTUgNi43MDkgMTUgMTVzLTYuNzA5IDE1LTE1IDE1aC0xMjBjLTguMjkxIDAtMTUtNi43MDktMTUtMTVzNi43MDktMTUgMTUtMTV6IiBmaWxsPSIjZWI1OTdiIiBkYXRhLW9yaWdpbmFsPSIjMDAwMDAwIiBzdHlsZT0iIiBjbGFzcz0iIj48L3BhdGg+PHBhdGggeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBkPSJtMzQ2IDEyMGg5Ni4yMTFsLTExMS4yMTEtMTExLjIxMXY5Ni4yMTFjMCA4LjI3NiA2LjcyNCAxNSAxNSAxNXoiIGZpbGw9IiNlYjU5N2IiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIHN0eWxlPSIiIGNsYXNzPSIiPjwvcGF0aD48L2c+PC9zdmc+" />
                    <div class="wrap">
                        <a href="{{route('organization.event.handout.preview',[$organizationID, $event->id, $handout->value])}}" target="_blank">{{$handout->value}}</a>
                        <div class="label bg-primer">Upload : {{ $handout->created_at }}</div>
                    </div>
                    <div class="bagi bagi-2 mt-2">
                        <div class="wrap">
                            <button class="lebar-100 hijau" onclick="edit('{{ $handout }}', '{{ $event }}', 3, 0)">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bagi bagi-2 mt-2">
                        <div class="wrap">
                            <form action="{{route('organization.event.handout.delete',[$organizationID, $event->id, $handout->id])}}" method="POST">
                                @csrf
                                @method('delete')
                                <button class="lebar-100 merah"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <img class="partial-image" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zOnN2Z2pzPSJodHRwOi8vc3ZnanMuY29tL3N2Z2pzIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiIgeD0iMCIgeT0iMCIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMiA1MTIiIHhtbDpzcGFjZT0icHJlc2VydmUiIGNsYXNzPSIiPjxnPjxwYXRoIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgZD0ibTEwNiA1MTJoMzAwYzI0LjgxNCAwIDQ1LTIwLjE4NiA0NS00NXYtMzE3aC0xMDVjLTI0LjgxNCAwLTQ1LTIwLjE4Ni00NS00NXYtMTA1aC0xOTVjLTI0LjgxNCAwLTQ1IDIwLjE4Ni00NSA0NXY0MjJjMCAyNC44MTQgMjAuMTg2IDQ1IDQ1IDQ1em02MC0zMDFoMTgwYzguMjkxIDAgMTUgNi43MDkgMTUgMTVzLTYuNzA5IDE1LTE1IDE1aC0xODBjLTguMjkxIDAtMTUtNi43MDktMTUtMTVzNi43MDktMTUgMTUtMTV6bTAgNjBoMTgwYzguMjkxIDAgMTUgNi43MDkgMTUgMTVzLTYuNzA5IDE1LTE1IDE1aC0xODBjLTguMjkxIDAtMTUtNi43MDktMTUtMTVzNi43MDktMTUgMTUtMTV6bTAgNjBoMTgwYzguMjkxIDAgMTUgNi43MDkgMTUgMTVzLTYuNzA5IDE1LTE1IDE1aC0xODBjLTguMjkxIDAtMTUtNi43MDktMTUtMTVzNi43MDktMTUgMTUtMTV6bTAgNjBoMTIwYzguMjkxIDAgMTUgNi43MDkgMTUgMTVzLTYuNzA5IDE1LTE1IDE1aC0xMjBjLTguMjkxIDAtMTUtNi43MDktMTUtMTVzNi43MDktMTUgMTUtMTV6IiBmaWxsPSIjZWI1OTdiIiBkYXRhLW9yaWdpbmFsPSIjMDAwMDAwIiBzdHlsZT0iIiBjbGFzcz0iIj48L3BhdGg+PHBhdGggeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBkPSJtMzQ2IDEyMGg5Ni4yMTFsLTExMS4yMTEtMTExLjIxMXY5Ni4yMTFjMCA4LjI3NiA2LjcyNCAxNSAxNSAxNXoiIGZpbGw9IiNlYjU5N2IiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIHN0eWxlPSIiIGNsYXNzPSIiPjwvcGF0aD48L2c+PC9zdmc+" />
        <div class="rata-tengah">
            <h3>Tidak ada Event Handout Tipe Document</h3>
            <h4>Buat Sekarang!</h4>
        </div>
    @endforelse
</div>

{{-- tambah --}}
<div class="bg"></div>
<div class="popupWrapper" id="addHandout">
    <div class="popup" style="width: 70%;">
        <div class="wrap">
            <div class="rata-tengah">
                <h1 class="teks-tebal">Upload File
                    <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#addHandout')"></i>
                </h1>
                <p>Unggah file foto, video atau dokumen untuk eventmu</p>
            </div>

            <div class="rata-tengah">
                <div class="tab" style="border: none">
                    <button class="tablinks-event-handout-create active" onclick="opentabs(event, 'event-handout-create', 'foto')">Photo</button>
                    <button class="tablinks-event-handout-create" onclick="opentabs(event, 'event-handout-create', 'video')">Video</button>
                    <button class="tablinks-event-handout-create" onclick="opentabs(event, 'event-handout-create', 'dokumen')">Documents</button>
                </div>

                    <div id="foto" class="tabcontent-event-handout-create" style="display: block; border:none;">
                        <form action="" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="foto" name="type">
                            <div id="inputFileArea">
                                <input type="file" class="box" name="value" id="inputfoto" onchange="uploadFile(this)" required oninvalid="this.setCustomValidity('Harap Upload Gambar')" oninput="setCustomValidity('')">
                                <div class="uploadArea"> Klik Disini Untuk Upload Photo</div>
                            </div>
                            <div id="previewFileArea" class="d-none">
                                <img id="FilePreview" style="width: 200px;height: 200px; margin-top:30px;"><br />
                                <label id="FotoPreview"></label><br />
                                <span class="pointer teks-merah" onclick="removeFilePreview()">hapus</span>
                            </div>
                            <button class="primer lebar-100 mt-2">Submit</button>
                        </form>
                    </div>

                    <div id="video" class="tabcontent-event-handout-create" style="display: none; border:none;" >
                        <form action="" method="post">
                            @csrf
                            <input type="hidden" value="video" name="type">
                            <h4>Input Link Video</h4>
                            <input type="url" class="box" name="value" required oninvalid="this.setCustomValidity('Harap Masukkan Url Dengan Benar')" oninput="setCustomValidity('')">
                            <button class="primer lebar-100 mt-2">Submit</button>
                        </form>
                    </div>

                    <div id="dokumen" class="tabcontent-event-handout-create" style="display: none; border:none;">
                        <form action="" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="dokumen" name="type">
                            <div id="inputDokumenArea">
                                <input type="file" class="box" name="value" id="filedokumen" onchange="uploadDokumen(this)" required oninvalid="this.setCustomValidity('Harap Upload File Document')" oninput="setCustomValidity('')">
                                <div class="uploadArea">Klik Disini Untuk Upload Documents(pdf, doc, docx)</div>
                            </div>
                            <div id="previewDokumenArea" class="d-none">
                                <i class="far fa-file"></i>
                                <label id="DokumenPreview"></label><br />
                                <span class="pointer teks-merah" onclick="removeDokumenPreview()">hapus</span>
                            </div>
                            <button class="primer lebar-100 mt-2">Submit</button>
                        </form>
                    </div>

            </div>
        </div>
    </div>
</div>

{{-- edit --}}
<div class="popupWrapper" id="editHandout">
    <div class="popup" style="width: 70%;">
        <div class="wrap">
            <div class="rata-tengah">
                <h1 class="teks-tebal">Edit File
                    <i class="fas fa-times ke-kanan pointer" onclick="hilangPopup('#editHandout')"></i>
                </h1>
                <p>Unggah file foto, video atau dokumen untuk eventmu</p>
            </div>

            <div class="rata-tengah">
                <div class="tab" style="border: none">
                    <button class="tablinks-event-handout-edit" id="edit-foto" onclick="opentabs(event, 'event-handout-edit', 'foto-edit')">Photo</button>
                    <button class="tablinks-event-handout-edit" disabled id="edit-video" onclick="opentabs(event, 'event-handout-edit', 'video-edit')">Video</button>
                    <button class="tablinks-event-handout-edit" disabled id="edit-dokumen" onclick="opentabs(event, 'event-handout-edit', 'dokumen-edit')">Documents</button>
                </div>

                {{-- foto edit --}}
                <div id="foto-edit" class="tabcontent-event-handout-edit">
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="handoutID" id="fotoID">
                        <input type="hidden" name="slug" id="slug" value="{{ $event->slug }}">
                        <input type="hidden" value="foto" name="type">
                        <div id="inputFileArea" class="d-none">
                            <input type="file" class="box" name="value" id ="editfoto" onchange="uploadFile(this, 1)">
                            <div class="uploadArea"> Klik Disini Untuk Upload Photo</div>
                        </div>
                        <div id="previewFileArea" >
                            <img id="FilePreview" style="width: 200px;height: 200px; margin-top:30px;"><br />
                            <label id="EditFotoPreview"></label><br />
                            <span class="pointer teks-merah" onclick="removeFilePreview(1)">Hapus</span>
                        </div>
                        <button class="primer lebar-100 mt-2">Submit</button>
                    </form>
                </div>

                {{-- video edit --}}
                <div id="video-edit" class="tabcontent-event-handout-edit" >
                    <form action="" method="post">
                        @csrf
                        <input type="hidden" name="handoutID" id="videoID">
                        <input type="hidden" value="video" name="type">
                        <h4>Input Link Video</h4>
                        <input type="url" class="box" name="value" id="valueVideo" required oninvalid="this.setCustomValidity('Harap Masukkan Url Dengan Benar')" oninput="setCustomValidity('')">
                        <button class="primer lebar-100 mt-2">Submit</button>
                    </form>
                </div>

                {{-- dokumen edit --}}
                <div id="dokumen-edit" class="tabcontent-event-handout-edit">
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="dokumen" name="type">
                        <input type="hidden" name="handoutID" id="dokumenID">
                        <div id="inputDokumenArea" class="d-none">
                            <input type="file" class="box" name="value" id="editdokumen" onchange="uploadDokumen(this,1)">
                            <div class="uploadArea">Klik Disini Untuk Upload Documents(pdf, doc, docx)</div>
                        </div>
                        <div id="previewDokumenArea" >
                            <i class="far fa-file"></i>
                            <label id="DokumenEditPreview"></label><br />
                            <span class="pointer teks-merah" onclick="removeDokumenPreview(1)">hapus</span>
                        </div>
                        <button class="primer lebar-100 mt-2">Submit</button>
                    </form>
                </div>

            </div>
    </div>
</div>

@endsection

@section('javascript')
<script>

    //Tambah Foto
    const uploadFile = (input, isEdit = null) => {
        let file = input.files[0];
        let reader = new FileReader();
        reader.readAsDataURL(file);
        let namafile= document.getElementById('inputfoto').value;
        let fileedit= document.getElementById('editfoto').value;

        reader.addEventListener("load", function() {
            if (isEdit == null) {
                let preview = select("#FilePreview");
                select("#inputFileArea").classList.add('d-none');
                select("#previewFileArea").classList.remove('d-none');
                preview.setAttribute('src', reader.result);
                document.getElementById('FotoPreview').innerHTML=namafile;
            }else {
                let preview = select("#editHandout #FilePreview");
                select("#editHandout #inputFileArea").classList.add('d-none');
                select("#editHandout #previewFileArea").classList.remove('d-none');
                preview.setAttribute('src', reader.result);
                document.getElementById('EditFotoPreview').innerHTML=fileedit;
            }
        });
    }
    // Hapus Foto
    const removeFilePreview = (isEdit = null) => {
        if (isEdit == null) {
            select("input[type='file']").value = "";
            select("#inputFileArea").classList.remove('d-none');
            select("#previewFileArea").classList.add('d-none');
        }else {
            select("#editHandout input[type='file']").value = "";
            select("#editHandout #inputFileArea").classList.remove('d-none');
            select("#editHandout #previewFileArea").classList.add('d-none');
        }
    }

    const uploadDokumen = (input, isEdit = null) => {
        let file = input.files[0];
        let reader = new FileReader();
        reader.readAsDataURL(file);
        let namafile= document.getElementById('filedokumen').value;
        let fileedit= document.getElementById('editdokumen').value;

        reader.addEventListener("load", function() {
            if (isEdit == null) {
                let preview = select("#DokumenPreview");
                select("#inputDokumenArea").classList.add('d-none');
                select("#previewDokumenArea").classList.remove('d-none');
                document.getElementById('DokumenPreview').innerHTML=namafile;
            }else {
                let preview = select("#editHandout #DokumenPreview");
                select("#editHandout #inputDokumenArea").classList.add('d-none');
                select("#editHandout #previewDokumenArea").classList.remove('d-none');
                document.getElementById('DokumenEditPreview').innerHTML=fileedit;
            }
        });
    }
    // Hapus Dokumen
    const removeDokumenPreview = (isEdit = null) => {
        if (isEdit == null) {
            select("input[type='file']").value = "";
            select("#inputDokumenArea").classList.remove('d-none');
            select("#previewDokumenArea").classList.add('d-none');
        }else {
            select("#editHandout input[type='file']").value = "";
            select("#editHandout #inputDokumenArea").classList.remove('d-none');
            select("#editHandout #previewDokumenArea").classList.add('d-none');
        }
    }

    const edit = (data, event, tipe, isEdit=null) => {
        data = JSON.parse(data);
        event = JSON.parse(event);
        if(tipe == 1){
            munculPopup("#editHandout");
            document.getElementById("edit-foto").disabled = false;
            document.getElementById("edit-video").disabled = true;
            document.getElementById("edit-dokumen").disabled = true;
            document.getElementById("edit-foto").classList.add("active");
            document.getElementById("edit-video").classList.remove("active");
            document.getElementById("edit-dokumen").classList.remove("active");

            document.getElementById("foto-edit").style.display = "block";
            document.getElementById("video-edit").style.display= "none";
            document.getElementById("dokumen-edit").style.display= "none";

            if (isEdit == null) {
                select("input[type='file']").value = "";
                select("#inputFileArea").classList.add('d-none');
                select("#previewFileArea").classList.remove('d-none');
            }else {
                select("#editHandout input[type='file']").value = "";
                select("#editHandout #inputFileArea").classList.add('d-none');
                select("#editHandout #previewFileArea").classList.remove('d-none');
            }

            select("#editHandout #fotoID").value = data.id;
            select("#editHandout #FilePreview").setAttribute('src', `{{ asset('storage/event_assets/${event.slug}/event_handouts/${data.value}') }}`);
        }
        else if(tipe == 2){
            munculPopup("#editHandout");
            document.getElementById("edit-foto").disabled = true;
            document.getElementById("edit-video").disabled = false;
            document.getElementById("edit-dokumen").disabled = true;
            document.getElementById("edit-foto").classList.remove("active");
            document.getElementById("edit-video").classList.add("active");
            document.getElementById("edit-dokumen").classList.remove("active");

            document.getElementById("foto-edit").style.display = "none";
            document.getElementById("video-edit").style.display= "block";
            document.getElementById("dokumen-edit").style.display= "none";


            select("#editHandout #videoID").value = data.id;
            select("#editHandout #valueVideo").value = data.value;

        }else{
            munculPopup("#editHandout");
            document.getElementById("edit-foto").disabled = true;
            document.getElementById("edit-video").disabled = true;
            document.getElementById("edit-dokumen").disabled = false;
            document.getElementById("edit-foto").classList.remove("active");
            document.getElementById("edit-video").classList.remove("active");
            document.getElementById("edit-dokumen").classList.add("active");

            document.getElementById("foto-edit").style.display = "none";
            document.getElementById("video-edit").style.display= "none";
            document.getElementById("dokumen-edit").style.display= "block";

            if (isEdit == null) {
                select("input[type='file']").value = "";
                select("#inputDokumenArea").classList.add('d-none');
                select("#previewDokumenArea").classList.remove('d-none');
            }else {
                select("#editHandout input[type='file']").value = "";
                select("#editHandout #inputDokumenArea").classList.add('d-none');
                select("#editHandout #previewDokumenArea").classList.remove('d-none');
            }

            select("#editHandout #dokumenID").value = data.id;
            document.getElementById('DokumenEditPreview').innerHTML=data.value;
        }

    }
    const buat =  (data, event = null) => {
        data = JSON.parse(data);
        event = JSON.parse(event);
        munculPopup("#addHandout");
    }
</script>
@endsection

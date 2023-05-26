@extends('layouts.user')

@section('title', "Speaker")

@section('head.dependencies')
<link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/eventDashboard/speakersPage.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-md-7">
        @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
        <h2>Speaker</h2>
        @else
        <h2>Peformers</h2>
        @endif
        <p class="mt-2" style="color: #979797; font-size: 14px;">Add and manage speaker / Peformers profiles for your event</p>
    </div>
    <div class="col-md-5">
        <a href="{{route('organization.event.speaker.create',[$organizationID,$eventID])}}">
            <button class="btn ke-kanan primer font-inter">
                <i class="fas fa-plus mr-1"></i> Tambah
            </button>
        </a>
    </div>
</div>


@include('admin.partials.alert')
@forelse ($events->speakers as $speaker)
    <div class="bagi bagi-3 mt-5 pr-3">
        <div class="square-speaker">
            <div class="mt-3 wrap">
                <img src="{{ asset('storage/event_assets/'.$events->slug.'/speaker_photos/'.$speaker->photo) }}" style="width: 70px; height: 70px;border-radius:50%;">

                <i class="fa fa-ellipsis-h pointer dropdownToggle bagi-bagi-2" data-id="{{ $speaker->id }}" style=" color: #C4C4C4; font-size:20pt; float: right;" aria-hidden="true"></i>
                <div id="Dropdown{{$speaker->id}}" class="dropdown-content">
                    <a href="{{route( 'organization.event.speaker.edit', [$organizationID, $eventID, $speaker->id] ) }}">
                        <span class="teks-hijau pointer">
                            <i class="fas fa-edit"></i> Edit
                        </span>
                    </a>
                    <a href="{{route( 'organization.event.speaker.delete', [$organizationID, $eventID, $speaker->id] ) }}"
                    onclick="deleteConfirm(event)">
                        <span class="teks-merah pointer">
                            <i class="fas fa-trash"></i> Delete
                        </span>
                    </a>
                </div>
            </div>
            <div class="wrap">
                <div class="teks-tebal text-overflow">
                    {{$speaker->name}}
                </div>
                <div class="teks-tipis text-overflow" style="color: #C4C4C4;">
                    {{$speaker->job}}
                </div>
            </div>
            <div class="mt-2 mb-3 wrap">
                <a href="{{$speaker->website}}"><i class="fa fa-envelope mr-1" style="color: grey;"></i></a>
                <a href="{{$speaker->linkedin}}"><i class="fab fa-linkedin mr-1" style="color: grey;"></i></a>
                <a href="{{$speaker->instagram}}"><i class="fab fa-instagram mr-1" style="color: grey;"></i></a>
                <a href="{{$speaker->twitter}}"><i class="fab fa-twitter mr-1" style="color: grey;"></i></a>
            </div>
        </div>
    </div>
@empty
    <img class="partial-image" src="{{asset('images/speaker.png')}}" />
    <div class="rata-tengah">
        @if ($event->type == 'Seminar' || $event->type == 'Conference' || $event->type == 'Symposium' || $event->type == 'Workshop' || $event->type == 'Talkshow')
        <h3>Tidak Ada Speaker Untuk Saat ini</h3>
        @else
        <h3>Tidak Ada Bintang Tamu Untuk Saat ini</h3>
        @endif
        <h4>Buat Sekarang!</h4>
    </div>
@endforelse
@endsection

@section('javascript')
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2({
            placeholder: "Select a Speakers",
            allowClear: true
        });
    });

    let state = {
        isOptionOpened: false,
    }

    document.addEventListener("click", e => {
        selectAll(".dropdown-content").forEach(dropdown => {
            dropdown.classList.remove('show');
        });


        let target = e.target;
        if (target.classList.contains('dropdownToggle')) {
            let id = target.getAttribute('data-id');
            document.getElementById("Dropdown"+id).classList.toggle("show");
            state.isOptionOpened = true;
        }else {
            state.isOptionOpened = false;
        }
    });

    // Confirm delete by href url action
    function deleteConfirm(evt) {
        evt.preventDefault(); // prevent form submit
        var urlToRedirect = evt.currentTarget.getAttribute('href');

        Swal.fire({
        title: 'Apakah kamu yakin ?',
        text: "Kamu akan menghapus akun exhibitor ini.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.replace(urlToRedirect); 
            }
        })
    }

    // confirm delete by url href
    // Sweet alert delete
        // function onDelete(ticketBuyed) {
        //     event.preventDefault(); // prevent form submit
        //     var urlToRedirect = event.currentTarget.getAttribute('href');
            
        //             Swal.fire({
        //                 title: "Apakah kamu yakin ?",
        //                 text: "Session ini memiliki ticket yang terjual sejumlah "+ticketBuyed+", ticket kamu yang terhubung dari session ini akan terhapus",
        //                 type: "warning",
        //                 icon: "warning",
        //                 dangerMode: true,
        //                 showCancelButton: true,
        //                 confirmButtonText: "Ya, hapus",
        //                 cancelButtonText: "Batal",
        //                 closeOnConfirm: false,
        //                 closeOnCancel: false
        //             }).then((result) => {
        //                 if (result.isConfirmed) {
        //                     console.log(urlToRedirect);
        //                     window.location.replace(urlToRedirect);         // submitting the form when user press yes
        //                 } else {
        //                     Swal.fire("Dibatalkan", "Session batal dihapus", "info");
        //                 }
        //             });
        // }
</script>
@endsection

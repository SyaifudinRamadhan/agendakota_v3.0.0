<link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/user/connectionPage.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/user/peopleStream.css') }}">

@php
$connection = $event->purchase->unique('user_id');
@endphp

@if (count($connection) == 0)
    <div class="mt-5">
        <div class="mt-5 rata-tengah">
            <i class="bi bi-people teks-primer font-img"></i>
            <h5>Bertemu dengan orang - orang baru!</h5>
            <p>Temukan koneksi lewat berbagai event menarik</p>
        </div>
    </div>
@else
    @foreach ($connection as $conn)
            <div class="wrap">
                <div class="bayangan-5 rounded inline-flex w-100">

                    @if ($conn->users->photo == 'default')
                        <img src="{{ asset('images/profile-user.png') }}" class="picture">
                    @else
                        <img src="{{ asset('storage/profile_photos/' . $conn->users->photo) }}" class="picture">
                    @endif
                    <div class="wrap d-inline text-side">
                        <p class="mb-0 over-any">{{ $conn->users->name }}</p>
                        <p class="teks-transparan over-any">
                            {{ $conn->users->email }}
                        </p>
                    </div>
                </div>
            </div>
    @endforeach
@endif

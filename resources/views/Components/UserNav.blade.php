<div class="profile">
    <div class="wrap">
        <div class="rata-kiri">
            <div class="picture" bg-image="{{ asset('images/riyan.jpg') }}"></div>
        </div>
        <h3>{{ $myData->name }}</h3>
        <p class=" teks-transparan">riyan.satria.619@gmail.com</p>
    </div>
</div>
<div class="menu">
    <div class="wrap">
        <h4>GENERAL</h4>
    </div>
    <a href="{{ route('user.events') }}">
        <li class="{{ Route::currentRouteName() == 'user.events' ? 'active' : '' }}">
            Events
        </li>
    </a>
    <a href="{{ route('user.connections') }}">
        <li class="{{ Route::currentRouteName() == 'user.connections' ? 'active' : '' }}">
            Connections
        </li>
    </a>
    <a href="{{ route('user.invitations') }}">
        <li class="{{ Route::currentRouteName() == 'user.invitations' ? 'active' : '' }}">
            Invitations
            <span class="count">20</span>
        </li>
    </a>
    <a href="{{ route('user.profile') }}">
        <li class="{{ Route::currentRouteName() == 'user.profile' ? 'active' : '' }}">
            Profile
        </li>
    </a>
</div>
<div class="menu organization">
    <div class="wrap">
        <h4>ORGANIZATIONS
            <a href="{{ route('organization.create') }}">
                <i class="fas fa-plus ke-kanan pointer"></i>
            </a>
        </h4>
    </div>
    <a href="{{ route('organizer.index') }}">
        <li>
            <div class="icon" bg-image="{{ asset('images/surabaya.jpg') }}"></div>
            <div class="text">Bangga Surabaya</div>
        </li>
    </a>
    @foreach ($organizations as $organization)
        <a href="{{ route('organization.events', $organization->id) }}">
            <li>
                <div class="icon" bg-image="{{ asset('organizer_logo/'.$organization->logo) }}"></div>
                <div class="text">{{ $organization->name }}</div>
            </li>
        </a>
    @endforeach
</div>
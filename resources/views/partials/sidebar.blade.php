<div class="sidebar px-0 col-3 col-xl-3 col-xxl-2">
    {{-- <span class="sidebar__close text-light d-xl-none">Close</span> --}}
    <div class="logo"><img src="/assets/images/dash-logo-white.png" class="img-fluid" /></div>
    <ul class="side-menu">
        <li>
            <a href="/dashboard" class="{{ $modelName == 'dashboard' ? 'active' : '' }}">
                <i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="/kiosk" class="{{ $modelName == 'Kiosk Qr' ? 'active' : '' }}">
                <i class="fa-solid fa-tablet-screen-button"></i> Kiosk QR <small
                    class="blink"><strong>DOWNLOAD</strong></small>
            </a>
        </li>
        {{-- <li>
            <a href="#">
                <i class="fa fa-users" aria-hidden="true"></i> Users
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fa fa-calendar" aria-hidden="true"></i> Calendar
            </a>
        </li> --}}
        <li>
            <a href="/race" class="{{ $modelName == 'race' ? 'active' : '' }}">
                <i class="fa fa-flag-checkered" aria-hidden="true"></i> Rides + Entries
            </a>
        </li>
        {{-- <li>
            <a href="/entry">
                <i class="fa fa-list-alt" aria-hidden="true"></i> Entries
            </a>
        </li> --}}
        <li>
            <a href="/horse" class="{{ $modelName == 'horse' ? 'active' : '' }}">
                <i class="fa fa-horse" aria-hidden="true"></i> Horses
            </a>
        </li>
        <li>
            <a href="/rider" class="{{ $modelName == 'rider' ? 'active' : '' }}">
                <i class="fa-solid fa-hat-cowboy"></i> Riders
            </a>
        </li>
        <li>
            <a href="/trainer" class="{{ $modelName == 'trainer' ? 'active' : '' }}">
                <i class="fa-solid fa-user-tie"></i> Trainers
            </a>
        </li>
        {{-- <li>
            <a href="/season" class="{{ $modelName == 'season' ? 'active' : '' }}">
                <i class="fa fa-cloud" aria-hidden="true"></i> Seasons
            </a>
        </li> --}}
        {{-- <li>
            <a href="/owner" class="{{ $modelName == 'owner' ? 'active' : '' }}">
                <i class="fa fa-user" aria-hidden="true"></i> Owners
            </a>
        </li> --}}
        {{-- <li>
            <a href="/event" class="{{ $modelName == 'event' ? 'active' : '' }}">
                <i class="fa fa-list" aria-hidden="true"></i> Events
            </a>
        </li> --}}
        <li>
            <a href="/me" class="{{ $modelName == 'profile' ? 'active' : '' }}">
                <i class="fa-solid fa-user"></i> My Profile
            </a>
        </li>
        <li>
            <a href="/logout" class="">
                <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>Logout
                {{-- <i class="fa fa-user" aria-hidden="true"></i> --}}
            </a>
        </li>
    </ul>
</div>

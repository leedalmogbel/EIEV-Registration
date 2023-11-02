<nav class="navbar navbar-expand-lg px-0" style="background-color:#13214A;">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="rideslist">EIEV REGISTRATION</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown"
            aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
            <ul class="nav  nav-pills nav-fill ">
                <li class="nav-item">
                    <a href="rideslist" class="text-white nav-link {{ $modelName == 'entry' ? 'active' : '' }}">
                        <i class="fa-solid fa-list-check" aria-hidden="true"></i> Entries
                    </a>
                </li>
                <li class="nav-item">
                    <a href="submitentry" class="text-white nav-link {{ $modelName == 'submitentry' ? 'active' : '' }}">
                        <i class="fa fa-list" aria-hidden="true"></i> Add Entry
                    </a>
                </li>
                <li class="nav-item">
                    <a href="medialist" class=" text-white nav-link {{ $modelName == 'media' ? 'active' : '' }}">
                        <i class="fa-solid fa-pager" aria-hidden="true"></i> Media
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>

<nav class="navbar navbar-expand-lg px-0" style="background-color:#13214A;">
    <div class="container-fluid">
        <a class="navbar-brand" href="rideslist">EIEV REGISTRATION</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
            <ul class="nav  nav-pills nav-fill ">
                <li class="nav-item">
                    <a href="rideslist" class="text-white nav-link {{ $modelName == 'entry' ? 'active' : '' }}">
                        <i class="fa fa-paw" aria-hidden="true"></i> Entries
                    </a>
                </li>
                <li class="nav-item">
                    <a href="submitentry" class="text-white nav-link {{ $modelName == 'submitentry' ? 'active' : '' }}">
                        <i class="fa fa-list" aria-hidden="true"></i> Add Entry
                    </a>
                </li>
                <li class="nav-item">
                    <a href="swapentry" class=" text-white nav-link {{ $modelName == 'sentry' ? 'active' : '' }}">
                        <i class="fa fa-home" aria-hidden="true"></i> Swap Entry
                    </a>
                </li>
                <li class="nav-item">
                    <a href="rideeligibility" class="text-white nav-link {{ $modelName == 'rechecker' ? 'active' : '' }}">
                        <i class="fa fa-cloud" aria-hidden="true"></i> Rider Eligibity Checker
                    </a>
                </li>
                <li class="nav-item">
                    <a href="horseeligibility" class="text-white nav-link {{ $modelName == 'hechecker' ? 'active' : '' }}">
                        <i class="fa fa-user" aria-hidden="true"></i> Horse Eligibity Checker
                    </a>
                </li>
                
            </ul>
        </div>
        
    </div>
</nav>
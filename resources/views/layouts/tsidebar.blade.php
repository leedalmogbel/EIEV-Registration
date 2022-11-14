<div class="sidebar px-0 col">
    <div class="logo"><img src="/assets/images/dash-logo.png" /></div>
    <ul class="side-menu">
        <li>
            <a href="/rideslist" class="{{ $modelName == 'rlist' ? 'active' : '' }}">
                <i class="fa fa-paw" aria-hidden="true"></i> Rides
            </a>
        </li>
        <li>
            <a href="/rideeligibility" class="{{ $modelName == 'rechecker' ? 'active' : '' }}">
                <i class="fa fa-cloud" aria-hidden="true"></i> Rider Eligibity Checker
            </a>
        </li>
        <li>
            <a href="/horseeligibility" class="{{ $modelName == 'hechecker' ? 'active' : '' }}">
                <i class="fa fa-user" aria-hidden="true"></i> Horse Eligibity Checker
            </a>
        </li>
        <li>
            <a href="/addentry" class="{{ $modelName == 'aentry' ? 'active' : '' }}">
                <i class="fa fa-list" aria-hidden="true"></i> Add Entry
            </a>
        </li>
        <li>
            <a href="/swapentry" class="{{ $modelName == 'sentry' ? 'active' : '' }}">
                <i class="fa fa-home" aria-hidden="true"></i> Swap Entry
            </a>
        </li>
    </ul>
</div>

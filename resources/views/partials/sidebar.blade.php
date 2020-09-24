<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Bible <sup>API</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Events Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCopyrights" aria-expanded="true" aria-controls="collapseCopyrights">
            <i class="fas fa-fw fa-copyright"></i>
            <span>Copyrights</span>
        </a>
        <div id="collapseCopyrights" class="collapse" aria-labelledby="headingCopyrights" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('copyrights.index') }}">Copyrights</a>
                <a class="collapse-item" href="{{ route('copyrights.create') }}">Create</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Events Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTranslations" aria-expanded="true" aria-controls="collapseTranslations">
            <i class="fas fa-fw fa-font"></i>
            <span>Translations</span>
        </a>
        <div id="collapseTranslations" class="collapse" aria-labelledby="headingTranslations" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('translations.index') }}">Translations</a>
                <a class="collapse-item" href="{{ route('translations.create') }}">Create</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>

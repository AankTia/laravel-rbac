<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="{{ Route::currentRouteName() === 'home' ? 'active' : '' }}">
                    <a href="{{ route('home') }}"><img src="{{ asset('assets/img/icons/dashboard.svg') }}" alt="img"><span> Dashboard</span> </a>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/img/icons/users1.svg') }}" alt="img">
                        <span> Users</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="#">User</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/img/icons/time.svg') }}" alt="img">
                        <span> Report</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="#">Report 1</a></li>
                        <li><a href="#">Report 2</a></li>
                        <li><a href="#">Report 3</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);" class="
                        @if (Str::startsWith(Route::currentRouteName(), 'roles.'))
                            active subdrop
                        @endif
                    ">
                        <img src="assets/img/icons/settings.svg" alt="img">
                        <span> Settings</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="#">General Settings</a></li>
                        <li>
                            <a href="{{ route('roles.index') }}" class="{{ Str::startsWith(Route::currentRouteName(), 'roles.') ? 'active' : '' }}">
                                Role
                            </a>
                        </li>
                        <li><a href="#">Permissions</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
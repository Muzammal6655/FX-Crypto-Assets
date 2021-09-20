<nav class="navbar navbar-default navbar-fixed-top admin-pannel-styling">
    <div class="brand logo-sty">
        <a href="{{route('admin.dashboard')}}">
            <img src="{{ asset(env('PUBLIC_URL').'images/logo.png') }}" alt="logo" class="img-responsive logo" style="width: 250px;">
        </a>
        <div id="tour-fullwidth" class="navbar-btn-togl">
            <button type="button" class="btn-toggle-fullwidth"><i class="ti-arrow-circle-left"></i></button>
        </div>
    </div>
    <div class="right-menu-bar">
        <div id="navbar-menu" class="navbar-menu head-sec-des">
            <div class="heading hidden-xs">
                <h1 class="page-title">@yield('title')</h1>
                <p class="page-subtitle">@yield('sub-title')</p>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle user-status-div" data-toggle="dropdown">
                        <div class="user-name-sty">
                            <span>{{ Auth::user()->name }}</span>
                        </div>
                        <div class="user-img-sty">
                            <img src="{{checkImage(asset('storage/admins/profile-images/' . Auth::user()->profile_image),'avatar.png',Auth::user()->profile_image)}}"
                                alt="Avatar">
                        </div>

                    </a>
                    <ul class="dropdown-menu logged-user-menu">
                        <li>
                            <a href="{{ route('admin.profile') }}">
                                <i class="ti-user"></i> <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.auth.logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="ti-power-off"></i> <span>Logout</span>
                            </a>
                        </li>

                        <form id="logout-form" action="{{ route('admin.auth.logout') }}" method="POST"
                            style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </ul>
                </li>
                <li class="xs-visi-btn">
                    <button class="navicon navbar-toggler btn-toggle-fullwidth" type="button" id="tour-fullwidth">
                        <div class="navicon__holder">
                            <div style="display:inline-block">
                                <div class="navicon__line"></div>
                                <div class="navicon__line"></div>
                                <div class="navicon__line"></div>
                            </div>
                        </div>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>
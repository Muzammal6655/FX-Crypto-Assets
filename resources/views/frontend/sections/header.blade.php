<?php
$segment_1 = Request::segment(1);
$segment_2 = Request::segment(2);
?>

<header class="site-header">
    <div class="container-fluid">
        <div class="main-menu">
            <div class="masthead-des">
                <!-- navbar-->
                <nav class="navbar navbar-expand-lg navbar-light main-nav fill">
                    <a class="navbar-brand js-scroll-trigger" href="{{ url('/') }}">
                        <img class="logo-img" src="{{asset('images/logo.svg')}}" alt="logo"></a>
                    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                        data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
                        aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarResponsive">
                        @if (Auth::check())
                            <ul class="navbar-nav list-unstyled">
                                <li class="nav-item active">
                                    <a class="nav-link" href="{{ url('/pages/home') }}" data-menu-name="Home">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{($segment_1 == 'dashboard') ? 'active' : ''}} " href="{{ url('/dashboard') }}" data-menu-name="Dashboard">Dashboard</a>
                                </li>
                                @if ( CheckKYCStatus() ) 
                                <li class="nav-item">
                                    <a class="nav-link {{($segment_1 == 'pools') ? 'active' : ''}} " href="{{ url('/pools') }}" data-menu-name="Pools">Pools</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{($segment_1 == 'deposits') ? 'active' : ''}} " href="{{ url('/deposits') }}" data-menu-name="Deposits">Deposits</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{($segment_1 == 'withdraws') ? 'active' : ''}} " href="{{ url('/withdraws') }}" data-menu-name="Withdraws">Withdraws</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{($segment_1 == 'invite-a-friend') ? 'active' : ''}} " href="{{ url('/invite-a-friend') }}" data-menu-name="Invite A Friend">Invite A Friend</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">More</a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item {{($segment_1 == 'pool-investments') ? 'active' : ''}} " href="{{ url('/pool-investments') }}">Pool Investments</a>

                                        <div class="dropdown-divider"></div>

                                        <a class="dropdown-item {{($segment_1 == 'transactions') ? 'active' : ''}} " href="{{ url('/transactions') }}">Transactions</a>

                                        <div class="dropdown-divider"></div>

                                        <a class="dropdown-item {{($segment_1 == 'balances') ? 'active' : ''}} " href="{{ url('/balances') }}" data-menu-name="Balances">Balances</a>
                                    </div>
                                </li>
                                @endif
                            </ul>
                            <div class="button-wrap">
                                <a class="nav-link btn-login" href="{{ url('/profile') }}">Profile</a>
                                <a class="nav-link btn-header" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        @else
                            <ul class="navbar-nav list-unstyled">
                                <li class="nav-item active">
                                    <a class="nav-link active" href="{{ url('/pages/home') }}" data-menu-name="Home">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/pages/about-us') }}" data-menu-name="About us">About us</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/pages/fees') }}" data-menu-name="Fees">Fees</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/pages/how-to-get-started') }}" data-menu-name="How to Get Started">How to Get Started</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/pages/pool-information/') }}" data-menu-name="Pool Information">Pool Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/pages/contact-us/') }}" data-menu-name="Contact us">Contact us</a>
                                </li>
                            </ul>
                            <div class="button-wrap">
                                <a class="nav-link btn-login" href="{{ url('/login') }}">login</a>
                                <a class="nav-link btn-header" href="{{ url('/register') }}">Register</a>
                            </div>
                        @endif
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
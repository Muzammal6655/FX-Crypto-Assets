<?php
$segment_2 = Request::segment(2);
$segment_3 = Request::segment(3);
?>

<div id="sidebar-nav" class="sidebar ad-pannel-sdbar-sty">
    <nav>
        <ul class="nav" id="sidebar-nav-menu">
            <li>
                <a href="{{route('admin.dashboard')}}" class="{{($segment_2 == 'dashboard') ? 'active' : ''}}">
                    <i class="fa fa-pie-chart"></i><span class="title">Dashboard</span>
                </a>
            </li>

            <?php
            if ($segment_2 == 'roles' || $segment_2 == 'sub-admins') {
                $admins_active_class = 'active';
                $admins_aria_expanded = 'true';
                $admins_div_height = '';
                $admins_div_collapse_class = 'collapse in';
            } else {
                $admins_active_class = 'collapsed';
                $admins_aria_expanded = 'false';
                $admins_div_height = 'height: 0px';
                $admins_div_collapse_class = 'collapse';
            }
            ?>

            @if(have_right('roles-list') || have_right('admins-list'))
                <li class="panel">
                    <a href="#admins" data-toggle="collapse" data-parent="#sidebar-nav-menu"
                       class="{{ $admins_active_class }} drop-menu-links" aria-expanded="{{ $admins_aria_expanded }}">
                        <i class="fa fa-users"></i><span class="title">Admins</span>
                    </a>

                    <div id="admins" class="{{ $admins_div_collapse_class }}"
                         aria-expanded="{{ $admins_aria_expanded }}"
                         style="{{ $admins_div_height }}">
                        <ul class="submenu">
                            @if(have_right('roles-list'))
                                <li>
                                    <a href="{{ url('admin/roles') }}"
                                       class="{{($segment_2 == 'roles') ? 'active' : ''}}">
                                        <i class="fa fa-user-secret"></i><span class="title">Roles</span>
                                    </a>
                                </li>
                            @endif
                            @if(have_right('admins-list'))
                                <li>
                                    <a href="{{ url('admin/sub-admins') }}"
                                       class="{{($segment_2 == 'sub-admins') ? 'active' : ''}}">
                                        <i class="fa fa-user"></i> <span class="title">Sub Admins</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(have_right('investors-list'))
                <li>
                    <a href="{{  url('admin/investors') }}" class="{{($segment_2 == 'investors') ? 'active' : ''}}">
                        <i class="fa fa-users"></i><span class="title">Investors</span>
                    </a>
                </li>
            @endif

            @if(have_right('pools-list'))
                <li>
                    <a href="{{  url('admin/pools') }}" class="{{($segment_2 == 'pools') ? 'active' : ''}}">
                        <i class="fa fa-product-hunt"></i><span class="title">Pools</span>
                    </a>
                </li>
            @endif
            
            @if(have_right('pool-investments-list'))
                <li>
                    <a href="{{  url('admin/pool-investments') }}" class="{{($segment_2 == 'pool-investments') ? 'active' : ''}}">
                        <i class="fa fa-product-hunt"></i><span class="title">Pool Investments</span>
                    </a>
                </li>
            @endif

            @if(have_right('deposits-list'))
                <li>
                    <a href="{{  url('admin/deposits') }}" class="{{($segment_2 == 'deposits') ? 'active' : ''}}">
                        <i class="fa fa-money"></i><span class="title">Deposits</span>
                    </a>
                </li>
            @endif

            @if(have_right('withdraws-list'))
                <li>
                    <a href="{{  url('admin/withdraws') }}" class="{{($segment_2 == 'withdraws') ? 'active' : ''}}">
                        <i class="fa fa-money"></i><span class="title">Withdraws</span>
                    </a>
                </li>
            @endif

            @if(have_right('profits-list'))
                <li>
                    <a href="{{ url('admin/profits') }}" class="{{($segment_2 == 'profits') ? 'active' : ''}}">
                        <i class="fa fa-money"></i><span class="title">Profits</span>
                    </a>
                </li>
            @endif

            @if(have_right('pool-balances-list'))
                <li>
                    <a href="{{ url('admin/pool-balances') }}" class="{{($segment_2 == 'pool-balances') ? 'active' : ''}}">
                        <i class="fa fa-money"></i><span class="title">Pool Balances</span>
                    </a>
                </li>
            @endif

            @if(have_right('email-templates-list'))
                <li>
                    <a href="{{  url('admin/email-templates') }}" class="{{($segment_2 == 'email-templates') ? 'active' : ''}}">
                        <i class="fa fa-envelope"></i><span class="title">Email Templates</span>
                    </a>
                </li>
            @endif
            @if(have_right('security-questions-list'))
                <li>
                    <a href="{{  url('admin/security-questions') }}" class="{{($segment_2 == 'security-questions') ? 'active' : ''}}">
                        <i class="fa fa-envelope"></i><span class="title">Security Questions</span>
                    </a>
                </li>
            @endif

            @if(have_right('site-settings'))
                <li>
                    <a href="{{ url('admin/settings') }}" class="{{($segment_2 == 'settings') ? 'active' : ''}}">
                        <i class="fa fa-cog"></i><span class="title">Site Settings</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>

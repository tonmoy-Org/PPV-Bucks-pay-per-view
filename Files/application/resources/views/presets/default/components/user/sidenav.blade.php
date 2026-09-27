<div class="sidebar">
    <div class="sidebar__inner">
        <div class="sidebar-top-inner">
            <div class="sidebar__logo">
                <a href="{{route('home')}}" class="sidebar__main-logo">
                    <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png', '?' . time()) }}"
                    alt="{{ config('app.name') }}">
                </a>
                <div class="navbar__left">
                    <button class="navbar__expand">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>
                    <button class="sidebar-mobile-menu">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>
                </div>
            </div>
            <div class="sidebar__menu-wrapper">
                <ul class="sidebar__menu p-0">
                    <li class="sidebar-menu-item {{ Route::is('user.home') ? 'active' : '' }}">
                        <a href="{{route('user.home')}}">
                            <i class="menu-icon las la-tachometer-alt"></i>
                            <span class="menu-title">@lang('Dashboard')</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item {{ Route::is('user.plan') ? 'active' : '' }}">
                        <a href="{{route('user.plan')}}">
                            <i class="menu-icon las la-gem"></i>
                            <span class="menu-title">@lang('Membership Plans')</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las la-ad"></i>
                            <span class="menu-title">@lang('PPV Management')</span>
                        </a>
                        <ul class="sidebar-submenu {{ isActiveRoute('user.ptc') ? 'd-block' : '' }}">
                            <li class="sidebar-menu-item {{ Route::is('user.ptc.index') ? 'active' : '' }}">
                                <a href="{{route('user.ptc.index')}}" class="nav-link">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">@lang('Ads')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ Route::is('user.ptc.today.click') ? 'active' : '' }}">
                                <a href="{{route('user.ptc.today.click')}}" class="nav-link">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">@lang('Today Ads Click')</span>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las la-wallet"></i>
                            <span class="menu-title">@lang('Deposit')</span>
                        </a>
                        <ul class="sidebar-submenu {{ (Route::is('user.deposit') || Route::is('user.deposit.history')) ? 'd-block' : '' }}">
                            <li class="sidebar-menu-item {{ Route::is('user.deposit') ? 'active' : '' }}">
                                <a href="{{route('user.deposit')}}" class="nav-link">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">@lang('Deposit Now')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ Route::is('user.deposit.history') ? 'active' : '' }}">
                                <a href="{{route('user.deposit.history')}}" class="nav-link">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">@lang('Deposit Log')</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las la-hand-holding-usd"></i>
                            <span class="menu-title">@lang('Withdraw')</span>
                        </a>
                        <ul class="sidebar-submenu {{ isActiveRoute('user.withdraw') ? 'd-block' : '' }}">
                            <li class="sidebar-menu-item {{ Route::is('user.withdraw') ? 'active' : '' }}">
                                <a href="{{route('user.withdraw')}}" class="nav-link">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">@lang('Withdraw Now')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ Route::is('user.withdraw.history') ? 'active' : '' }}">
                                <a href="{{route('user.withdraw.history')}}" class="nav-link">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">@lang('Withdraw Log')</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-menu-item {{ Route::is('user.transactions') ? 'active' : '' }}">
                        <a href="{{route('user.transactions')}}">
                            <i class="menu-icon fa-solid fa-filter-circle-dollar"></i>
                            <span class="menu-title">@lang('Transactions')</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las fas fa-headset"></i>
                            <span class="menu-title">@lang('Support Tickets')</span>
                        </a>
                        <ul class="sidebar-submenu {{ isActiveRoute('ticket') ? 'd-block' : '' }}">
                            <li class="sidebar-menu-item sidebar-menu-sub-menu {{ Route::is('ticket') ? 'active' : '' }}">
                                <a href="{{ route('ticket') }}" class="nav-link">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">@lang('My Tickets')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item sidebar-menu-sub-menu {{ Route::is('ticket.open') ? 'active' : '' }}">
                                <a href="{{ route('ticket.open') }}" class="nav-link">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">@lang('New Ticket')</span>
                                </a>
                            </li>
                        </ul>
                    </li>


                </ul>
            </div>
        </div>
        <div class="sidebar-support-box d-grid align-items-center bg-img"
        data-background="{{ asset($activeTemplateTrue . 'images/element/sidebar-bg.png') }}">
        <div class="sidebar-support-icon">
            <i class="fas fa-question-circle"></i>
        </div>
        <div class="sidebar-support-content">
            <h4 class="title">@lang('Need Help')?</h4>
            <p>@lang('Please contact our support').</p>
            <div class="sidebar-support-btn">
                <a href="{{route('ticket.open')}}" class="btn btn--base w-100 mt-2">@lang('Get Support')</a>
            </div>
        </div>
    </div>
    </div>
</div>

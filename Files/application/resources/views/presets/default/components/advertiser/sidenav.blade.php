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
                    <li class="sidebar-menu-item {{ Route::is('advertiser.home') ? 'active' : '' }}">
                        <a href="{{route('advertiser.home')}}">
                            <i class="menu-icon las la-tachometer-alt"></i>
                            <span class="menu-title">@lang('Dashboard')</span>
                        </a>
                    </li>


                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las la-ad"></i>
                            <span class="menu-title">@lang('Ads Management')</span>
                        </a>
                        <ul class="sidebar-submenu {{ isActiveRoute('advertiser.ptc') ? 'd-block' : '' }}">
                            <li class="sidebar-menu-item sidebar-menu-sub-menu {{ Route::is('advertiser.ptc.index') ? 'active' : '' }}">
                                <a href="{{route('advertiser.ptc.index')}}" class="nav-link">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">@lang('All Ads')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item sidebar-menu-sub-menu {{ Route::is('advertiser.ptc.create') ? 'active' : '' }}">
                                <a href="{{route('advertiser.ptc.create')}}" class="nav-link">
                                    <i class="menu-icon  fas fa-plus"></i>
                                    <span class="menu-title">@lang('Create')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item sidebar-menu-sub-menu {{ Route::is('advertiser.ptc.active') ? 'active' : '' }}">
                                <a href="{{route('advertiser.ptc.active')}}" class="nav-link">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">@lang('Active')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item sidebar-menu-sub-menu {{ Route::is('advertiser.ptc.pending') ? 'active' : '' }}">
                                <a href="{{route('advertiser.ptc.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">@lang('Pending')</span>
                                </a>
                            </li>


                        </ul>
                    </li>


                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las la-dollar-sign"></i>
                            <span class="menu-title">@lang('Deposit')</span>
                        </a>
                        <ul class="sidebar-submenu {{ isActiveRoute('advertiser.deposit') ? 'd-block' : '' }}">
                            <li class="sidebar-menu-item sidebar-menu-sub-menu {{ Route::is('advertiser.deposit') ? 'active' : '' }}">
                                <a href="{{route('advertiser.deposit')}}" class="nav-link">
                                    <i class="menu-icon las la-plus"></i>
                                    <span class="menu-title">@lang('Deposit Now')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item sidebar-menu-sub-menu {{ Route::is('advertiser.deposit.history') ? 'active' : '' }}">
                                <a href="{{route('advertiser.deposit.history')}}" class="nav-link">
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
                        <ul class="sidebar-submenu {{ isActiveRoute('advertiser.withdraw') ? 'd-block' : '' }}">
                            <li class="sidebar-menu-item {{ Route::is('advertiser.withdraw') ? 'active' : '' }}">
                                <a href="{{route('advertiser.withdraw')}}" class="nav-link">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">@lang('Withdraw Now')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ Route::is('advertiser.withdraw.history') ? 'active' : '' }}">
                                <a href="{{route('advertiser.withdraw.history')}}" class="nav-link">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">@lang('Withdraw Log')</span>
                                </a>
                            </li>
                        </ul>
                    </li>


                    <li class="sidebar-menu-item {{ Route::is('advertiser.get.packages') || Route::is('plan') ? 'active' : '' }}">
                        <a href="{{route('plan')}}">
                            <i class="menu-icon las la-gem"></i>
                            <span class="menu-title">@lang('Membership Plans')</span>
                            <span class="badge bg-danger rounded-pill ms-auto" style="font-size: 0.65rem;">@lang('HOT')</span>
                        </a>
                    </li>


                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las la-percent"></i>
                            <span class="menu-title">@lang('Affiliation')</span>
                        </a>
                        <ul class="sidebar-submenu {{ isActiveRoute('advertiser.reffered') ? 'd-block' : '' }}">
                            <li class="sidebar-menu-item {{ Route::is('advertiser.reffered') ? 'active' : '' }}">
                                <a href="{{route('advertiser.reffered')}}" class="nav-link">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">@lang('Referred')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ Route::is('advertiser.reffered.commission') ? 'active' : '' }}">
                                <a href="{{route('advertiser.reffered.commission')}}" class="nav-link">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">@lang('Commission Logs')</span>
                                </a>
                            </li>

                        </ul>
                    </li>


                    <li class="sidebar-menu-item {{ Route::is('advertiser.transactions') ? 'active' : '' }}">
                        <a href="{{route('advertiser.transactions')}}">
                            <i class="menu-icon fa-solid fa-filter-circle-dollar"></i>
                            <span class="menu-title">@lang('Transactions')</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item sidebar-dropdown">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las fas fa-headset"></i>
                            <span class="menu-title">@lang('Support Tickets')</span>
                        </a>
                        <ul class="sidebar-submenu {{ isActiveRoute('advertiser.ticket') ? 'd-block' : '' }}">
                            <li class="sidebar-menu-item sidebar-menu-sub-menu {{ Route::is('advertiser.ticket') ? 'active' : '' }}">
                                <a href="{{ route('advertiser.ticket') }}" class="nav-link">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">@lang('My Tickets')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item sidebar-menu-sub-menu {{ Route::is('advertiser.ticket.open') ? 'active' : '' }}">
                                <a href="{{ route('advertiser.ticket.open') }}" class="nav-link">
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
                <a href="{{route('advertiser.ticket.open')}}" class="btn btn--base w-100 mt-2">@lang('Get Support')</a>
            </div>
        </div>
    </div>
    </div>
</div>

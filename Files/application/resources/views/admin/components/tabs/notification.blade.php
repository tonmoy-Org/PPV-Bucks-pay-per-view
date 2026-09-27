<div class="row">
    <div class="col">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.report.notification.history') ? 'active' : '' }}"
                    href="{{route('admin.report.notification.history')}}">@lang('User')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.advertiser.report.notification.history') ? 'active' : '' }}"
                    href="{{route('admin.advertiser.report.notification.history')}}">@lang('Advertiser')
                </a>
            </li>
        </ul>
    </div>
</div>

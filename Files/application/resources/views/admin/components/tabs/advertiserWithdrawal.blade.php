<div class="row">
    <div class="col">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.withdraw.advertiser.pending') ? 'active' : '' }}"
                    href="{{route('admin.withdraw.advertiser.pending')}}">@lang('Pending')
                    @if($pendingadvertiserWithdrawCount)
                    <span class="badge rounded-pill bg--white text-muted">{{$pendingadvertiserWithdrawCount}}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.withdraw.approved') ? 'active' : '' }}"
                    href="{{route('admin.withdraw.advertiser.approved')}}">@lang('Approved')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.withdraw.rejected') ? 'active' : '' }}"
                    href="{{route('admin.withdraw.advertiser.rejected')}}">@lang('Rejected')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.withdraw.log') ? 'active' : '' }}"
                    href="{{route('admin.withdraw.advertiser.log')}}">@lang('All')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.withdraw.advertiser.method.index') ? 'active' : '' }}"
                    href="{{route('admin.withdraw.advertiser.method.index')}}">@lang('Withdrawal Methods')
                </a>
            </li>
        </ul>
    </div>
</div>

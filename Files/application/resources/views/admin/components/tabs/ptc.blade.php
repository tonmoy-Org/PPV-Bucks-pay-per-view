<div class="row">
    <div class="col">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.ptc.index') ? 'active' : '' }}"
                    href="{{route('admin.ptc.index')}}">@lang('All')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.ptc.active') ? 'active' : '' }}"
                    href="{{route('admin.ptc.active')}}">@lang('Active')
                    @if($activePtcCount)
                    <span class="badge rounded-pill bg--white text-muted">{{$activePtcCount}}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.ptc.pending') ? 'active' : '' }}"
                    href="{{route('admin.ptc.pending')}}">@lang('Pending')
                    @if($pendingPtcCount)
                    <span class="badge rounded-pill bg--white text-muted">{{$pendingPtcCount}}</span>
                    @endif
                </a>
            </li>
        </ul>
    </div>
</div>

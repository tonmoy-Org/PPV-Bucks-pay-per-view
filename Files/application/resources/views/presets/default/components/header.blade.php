@php
    $languages = App\Models\Language::all();
    $pages = App\Models\Page::where('tempname', $activeTemplate)->get();
@endphp
<!-- ==================== Header End Here ==================== -->
<div class="{{gs()->homepage ==1 ? 'header':'header-two'}}" id="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand logo" href="{{route('home')}}">   <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png', '?' . time()) }}" alt="{{ config('app.name') }}"></a>
            <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span id="hiddenNav"><i class="las la-bars"></i></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu mx-auto ps-lg-4 ps-0">
                    @php
                        $hasPlanPage = $pages->contains(function($p) {
                            return strtolower($p->slug) == 'plan' || strtolower($p->slug) == 'plans' || strtolower($p->name) == 'plan';
                        });
                        $planRendered = false;
                    @endphp

                    @foreach($pages as $page)
                        <li class="nav-item">
                            <a href="{{ $page->slug == '/' ? route('home') : route('pages', [$page->slug]) }}" class="nav-link {{ (Request::url() == url('/') . '/' . $page->slug || (request()->routeIs('home') && $page->slug == '/')) ? 'active' : '' }}" aria-current="page">{{ __($page->name) }}</a>
                        </li>

                        @if(strtolower($page->name) == 'about' && !$hasPlanPage && !$planRendered)
                            @php $planRendered = true; @endphp
                            <li class="nav-item">
                                <a href="{{ route('plan') }}" class="nav-link {{ request()->routeIs('plan') ? 'active' : '' }}" aria-current="page">@lang('Plan')</a>
                            </li>
                        @endif
                    @endforeach

                    @if(!$hasPlanPage && !$planRendered)
                        <li class="nav-item">
                            <a href="{{ route('plan') }}" class="nav-link {{ request()->routeIs('plan') ? 'active' : '' }}" aria-current="page">@lang('Plan')</a>
                        </li>
                    @endif
                </ul>
                <div class="nav-end d-lg-flex d-block align-items-center py-lg-0 py-1">
                    <div class="d-flex mx-2">
                        <div class="icon">
                            <i class="fa-solid fa-globe"></i>
                        </div>
                        <select class="select-dir langSel form--select">
                            @foreach ($languages as $language)
                            <option value="{{ $language->code }}"
                                @if (Session::get('lang') === $language->code) selected @endif>
                                {{ __($language->name) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mx-lg-0 mx-2">
                        @auth
                        <a class="btn btn--base mt-2 mt-lg-0" href="{{route('user.home')}}">@lang('Dashboard') <i class="fas fa-tachometer-alt"></i></a>
                        @endif

                        @auth('advertiser')
                        <a class="btn btn--base mt-2 mt-lg-0" href="{{route('advertiser.home')}}">@lang('Dashboard') <i class="fas fa-tachometer-alt"></i></a>
                        @endauth

                        @if (!(auth()->id() || authAdvertiserId()))
                            <a class="btn btn--base mt-3 mt-lg-0" href="{{route('user.login')}}">@lang('Sign In') <i class="fas fa-sign-in-alt"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- ==================== Header End Here ==================== -->

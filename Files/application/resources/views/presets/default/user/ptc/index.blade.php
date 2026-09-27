@extends($activeTemplate.'layouts.user.master')
@section('content')
<div class="body-wrapper">
    <div class="body-area">
        <div class="ad-list">
            <div class="top-area mb-3">
                <h5 class="text-center">@lang('You have viewed') <span class="text--base fw-bold">{{$todayTotalView}} / {{$dailyLimit}}</span> @lang('advertisements in the last') {{ $resetHours ?? 12 }} @lang('hours')</h5>
            </div>
            
            <div class="alert alert-warning d-flex align-items-center justify-content-between flex-wrap gap-2 p-3 mb-4 rounded" style="background: linear-gradient(45deg, #fff8e6, #fff3cd); border-left: 4px solid #ffc107; color: #856404;">
                <div>
                    <i class="fas fa-crown text-warning me-2 fs-5"></i>
                    <strong>@lang('Want to unlock high-paying exclusive ads?')</strong> @lang('Upgrade your plan to get higher earnings and access premium ads!')
                </div>
                <a href="{{ route('user.plan') }}" class="btn btn-sm btn--base text-nowrap"><i class="fas fa-rocket me-1"></i> @lang('Upgrade Plan')</a>
            </div>
            <div class="row gy-4">
                @forelse($ads as $ad)
                @if(!in_array($ad->id, $viewed))
                <div class="col-xl-3 col-lg-4 col-12">
                    <div class="card">
                        <div class="d-flex justify-content-between">
                            <div class="content">
                                <h5>
                                    @if (strlen(__(@$ad->title)) > 15)
                                    {{ substr(__(@$ad->title), 0, 15) . '...' }}
                                    @else
                                    {{__(@$ad->title) }}
                                    @endif
                                </h5>
                                <p>@lang('Ads duration :') {{@$ad->duration}} @lang('s')</p>
                            </div>
                            <div class="price">
                                <h4>{{$general->cur_sym}}{{formatPrice(isset($adRate) ? $adRate : $general->ptc_amount)}}</h4>
                            </div>
                        </div>
                        <div>
                            <a href="{{route('user.ptc.show',encrypt($ad->id.'|'.auth()->user()->id))}}"
                                class="btn btn--base">@lang('View Ads') <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                @empty
                <h2 class="text-center">{{__($emptyMessage)}}</h2>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

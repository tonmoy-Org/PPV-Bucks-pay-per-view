@php
$ptc = getContent('ptc.content', true);
$ptcs = App\Models\Ptc::where('status',1)->latest()->inRandomOrder()->limit(4)->get();
@endphp
<!-- ==================== provide Start ==================== -->
<section class="provide py-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-12 my-auto">
                <div class="title">
                    <h4>{{__(@$ptc->data_values->heading)}}</h4>
                    <p>{{__(@$ptc->data_values->sub_heading)}}</p>
                    <a href="{{route('user.login')}}" class="btn btn--base mt-4">@lang('Get Started') <i class="las la-arrow-alt-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-7 col-12">
                <div class="row gy-4">
                    @foreach($ptcs as $ptc)
                    <div class="col-lg-6 col-md-6 col-12 wow animate__animated animate__fadeInDown" data-wow-delay="0.6s">
                        <div class="card">
                            <div>
                                <h4>{{formatPrice($general->ptc_amount)}} {{__($general->cur_text)}}</h4>
                                <p>@lang('Title'): {{__(@$ptc->title)}}</p>
                                <p>@lang('Ads Duration'): {{__(@$ptc->duration)}}@lang('s')</p>
                                <div>
                                    @if(auth()->check())
                                    <a href="{{route('user.ptc.show',encrypt($ptc->id.'|'.auth()->user()->id))}}" class="btn btn--base mt-3">@lang('Click')</a>
                                    @else
                                    <a href="{{route('user.login')}}" class="btn btn--base mt-3"><i class="las la-hand-point-up"></i>@lang('Click')</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==================== provide End ==================== -->

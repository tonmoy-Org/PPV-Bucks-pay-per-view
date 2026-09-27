@php
$banner = getContent('banner.content', true);
$highlightedText = $banner->data_values->highlighted_heading_text;
@endphp
<!--========================== Banner Section Start ==========================-->
<section class="banner-section">

    <div class="shape1">
        <img src="{{ asset($activeTemplateTrue . 'images/shape/shape1.png') }}" alt="@lang('image')">
    </div>
    <div class="shape2">
        <img src="{{ asset($activeTemplateTrue . 'images/shape/shape2.png') }}" alt="@lang('image')">
    </div>

    <div class="banner-thumb">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-7 col-12 my-auto wow animate__animated animate__fadeInLeft" data-wow-delay="0.6s">
                    <div class="content">
                        <h5>
                            <img src="{{ asset($activeTemplateTrue . 'images/shape/banner_icon.png') }}" alt="@lang('image')"> {{__(@$banner->data_values->top_heading)}}
                        </h5>
                        <h3>
                            {!! str_replace(__(@$highlightedText),"<span>$highlightedText</span>",__(@$banner->data_values->heading) )!!}
                        </h3>
                        <p>
                            @if(strlen(__(@$banner->data_values->sub_heading)) >200)
                            {{substr(__(@$banner->data_values->sub_heading), 0,200).'...' }}
                            @else
                            {{__(@$banner->data_values->sub_heading)}}
                            @endif
                        </p>
                        <div class="action">
                            <a href="{{route('user.login')}}" class="btn btn--base">@lang('Earn Now') <i class="fa-solid fa-hand-holding-dollar"></i></a>
                            <a href="{{route('advertiser.login')}}" class="btn btn--base d-md-block d-none">@lang('Advertiser') <i class="fas fa-ad"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-12 wow animate__animated animate__fadeInRight" data-wow-delay="0.6s">
                    <img src="{{ getImage(getFilePath('frontend') .'/'.'banner/' . @$banner->data_values->banner_image) }}" class="img-fluid d-flex mx-auto mx-lg-0" alt="@lang('image')">
                </div>
            </div>
        </div>
    </div>

</section>
<!--========================== Banner Section End ==========================-->

@php
$banner = getContent('banner_two.content', true);
$highlightedText = $banner->data_values->highlighted_heading_text;
@endphp

<!--========================== Banner Section Start ==========================-->
<section class="banner-section-two bg-img bg-overlay" data-background="{{ getImage(getFilePath('frontend') .'/'.'banner_two/' . @$banner->data_values->banner_image) }}">
    <div class="banner-thumb">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-10 my-auto wow animate__animated animate__fadeIn" data-wow-delay="0.6s">
                    <div class="content">
                        <h5><img src="{{ asset($activeTemplateTrue . 'images/shape/banner_icon.png') }}" alt="@lang('image')"> {{__(@$banner->data_values->top_heading)}}</h5>
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
                            <a href="{{route('user.login')}}" class="btn btn--base"><i class="fa-solid fa-hand-holding-dollar"></i> @lang('Earn Now')</a>
                            <a href="{{route('advertiser.login')}}" class="btn btn--base d-md-block d-none"><i class="fas fa-ad"></i> @lang('Advertiser')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--========================== Banner Section End ==========================-->

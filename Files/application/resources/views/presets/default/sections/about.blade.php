@php
$about = getContent('about.content', true);
$abouts = getContent('about.element', false,4);
@endphp
<!--========================== About Start ==========================-->
<section class="about py-80">
    <div class="shape1">
        <img src="{{ asset($activeTemplateTrue . 'images/shape/shape3.png') }}" alt="@lang('image')">
    </div>
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-6 col-12 thumb mb-lg-0 mb-3 wow animate__animated animate__fadeInLeft"
                data-wow-delay="0.6s">
                <img src="{{ getImage(getFilePath('frontend') .'/'.'about/' . @$about->data_values->about_image) }}" alt="@lang('image')">
            </div>
            <div class="col-lg-6 col-12 my-auto wow animate__animated animate__fadeInRight" data-wow-delay="0.6s">
                <div class="title-two">
                    <h4>{{__(@$about->data_values->heading)}}</h4>
                    <p>{{__(@$about->data_values->description)}}</p>
                </div>
                <div class="info">
                    <div class="row">
                        @foreach($abouts  as $item)
                        <div class="col-md-6 col-lg-12 col-xl-6 col-12">
                            <p><i class="fas fa-check-circle"></i>{{__(@$item->data_values->content)}}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{url('/about')}}" class="btn btn--base">@lang('Know More') <i class="las la-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--========================== About End ==========================-->

@php
$testimonial = getContent('testimonial.content', true);
$testimonials = getContent('testimonial.element', false);
@endphp
<!--========================== Testimonial Start ==========================-->
<section class="testimonial py-80">
    <div class="container">
        <div class="title">
            <h4>{{__(@$testimonial->data_values->heading)}}</h4>
            <p>{{__(@$testimonial->data_values->sub_heading)}}</p>
        </div>
        <div class="testimonial-slider wow animate__animated animate__fadeInUp" data-wow-delay="0.6s">
            @foreach($testimonials as $item)
            {{-- @dd($item); --}}
            <div class="card">
                <div class="star">
                    @php $starIcons = showRatings(@$item->data_values->star_count);@endphp
                    {!! $starIcons !!}
                </div>
                <div class="content">
                    @if (strlen(__(@$item->data_values->description)) >150)
                        {{ substr(__(@$item->data_values->description), 0, 150) . '...' }}
                    @else
                        {{__(@$item->data_values->description) }}
                    @endif
                </div>
                <div class="profile d-flex">
                    <div>
                        <img src="{{getImage(getFilePath('frontend').'/'.'testimonial/'.@$item->data_values->client_image)}}" alt="@lang('image')">
                    </div>
                    <div>
                        <h5>{{__(@$item->data_values->name)}}</h5>
                        <p>{{__(@$item->data_values->designation)}}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!--========================== Testimonial End ==========================-->

@php
$provide = getContent('we_provide.content', true);
$provides = getContent('we_provide.element', false,4);
@endphp
<!-- ====================  Services Start ==================== -->
<section class="services py-80">
    <div class="container">
        <div class="title">
            <h4>{{__(@$provide->data_values->heading)}}</h4>
            <p>{{__(@$provide->data_values->sub_heading)}}</p>
        </div>
        <div class="row gy-5 mt-3 wow animate__animated animate__fadeInUp" data-wow-delay="0.6s">
            @foreach($provides as $item)
            <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                <div class="card">
                    <div class="icon">
                      @php echo $item->data_values->icon; @endphp
                    </div>
                    <div class="content">
                        <h4>{{__(@$item->data_values->title)}}</h4>
                        <p>
                            @if(strlen(__(@$item->data_values->short_description)) >75)
                            {{ substr(__(@$item->data_values->short_description), 0, 75) . '...' }}
                            @else
                            {{__(@$item->data_values->short_description) }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- ====================  Services End ==================== -->

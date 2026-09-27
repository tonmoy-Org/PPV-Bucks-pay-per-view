@php
$counters = getContent('counter.element', false,4);
$counter = getContent('counter.content', true);
@endphp
<!-- ==================== counter start ==================== -->
<section class="counter py-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-12">
                <div class="title">
                    <h4>{{__(@$counter->data_values->heading)}}</h4>
                    <p>{{__(@$counter->data_values->sub_heading)}} </p>
                </div>
            </div>
            <div class="col-lg-8 col-12 my-auto">
                <div class="row gy-4">
                    @foreach($counters as $item)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                        <div class="card">
                            <div class="counterup-item">
                                <h3>
                                    <span class="odometer" data-odometer-final="{{@$item->data_values->counter_digit}}">1</span>{{__(@$item->data_values->symbol)}}
                                </h3>
                                <h6>{{__(@$item->data_values->title)}}</h6>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==================== counter end ==================== -->

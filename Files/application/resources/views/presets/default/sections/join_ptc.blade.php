@php
    $joinPtc = getContent('join_ptc.content', true);
@endphp
<!-- ==================== Join Start ==================== -->
<section class="join pb-80">
    <div class="container">
        <div class="box">
            <div class="d-lg-flex d-md-flex d-block justify-content-between">
                <h4>{{__(@$joinPtc->data_values->title)}}</h4>
                <a href="{{route('user.ptc.index')}}" class="btn btn--base mt-3 mt-md-0 mt-lg-0">@lang('Join ClickBucks') <i class="las la-arrow-alt-circle-right"></i></a>
            </div>
        </div>
    </div>
</section>
<!-- ==================== Join End ==================== -->

@extends($activeTemplate.'layouts.frontend')
@section('content')
<!--=======-** Term and Conditions start **-=======-->
<section class="py-80 policy">
    <div class="thumb">
        <img src="{{ asset($activeTemplateTrue . 'images/shape/shape2.png') }}" alt="@lang('image')">
    </div>
    <div class="container">
        <div class="row body">
            <div class="col-12">
                <div>
                    <div class="info">
                        <h4>{{ __($pageTitle) }}</h4>
                        <div class="wyg">
                            @php
                                echo $policy->data_values->details
                            @endphp
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!--=======-** Term and Conditions End **-=======-->

@endsection

@push('style')
<style>
    .wyg h1, h2, h3, h4{
        color:#383838;
    }
    .wyg strong{
        color:#383838
    }
    .wyg p{
        color: #666666
    }
    .wyg ul{
        margin-left: 40px
    }
    .wyg ul li{
        list-style-type: disc;
        color: #666666
    }
    .section-title{
        font-size: 30px;
        margin-bottom: 0;
    }
</style>
@endpush

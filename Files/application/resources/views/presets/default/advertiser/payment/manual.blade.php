@extends(isset($layout) ? $layout : (authAdvertiser() ? $activeTemplate.'layouts.advertiser.master' : $activeTemplate.'layouts.user.master'))

@section('content')
<div class="body-wrapper">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <div class="body-area">
                <form action="{{ authAdvertiser() ? route('advertiser.deposit.manual.update') : route('user.deposit.manual.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-body">
                        <div>
                            <h4>{{__($pageTitle)}}</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center mb-3">
                                <p class="text-center mt-2">@lang('You have requested') <b class="text-success">{{ showAmount($data['amount']) }} {{__($general->cur_text)}}</b> , @lang('Please pay')
                                    <b class="text-success">{{showAmount($data['final_amo']) .' '.$data['method_currency'] }} </b> @lang('for successful payment')
                                </p>
                                <h5 class="text-center mb-3">@lang('Please follow the instruction below')</h5>
                                <div class="my-3 p-3 rounded-3 bg-light text-center">@php echo $data->gateway->description @endphp</div>
                            </div>

                            <x-custom-form identifier="id" identifierValue="{{ $gateway->form_id }}"></x-custom-form>

                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn--base">@lang('Pay Now')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

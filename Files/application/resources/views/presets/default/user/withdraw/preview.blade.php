@extends($activeTemplate.'layouts.user.master')
@section('content')
<div class="body-wrapper">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-10 col-12">
            <div class="body-area">
                <form action="{{route('user.withdraw.submit')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-body">
                        <div>
                            <h4>{{__($pageTitle)}}</h4>
                        </div>
                        <div class="row">
                            <div class="mb-2">
                                @php
                                echo $withdraw->method->description;
                                @endphp
                            </div>
                            <x-custom-form identifier="id" identifierValue="{{ $withdraw->method->form_id }}">
                            </x-custom-form>
                            @if (auth()->user()->ts)
                            <div class="form-group mb-3">
                                <label>@lang('Google Authenticator Code')</label>
                                <input type="text" name="authenticator_code" class="form-control form--control" required>
                            </div>
                            @endif
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn--base">@lang('Submit')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

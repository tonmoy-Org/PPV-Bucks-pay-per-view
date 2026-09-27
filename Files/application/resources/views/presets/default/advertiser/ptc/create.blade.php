@extends($activeTemplate.'layouts.advertiser.master')
@section('content')

<div class="body-wrapper">
    <div class="table-content">
        <div class="row justify-content-end mb-3">
            <div class="col-lg-4 col-md-8 col-12 text-end">
                <a class="btn btn--base btn--sm" href="{{route('advertiser.ptc.index')}}"> <i class="fas fa-backward"></i> @lang('Back') </a>
            </div>
        </div>
        <div class="body-area">
            <div class="form-body">
                <form role="form" method="POST" action="{{route('advertiser.ptc.store')}}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="form--label">@lang('Title') <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form--control" value="{{ old('title') }}"
                                placeholder="@lang('Title')" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="form--label">@lang('Duration') <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="duration" class="form--control form-control" value="{{ old('duration') }}"
                                    placeholder="@lang('Duration')" required>
                                <div class="input-group-text">@lang('SECONDS')</div>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="form--label">@lang('Maximum Show') <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="max_show" class="form-control form--control" value="{{ old('max_show') }}"
                                    placeholder="@lang('Maximum Show') " required>
                                <div class="input-group-text">@lang('Times')</div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-5 mt-5 border-top">
                        <div class="form-group col-md-4">
                            <label class="form--label">@lang('Advertisement Type') <span class="text-danger">*</span></label>
                            <select class="form-select select" id="ads_type" name="ads_type" required>
                                <option value="1" {{ old('ads_type')==1?'selected':'' }}>@lang('Link / URL')</option>
                                <option value="2" {{ old('ads_type')==2?'selected':'' }}>@lang('Banner / Image')
                                </option>
                                <option value="3" {{ old('ads_type')==3?'selected':'' }}>@lang('Script / Code')</option>
                                <option value="4" {{ old('ads_type')==4?'selected':'' }}>@lang('Youtube Embeded Link')
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-8" id="websiteLink">
                            <label class="form--label">@lang('Link') <span class="text-danger">*</span></label>
                            <input type="text" name="website_link" class="form--control"
                                value="{{ old('website_link') }}" placeholder="@lang('http://example.com')">
                        </div>
                        <div class="form-group col-md-8" id="youtube">
                            <label class="form--label"> @lang('Youtube Embeded Link')<span class="text-danger">*</span></label>
                            <input type="text" name="youtube" class="form--control" value="{{ old('youtube') }}"
                                placeholder="@lang('https://www.youtube.com/embed/your_code')">
                        </div>
                        <div class="form-group col-md-8 d-none" id="bannerImage">
                            <label class="form--label">@lang('Banner')</label>
                            <input type="file" class="form--control" name="image">
                        </div>
                        <div class="form-group col-md-8 d-none" id="script">
                            <label class="form--label">@lang('Script') <span class="text-danger">*</span></label>
                            <textarea name="script" class="form-control">{{ old('script') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group col-md-12 mt-3 text-end">
                        <button type="submit" class="btn btn--base">@lang('Create') <i class="fas fa-rectangle-ad"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection


@push('script')
<script>
    (function ($) {
        "use strict";
        $('#ads_type').change(function () {
            var adType = $(this).val();
            if (adType == 1) {
                $("#websiteLink").removeClass('d-none');
                $("#bannerImage").addClass('d-none');
                $("#script").addClass('d-none');
                $("#youtube").addClass('d-none');
            } else if (adType == 2) {
                $("#bannerImage").removeClass('d-none');
                $("#websiteLink").addClass('d-none');
                $("#script").addClass('d-none');
                $("#youtube").addClass('d-none');
            } else if (adType == 3) {
                $("#bannerImage").addClass('d-none');
                $("#websiteLink").addClass('d-none');
                $("#script").removeClass('d-none');
                $("#youtube").addClass('d-none');
            } else {
                $("#bannerImage").addClass('d-none');
                $("#websiteLink").addClass('d-none');
                $("#script").addClass('d-none');
                $("#youtube").removeClass('d-none');
            }
        }).change();
    })(jQuery);
</script>
@endpush




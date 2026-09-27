@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form role="form" method="POST" action="{{route('admin.ptc.update',$ptc->id)}}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="ads_type" value="{{$ptc->ads_type}}">
                    <div class="row">
                        <div class="form-group col-md-12 mt-4">
                            <label>@lang('Status') </label>
                            <label class="switch m-0">
                                <input type="checkbox" class="toggle-switch" name="status" {{ $ptc->status ?
                                'checked' : null }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>@lang('Title')</label>
                            <input type="text" name="title" class="form-control" value="{{__($ptc->title) }}"
                                placeholder="@lang('Title')" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label>@lang('Duration')</label>
                            <div class="input-group">
                                <input type="number" name="duration" class="form-control"
                                    value="{{__($ptc->duration) }}" placeholder="@lang('Duration')" required>
                                <div class="input-group-text">@lang('SECONDS')</div>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label>@lang('Maximum Show')</label>
                            <div class="input-group">
                                <input type="number" name="max_show" class="form-control"
                                    value="{{ __($ptc->max_show) }}" placeholder="@lang('Maximum Show') " required>
                                <div class="input-group-text">@lang('Times')</div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center pt-5 mt-5 border-top">
                        <div class="form-group col-md-4 ">
                            @if($ptc->ads_type==2)
                            <span class="font-weight-normal text--small badge badge--success"><i
                                    class="fa fa-image"></i> @lang('Image')</span>
                            @endif
                            @if($ptc->ads_type==1)
                            <td><span class="badge badge--success"><i class="las la-link"></i></span></td>
                            @endif
                            @if($ptc->ads_type==3)
                            <td><span class="badge badge--success"><i class="las la-comment-alt"></i></span></td>
                            @endif
                            @if($ptc->ads_type==4)
                            <td><span class="badge badge--success"><i class="las la-link"></i></span></td>
                            @endif
                        </div>


                        @if($ptc->ads_type == 1)
                        <div class="form-group col-md-8">
                            <label>@lang('Link') <span class="text-danger">*</span></label>
                            <input type="text" name="website_link" class="form-control form-control-lg"
                                value="{{ $ptc->ads_body }}" placeholder="@lang('http://example.com')">
                        </div>
                        @elseif($ptc->ads_type == 2)

                        <div class="form-group col-md-4 ">
                            <label>@lang('Banner')</label>
                            <input type="file" class="form-control form-control-lg" name="image">
                        </div>

                        <div class="form-group col-md-4 ">

                            <label>@lang('Current Banner :')</label>
                            <img src="{{ getImage(getFilePath('ptc').'/'.$ptc->ads_body) }}" class="rounded"
                                style="width:100px" alt="*">

                        </div>

                        @else

                        <div class="form-group col-md-8">
                            <label>@lang('Script') <span class="text-danger">*</span></label>
                            <textarea name="script" class="form-control form-control-lg">{{ $ptc->ads_body }}</textarea>
                        </div>

                        @endif
                        <div class="form-group col-md-12 mt-3 text-end">
                            <button type="submit" class="btn btn-global bg--primary">@lang('Submit')</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="{{route('admin.ptc.index')}}" type="button" class="btn btn-sm btn--primary "><i
        class="las la-undo"></i>@lang('Back')</a>
@endpush

@extends('admin.layouts.app')
@section('panel')
<div class="d-flex flex-wrap justify-content-end mb-3">
    <div class="d-inline">
        <div class="input-group justify-content-end">
            <input type="text" name="search_table" class="form-control bg--white"
                placeholder="@lang('Search Ad')...">
            <button class="btn btn--primary input-group-text"><i class="fa fa-search"></i></button>
        </div>
    </div>
</div>
@include('admin.components.tabs.ptc')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two custom-data-table">
                        <thead>
                            <tr>
                                <th>@lang('Title')</th>
                                <th>@lang('User')</th>
                                <th>@lang('Duration')</th>
                                <th>@lang('Max Show')</th>
                                <th>@lang('Showed')</th>
                                <th>@lang('Remain')</th>
                                <th>@lang('Image/Link/Script')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ptcs as $ptc)
                            <tr>
                                <td>{{__($ptc->title)}}</td>
                                <td>
                                    @if(@$ptc->user->username)
                                    <span>{{ @$ptc->user->username }}</span>
                                    @else
                                    <span>@lang('Admin')</span>
                                    @endif
                                </td>
                                <td>{{$ptc->duration}} </td>
                                <td>{{$ptc->max_show}}</td>
                                <td>{{$ptc->showed}}</td>
                                <td>{{$ptc->remain}}</td>

                                @if(in_array($ptc->ads_type, [1, 4]))
                                <td><span class="badge badge--success" title="{{$ptc->ads_body}}"><i class="las la-link"></i></span></td>
                                @endif

                                @if($ptc->ads_type == 2)
                                    <td><img class="rounded" src="{{ getImage(getFilePath('ptc').'/'.$ptc->ads_body) }}" alt="" style="width:50px"></td>
                                @endif

                                @if($ptc->ads_type == 3)
                                    <td><span class="badge badge--success" title="{{$ptc->ads_body}}"><i class="las la-comment-alt"></i></span></td>
                                @endif

                                <td>
                                    @php echo $ptc->statusBadge($ptc->status); @endphp
                                </td>
                                <td>
                                    <button class="btn btn-sm btn--warning changeStatusBtn" data-id ="{{$ptc->id}}" data-status="{{$ptc->status}}" title="Change Status"><i class="fas fa-toggle-off"></i></button>
                                    <a href="{{route('admin.ptc.edit',$ptc->id)}}" class="btn btn-sm btn--primary" title="Edit"><i class="las la-edit"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{__($emptyMessage) }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if ($ptcs->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($ptcs) }}
            </div>
            @endif
        </div><!-- card end -->
    </div>
</div>

{{-- status change modal --}}
<div id="changeStatusModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Status Change Confirmation')</h5>
                <button type="button" class="close btn btn--danger" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{route('admin.ptc.change.status')}}" method="post">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Description')</label>
                        <select name="status" class="form-control">
                            <option @if(1) selected @endif value="1">@lang('Active')</option>
                            <option @if(0) selected @endif value="0">@lang('Pending')</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="buttorn_wrapper">
                        <button type="submit" class="btn btn--primary"> @lang('Change')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('breadcrumb-plugins')
<a href="{{route('admin.ptc.create')}}" type="button" class="btn btn-sm btn--primary " title="add"><i class="las la-plus"></i>@lang('Add New')</a>
@endpush

@push('script')
<script>
    (function ($) {
            "use strict";

            $('.changeStatusBtn').on('click', function () {
                var modal = $('#changeStatusModal');
                modal.find('input[name=id]').val($(this).data('id'));
                var status = $(this).data('status');
                 modal.find('select[name=status]').val(status);
                modal.modal('show');
            });

        })(jQuery);

</script>
@endpush


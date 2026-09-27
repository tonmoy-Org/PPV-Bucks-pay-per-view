@extends('admin.layouts.app')
@section('panel')
<div class="d-flex flex-wrap justify-content-end mb-3">
    <div class="d-inline">
        <div class="input-group justify-content-end">
            <input type="text" name="search_table" class="form-control bg--white"
                placeholder="@lang('Search Plan')...">
            <button class="btn btn--primary input-group-text"><i class="fa fa-search"></i></button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two custom-data-table">
                        <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Price')</th>
                                <th>@lang('Referral Commission')</th>
                                <th>@lang('Daily Ads View Limit')</th>
                                <th>@lang('Ad Earning Rate')</th>
                                <th>@lang('Ad Stay Time')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($plans as $plan)
                            <tr>
                                <td>{{__($plan->name)}}</td>
                                <td>{{$general->cur_sym}}{{showAmount($plan->price) }}</td>
                                <td>{{__($plan->ref_level)}}</td>
                                <td>{{__($plan->point)}} @lang('Ads / Day')</td>
                                <td>{{$general->cur_sym}}{{showAmount($plan->ad_rate) }} / @lang('Ad')</td>
                                <td>{{ $plan->ad_duration > 0 ? $plan->ad_duration.' '.__('Sec') : __('Ad Default') }}</td>
                                <td>
                                    @php echo $plan->statusBadge($plan->status); @endphp
                                </td>
                                <td>
                                    <div class="button--group">
                                        <button type="button" class="btn btn-sm btn--primary editBtn"
                                            data-id="{{ $plan->id }}" 
                                            data-name="{{ $plan->name }}"
                                            data-subtitle="{{ $plan->subtitle }}"
                                            data-price="{{ $plan->price }}"
                                            data-status="{{ $plan->status }}"
                                            data-validity_text="{{ $plan->validity_text }}"
                                            data-features="{{ $plan->features }}"
                                            data-ad_rate="{{ $plan->ad_rate }}"
                                            data-ad_duration="{{ $plan->ad_duration }}"
                                            data-point="{{ $plan->point}}"><i class="las la-edit"></i>
                                        </button>
                                    </div>
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
            @if ($plans->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($plans) }}
            </div>
            @endif
        </div><!-- card end -->
    </div>
</div>
{{-- Add modal --}}
<div id="addModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> @lang('Add Plan')</h5>
                <button type="button" class="close btn btn--danger" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.plan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name"> @lang('Name'):</label>
                        <input type="text" class="form-control" name="name" placeholder="@lang('Plan Name')" required>
                    </div>
                    <div class="form-group">
                        <label for="subtitle"> @lang('Subtitle / Tagline'):</label>
                        <input type="text" class="form-control" name="subtitle" placeholder="@lang('e.g. Instant Activation Package')">
                    </div>
                    <div class="form-group">
                        <label> @lang('Price') :</label>
                        <div class="input-group mb-3">
                            <input type="number" min="0" step="any" class="form-control" name="price" placeholder="@lang('Price of Plan (Set 0 for Free Plan)')" required>
                            <div class="input-group-text">{{ $general->cur_text }}</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label> @lang('Daily Ads View Limit'): </label>
                        <input type="number" min="0" class="form-control" name="point" placeholder="@lang('e.g. 50 (User can view max 50 ads per day)')"
                            required>
                        <small class="form-text text-muted"><i class="las la-info-circle"></i> @lang('Set how many ads per day a user subscribing to this plan can watch (e.g. 50 ads/day for Premium).')</small>
                    </div>
                    <div class="form-group">
                        <label> @lang('Per Click Ad Earning Rate'): </label>
                        <div class="input-group mb-3">
                            <input type="number" step="any" min="0" class="form-control" name="ad_rate" placeholder="@lang('e.g. 0.50 (User earns 0.50 per ad view)')" required>
                            <div class="input-group-text">{{ $general->cur_text }}</div>
                        </div>
                        <small class="form-text text-muted"><i class="las la-info-circle"></i> @lang('Set the reward amount a user earns for each ad view under this plan.')</small>
                    </div>
                    <div class="form-group">
                        <label> @lang('Ad Stay Duration (Seconds)'): </label>
                        <div class="input-group mb-3">
                            <input type="number" min="0" class="form-control" name="ad_duration" placeholder="@lang('e.g. 16 (Required stay time in seconds per ad)')">
                            <div class="input-group-text">@lang('Seconds')</div>
                        </div>
                        <small class="form-text text-muted"><i class="las la-info-circle"></i> @lang('Set required stay time in seconds per ad for users under this plan (e.g. 16s). Set 0 to use Ad Default.')</small>
                    </div>
                    <div class="form-group">
                        <label for="validity_text"> @lang('Validity / Period Text'):</label>
                        <input type="text" class="form-control" name="validity_text" placeholder="@lang('e.g. One-time payment • Lifetime validity')">
                    </div>
                    <div class="form-group">
                        <label for="features"> @lang('Features List (One feature per line)'):</label>
                        <textarea class="form-control" name="features" rows="5" placeholder="@lang('50 Daily Ads View Limit Included&#10;Instant Activation&#10;Access to All Premium Ads&#10;- Express Approval&#10;- 24/7 Dedicated Support Assistance')"></textarea>
                        <small class="form-text text-muted"><i class="las la-info-circle"></i> @lang('Tip: Start a line with "-" or "x" (e.g. "- Express Approval") to show a red Cross icon for unavailable features.')</small>
                    </div>
                    <div class="form-group">
                        <label> @lang('Status')</label>
                        <label class="switch m-0">
                            <input type="checkbox" class="toggle-switch" name="status">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary btn-global">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- edit modal --}}
<div id="editModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> @lang('Update Plan')</h5>
                <button type="button" class="close btn btn--danger" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.plan.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name"> @lang('Name'):</label>
                        <input type="text" class="form-control" name="name" placeholder="@lang('Plan Name')" required>
                    </div>
                    <div class="form-group">
                        <label for="subtitle"> @lang('Subtitle / Tagline'):</label>
                        <input type="text" class="form-control" name="subtitle" placeholder="@lang('e.g. Instant Activation Package')">
                    </div>
                    <div class="form-group">
                        <label> @lang('Price') :</label>
                        <div class="input-group mb-3">
                            <input type="number" min="0" step="any" class="form-control" name="price" placeholder="@lang('Price of Plan (Set 0 for Free Plan)')" required>
                              <div class="input-group-text">{{ $general->cur_text }}</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label> @lang('Daily Ads View Limit'): </label>
                        <input type="number" min="0" class="form-control" name="point" placeholder="@lang('e.g. 50 (User can view max 50 ads per day)')"
                            required>
                        <small class="form-text text-muted"><i class="las la-info-circle"></i> @lang('Set how many ads per day a user subscribing to this plan can watch (e.g. 50 ads/day for Premium).')</small>
                    </div>
                    <div class="form-group">
                        <label> @lang('Per Click Ad Earning Rate'): </label>
                        <div class="input-group mb-3">
                            <input type="number" step="any" min="0" class="form-control" name="ad_rate" placeholder="@lang('e.g. 0.50 (User earns 0.50 per ad view)')" required>
                            <div class="input-group-text">{{ $general->cur_text }}</div>
                        </div>
                        <small class="form-text text-muted"><i class="las la-info-circle"></i> @lang('Set the reward amount a user earns for each ad view under this plan.')</small>
                    </div>
                    <div class="form-group">
                        <label> @lang('Ad Stay Duration (Seconds)'): </label>
                        <div class="input-group mb-3">
                            <input type="number" min="0" class="form-control" name="ad_duration" placeholder="@lang('e.g. 16 (Required stay time in seconds per ad)')">
                            <div class="input-group-text">@lang('Seconds')</div>
                        </div>
                        <small class="form-text text-muted"><i class="las la-info-circle"></i> @lang('Set required stay time in seconds per ad for users under this plan (e.g. 16s). Set 0 to use Ad Default.')</small>
                    </div>
                    <div class="form-group">
                        <label for="validity_text"> @lang('Validity / Period Text'):</label>
                        <input type="text" class="form-control" name="validity_text" placeholder="@lang('e.g. One-time payment • Lifetime validity')">
                    </div>
                    <div class="form-group">
                        <label for="features"> @lang('Features List (One feature per line)'):</label>
                        <textarea class="form-control" name="features" rows="5" placeholder="@lang('Enter plan features, one per line')"></textarea>
                        <small class="form-text text-muted"><i class="las la-info-circle"></i> @lang('Tip: Start a line with "-" or "x" (e.g. "- Express Approval") to show a red Cross icon for unavailable features.')</small>
                    </div>
                    <div class="form-group">
                        <label> @lang('Status')</label>
                        <label class="switch m-0">
                            <input type="checkbox" class="toggle-switch" name="status">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary btn-global">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
<a href="{{ route('admin.frontend.sections', 'plan') }}" class="btn btn-sm btn--info me-2"><i class="las la-pen"></i> @lang('Edit Section Title & Subheading')</a>
<button type="button" class="btn btn-sm btn--primary addBtn"><i class="las la-plus"></i>@lang('Add New')</button>
@endpush
@push('script')
<script>
    (function($){
        "use strict";
        $('.addBtn').on('click', function() {
            var modal = $('#addModal');
            modal.modal('show');
        });

        $('.editBtn').on('click', function() {
            var modal = $('#editModal');
            modal.find('.act').text($(this).data('act'));
            modal.find('input[name=id]').val($(this).data('id'));
            modal.find('input[name=name]').val($(this).data('name'));
            modal.find('input[name=subtitle]').val($(this).data('subtitle'));
            modal.find('input[name=price]').val($(this).data('price'));
            modal.find('input[name=point]').val($(this).data('point'));
            modal.find('input[name=ad_rate]').val($(this).data('ad_rate'));
            modal.find('input[name=ad_duration]').val($(this).data('ad_duration'));
            modal.find('input[name=validity_text]').val($(this).data('validity_text'));
            modal.find('textarea[name=features]').val($(this).data('features'));
            modal.find('input[name=status]').prop('checked', $(this).data('status') == 1 ? true : false );
            modal.find('input[name=status]').val($(this).data('status') == 1 ? 1 : 0);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush


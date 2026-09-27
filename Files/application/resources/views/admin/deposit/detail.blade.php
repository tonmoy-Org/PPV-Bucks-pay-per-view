@extends('admin.layouts.app')
@section('panel')
<div class="row mb-none-30 justify-content-center">
    <div class="col-xl-4 col-md-6 mb-30">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="mb-20 text-muted">@lang('Deposit Via') {{ __(@$deposit->gateway->name) }}</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Date')
                        <span class="fw-bold">{{ showDateTime($deposit->created_at) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Transaction Number')
                        <span class="fw-bold">{{ $deposit->trx }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Username')
                        <span class="fw-bold">
                            <a href="{{ ($deposit->user instanceof \App\Models\Advertiser) ? route('admin.advertisers.detail', $deposit->user_id) : route('admin.users.detail', $deposit->user_id) }}">{{ @$deposit->user->username }}</a>
                        </span>
                    </li>
                    @if($deposit->plan_id)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Target Plan')
                        <span class="badge badge--primary">{{ @App\Models\Plan::find($deposit->plan_id)->name }}</span>
                    </li>
                    @endif
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Method')
                        <span class="fw-bold">{{ __(@$deposit->gateway->name) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Amount')
                        <span class="fw-bold">{{ showAmount($deposit->amount ) }} {{ __($general->cur_text) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Charge')
                        <span class="fw-bold">{{ showAmount($deposit->charge ) }} {{ __($general->cur_text) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('After Charge')
                        <span class="fw-bold">{{ showAmount($deposit->amount+$deposit->charge ) }} {{
                            __($general->cur_text) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Rate')
                        <span class="fw-bold">1 {{__($general->cur_text)}}
                            = {{ showAmount($deposit->rate) }} {{__($deposit->baseCurrency())}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Payable')
                        <span class="fw-bold">{{ showAmount($deposit->final_amo ) }}
                            {{__($deposit->method_currency)}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Status')
                        @php echo $deposit->statusBadge @endphp
                    </li>
                    @if($deposit->admin_feedback)
                    <li class="list-group-item">
                        <strong>@lang('Admin Response')</strong>
                        <br>
                        <p>{{__($deposit->admin_feedback)}}</p>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    @if($details || $deposit->status == 2)
    <div class="col-xl-8 col-md-6 mb-30">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="card-title mb-50 border-bottom pb-2">@lang('Deposit Info')</h5>
                @if($details != null)
                @foreach(json_decode($details) as $key => $val)
                @if($deposit->method_code >= 1000)
                    @if(is_object($val) && isset($val->name))
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6>{{__($val->name)}}</h6>
                            @if(isset($val->type) && $val->type == 'checkbox')
                            {{ is_array($val->value) ? implode(',', $val->value) : $val->value }}
                            @elseif(isset($val->type) && $val->type == 'file')
                            @if(!empty($val->value))
                                @php
                                    $fileName = $val->value;
                                    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($fileExt), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg']);
                                    $fileUrl = asset(getFilePath('verify') . '/' . $fileName);
                                @endphp
                                @if($isImage)
                                    <div class="my-2">
                                        <a href="javascript:void(0)" class="view-image-btn d-inline-block border p-1 rounded bg-light shadow-sm" data-src="{{ $fileUrl }}">
                                            <img src="{{ $fileUrl }}" alt="@lang('Attachment')" style="max-height: 220px; max-width: 100%; border-radius: 6px; cursor: pointer;">
                                        </a>
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline--primary me-2 view-image-btn" data-src="{{ $fileUrl }}">
                                            <i class="fa fa-eye"></i> @lang('View Attachment')
                                        </button>
                                        <a href="{{ route('admin.download.attachment',encrypt(getFilePath('verify').'/'.$fileName)) }}" class="btn btn-sm btn-outline--secondary">
                                            <i class="fa fa-download"></i> @lang('Download')
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-2">
                                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline--primary me-2">
                                            <i class="fa fa-eye"></i> @lang('View File')
                                        </a>
                                        <a href="{{ route('admin.download.attachment',encrypt(getFilePath('verify').'/'.$fileName)) }}" class="btn btn-sm btn-outline--secondary">
                                            <i class="fa fa-download"></i> @lang('Download')
                                        </a>
                                    </div>
                                @endif
                            @else
                            @lang('No File')
                            @endif
                            @else
                            <p>{{__($val->value)}}</p>
                            @endif
                        </div>
                    </div>
                    @elseif(is_array($val) && isset($val['name']))
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6>{{__($val['name'])}}</h6>
                            @if(isset($val['type']) && $val['type'] == 'checkbox')
                            {{ is_array($val['value']) ? implode(',', $val['value']) : $val['value'] }}
                            @elseif(isset($val['type']) && $val['type'] == 'file')
                            @if(!empty($val['value']))
                                @php
                                    $fileName = $val['value'];
                                    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($fileExt), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg']);
                                    $fileUrl = asset(getFilePath('verify') . '/' . $fileName);
                                @endphp
                                @if($isImage)
                                    <div class="my-2">
                                        <a href="javascript:void(0)" class="view-image-btn d-inline-block border p-1 rounded bg-light shadow-sm" data-src="{{ $fileUrl }}">
                                            <img src="{{ $fileUrl }}" alt="@lang('Attachment')" style="max-height: 220px; max-width: 100%; border-radius: 6px; cursor: pointer;">
                                        </a>
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline--primary me-2 view-image-btn" data-src="{{ $fileUrl }}">
                                            <i class="fa fa-eye"></i> @lang('View Attachment')
                                        </button>
                                        <a href="{{ route('admin.download.attachment',encrypt(getFilePath('verify').'/'.$fileName)) }}" class="btn btn-sm btn-outline--secondary">
                                            <i class="fa fa-download"></i> @lang('Download')
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-2">
                                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline--primary me-2">
                                            <i class="fa fa-eye"></i> @lang('View File')
                                        </a>
                                        <a href="{{ route('admin.download.attachment',encrypt(getFilePath('verify').'/'.$fileName)) }}" class="btn btn-sm btn-outline--secondary">
                                            <i class="fa fa-download"></i> @lang('Download')
                                        </a>
                                    </div>
                                @endif
                            @else
                            @lang('No File')
                            @endif
                            @else
                            <p>{{__($val['value'])}}</p>
                            @endif
                        </div>
                    </div>
                    @elseif(is_string($val) && $key != 'user_type')
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6>{{ keyToTitle($key) }}</h6>
                            <p>{{ __($val) }}</p>
                        </div>
                    </div>
                    @endif
                @endif
                @endforeach
                @if($deposit->method_code < 1000) 
                    @include('admin.deposit.gateway_data',['details'=> json_decode($details)])
                @endif
                @endif
                    @if($deposit->status == 2)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button class="btn btn--success ms-1 confirmationBtn"
                                data-action="{{ route('admin.deposit.approve', $deposit->id) }}"
                                data-question="@lang('Are you sure to approve this transaction?')"><i
                                    class="fas fa-check"></i>
                                @lang('Approve')
                            </button>

                            <button class="btn btn--danger ms-1 rejectBtn" data-id="{{ $deposit->id }}"
                                data-info="{{$details}}"
                                data-amount="{{ showAmount($deposit->amount)}} {{ __($general->cur_text) }}"
                                data-username="{{ @$deposit->user->username }}"><i class="fas fa-ban"></i>
                                @lang('Reject')
                            </button>
                        </div>
                    </div>
                    @endif
            </div>
        </div>
    </div>
    @endif
</div>

{{-- REJECT MODAL --}}
<div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Reject Deposit Confirmation')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.deposit.reject')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="fw-bold">@lang('reject')</span> <span
                            class="fw-bold withdraw-amount text-success"></span> @lang('deposit of') <span
                            class="fw-bold withdraw-user"></span>?</p>

                    <div class="form-group">
                        <label class="fw-bold mt-2">@lang('Reason for Rejection')</label>
                        <textarea name="message" maxlength="255" class="form-control" rows="5"
                            required>{{ old('message') }}</textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary btn-global">@lang('Save')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ATTACHMENT PREVIEW MODAL --}}
<div id="attachmentModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Attachment Preview')</h5>
                <button type="button" class="close btn btn--danger btn--sm" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body text-center p-3">
                <img src="" id="attachmentPreviewImage" class="img-fluid rounded shadow-sm" style="max-height: 75vh; object-fit: contain;">
            </div>
            <div class="modal-footer">
                <a href="" id="attachmentDownloadLink" class="btn btn--primary btn-sm" download><i class="fa fa-download"></i> @lang('Download')</a>
                <button type="button" class="btn btn--secondary btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

<x-confirmation-modal></x-confirmation-modal>
@endsection

@push('script')
<script>
    (function ($) {
        "use strict";

        $('.rejectBtn').on('click', function () {
            var modal = $('#rejectModal');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.find('.withdraw-amount').text($(this).data('amount'));
            modal.find('.withdraw-user').text($(this).data('username'));
            modal.modal('show');
        });

        $('.view-image-btn').on('click', function () {
            var src = $(this).data('src');
            $('#attachmentPreviewImage').attr('src', src);
            $('#attachmentDownloadLink').attr('href', src);
            $('#attachmentModal').modal('show');
        });
    })(jQuery);
</script>
@endpush
@extends($activeTemplate.'layouts.user.master')
@section('content')
<div class="body-wrapper">
    <div class="table-content">
        <div class="m-0">
            <div class="list-card">
                <div class="row search-dash justify-content-end mb-3">
                    <div class="col-lg-4 col-md-8 col-12">
                        <input type="text" name="search_table" class="form--control" placeholder="Search...">
                        <i class="las la-search"></i>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <p class="text-end">@lang('Today Clicks'):  <strong>{{auth()->user()->clicks->where('view_date',Date('Y-m-d'))->count()}}</strong></p>
                    <div class="col-xl-12">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Total Click')</th>
                                    <th>@lang('Amount')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($totalClicks as $click)
                                <tr>
                                    <td data-label="@lang('Date')">
                                       {{$click->date}}
                                    </td>
                                    <td data-label="@lang('Total Click')">
                                        {{$click->clicks}}
                                     </td>
                                     <td data-label="@lang('Amount')">
                                        {{$general->cur_sym}}{{showAmount($click->earned)}}
                                     </td>

                                </tr>
                                @empty
                                <tr>
                                    <td data-label="@lang('Today Click Table')" class="text-center" colspan="100%">{{__($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-5">
                        @if($totalClicks->hasPages())
                        <div class="card-footer py-4">
                            {{ paginateLinks($totalClicks) }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('script')
<script>
    (function ($) {
            "use strict";

            $('.custom-table').css('padding-top', '0px');
            var tr_elements = $('.custom-table tbody tr');

            $(document).on('input', 'input[name=search_table]', function () {
                "use strict";

                var search = $(this).val().toUpperCase();
                var match = tr_elements.filter(function (idx, elem) {
                    return $(elem).text().trim().toUpperCase().indexOf(search) >= 0 ? elem : null;
                }).sort();
                var table_content = $('.custom-table tbody');
                if (match.length == 0) {
                    table_content.html('<tr><td colspan="100%" class="text-center">Data Not Found</td></tr>');
                } else {
                    table_content.html(match);
                }
            });

            $('.deletModalBtn').on('click', function () {
                var modal = $('#deleteModal');
                var id = $(this).data('id');
                modal.find('input[name=id]').val(id);
                modal.modal('show');
            });

    })(jQuery);

</script>
@endpush


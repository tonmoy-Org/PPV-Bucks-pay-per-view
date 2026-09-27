@extends($activeTemplate.'layouts.advertiser.master')
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
                    <div class="col-xl-12">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>@lang('Title')</th>
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
                                    <td  data-label="@lang('Title')">{{__($ptc->title)}}</td>
                                    <td  data-label="@lang('Duration')">{{$ptc->duration}}@lang('s') </td>
                                    <td  data-label="@lang('Max Show')">{{$ptc->max_show}}</td>
                                    <td  data-label="@lang('Showed')">{{$ptc->showed}}</td>
                                    <td  data-label="@lang('Remains')">{{$ptc->remain}}</td>

                                    @if(in_array($ptc->ads_type, [1, 4]))
                                    <td data-label="@lang('Image/Link/Script')"><span class="badge badge--success" title="{{$ptc->ads_body}}"><i class="las la-link"></i></span></td>
                                    @endif

                                    @if($ptc->ads_type == 2)
                                        <td data-label="@lang('Image/Link/Script')"><img class="rounded" src="{{ getImage(getFilePath('ptc').'/'.$ptc->ads_body) }}" alt="" style="width:50px"></td>
                                    @endif

                                    @if($ptc->ads_type == 3)
                                        <td  data-label="@lang('Image/Link/Script')"><span class="badge badge--success" title="{{$ptc->ads_body}}"><i class="las la-comment-alt"></i></span></td>
                                    @endif

                                    <td  data-label="@lang('Status')">
                                        @php echo $ptc->statusBadge($ptc->status); @endphp
                                    </td>
                                    <td data-label="@lang('Action')">
                                        <a href="{{route('advertiser.ptc.edit',$ptc->id)}}" class="btn btn--sm btn--base" title="Edit"><i class="las la-edit"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td data-label="@lang('Ad Table')" class="text-muted text-center" colspan="100%">{{__($emptyMessage) }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-5">
                        @if ($ptcs->hasPages())
                        <div class="card-footer py-4">
                            {{ paginateLinks($ptcs) }}
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




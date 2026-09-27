@extends($activeTemplate.'layouts.advertiser.master')
@section('content')
    <div class="body-wrapper">
        <div class="table-content">
            <div class="m-0">
                <div class="list-card">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{route('advertiser.ticket.open') }}" class="btn btn--base"> <i class="fa fa-plus"></i> @lang('New Ticket')</a>
                    </div>
                    <div class="table-area m-0">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>@lang('Subject')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Priority')</th>
                                    <th>@lang('Last Reply')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supports as $support)
                                    <tr>
                                        <td data-label="@lang('Subject')"> <a href="{{ route('advertiser.ticket.view', $support->ticket) }}" class="fw-bold text--base"> [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} </a></td>
                                        <td data-label="@lang('Status')">
                                            @php echo $support->statusBadge; @endphp
                                        </td>
                                        <td data-label="@lang('Priority')">
                                            @if($support->priority == 1)
                                                <span class="badge badge--info">@lang('Low')</span>
                                            @elseif($support->priority == 2)
                                                <span class="badge badge--success">@lang('Medium')</span>
                                            @elseif($support->priority == 3)
                                                <span class="badge badge--base">@lang('High')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Last Reply')">{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }} </td>

                                        <td data-label="@lang('Action')">
                                            <a href="{{ route('advertiser.ticket.view', $support->ticket) }}" class="btn btn--base btn--sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td data-label="Support Ticket Table" colspan="100%" class="text-center" data-label="@lang('Support Ticket Table')">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-5">
                        {{$supports->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

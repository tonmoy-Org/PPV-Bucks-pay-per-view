@php
$withdrawal = getContent('withdrawal.content', true);
$withdraws = App\Models\Withdrawal::with('method')->where('status',1)->latest()->limit(10)->get();
@endphp
<!-- ==================== latest withdrawal start ==================== -->
<section class="latest-withdrawal py-80">
    <div class="container">
        <div class="title">
            <h4>{{__(@$withdrawal->data_values->heading)}}</h4>
            <p>{{__(@$withdrawal->data_values->sub_heading)}}</p>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="custom-table wow animate__animated animate__jackInTheBox" data-wow-delay="0.6s">
                    <thead>
                        <tr>
                            <th>@lang('Gateway')</th>
                            <th class="text-center">@lang('Time')</th>
                            <th class="text-center">@lang('Amount')</th>
                            <th class="text-center">@lang('Conversion')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdraws as $withdraw)
                        <tr>
                            <td data-label="@lang('Gateway')">
                                 {{__(@$withdraw->method->name) }}
                            </td>
                            <td data-label="@lang('Time')" class="text-center">
                                {{ showDateTime($withdraw->created_at) }}
                            </td>
                            <td data-label="@lang('Amount')" class="text-center">
                                {{ __($general->cur_sym) }}{{ showAmount($withdraw->amount ) }} </span>
                            </td>
                            <td data-label="@lang('Conversion')" class="text-center">
                                1 {{ __($general->cur_text) }} = {{ showAmount($withdraw->rate) }}
                                {{__($withdraw->currency) }}

                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td data-label="@lang('Withdrawal Table')" class="text-muted text-center" colspan="100%">{{__($emptyMessage) }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- ==================== latest withdrawal end ==================== -->

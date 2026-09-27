@extends($activeTemplate.'layouts.advertiser.master')

@section('content')
<div class="body-wrapper">
    <div class="table-content">
        @include($activeTemplate.'sections.plan', ['plans' => $packages])
    </div>
</div>
@endsection

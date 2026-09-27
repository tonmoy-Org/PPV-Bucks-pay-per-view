@extends($activeTemplate.'layouts.frontend')

@section('content')
    @include($activeTemplate.'sections.plan')

    @if($sections != null && $sections->secs != null)
        @foreach(json_decode($sections->secs) as $sec)
            @include($activeTemplate.'sections.'.$sec)
        @endforeach
    @endif
@endsection

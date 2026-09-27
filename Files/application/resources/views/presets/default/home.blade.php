@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
  $sessionTemplate = session('template');
@endphp
{{-- demo homepart( remove this @$sessionTemplate? $sessionTemplate == 1 and  $sessionTemplate = session('template') ) --}}
@if(@$sessionTemplate? $sessionTemplate == 1: $general->homepage == 1)
@include($activeTemplate.'components.banners.banner_one')
@else
@include($activeTemplate.'components.banners.banner_two')
@endif

@if($sections->secs != null)
@foreach(json_decode($sections->secs) as $sec)
@include($activeTemplate.'sections.'.$sec)
@endforeach
@endif
@endsection

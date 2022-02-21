@extends('rapidez::layouts.app')

@section('title', 'Pending payment')

@php(header("Refresh:2"))

@section('content')
    <div>
        <h1 class="font-bold text-4xl mb-5">@lang('Pending payment')</h1>
        <p>@lang('We did not receive the payment (yet). We will be refreshing the page until we do.')</p>
    </div>
@endsection

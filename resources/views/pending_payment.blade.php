@extends('rapidez::layouts.app')

@section('title', 'Pending payment')

@section('content')
    <checkout-success>
        <h1 class="font-bold text-4xl mb-5">@lang('Pending payment')</h1>
        <p>@lang('We did not receive the payment (yet).')</p>
    </checkout-success>
@endsection

@extends('rapidez::layouts.app')

@section('title', 'Order canceled')

@section('content')
    <h1 class="font-bold text-4xl mb-5">@lang('Order canceled')</h1>
    <p>@lang('You did not finish the payment so the order is canceled.')</p>
@endsection

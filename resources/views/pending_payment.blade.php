@extends('rapidez::layouts.app')

@section('title', 'Pending payment')

@section('content')
    <h1 class="font-bold text-4xl">@lang('Pending payment')</h1>
    <p>@lang('We did not receive the payment (yet).')</p>
@endsection

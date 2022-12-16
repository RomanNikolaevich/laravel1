@extends('layouts.master')
@section('title', 'Главная')
@section('content')
        <h1>@lang('main.all_products')</h1>
        <form method="GET" action="{{ route('index') }}">
            <div class="filters row">
                @foreach($products as $product)
                    @include('layouts.card', compact('product'))
                @endforeach
            </div>
        </form>
@endsection

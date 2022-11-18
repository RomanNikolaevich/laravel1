@extends('layouts.master')
@section('title', 'Главная')
@section('content')
    <div class="starter-template">
        <h1>Все товары</h1>
        <form method="GET" action="{{ route('index') }}">
            <div class="filters row">
                @foreach($products as $product)
                    @include('card', compact('product'))
                @endforeach
            </div>
        </form>
    </div>
@endsection

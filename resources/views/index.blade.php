@extends('layouts.master')
@section('title', 'Главная')
@section('content')
        <h1>Все товары</h1>
        <form method="GET" action="{{ route('index') }}">
            <div class="filters row">
                @foreach($products as $product)
                    @include('layouts.card', compact('product'))
                @endforeach
            </div>
        </form>
@endsection

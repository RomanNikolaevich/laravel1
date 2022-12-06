@extends('layouts.master')
@section('title', 'товар')
@section('content')
        <h1>iPhone X 64GB</h1>
        <h2>{{ $product }}</h2>
        <p>Цена: <b>71990 ₴</b></p>
        <img src="">
        <p>Отличный продвинутый телефон с памятью на 64 gb</p>

        <form action="/basket/add/1" method="POST">
            <button type="submit" class="btn btn-success" role="button">Добавить в корзину</button>

        </form>
@endsection

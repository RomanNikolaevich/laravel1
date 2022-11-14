@extends('master')
@section('title', 'товар')
@section('content')
    <div class="starter-template">
        <h1>iPhone X 64GB</h1>
        <h2>{{$product}}</h2>
        <h2>Мобильные телефоны</h2>
        <p>Цена: <b>71990 ₽</b></p>
        <img src="http://internet-shop.tmweb.ru/storage/products/iphone_x.jpg">
        <p>Отличный продвинутый телефон с памятью на 64 gb</p>


        <span>product.not_available</span>
        <br>
        <span>Сообщить мне, когда товар появится в наличии:</span>
        <div class="warning">
        </div>
        <form method="POST" action="/subscription/1">
            <input type="hidden" name="_token" value="7IzSj5ij6LsH3lbOS2YzEgUfrH4aWvCcTtxWjIkv"> <input type="text"
                                                                                                        name="email"></input>
            <button type="submit">Отправить</button>
        </form>
    </div>
@endsection

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    public function basket():Factory|View|Application
    {
        $orderId = session('orderId');
        if (is_null($orderId)) {
            return \view('empty');
        } else {
            $order = Order::findOrFail($orderId);

            return view('basket', compact('order'));
        }
        //return view('basket', compact('order'));
    }

    public function basketPlace():View|Factory|RedirectResponse|Application
    {
        $orderId = session('orderId');
        if (is_null($orderId)) {
            return redirect()->route('index'); //возвращаем на главную
        }
        $order = Order::find($orderId);

        return view('order', compact('order'));
    }

    public function basketConfirm(Request $request):RedirectResponse
    {
        $orderId = session('orderId');
        if (is_null($orderId)) { //если нет сессии создаем ее
            return redirect()->route('index'); //возвращаем на главную
        }
        $order = Order::find($orderId); //если есть находим
        $success = $order->saveOrder($request->name, $request->phone);
        if ($success) {
            session()->flash('success', 'Ваш заказ принят в обработку');
        } else {
            session()->flash('warning', 'Случилась ошибка');
        }

        return redirect()->route('index'); //возвращаем на главную
    }

    public function basketAdd($productId):RedirectResponse
    {
        $orderId = session('orderId');
        if (is_null($orderId)) { //если нет сессии создаем ее
            $order = Order::create();
            session(['orderId' => $order->id]);
        } else {
            $order = Order::find($orderId); //если есть находим
        }

        if ($order->products->contains($productId)) {//если товар есть в корзине:
            $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
            $pivotRow->count++;//увеличивает attributes-count на 1
            $pivotRow->update();//обновляем в БД
        } else {//если товара нет в корзине:
            $order->products()->attach($productId); //добавляем товар в корзину, используем нашу связь.
        }

        if (Auth::check()) {//если мы авторизованы, то:
            $order->user_id = Auth::id();//добавляем в поле 'user_id' метод id() класса Auth
            $order->save(); // сохраняем
        }
        $product = Product::find($productId);//находим товар
        session()->flash('success', 'Добавлен товар '. $product->name);

        return redirect()->route('basket');
    }

    public function basketRemove($productId):RedirectResponse
    {
        $orderId = session('orderId');
        if (is_null($orderId)) {
            return redirect()->route('basket');
        }
        $order = Order::find($orderId);

        if ($order->products->contains($productId)) {
            $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
            if ($pivotRow->count < 2) { //если 1 или 0
                $order->products()->detach($productId);//удалям продукт
            } else {
                $pivotRow->count--;//уменьшаем attributes-count на 1
                $pivotRow->update();//обновляем в БД
            }
        }
        $product = Product::find($productId);//находим товар
        session()->flash('warning', 'Удален товар '. $product->name);

        return redirect()->route('basket');
    }
}

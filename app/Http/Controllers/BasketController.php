<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

    public function basketPlace():Factory|View|Application
    {
        return view('order');
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

        return redirect()->route('basket');
    }
}

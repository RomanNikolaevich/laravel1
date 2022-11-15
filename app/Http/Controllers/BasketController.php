<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    public function basket():Factory|View|Application
    {
        $orderId = session('orderId');
        if (!is_null($orderId)) {
        $order = Order::findOfFail($orderId);
        }

        return view('basket');
    }

    public function basketPlace():Factory|View|Application
    {
        return view('order');
    }

    public function basketAdd($productId)
    {
        $orderId = session('orderId');
        if (is_null($orderId)) { //если нет сессии создаем ее
            $order = Order::create()->id;
            session(['orderId' => $order->id]);
        } else {
            $order = Order::find($orderId); //если есть находим
        }
        $order->products()->attach($productId); //добавляем товар в корзину, используем нашу связь.

        return view('basket', compact('order'));
    }
}

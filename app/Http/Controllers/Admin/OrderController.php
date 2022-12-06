<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index():Factory|View|Application
    {
        $orders = Order::where('status', 1)->get();
        return view('auth.orders.index', compact('orders'));
    }

    public function show(Order $order):Factory|View|Application
    {
        return view('auth.orders.show', compact('order'));
    }
}

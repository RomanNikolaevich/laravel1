<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Admin\OrderService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class OrderController extends Controller
{
    protected OrderService $service;

    public function __construct()
    {
        $this->service = app(OrderService::class);
    }

    public function index():Factory|View|Application
    {
        $orders = $this->service->getList();

        return view('auth.orders.index', compact('orders'));
    }

    public function show(Order $order):Factory|View|Application
    {
        return view('auth.orders.show', compact('order'));
    }
}

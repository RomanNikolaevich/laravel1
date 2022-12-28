<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index():Factory|View|Application
    {
        $orders = Auth::user()->orders()->where('status', 1)->get();

        return view('auth.orders.index', compact('orders'));
    }

    public function show(Order $order):View|Factory|RedirectResponse|Application
    {
        if (!Auth::user()->orders->contains($order)) {
            return back();
        }

        return view('auth.orders.show', compact('order'));
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderCreateRequest;
use App\Http\Requests\Orders\OrderUpdateRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of the order.
     *
     * @return JsonResponse
     */
    public function index():JsonResponse
    {
        $orders = Order::where('status', 1)->get();

        return response()->json($orders->toBase());
    }

    /**
     * Store a newly created order.
     *
     * @param OrderCreateRequest $request
     *
     * @return JsonResponse
     */
    public function store(OrderCreateRequest $request):JsonResponse
    {
        $params = $request->validated();

        $order = Order::create($params);

        return response()->json($order->toArray(), 201);
    }

    /**
     * Display the order.
     *
     * @param Order $order
     *
     * @return JsonResponse
     */
    public function show(Order $order):JsonResponse
    {
        return response()->json($order->toArray());
    }

    /**
     * Update the order.
     *
     * @param OrderUpdateRequest $request
     * @param Order              $order
     *
     * @return JsonResponse
     */
    public function update(OrderUpdateRequest $request, Order $order):JsonResponse
    {
        $params = $request->validated();

        $order->update($params);

        return response()->json($order->toArray(), 201);
    }

    /**
     * Remove the ordere.
     *
     * @param Order $order
     *
     * @return JsonResponse
     */
    public function destroy(Order $order):JsonResponse
    {
        $order->delete();

        return response()->json($order->toArray());
    }
}

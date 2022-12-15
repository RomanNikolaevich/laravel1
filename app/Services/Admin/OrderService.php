<?php

namespace App\Services\Admin;

use App\Http\Requests\Orders\OrderCreateRequest;
use App\Http\Requests\Orders\OrderUpdateRequest;
use App\Models\Order;
use Illuminate\Support\Collection;

class OrderService
{
    /**
     * Get order list
     *
     * @return Collection
     */
    public function getList():Collection
    {
        return Order::where('status', 1)->get();
    }

    /**
     * Store new order
     *
     * @param OrderCreateRequest $request
     *
     * @return Order
     */
    public function store(OrderCreateRequest $request):Order
    {
        $params = $request->validated();

        return Order::create($params);
    }

    /**
     * Update order
     *
     * @param OrderUpdateRequest $request
     * @param Order              $order
     *
     * @return Order
     */
    public function update(OrderUpdateRequest $request, Order $order):Order
    {
        $params = $request->validated();

        $order->update($params);

        return $order;
    }

    /**
     * Delete order
     *
     * @param Order $order
     *
     * @return Order
     */
    public function delete(Order $order):Order
    {
        $order->delete();

        return $order;
    }
}

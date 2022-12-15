<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderCreateRequest;
use App\Http\Requests\Orders\OrderUpdateRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use App\Services\Admin\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    protected OrderService $service;

    public function __construct()
    {
        $this->service = app(OrderService::class);
    }

    /**
     * Display a listing of the order.
     *
     * @return AnonymousResourceCollection
     */
    public function index():AnonymousResourceCollection
    {
        $orders = $this->service->getList();

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created order.
     *
     * @param OrderCreateRequest $request
     *
     * @return OrderResource
     */
    public function store(OrderCreateRequest $request):OrderResource
    {
        $order = $this->service->store($request);

        /** @var OrderResource $resource */
        $resource = app(OrderResource::class, ['resource' => $order]);
        $resource->response()->setStatusCode(201);

        return $resource;
    }

    /**
     * Display the order.
     *
     * @param Order $order
     *
     * @return OrderResource
     */
    public function show(Order $order):OrderResource
    {
        return app(OrderResource::class, ['resource' => $order]);
    }

    /**
     * Update the order.
     *
     * @param OrderUpdateRequest $request
     * @param Order              $order
     *
     * @return OrderResource
     */
    public function update(OrderUpdateRequest $request, Order $order):OrderResource
    {
        $order = $this->service->update($request, $order);

        return app(OrderResource::class, ['resource' => $order]);
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
        $order = $this->service->delete($order);

        return app(OrderResource::class, ['resource' => $order]);
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Models\Product;
use App\Services\Admin\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    protected ProductService $service;

    public function __construct()
    {
        $this->service = app(ProductService::class);
    }

    /**
     * Display a listing of the product.
     *
     * @return JsonResponse
     */
    public function index():JsonResponse
    {
        $products = $this->service->getList();

        return response()->json($products->toBase());
    }

    /**
     * Created product
     *
     * @param ProductCreateRequest $request
     *
     * @return JsonResponse
     */
    public function store(ProductCreateRequest $request):JsonResponse
    {
        $product = $this->service->store($request);

        return response()->json($product->toArray(), 201);
    }

    /**
     * Show product
     *
     * @param Product $product
     *
     * @return JsonResponse
     */
    public function show(Product $product):JsonResponse
    {
        return response()->json($product->toArray());
    }

    /**
     * Update product
     *
     * @param ProductUpdateRequest $request
     * @param Product              $product
     *
     * @return JsonResponse
     */
    public function update(ProductUpdateRequest $request, Product $product):JsonResponse
    {
        $this->service->update($request, $product);

        return response()->json($product->toArray());
    }

    /**
     * Remove product
     *
     * @param Product $product
     *
     * @return JsonResponse
     */
    public function destroy(Product $product):JsonResponse
    {
        $product = $this->service->delete($product);

        return response()->json($product->toArray());
    }
}

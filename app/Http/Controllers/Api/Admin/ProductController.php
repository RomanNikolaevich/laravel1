<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Services\Admin\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
     * @return AnonymousResourceCollection
     */
    public function index():AnonymousResourceCollection
    {
        $products = $this->service->getList();

        return ProductResource::collection($products);
    }

    /**
     * Created product
     *
     * @param ProductCreateRequest $request
     *
     * @return ProductResource
     */
    public function store(ProductCreateRequest $request):ProductResource
    {
        $product = $this->service->store($request);

        /** @var ProductResource $resource */
        $resource = app(ProductResource::class, ['resource' => $product]);
        $resource->response()->setStatusCode(201);

        return $resource;
    }

    /**
     * Show product
     *
     * @param Product $product
     *
     * @return ProductResource
     */
    public function show(Product $product):ProductResource
    {
        return app(ProductResource::class, ['resource' => $product]);
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
        $product = $this->service->update($request, $product);

        return app(ProductResource::class, ['resource' => $product]);
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

        return app(ProductResource::class, ['resource' => $product]);
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Services\Admin\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct()
    {
        $this->productService = app(ProductService::class);
    }

    /**
     * Display a listing of the product with default currency.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $defaultCode = config('currency.default_code');
        $code = $request->get('currency', $defaultCode);
        $products = $this->productService->getList(strtoupper($code));

        return ProductResource::collection($products);
    }

    /**
     * Created product
     *
     * @param ProductCreateRequest $request
     *
     * @return ProductResource
     */
    public function store(ProductCreateRequest $request): ProductResource
    {
        $product = $this->productService->store($request);

        /** @var ProductResource $resource */
        $resource = app(ProductResource::class, ['resource' => $product]);
        $resource->response()->setStatusCode(201);

        return $resource;
    }

    /**
     * Show product with default currency
     *
     * @param Product $product
     * @param Request $request
     * @return ProductResource
     */
    public function show(Product $product, Request $request): ProductResource
    {
        $defaultCode = config('currency.default_code');
        $code = $request->get('currency', $defaultCode);
        $this->productService->show($product, strtoupper($code));

        return app(ProductResource::class, ['resource' => $product]);

	}

    /**
     * Update product
     *
     * @param ProductUpdateRequest $request
     * @param Product $product
     *
     * @return JsonResponse
     */
    public function update(ProductUpdateRequest $request, Product $product): JsonResponse
    {
        $product = $this->productService->update($request, $product);

        return app(ProductResource::class, ['resource' => $product]);
    }

    /**
     * Remove product
     *
     * @param Product $product
     *
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        $product = $this->productService->delete($product);

        return app(ProductResource::class, ['resource' => $product]);
    }
}

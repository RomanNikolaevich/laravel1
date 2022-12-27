<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Services\Admin\CurrencyService;
use App\Services\Admin\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
	protected ProductService $productService;

	protected CurrencyService $currencytService;

	public function __construct()
	{
		$this->productService = app(ProductService::class);
		$this->currencytService = app(CurrencyService::class);
	}

	/**
	 * Display a listing of the product with default currency.
	 *
	 * @return AnonymousResourceCollection
	 */
	public function index(): AnonymousResourceCollection
	{
		$products = $this->productService->getList();

		return ProductResource::collection($products);
	}

	/**
	 * Display a listing of the product with any currency.
	 *
	 * @return AnonymousResourceCollection
	 */
	public function indexPriceConvert(string $currency): AnonymousResourceCollection
	{
		$products = $this->productService->getList();
		$mappedcollection = $products->map(function ($product, $key) use ($currency) {
			return [
				'id' => $product->id,
				'price' =>  $this->currencytService->convertPrice(now(), $currency, $product->price),
			];
		});

		dd($mappedcollection);

//		$products = $this->productService->getList();
//
//		return ProductResource::collection($products);
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
	 *
	 * @return ProductResource
	 */
	public function show(Product $product): ProductResource
	{
		return app(ProductResource::class, ['resource' => $product]);
	}

	/**
	 * Show product with any currency
	 *
	 * @param Product $product
	 *
	 * @return ProductResource
	 */
	public function showPriceConvert(Product $product, string $currency): ProductResource
	{
		 $show = app(ProductResource::class, ['resource' => $product]);
		return $show;
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

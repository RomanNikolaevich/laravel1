<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the product.
     *
     * @return JsonResponse
     */
    public function index():JsonResponse
    {
        $products = Product::get();

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
        $params = $request->validated();
        unset($params['image']);

        if ($request->has('image')) {
            $params['image'] = $request
                ->file('image')
                ?->store('products');
        }

        $product = Product::create($params);

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
        $params = $request->validated();
        unset($params['image']);

        if ($request->has('image')) {
            Storage::delete('image');
            $params['image'] = $request
                ->file('image')
                ?->store('products');
        }

        $product->update($params);

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
        $product->delete();

        return response()->json($product->toArray());
    }
}

<?php

namespace App\Services\Admin;

use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * Get category list
     *
     * @param string $code
     * @return Collection
     */
    public function getList(?string $code): Collection
    {
        $date = Carbon::now();
        $defaultCode = config('currency.default_code');
        $products = Product::get();

        /** @var CurrencyService $currencyService */
        $currencyService = app(CurrencyService::class);

        foreach ($products as $product) {
            if ($code !== $defaultCode | null) {
                $product->price = $currencyService->convertPrice($date, $code, $product->price);
            }
        }

        return $products;
    }

    public function show(Product $product, string $code): Product
    {
        $date = Carbon::now();
        $defaultCode = config('currency.default_code');

        if ($code === $defaultCode | null) {
            return $product;
        }

        /** @var CurrencyService $currencyService */
        $currencyService = app(CurrencyService::class);
        $product->price = $currencyService->convertPrice($date, $code, $product->price);

        return $product;
    }

    /**
     * Store new product
     *
     * @param ProductCreateRequest $request
     *
     * @return Product
     */
    public function store(ProductCreateRequest $request): Product
    {
        $params = $request->all();
        unset($params['image']);

        if ($request->has('image')) {
            $params['image'] = $request
                ->file('image')
                ?->store('products');
        }

        return Product::create($params);
    }

    /**
     * Update product
     *
     * @param ProductUpdateRequest $request
     * @param Product $product
     *
     * @return Product
     */
    public function update(ProductUpdateRequest $request, Product $product): Product
    {
        $params = $request->all();
        unset($params['image']);

        if ($request->has('image')) {
            if (!empty($product->image) && Storage::exists($product->image)) {
                Storage::delete('image');
            }

            $params['image'] = $request
                ->file('image')
                ?->store('products');
        }

        $product->update($params);

        return $product;
    }

    /**
     * Delete product
     *
     * @param Product $product
     *
     * @return Product
     */
    public function delete(Product $product): Product
    {
        $product->delete();

        if (!empty($product->image) && Storage::exists($product->image)) {
            Storage::delete($product->image);
        }

        return $product;
    }
}

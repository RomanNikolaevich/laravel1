<?php

namespace App\Services\Admin;

use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * Get category list
     *
     * @return Collection
     */
    public function getList():Collection
    {
        return Product::get();
    }

    /**
     * Store new product
     *
     * @param ProductCreateRequest $request
     *
     * @return Product
     */
    public function store(ProductCreateRequest $request):Product
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
     * @param Product              $product
     *
     * @return Product
     */
    public function update(ProductUpdateRequest $request, Product $product):Product
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
    public function delete(Product $product):Product
    {
        $product->delete();

        if (!empty($product->image) && Storage::exists($product->image)) {
            Storage::delete($product->image);
        }

        return $product;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Models\Product;
use App\Services\Admin\ProductService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    protected ProductService $service;

    public function __construct()
    {
        $this->service = app(ProductService::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index():Application|Factory|View
    {
        $products = $this->service->getList();

        return view('auth.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create():View|Factory|Application
    {
        $categories = Category::get();

        return view('auth.products.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductCreateRequest $request
     *
     * @return RedirectResponse
     */
    public function store(ProductCreateRequest $request):RedirectResponse
    {
        $this->service->store($request);

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     *
     * @return Application|Factory|View
     */
    public function show(Product $product):View|Factory|Application
    {
        return view('auth.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     *
     * @return Application|Factory|View
     */
    public function edit(Product $product):View|Factory|Application
    {
        $categories = Category::get();

        return view('auth.products.form', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductUpdateRequest $request
     * @param Product              $product
     *
     * @return RedirectResponse
     */
    public function update(ProductUpdateRequest $request, Product $product):RedirectResponse
    {
        $this->service->update($request, $product);

        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     *
     * @return RedirectResponse
     */
    public function destroy(Product $product):RedirectResponse
    {
        $product->delete($product);

        return redirect()->route('products.index');
    }
}

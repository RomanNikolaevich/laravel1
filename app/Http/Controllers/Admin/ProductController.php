<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index():Application|Factory|View
    {
        $products = Product::get();

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
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function store(Request $request):RedirectResponse
    {
        $path = $request->file('image')->store('products');
        $params = $request->all();
        $params['image'] = $path;
        Product::create($params);
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
     * @param Request $request
     * @param Product $product
     *
     * @return RedirectResponse
     */
    public function update(Request $request, Product $product):RedirectResponse
    {
        Storage::delete('image');
        $path = $request->file('image')->store('products');
        $params = $request->all();
        $params['image'] = $path;
        $product->update($params);
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
        $product->delete();

        return redirect()->route('products.index');
    }
}

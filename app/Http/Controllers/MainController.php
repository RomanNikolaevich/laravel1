<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;

class MainController extends Controller
{
    public function index():Factory|View|Application
    {
        $products = Product::get();

        return view('index', compact('products'));
    }

    public function categories():Factory|View|Application
    {
        $categories = Category::get();

        return view('categories', compact('categories'));
    }

    public function category($code):Factory|View|Application
    {
        $category = Category::where('code', $code)->first();

        return view('category', compact('category'));
    }

    public function product($category, $product = null):Factory|View|Application//означает, что свойство не обязательное
    {
        return view('product', ['product' => $product]); //второй вариант передачи параметров
    }

    public function changeLocale($locale):RedirectResponse
    {
        $avaibleLocales = ['ua', 'en'];
        if (!in_array($locale, $avaibleLocales, true)) {
            $locale = config('app.locale');
    }
        session(['locale' => $locale]);
        App::setLocale($locale);

        return redirect()->back();
    }
}

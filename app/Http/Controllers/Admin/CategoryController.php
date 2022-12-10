<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Http\Requests\CategoryCreateRequest;
use App\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index():View|Factory|Application
    {
        $categories = Category::get();

        return view('auth.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create():View|Factory|Application
    {
        return view('auth.categories.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function store(CategoryCreateRequest $request):RedirectResponse
    {
        $params = $request->all();
        unset($params['image']);
        if ($request->has('image')) {//если в запросе есть картинка, то мы добавляем сохранение
            $params['image'] = $request->file('image')->store('categories');
            //image - название поля html верстке в input у кнопки "Загрузить", categories - папка для загрузки картинок
        }
        Category::create($params);
        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     *
     * @return Application|Factory|View
     */
    public function show(Category $category):View|Factory|Application
    {
        return view('auth.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $category
     *
     * @return Application|Factory|View
     */
    public function edit(Category $category):View|Factory|Application
    {
        return view('auth.categories.form', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request  $request
     * @param Category $category
     *
     * @return RedirectResponse
     */
    public function update(CategoryUpdateRequest $request, Category $category):RedirectResponse
    {
        $params = $request->all();
        unset($params['image']);
        if ($request->has('image')) { //проверка на существование картинки
            Storage::delete('image');
            $params['image']  = $request->file('image')->store('categories');
        }
        $category->update($params);
        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     *
     * @return RedirectResponse
     */
    public function destroy(Category $category):RedirectResponse
    {
        $category->delete();

        return redirect()->route('categories.index');
    }
}

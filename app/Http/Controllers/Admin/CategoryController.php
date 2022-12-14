<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\CategoryCreateRequest;
use App\Http\Requests\Categories\CategoryUpdateRequest;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    protected CategoryService $service;

    public function __construct()
    {
        $this->service = app(CategoryService::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index():View|Factory|Application
    {
        $categories = $this->service->getList();

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
     * @param CategoryCreateRequest $request
     *
     * @return RedirectResponse
     */
    public function store(CategoryCreateRequest $request):RedirectResponse
    {
        $this->service->store($request);

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
     * @param CategoryUpdateRequest $request
     * @param Category $category
     *
     * @return RedirectResponse
     */
    public function update(CategoryUpdateRequest $request, Category $category):RedirectResponse
    {
        $this->service->update($request, $category);

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
        $this->service->delete($category);

        return redirect()->route('categories.index');
    }
}

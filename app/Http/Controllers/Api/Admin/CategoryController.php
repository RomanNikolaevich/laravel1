<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\CategoryCreateRequest;
use App\Http\Requests\Categories\CategoryUpdateRequest;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected CategoryService $service;

    public function __construct()
    {
        $this->service = app(CategoryService::class);
    }

    /**
     * Display a listing of the category.
     *
     * @return JsonResponse
     */
    public function index():JsonResponse
    {
        $categories = $this->service->getList();

        return response()->json($categories->toBase());
    }

    /**
     * store category.
     *
     * @param CategoryCreateRequest $request
     *
     * @return JsonResponse
     */
    public function store(CategoryCreateRequest $request):JsonResponse
    {
        $category = $this->service->store($request);

        return response()->json($category->toArray(), 201);
    }

    /**
     * Show category.
     *
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category):JsonResponse
    {
        return response()->json($category->toArray());
    }

    /**
     * Update category.
     *
     * @param CategoryUpdateRequest $request
     * @param Category              $category
     *
     * @return JsonResponse
     */
    public function update(CategoryUpdateRequest $request, Category $category):JsonResponse
    {
        $category = $this->service->update($request, $category);

        return response()->json($category->toArray());
    }

    /**
     * Remove category.
     *
     * @param Category $category
     *
     * @return JsonResponse
     */
    public function destroy(Category $category):JsonResponse
    {
        $category = $this->service->delete($category);

        return response()->json($category->toArray());
    }
}

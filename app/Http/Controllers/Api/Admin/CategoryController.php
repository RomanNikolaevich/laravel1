<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\CategoryCreateRequest;
use App\Http\Requests\Categories\CategoryUpdateRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
     * @return AnonymousResourceCollection
     */
    public function index():AnonymousResourceCollection
    {
        $categories = $this->service->getList();

        return CategoryResource::collection($categories);
    }

    /**
     * store category.
     *
     * @param CategoryCreateRequest $request
     *
     * @return CategoryResource
     */
    public function store(CategoryCreateRequest $request):CategoryResource
    {
        $category = $this->service->store($request);

        /** @var CategoryResource $resource */
        $resource = app(CategoryResource::class, ['resource' => $category]);
        $resource->response()->setStatusCode(201);

        return $resource;
    }

    /**
     * Show category.
     *
     * @param Category $category
     *
     * @return CategoryResource
     */
    public function show(Category $category):CategoryResource
    {
        return app(CategoryResource::class, ['resource' => $category]);
    }

    /**
     * Update category.
     *
     * @param CategoryUpdateRequest $request
     * @param Category              $category
     *
     * @return CategoryResource
     */
    public function update(CategoryUpdateRequest $request, Category $category):CategoryResource
    {
        $category = $this->service->update($request, $category);

        return app(CategoryResource::class, ['resource' => $category]);
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

        return app(CategoryResource::class, ['resource' => $category]);
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Models\Category;
use App\Http\Requests\Category\CategoryCreateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the category.
     *
     * @return JsonResponse
     */
    final public function index():JsonResponse
    {
        $categories = Category::get();

        return response()->json($categories->toBase());
    }

    /**
     * store category.
     *
     * @param CategoryCreateRequest $request
     *
     * @return JsonResponse
     */
    final public function store(CategoryCreateRequest $request):JsonResponse
    {
        $params = $request->validated();
        unset($params['image']);

        if ($request->has('image')) {
            $params['image'] = $request
                ->file('image')
                ?->store('categories');
        }

        $category = Category::create($params);

        return response()->json($category->toArray(), 201);
    }

    /**
     * Show category.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    final public function show(Category $category):JsonResponse
    {
        return response()->json($category->toArray());
    }

    /**
     * Update category..
     *
     * @param CategoryUpdateRequest $request
     * @param Category              $category
     *
     * @return JsonResponse
     */
    final public function update(CategoryUpdateRequest $request, Category $category):JsonResponse
    {
        $params = $request->validated();
        unset($params['image']);

        if ($request->has('image')) { //проверка на существование картинки
            Storage::delete('image');
            $params['image'] = $request
                ->file('image')
                ?->store('categories');
        }
        $category->update($params);

        return response()->json($category->toArray());
    }

    /**
     * Remove category.
     *
     * @param Category $category
     *
     * @return JsonResponse
     */
    final public function destroy(Category $category):JsonResponse
    {
        $category->delete();

        return response()->json($category->toArray());
    }
}

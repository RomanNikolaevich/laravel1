<?php

namespace App\Services\Admin;

use App\Http\Requests\Categories\CategoryCreateRequest;
use App\Http\Requests\Categories\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    /**
     * Get category list
     *
     * @return Collection
     */
    public function getList(): Collection
    {
        return Category::get();
    }

    /**
     * Store new category
     *
     * @param CategoryCreateRequest $request
     * @return Category
     */
    public function store(CategoryCreateRequest $request): Category
    {
        $params = $request->validated();
        unset($params['image']);

        if ($request->has('image')) {
            $params['image'] = $request
                ->file('image')
                ?->store('categories');
        }

        return Category::create($params);
    }

    /**
     * Update category
     *
     * @param CategoryUpdateRequest $request
     * @param Category $category
     * @return Category
     */
    public function update(CategoryUpdateRequest $request, Category $category): Category
    {
        $params = $request->all();
        unset($params['image']);

        if ($request->has('image')) {
            if (!empty($category->image) && Storage::exists($category->image)) {
                Storage::delete($category->image);
            }

            $params['image'] = $request
                ->file('image')
                ?->store('categories');
        }

        $category->update($params);

        return $category;
    }

    /**
     * Delete category
     *
     * @param Category $category
     * @return Category
     */
    public function delete(Category $category): Category
    {
        $category->delete();

        if (!empty($category->image) && Storage::exists($category->image)) {
            Storage::delete($category->image);
        }

        return $category;
    }
}

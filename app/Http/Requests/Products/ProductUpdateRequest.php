<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize():bool
    {
        return true; //false меняем на true
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules():array
    {
        return [
            'code' => 'min:3|max:255|unique:products,code,' . $this->route()?->parameter('product')->id,
            'name' => 'min:3|max:255',
            'description' => 'min:5',
            'price' => 'numeric|min:1',
            'category_id' => 'exists:categories,id',
            'image' => 'image',
        ];
    }
}

<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $product = $this->route()?->parameter('product')->id;

        return [
            'code'        => [
                'min:3',
                'max:255',
                Rule::unique('products', 'code')
                    ->ignore($product->id),
            ],
            'name' => 'min:3|max:255',
            'description' => 'min:5',
            'price' => 'numeric|min:1',
            'category_id' => 'exists:categories,id',
            'image' => 'image',
        ];
    }
}

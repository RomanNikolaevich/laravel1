<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $rules = [
            'code' => 'required|min:3|max:255|unique:products,code',
            'name' => 'required|min:3|max:255',
            'description' => 'required|min:5',
            'price' => 'required|numeric|min:1' //numeric - значит не один символ, а одно значение
        ];

        if ($this->route()->named('products.update')) {
            $rules['code'] .= ',' . $this->route()->parameter('product')->id;
        }

        return $rules;
    }
}

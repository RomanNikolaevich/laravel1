<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    final public function authorize():bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    final public function rules():array
    {
        return [
            'code'        => 'required|min:3|max:255|unique:categories,code',
            'name'        => 'required|min:3|max:255',
            'description' => 'required|min:5',
            'image'       => 'image',
        ];
    }

    final public function messages():array
    {
        return [
            'required' => 'Поле :attribute обязательно для ввода',
            'min'      => 'Поле :attribute должно иметь минимум :min символов',
            'code.min' => 'Поле код должно содержать не менее :min символов',
            'image' => 'Поле предназначено только для изображений',
        ];
    }
}

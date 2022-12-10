<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
            'code'        => 'min:3|max:255|unique:categories,code'.$this->route()?->parameter('category')->id,
            'name'        => 'min:3|max:255',
            'description' => 'min:5',
        ];
    }

    /**
     * @return string[]
     */
    final public function messages():array
    {
        return [
            'required' => 'Поле :attribute обязательно для ввода',
            'min'      => 'Поле :attribute должно иметь минимум :min символов',
            'code.min' => 'Поле код должно содержать не менее :min символов',
        ];
    }
}

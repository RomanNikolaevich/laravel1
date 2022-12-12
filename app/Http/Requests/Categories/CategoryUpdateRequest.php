<?php

namespace App\Http\Requests\Categories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize():bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules():array
    {
        $category = $this->route()?->parameter('category');

        return [
            'code'        => [
                'min:3',
                'max:255',
                Rule::unique('categories', 'code')
                    ->ignore($category->code),
            ],
            'name'        => 'min:3|max:255',
            'description' => 'min:5',
            'image'       => 'image',
        ];
    }

    /**
     * @return string[]
     */
    public function messages():array
    {
        return [
            'required' => 'Поле :attribute обязательно для ввода',
            'min'      => 'Поле :attribute должно иметь минимум :min символов',
            'code.min' => 'Поле код должно содержать не менее :min символов',
            'image'    => 'Поле предназначено только для изображений',
        ];
    }
}

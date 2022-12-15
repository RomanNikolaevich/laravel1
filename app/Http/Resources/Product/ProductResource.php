<?php

namespace App\Http\Resources\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the category resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var $this Product */
        return [
            'id'          => $this->id,
            'category_id' => $this->category_id,
            'name'        => $this->name,
            'code'        => $this->code,
            'description' => $this->description,
            'image'       => $this->image,
            'price'       => $this->price,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}

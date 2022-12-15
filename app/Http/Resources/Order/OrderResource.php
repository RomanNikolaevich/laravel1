<?php

namespace App\Http\Resources\Order;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the category resource collection into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request):array
    {
        /** @var $this Order */
        return [
            'id'         => $this->id,
            'status'     => $this->status,
            'name'       => $this->name,
            'phone'      => $this->phone,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_id'    => $this->user_id,
        ];
    }
}

<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'qty' => $this->qty,
            'thumbnail' => $this->thumbnail,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
        ];
    }
}

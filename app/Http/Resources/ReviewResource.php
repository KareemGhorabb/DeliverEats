<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'customer'           => new UserResource($this->whenLoaded('user')),
            'restaurant_rating'  => $this->restaurant_rating,
            'restaurant_comment' => $this->restaurant_comment,
            'rider_rating'       => $this->rider_rating,
            'rider_comment'      => $this->rider_comment,
            'created_at'         => $this->created_at?->toISOString(),
        ];
    }
}

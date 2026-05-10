<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'slug'              => $this->slug,
            'description'       => $this->description,
            'category'          => $this->category,
            'logo'              => $this->logo,
            'cover_image'       => $this->cover_image,
            'phone'             => $this->phone,
            'address'           => $this->address,
            'latitude'          => $this->latitude,
            'longitude'         => $this->longitude,
            'opening_hours'     => $this->opening_hours,
            'min_order_amount'  => (float) $this->min_order_amount,
            'delivery_radius_km' => (float) $this->delivery_radius_km,
            'avg_rating'        => (float) $this->avg_rating,
            'total_reviews'     => (int) $this->total_reviews,
            'is_active'         => $this->is_active,
            'is_featured'       => $this->is_featured,

            'menu_categories'   => MenuCategoryResource::collection($this->whenLoaded('menuCategories')),
            'owner'             => new UserResource($this->whenLoaded('user')),

            'created_at'        => $this->created_at?->toISOString(),
        ];
    }
}

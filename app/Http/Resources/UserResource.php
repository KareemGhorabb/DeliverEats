<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'role'       => $this->role,
            'avatar'     => $this->avatar,
            'address'    => $this->address,
            'latitude'   => $this->latitude,
            'longitude'  => $this->longitude,
            'is_active'  => $this->is_active,
            'created_at' => $this->created_at?->toISOString(),
        ];

        // Include restaurant name for restaurant owners
        if ($this->isRestaurantOwner()) {
            $restaurant = $this->restaurantsOwned()->first();
            $data['restaurant_name'] = $restaurant?->name;
            $data['restaurant_id']   = $restaurant?->id;
        }

        return $data;
    }
}

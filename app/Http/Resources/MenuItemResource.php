<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                       => $this->id,
            'name'                     => $this->name,
            'description'              => $this->description,
            'image'                    => $this->image,
            'price'                    => (float) $this->price,
            'is_available'             => $this->is_available,
            'preparation_time_minutes' => $this->preparation_time_minutes,
            'sort_order'               => $this->sort_order,
            'variants'                 => ItemVariantResource::collection($this->whenLoaded('itemVariants')),
        ];
    }
}

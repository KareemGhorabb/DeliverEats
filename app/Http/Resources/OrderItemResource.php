<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'menu_item'        => new MenuItemResource($this->whenLoaded('menuItem')),
            'variant'          => new ItemVariantResource($this->whenLoaded('itemVariant')),
            'quantity'         => $this->quantity,
            'unit_price'       => (float) $this->unit_price,
            'total_price'      => (float) $this->total_price,
            'special_requests' => $this->special_requests,
        ];
    }
}

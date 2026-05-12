<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'phone'        => $this->phone,
            'avatar'       => $this->avatar,
            'is_active'    => $this->is_active,
            'location'     => $this->whenLoaded('riderLocation', fn () => [
                'latitude'     => $this->riderLocation->latitude,
                'longitude'    => $this->riderLocation->longitude,
                'availability' => $this->riderLocation->availability,
                'last_ping_at' => $this->riderLocation->last_ping_at?->toISOString(),
            ]),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}

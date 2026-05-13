<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayoutResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'restaurant_id'       => $this->restaurant_id,
            'rider_id'            => $this->rider_id,
            'amount'              => $this->amount,
            'platform_commission' => $this->platform_commission,
            'net_amount'          => $this->net_amount,
            'status'              => $this->status,
            'paid_at'             => $this->paid_at?->toISOString(),
            'period_start'        => $this->period_start?->toISOString(),
            'period_end'          => $this->period_end?->toISOString(),
            'restaurant'          => $this->whenLoaded('restaurant', fn () => [
                'id'   => $this->restaurant->id,
                'name' => $this->restaurant->name,
            ]),
            'rider' => $this->whenLoaded('rider', fn () => [
                'id'   => $this->rider->id,
                'name' => $this->rider->name,
            ]),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'method'            => $this->method,
            'status'            => $this->status,
            'amount'            => (float) $this->amount,
            'currency'          => $this->currency,
            'paid_at'           => $this->paid_at?->toISOString(),
            'refunded_at'       => $this->refunded_at?->toISOString(),
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'restaurant_id'       => ['required', 'integer', 'exists:restaurants,id'],
            'delivery_address'    => ['required', 'string', 'max:500'],
            'delivery_lat'        => ['nullable', 'numeric', 'between:-90,90'],
            'delivery_lng'        => ['nullable', 'numeric', 'between:-180,180'],
            'special_instructions' => ['nullable', 'string', 'max:500'],
            'payment_method'      => ['required', 'in:card,cash,wallet'],

            'items'               => ['required', 'array', 'min:1'],
            'items.*.menu_item_id'    => ['required', 'integer', 'exists:menu_items,id'],
            'items.*.item_variant_id' => ['nullable', 'integer', 'exists:item_variants,id'],
            'items.*.quantity'        => ['required', 'integer', 'min:1', 'max:20'],
            'items.*.special_requests' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'          => 'Your cart is empty. Add items to place an order.',
            'items.min'               => 'You must order at least one item.',
            'items.*.quantity.max'    => 'Maximum 20 of any single item per order.',
        ];
    }
}

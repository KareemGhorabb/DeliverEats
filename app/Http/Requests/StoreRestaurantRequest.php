<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRestaurantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:255'],
            'slug'              => ['required', 'string', 'max:255', 'unique:restaurants,slug'],
            'description'       => ['nullable', 'string'],
            'category'          => ['nullable', 'string', 'max:100'],
            'phone'             => ['required', 'string', 'max:20'],
            'address'           => ['required', 'string'],
            'latitude'          => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'         => ['nullable', 'numeric', 'between:-180,180'],
            'min_order_amount'  => ['nullable', 'numeric', 'min:0'],
            'delivery_radius_km' => ['nullable', 'numeric', 'min:0'],
            'opening_hours'     => ['nullable', 'array'],
        ];
    }
}

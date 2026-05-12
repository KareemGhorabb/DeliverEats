<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'restaurant_rating'  => ['required', 'integer', 'between:1,5'],
            'restaurant_comment' => ['nullable', 'string', 'max:1000'],
            'rider_rating'       => ['nullable', 'integer', 'between:1,5'],
            'rider_comment'      => ['nullable', 'string', 'max:1000'],
        ];
    }
}

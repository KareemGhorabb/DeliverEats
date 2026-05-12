<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'is_available',
        'menu_category_id',
        'restaurant_id',
        'preparation_time_minutes',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'float',
        'is_available' => 'boolean',
    ];

    public function menuCategory(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function itemVariants(): HasMany
    {
        return $this->hasMany(ItemVariant::class);
    }
}

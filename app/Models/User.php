<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role',
        'avatar', 'address', 'latitude', 'longitude', 'is_active',
        'provider', 'provider_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
            'latitude'          => 'decimal:7',
            'longitude'         => 'decimal:7',
            'role'              => UserRole::class,
        ];
    }

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function restaurantsOwned(): HasMany
    {
        return $this->hasMany(Restaurant::class, 'user_id');
    }

    public function riderLocation(): HasOne
    {
        return $this->hasOne(RiderLocation::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function riderPayouts(): HasMany
    {
        return $this->hasMany(Payout::class, 'rider_id');
    }

    // ──────────────────────────────────────────────
    // Role Helpers
    // ──────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isRider(): bool
    {
        return $this->role === UserRole::Rider;
    }

    public function isCustomer(): bool
    {
        return $this->role === UserRole::Customer;
    }

    public function isRestaurantOwner(): bool
    {
        return $this->role === UserRole::RestaurantOwner;
    }
}

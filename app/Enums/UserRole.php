<?php

namespace App\Enums;

enum UserRole: string
{
    case Customer = 'customer';
    case RestaurantOwner = 'restaurant_owner';
    case Rider = 'rider';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Customer        => 'Customer',
            self::RestaurantOwner => 'Restaurant Owner',
            self::Rider           => 'Rider',
            self::Admin           => 'Admin',
        };
    }

    /**
     * Roles that can self-register.
     *
     * @return array<self>
     */
    public static function registerable(): array
    {
        return [self::Customer, self::RestaurantOwner, self::Rider];
    }
}

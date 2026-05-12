<?php

namespace App\Enums;

enum RiderAvailability: string
{
    case Online = 'online';
    case Busy = 'busy';
    case Offline = 'offline';

    public function label(): string
    {
        return match ($this) {
            self::Online  => 'Online',
            self::Busy    => 'Busy',
            self::Offline => 'Offline',
        };
    }

    public function isAvailableForDispatch(): bool
    {
        return $this === self::Online;
    }
}

<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Card = 'card';
    case Cash = 'cash';
    case Wallet = 'wallet';

    public function label(): string
    {
        return match ($this) {
            self::Card   => 'Credit/Debit Card',
            self::Cash   => 'Cash on Delivery',
            self::Wallet => 'Wallet',
        };
    }
}

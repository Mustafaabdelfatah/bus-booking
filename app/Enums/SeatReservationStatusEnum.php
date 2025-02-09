<?php

namespace App\Enums;

enum SeatReservationStatusEnum : string
{
    case PENDING = 'pending';
    case CANCELED = 'canceled';
    case CONFIRMED = 'confirmed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'pending',
            self::CANCELED => 'canceled',
            self::CONFIRMED => 'confirmed',
        };
    }
}
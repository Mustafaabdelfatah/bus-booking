<?php
namespace App\Enums;

enum SeatStatusEnum: string
{
    case AVAILABLE = 'available';
    case RESERVED = 'reserved';

    public function label(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Available',
            self::RESERVED => 'Reserved',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

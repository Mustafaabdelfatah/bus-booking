<?php
namespace App\Enums;

enum SeatTypeEnum: string
{
    case ADULT = 'adult';
    case CHILD = 'child';

    public function label(): string
    {
        return match ($this) {
            self::ADULT => 'Adult',
            self::CHILD => 'Child',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
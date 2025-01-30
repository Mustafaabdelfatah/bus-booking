<?php


namespace App\Support\Enum;

enum RestaurantApiTypeEnum: string
{

    case MARN = 'marn';
    case FOODICS = 'foodics';


    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }
}

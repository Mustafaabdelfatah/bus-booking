<?php


namespace App\Support\Enum;

enum ItemTypeEnum: string
{
    case ITEM_TYPE = 'item_type';
    case EXTRA = 'extra';

//    case BLOCK = 2;

    public static function fromName(string $name): string
    {
        foreach (self::cases() as $status) {
            if ($name === $status->name) {
                return $status->value;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class);
    }

    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }


    public function trans(): string
    {
        return match ($this) {
            self::ITEM_TYPE => __('lang.item_type'),
            self::EXTRA => __('lang.extra'),
//            self::BLOCK => __('lang.block'),
        };
    }


}

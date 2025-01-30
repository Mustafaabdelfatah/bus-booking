<?php


namespace App\Support\Enum;

enum InvoiceTypeEnum: string
{
    case DEFAULT = 'default';
    case MARN = 'marn';
    case FOODICS = 'foodics';

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
            self::DEFAULT => __('lang.default'),
            self::MARN => __('lang.marn'),
            self::FOODICS => __('lang.foodics'),
        };
    }


}

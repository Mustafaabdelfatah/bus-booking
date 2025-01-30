<?php


namespace App\Support\Enum;

enum StatusEnum: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;
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

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'bg-success',
            self::INACTIVE => 'bg-danger',
//            self::BLOCK => 'bg-warning',
        };
    }


    public function trans(): string
    {
        return match ($this) {
            self::ACTIVE => __('lang.active'),
            self::INACTIVE => __('lang.inactive'),
//            self::BLOCK => __('lang.block'),
        };
    }


}

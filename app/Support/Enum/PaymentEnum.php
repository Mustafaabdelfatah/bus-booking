<?php


namespace App\Support\Enum;

enum PaymentEnum: string
{
    case APPLE = 'apple-pay';
    case GOOGLE = 'google-pay';
    case CREDIT = 'credit-card';

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
            self::APPLE => 'bg-success',
            self::GOOGLE => 'bg-danger',
            self::CREDIT => 'bg-danger',
        };
    }


    public function trans(): string
    {
        return match ($this) {
            self::APPLE => __('user::lang.apple'),
            self::GOOGLE => __('user::lang.google'),
            self::CREDIT => __('user::lang.credit'),
        };
    }


}

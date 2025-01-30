<?php


namespace App\Support\Enum;

enum PaymentStatusEnum: string
{
    case PAID = 'paid';
    case UNPAID = 'unpaid';
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
            self::PAID => 'bg-success',
            self::UNPAID => 'bg-danger',
//            self::BLOCK => 'bg-warning',
        };
    }


    public function trans(): string
    {
        return match ($this) {
            self::PAID => __('lang.paid'),
            self::UNPAID => __('lang.unpaid'),
//            self::BLOCK => __('lang.block'),
        };
    }


}

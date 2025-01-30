<?php


namespace App\Support\Enum;

enum RestaurantStatusEnum: string
{
    case ACTIVE = 'active';
    case APPROVAL = 'approval';
    case PENDING = 'pending';
    case HOLD = 'hold';
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
            self::PENDING => 'bg-secondary',
            self::HOLD => 'bg-warning',
            self::APPROVAL => 'bg-info',
//            self::BLOCK => 'bg-warning',
        };
    }


    public function trans(): string
    {
        return match ($this) {
            self::ACTIVE => __('lang.active'),
            self::PENDING => __('lang.pending'),
            self::HOLD => __('lang.hold'),
            self::APPROVAL => __('lang.approval'),
//            self::BLOCK => __('lang.block'),
        };
    }


}

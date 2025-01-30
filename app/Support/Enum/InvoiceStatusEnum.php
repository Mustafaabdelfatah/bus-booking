<?php


namespace App\Support\Enum;

enum InvoiceStatusEnum: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case HOLD = 'hold';
    case CANCELED = 'canceled';
    case COMPLETED = 'completed';
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
            self::PENDING => 'bg-primary',
            self::IN_PROGRESS => 'bg-secondary',
            self::HOLD => 'bg-warning',
            self::CANCELED => 'bg-danger',
            self::COMPLETED => 'bg-success',
//            self::BLOCK => 'bg-warning',
        };
    }


    public function trans(): string
    {
        return match ($this) {
            self::PENDING => __('lang.pending'),
            self::IN_PROGRESS =>  __('lang.in_progress'),
            self::HOLD =>  __('lang.hold'),
            self::CANCELED =>  __('lang.canceled'),
            self::COMPLETED =>  __('lang.completed'),
//            self::BLOCK => __('lang.block'),
        };
    }


}

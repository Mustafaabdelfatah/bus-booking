<?php


namespace App\Support\Enum;

enum GroupAddonTypeEnum: string
{
    case REQUIRED = "required";
    case OPTIONAL = "optional";


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
            self::REQUIRED => 'bg-success',
            self::OPTIONAL => 'bg-danger',

        };
    }


    public function trans(): string
    {
        return match ($this) {
            self::REQUIRED => __('lang.active'),
            self::OPTIONAL => __('lang.inactive'),

        };
    }


}

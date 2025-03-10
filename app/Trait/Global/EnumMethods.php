<?php

namespace App\Trait\Global;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait EnumMethods
{
    /**
     * Retrieves a list of enumerated values and their corresponding labels.
     *
     * @return array An associative array containing the enumerated values and their labels.
     *
     * If an exception or error occurs during the retrieval process, an empty array is returned.
     */
    public static function getList(): array
    {
        try {
            return collect(array_combine(array_column(self::cases(), 'value'), array_column(self::cases(), 'name')))
                ->map(function ($key, $value) {

                    // If the value is numeric, convert it to lowercase and snake_case it
                    if (is_numeric($value)) {
                        $labelKey = ctype_upper($key) || count(explode('_', $key)) > 1 ? strtolower($key) : Str::snake($key);
                    } else {
                        $labelKey =ctype_upper($value) || count(explode('_', $value)) > 1 ? strtolower($value) : Str::snake($value);
                    }

                    // Check if the key exists in the translation file
                    $keyName = method_exists(__class__, 'keyName')
                        ? self::keyName()
                        : Str::of(class_basename(self::class))
                            ->snake()
                            ->replaceLast('_enum', '')
                            ->toString();

                    // Translate the key and return the result
                    $fullKeyPath = __("enums.$keyName.$labelKey");

                    $labelValue = Str::startsWith($fullKeyPath, 'enums.') ? $labelKey : $fullKeyPath;

                    return [
                        'key' => $key,
                        'value' => $value,
                        'label' => $labelValue,
                        'snake_key' => $labelKey,
                        'icon' => method_exists(__class__, 'icons') ? @self::icons()[$value] : null,
                    ];
                })->values()->toArray();

        } catch (\Exception|\Error $e) {
            logError($e);
            return [];
        }
    }

    /**
     * Matches a given value to its corresponding enumerated key or display value.
     *
     * @param mixed $value The value to be matched against the enumerated values or keys.
     * @param bool $trans Optional. Whether to return the translated display value. Default is true.
     *
     * @return mixed The matched display value if $trans is true, otherwise the matched key or value.
     *               If no match is found, the original $value is returned.
     */
    public static function resolve(mixed $value = null, ?bool $trans = true): mixed
    {
        // Handle case when value is an instance of an Enum
        if (is_object($value)) {
            $value = $value->value; // Get the value from the Enum instance
        }

        $key = false;
        // Attempt to find the object by its 'value'
        if (!$object = collect(self::getList())->filter(fn($item) => $item['value'] == $value)->first()) {
            $key = true;
            // If not found by 'value', attempt to find the object by its 'key'
            if (!$object = collect(self::getList())->filter(fn($item) => $item['key'] == $value)->first()) {
                return $value;
            }
        }

        if ($trans) {
            return $object['label'];
        }

        return $key ? $object['value'] : $object['snake_key'];
    }

    /**
     * @return array
     */
    public static function values(): array
    {
        return Arr::pluck(self::cases(), 'value');
    }
}
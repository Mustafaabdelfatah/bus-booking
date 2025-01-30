<?php

use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Support\Traits\Api\ApiResponseTrait;

/*
|--------------------------------------------------------------------------
| Responses Methods
|--------------------------------------------------------------------------
*/
// if (!function_exists('successResponse')) {
//     function successResponse($data = [], $msg = 'success', $code = 200): JsonResponse
//     {
//         return response()->json(['status' => true, 'code' => $code, 'message' => $msg, 'data' => $data], $code);
//     }
// }

if (!function_exists('successResponse')) {
    function successResponse($data = [], $msg = 'success', $code = 200): \Illuminate\Http\JsonResponse
    {
        $apiResponse = new class {
            use ApiResponseTrait;
        };

        return $apiResponse->responseData($data, $code, $msg, true);
    }
}

if (!function_exists('v_image')) {
    function v_image($ext = null): string
    {
        return ($ext === null) ? 'mimes:jpg,png,jpeg,png,gif,bmp' : 'mimes:' . $ext;
    }
}

if (!function_exists('failResponse')) {
    function failResponse($msg = 'Fail', $code = 400): JsonResponse
    {
        return response()->json(['status' => false, 'message' => $msg, 'code' => $code], $code);
    }
}

if (!function_exists('abort403')) {
    function abort403(): JsonResponse
    {
        abort(403, trans('api.no_required_permissions'));
    }
}

if (!function_exists('unKnownError')) {
    function unKnownError($message = null): JsonResponse|RedirectResponse
    {
        $message = trans('dashboard.something_error') . '' . (config('debug') ? " : $message" : '');

        return request()?->expectsJson()
            ? response()->json(['message' => $message], 400)
            : redirect()->back()->with(['status' => 'error', 'message' => $message]);
    }
}

/*
|--------------------------------------------------------------------------
| App Methods
|--------------------------------------------------------------------------
*/
if (!function_exists('dateFormat')) {
    function dateFormat($date, $format = 'j F Y'): string
    {
        return !is_numeric($date)
            ? Jenssegers\Date\Date::parse($date)->format($format)
            : '----';
    }
}

if (!function_exists('timeFormat')) {
    function timeFormat($time): ?string
    {
        if ($time === null) {
            return null;
        }

        return Jenssegers\Date\Date::parse($time)->format('h:i a');
    }
}

if (!function_exists('getModelKey')) {
    function getModelKey(?string $className = null): ?string
    {
        if (!$className) {
            return null;
        }

        $shortName = class_basename($className);

        return strtolower(Str::snake($shortName));
    }
}

if (!function_exists('detectModelPath')) {
    function detectModelPath($type): string
    {
        return "App\\Models\\" . Str::ucfirst(Str::singular($type));
    }
}

if (!function_exists('fetchData')) {
    function fetchData(Builder $query, string|int|null $pageSize = null, $resource = null)
    {
        if ($pageSize && (int)$pageSize !== -1) {
            $data = $query->paginate($pageSize);

            if ($resource) {
                $data->data = $resource::collection($data);
            }

        } else {
            $data = $resource ? $resource::collection($query->get()) : $query->get();
        }

        return $data;
    }
}

/*
|--------------------------------------------------------------------------
| Resolves Methods
|--------------------------------------------------------------------------
*/
if (!function_exists('resolveTrans')) {
    function resolveTrans($trans = '', $return = null, $lang = null)
    {
        if (empty($trans)) {
            return '---';
        }

        app()->setLocale($lang ?? app()->getLocale());

        $key = Str::snake($trans);

        if ($return === null) {
            $return = $trans;
        }

        return Str::startsWith(__("api.$key"), 'api.') ? $return : __("api.$key");
    }
}

if (!function_exists('resolveBool')) {
    function resolveBool($item): string
    {
        if ($item === 0) {
            return __('api.no');
        }

        if ($item === 1) {
            return __('api.yes');
        }

        return $item;
    }
}

if (!function_exists('resolvePhoto')) {
    function resolvePhoto($image = null, $type = 'user')
    {
        $result = ($type === 'user'
            ? asset('media/avatar.png')
            : asset('media/blank.png'));

        if (is_null($image)) {
            return $result;
        }

        if (Str::startsWith($image, 'http')) {
            return $image;
        }

        return Storage::exists($image)
            ? Storage::url($image)
            : $result;
    }
}

if (!function_exists('resolveArray')) {
    function resolveArray(string|array $array): array
    {
        return is_array($array) ? $array : explode(',', $array);
    }
}

/**
 * Resolve model instance from table name.
 *
 * @param string $tableName
 * @return mixed
 */
if (!function_exists('resolveModel')) {
    function resolveModel(string $name, $module= null): ?object
    {
        $modelPath = !empty($module) && $module !== 'none'
            ? "Modules\\" . ucfirst(Str::camel($module)) . "\\App\\Models"
            : "App\\Models";


        $modelClass = $modelPath . "\\" . Str::studly(Str::singular($name));

        return class_exists($modelClass) ? app($modelClass) : null;
    }
}


if (!function_exists('when')) {
    /**
     * Executes the given closure if the condition is true.
     * The condition is considered true if:
     * - It is a boolean and true
     * - It is a collection and not empty
     * - It is an array and not empty
     * - It is a string and not empty
     *
     * @param mixed $condition
     * @param callable $closure The closure to execute if the condition is pass from check.
     */
    function when(mixed $condition, callable $closure): void
    {
        //Determine if the condition is true based on its type using match
        $isTrue = match (true) {
            is_bool($condition) => $condition,
            $condition instanceof Collection => !$condition->isEmpty(),
            is_array($condition) => !empty($condition),
            is_string($condition) => $condition !== '',
            default => false,
        };

        //If the condition is true, execute the closure
        if ($isTrue) {
            $closure();
        }
    }
}

/*
|--------------------------------------------------------------------------
| App Methods
|--------------------------------------------------------------------------
*/
if (!function_exists('v_image')) {
    function v_image($ext = null): string
    {
        return ($ext === null) ? 'mimes:jpg,png,jpeg,png,gif,bmp' : 'mimes:' . $ext;
    }
}

if (!function_exists('is_base64')) {
    function is_base64($data): bool
    {
        $decoded_data = base64_decode($data, true);
        $encoded_data = base64_encode($decoded_data);

        if ($encoded_data !== $data) {
            return false;
        }

        if (!ctype_print($decoded_data)) {
            return false;
        }

        return true;
    }
}

if (!function_exists('updateDotEnv')) {
    function updateDotEnv(array $data = []): void
    {
        $path = base_path('.env');

        foreach ($data as $dataKey => $dataValue) {
            if (is_bool($dataValue)) {
                $dataValue = $dataValue ? 'true' : 'false';
            }

            if (str_contains(file_get_contents($path), "\n" . $dataKey . '=')) {
                $contents = array_values(array_filter(explode("\n", file_get_contents($path))));
                foreach ($contents as $content) {
                    if (str_starts_with($content, $dataKey . '=')) {
                        $delim = '';

                        if (str_contains($content, '"') || str_contains($dataValue, ' ') || str_contains($dataValue, '#')) {
                            $delim = '"';
                        }
                        file_put_contents(
                            $path, str_replace(
                                $content,
                                $dataKey . '=' . $delim . $dataValue . $delim,
                                file_get_contents($path)
                            )
                        );
                    }
                }
            } else if (str_contains($dataValue, ' ') || str_contains($dataValue, '#')) {
                File::append($path, $dataKey . '="' . $dataValue . '"' . "\n");
            } else {
                File::append($path, $dataKey . '=' . $dataValue . "\n");
            }
        }
    }
}

if (!function_exists('logError')) {
    function logError($exception): void
    {
        info("Error In Line => " . $exception->getLine() . " in File => {$exception->getFile()} , ErrorDetails => " . $exception->getMessage());
    }
}

if (!function_exists('isArrayIndex')) {
    function isArrayIndex($value): bool
    {
        return is_array($value) && count(array_filter(array_keys($value), 'is_string')) === 0;
    }
}
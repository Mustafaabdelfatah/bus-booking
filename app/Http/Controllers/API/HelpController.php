<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Global\HelpEnumRequest;
use App\Http\Requests\Global\HelpModelRequest;
use App\Services\Global\CountryService;
use Error;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class HelpController extends Controller
{
    /**
     * Retrieves and transforms data from specified models based on the provided request.
     *
     * @param HelpModelRequest $request
     * @return JsonResponse
     */
    public function models(HelpModelRequest $request): JsonResponse
    {
//        dd(11);
        foreach ($request->tables as $table) {
            try {
                when(!$model = resolveModel($table['name'], @$table['module']), fn() => throw new \RuntimeException());

                $select = $this->determineSelectFields($table);

                $this->applyScopes($model, @$table['scopes']);

                $result[$table['name']] = $model->select($select)
                    ->get()
                    ->transform(fn($record) => $this->transformRecord($record, $select))
                    ->toArray();

            } catch (Exception|Error $e) {
                logError($e);
                $result[$table['name']] = [];
            }
        }
        return successResponse($result ?? []);
    }

    /**
     * Retrieves a list of enums based on the request parameters.
     *
     * @param HelpEnumRequest $request
     * @return JsonResponse
     */
    public function enums(HelpEnumRequest $request): JsonResponse
    {
        if (!$request->enums) {
            return successResponse(array_merge($this->getDefaultEnums(), $this->getDefaultModuleEnums()));
        }

        $result = collect($request->enums)->mapWithKeys(function ($enum) {

            $name = implode("\\", array_map(fn($i) => ucfirst(Str::camel($i)), explode('.', $enum['name'])));

            $enumPath = !empty($enum['module'])
                ? "Modules\\" . ucfirst(Str::camel($enum['module'])) . "\\App\\Enums\\{$name}Enum"
                : "App\\Enums\\{$name}Enum";

            try {
                return [$enum['name'] => $enumPath::getList()];
            } catch (\Exception|Error) {
                return [$enum['name'] => []];
            }
        })->toArray();

        return successResponse($result);
    }


    /**
     * Retrieves the default enums and their corresponding values.
     *
     * @return array
     */
    private function getDefaultEnums(): array
    {
        $result = []; // Resolve array with its enum key and file path
        $this->resolveFilesFromDir(app_path('Enums'), $result);

        // Transform the enum keys and file paths to their corresponding enum keys and values
        collect($result)->each(function ($enum, $name) use (&$result) {
            $result[$name] = $enum::getList();
        });

        return $result;
    }

    /**
     * Retrieves the default module enums and their corresponding values.
     *
     * @return array
     */
    private function getDefaultModuleEnums(): array
    {
        $result = [];
        if (is_dir(base_path('Modules'))) {
            collect(is_dir(base_path('Modules')))
                ->map(function ($path) use (&$result) {
                    if (is_dir("$path\\App\\Enums")) {

                        // Resolve array with its enum key and file path
                        $this->resolveFilesFromDir($path, $result);

                        // Transform the enum keys and file paths to their corresponding enum keys and values
                        collect($result)->each(function ($enum, $name) use (&$result) {
                            $result[$name] = $enum::getList();
                        });
                    }
                });
        }
        return $result;
    }

    /**
     * Recursively scans a directory and resolves files to their corresponding enum keys.
     *
     * @param string $dir
     * @param array &$result
     * @return void
     */
    private function resolveFilesFromDir(string $dir, array &$result): void
    {

        collect(scandir($dir))
            ->filter(fn($dir) => !Str::startsWith($dir, '.'))
            ->each(function ($dirOrFile) use ($dir, &$result) {
                $filePath = "$dir\\$dirOrFile";

                if (is_dir($filePath)) {
                    $this->resolveFilesFromDir($filePath, $result);
                }

                // The result array with its enum key and file path
                if (is_file($filePath)) {
                    $result[$this->resolveEnumKey($filePath)] = ucfirst(str_replace([base_path() . "\\", '.php'], ['', ''], $filePath));
                }
            });

    }

    /**
     * Resolves the enum key from a given file path.
     *
     * @param string $filePath
     * @return string The resolved enum key.
     */
    private function resolveEnumKey(string $filePath): string
    {
        return Str::of($filePath)
            ->after('Enum\\')
            ->replace(['\\', '.php'], ['.', ''])
            ->snake()
            ->replaceLast('_enum', '')
            ->replace('._', '.')
            ->toString();
    }

    /**
     * @param $model
     * @param $scopes
     * @return void
     */
    private function applyScopes(&$model, $scopes = null): void
    {
        foreach (Arr::wrap($scopes) as $scope) {
            if (method_exists($model, 'scope' . ucfirst($scope))) {
                $model = $model->{$scope}();
            }
        }
    }

    /**
     * @param array $table
     * @return string[]
     */
    private function determineSelectFields(array $table): array
    {
        $select = match (true) {
            Schema::hasColumn($table['name'], 'display_name') => ['id', 'display_name'],
            default => ['id', 'name'],
        };
        if (!empty($table['extra'])) {
            $select = array_merge($select, Arr::wrap($table['extra']));
        }

        return $select;
    }

    /**
     * @param $record
     * @param array $select
     * @return array
     */
    private function transformRecord($record, array $select): array
    {
        foreach ($select as $r) {
            $item[$r] = $record->{$r};
        }

        // Replace DisplayName with name
        if (array_key_exists('display_name', $record->getAttributes())) {
            $item['name'] = $record->display_name;
            unset($item['display_name']);
        }

        return $item ?? [];
    }
}
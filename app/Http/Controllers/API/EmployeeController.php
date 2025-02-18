<?php

namespace App\Http\Controllers\API;

use App\Models\Employee;
use Illuminate\Support\Arr;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;
use App\Filters\Global\NameFilter;
use App\Http\Controllers\Controller;
use App\Filters\Global\OrderByFilter;
use App\Filters\Global\JsonNameFilter;
use App\Services\Global\UploadService;
use App\Http\Requests\Global\PageRequest;
use App\Http\Requests\API\EmployeeRequest;
use App\Http\Resources\API\EmployeeResource;
use App\Http\Requests\Global\DeleteAllRequest;

class EmployeeController extends Controller
{
    /**
     * @param PageRequest $request
     * @return JsonResponse
     */
    public function index(PageRequest $request): JsonResponse
    {
        $query = app(Pipeline::class)
            ->send(Employee::query())
            ->through([OrderByFilter::class])
            ->thenReturn();

        return successResponse(fetchData($query, $request->pageSize, EmployeeResource::class));
    }

    /**
     * @param EmployeeRequest $request
     * @return JsonResponse
     */
    public function store(EmployeeRequest $request): JsonResponse
    {
        $data = Arr::except($request->validated(), ['avatar', 'permissions', 'roles']);

        if ($request->avatar) {
            $data['avatar'] = UploadService::store($request->avatar, 'employee');
        }

        $employee = Employee::create($data);
        $employee->assignRole($request->roles);

        return successResponse(new EmployeeResource($employee), __('api.created_success'));
    }

    /**
     * @param Employee $employee
     * @return JsonResponse
     */
    public function show(Employee $employee): JsonResponse
    {
        return successResponse(new EmployeeResource($employee));
    }

    /**
     * @param EmployeeRequest $request
     * @param Employee $employee
     * @return JsonResponse
     */
    public function update(EmployeeRequest $request, Employee $employee): JsonResponse
    {
        $data = Arr::except($request->validated(), ['avatar', 'roles', 'permissions']);
        if ($request->avatar) {
            $data['avatar'] = UploadService::store($request->avatar, 'employees');
        }
        $employee->update($data);

        $employee->syncRoles($request->roles);
        $employee->syncPermissions($request->permissions);

        return successResponse(new EmployeeResource($employee), __('api.updated_success'));
    }

    /**
     * @param Employee $employee
     * @return JsonResponse
     */
    public function destroy(Employee $employee): JsonResponse
    {
        UploadService::delete($employee->avatar);
        $employee->delete();
        return successResponse(msg: __('api.deleted_success'));
    }

    /**
     * @param DeleteAllRequest $request
     * @return JsonResponse
     */
    public function destroyAll(DeleteAllRequest $request): JsonResponse
    {
        Employee::whereIn('id', $request->ids)->delete();
        return successResponse(msg: __('api.deleted_success'));
    }
}
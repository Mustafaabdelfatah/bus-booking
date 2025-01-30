<?php

namespace App\Http\Controllers\API\User;

use function __;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;
use App\Filters\Global\NameFilter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Filters\Global\OrderByFilter;
use App\Http\Requests\User\RoleRequest;
use App\Http\Requests\Global\PageRequest;
use App\Http\Resources\User\RoleResource;
use App\Filters\Global\JsonDisplayNameFilter;
use App\Http\Requests\Global\DeleteAllRequest;

class RoleController extends Controller
{
    /**
     * @param PageRequest $request
     * @return JsonResponse
     */
    public function index(PageRequest $request): JsonResponse
    {
        $query = app(Pipeline::class)
        ->send(Role::with('permissions'))
        ->through([NameFilter::class, OrderByFilter::class])
        ->thenReturn();

        return successResponse(fetchData($query, $request->pageSize, RoleResource::class));
    }

    /**
     * @param RoleRequest $request
     * @return JsonResponse
     */
    public function store(RoleRequest $request): JsonResponse
    {
        // Gate::authorize('create', Role::class);

        return DB::transaction(function () use ($request) {

            $role = Role::create($request->validated());
            $role->syncPermissions($request->permissions);

            $role->load('permissions');

            return successResponse(new RoleResource($role), __('api.created_success'));
        });
    }

    /**
     * @param Role $role
     * @return JsonResponse
     */
    public function show(Role $role): JsonResponse
    {
        // Gate::authorize('update', $role);

        $role->load('permissions');

        return successResponse(new RoleResource($role));
    }

    /**
     * @param RoleRequest $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(RoleRequest $request, Role $role): JsonResponse
    {
        // Gate::authorize('update', $role);

        return DB::transaction(function () use ($role, $request) {

            $role->update($request->validated());
            $role->syncPermissions($request->permissions);

            $role->refresh();

            $role->load('permissions');

            return successResponse(new RoleResource($role), __('api.updated_success'));
        });
    }

    /**
     * @param Role $role
     * @return JsonResponse
     */
    public function destroy(Role $role): JsonResponse
    {
        // Gate::authorize('delete', $role);

        DB::table('roles')->where('id', $role->id)->delete();

        return successResponse(msg: __('api.deleted_success'));
    }

    /**
     * @param DeleteAllRequest $request
     * @return JsonResponse
     */
    public function destroyAll(DeleteAllRequest $request): JsonResponse
    {
        // Gate::authorize('delete', Role::class);

        DB::table('roles')->whereIn('id', $request->ids)->delete();

        return successResponse(msg: __('api.deleted_success'));
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\BusRequest;
use App\Http\Resources\API\BusResource;
use Illuminate\Pipeline\Pipeline;
use App\Http\Requests\Global\PageRequest;
use App\Http\Requests\Global\DeleteAllRequest;

class BusController extends Controller
{
    public function index(PageRequest $request): JsonResponse
    {
        $query = app(Pipeline::class)
            ->send(Bus::query())
            ->through([])
            ->thenReturn();
        return successResponse(fetchData($query, $request->pageSize, BusResource::class));
    }

    public function store(BusRequest $request): JsonResponse
    {
        $bus = Bus::create($request->validated());
        return successResponse(new BusResource($bus), __('api.created_success'));
    }

    public function show(Bus $bus): JsonResponse
    {
        return successResponse(new BusResource($bus));
    }

    public function update(BusRequest $request, Bus $bus): JsonResponse
    {
        $bus->update($request->validated());
        return successResponse(new BusResource($bus), __('api.updated_success'));
    }

    public function destroy(Bus $bus): JsonResponse
    {
        $bus->delete();
        return successResponse(msg: __('api.deleted_success'));
    }

    public function destroyAll(DeleteAllRequest $request): JsonResponse
    {
        Bus::whereIn('id', $request->ids)->delete();
        return successResponse(msg: __('api.deleted_success'));
    }
}
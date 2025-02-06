<?php

namespace App\Http\Controllers\API;

use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\TravelRequest;
use App\Http\Requests\Global\PageRequest;
use App\Http\Resources\API\TravelResource;
use App\Http\Requests\Global\DeleteAllRequest;

class TravelController extends Controller
{
    public function index(PageRequest $request): JsonResponse
    {
        $query = app(Pipeline::class)
            ->send(Travel::query())
            ->through([])
            ->thenReturn();
        return successResponse(fetchData($query, $request->pageSize, TravelResource::class));
    }

    public function store(TravelRequest $request): JsonResponse
    {
        $travel = Travel::create($request->validated());
        return successResponse(new TravelResource($travel), __('api.created_success'));
    }

    public function show(Travel $travel): JsonResponse
    {
        return successResponse(new TravelResource($travel));
    }

    public function update(travelRequest $request, Travel $travel): JsonResponse
    {
        $travel->update($request->validated());
        return successResponse(new TravelResource($travel), __('api.updated_success'));
    }

    public function destroy(Travel $travel): JsonResponse
    {
        $travel->delete();
        return successResponse(msg: __('api.deleted_success'));
    }

    public function destroyAll(DeleteAllRequest $request): JsonResponse
    {
        Travel::whereIn('id', $request->ids)->delete();
        return successResponse(msg: __('api.deleted_success'));
    }
}
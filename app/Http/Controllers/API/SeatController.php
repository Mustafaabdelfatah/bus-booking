<?php

namespace App\Http\Controllers\API;

use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\SeatRequest;
use App\Http\Resources\API\SeatResource;
use App\Http\Requests\Global\PageRequest;
use App\Http\Requests\Global\DeleteAllRequest;

class SeatController extends Controller
{
    public function index(PageRequest $request): JsonResponse
    {
        $query = app(Pipeline::class)
            ->send(Seat::query())
            ->through([])
            ->thenReturn();
        return successResponse(fetchData($query, $request->pageSize, SeatResource::class));
    }

    public function store(SeatRequest $request): JsonResponse
    {
        $seat = Seat::create($request->validated());
        return successResponse(new SeatResource($seat), __('api.created_success'));
    }

    public function show(Seat $seat): JsonResponse
    {
        return successResponse(new SeatResource($seat));
    }

    public function update(SeatRequest $request, Seat $seat): JsonResponse
    {
        $seat->update($request->validated());
        return successResponse(new SeatResource($seat), __('api.updated_success'));
    }

    public function destroy(Seat $seat): JsonResponse
    {
        $seat->delete();
        return successResponse(msg: __('api.deleted_success'));
    }

    public function destroyAll(DeleteAllRequest $request): JsonResponse
    {
        Seat::whereIn('id', $request->ids)->delete();
        return successResponse(msg: __('api.deleted_success'));
    }
}
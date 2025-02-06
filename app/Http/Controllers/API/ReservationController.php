<?php

namespace App\Http\Controllers\API;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;
use App\Http\Controllers\Controller;
use App\Http\Requests\Global\PageRequest;
use App\Http\Requests\API\ReservationRequest;
use App\Http\Requests\Global\DeleteAllRequest;
use App\Http\Resources\API\ReservationResource;

class ReservationController extends Controller
{
    public function index(PageRequest $request): JsonResponse
    {
        $query = app(Pipeline::class)
            ->send(Reservation::query())
            ->through([])
            ->thenReturn();
        return successResponse(fetchData($query, $request->pageSize, ReservationResource::class));
    }

    public function store(ReservationRequest $request): JsonResponse
    {
        $reservation = Reservation::create($request->validated());
        return successResponse(new ReservationResource($reservation), __('api.created_success'));
    }

    public function show(Reservation $reservation): JsonResponse
    {
        return successResponse(new ReservationResource($reservation));
    }

    public function update(ReservationRequest $request, Reservation $reservation): JsonResponse
    {
        $reservation->update($request->validated());
        return successResponse(new ReservationResource($reservation), __('api.updated_success'));
    }

    public function destroy(Reservation $reservation): JsonResponse
    {
        $reservation->delete();
        return successResponse(msg: __('api.deleted_success'));
    }

    public function destroyAll(DeleteAllRequest $request): JsonResponse
    {
        Reservation::whereIn('id', $request->ids)->delete();
        return successResponse(msg: __('api.deleted_success'));
    }
}

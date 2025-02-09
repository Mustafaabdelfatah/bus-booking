<?php

namespace App\Http\Controllers\API;

use App\Models\Seat;
use App\Models\Travel;
use Illuminate\Http\Request;
use App\Models\SeatReservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\SeatRequest;
use App\Http\Resources\API\SeatResource;
use App\Http\Requests\Global\PageRequest;
use App\Http\Requests\Global\DeleteAllRequest;

class SeatController extends Controller
{
    public function index(PageRequest $request): JsonResponse
    {
        $query = app(Pipeline::class)->send(Seat::query())->through([])->thenReturn();
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

    public function availableSeats($travelId)
    {
        $travel = Travel::with('bus.seats')->findOrFail($travelId);

        $availableSeats = $travel->bus->seats()->where('status', 'available')->get();

        return response()->json(['seats' => $availableSeats]);
    }

    public function reserveSeat(Request $request)
    {
        $request->validate([
            'travel_id' => 'required|exists:trips,id',
            'seat_id' => 'required|exists:seats,id',
            'client_id' => 'required|exists:clients,id',
        ]);

        $seat = Seat::findOrFail($request->seat_id);

        if ($seat->status === 'reserved') {
            return response()->json(['message' => 'Seat already booked'], 400);
        }

        DB::transaction(function () use ($seat, $request) {
            // Reserve seat
            $seat->update(['status' => 'reserved']);

            // Save reservation
            SeatReservation::create([
                'travel_id' => $request->travel_id,
                'seat_id' => $seat->id,
                'customer_id' => $request->customer_id,
                'status' => 'confirmed',
            ]);
        });

        return response()->json(['message' => 'Seat reserved successfully']);
    }

    public function cancelReservation($reservationId)
    {
        $reservation = SeatReservation::findOrFail($reservationId);

        DB::transaction(function () use ($reservation) {
            $reservation->update(['status' => 'canceled']);
            $reservation->seat->update(['status' => 'available']);
        });

        return response()->json(['message' => 'Seat reservation canceled']);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\SeatReservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Global\PageRequest;
use App\Http\Requests\API\ReservationRequest;
use App\Http\Requests\Global\DeleteAllRequest;
use App\Http\Resources\API\ReservationResource;
use App\Http\Requests\API\ConfirmPaymentRequest;

class ReservationController extends Controller
{
    public function index(PageRequest $request): JsonResponse
    {
        $query = app(Pipeline::class)->send(Reservation::query())->through([])->thenReturn();
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
    public function confirmPayment(ConfirmPaymentRequest $request, Reservation $reservation)
    {
        $reservation->update([
            'is_paid' => $request->is_paid,
        ]);

        return response()->json([
            'message' => __('messages.payment_confirmed'),
            'data' => new ReservationResource($reservation),
        ]);
    }

    // public function exportByCountry($country)
    // {
    //     $customers = Client::where('country', $country)->get();

    //     $pdf = PDF::loadView('exports.customers_by_country', compact('customers'));

    //     return $pdf->download("customers_{$country}.pdf");
    // }



}

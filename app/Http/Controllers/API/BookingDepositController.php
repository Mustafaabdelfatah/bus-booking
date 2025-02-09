<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\BookingDeposit;
use App\Http\Controllers\Controller;

class BookingDepositController extends Controller
{
    public function index()
    {
        $deposits = BookingDeposit::with('user')->get();
        return BookingDepositResource::collection($deposits);
    }

    // إضافة حجز لعهدة الموظف
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_bookings' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $deposit = BookingDeposit::create([
            'user_id' => $request->user_id,
            'total_bookings' => $request->total_bookings,
            'total_amount' => $request->total_amount,
            'is_settled' => false
        ]);

        return new BookingDepositResource($deposit);
    }

    // تصفية العهدة
    public function settle($id)
    {
        $deposit = BookingDeposit::findOrFail($id);
        $deposit->update(['is_settled' => true]);

        return response()->json(['message' => __('messages.deposit_settled')]);
    }

    // حذف العهدة (في حال الإدخال الخاطئ)
    public function destroy($id)
    {
        $deposit = BookingDeposit::findOrFail($id);
        $deposit->delete();

        return response()->json(['message' => __('messages.deposit_deleted')]);
    }
}

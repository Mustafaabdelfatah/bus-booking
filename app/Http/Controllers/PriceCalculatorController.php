<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use Illuminate\Http\Request;

class PriceCalculatorController extends Controller
{
    public function calculate(Request $request)
    {
        $request->validate([
            'travel_id' => 'required|exists:travels,id',
            'adults' => 'required|integer|min:0',
            'children' => 'required|integer|min:0',
        ]);

        $travel = Travel::findOrFail($request->travel_id);
        $adult_price = $travel->ticket_price;
        // $child_fee = 20;
        $child_price = $travel->ticket_price * 0.5;

        $total_price = ($request->adults * $adult_price) + ($request->children * $child_price);

        return response()->json([
            'travel_id' => $request->travel_id,
            'adults' => $request->adults,
            'children' => $request->children,
            'total_price' => $total_price,
        ]);
    }
}
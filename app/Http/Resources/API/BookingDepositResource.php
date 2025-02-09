<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingDepositResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee' => new EmployeeResource($this->user),
            'total_bookings' => $this->total_bookings,
            'total_amount' => $this->total_amount,
            'is_settled' => $this->is_settled,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}

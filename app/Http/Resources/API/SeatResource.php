<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeatResource extends JsonResource
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
            'bus' => new BusResource($this->bus),
            'seat_number' => $this->seat_number,
            'status' => $this->status->value,
            'type' => $this->type->value,
        ];
    }
}

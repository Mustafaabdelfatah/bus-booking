<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelResource extends JsonResource
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
            'departure_station' => $this->departure_station,
            'arrival_station' => $this->arrival_station,
            'departure_time' => $this->departure_time->format('Y-m-d H:i:s'),
            'available_seats' => $this->available_seats,
            'ticket_price' => $this->ticket_price,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'client' => new ClientResource($this->client),
            'travel' => new TravelResource($this->travel),
            'passenger_type' => $this->passenger_type,
            'price' => $this->price,
            'is_paid' => (bool) $this->is_paid,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
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
            'travel_id' => $this->travel_id,
            'seat_number' => $this->seat_number,
            'is_reserved' => (bool) $this->is_reserved,
            // 'reserved_by' => $this->reserved_by ? [
            //     'id' => $this->reserved_by->id,
            //     'name' => $this->reserved_by->name
            // ] : null,
        ];
    }
}
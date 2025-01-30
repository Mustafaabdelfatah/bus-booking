<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'name' => $this->getTranslations('name'),
            'mother_name' => $this->mother_name,
            'passport_number' => $this->passport_number,
            'birth_date' => $this->birth_date->format('Y-m-d'),
            'issue_date' => $this->issue_date->format('Y-m-d'),
            'expiry_date' => $this->expiry_date->format('Y-m-d'),
            'phone' => $this->phone,
            'passport_image' => $this->passport_image ? asset("storage/{$this->passport_image}") : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
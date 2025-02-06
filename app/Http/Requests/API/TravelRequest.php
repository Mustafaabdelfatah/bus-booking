<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class TravelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bus_id' => 'required|exists:buses,id',
            'departure_station' => 'required|string|max:255',
            'arrival_station' => 'required|string|max:255',
            'departure_time' => 'required|date|after:now',
            'available_seats' => 'required|integer|min:1',
            'ticket_price' => 'required|numeric|min:0',
        ];
    }
}

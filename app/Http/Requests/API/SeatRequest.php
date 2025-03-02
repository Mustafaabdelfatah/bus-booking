<?php

namespace App\Http\Requests\API;

use App\Enums\SeatTypeEnum;
use App\Enums\SeatStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class SeatRequest extends FormRequest
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
            'bus_id' => ['required', 'exists:buses,id'],
            'seat_number' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'in:' . implode(',', SeatTypeEnum::values())],
            'status' => ['required', 'in:' . implode(',', SeatStatusEnum::values())],
        ];
    }
}
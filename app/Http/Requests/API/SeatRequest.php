<?php

namespace App\Http\Requests\API;

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
            'bus_id' => 'required|exists:travels,id',
            'seats' => 'required|array|min:1',
            'seats.*' => 'required|string|distinct|regex:/^[A-Z]\d+$/', // Example: "A1", "B3"
            'adults' => 'required|integer|min:1',
            'children' => 'required|integer|min:0',
        ];
    }
}
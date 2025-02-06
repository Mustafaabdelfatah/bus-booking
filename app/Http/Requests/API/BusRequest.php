<?php

namespace App\Http\Requests\API;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class BusRequest extends FormRequest
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
            'number' => 'required|string|unique:buses,number',
            'number'       => [
                'required',
                'integer',
                Rule::unique('buses', 'number')->ignore($this->route('bus')),
            ],
            'capacity' => 'required|integer|min:1',
            'type' => 'required|string|max:255',
        ];
    }
}
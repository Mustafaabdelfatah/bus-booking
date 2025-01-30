<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'passport_number' => 'required|string|max:50|unique:clients,passport_number,' . $this->client?->id,
            'birth_date' => 'required|date',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'phone' => 'required|string|max:20|unique:clients,phone,' . $this->client?->id,
            'passport_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
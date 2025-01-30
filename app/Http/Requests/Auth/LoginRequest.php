<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    protected string $userType;

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->userType = $this->route('userType');
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required_if:userType,employee',
                'nullable',
                'email',
            ],
            'phone' => [
                'required_if:userType,client,email,null',
                'nullable',
                'regex:/^\+?[1-9]\d{1,14}$/',
            ],
            'email' => [
                'required_if:userType,employee',
                'nullable',
                'email',
            ],
        ];
    }
}
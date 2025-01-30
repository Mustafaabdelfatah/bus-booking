<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
{

    public function rules(): array
    {
        $permission = $this->route('permission');

        return [
            'name' => 'required',
            'display_name' => ['required', 'array'],
            'display_name.ar' => ['required', 'string', 'max:100'],
            'display_name.en' => ['required', 'string', 'max:100'],
            'group' => 'required'
        ];
    }
}

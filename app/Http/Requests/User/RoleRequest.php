<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{

    public function rules(): array
    {
        $role = $this->route('role');

        return [
            'name' => ['required', Rule::unique('roles', 'name')->ignore($role)],
            'display_name' => ['required', 'array'],
            'display_name.ar' => ['required', 'string', 'max:100'],
            'display_name.en' => ['required', 'string', 'max:100'],
            'permissions' => 'required|array',
            'permissions.*' => 'required|exists:permissions,name',
        ];
    }
}

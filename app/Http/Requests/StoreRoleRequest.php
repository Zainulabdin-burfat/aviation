<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
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
            'role_name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'role_name.required' => 'The role name is required.',
            'role_name.string' => 'The role name must be a string.',
            'role_name.max' => 'The role name must not exceed 255 characters.',
            'role_name.unique' => 'The role name has already been taken.',
            'permissions.required' => 'At least one permission is required.',
            'permissions.array' => 'Invalid data format for permissions.',
            'permissions.*.exists' => 'Invalid permission selected.',
        ];
    }
}

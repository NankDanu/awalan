<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit-permissions');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $permission = $this->route('permission');
        $permissionId = $permission?->id ?? null;

        return [
            'name' => ['required', 'string', 'max:100', 'unique:sy_permissions,name,' . $permissionId],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama permission wajib diisi.',
            'name.max' => 'Nama permission maksimal 100 karakter.',
            'name.unique' => 'Nama permission sudah digunakan.',
        ];
    }
}

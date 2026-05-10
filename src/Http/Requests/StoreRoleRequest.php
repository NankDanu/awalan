<?php

declare(strict_types=1);

namespace Nank\Awalan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-roles');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:sy_roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:sy_permissions,name'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama role wajib diisi.',
            'name.max' => 'Nama role maksimal 100 karakter.',
            'name.unique' => 'Nama role sudah digunakan.',
            'permissions.array' => 'Format permission tidak valid.',
            'permissions.*.exists' => 'Permission tidak valid.',
        ];
    }
}

<?php

declare(strict_types=1);

namespace Org\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit-roles');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $role = $this->route('role');
        $roleId = $role?->id ?? null;

        return [
            'name' => ['required', 'string', 'max:100', 'unique:sy_roles,name,' . $roleId],
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

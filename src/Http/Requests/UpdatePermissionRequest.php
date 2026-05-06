<?php

declare(strict_types=1);

namespace Org\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit-permissions');
    }

    /**
     * @return array<string, mixed>
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

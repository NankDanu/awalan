<?php

declare(strict_types=1);

namespace App\Http\Requests\Catat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkspaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $workspace = $this->route('workspace');
        $workspaceId = is_object($workspace) ? $workspace->id : $workspace;

        return [
            'name' => ['required', 'string', 'max:255'],
            'client_id' => ['nullable', 'exists:ct_clients,id'],
            'is_project' => ['boolean'],
            'status' => ['nullable', 'in:open,ongoing,closed'],
            'type_tag' => ['nullable', 'in:one-time,maintenance,saas,retainer'],
            'description' => ['nullable', 'string'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('ct_workspaces', 'slug')->ignore($workspaceId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama workspace wajib diisi.',
            'name.max' => 'Nama workspace maksimal 255 karakter.',
            'client_id.exists' => 'Klien tidak ditemukan.',
            'status.in' => 'Status harus open, ongoing, atau closed.',
            'type_tag.in' => 'Tipe proyek harus one-time, maintenance, saas, atau retainer.',
            'slug.unique' => 'Slug workspace sudah digunakan.',
        ];
    }
}

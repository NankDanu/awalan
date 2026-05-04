<?php

declare(strict_types=1);

namespace App\Http\Requests\Catat;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkspaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'client_id' => ['nullable', 'exists:ct_clients,id'],
            'is_project' => ['boolean'],
            'status' => ['nullable', 'in:open,ongoing,closed'],
            'type_tag' => ['nullable', 'in:one-time,maintenance,saas,retainer'],
            'description' => ['nullable', 'string'],
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
        ];
    }
}

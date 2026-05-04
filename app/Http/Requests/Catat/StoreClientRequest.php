<?php

declare(strict_types=1);

namespace App\Http\Requests\Catat;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['nullable', 'in:active,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama klien wajib diisi.',
            'name.max' => 'Nama klien maksimal 255 karakter.',
            'email.email' => 'Format email tidak valid.',
            'status.in' => 'Status klien harus active atau inactive.',
        ];
    }
}

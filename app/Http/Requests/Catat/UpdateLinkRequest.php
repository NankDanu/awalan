<?php

declare(strict_types=1);

namespace App\Http\Requests\Catat;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'url'         => ['required', 'url', 'max:2048'],
            'link_type'   => ['required', 'in:git,storage,password,staging,monitoring,custom'],
            'environment' => ['nullable', 'string', 'max:100'],
            'link_notes'  => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'     => 'Label link wajib diisi.',
            'title.max'          => 'Label link maksimal 255 karakter.',
            'url.required'       => 'URL wajib diisi.',
            'url.url'            => 'Format URL tidak valid.',
            'link_type.required' => 'Tipe link wajib dipilih.',
            'link_type.in'       => 'Tipe link tidak valid.',
        ];
    }
}

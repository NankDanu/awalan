<?php

declare(strict_types=1);

namespace App\Http\Requests\Catat;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content_md' => ['nullable', 'string'],
            'last_known_updated_at' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('catat.validation.title_required'),
            'title.max' => __('catat.validation.title_max'),
            'content_md.string' => __('catat.validation.content_string'),
            'last_known_updated_at.string' => __('catat.validation.version_string'),
        ];
    }
}

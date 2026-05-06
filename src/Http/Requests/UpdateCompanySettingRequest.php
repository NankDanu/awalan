<?php

declare(strict_types=1);

namespace Org\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Org\Base\Models\CompanySetting;

class UpdateCompanySettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $setting = CompanySetting::where('is_active', true)->first()
            ?? CompanySetting::first();
        $excludeId = $setting?->id;

        return [
            'company_name' => ['required', 'string', 'max:255', 'unique:cf_company_settings,company_name,' . $excludeId],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,ico,svg', 'max:512'],
            'login_background' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'],
            'primary_color' => ['nullable', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
            'secondary_color' => ['nullable', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'company_name.required' => 'Nama perusahaan wajib diisi.',
            'company_name.unique' => 'Nama perusahaan sudah terdaftar.',
            'email.email' => 'Format email tidak valid.',
            'website.url' => 'Format URL tidak valid.',
            'logo.image' => 'File logo harus berupa gambar.',
            'logo.mimes' => 'Format logo harus JPEG, PNG, JPG, GIF, atau SVG.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
            'favicon.image' => 'File favicon harus berupa gambar.',
            'favicon.mimes' => 'Format favicon harus JPEG, PNG, JPG, GIF, ICO, atau SVG.',
            'login_background.image' => 'File background login harus berupa gambar.',
            'login_background.max' => 'Ukuran background login maksimal 5MB.',
            'primary_color.regex' => 'Format warna primer harus hex (#RRGGBB).',
            'secondary_color.regex' => 'Format warna sekunder harus hex (#RRGGBB).',
        ];
    }
}

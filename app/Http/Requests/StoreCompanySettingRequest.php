<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanySettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Add permission check as needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255', 'unique:cf_company_settings,company_name'],
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
     * Get the error messages for the defined validation rules.
     *
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

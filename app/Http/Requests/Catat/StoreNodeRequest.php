<?php

declare(strict_types=1);

namespace App\Http\Requests\Catat;

use Illuminate\Foundation\Http\FormRequest;

class StoreNodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'workspace_id' => ['required', 'exists:ct_workspaces,id'],
            'parent_id' => ['nullable', 'exists:ct_nodes,id'],
            'type' => ['required', 'in:folder,note,link'],
            'title' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'required_if:type,link', 'url', 'max:2048'],
            'link_type' => ['nullable', 'required_if:type,link', 'in:git,storage,password,staging,monitoring,custom'],
            'environment' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'workspace_id.required' => 'Workspace wajib dipilih.',
            'workspace_id.exists' => 'Workspace tidak ditemukan.',
            'parent_id.exists' => 'Parent node tidak ditemukan.',
            'type.required' => 'Tipe node wajib diisi.',
            'type.in' => 'Tipe node harus folder, note, atau link.',
            'title.required' => 'Judul node wajib diisi.',
            'title.max' => 'Judul node maksimal 255 karakter.',
            'url.required_if' => 'URL wajib diisi untuk node tipe link.',
            'url.url' => 'Format URL tidak valid.',
            'link_type.required_if' => 'Tipe link wajib dipilih.',
            'link_type.in' => 'Tipe link tidak valid.',
        ];
    }
}

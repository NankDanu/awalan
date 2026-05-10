<?php

declare(strict_types=1);

namespace Nank\Awalan\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Nank\Awalan\Models\CompanySetting;

class CompanySettingService
{
    public function getActive(): ?CompanySetting
    {
        return CompanySetting::where('is_active', true)->first();
    }

    public function getById(int $id): CompanySetting
    {
        return CompanySetting::findOrFail($id);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): CompanySetting
    {
        DB::beginTransaction();

        try {
            $companySetting = CompanySetting::create($this->prepareData($data));

            DB::commit();

            return $companySetting;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): CompanySetting
    {
        DB::beginTransaction();

        try {
            $companySetting = CompanySetting::findOrFail($id);
            $this->deleteOldFiles($companySetting, $data);
            $companySetting->update($this->prepareData($data));

            DB::commit();

            return $companySetting;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();

        try {
            $companySetting = CompanySetting::findOrFail($id);
            $this->deleteFiles($companySetting);
            $companySetting->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function prepareData(array $data): array
    {
        $prepared = [
            'company_name' => $data['company_name'] ?? null,
            'address' => $data['address'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'website' => $data['website'] ?? null,
            'description' => $data['description'] ?? null,
            'primary_color' => $data['primary_color'] ?? '#3B82F6',
            'secondary_color' => $data['secondary_color'] ?? '#10B981',
            'is_active' => $data['is_active'] ?? false,
        ];

        foreach (['logo' => 'company/logos', 'favicon' => 'company/favicons', 'login_background' => 'company/backgrounds'] as $field => $path) {
            if (! empty($data[$field])) {
                $prepared[$field] = $data[$field]->store($path, 'public');
            }
        }

        return $prepared;
    }

    /**
     * @param array<string, mixed> $data
     */
    private function deleteOldFiles(CompanySetting $setting, array $data): void
    {
        foreach (['logo', 'favicon', 'login_background'] as $field) {
            if (! empty($data[$field]) && $setting->$field) {
                Storage::disk('public')->delete($setting->$field);
            }
        }
    }

    private function deleteFiles(CompanySetting $setting): void
    {
        foreach (['logo', 'favicon', 'login_background'] as $field) {
            if ($setting->$field) {
                Storage::disk('public')->delete($setting->$field);
            }
        }
    }
}

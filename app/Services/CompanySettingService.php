<?php

namespace App\Services;

use App\Models\CompanySetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanySettingService
{
    /**
     * Get the active company setting.
     *
     * @return CompanySetting|null
     */
    public function getActive(): ?CompanySetting
    {
        return CompanySetting::where('is_active', true)->first();
    }

    /**
     * Get all company settings with pagination.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15)
    {
        return CompanySetting::paginate($perPage);
    }

    /**
     * Get a company setting by ID.
     *
     * @param int $id
     * @return CompanySetting
     */
    public function getById(int $id): CompanySetting
    {
        return CompanySetting::findOrFail($id);
    }

    /**
     * Create a new company setting.
     *
     * @param array<string, mixed> $data
     * @return CompanySetting
     * @throws \Exception
     */
    public function create(array $data): CompanySetting
    {
        DB::beginTransaction();

        try {
            $preparedData = $this->prepareData($data);

            $companySetting = CompanySetting::create($preparedData);

            DB::commit();
            return $companySetting;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update a company setting.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return CompanySetting
     * @throws \Exception
     */
    public function update(int $id, array $data): CompanySetting
    {
        DB::beginTransaction();

        try {
            $companySetting = CompanySetting::findOrFail($id);

            // Delete old files if new files are uploaded
            $this->deleteOldFiles($companySetting, $data);

            $preparedData = $this->prepareData($data);

            $companySetting->update($preparedData);

            DB::commit();
            return $companySetting;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete a company setting.
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id): bool
    {
        DB::beginTransaction();

        try {
            $companySetting = CompanySetting::findOrFail($id);

            // Delete associated files
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
     * Prepare data for create/update operations.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function prepareData(array $data): array
    {
        $preparedData = [
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

        // Handle file uploads
        if (isset($data['logo']) && $data['logo']) {
            $preparedData['logo'] = $data['logo']->store('company/logos', 'public');
        }

        if (isset($data['favicon']) && $data['favicon']) {
            $preparedData['favicon'] = $data['favicon']->store('company/favicons', 'public');
        }

        if (isset($data['login_background']) && $data['login_background']) {
            $preparedData['login_background'] = $data['login_background']->store('company/backgrounds', 'public');
        }

        return $preparedData;
    }

    /**
     * Delete old files if new files are being uploaded.
     *
     * @param CompanySetting $companySetting
     * @param array<string, mixed> $data
     * @return void
     */
    private function deleteOldFiles(CompanySetting $companySetting, array $data): void
    {
        $fileFields = ['logo', 'favicon', 'login_background'];

        foreach ($fileFields as $field) {
            if (isset($data[$field]) && $data[$field] && $companySetting->$field) {
                Storage::disk('public')->delete($companySetting->$field);
            }
        }
    }

    /**
     * Delete all associated files.
     *
     * @param CompanySetting $companySetting
     * @return void
     */
    private function deleteFiles(CompanySetting $companySetting): void
    {
        $fileFields = ['logo', 'favicon', 'login_background'];

        foreach ($fileFields as $field) {
            if ($companySetting->$field) {
                Storage::disk('public')->delete($companySetting->$field);
            }
        }
    }
}

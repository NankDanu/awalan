<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    /**
     * Get all permissions with pagination.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15)
    {
        return Permission::query()->orderBy('name')->paginate($perPage);
    }

    /**
     * Get server-side datatable payload.
     *
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getDatatablePayload(array $params): array
    {
        $searchValue = trim((string) ($params['search']['value'] ?? ''));
        $start = max(0, (int) ($params['start'] ?? 0));
        $length = max(1, (int) ($params['length'] ?? 10));

        $columns = ['name', null];
        $orderIndex = (int) ($params['order'][0]['column'] ?? 0);
        $orderDir = strtolower((string) ($params['order'][0]['dir'] ?? 'asc')) === 'desc' ? 'desc' : 'asc';
        $orderColumn = $columns[$orderIndex] ?? 'name';

        $baseQuery = Permission::query();
        $recordsTotal = $baseQuery->count();

        $filteredQuery = Permission::query();
        if ($searchValue !== '') {
            $filteredQuery->where('name', 'like', '%' . $searchValue . '%');
        }

        $recordsFiltered = $filteredQuery->count();

        if ($orderColumn !== null) {
            $filteredQuery->orderBy($orderColumn, $orderDir);
        } else {
            $filteredQuery->orderBy('name');
        }

        $permissions = $filteredQuery
            ->skip($start)
            ->take($length)
            ->get();

        return [
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $permissions,
        ];
    }

    /**
     * Get a permission by ID.
     *
     * @param int $id
     * @return Permission
     */
    public function getById(int $id): Permission
    {
        return Permission::findOrFail($id);
    }

    /**
     * Create a new permission.
     *
     * @param array<string, mixed> $data
     * @return Permission
     * @throws \Exception
     */
    public function create(array $data): Permission
    {
        DB::beginTransaction();

        try {
            $permission = Permission::create([
                'name' => $data['name'],
                'guard_name' => 'web',
            ]);

            DB::commit();
            return $permission;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update a permission.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return Permission
     * @throws \Exception
     */
    public function update(int $id, array $data): Permission
    {
        DB::beginTransaction();

        try {
            $permission = Permission::findOrFail($id);
            $permission->update([
                'name' => $data['name'],
            ]);

            DB::commit();
            return $permission;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete a permission.
     *
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function delete(int $id): void
    {
        DB::beginTransaction();

        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

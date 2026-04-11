<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    /**
     * Get all roles with pagination.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15)
    {
        return Role::query()
            ->with('permissions')
            ->orderBy('name')
            ->paginate($perPage);
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

        $columns = ['name', null, 'permissions_count', null];
        $orderIndex = (int) ($params['order'][0]['column'] ?? 0);
        $orderDir = strtolower((string) ($params['order'][0]['dir'] ?? 'asc')) === 'desc' ? 'desc' : 'asc';
        $orderColumn = $columns[$orderIndex] ?? 'name';

        $baseQuery = Role::query();
        $recordsTotal = $baseQuery->count();

        $filteredQuery = Role::query()
            ->with('permissions')
            ->withCount('permissions');

        if ($searchValue !== '') {
            $filteredQuery->where(function ($query) use ($searchValue): void {
                $query->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('permissions', function ($permissionQuery) use ($searchValue): void {
                        $permissionQuery->where('name', 'like', '%' . $searchValue . '%');
                    });
            });
        }

        $recordsFiltered = $filteredQuery->count();

        if ($orderColumn !== null) {
            $filteredQuery->orderBy($orderColumn, $orderDir);
        } else {
            $filteredQuery->orderBy('name');
        }

        $roles = $filteredQuery
            ->skip($start)
            ->take($length)
            ->get();

        return [
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $roles,
        ];
    }

    /**
     * Get a role by ID.
     *
     * @param int $id
     * @return Role
     */
    public function getById(int $id): Role
    {
        return Role::findOrFail($id);
    }

    /**
     * Create a new role.
     *
     * @param array<string, mixed> $data
     * @return Role
     * @throws \Exception
     */
    public function create(array $data): Role
    {
        DB::beginTransaction();

        try {
            $role = Role::create([
                'name' => $data['name'],
                'guard_name' => 'web',
            ]);

            $permissions = $data['permissions'] ?? [];
            $role->syncPermissions($permissions);

            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update a role.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return Role
     * @throws \Exception
     */
    public function update(int $id, array $data): Role
    {
        DB::beginTransaction();

        try {
            $role = Role::findOrFail($id);
            $role->update([
                'name' => $data['name'],
            ]);

            $permissions = $data['permissions'] ?? [];
            $role->syncPermissions($permissions);

            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete a role.
     *
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function delete(int $id): void
    {
        DB::beginTransaction();

        try {
            $role = Role::findOrFail($id);

            if ($role->name === 'admin') {
                throw new \RuntimeException('Role admin tidak dapat dihapus.');
            }

            $role->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get all permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Permission>
     */
    public function getAllPermissions()
    {
        return Permission::query()->orderBy('name')->get();
    }
}

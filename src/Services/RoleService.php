<?php

declare(strict_types=1);

namespace Org\Base\Services;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    /**
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

        $recordsTotal = Role::query()->count();

        $filteredQuery = Role::query()->with('permissions')->withCount('permissions');
        if ($searchValue !== '') {
            $filteredQuery->where(function ($query) use ($searchValue): void {
                $query->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('permissions', function ($q) use ($searchValue): void {
                        $q->where('name', 'like', '%' . $searchValue . '%');
                    });
            });
        }

        $recordsFiltered = $filteredQuery->count();

        if ($orderColumn !== null) {
            $filteredQuery->orderBy($orderColumn, $orderDir);
        } else {
            $filteredQuery->orderBy('name');
        }

        $roles = $filteredQuery->skip($start)->take($length)->get();

        return [
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $roles,
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Permission>
     */
    public function getAllPermissions()
    {
        return Permission::query()->orderBy('name')->get();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): Role
    {
        DB::beginTransaction();

        try {
            $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']);
            $role->syncPermissions($data['permissions'] ?? []);

            DB::commit();

            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): Role
    {
        DB::beginTransaction();

        try {
            $role = Role::findOrFail($id);
            $role->update(['name' => $data['name']]);
            $role->syncPermissions($data['permissions'] ?? []);

            DB::commit();

            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

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
}

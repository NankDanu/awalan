<?php

declare(strict_types=1);

namespace Nank\Awalan\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Nank\Awalan\Models\User;
use Spatie\Permission\Models\Role;

class UserService
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

        $columns = ['name', 'email', null, 'is_active', null];
        $orderIndex = (int) ($params['order'][0]['column'] ?? 0);
        $orderDir = strtolower((string) ($params['order'][0]['dir'] ?? 'asc')) === 'desc' ? 'desc' : 'asc';
        $orderColumn = $columns[$orderIndex] ?? 'name';

        $recordsTotal = User::query()->count();

        $filteredQuery = User::query()->with('roles');
        if ($searchValue !== '') {
            $filteredQuery->where(function ($query) use ($searchValue): void {
                $query->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhere('email', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('roles', function ($roleQuery) use ($searchValue): void {
                        $roleQuery->where('name', 'like', '%' . $searchValue . '%');
                    });

                if (in_array(strtolower($searchValue), ['aktif', 'active'], true)) {
                    $query->orWhere('is_active', true);
                }
                if (in_array(strtolower($searchValue), ['nonaktif', 'inactive'], true)) {
                    $query->orWhere('is_active', false);
                }
            });
        }

        $recordsFiltered = $filteredQuery->count();

        if ($orderColumn !== null) {
            $filteredQuery->orderBy($orderColumn, $orderDir);
        } else {
            $filteredQuery->orderByDesc('id');
        }

        $users = $filteredQuery->skip($start)->take($length)->get();

        return [
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $users,
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Role>
     */
    public function getAllRoles()
    {
        return Role::query()->orderBy('name')->get();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): User
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'is_active' => $data['is_active'] ?? true,
            ]);

            $user->assignRole($data['role'] ?? 'user');

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): User
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);

            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'is_active' => $data['is_active'] ?? true,
            ];

            if (! empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            if (! empty($data['role'])) {
                $user->syncRoles($data['role']);
            }

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $user->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

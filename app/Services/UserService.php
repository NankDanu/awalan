<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Get all users with pagination.
     *
     * @param int $perPage
     * @param string $search
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15, string $search = '')
    {
        $search = trim($search);

        return User::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($subQuery) use ($search): void {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
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

        $columns = [
            'name',
            'email',
            null,
            'is_active',
            null,
        ];

        $orderIndex = (int) ($params['order'][0]['column'] ?? 0);
        $orderDir = strtolower((string) ($params['order'][0]['dir'] ?? 'asc')) === 'desc' ? 'desc' : 'asc';
        $orderColumn = $columns[$orderIndex] ?? 'name';

        $baseQuery = User::query();
        $recordsTotal = $baseQuery->count();

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

        $users = $filteredQuery
            ->skip($start)
            ->take($length)
            ->get();

        return [
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $users,
        ];
    }

    /**
     * Get a user by ID.
     *
     * @param int $id
     * @return User|null
     */
    public function getById(int $id): ?User
    {
        return User::findOrFail($id);
    }

    /**
     * Create a new user.
     *
     * @param array<string, mixed> $data
     * @return User
     * @throws \Exception
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

            // Assign role if provided
            if (!empty($data['role'])) {
                $user->assignRole($data['role']);
            } else {
                $user->assignRole('user');
            }

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update a user.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return User
     * @throws \Exception
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

            // Only update password if provided
            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            // Update role if provided
            if (!empty($data['role'])) {
                $user->syncRoles($data['role']);
            }

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete a user.
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
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

    /**
     * Get all available roles.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRoles()
    {
        return \Spatie\Permission\Models\Role::all();
    }
}

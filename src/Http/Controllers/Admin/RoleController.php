<?php

declare(strict_types=1);

namespace Org\Base\Http\Controllers\Admin;

use Org\Base\Http\Requests\StoreRoleRequest;
use Org\Base\Http\Requests\UpdateRoleRequest;
use Org\Base\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct(
        private RoleService $roleService
    ) {
        $this->middleware('permission:view-roles')->only(['index']);
        $this->middleware('permission:create-roles')->only(['create', 'store']);
        $this->middleware('permission:edit-roles')->only(['edit', 'update']);
        $this->middleware('permission:delete-roles')->only(['destroy']);
    }

    public function index(): View
    {
        return view('base::base.admin.roles.index');
    }

    public function datatable(Request $request): \Illuminate\Http\JsonResponse
    {
        $payload = $this->roleService->getDatatablePayload($request->all());

        $rows = $payload['data']->map(function (Role $role): array {
            $permissions = $role->permissions->map(fn ($p): string => '<span class="kt-badge kt-badge-light kt-badge-primary kt-badge-sm">' . e($p->name) . '</span>')->implode(' ');

            if ($permissions === '') {
                $permissions = '<span class="text-xs text-muted-foreground">Tidak ada</span>';
            }

            $actions = '';
            if (Auth::user()?->can('edit-roles')) {
                $actions .= '<a href="' . route('roles.edit', $role->id) . '" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline" aria-label="Edit" title="Edit">'
                    . '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
                    . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
                    . '</svg>'
                    . '</a>';
            }

            if (Auth::user()?->can('delete-roles')) {
                if ($role->name !== 'admin') {
                    $actions .= '<form action="' . route('roles.destroy', $role->id) . '" method="POST" class="inline-block" onsubmit="return confirm(\'Yakin ingin menghapus role ini?\');">'
                        . '<input type="hidden" name="_token" value="' . csrf_token() . '">'
                        . '<input type="hidden" name="_method" value="DELETE">'
                        . '<button type="submit" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline" aria-label="Hapus" title="Hapus">'
                        . '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
                        . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>'
                        . '</svg>'
                        . '</button>'
                        . '</form>';
                } else {
                    $actions .= '<span class="text-xs text-muted-foreground">Terkunci</span>';
                }
            }

            return [
                'name' => '<span class="font-medium text-foreground">' . e($role->name) . '</span>',
                'permissions' => '<div class="flex flex-wrap gap-2">' . $permissions . '</div>',
                'count' => '<span class="text-muted-foreground">' . (int) $role->permissions_count . '</span>',
                'actions' => '<div class="flex items-center justify-end gap-2">' . $actions . '</div>',
            ];
        })->all();

        return response()->json([
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => $payload['recordsTotal'],
            'recordsFiltered' => $payload['recordsFiltered'],
            'data' => $rows,
        ]);
    }

    public function create(): View
    {
        $permissions = $this->roleService->getAllPermissions();

        return view('base::base.admin.roles.create', ['permissions' => $permissions]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        try {
            $this->roleService->create($request->validated());

            return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan role: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Role $role): View
    {
        $permissions = $this->roleService->getAllPermissions();
        $rolePermissions = $role->getPermissionNames();

        return view('base::base.admin.roles.edit', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        try {
            $this->roleService->update($role->id, $request->validated());

            return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui role: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Role $role): RedirectResponse
    {
        try {
            $this->roleService->delete($role->id);

            return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus role: ' . $e->getMessage());
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Services\PermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct(
        private PermissionService $permissionService
    ) {
        $this->middleware('permission:view-permissions')->only(['index']);
        $this->middleware('permission:create-permissions')->only(['create', 'store']);
        $this->middleware('permission:edit-permissions')->only(['edit', 'update']);
        $this->middleware('permission:delete-permissions')->only(['destroy']);
    }

    /**
     * Display a listing of permissions.
     */
    public function index(): View
    {
        return view('permissions.index');
    }

    /**
     * Get permissions for server-side datatable.
     */
    public function datatable(Request $request)
    {
        $payload = $this->permissionService->getDatatablePayload($request->all());

        $rows = $payload['data']->map(function (Permission $permission): array {
            $actions = '';
            if (Auth::user()?->can('edit-permissions')) {
                $actions .= '<a href="' . route('permissions.edit', $permission->id) . '" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline" aria-label="Edit" title="Edit">'
                    . '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
                    . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
                    . '</svg>'
                    . '</a>';
            }

            if (Auth::user()?->can('delete-permissions')) {
                $actions .= '<form action="' . route('permissions.destroy', $permission->id) . '" method="POST" class="inline-block" onsubmit="return confirm(\'Yakin ingin menghapus permission ini?\');">'
                    . '<input type="hidden" name="_token" value="' . csrf_token() . '">'
                    . '<input type="hidden" name="_method" value="DELETE">'
                    . '<button type="submit" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline" aria-label="Hapus" title="Hapus">'
                    . '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
                    . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>'
                    . '</svg>'
                    . '</button>'
                    . '</form>';
            }

            return [
                'name' => '<span class="font-medium text-foreground">' . e($permission->name) . '</span>',
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

    /**
     * Show the form for creating a new permission.
     */
    public function create(): View
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created permission in storage.
     */
    public function store(StorePermissionRequest $request): RedirectResponse
    {
        try {
            $this->permissionService->create($request->validated());

            return redirect()
                ->route('permissions.index')
                ->with('success', 'Permission berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menambahkan permission: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission): View
    {
        return view('permissions.edit', ['permission' => $permission]);
    }

    /**
     * Update the specified permission in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        try {
            $this->permissionService->update($permission->id, $request->validated());

            return redirect()
                ->route('permissions.index')
                ->with('success', 'Permission berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal memperbarui permission: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        try {
            $this->permissionService->delete($permission->id);

            return redirect()
                ->route('permissions.index')
                ->with('success', 'Permission berhasil dihapus.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus permission: ' . $e->getMessage());
        }
    }
}

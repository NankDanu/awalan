<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    /**
     * Display a listing of users.
     */
    public function index(): View
    {
        return view('users.index');
    }

    /**
     * Get users for server-side datatable.
     */
    public function datatable(Request $request)
    {
        $payload = $this->userService->getDatatablePayload($request->all());

        $rows = $payload['data']->map(function (User $user): array {
            $roles = $user->getRoleNames()->map(fn (string $role): string => '<span class="kt-badge kt-badge-light kt-badge-primary kt-badge-sm">' . e(ucfirst($role)) . '</span>')->implode(' ');

            if ($roles === '') {
                $roles = '<span class="text-muted-foreground text-xs">-</span>';
            }

            $status = $user->is_active
                ? '<span class="kt-badge kt-badge-light kt-badge-success kt-badge-sm">Aktif</span>'
                : '<span class="kt-badge kt-badge-light kt-badge-destructive kt-badge-sm">Nonaktif</span>';

            $actions = '';
            if (Auth::user()?->can('edit-users')) {
                $actions .= '<a href="' . route('users.edit', $user->id) . '" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline" aria-label="Edit" title="Edit">'
                    . '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
                    . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
                    . '</svg>'
                    . '</a>';
            }

            if (Auth::user()?->can('delete-users')) {
                $actions .= '<form action="' . route('users.destroy', $user->id) . '" method="POST" class="inline-block" onsubmit="return confirm(\'Yakin ingin menghapus pengguna ini?\');">'
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
                'name' => '<span class="font-medium text-foreground">' . e($user->name) . '</span>',
                'email' => '<span class="text-muted-foreground">' . e($user->email) . '</span>',
                'role' => $roles,
                'status' => $status,
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
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $roles = $this->userService->getAllRoles();

        return view('users.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $this->userService->create($request->validated());

            return redirect()
                ->route('users.index')
                ->with('success', 'Pengguna berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $roles = $this->userService->getAllRoles();
        $userRoles = $user->getRoleNames();

        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles,
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {
            $this->userService->update($user->id, $request->validated());

            return redirect()
                ->route('users.index')
                ->with('success', 'Pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            $this->userService->delete($user->id);

            return redirect()
                ->route('users.index')
                ->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}

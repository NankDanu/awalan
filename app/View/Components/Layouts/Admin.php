<?php

declare(strict_types=1);

namespace App\View\Components\Layouts;

use App\Services\MenuService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Admin extends Component
{
    /**
     * @var Collection<int, \App\Models\Menu>
     */
    public Collection $menus;

    public bool $showWidget;

    /**
     * @var array<int, array<string, string>>
     */
    public array $docToolbarActions = [];

    /**
     * @var array<int, array<string, string>>
     */
    public array $docComments = [];

    public function __construct(
        public ?string $title = null,
        public ?string $pageTitle = null,
        bool $showWidget = true,
        MenuService $menuService
    ) {
        $user = Auth::user();
        $this->menus = $user ? $menuService->getSidebarMenusForUser($user) : collect();
        $this->showWidget = $showWidget;

        $this->docToolbarActions = [
            // ['label' => 'Edit', 'style' => 'muted'],
            // ['label' => 'Read', 'style' => 'active'],
            // ['label' => 'Share', 'style' => 'muted'],
        ];

        if ($user) {
            $this->docComments = [
                [
                    'author' => $user->name,
                    'time' => now()->subMinutes(8)->diffForHumans(),
                    'message' => 'Layout panel sudah diselaraskan ke gaya dokumentasi dengan struktur kiri-tengah-kanan.',
                ],
                [
                    'author' => 'System',
                    'time' => now()->subMinutes(2)->diffForHumans(),
                    'message' => 'Sidebar menggunakan menu dinamis sesuai permission user aktif.',
                ],
            ];
        }
    }

    public function render(): View
    {
        return view('components.layouts.admin');
    }
}

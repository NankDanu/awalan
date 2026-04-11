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

    public function __construct(
        public ?string $title = null,
        public ?string $pageTitle = null,
        MenuService $menuService
    ) {
        $user = Auth::user();
        $this->menus = $user ? $menuService->getSidebarMenusForUser($user) : collect();
    }

    public function render(): View
    {
        return view('components.layouts.admin');
    }
}

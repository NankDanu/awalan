<?php

declare(strict_types=1);

namespace Org\Base\Services;

use Illuminate\Support\Collection;
use Org\Base\Models\Menu;
use Org\Base\Models\User;

class MenuService
{
    /**
     * @return Collection<int, Menu>
     */
    public function getSidebarMenusForUser(User $user): Collection
    {
        $menus = Menu::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $filtered = $menus->filter(function (Menu $menu) use ($user): bool {
            if (empty($menu->permission_name)) {
                return true;
            }

            return $user->can($menu->permission_name);
        });

        return $this->buildTree($filtered->groupBy('parent_id'), null);
    }

    /**
     * @param Collection<int, Collection<int, Menu>> $grouped
     * @return Collection<int, Menu>
     */
    private function buildTree(Collection $grouped, ?int $parentId): Collection
    {
        return $grouped->get($parentId, collect())->values()
            ->map(function (Menu $menu) use ($grouped): Menu {
                $menu->children = $this->buildTree($grouped, $menu->id);

                return $menu;
            })
            ->filter(function (Menu $menu): bool {
                return ! empty($menu->route_name) || ! empty($menu->url) || $menu->children->isNotEmpty();
            })
            ->values();
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Collection;

class MenuService
{
    /**
     * Get sidebar menus filtered by user permissions.
     *
     * @param User $user
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

        $grouped = $filtered->groupBy('parent_id');

        return $this->buildTree($grouped, null);
    }

    /**
     * Build a hierarchical menu tree from a grouped collection.
     *
     * @param Collection<int, Collection<int, Menu>> $grouped
     * @param int|null $parentId
     * @return Collection<int, Menu>
     */
    private function buildTree(Collection $grouped, ?int $parentId): Collection
    {
        $items = $grouped->get($parentId, collect())->values();

        return $items
            ->map(function (Menu $menu) use ($grouped): Menu {
                $menu->children = $this->buildTree($grouped, $menu->id);

                return $menu;
            })
            ->filter(function (Menu $menu): bool {
                $hasLink = !empty($menu->route_name) || !empty($menu->url);

                return $hasLink || ($menu->children->isNotEmpty());
            })
            ->values();
    }
}

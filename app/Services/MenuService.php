<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Nank\Awalan\Menu\MenuManager;

class MenuService
{
    /**
     * Get sidebar menus filtered by user permissions.
     *
     * @param User $user
     * @return Collection<int, Menu|Fluent>
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

        $dbMenus = $this->buildTree($grouped, null);

        $registeredMenus = $this->buildRegisteredMenus($user);

        return $dbMenus->concat($registeredMenus)->sortBy('sort_order')->values();
    }

    /**
     * Build menu items from MenuManager::all() as Fluent objects compatible with the sidebar view.
     *
     * @return Collection<int, Fluent>
     */
    private function buildRegisteredMenus(User $user): Collection
    {
        return collect(MenuManager::all())
            ->filter(function (array $item) use ($user): bool {
                if (empty($item['permission'])) {
                    return true;
                }

                return $user->can($item['permission']);
            })
            ->map(function (array $item): Fluent {
                $children = collect($item['children'] ?? [])->map(fn (array $child): Fluent => new Fluent([
                    'name' => $child['label'] ?? '',
                    'route_name' => $child['route'] ?? null,
                    'url' => $child['url'] ?? null,
                    'icon' => $child['icon'] ?? '',
                    'permission_name' => $child['permission'] ?? null,
                    'sort_order' => $child['order'] ?? 99,
                    'children' => collect(),
                ]));

                return new Fluent([
                    'name' => $item['label'],
                    'route_name' => $item['route'] ?? null,
                    'url' => $item['url'] ?? null,
                    'icon' => $item['icon'] ?? '',
                    'permission_name' => $item['permission'] ?? null,
                    'sort_order' => $item['order'] ?? 99,
                    'children' => $children,
                ]);
            })
            ->values();
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

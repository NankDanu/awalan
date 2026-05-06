<?php

declare(strict_types=1);

namespace Org\Base\Menu;

final class MenuManager
{
    /**
     * @var array<int, array<string, mixed>>
     */
    private static array $items = [];

    /**
     * @param array<string, mixed> $menu
     */
    public static function add(array $menu): void
    {
        self::$items[] = [
            'label' => $menu['label'] ?? '',
            'icon' => $menu['icon'] ?? '',
            'route' => $menu['route'] ?? '',
            'permission' => $menu['permission'] ?? null,
            'order' => $menu['order'] ?? 99,
            'active' => $menu['active'] ?? [],
            'children' => $menu['children'] ?? [],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        usort(self::$items, static fn (array $a, array $b): int => (int) $a['order'] <=> (int) $b['order']);

        return self::$items;
    }

    public static function clear(): void
    {
        self::$items = [];
    }
}

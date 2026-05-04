@props(['href', 'active' => false, 'icon' => 'default'])

@php
$baseClasses = 'catat_sidebar_link transition-colors';
$classes = $active 
    ? $baseClasses . ' is-active' 
    : $baseClasses;

$iconColors = [
    'home' => 'text-slate-600',
    'users' => 'text-slate-600',
    'shield' => 'text-slate-600',
    'settings' => 'text-slate-600',
    'default' => 'text-slate-500',
];

$icons = [
    'home' => 'ti ti-home-2',
    'users' => 'ti ti-users',
    'shield' => 'ti ti-shield-check',
    'settings' => 'ti ti-settings',
    'default' => 'ti ti-message-circle',
];

$iconClass = $icons[$icon] ?? $icons['default'];

if (! isset($icons[$icon]) && filled($icon)) {
    if (str_starts_with($icon, 'ti ')) {
        $iconClass = $icon;
    } else {
        $iconClass = 'ti ti-' . str_replace('_', '-', $icon);
    }
}

$iconColor = $iconColors[$icon] ?? $iconColors['default'];
$iconColorActive = $active ? 'text-slate-900' : $iconColor;
$labelClass = $active ? 'text-slate-900' : 'text-slate-600';
@endphp

<a href="{{ $href }}" 
   :title="sidebarCollapsed ? '{{ $slot }}' : ''"
   class="group {{ $classes }}"
   :class="sidebarCollapsed ? 'justify-center gap-0' : 'gap-3'">
    <i aria-hidden="true" class="flex-shrink-0 {{ $iconClass }} {{ $iconColorActive }}"></i>
    <span class="{{ $labelClass }}"
          x-cloak
          x-show="!sidebarCollapsed">
        {{ $slot }}
    </span>
</a>


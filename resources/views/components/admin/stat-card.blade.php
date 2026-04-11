@props(['label', 'value', 'icon' => '', 'color' => 'primary'])

@php
$colorClasses = [
    'primary' => 'bg-primary-500',
    'green' => 'bg-green-500',
    'yellow' => 'bg-yellow-500',
    'red' => 'bg-red-500',
    'blue' => 'bg-blue-500',
    'purple' => 'bg-purple-500',
];

$bgColor = $colorClasses[$color] ?? $colorClasses['primary'];
@endphp

<div class="card-compact">
    <div class="card-pad-compact">
        <div class="flex items-center">
            @if($icon)
            <div class="flex-shrink-0 {{ $bgColor }} rounded-md p-2">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $icon !!}
                </svg>
            </div>
            @endif
            <div class="ml-3">
                <h3 class="text-xs font-medium text-gray-500">{{ $label }}</h3>
                <p class="text-lg font-semibold text-gray-900">{{ $value }}</p>
            </div>
        </div>
    </div>
</div>

@props([
    'items' => [],
])

@if($items->isNotEmpty())
    <nav class="flex items-center gap-1 text-sm" aria-label="Breadcrumb">
        @foreach($items as $index => $item)
            @if($index > 0)
                <span class="text-slate-400">/</span>
            @endif

            @if($loop->last)
                <span class="text-slate-900 font-medium">{{ $item['label'] }}</span>
            @else
                <a href="{{ $item['url'] }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                    {{ $item['label'] }}
                </a>
            @endif
        @endforeach
    </nav>
@endif

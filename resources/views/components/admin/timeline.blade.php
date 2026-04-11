@props([
    'items' => [],
    'empty' => 'Belum ada data.',
    'groupByDate' => true,
    'locale' => 'id',
    'statusColors' => [],
])

@php
    $items = collect($items);
    $currentDate = null;
    $defaultStatusColors = [
        'submitted' => 'bg-blue-500',
        'approved' => 'bg-emerald-500',
        'rejected' => 'bg-rose-500',
        'disbursed' => 'bg-amber-500',
        'payment' => 'bg-sky-500',
    ];
    $statusColors = array_merge($defaultStatusColors, $statusColors);
    $monthLabels = [
        'id' => [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des',
        ],
        'en' => [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec',
        ],
    ];
    $locale = array_key_exists($locale, $monthLabels) ? $locale : 'id';
    $formatDateLabel = static function ($date) use ($monthLabels, $locale): ?string {
        if ($date instanceof \DateTimeInterface) {
            $month = (int) $date->format('n');
            $day = $date->format('j');
            $year = $date->format('Y');

            return sprintf('%s %s %s', $day, $monthLabels[$locale][$month] ?? $month, $year);
        }

        if (is_string($date) && $date !== '') {
            return $date;
        }

        return null;
    };
@endphp

<div class="relative space-y-4 pl-4">
    <div class="absolute left-1 top-2 bottom-2 w-px bg-gray-200"></div>
    @forelse($items as $item)
        @if($groupByDate && ($item['date'] ?? null) !== $currentDate)
            @php
                $currentDate = $item['date'] ?? null;
                $currentDateLabel = $formatDateLabel($currentDate);
            @endphp
            @if($currentDateLabel)
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    {{ $currentDateLabel }}
                </div>
            @endif
        @endif

        <div class="flex gap-3 text-xs">
            @php
                $statusKey = $item['status'] ?? null;
                $dotClass = $statusKey && isset($statusColors[$statusKey])
                    ? $statusColors[$statusKey]
                    : 'bg-gray-400';
            @endphp
            <div class="relative mt-1 h-2 w-2 rounded-full ring-2 ring-white {{ $dotClass }}"></div>
            <div class="flex-1 space-y-1">
                <div class="flex flex-wrap items-center gap-2 text-gray-500">
                    @if(!empty($item['time']))
                        <span>{{ $item['time'] }}</span>
                    @endif
                    @if(!empty($item['title']))
                        @if(!empty($item['time']))
                            <span class="text-gray-300">•</span>
                        @endif
                        <span class="font-medium text-gray-700">{{ $item['title'] }}</span>
                    @endif
                    @if(!empty($item['meta']))
                        <span class="text-gray-300">•</span>
                        <span>{{ $item['meta'] }}</span>
                    @endif
                </div>
                @if(!empty($item['description']))
                    <div class="text-gray-700">{{ $item['description'] }}</div>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center text-sm text-gray-500">{{ $empty }}</div>
    @endforelse
</div>

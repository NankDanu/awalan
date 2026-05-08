@props([
    'mode' => 'panel',
])

@if ($mode === 'sidebar')
    <div class="px-2 pb-1 text-center text-[10px] leading-4 text-slate-600">
        <p>
            Diracik santai oleh <a href="https://github.com/NankDanu" target="_blank" rel="noopener noreferrer" class="font-semibold text-blue-600 hover:text-blue-500">Nank</a>, bersama AI dan kopi <span class="text-amber-700">☕</span>
        </p>
        <p class="mt-0.5 text-[9px] text-slate-500">Dari Cikarang, dengan ❤️</p>
    </div>
@else
    <div class="mt-6 rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-center text-[11px] leading-4 text-slate-600 shadow-sm">
        <p>
            Diracik santai oleh <a href="https://github.com/NankDanu" target="_blank" rel="noopener noreferrer" class="font-semibold text-blue-600 hover:text-blue-500">Nank</a>, bersama AI dan kopi <span class="text-amber-700">☕</span>
        </p>
        <p class="mt-0.5 text-[10px] text-slate-500">Dari Cikarang, dengan ❤️</p>
    </div>
@endif

<x-layouts.admin>
    <x-slot name="title">Dashboard - AWALAN</x-slot>
    <x-slot name="pageTitle">Dashboard</x-slot>

    <div class="space-y-4">
        <div class="card-compact border border-primary-200">
            <div class="card-pad-compact">
                <h2 class="text-lg font-semibold text-gray-900">Selamat Datang, {{ auth()->user()->name }}.</h2>
                <p class="mt-2 text-sm text-gray-600">Dashboard telah dirapikan dan dependensi ke modul lama sudah dihapus. Halaman ini sekarang siap diisi ulang sesuai kebutuhan AWALAN.</p>
            </div>
        </div>

        <div class="card-compact border border-dashed border-gray-300 bg-gray-50">
            <div class="card-pad-compact">
                <h3 class="text-sm font-semibold text-gray-900">Status Dashboard</h3>
                <p class="mt-2 text-sm text-gray-600">Ringkasan modul lama sudah dihilangkan dari controller maupun tampilan dashboard.</p>
            </div>
        </div>
    </div>
</x-layouts.admin>

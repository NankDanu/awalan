<x-layouts.admin :showComments="false">
    <x-slot name="title">Dashboard Dummy - AWALAN</x-slot>
    <x-slot name="pageTitle">Dashboard - Dummy</x-slot>

    <article class="w-full min-w-0 space-y-6">
            <header>
                <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Walkthrough</p>
                <h2 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-900">Dashboard - Dummy</h2>
                <p class="mt-3 text-base text-slate-600">Data di halaman ini adalah data dummy untuk demo tampilan chart, card, dan table.</p>
            </header>

            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                {{-- Card 1: Total Pengguna (Sky/Blue) --}}
                <div class="rounded-xl bg-sky-500 p-4 shadow-md">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-sky-100">Total Pengguna</p>
                            <p class="mt-2 text-3xl font-bold text-white">1.240</p>
                            <p class="mt-1 text-xs font-medium text-sky-100">+8.4% dari bulan lalu</p>
                        </div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-sky-400/40">
                            <i class="ti ti-users text-2xl text-white"></i>
                        </span>
                    </div>
                </div>
                {{-- Card 2: Transaksi (Emerald/Green) --}}
                <div class="rounded-xl bg-emerald-500 p-4 shadow-md">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-100">Transaksi Hari Ini</p>
                            <p class="mt-2 text-3xl font-bold text-white">326</p>
                            <p class="mt-1 text-xs font-medium text-emerald-100">+12 transaksi per jam</p>
                        </div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-400/40">
                            <i class="ti ti-transfer text-2xl text-white"></i>
                        </span>
                    </div>
                </div>
                {{-- Card 3: Pendapatan (Violet) --}}
                <div class="rounded-xl bg-violet-500 p-4 shadow-md">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-violet-100">Pendapatan Bulan Ini</p>
                            <p class="mt-2 text-3xl font-bold text-white">Rp 87,5 Jt</p>
                            <p class="mt-1 text-xs font-medium text-violet-100">Target 92% tercapai</p>
                        </div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-violet-400/40">
                            <i class="ti ti-currency-dollar text-2xl text-white"></i>
                        </span>
                    </div>
                </div>
                {{-- Card 4: Tiket (Rose/Red) --}}
                <div class="rounded-xl bg-rose-500 p-4 shadow-md">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-rose-100">Tiket Terbuka</p>
                            <p class="mt-2 text-3xl font-bold text-white">19</p>
                            <p class="mt-1 text-xs font-medium text-rose-100">5 tiket prioritas tinggi</p>
                        </div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-rose-400/40">
                            <i class="ti ti-ticket text-2xl text-white"></i>
                        </span>
                    </div>
                </div>
            </section>

            <section class="grid grid-cols-1 gap-4 xl:grid-cols-3">
                <div class="flex flex-col rounded-xl border border-slate-200 bg-white p-4 shadow-sm xl:col-span-2">
                    <div class="flex items-center justify-between">
                        <p class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <i class="ti ti-chart-bar text-lg text-sky-500"></i>
                            Trafik Bulanan 2026
                        </p>
                        <span class="text-xs text-slate-500">Jan – Des 2026</span>
                    </div>

                    {{-- data dummy bulanan: max=Des(2100) → 150px, skala = val/2100*150 --}}
                    <div class="mt-4 flex flex-1 items-end gap-1.5 rounded-lg bg-slate-100 px-3 pb-3 pt-4 min-h-0">
                        {{-- Jan: 1200 → 86px --}}
                        <div class="flex flex-1 flex-col items-center gap-1">
                            <span class="text-[9px] font-semibold text-slate-400">1.2k</span>
                            <div class="w-full rounded-t bg-sky-400" style="height:86px"></div>
                            <span class="text-[10px] text-slate-600">Jan</span>
                        </div>
                        {{-- Feb: 980 → 70px --}}
                        <div class="flex flex-1 flex-col items-center gap-1">
                            <span class="text-[9px] font-semibold text-slate-400">980</span>
                            <div class="w-full rounded-t bg-sky-400" style="height:70px"></div>
                            <span class="text-[10px] text-slate-600">Feb</span>
                        </div>
                        {{-- Mar: 1450 → 104px --}}
                        <div class="flex flex-1 flex-col items-center gap-1">
                            <span class="text-[9px] font-semibold text-slate-400">1.5k</span>
                            <div class="w-full rounded-t bg-sky-400" style="height:104px"></div>
                            <span class="text-[10px] text-slate-600">Mar</span>
                        </div>
                        {{-- Apr: 1320 → 94px --}}
                        <div class="flex flex-1 flex-col items-center gap-1">
                            <span class="text-[9px] font-semibold text-slate-400">1.3k</span>
                            <div class="w-full rounded-t bg-sky-400" style="height:94px"></div>
                            <span class="text-[10px] text-slate-600">Apr</span>
                        </div>
                        {{-- Mei: 1680 → 120px --}}
                        <div class="flex flex-1 flex-col items-center gap-1">
                            <span class="text-[9px] font-semibold text-slate-400">1.7k</span>
                            <div class="w-full rounded-t bg-sky-500" style="height:120px"></div>
                            <span class="text-[10px] text-slate-600">Mei</span>
                        </div>
                        {{-- Jun: 1540 → 110px --}}
                        <div class="flex flex-1 flex-col items-center gap-1">
                            <span class="text-[9px] font-semibold text-slate-400">1.5k</span>
                            <div class="w-full rounded-t bg-sky-500" style="height:110px"></div>
                            <span class="text-[10px] text-slate-600">Jun</span>
                        </div>
                        {{-- Jul: 1290 → 92px --}}
                        <div class="flex flex-1 flex-col items-center gap-1">
                            <span class="text-[9px] font-semibold text-slate-400">1.3k</span>
                            <div class="w-full rounded-t bg-sky-400" style="height:92px"></div>
                            <span class="text-[10px] text-slate-600">Jul</span>
                        </div>
                        {{-- Agu: 1760 → 126px --}}
                        <div class="flex flex-1 flex-col items-center gap-1">
                            <span class="text-[9px] font-semibold text-slate-400">1.8k</span>
                            <div class="w-full rounded-t bg-sky-500" style="height:126px"></div>
                            <span class="text-[10px] text-slate-600">Agu</span>
                        </div>
                        {{-- Sep: 1600 → 114px --}}
                        <div class="flex flex-1 flex-col items-center gap-1">
                            <span class="text-[9px] font-semibold text-slate-400">1.6k</span>
                            <div class="w-full rounded-t bg-sky-500" style="height:114px"></div>
                            <span class="text-[10px] text-slate-600">Sep</span>
                        </div>
                        {{-- Okt: 1850 → 132px --}}
                        <div class="flex flex-1 flex-col items-center gap-1">
                            <span class="text-[9px] font-semibold text-slate-400">1.9k</span>
                            <div class="w-full rounded-t bg-sky-500" style="height:132px"></div>
                            <span class="text-[10px] text-slate-600">Okt</span>
                        </div>
                        {{-- Nov: 1420 → 102px --}}
                        <div class="flex flex-1 flex-col items-center gap-1">
                            <span class="text-[9px] font-semibold text-slate-400">1.4k</span>
                            <div class="w-full rounded-t bg-sky-400" style="height:102px"></div>
                            <span class="text-[10px] text-slate-600">Nov</span>
                        </div>
                        {{-- Des: 2100 → 150px (tertinggi) --}}
                        <div class="flex flex-1 flex-col items-center gap-1">
                            <span class="text-[9px] font-semibold text-sky-600">2.1k</span>
                            <div class="w-full rounded-t bg-sky-600" style="height:150px"></div>
                            <span class="text-[10px] font-semibold text-sky-600">Des</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <i class="ti ti-chart-pie text-lg text-violet-500"></i>
                            Pie Chart Dummy
                        </p>
                        <span class="text-xs text-slate-500">Segment User</span>
                    </div>

                    <div class="mt-6 flex items-center justify-center">
                        <div class="relative h-44 w-44 rounded-full" style="background: conic-gradient(#0ea5e9 0 42%, #14b8a6 42% 70%, #f59e0b 70% 88%, #f43f5e 88% 100%);">
                            <div class="absolute inset-8 rounded-full bg-white"></div>
                        </div>
                    </div>

                    <ul class="mt-6 space-y-2 text-xs text-slate-600">
                        <li class="flex items-center justify-between"><span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-sky-500"></span>Free</span><span>42%</span></li>
                        <li class="flex items-center justify-between"><span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-teal-500"></span>Basic</span><span>28%</span></li>
                        <li class="flex items-center justify-between"><span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>Pro</span><span>18%</span></li>
                        <li class="flex items-center justify-between"><span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>Enterprise</span><span>12%</span></li>
                    </ul>
                </div>
            </section>

            <section>
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                        <p class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <i class="ti ti-table text-lg text-emerald-500"></i>
                            Tabel Aktivitas (Dummy)
                        </p>
                        <span class="text-xs text-slate-500">Updated 5 menit lalu</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                        <thead class="bg-slate-100 text-slate-600">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold">Tanggal</th>
                                <th class="px-3 py-2 text-left font-semibold">User</th>
                                <th class="px-3 py-2 text-left font-semibold">Aktivitas</th>
                                <th class="px-3 py-2 text-left font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-slate-700">
                            <tr>
                                <td class="px-3 py-2">04 Mei 2026</td>
                                <td class="px-3 py-2">Andi Pratama</td>
                                <td class="px-3 py-2">Create Invoice INV-2031</td>
                                <td class="px-3 py-2"><span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">Success</span></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2">04 Mei 2026</td>
                                <td class="px-3 py-2">Siti Rahma</td>
                                <td class="px-3 py-2">Update Profil Perusahaan</td>
                                <td class="px-3 py-2"><span class="rounded-full bg-sky-100 px-2 py-1 text-xs font-semibold text-sky-700">In Progress</span></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2">03 Mei 2026</td>
                                <td class="px-3 py-2">Budi Santoso</td>
                                <td class="px-3 py-2">Generate Laporan Bulanan</td>
                                <td class="px-3 py-2"><span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700">Pending</span></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2">03 Mei 2026</td>
                                <td class="px-3 py-2">Dewi Lestari</td>
                                <td class="px-3 py-2">Approval Refund RF-118</td>
                                <td class="px-3 py-2"><span class="rounded-full bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-700">Rejected</span></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </section>
    </article>
</x-layouts.admin>

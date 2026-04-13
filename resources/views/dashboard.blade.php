<x-layouts.admin>
    <x-slot name="title">Walkthrough - AWALAN</x-slot>
    <x-slot name="pageTitle">Walkthrough</x-slot>

    <article class="space-y-6">
        <header>
            <p class="text-xs font-semibold text-amber-600">Indonesia Workspace</p>
            <h2 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-900">Walkthrough</h2>
            <p class="mt-3 text-base text-slate-600">Ringkasan kerja untuk tim {{ auth()->user()->name }} dengan tampilan dokumentasi yang fokus pada konten.</p>
        </header>

        <section class="space-y-2 text-slate-700">
            <p>Halaman ini mengikuti pola kerja ala knowledge base: struktur jelas, checklist progress, snippet kode, dan tabel ringkas.</p>
            <ul class="space-y-2 text-sm">
                <li class="flex items-center gap-2"><span class="h-4 w-4 rounded border border-emerald-400 bg-emerald-100 text-emerald-700 text-[10px] font-bold leading-4 text-center">✓</span>Review backlog sprint mingguan</li>
                <li class="flex items-center gap-2"><span class="h-4 w-4 rounded border border-emerald-400 bg-emerald-100 text-emerald-700 text-[10px] font-bold leading-4 text-center">✓</span>Sinkronisasi dokumentasi modul inti</li>
                <li class="flex items-center gap-2"><span class="h-4 w-4 rounded border border-slate-300 bg-white text-slate-400 text-[10px] font-bold leading-4 text-center"> </span>Rencanakan peluncuran fase berikutnya</li>
            </ul>
        </section>

        <section class="rounded-xl border border-slate-200 bg-slate-50 p-4">
            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Snippet</p>
            <pre class="rounded-lg bg-slate-900 px-4 py-3 text-sm text-emerald-300 overflow-x-auto"><code class="font-mono">&lt;?php
echo &quot;Panel baru siap dipakai!&quot;;</code></pre>
        </section>

        <section>
            <div class="rounded-xl border border-slate-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-slate-100 text-slate-600">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold">Nama</th>
                            <th class="px-3 py-2 text-left font-semibold">Role</th>
                            <th class="px-3 py-2 text-left font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-700">
                        <tr>
                            <td class="px-3 py-2">{{ auth()->user()->name }}</td>
                            <td class="px-3 py-2">Admin</td>
                            <td class="px-3 py-2"><span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">Online</span></td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Project Core</td>
                            <td class="px-3 py-2">Documentation</td>
                            <td class="px-3 py-2"><span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700">In Review</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </article>
</x-layouts.admin>

{{-- resources/views/admin/stok/stok_realtime.blade.php --}}
@extends('admin.layout.app')

@section('title', 'DPM Workshop - Admin')

@section('content')

{{-- TOPBAR --}}
<header class="sticky top-0 z-20 border-b border-slate-200 bg-white/80 backdrop-blur">
    <div class="h-16 px-4 sm:px-6 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <button id="btnSidebar" type="button"
                    class="md:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                    aria-label="Buka menu">
                <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="min-w-0">
                <h1 class="text-sm font-semibold tracking-tight text-slate-900">Stok Real-time</h1>
                <p class="text-xs text-slate-500">Tampilan stok terkini dari data barang (view-only untuk staf).</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="/tampilan_notifikasi"
               class="tip h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
               data-tip="Notifikasi"
               aria-label="Notifikasi">
                <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
                </svg>
            </a>

            <button type="button"
                    class="h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                {{ now()->format('d M Y') }}
            </button>

            <a href="{{ route('tampilan_dashboard') ?? '/tampilan_dashboard' }}"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition px-3 py-2 text-sm">
                <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>
</header>

<section class="relative p-4 sm:p-6">
    {{-- BACKGROUND --}}
    <div class="pointer-events-none absolute inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
        <div class="absolute inset-0 opacity-[0.12]"
             style="background-image:
                linear-gradient(to right, rgba(2,6,23,0.06) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(2,6,23,0.06) 1px, transparent 1px);
                background-size: 56px 56px;">
        </div>
        <div class="absolute -top-48 left-1/2 -translate-x-1/2 h-[720px] w-[720px] rounded-full blur-3xl opacity-10
                    bg-gradient-to-tr from-blue-950/25 via-blue-700/10 to-transparent"></div>
    </div>

    <div class="max-w-[1120px] mx-auto w-full">

        @php
            $totalItem = collect($barangs ?? [])->count();
            $sumStok   = collect($barangs ?? [])->sum(function ($b) {
                return (int) ($b->stok ?? $b->stok_akhir ?? 0);
            });
            $STOK_MIN  = 25;
            $stokLow   = collect($barangs ?? [])->filter(fn($b) => (int)($b->stok ?? $b->stok_akhir ?? 0) > 0 && (int)($b->stok ?? $b->stok_akhir ?? 0) < $STOK_MIN)->count();
            $stokOut   = collect($barangs ?? [])->filter(fn($b) => (int)($b->stok ?? $b->stok_akhir ?? 0) <= 0)->count();
        @endphp

        {{-- SUMMARY --}}
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-4">
            <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_16px_44px_rgba(2,6,23,0.08)] p-5">
                <p class="text-xs text-slate-500">Total Item</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $totalItem }}</p>
                <p class="text-xs text-slate-400 mt-1">Jumlah jenis barang</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_16px_44px_rgba(2,6,23,0.08)] p-5">
                <p class="text-xs text-slate-500">Total Stok</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $sumStok }}</p>
                <p class="text-xs text-slate-400 mt-1">Akumulasi stok semua barang</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_16px_44px_rgba(2,6,23,0.08)] p-5">
                <p class="text-xs text-slate-500">Stok Menipis</p>
                <p class="text-2xl font-bold text-amber-700 mt-1">{{ $stokLow }}</p>
                <p class="text-xs text-slate-400 mt-1">Stok 1 – 24 unit</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_16px_44px_rgba(2,6,23,0.08)] p-5">
                <p class="text-xs text-slate-500">Barang Habis</p>
                <p class="text-2xl font-bold text-rose-700 mt-1">{{ $stokOut }}</p>
                <p class="text-xs text-slate-400 mt-1">Stok = 0 unit</p>
            </div>
        </div>

        {{-- KETERANGAN STATUS --}}
        <div class="flex flex-wrap items-center gap-3 mb-4 text-xs text-slate-600">
            <span class="font-semibold text-slate-500">Keterangan:</span>
            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-100 text-emerald-700 px-3 py-1 font-semibold">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span> Aman (≥ 25)
            </span>
            <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-100 text-amber-800 px-3 py-1 font-semibold">
                <span class="h-2 w-2 rounded-full bg-amber-500"></span> Menipis (1 – 24)
            </span>
            <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-100 text-rose-700 px-3 py-1 font-semibold">
                <span class="h-2 w-2 rounded-full bg-rose-500"></span> Habis (= 0)
            </span>
        </div>

        {{-- TOOLBAR --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div class="w-full sm:w-[420px]">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                        </svg>
                    </span>
                    <input id="searchStok"
                           type="text"
                           placeholder="Cari kode / nama barang..."
                           class="w-full pl-9 pr-10 py-2.5 rounded-xl border border-slate-200 bg-white/90
                                  text-sm placeholder:text-slate-400
                                  focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                </div>
            </div>

            <div class="flex items-center gap-2">
                {{-- Filter Status --}}
                <select id="filterStatus"
                        class="h-10 px-3 rounded-xl border border-slate-200 bg-white text-sm
                               focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                    <option value="">Semua Status</option>
                    <option value="Aman">Aman</option>
                    <option value="Menipis">Menipis</option>
                    <option value="Habis">Habis</option>
                </select>

                <button id="btnPrint" type="button"
                        class="h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                    Print
                </button>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                    shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm" id="stokTable">
                    <thead class="bg-slate-50/90 sticky top-0 z-10 backdrop-blur">
                        <tr class="text-left text-slate-600">
                            <th class="px-5 py-4 font-semibold w-[70px]">No</th>
                            <th class="px-5 py-4 font-semibold">Kode</th>
                            <th class="px-5 py-4 font-semibold">Nama Barang</th>
                            <th class="px-5 py-4 font-semibold">Satuan</th>
                            <th class="px-5 py-4 font-semibold text-right w-[120px]">Stok</th>
                            <th class="px-5 py-4 font-semibold w-[140px]">Status</th>
                            <th class="px-5 py-4 font-semibold text-right w-[160px]">Harga Jual</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200" id="stokTbody">
                        @forelse (($barangs ?? []) as $i => $b)
                            @php
                                $stok = (int) ($b->stok ?? $b->stok_akhir ?? 0);

                                if ($stok <= 0) {
                                    $label = 'Habis';
                                    $cls   = 'bg-rose-100 text-rose-700 border-rose-200';
                                } elseif ($stok < 25) {
                                    $label = 'Menipis';
                                    $cls   = 'bg-amber-100 text-amber-800 border-amber-200';
                                } else {
                                    $label = 'Aman';
                                    $cls   = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                                }
                            @endphp

                            <tr class="row-lift hover:bg-slate-50/70 transition" data-status="{{ $label }}">
                                <td class="px-5 py-4 text-slate-600">{{ $i + 1 }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-900">{{ $b->kode_barang ?? '-' }}</td>
                                <td class="px-5 py-4 text-slate-700">{{ $b->nama_barang ?? '-' }}</td>
                                <td class="px-5 py-4 text-slate-700">{{ $b->satuan ?? '-' }}</td>
                                <td class="px-5 py-4 text-right font-bold
                                    {{ $stok <= 0 ? 'text-rose-700' : ($stok < 25 ? 'text-amber-700' : 'text-slate-900') }}">
                                    {{ $stok }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border {{ $cls }}">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right text-slate-700">
                                    {{ isset($b->harga_jual) ? 'Rp '.number_format($b->harga_jual, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @empty
                            @for ($r = 1; $r <= 4; $r++)
                                <tr class="row-lift hover:bg-slate-50/70 transition">
                                    <td class="px-5 py-5 text-slate-400">{{ $r }}</td>
                                    <td class="px-5 py-5"><div class="h-4 w-24 rounded bg-slate-100"></div></td>
                                    <td class="px-5 py-5"><div class="h-4 w-52 rounded bg-slate-100"></div></td>
                                    <td class="px-5 py-5"><div class="h-4 w-16 rounded bg-slate-100"></div></td>
                                    <td class="px-5 py-5 text-right"><div class="h-4 w-14 ml-auto rounded bg-slate-100"></div></td>
                                    <td class="px-5 py-5"><div class="h-6 w-24 rounded-full bg-slate-100"></div></td>
                                    <td class="px-5 py-5 text-right"><div class="h-4 w-24 ml-auto rounded bg-slate-100"></div></td>
                                </tr>
                            @endfor
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Empty state setelah filter --}}
            <div id="emptyFilter" class="hidden px-6 py-10 text-center text-sm text-slate-400">
                Tidak ada barang yang cocok dengan pencarian / filter.
            </div>

            <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
                © DPM Workshop 2025
            </div>
        </div>
    </div>
</section>

@endsection

@push('head')
<style>
    .row-lift {
        transform: translateY(0);
        transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease;
    }
    .row-lift:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 26px rgba(2,6,23,0.06);
    }

    @media print {
        #sidebar, #overlay, #btnSidebar, #btnCloseSidebar, #btnPrint, #filterStatus { display: none !important; }
        #main { margin-left: 0 !important; }
        body { background: #fff !important; }
    }
</style>
@endpush

@push('scripts')
<script>
    const searchInput  = document.getElementById('searchStok');
    const filterSelect = document.getElementById('filterStatus');
    const tbody        = document.getElementById('stokTbody');
    const emptyFilter  = document.getElementById('emptyFilter');

    function applyFilter() {
        const q      = (searchInput?.value ?? '').trim().toLowerCase();
        const status = (filterSelect?.value ?? '').toLowerCase();

        let visible = 0;

        Array.from(tbody.querySelectorAll('tr')).forEach(tr => {
            const text       = (tr.innerText || '').toLowerCase();
            const rowStatus  = (tr.dataset.status || '').toLowerCase();

            const matchQ      = !q      || text.includes(q);
            const matchStatus = !status || rowStatus === status;

            const show = matchQ && matchStatus;
            tr.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (emptyFilter) {
            emptyFilter.classList.toggle('hidden', visible > 0);
        }
    }

    searchInput?.addEventListener('input',  applyFilter);
    filterSelect?.addEventListener('change', applyFilter);

    // Print
    document.getElementById('btnPrint')?.addEventListener('click', () => {
        window.open("{{ route('stok_realtime.print') }}", '_blank');
    });
</script>
@endpush
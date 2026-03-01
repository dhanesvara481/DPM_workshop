@extends('staff.layout.app')

@section('page_title', 'Riwayat Transaksi')
@section('page_subtitle', 'Riwayat transaksi yang Anda buat')

@section('content')

@php
    $rowsCol  = collect($rows->items());
    $sortNext = ($sort ?? 'desc') === 'desc' ? 'asc' : 'desc';
@endphp

<div class="max-w-[980px] mx-auto w-full space-y-4">

    {{-- FILTER --}}
    <div class="rounded-3xl border border-slate-200 bg-white/90 backdrop-blur shadow-sm p-6">

        <form id="filterForm" method="GET" action="{{ route('riwayat_transaksi_staff') }}">

            {{-- Hidden fields preserved on filter submit --}}
            <input type="hidden" name="sort"     value="{{ $sort ?? 'desc' }}">
            <input type="hidden" name="per_page" value="{{ $perPage ?? 15 }}">

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">

                {{-- CARI --}}
                <div class="sm:col-span-5">
                    <label class="block text-xs tracking-widest font-semibold text-slate-500 mb-2">
                        CARI
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                            </svg>
                        </span>
                        <input name="q"
                               value="{{ $q ?? '' }}"
                               type="text"
                               placeholder="Cari pelanggan / kode invoice..."
                               class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 bg-white text-sm
                                      focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                    </div>
                </div>

                {{-- DARI --}}
                <div class="sm:col-span-3">
                    <label class="block text-xs tracking-widest font-semibold text-slate-500 mb-2">
                        DARI
                    </label>
                    <input type="date"
                           name="dari"
                           value="{{ $dari ?? '' }}"
                           class="w-full py-3 px-4 rounded-2xl border border-slate-200 bg-white text-sm
                                  focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                </div>

                {{-- SAMPAI --}}
                <div class="sm:col-span-3">
                    <label class="block text-xs tracking-widest font-semibold text-slate-500 mb-2">
                        SAMPAI
                    </label>
                    <input type="date"
                           name="sampai"
                           value="{{ $sampai ?? '' }}"
                           class="w-full py-3 px-4 rounded-2xl border border-slate-200 bg-white text-sm
                                  focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                </div>

                {{-- BUTTON --}}
                <div class="sm:col-span-1 flex sm:flex-col gap-3 sm:justify-end">
                    <button type="submit"
                            class="rounded-2xl bg-blue-950 text-white py-3 text-sm font-semibold
                                   hover:bg-blue-900 transition shadow">
                        Filter
                    </button>
                    <a href="{{ route('riwayat_transaksi_staff') }}"
                       class="rounded-2xl border border-slate-200 bg-white py-3 text-sm font-semibold text-center
                              hover:bg-slate-50 transition">
                        Reset
                    </a>
                </div>

            </div>

            {{-- Second row: sort + per_page + count --}}
            <div class="mt-5 flex flex-wrap items-center gap-3">

                {{-- Sort toggle --}}
                <a href="{{ route('riwayat_transaksi_staff', array_merge(request()->except(['sort','page']), ['sort' => $sortNext, 'per_page' => $perPage])) }}"
                   class="inline-flex items-center gap-1.5 rounded-2xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold
                          text-slate-700 hover:bg-slate-50 transition">
                    @if(($sort ?? 'desc') === 'desc')
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9M3 12h5m10 0v8m0 0l-3-3m3 3 3-3"/>
                        </svg>
                        Terbaru
                    @else
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9M3 12h5m10 4V8m0 0l-3 3m3-3 3 3"/>
                        </svg>
                        Terlama
                    @endif
                </a>

                {{-- Per page --}}
                <div class="flex items-center gap-1.5">
                    <span class="text-xs text-slate-500">Tampilkan</span>
                    <select id="perPageSelect"
                            class="rounded-xl border border-slate-200 bg-white text-xs px-2 py-1.5
                                   focus:outline-none focus:ring-4 focus:ring-blue-900/10 transition">
                        @foreach([10, 15, 25, 50] as $pp)
                            <option value="{{ $pp }}" @selected(($perPage ?? 15) == $pp)>{{ $pp }}</option>
                        @endforeach
                    </select>
                    <span class="text-xs text-slate-500">/ halaman</span>
                </div>

                <div class="ml-auto text-sm text-slate-500">
                    Menampilkan
                    <span class="font-semibold text-slate-700">{{ $rows->firstItem() }}–{{ $rows->lastItem() }}</span>
                    dari <span class="font-semibold text-slate-700">{{ $rows->total() }}</span> invoice.
                </div>

            </div>

        </form>

        {{-- Hidden form for per_page change --}}
        <form id="perPageForm" method="GET" action="{{ route('riwayat_transaksi_staff') }}" class="hidden">
            <input type="hidden" name="q"        value="{{ $q ?? '' }}">
            <input type="hidden" name="dari"     value="{{ $dari ?? '' }}">
            <input type="hidden" name="sampai"   value="{{ $sampai ?? '' }}">
            <input type="hidden" name="sort"     value="{{ $sort ?? 'desc' }}">
            <input type="hidden" name="per_page" id="hiddenPerPage">
        </form>

    </div>

    {{-- LIST --}}
    <div class="rounded-3xl border border-slate-200 bg-white/90 backdrop-blur shadow-sm overflow-hidden">

        @forelse($rowsCol as $trx)

            <a href="{{ route('detail_riwayat_transaksi_staff', $trx->id) }}"
               class="block px-6 py-4 hover:bg-slate-50 transition border-b last:border-b-0">

                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-slate-900">
                            {{ $trx->kode_transaksi ?? 'INV-'.$trx->id }}
                        </div>
                        <div class="text-xs text-slate-500">
                            {{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y H:i') }}
                        </div>
                        <div class="text-xs text-slate-400 mt-0.5">
                            {{ $trx->nama_pengguna ?? '-' }}
                        </div>
                    </div>
                    <div class="text-right flex flex-col items-end gap-1.5">
                    
                        {{-- Status Badge --}}
                        @php
                            $status = strtolower($trx->status ?? 'Pending');
                            $badge = match($status) {
                                'lunas', 'selesai', 'paid', 'completed'
                                    => ['bg-emerald-50 text-emerald-700 border-emerald-200', 'Paid'],
                                'pending', 'Pending'
                                    => ['bg-amber-50 text-amber-700 border-amber-200', 'Pending'],
                                default
                                    => ['bg-slate-100 text-slate-600 border-slate-200', ucfirst($status)],
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full border text-[11px] font-semibold tracking-wide {{ $badge[0] }}">
                            {{ $badge[1] }}
                        </span>
                    
                        <div class="text-emerald-700 font-semibold">
                            Rp {{ number_format($trx->total ?? 0, 0, ',', '.') }}
                        </div>
                    
                    </div>
                </div>
            </a>

        @empty

            <div class="py-24 text-center">
                <div class="mx-auto h-16 w-16 rounded-2xl border border-slate-200 bg-white grid place-items-center text-slate-400">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 7h18M6 7V5a2 2 0 012-2h8a2 2 0 012 2v2M6 7v14a2 2 0 002 2h8a2 2 0 002-2V7"/>
                    </svg>
                </div>
                <div class="mt-4 text-lg font-semibold text-slate-900">
                    Belum ada riwayat invoice
                </div>
                <div class="mt-1 text-sm text-slate-500">
                    Coba ubah filter tanggal atau kata kunci pencarian.
                </div>
            </div>

        @endforelse

        {{-- PAGINATION --}}
        @if($rows->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 flex flex-wrap items-center justify-between gap-3">

                <div class="text-xs text-slate-500">
                    Halaman {{ $rows->currentPage() }} dari {{ $rows->lastPage() }}
                </div>

                <div class="flex items-center gap-1">

                    {{-- Prev --}}
                    @if($rows->onFirstPage())
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl border border-slate-200 text-slate-300 cursor-not-allowed">
                            ‹
                        </span>
                    @else
                        <a href="{{ $rows->previousPageUrl() }}"
                           class="inline-flex h-9 w-9 items-center justify-center rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 transition text-slate-600">
                            ‹
                        </a>
                    @endif

                    {{-- Page numbers --}}
                    @foreach($rows->getUrlRange(max(1, $rows->currentPage()-2), min($rows->lastPage(), $rows->currentPage()+2)) as $page => $url)
                        @if($page == $rows->currentPage())
                            <span class="inline-flex h-9 min-w-[2.25rem] px-2 items-center justify-center rounded-2xl bg-blue-950 text-white text-xs font-semibold">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="inline-flex h-9 min-w-[2.25rem] px-2 items-center justify-center rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 transition text-slate-600 text-xs">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if($rows->hasMorePages())
                        <a href="{{ $rows->nextPageUrl() }}"
                           class="inline-flex h-9 w-9 items-center justify-center rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 transition text-slate-600">
                            ›
                        </a>
                    @else
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl border border-slate-200 text-slate-300 cursor-not-allowed">
                            ›
                        </span>
                    @endif

                </div>
            </div>
        @endif

        <div class="px-6 py-4 border-t border-slate-200 text-sm text-slate-500">
            © DPM Workshop 2025
        </div>

    </div>

</div>

@push('scripts')
<script>
    document.getElementById('perPageSelect')?.addEventListener('change', function () {
        document.getElementById('hiddenPerPage').value = this.value;
        document.getElementById('perPageForm').submit();
    });
</script>
@endpush

@endsection
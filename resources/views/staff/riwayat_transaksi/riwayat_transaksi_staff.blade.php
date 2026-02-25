@extends('staff.layout.app')

@section('page_title', 'Riwayat Transaksi')
@section('page_subtitle', 'Riwayat transaksi yang Anda buat')

@section('content')

@php
    $rowsCol = collect($rows ?? []);
@endphp

<div class="max-w-[980px] mx-auto w-full space-y-4">

    {{-- FILTER --}}
    <div class="rounded-3xl border border-slate-200 bg-white/90 backdrop-blur shadow-sm p-6">

        <form method="GET" action="{{ route('riwayat_transaksi_staff') }}">

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

            <div class="mt-6 text-sm text-slate-500">
                Menampilkan {{ $rowsCol->count() }} invoice.
            </div>

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
                    <div class="text-right text-emerald-700 font-semibold">
                        Rp {{ number_format($trx->total ?? 0, 0, ',', '.') }}
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

        <div class="px-6 py-4 border-t border-slate-200 text-sm text-slate-500">
            © DPM Workshop 2025
        </div>

    </div>

</div>

@endsection
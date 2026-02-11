<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Penjualan</title>
    @vite('resources/js/app.js')
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
<div class="min-h-screen flex">

    {{-- ================= SIDEBAR ================= --}}
    <aside id="sidebar"
       class="fixed inset-y-0 left-0 z-40 h-screen
              w-[280px] md:w-[280px]
              -translate-x-full md:translate-x-0
              bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-white
              border-r border-white/5
              transition-[transform,width] duration-300 ease-out
              overflow-y-auto">

        <div class="h-16 px-5 flex items-center justify-between border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-white/10 border border-white/15 grid place-items-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" class="h-7 w-7 object-contain" alt="Logo">
                </div>
                <div class="leading-tight">
                    <p class="font-semibold tracking-tight">DPM Workshop</p>
                </div>
            </div>

            <button id="btnCloseSidebar"
                    type="button"
                    class="md:hidden h-10 w-10 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition grid place-items-center"
                    aria-label="Tutup menu">
                <svg class="h-5 w-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="px-5 py-5">
            {{-- Profile --}}
            <div class="flex items-center gap-3 rounded-2xl bg-white/5 border border-white/10 px-4 py-3">
                <div class="h-10 w-10 rounded-full bg-white/10 border border-white/15"></div>
                <div class="min-w-0">
                    <p class="text-sm font-medium truncate">{{ $userName ?? 'User' }}</p>
                    <p class="text-[11px] text-white/60">{{ $role ?? 'Admin' }}</p>
                </div>
            </div>

            {{-- Menu --}}
            <nav class="mt-5 space-y-1">

                <a href="#"
                   data-nav
                   class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                    <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                        <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5V21a1.5 1.5 0 01-1.5 1.5H4.5A1.5 1.5 0 013 21V10.5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 22V12h6v10"/>
                        </svg>
                    </span>
                    Dashboard
                </a>

                <div class="mt-3">
                    <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">BARANG</p>

                    <a href="/tampilan_barang"
                       data-nav
                       class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                            text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8 4-8-4"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10l8 4 8-4V7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v10"/>
                            </svg>
                        </span>
                        Kelola Barang
                    </a>

                    <a href="/barang_keluar"
                       data-nav
                       class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 7h7v7"/>
                            </svg>
                        </span>
                        Barang Keluar
                    </a>

                    <a href="/barang_masuk"
                       data-nav
                       class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 7L7 17"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 10v7h7"/>
                            </svg>
                        </span>
                        Barang Masuk
                    </a>
                </div>

                <div class="mt-3">
                    <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">RIWAYAT & LAPORAN</p>

                    <a href="/riwayat_perubahan_stok"
                       data-nav
                       class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5l3 2"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        Riwayat Perubahan Stok
                    </a>

                    <a href="/riwayat_transaksi"
                       data-nav
                       class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10a2 2 0 012 2v16l-2-1-2 1-2-1-2 1-2-1-2 1V5a2 2 0 012-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h6M9 16h4"/>
                            </svg>
                        </span>
                        Riwayat Transaksi
                    </a>

                    {{-- ACTIVE: Laporan Penjualan --}}
                    <a href="/laporan_penjualan"
                       data-nav data-active="true"
                       class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                            bg-white/12 text-white border border-white/10
                            hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/80 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 17v-6"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 17V9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 17v-3"/>
                            </svg>
                        </span>
                        Laporan Penjualan
                    </a>
                </div>

                <div class="mt-3">
                    <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">MANAJEMEN</p>

                    <a href="/tampilan_jadwal_kerja"
                       data-nav
                       class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        Kelola Jadwal Kerja
                    </a>

                    <a href="#"
                       data-nav
                       class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20c0-2.2-2.7-4-5-4s-5 1.8-5 4"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 20c0-1.7-1.4-3.1-3.3-3.7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7a2.5 2.5 0 01-1.5 2.3"/>
                            </svg>
                        </span>
                        Manajemen Staf
                    </a>
                </div>

                <div class="mt-4 pt-4 border-t border-white/10">
                    <a href="#"
                       class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 17l5-5-5-5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21V3a2 2 0 00-2-2h-6"/>
                            </svg>
                        </span>
                        Logout
                    </a>
                </div>
            </nav>
        </div>
    </aside>

    <div id="overlay" class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-sm hidden md:hidden"></div>

    {{-- ================= MAIN ================= --}}
    <main id="main" class="flex-1 min-w-0 relative overflow-hidden md:ml-[280px] transition-[margin] duration-300 ease-out">

        {{-- BACKGROUND --}}
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
            <div class="absolute inset-0 opacity-[0.12]"
                 style="background-image:
                    linear-gradient(to right, rgba(2,6,23,0.06) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(2,6,23,0.06) 1px, transparent 1px);
                    background-size: 56px 56px;">
            </div>
            <div class="absolute inset-0 opacity-[0.20] mix-blend-screen animate-grid-scan"
                 style="background-image:
                    repeating-linear-gradient(90deg, transparent 0px, transparent 55px, rgba(255,255,255,0.95) 56px, transparent 57px, transparent 112px),
                    repeating-linear-gradient(180deg, transparent 0px, transparent 55px, rgba(255,255,255,0.70) 56px, transparent 57px, transparent 112px);
                    background-size: 112px 112px, 112px 112px;">
            </div>
            <div class="absolute -top-48 left-1/2 -translate-x-1/2 h-[720px] w-[720px] rounded-full blur-3xl opacity-10
                        bg-gradient-to-tr from-blue-950/25 via-blue-700/10 to-transparent"></div>
            <div class="absolute -bottom-72 right-1/4 h-[720px] w-[720px] rounded-full blur-3xl opacity-08
                        bg-gradient-to-tr from-blue-950/18 via-indigo-700/10 to-transparent"></div>
        </div>

        {{-- TOPBAR --}}
        <header class="relative h-16 bg-white/75 backdrop-blur border-b border-slate-200 sticky top-0 z-20">
            <div class="h-full px-4 sm:px-6 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <button id="btnSidebar" type="button"
                            class="md:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                            aria-label="Buka menu">
                        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="min-w-0">
                        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Laporan Penjualan</h1>
                        <p class="text-xs text-slate-500">Pilih range (minggu/bulan/tahun/custom) lalu export ke PDF.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button"
                            class="tip h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                            data-tip="Notifikasi">
                        <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        {{-- CONTENT --}}
        <section class="relative p-4 sm:p-6">
            <div class="max-w-[980px] mx-auto w-full">

                @php
                    $mode   = request('mode', 'custom'); // custom|week|month|year
                    $dari   = request('dari');
                    $sampai = request('sampai');
                    $week   = request('week');   // YYYY-W##
                    $month  = request('month');  // YYYY-MM
                    $year   = request('year');   // YYYY

                    $hasRange = false;
                    if ($mode === 'custom') $hasRange = !empty($dari) && !empty($sampai);
                    if ($mode === 'week')   $hasRange = !empty($week);
                    if ($mode === 'month')  $hasRange = !empty($month);
                    if ($mode === 'year')   $hasRange = !empty($year);
                @endphp

                {{-- TOOLBAR --}}
                <form method="GET" action="{{ route('laporan_penjualan') }}"
                      class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-3 mb-5">

                    {{-- pilih mode --}}
                    <div class="w-full lg:max-w-[280px]">
                        <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">RANGE</label>
                        <select name="mode" id="mode"
                                class="w-full py-2.5 px-3 rounded-lg border border-slate-200 bg-white/90 text-sm
                                       focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                            <option value="custom" {{ $mode==='custom'?'selected':'' }}>Custom (Dari - Sampai)</option>
                            <option value="week"   {{ $mode==='week'?'selected':'' }}>Mingguan</option>
                            <option value="month"  {{ $mode==='month'?'selected':'' }}>Bulanan</option>
                            <option value="year"   {{ $mode==='year'?'selected':'' }}>Tahunan</option>
                        </select>
                    </div>

                    {{-- input range --}}
                    <div class="w-full lg:max-w-[520px]">
                        <div id="box-custom" class="{{ $mode==='custom' ? '' : 'hidden' }}">
                            <div class="grid grid-cols-1 sm:grid-cols-[1fr_auto_1fr] gap-3">
                                <div>
                                    <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">DARI</label>
                                    <input type="date" name="dari" value="{{ $dari ?? '' }}"
                                           class="w-full py-2.5 px-3 rounded-lg border border-slate-200 bg-white/90 text-sm
                                                  focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                                </div>
                                <div class="hidden sm:flex items-end justify-center pb-2 text-slate-400 font-semibold">—</div>
                                <div>
                                    <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">SAMPAI</label>
                                    <input type="date" name="sampai" value="{{ $sampai ?? '' }}"
                                           class="w-full py-2.5 px-3 rounded-lg border border-slate-200 bg-white/90 text-sm
                                                  focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                                </div>
                            </div>
                        </div>

                        <div id="box-week" class="{{ $mode==='week' ? '' : 'hidden' }}">
                            <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">MINGGU</label>
                            <input type="week" name="week" value="{{ $week ?? '' }}"
                                   class="w-full py-2.5 px-3 rounded-lg border border-slate-200 bg-white/90 text-sm
                                          focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                        </div>

                        <div id="box-month" class="{{ $mode==='month' ? '' : 'hidden' }}">
                            <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">BULAN</label>
                            <input type="month" name="month" value="{{ $month ?? '' }}"
                                   class="w-full py-2.5 px-3 rounded-lg border border-slate-200 bg-white/90 text-sm
                                          focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                        </div>

                        <div id="box-year" class="{{ $mode==='year' ? '' : 'hidden' }}">
                            <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">TAHUN</label>
                            <input type="number" min="2000" max="2100" name="year" value="{{ $year ?? '' }}"
                                   placeholder="2026"
                                   class="w-full py-2.5 px-3 rounded-lg border border-slate-200 bg-white/90 text-sm
                                          focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                        </div>
                    </div>

                    {{-- buttons --}}
                    <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto lg:justify-end">
                        <button type="submit"
                                class="btn-shine inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold
                                       bg-blue-950 text-white hover:bg-blue-900 transition
                                       shadow-[0_12px_24px_rgba(2,6,23,0.16)]">
                            Filter
                        </button>

                        <a href="{{ route('laporan_penjualan') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold
                                  border border-slate-200 bg-white hover:bg-slate-50 transition">
                            Reset
                        </a>

                        {{-- Tampilkan Laporan (PDF) --}}
                        <a href="{{ $hasRange ? route('laporan_penjualan.pdf', request()->query()) : '#' }}"
                           class="inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold
                                  {{ $hasRange ? 'bg-slate-900 text-white hover:bg-slate-800' : 'bg-slate-300 text-slate-500 cursor-not-allowed' }}
                                  transition shadow-[0_12px_24px_rgba(2,6,23,0.18)] sm:ml-2"
                           {{ $hasRange ? '' : 'aria-disabled=true onclick=event.preventDefault()' }}>
                            Tampilkan<br class="hidden sm:block">Laporan
                        </a>
                    </div>
                </form>

                {{-- TABLE CONTAINER --}}
                <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                            shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

                    <div class="p-5 sm:p-6">

                        {{-- info periode --}}
                        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div class="text-sm font-semibold text-slate-900">Daftar Transaksi</div>
                            <div class="text-xs text-slate-500">
                                @if($mode==='custom')
                                    Periode: <span class="font-semibold">{{ $dari ?? '-' }}</span> s/d <span class="font-semibold">{{ $sampai ?? '-' }}</span>
                                @elseif($mode==='week')
                                    Minggu: <span class="font-semibold">{{ $week ?? '-' }}</span>
                                @elseif($mode==='month')
                                    Bulan: <span class="font-semibold">{{ $month ?? '-' }}</span>
                                @elseif($mode==='year')
                                    Tahun: <span class="font-semibold">{{ $year ?? '-' }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="rounded-xl bg-slate-200 border border-slate-300 overflow-hidden">
                            {{-- header tabel --}}
                            <div class="grid grid-cols-[80px_1fr_220px] items-center bg-slate-200 text-slate-900 font-semibold">
                                <div class="px-4 py-3 border-r-4 border-slate-900">No</div>
                                <div class="px-4 py-3 border-r-4 border-slate-900 text-center">Kode Transaksi</div>
                                <div class="px-4 py-3 text-center">Tanggal</div>
                            </div>

                            <div class="h-1 bg-slate-900"></div>

                            {{-- body --}}
                            <div class="min-h-[260px]">
                                @php $rows = $rows ?? []; @endphp

                                @if(!empty($rows))
                                    @foreach($rows as $i => $r)
                                        <div class="grid grid-cols-[80px_1fr_220px] items-center">
                                            <div class="px-4 py-3 border-r-4 border-slate-900">{{ $i+1 }}</div>
                                            <div class="px-4 py-3 border-r-4 border-slate-900 text-center font-semibold">
                                                {{ $r->kode_transaksi ?? $r->kode ?? ('TRX-'.($r->id ?? '-')) }}
                                            </div>
                                            <div class="px-4 py-3 text-center">
                                                {{ isset($r->created_at) ? \Carbon\Carbon::parse($r->created_at)->format('Y-m-d') : ($r->tanggal ?? '-') }}
                                            </div>
                                        </div>
                                        <div class="h-px bg-slate-300"></div>
                                    @endforeach
                                @else
                                    <div class="p-10 text-center text-slate-600">
                                        <div class="text-sm font-semibold">Belum ada data untuk ditampilkan.</div>
                                        <div class="text-xs mt-1">Pilih range lalu klik <span class="font-semibold">Filter</span>.</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
                        © DPM Workshop 2025
                    </div>
                </div>
            </div>
        </section>

        <style>
            @media (prefers-reduced-motion: reduce) {
                .animate-grid-scan, .btn-shine, .nav-item::before { animation: none !important; transition: none !important; }
            }

            @keyframes gridScan {
                0%   { background-position: 0 0, 0 0; opacity: 0.10; }
                40%  { opacity: 0.22; }
                60%  { opacity: 0.18; }
                100% { background-position: 220px 220px, -260px 260px; opacity: 0.10; }
            }
            .animate-grid-scan { animation: gridScan 8.5s ease-in-out infinite; }

            /* sidebar active indicator */
            .nav-item{ position: relative; overflow: hidden; }
            .nav-item::before{
                content:"";
                position:absolute;
                left:0; top:10px; bottom:10px;
                width:3px;
                background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,.75), rgba(255,255,255,0));
                opacity:0;
                transform: translateX(-6px);
                transition: .25s ease;
                border-radius: 999px;
            }
            .nav-item.is-active::before{ opacity:.95; transform: translateX(0); }

            .btn-shine{ position: relative; overflow: hidden; }
            .btn-shine::after{
                content:"";
                position:absolute;
                inset:0;
                transform: translateX(-120%);
                background: linear-gradient(90deg, transparent, rgba(255,255,255,.28), transparent);
                transition: transform .65s ease;
            }
            .btn-shine:hover::after{ transform: translateX(120%); }

            .tip{ position: relative; }
            .tip[data-tip]::after{
                content: attr(data-tip);
                position:absolute;
                right:0;
                top: calc(100% + 10px);
                background: rgba(15,23,42,.92);
                color: rgba(255,255,255,.92);
                font-size: 11px;
                padding: 6px 10px;
                border-radius: 10px;
                white-space: nowrap;
                opacity:0;
                transform: translateY(-4px);
                pointer-events:none;
                transition: .15s ease;
            }
            .tip:hover::after{ opacity:1; transform: translateY(0); }

            #sidebar { -webkit-overflow-scrolling: touch; }
        </style>

        <script>
            // sidebar active indicator (pakai data-active)
            document.querySelectorAll('[data-nav]').forEach(a => {
                if (a.dataset.active === "true") a.classList.add('is-active');
            });

            // switch input mode
            const modeEl = document.getElementById('mode');
            const boxes = {
                custom: document.getElementById('box-custom'),
                week: document.getElementById('box-week'),
                month: document.getElementById('box-month'),
                year: document.getElementById('box-year'),
            };
            function showBox(mode){
                Object.values(boxes).forEach(b => b && b.classList.add('hidden'));
                boxes[mode] && boxes[mode].classList.remove('hidden');
            }
            modeEl?.addEventListener('change', (e) => showBox(e.target.value));
            showBox(modeEl?.value || 'custom');

            // mobile sidebar
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const btnSidebar = document.getElementById('btnSidebar');
            const btnCloseSidebar = document.getElementById('btnCloseSidebar');

            const openSidebar = () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };
            const closeSidebar = () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            btnSidebar?.addEventListener('click', openSidebar);
            btnCloseSidebar?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeSidebar();
            });

            const syncOnResize = () => {
                if (window.innerWidth >= 768) {
                    overlay.classList.add('hidden');
                    sidebar.classList.remove('-translate-x-full');
                    document.body.classList.remove('overflow-hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            };
            window.addEventListener('resize', syncOnResize);
            syncOnResize();
        </script>

    </main>
</div>
</body>
</html>

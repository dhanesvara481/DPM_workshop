<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Jadwal Kerja</title>
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
                       class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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

                    <a href="/laporan_penjualan"
                       data-nav
                       class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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

                    {{-- ACTIVE: Kelola Jadwal Kerja --}}
                    {{-- IMPORTANT: sesuaikan href sesuai route kamu (sekarang route kamu /jadwal_kerja) --}}
                    <a href="/jadwal_kerja"
                       data-nav data-active="true"
                       class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                              bg-white/12 text-white border border-white/10
                              hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/80 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        Kelola Jadwal Kerja
                    </a>

                    <a href="/tampilan_manajemen_staf"
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
        <header class="relative bg-white/75 backdrop-blur border-b border-slate-200 sticky top-0 z-20">
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
                        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Kelola Jadwal Kerja</h1>
                        <p class="text-xs text-slate-500">Kalender bulanan (raksasa) untuk mengatur jadwal & ketersediaan.</p>
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
            <div class="max-w-[1280px] mx-auto w-full">

                @php
                    /*
                      Backend opsional:
                      $events = [
                        '2026-02-07' => [
                          ['id'=>1, 'title'=>'Shift Pagi', 'type'=>'ok', 'time'=>'08:00 - 16:00', 'desc'=>'...'],
                          ['id'=>2, 'title'=>'Meeting', 'type'=>'warn', 'time'=>'10:00', 'desc'=>'...'],
                        ],
                      ];

                      $slots = [
                        '2026-02-07' => ['left'=>30, 'status'=>'open'], // open|closed
                      ];
                    */
                    // $events = $events ?? [];
                    // $slots  = $slots ?? [];

                     // ===== DUMMY DATA (hapus nanti kalau backend sudah siap) =====
                    $events = $events ?? [
                        now()->format('Y-m-d') => [
                            ['id'=> 101, 'title'=>'Shift Pagi - Asep', 'status'=>'aktif', 'time'=>'08:00 - 16:00', 'desc'=>'Servis rutin / tune up'],
                            ['id'=> 102, 'title'=>'Catatan: Sparepart datang', 'status'=>'catatan', 'time'=>'10:30', 'desc'=>'Cek gudang + follow up supplier'],
                        ],
                        now()->addDay()->format('Y-m-d') => [
                            ['id'=> 103, 'title'=>'Tutup (Libur)', 'status'=>'tutup', 'time'=>'-', 'desc'=>'Hari libur operasional'],
                        ],
                    ];
                    $slots = $slots ?? [
                        now()->format('Y-m-d') => ['left'=> 3, 'status'=>'open'],     // open|closed
                        now()->addDay()->format('Y-m-d') => ['left'=> 0, 'status'=>'closed'],
                    ];
                @endphp

                <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                            shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

                    {{-- header kalender --}}
                    <div class="px-5 sm:px-6 py-5 border-b border-slate-200">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="min-w-0">
                                <div id="monthTitle" class="text-xl sm:text-2xl font-semibold tracking-tight text-slate-900">—</div>
                                <div class="text-xs text-slate-500 mt-1">
                                    Klik tanggal untuk lihat detail. Tombol Tambah/Ubah/Hapus diarahkan ke halaman terpisah.
                                </div>
                            </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                    {{-- NAV (pindahan dari topbar) --}}
                    <div class="flex items-center gap-2">
                        <button id="btnToday" type="button"
                                class="h-10 px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                        Today
                        </button>

                        <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <button id="btnPrev" type="button"
                                class="h-10 w-10 grid place-items-center hover:bg-slate-50 transition"
                                aria-label="Prev">
                            <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button id="btnNext" type="button"
                                class="h-10 w-10 grid place-items-center hover:bg-slate-50 transition border-l border-slate-200"
                                aria-label="Next">
                            <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        </div>
                    </div>

                    <div class="hidden sm:block w-px h-10 bg-slate-200 mx-1"></div>

                    {{-- LEGEND --}}
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-slate-600">
                        <span class="inline-flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Aktif
                        </span>
                        <span class="inline-flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span> Tutup
                        </span>
                        <span class="inline-flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span> Catatan
                        </span>
                    </div>
                    </div>

                        </div>
                    </div>

                    {{-- kalender --}}
                    <div class="p-3 sm:p-4">
                        <div class="overflow-x-auto">
                            <div class="min-w-[980px]">
                                {{-- header hari --}}
                                <div class="grid grid-cols-7 gap-2 px-1 pb-2 text-[12px] font-semibold text-slate-600">
                                    <div class="px-2">Minggu</div>
                                    <div class="px-2">Senin</div>
                                    <div class="px-2">Selasa</div>
                                    <div class="px-2">Rabu</div>
                                    <div class="px-2">Kamis</div>
                                    <div class="px-2">Jumat</div>
                                    <div class="px-2">Sabtu</div>
                                </div>

                                {{-- grid days --}}
                                <div id="calendarGrid" class="grid grid-cols-7 gap-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
                        © DPM Workshop 2025
                    </div>
                </div>

                {{-- MODAL DETAIL --}}
                <div id="detailModal" class="fixed inset-0 z-[60] hidden">
                    <div id="detailOverlay" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

                    <div class="relative min-h-screen flex items-end sm:items-center justify-center p-3 sm:p-6">
                        <div class="w-full max-w-lg rounded-2xl bg-white border border-slate-200 shadow-[0_30px_90px_rgba(2,6,23,0.30)] overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-slate-900">Detail Jadwal</div>
                                    <div id="modalDate" class="text-xs text-slate-500 mt-0.5">—</div>
                                </div>
                                <button id="btnCloseModal"
                                        type="button"
                                        class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                                        aria-label="Tutup">
                                    <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="p-5">
                                <div id="modalSlot" class="mb-4"></div>
                                <div id="modalEvents" class="space-y-2"></div>

                                <div id="modalEmpty" class="hidden rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                                    Belum ada jadwal di tanggal ini.
                                </div>

                                <div class="mt-5 flex flex-col sm:flex-row gap-2 sm:justify-end">
                                    <a id="modalTambah"
                                       href="/jadwal_kerja/tambah?date="
                                       class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                                              bg-slate-900 text-white hover:bg-slate-800 transition">
                                        Tambah Jadwal
                                    </a>
                                    <a id="modalUbah"
                                       href="#"
                                       class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                                              border border-slate-200 bg-white hover:bg-slate-50 transition">
                                        Ubah
                                    </a>
                                    <a id="modalHapus"
                                       href="#"
                                       class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                                              border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 transition">
                                        Hapus
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <style>
            @media (prefers-reduced-motion: reduce) {
                .animate-grid-scan, .nav-item::before { animation: none !important; transition: none !important; }
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
            #sidebar { -webkit-overflow-scrolling: touch; }

            /* calendar day card */
            .day-card{
                border: 1px solid rgba(15,23,42,0.10);
                background: rgba(255,255,255,0.92);
                border-radius: 18px;
                min-height: 132px;
                overflow: hidden;
                transition: .15s ease;
            }
            .day-card:hover{
                border-color: rgba(2,6,23,0.18);
                box-shadow: 0 14px 34px rgba(2,6,23,0.10);
                transform: translateY(-1px);
            }
            .day-muted{
                opacity: .45;
                background: rgba(248,250,252,0.85);
            }
            .day-top{
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding: 10px 12px 6px 12px;
            }

            .day-top .right-slot {
            min-width: 86px; /* kira2 lebar pill */
            display:flex;
            justify-content:flex-end;
            }

            /* angka fixed biar simetris */
            .day-num{
            width: 32px;
            height: 32px;
            display: grid;
            place-items: center;
            border-radius: 999px;
            font-weight: 800;
            font-size: 13px;
            color: rgba(15,23,42,0.92);
            background: rgba(255,255,255,0.0);
            }

            /* today: cuma beda warna, ukuran tetap */
            .day-num.today{
            background: rgba(2,6,23,0.92);
            color: #fff;
            }

            .pill{
                display:inline-flex;
                align-items:center;
                gap:8px;
                font-size: 11px;
                padding: 6px 10px;
                border-radius: 12px;
                border: 1px solid rgba(15,23,42,0.10);
                background: rgba(255,255,255,0.75);
                white-space: nowrap;
                overflow:hidden;
                text-overflow: ellipsis;
                max-width: 100%;
            }
            .pill.aktif   { background: rgba(16,185,129,0.12); border-color: rgba(16,185,129,0.25); color: rgba(6,95,70,0.95); }
            .pill.catatan { background: rgba(245,158,11,0.12); border-color: rgba(245,158,11,0.25); color: rgba(120,53,15,0.95); }
            .pill.tutup   { background: rgba(244,63,94,0.12); border-color: rgba(244,63,94,0.25); color: rgba(136,19,55,0.95); }

            .day-body{ padding: 8px 12px 12px 12px; display:flex; flex-direction:column; gap:6px; }

            .has-data{
                outline: 2px solid rgba(2,6,23,0.10);
            }
        </style>

        <script>
            // sidebar active indicator
            document.querySelectorAll('[data-nav]').forEach(a => {
                if (a.dataset.active === "true") a.classList.add('is-active');
            });

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
                if (e.key === 'Escape') {
                    closeSidebar();
                    hideModal();
                }
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

            // ===== Calendar Rendering =====
            const monthTitle = document.getElementById('monthTitle');
            const grid = document.getElementById('calendarGrid');
            const btnPrev = document.getElementById('btnPrev');
            const btnNext = document.getElementById('btnNext');
            const btnToday = document.getElementById('btnToday');

            // // action buttons
            // const btnTambah = document.getElementById('btnTambah');
            // const btnUbah = document.getElementById('btnUbah');
            // const btnHapus = document.getElementById('btnHapus');

            // modal elements
            const detailModal = document.getElementById('detailModal');
            const detailOverlay = document.getElementById('detailOverlay');
            const btnCloseModal = document.getElementById('btnCloseModal');
            const modalDate = document.getElementById('modalDate');
            const modalSlot = document.getElementById('modalSlot');
            const modalEvents = document.getElementById('modalEvents');
            const modalEmpty = document.getElementById('modalEmpty');

            const modalTambah = document.getElementById('modalTambah');
            const modalUbah = document.getElementById('modalUbah');
            const modalHapus = document.getElementById('modalHapus');

            // data from backend (optional)
            const EVENTS = @json($events);
            const SLOTS  = @json($slots);

            const pad2 = (n) => String(n).padStart(2, '0');
            const ymd = (d) => `${d.getFullYear()}-${pad2(d.getMonth()+1)}-${pad2(d.getDate())}`;
            const sameDay = (a,b) => a.getFullYear()===b.getFullYear() && a.getMonth()===b.getMonth() && a.getDate()===b.getDate();

            const fmtMonth = (d) => d.toLocaleDateString('id-ID', { month:'long', year:'numeric' });
            const fmtLong = (iso) => {
                try {
                    const [y,m,dd] = iso.split('-').map(Number);
                    const obj = new Date(y, m-1, dd);
                    return obj.toLocaleDateString('id-ID', { weekday:'long', day:'2-digit', month:'long', year:'numeric' });
                } catch(e) {
                    return iso;
                }
            };

            let current = new Date();
            current.setDate(1);

            function showModal(dateStr){
                const ev = EVENTS?.[dateStr] || [];
                const slot = SLOTS?.[dateStr];

                modalDate.textContent = fmtLong(dateStr);

                // slot UI
                modalSlot.innerHTML = '';
                if (slot) {
                    const isClosed = slot.status === 'closed';
                    modalSlot.innerHTML = `
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div class="text-sm font-semibold text-slate-900">Ketersediaan</div>
                               <span class="pill ${isClosed ? 'tutup' : 'aktif'}">
                                    ${isClosed ? 'TUTUP' : 'AKTIF'}
                                </span>
                            </div>
                            <div class="text-sm text-slate-600 mt-2">
                                ${isClosed ? 'Hari ini tidak tersedia.' : `Sisa slot: <span class="font-semibold">${slot.left ?? 0}</span>`}
                            </div>
                        </div>
                    `;
                }

                // events UI
                modalEvents.innerHTML = '';
                if (ev.length > 0) {    
                   ev.forEach((e) => {
                    const status = (e.status || 'aktif'); // aktif | catatan | tutup
                    const time = e.time ? `<div class="text-xs text-slate-500 mt-0.5">${e.time}</div>` : '';
                    const desc = e.desc ? `<div class="text-xs text-slate-600 mt-1">${e.desc}</div>` : '';
                    modalEvents.innerHTML += `
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-slate-900 truncate">${e.title || 'Jadwal'}</div>
                                    ${time}
                                </div>
                                <span class="pill ${status}">${status.toUpperCase()}</span>
                            </div>
                            ${desc}
                        </div>
                    `;
                });


                    modalEmpty.classList.add('hidden');
                } else {
                    modalEmpty.classList.remove('hidden');
                }

                // modal action links
                modalTambah.href = `/tambah_jadwal_kerja?date=${encodeURIComponent(dateStr)}`;

                const hasData = (ev.length > 0) || !!slot;
                modalUbah.href  = hasData ? `/ubah_jadwal_kerja?date=${encodeURIComponent(dateStr)}` : '#';
                modalHapus.href = hasData ? `/hapus_jadwal_kerja?date=${encodeURIComponent(dateStr)}` : '#';

                modalUbah.classList.toggle('opacity-50', !hasData);
                modalUbah.classList.toggle('pointer-events-none', !hasData);
                modalHapus.classList.toggle('opacity-50', !hasData);
                modalHapus.classList.toggle('pointer-events-none', !hasData);

                detailModal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function hideModal(){
                detailModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            detailOverlay?.addEventListener('click', hideModal);
            btnCloseModal?.addEventListener('click', hideModal);

            function render() {
            grid.innerHTML = '';
            monthTitle.textContent = fmtMonth(current);

            const today = new Date();
            const year = current.getFullYear();
            const month = current.getMonth();

            const first = new Date(year, month, 1);
            const startDay = first.getDay(); // 0 = Minggu
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            // 1) kosong di depan biar alignment sesuai hari (tanpa tanggal bulan lain)
            for (let i = 0; i < startDay; i++) {
                const empty = document.createElement('div');
                empty.className = 'day-card day-muted';
                empty.innerHTML = `<div class="day-top"><div class="day-num"></div><div class="right-slot"></div></div>`;
                grid.appendChild(empty);

            }

            // 2) hanya tanggal 1..akhir bulan
            for (let day = 1; day <= daysInMonth; day++) {
                const dateObj = new Date(year, month, day);
                const key = ymd(dateObj);
                const isToday = sameDay(dateObj, today);

                const ev = EVENTS?.[key] || [];
                const slot = SLOTS?.[key];
                const hasData = (ev.length > 0) || !!slot;

                const card = document.createElement('button');
                card.type = 'button';
                card.className = `day-card text-left ${hasData ? 'has-data' : ''}`;
                card.dataset.date = key;

                const top = document.createElement('div');
                top.className = 'day-top';

                const num = document.createElement('div');
                num.className = `day-num ${isToday ? 'today' : ''}`;
                num.textContent = String(day);

                // wadah kanan biar posisi rapi walau slot kosong
                const right = document.createElement('div');
                right.className = 'right-slot';

                // slot pill
                if (slot) {
                const statusPill = document.createElement('div');
                const isClosed = slot.status === 'closed';
                statusPill.className = `pill ${isClosed ? 'tutup' : 'aktif'}`;
                statusPill.textContent = isClosed ? 'N/A' : `Slot · ${slot.left ?? 0}`;
                right.appendChild(statusPill);
                }

                top.appendChild(num);
                top.appendChild(right);


                const body = document.createElement('div');
                body.className = 'day-body';

                // events preview (max 3)
                const take = ev.slice(0, 3);
                take.forEach(e => {
                const pill = document.createElement('div');
                const status = (e.status || 'aktif'); // aktif | catatan | tutup
                pill.className = `pill ${status}`;
                pill.title = e.title || '';
                pill.textContent = e.title || 'Jadwal';
                body.appendChild(pill);
                });

                if (take.length === 0 && !slot) {
                const hint = document.createElement('div');
                hint.className = 'text-[11px] text-slate-500/80';
                hint.textContent = '—';
                body.appendChild(hint);
                }

                card.appendChild(top);
                card.appendChild(body);

                // klik => buka modal (tanpa tombol header)
                card.addEventListener('click', () => {
                showModal(key);
                });

                grid.appendChild(card);
            }

            // 3) padding belakang biar grid genap kelipatan 7 (opsional)
            const totalCells = startDay + daysInMonth;
            const remaining = (7 - (totalCells % 7)) % 7;
            for (let i = 0; i < remaining; i++) {
            const empty = document.createElement('div');
            empty.className = 'day-card day-muted';
            empty.setAttribute('aria-hidden', 'true');
            empty.innerHTML = `<div class="day-top"><div class="day-num"></div><div class="right-slot"></div></div>`;
            grid.appendChild(empty);
            }
            }


            btnPrev?.addEventListener('click', () => {
                current = new Date(current.getFullYear(), current.getMonth()-1, 1);
                render();
            });
            btnNext?.addEventListener('click', () => {
                current = new Date(current.getFullYear(), current.getMonth()+1, 1);
                render();
            });
            btnToday?.addEventListener('click', () => {
                const t = new Date();
                current = new Date(t.getFullYear(), t.getMonth(), 1);
                render();
                showModal(ymd(t)); // optional: langsung buka modal hari ini
            });


            // init
            render();
        </script>

    </main>
</div>
</body>
</html>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - DPM Workshop</title>

    {{-- Tailwind via Vite --}}
    @vite('resources/js/app.js')

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <a href="/tampilan_dashboard"
                   data-nav data-active="true"
                   class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                          bg-white/12 text-white border border-white/10
                          hover:bg-white/10 hover:text-white transition relative overflow-hidden">
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
                    <a href="/tampilan_barang" data-nav class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8 4-8-4"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10l8 4 8-4V7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v10"/>
                            </svg>
                        </span>
                        Kelola Barang
                    </a>
                    <a href="/barang_keluar" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 7h7v7"/>
                            </svg>
                        </span>
                        Barang Keluar
                    </a>
                    <a href="/barang_masuk" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
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
                    <a href="/riwayat_perubahan_stok" data-nav class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5l3 2"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        Riwayat Perubahan Stok
                    </a>
                    <a href="/riwayat_transaksi" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10a2 2 0 012 2v16l-2-1-2 1-2-1-2 1-2-1-2 1V5a2 2 0 012-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h6M9 16h4"/>
                            </svg>
                        </span>
                        Riwayat Transaksi
                    </a>
                    <a href="/laporan_penjualan" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
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

                    <a href="/kelola_jadwal_kerja"
                       class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        Kelola Jadwal Kerja
                    </a>

                    <a href="/tampilan_manajemen_staf"
                       class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/80 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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

        {{-- Background --}}
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
            <div class="absolute inset-0 opacity-[0.10]"
                 style="background-image:
                    linear-gradient(to right, rgba(2,6,23,0.05) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(2,6,23,0.05) 1px, transparent 1px);
                    background-size: 56px 56px;">
            </div>
            <div class="absolute -top-48 left-1/2 -translate-x-1/2 h-[680px] w-[680px] rounded-full blur-3xl opacity-10
                        bg-gradient-to-tr from-blue-950/25 via-blue-700/10 to-transparent"></div>
        </div>

        {{-- TOPBAR --}}
        <header class="relative bg-white/75 backdrop-blur border-b border-slate-200 sticky top-0 z-20"
                data-animate>
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
                        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Dashboard</h1>
                        <p class="text-xs text-slate-500">Ringkasan barang masuk/keluar, jadwal, dan shortcut cepat</p>
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
                </div>

            </div>
        </header>

        {{-- CONTENT --}}
        <section class="relative p-4 sm:p-6">
            <div class="max-w-[1280px] mx-auto w-full space-y-6">

                {{-- SUMMARY CARDS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5" data-animate-group>
                    <a href="/stok_realtime"
                       data-animate
                       class="group rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
                              shadow-[0_16px_44px_rgba(2,6,23,0.08)]
                              p-5 hover:shadow-[0_22px_60px_rgba(2,6,23,0.12)] transition">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm text-slate-500">Stok Real-time</p>
                                <p class="text-2xl font-bold text-slate-900 mt-1">Lihat stok</p>
                                <p class="text-xs text-slate-400 mt-1">Klik untuk menampilkan stok saat ini</p>
                            </div>
                            <div class="h-12 w-12 rounded-2xl bg-emerald-100 text-emerald-700 grid place-items-center border border-emerald-200">
                                <span class="text-xl">📦</span>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-emerald-700 font-semibold group-hover:underline">
                            Buka halaman stok →
                        </div>
                    </a>

                    <a href="/tampilan_invoice"
                       data-animate
                       class="group rounded-2xl border border-slate-900 bg-slate-900 text-white
                              shadow-[0_16px_44px_rgba(2,6,23,0.18)]
                              p-5 hover:bg-slate-800 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm text-slate-300">Invoice</p>
                                <p class="text-xl font-semibold mt-1">Buat Invoice</p>
                                <p class="text-xs text-slate-400 mt-1">Mulai transaksi penjualan</p>
                            </div>
                            <div class="h-12 w-12 rounded-2xl bg-white/10 grid place-items-center border border-white/10">
                                <span class="text-xl">🧾</span>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-slate-200 font-semibold group-hover:underline">
                            Buat sekarang →
                        </div>
                    </a>
                </div>

                {{-- CHARTS --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" data-animate-group>
                    <div data-animate
                         class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
                                shadow-[0_16px_44px_rgba(2,6,23,0.08)] p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="font-semibold text-slate-900">Barang Masuk</p>
                                <p class="text-xs text-slate-500">6 bulan terakhir</p>
                            </div>
                            <div class="flex gap-1">
                                <button type="button" class="chart-btn" onclick="setMasuk('line')">Line</button>
                                <button type="button" class="chart-btn" onclick="setMasuk('bar')">Bar</button>
                            </div>
                        </div>
                        <canvas id="chartMasuk" height="120"></canvas>
                    </div>

                    <div data-animate
                         class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
                                shadow-[0_16px_44px_rgba(2,6,23,0.08)] p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="font-semibold text-slate-900">Barang Keluar</p>
                                <p class="text-xs text-slate-500">6 bulan terakhir</p>
                            </div>
                            <div class="flex gap-1">
                                <button type="button" class="chart-btn" onclick="setKeluar('bar')">Bar</button>
                                <button type="button" class="chart-btn" onclick="setKeluar('line')">Line</button>
                            </div>
                        </div>
                        <canvas id="chartKeluar" height="120"></canvas>
                    </div>
                </div>

                {{-- ================== JADWAL KERJA ================== --}}
                @php
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
                        now()->format('Y-m-d') => ['left'=> 3, 'status'=>'open'],
                        now()->addDay()->format('Y-m-d') => ['left'=> 0, 'status'=>'closed'],
                    ];

                    $statusFor = function($date) use ($events, $slots) {
                        $ev = $events[$date] ?? [];
                        $slot = $slots[$date] ?? null;

                        if ($slot && ($slot['status'] ?? '') === 'closed') {
                            return ['label'=>'Tutup', 'class'=>'bg-rose-100 text-rose-700'];
                        }
                        foreach ($ev as $e) {
                            if (($e['status'] ?? '') === 'tutup') return ['label'=>'Tutup', 'class'=>'bg-rose-100 text-rose-700'];
                        }
                        foreach ($ev as $e) {
                            if (($e['status'] ?? '') === 'catatan') return ['label'=>'Catatan', 'class'=>'bg-amber-100 text-amber-800'];
                        }
                        if (!empty($ev) || ($slot && ($slot['status'] ?? '') === 'open')) {
                            return ['label'=>'Aktif', 'class'=>'bg-emerald-100 text-emerald-700'];
                        }
                        return ['label'=>'—', 'class'=>'bg-slate-100 text-slate-700'];
                    };
                @endphp

                <div data-animate
                     class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
                            shadow-[0_16px_44px_rgba(2,6,23,0.08)] p-5">

                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="font-semibold text-slate-900">Jadwal Kerja</p>
                            <p class="text-xs text-slate-500">Preview 7 hari ke depan</p>
                        </div>

                        <button id="btnOpenJadwalPopup" type="button"
                                class="text-sm text-emerald-700 font-semibold hover:underline">
                            Lihat penuh →
                        </button>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2 text-xs">
                        @for ($i = 0; $i < 7; $i++)
                            @php
                                $d = now()->addDays($i);
                                $key = $d->format('Y-m-d');
                                $info = $statusFor($key);
                            @endphp
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-2">
                                <div class="font-semibold text-slate-700">{{ $d->format('D') }}</div>
                                <div class="text-[11px] text-slate-500 mb-1">{{ $d->format('d M') }}</div>
                                <div class="text-[11px] rounded-lg px-2 py-1 {{ $info['class'] }}">
                                    {{ $info['label'] }}
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <div data-animate class="text-xs text-slate-400 pt-2">
                    © DPM Workshop 2025
                </div>

            </div>
        </section>

        {{-- ================= POPUP FULL JADWAL (VIEW ONLY) ================= --}}
        <div id="jadwalPopup" class="fixed inset-0 z-[80] hidden">
            <div id="jadwalPopupOverlay" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

            <div class="relative min-h-screen flex items-end sm:items-center justify-center p-3 sm:p-6">
                <div class="w-full max-w-[1100px] rounded-2xl bg-white border border-slate-200
                            shadow-[0_30px_90px_rgba(2,6,23,0.30)]
                            max-h-[85vh] flex flex-col overflow-hidden">

                    <div class="px-5 sm:px-6 py-4 border-b border-slate-200 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-slate-900">Jadwal Kerja (View Only)</div>
                            <div class="text-xs text-slate-500 mt-0.5">Klik tanggal untuk detail. Tidak ada edit.</div>
                        </div>
                        <button id="btnCloseJadwalPopup"
                                type="button"
                                class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                                aria-label="Tutup">
                            <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="p-4 sm:p-6 overflow-y-auto">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
                            <div class="min-w-0">
                                <div id="dashMonthTitle" class="text-xl sm:text-2xl font-semibold tracking-tight text-slate-900">—</div>
                                <div class="text-xs text-slate-500 mt-1">Jadwal tampil sesuai input admin (read-only).</div>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <div class="flex items-center gap-2">
                                    <button id="dashBtnToday" type="button"
                                            class="h-10 px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                                        Today
                                    </button>

                                    <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-white">
                                        <button id="dashBtnPrev" type="button"
                                                class="h-10 w-10 grid place-items-center hover:bg-slate-50 transition"
                                                aria-label="Prev">
                                            <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>
                                        <button id="dashBtnNext" type="button"
                                                class="h-10 w-10 grid place-items-center hover:bg-slate-50 transition border-l border-slate-200"
                                                aria-label="Next">
                                            <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="hidden sm:block w-px h-10 bg-slate-200 mx-1"></div>

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

                        <div class="overflow-x-auto">
                            <div class="min-w-[980px]">
                                <div class="grid grid-cols-7 gap-2 px-1 pb-2 text-[12px] font-semibold text-slate-600">
                                    <div class="px-2">Minggu</div>
                                    <div class="px-2">Senin</div>
                                    <div class="px-2">Selasa</div>
                                    <div class="px-2">Rabu</div>
                                    <div class="px-2">Kamis</div>
                                    <div class="px-2">Jumat</div>
                                    <div class="px-2">Sabtu</div>
                                </div>

                                <div id="dashCalendarGrid" class="grid grid-cols-7 gap-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
                        © DPM Workshop 2025
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL DETAIL (VIEW ONLY) --}}
        <div id="dashDetailModal" class="fixed inset-0 z-[90] hidden">
            <div id="dashDetailOverlay" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

            <div class="relative min-h-screen flex items-end sm:items-center justify-center p-3 sm:p-6">
                <div class="w-full max-w-lg rounded-2xl bg-white border border-slate-200 shadow-[0_30px_90px_rgba(2,6,23,0.30)]
                            max-h-[85vh] flex flex-col overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-slate-900">Detail Jadwal</div>
                            <div id="dashModalDate" class="text-xs text-slate-500 mt-0.5">—</div>
                        </div>
                        <button id="dashBtnCloseModal"
                                type="button"
                                class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                                aria-label="Tutup">
                            <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="p-5 overflow-y-auto">
                        <div id="dashModalSlot" class="mb-4"></div>
                        <div id="dashModalEvents" class="space-y-2"></div>

                        <div id="dashModalEmpty" class="hidden rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                            Belum ada jadwal di tanggal ini.
                        </div>

                        <div class="mt-5 flex justify-end">
                            <button id="dashModalTutup"
                                    type="button"
                                    class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                                           border border-slate-200 bg-white hover:bg-slate-50 transition">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- data utk JS --}}
        <script>
            window.DASH_EVENTS = @json($events);
            window.DASH_SLOTS  = @json($slots);
        </script>

        <style>
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

            .chart-btn{
                font-size:11px;
                padding:4px 10px;
                border-radius:999px;
                border:1px solid rgba(15,23,42,.15);
                background:#fff;
                transition:.15s;
            }
            .chart-btn:hover{ background:#f1f5f9; }

            /* ===== kalender popup styles ===== */
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
            .day-muted{ opacity: .45; background: rgba(248,250,252,0.85); }

            .day-top{ display:flex; align-items:center; justify-content:space-between; padding: 10px 12px 6px 12px; }
            .day-top .right-slot{ min-width: 86px; display:flex; justify-content:flex-end; }

            .day-num{
                width: 32px; height: 32px;
                display:grid; place-items:center;
                border-radius: 999px;
                font-weight: 800; font-size: 13px;
                color: rgba(15,23,42,0.92);
            }
            .day-num.today{ background: rgba(2,6,23,0.92); color:#fff; }

            .pill{
                display:inline-flex; align-items:center;
                font-size: 11px;
                padding: 6px 10px;
                border-radius: 12px;
                border: 1px solid rgba(15,23,42,0.10);
                background: rgba(255,255,255,0.75);
                white-space: nowrap; overflow:hidden; text-overflow: ellipsis;
                max-width: 100%;
            }
            .pill.aktif   { background: rgba(16,185,129,0.12); border-color: rgba(16,185,129,0.25); color: rgba(6,95,70,0.95); }
            .pill.catatan { background: rgba(245,158,11,0.12); border-color: rgba(245,158,11,0.25); color: rgba(120,53,15,0.95); }
            .pill.tutup   { background: rgba(244,63,94,0.12); border-color: rgba(244,63,94,0.25); color: rgba(136,19,55,0.95); }

            .day-body{ padding: 8px 12px 12px 12px; display:flex; flex-direction:column; gap:6px; }
            .has-data{ outline: 2px solid rgba(2,6,23,0.10); }

            /* =================== ANIMASI POP UP (STAGGER) =================== */
            [data-animate]{
                opacity: 0;
                transform: translateY(14px) scale(.985);
                filter: blur(3px);
                transition:
                    opacity .55s ease,
                    transform .55s cubic-bezier(.2,.8,.2,1),
                    filter .55s ease;
                will-change: opacity, transform, filter;
            }
            [data-animate].in{
                opacity: 1;
                transform: translateY(0) scale(1);
                filter: blur(0);
            }

            /* kalau user matiin animasi */
            @media (prefers-reduced-motion: reduce){
                [data-animate]{ opacity: 1 !important; transform: none !important; filter: none !important; transition:none !important; }
            }
        </style>

        <script>
            // ================= ANIMASI MASUK: POP UP SATU-SATU =================
            (function(){
                const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                if (reduce) return;

                // ambil semua item yg mau dianimasikan
                const items = Array.from(document.querySelectorAll('[data-animate]'));

                // kalau ada group, animasiin per group biar rapih (optional)
                // tapi tetap aman kalau gak ada group (tetap di-stagger)
                const baseDelay = 60;   // jarak antar item (ms)
                const startDelay = 80;  // delay awal (ms)

                items.forEach((el, i) => {
                    el.style.transitionDelay = (startDelay + (i * baseDelay)) + 'ms';
                });

                // trigger setelah 1 frame biar transition kebaca
                requestAnimationFrame(() => {
                    items.forEach(el => el.classList.add('in'));
                });
            })();
        </script>

        <script>
            // sidebar active indicator
            document.querySelectorAll('[data-nav]').forEach(a => {
                if (a.dataset.active === "true") a.classList.add('is-active');
            });

            // mobile sidebar toggle
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

            // ===== Charts (dummy, bisa ganti bentuk) =====
            const labels = ['Jan','Feb','Mar','Apr','Mei','Jun'];
            let chartMasuk, chartKeluar;

            function renderMasuk(type='line'){
                chartMasuk?.destroy();
                chartMasuk = new Chart(document.getElementById('chartMasuk'), {
                    type,
                    data: {
                        labels,
                        datasets: [{
                            data: [40,55,30,70,60,90],
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16,185,129,0.2)',
                            fill: type === 'line',
                            tension: .4
                        }]
                    },
                    options:{
                        plugins:{ legend:{ display:false } },
                        scales:{
                            x:{ grid:{ display:false } },
                            y:{ grid:{ color:'rgba(2,6,23,0.06)' } }
                        }
                    }
                });
            }

            function renderKeluar(type='bar'){
                chartKeluar?.destroy();
                chartKeluar = new Chart(document.getElementById('chartKeluar'), {
                    type,
                    data: {
                        labels,
                        datasets: [{
                            data: [30,20,45,50,40,60],
                            backgroundColor: '#f43f5e',
                            borderColor: '#f43f5e'
                        }]
                    },
                    options:{
                        plugins:{ legend:{ display:false } },
                        scales:{
                            x:{ grid:{ display:false } },
                            y:{ grid:{ color:'rgba(2,6,23,0.06)' } }
                        }
                    }
                });
            }

            function setMasuk(t){ renderMasuk(t); }
            function setKeluar(t){ renderKeluar(t); }

            renderMasuk('line');
            renderKeluar('bar');

            // ================== POPUP JADWAL ==================
            const jadwalPopup = document.getElementById('jadwalPopup');
            const jadwalPopupOverlay = document.getElementById('jadwalPopupOverlay');
            const btnOpenJadwalPopup = document.getElementById('btnOpenJadwalPopup');
            const btnCloseJadwalPopup = document.getElementById('btnCloseJadwalPopup');

            const openJadwalPopup = () => {
                jadwalPopup.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                dashRender();
            };

            const closeJadwalPopup = () => {
                jadwalPopup.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                dashHideModal();
            };

            btnOpenJadwalPopup?.addEventListener('click', openJadwalPopup);
            btnCloseJadwalPopup?.addEventListener('click', closeJadwalPopup);
            jadwalPopupOverlay?.addEventListener('click', closeJadwalPopup);

            // ESC: tutup detail dulu, lalu popup
            document.addEventListener('keydown', (e) => {
                if (e.key !== 'Escape') return;

                const detailOpen = !document.getElementById('dashDetailModal').classList.contains('hidden');
                if (detailOpen) dashHideModal();
                else if (!jadwalPopup.classList.contains('hidden')) closeJadwalPopup();
            });

            // ================== KALENDER (DI POPUP) ==================
            const monthTitle = document.getElementById('dashMonthTitle');
            const grid = document.getElementById('dashCalendarGrid');
            const btnPrev = document.getElementById('dashBtnPrev');
            const btnNext = document.getElementById('dashBtnNext');
            const btnToday = document.getElementById('dashBtnToday');

            // detail modal
            const detailModal = document.getElementById('dashDetailModal');
            const detailOverlay = document.getElementById('dashDetailOverlay');
            const btnCloseModal = document.getElementById('dashBtnCloseModal');
            const modalTutup = document.getElementById('dashModalTutup');

            const modalDate = document.getElementById('dashModalDate');
            const modalSlot = document.getElementById('dashModalSlot');
            const modalEvents = document.getElementById('dashModalEvents');
            const modalEmpty = document.getElementById('dashModalEmpty');

            const EVENTS = window.DASH_EVENTS || {};
            const SLOTS  = window.DASH_SLOTS  || {};

            const pad2 = (n) => String(n).padStart(2, '0');
            const ymd = (d) => `${d.getFullYear()}-${pad2(d.getMonth()+1)}-${pad2(d.getDate())}`;
            const sameDay = (a,b) => a.getFullYear()===b.getFullYear() && a.getMonth()===b.getMonth() && a.getDate()===b.getDate();

            const fmtMonth = (d) => d.toLocaleDateString('id-ID', { month:'long', year:'numeric' });
            const fmtLong = (iso) => {
                try {
                    const [y,m,dd] = iso.split('-').map(Number);
                    const obj = new Date(y, m-1, dd);
                    return obj.toLocaleDateString('id-ID', { weekday:'long', day:'2-digit', month:'long', year:'numeric' });
                } catch(e) { return iso; }
            };

            let current = new Date();
            current.setDate(1);

            function dashShowModal(dateStr){
                const ev = EVENTS?.[dateStr] || [];
                const slot = SLOTS?.[dateStr];

                modalDate.textContent = fmtLong(dateStr);

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

                modalEvents.innerHTML = '';
                if (ev.length > 0) {
                    ev.forEach((e) => {
                        const status = (e.status || 'aktif');
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

                detailModal.classList.remove('hidden');
            }

            function dashHideModal(){
                detailModal.classList.add('hidden');
            }

            detailOverlay?.addEventListener('click', dashHideModal);
            btnCloseModal?.addEventListener('click', dashHideModal);
            modalTutup?.addEventListener('click', dashHideModal);

            function dashRender() {
                if (!grid) return;

                grid.innerHTML = '';
                monthTitle.textContent = fmtMonth(current);

                const today = new Date();
                const year = current.getFullYear();
                const month = current.getMonth();

                const first = new Date(year, month, 1);
                const startDay = first.getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                // empty cells
                for (let i = 0; i < startDay; i++) {
                    const empty = document.createElement('div');
                    empty.className = 'day-card day-muted';
                    empty.innerHTML = `<div class="day-top"><div class="day-num"></div><div class="right-slot"></div></div>`;
                    grid.appendChild(empty);
                }

                // days
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

                    const right = document.createElement('div');
                    right.className = 'right-slot';

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

                    const take = ev.slice(0, 3);
                    take.forEach(e => {
                        const pill = document.createElement('div');
                        const status = (e.status || 'aktif');
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

                    card.addEventListener('click', () => dashShowModal(key));
                    grid.appendChild(card);
                }

                // trailing padding
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

            btnPrev?.addEventListener('click', () => { current = new Date(current.getFullYear(), current.getMonth()-1, 1); dashRender(); });
            btnNext?.addEventListener('click', () => { current = new Date(current.getFullYear(), current.getMonth()+1, 1); dashRender(); });
            btnToday?.addEventListener('click', () => {
                const t = new Date();
                current = new Date(t.getFullYear(), t.getMonth(), 1);
                dashRender();
                dashShowModal(ymd(t));
            });
        </script>

    </main>
</div>
</body>
</html>

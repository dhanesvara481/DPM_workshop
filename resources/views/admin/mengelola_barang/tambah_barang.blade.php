<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Barang</title>
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

            {{-- close button (mobile) --}}
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

                    {{-- Kelola Barang --}}
                    <a href="{{ route('mengelola_barang') ?? '#' }}"
                    class="nav-item is-active group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8 4-8-4"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10l8 4 8-4V7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v10"/>
                            </svg>
                        </span>
                        Kelola Barang
                    </a>    

                    {{-- submenu: Tambah Barang (aktif) --}}
                    {{-- <div class="mt-2 ml-4 pl-4 border-l border-white/10 space-y-1">
                        <a href="{{ route('tambah_barang') ?? '#' }}"
                        class="nav-item is-active group flex items-center gap-3 rounded-xl px-4 py-2 text-[13px]
                                bg-white/12 text-white border border-white/10 relative overflow-hidden">
                            <span class="h-7 w-7 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                                <svg class="h-[16px] w-[16px] text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                                </svg>
                            </span>
                            Tambah Barang
                        </a>
                    </div> --}}

                    <a href="#"
                    class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 7h7v7"/>
                            </svg>
                        </span>
                        Barang Keluar
                    </a>

                    <a href="#"
                    class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 7L7 17"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 10v7h7"/>
                            </svg>
                        </span>
                        Barang Masuk
                    </a>

                    {{-- ================= RIWAYAT & LAPORAN ================= --}}
                    <div class="mt-3">
                        <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">RIWAYAT & LAPORAN</p>

                        <a href="#"
                        data-nav
                        class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80
                                hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                                {{-- icon: clock --}}
                                <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5l3 2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            Riwayat Perubahan Stok
                        </a>

                        <a href="#"
                        data-nav
                        class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80
                                hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                                {{-- icon: receipt --}}
                                <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10a2 2 0 012 2v16l-2-1-2 1-2-1-2 1-2-1-2 1V5a2 2 0 012-2z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h6M9 16h4"/>
                                </svg>
                            </span>
                            Riwayat Transaksi
                        </a>

                        <a href="#"
                        data-nav
                        class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80
                                hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                                {{-- icon: chart --}}
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

                    {{-- ================= MANAJEMEN ================= --}}
                    <div class="mt-3">
                        <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">MANAJEMEN</p>

                        <a href="#"
                        data-nav
                        class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80
                                hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                                {{-- icon: calendar --}}
                                <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            Kelola Jadwal Kerja
                        </a>

                        <a href="#"
                        data-nav
                        class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80
                                hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                                {{-- icon: users --}}
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

                    {{-- ================= LOGOUT ================= --}}
                    <div class="mt-4 pt-4 border-t border-white/10">
                        <a href="#"
                        class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80
                                hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                                {{-- icon: logout --}}
                                <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 17l5-5-5-5"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21V3a2 2 0 00-2-2h-6"/>
                                </svg>
                            </span>
                            Logout
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </aside>

    {{-- overlay (mobile) - PASTIIN CUMA SATU --}}
    <div id="overlay"
        class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-sm hidden md:hidden"></div>



    {{-- ================= MAIN ================= --}}
    <main id="main"
      class="flex-1 min-w-0 relative overflow-hidden md:ml-[280px] transition-[margin] duration-300 ease-out">

        {{-- background (grid scan) --}}
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
                {{-- hamburger (mobile) --}}
                <button id="btnSidebar"
                        type="button"
                        class="md:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                        aria-label="Buka menu">
                    <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div class="min-w-0">
                    <h1 class="text-sm font-semibold tracking-tight text-slate-900">Tambah Barang</h1>
                    <p class="text-xs text-slate-500">Input data barang baru ke sistem.</p>
                </div>
                </div>

                <div class="flex items-center gap-2">
                    {{-- notif aja --}}
                    <button type="button"
                            class="tip h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                            data-tip="Notifikasi">
                        <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
                        </svg>
                    </button>

                    {{-- tombol kembali tetap boleh, tapi jadi icon/button biar konsisten --}}
                    <a href="{{ route('mengelola_barang') ?? '#' }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition px-3 py-2 text-sm">
                        <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </header>


        {{-- CONTENT --}}
       <section class="relative p-6">
        <div class="max-w-[980px] mx-auto w-full">

                {{-- Header card --}}
                <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200 shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Form Barang</p>
                            <p class="text-xs text-slate-500">Pastikan data benar sebelum disimpan.</p>
                        </div>

                        <span class="hidden sm:inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs text-slate-600">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Ready
                        </span>
                    </div>

                    {{-- FORM --}}
                   <form id="formBarang" method="POST" action="#" class="px-6 py-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Kode Barang --}}
                        <div class="field">
                            <label class="block text-xs font-semibold tracking-widest text-slate-600 mb-2">KODE BARANG</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10M7 12h10M7 17h10"/>
                                    </svg>
                                </span>
                                <input id="kode_barang" name="kode_barang" type="text" required
                                    placeholder="Contoh: BRG-001"
                                    class="w-full pl-9 pr-20 py-3 rounded-xl border border-slate-200 bg-white/95 text-sm
                                            placeholder:text-slate-400
                                            focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                                <button type="button" id="btnGenerate"
                                        class="absolute inset-y-0 right-0 mr-2 my-2 px-3 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition text-xs font-semibold">
                                    Auto
                                </button>
                            </div>
                            <p class="mt-2 text-[11px] text-slate-500">Tips: klik <b>Auto</b> untuk generate kode cepat.</p>
                        </div>

                        {{-- Nama Barang --}}
                        <div class="field">
                            <label class="block text-xs font-semibold tracking-widest text-slate-600 mb-2">NAMA BARANG</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V8a2 2 0 012-2h6a2 2 0 012 2v8"/>
                                    </svg>
                                </span>
                                <input id="nama_barang" name="nama_barang" type="text" required
                                    placeholder="Contoh: Oli Mesin"
                                    class="w-full pl-9 pr-3 py-3 rounded-xl border border-slate-200 bg-white/95 text-sm
                                            placeholder:text-slate-400
                                            focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                            </div>
                        </div>

                        {{-- Satuan --}}
                        <div class="field md:col-span-2">
                            <label class="block text-xs font-semibold tracking-widest text-slate-600 mb-2">SATUAN</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6M9 12h6M9 17h6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 4h14v16H5z"/>
                                    </svg>
                                </span>
                                <select id="satuan" name="satuan" required
                                        class="w-full pl-9 pr-3 py-3 rounded-xl border border-slate-200 bg-white/95 text-sm
                                            focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                                    <option value="" selected disabled>Pilih satuan</option>
                                    <option value="pcs">pcs</option>
                                    <option value="unit">unit</option>
                                    <option value="botol">botol</option>
                                    <option value="liter">liter</option>
                                    <option value="set">set</option>
                                </select>
                            </div>
                        </div>

                        {{-- ====== SECTION HARGA (rapi 2 kolom) ====== --}}
                        <div class="md:col-span-2">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50/40 p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <p class="text-xs font-semibold tracking-widest text-slate-600">HARGA</p>
                                        <p class="text-[11px] text-slate-500 mt-1">Isi harga beli & jual (stok diatur lewat Stok Masuk).</p>
                                    </div>
                                    <span class="text-[11px] text-slate-500">Preview margin di bawah</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    {{-- Harga Beli --}}
                                    <div class="field">
                                        <label class="block text-xs font-semibold tracking-widest text-slate-600 mb-2">HARGA BELI</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm font-semibold">Rp</span>
                                            <input id="harga_beli" name="harga_beli" type="text" inputmode="numeric" required
                                                placeholder="0"
                                                class="money w-full pl-10 pr-3 py-3 rounded-xl border border-slate-200 bg-white text-sm
                                                        placeholder:text-slate-400
                                                        focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                                        </div>
                                        <p class="mt-2 text-[11px] text-slate-500">Masukkan harga dalam format angka.</p>
                                    </div>

                                    {{-- Harga Jual --}}
                                    <div class="field">
                                        <label class="block text-xs font-semibold tracking-widest text-slate-600 mb-2">HARGA JUAL</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm font-semibold">Rp</span>
                                            <input id="harga_jual" name="harga_jual" type="text" inputmode="numeric" required
                                                placeholder="0"
                                                class="money w-full pl-10 pr-3 py-3 rounded-xl border border-slate-200 bg-white text-sm
                                                        placeholder:text-slate-400
                                                        focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                                        </div>

                                        {{-- mini hint kanan biar seimbang --}}
                                        <p class="mt-2 text-[11px] text-slate-500">Disarankan ≥ harga beli.</p>
                                    </div>

                                {{-- Preview margin full width --}}
                                <div class="md:col-span-2">
                                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3">
                                        <div class="flex items-center justify-between">
                                            <div class="text-[11px] text-slate-500">
                                                Preview Harga
                                                <span class="ml-2 text-[11px] text-slate-400">(Beli & Jual)</span>
                                            </div>
                                            <div class="text-[11px] text-slate-400">Live</div>
                                        </div>

                                        <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-3">
                                            <div class="rounded-xl border border-slate-200 bg-slate-50/40 px-4 py-3">
                                                <div class="text-[11px] tracking-widest text-slate-500 font-semibold">HARGA BELI</div>
                                                <div id="previewBeli" class="mt-1 text-sm font-semibold text-slate-900">Rp 0</div>
                                            </div>

                                            <div class="rounded-xl border border-slate-200 bg-slate-50/40 px-4 py-3">
                                                <div class="text-[11px] tracking-widest text-slate-500 font-semibold">HARGA JUAL</div>
                                                <div id="previewJual" class="mt-1 text-sm font-semibold text-slate-900">Rp 0</div>
                                            </div>

                                            <div class="rounded-xl border border-slate-200 bg-slate-50/40 px-4 py-3">
                                                <div class="text-[11px] tracking-widest text-slate-500 font-semibold">SELISIH</div>
                                                <div id="previewSelisih" class="mt-1 text-sm font-semibold text-slate-900">Rp 0</div>
                                                <div id="selisihHint" class="mt-1 text-[11px] text-slate-500">—</div>
                                            </div>
                                        </div>

                                </div>
                            </div>
                        {{-- ====== END SECTION HARGA ====== --}}
                        </div>

                    {{-- Actions --}}
                    <div class="mt-7 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                        <div class="flex w-full items-center justify-end gap-2">
                            <button type="button" id="btnReset"
                                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition px-4 py-2.5 text-sm font-semibold">
                                Reset
                            </button>

                            <button type="submit" id="btnSave"
                                    class="btn-shine inline-flex items-center gap-2 rounded-xl bg-blue-950 hover:bg-blue-900 transition px-5 py-2.5 text-sm font-semibold text-white
                                        shadow-[0_12px_24px_rgba(2,6,23,0.16)]">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>


                    <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
                        © DPW Workshop 2025
                    </div>
                </div>
            </div>
        </section>

        {{-- Toast --}}
        <div id="toast"
             class="fixed bottom-6 right-6 z-50 hidden w-[340px] rounded-2xl border border-slate-200 bg-white/90 backdrop-blur px-4 py-3 shadow-[0_18px_48px_rgba(2,6,23,0.14)]">
            <div class="flex items-start gap-3">
                <div id="toastDot" class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></div>
                <div class="min-w-0">
                    <p id="toastTitle" class="text-sm font-semibold text-slate-900">Berhasil</p>
                    <p id="toastMsg" class="text-xs text-slate-600 mt-0.5">Data tersimpan.</p>
                </div>
                <button id="toastClose" class="ml-auto text-slate-500 hover:text-slate-800 transition" type="button" aria-label="Close">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <style>
            @media (prefers-reduced-motion: reduce) {
                .animate-grid-scan, .btn-shine { animation: none !important; transition: none !important; }
            }
            @keyframes gridScan {
                0%   { background-position: 0 0, 0 0; opacity: 0.10; }
                40%  { opacity: 0.22; }
                60%  { opacity: 0.18; }
                100% { background-position: 220px 220px, -260px 260px; opacity: 0.10; }
            }
            .animate-grid-scan { animation: gridScan 8.5s ease-in-out infinite; }

            /* button shine */
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

            /* invalid shake */
            @keyframes shake {
                0% { transform: translateX(0) }
                25% { transform: translateX(-6px) }
                50% { transform: translateX(6px) }
                75% { transform: translateX(-4px) }
                100% { transform: translateX(0) }
            }
            .shake { animation: shake .28s ease; }

        /* ===== Sidebar nav-item ===== */
        /* sidebar active indicator (clean & kalem) */
        .nav-item{
            position: relative;
        }

        .nav-item::before{
            content:"";
            position:absolute;
            left:0;
            top:10px;
            bottom:10px;
            width:3px;
            background: linear-gradient(
                to bottom,
                rgba(255,255,255,0),
                rgba(255,255,255,.75),
                rgba(255,255,255,0)
            );
            opacity:0;
            transform: translateX(-6px);
            transition: .25s ease;
            border-radius: 999px;
        }

        /* active */
        .nav-item.is-active{
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.10);
        }

        .nav-item.is-active::before{
            opacity:.95;
            transform: translateX(0);
        }

    

        /* sidebar scroll smooth di iOS */
        #sidebar{
        -webkit-overflow-scrolling: touch;
        }



        </style>

        <script>
            // --- helpers ---
            const rupiah = (n) => {
                const num = Number(n || 0);
                return 'Rp ' + num.toLocaleString('id-ID');
            };

            const parseMoney = (s) => {
                if (!s) return 0;
                return Number(String(s).replace(/[^\d]/g, '')) || 0;
            };

            const formatMoneyInput = (el) => {
                const val = parseMoney(el.value);
                el.value = val.toLocaleString('id-ID');
                return val;
            };

            const toastEl = document.getElementById('toast');
            const toastTitle = document.getElementById('toastTitle');
            const toastMsg = document.getElementById('toastMsg');
            const toastDot = document.getElementById('toastDot');
            const toastClose = document.getElementById('toastClose');

            let toastTimer = null;
            const showToast = (title, msg, type='success') => {
                toastTitle.textContent = title;
                toastMsg.textContent = msg;

                toastDot.className = "mt-1 h-2.5 w-2.5 rounded-full " + (type==='success' ? "bg-emerald-500" : "bg-red-500");
                toastEl.classList.remove('hidden');

                clearTimeout(toastTimer);
                toastTimer = setTimeout(() => toastEl.classList.add('hidden'), 2600);
            };

            toastClose?.addEventListener('click', () => toastEl.classList.add('hidden'));

            // auto code
            const kode = document.getElementById('kode_barang');
            document.getElementById('btnGenerate')?.addEventListener('click', () => {
                const rnd = Math.floor(100 + Math.random() * 900);
                kode.value = `BRG-${rnd}`;
                kode.focus();
                showToast('Kode dibuat', `Kode: ${kode.value}`, 'success');
            });

            // reset
           document.getElementById('btnReset')?.addEventListener('click', () => {
                document.getElementById('formBarang').reset();
                if (previewBeli) previewBeli.textContent = 'Rp 0';
                if (previewJual) previewJual.textContent = 'Rp 0';
                if (previewSelisih) previewSelisih.textContent = 'Rp 0';
                if (selisihHint) selisihHint.textContent = '—';
                showToast('Reset', 'Form dikosongkan.', 'success');
            });


          
            // preview harga (beli & jual)
            const beli = document.getElementById('harga_beli');
            const jual = document.getElementById('harga_jual');

            const previewBeli = document.getElementById('previewBeli');
            const previewJual = document.getElementById('previewJual');
            const previewSelisih = document.getElementById('previewSelisih');
            const selisihHint = document.getElementById('selisihHint');


           const updatePreviewHarga = () => {
                const b = parseMoney(beli.value);
                const j = parseMoney(jual.value);
                const s = j - b;

                if (previewBeli) previewBeli.textContent = rupiah(b);
                if (previewJual) previewJual.textContent = rupiah(j);

                if (previewSelisih) previewSelisih.textContent = rupiah(Math.abs(s));

                // hint status selisih
                if (selisihHint) {
                    if (b === 0 && j === 0) {
                        selisihHint.textContent = '—';
                    } else if (s > 0) {
                        selisihHint.textContent = 'Untung';
                    } else if (s < 0) {
                        selisihHint.textContent = 'Rugi';
                    } else {
                        selisihHint.textContent = 'Impas';
                    }
                }
            };



           document.querySelectorAll('.money').forEach(el => {
                el.addEventListener('input', () => {
                    formatMoneyInput(el);
                    updatePreviewHarga();
                });
                el.addEventListener('blur', () => {
                    formatMoneyInput(el);
                    updatePreviewHarga();
                });
            });


            // submit (UI only for now)
            document.getElementById('formBarang')?.addEventListener('submit', (e) => {
                e.preventDefault(); // nanti kamu hapus kalau udah connect ke store()

                const requiredIds = ['kode_barang','nama_barang','satuan', 'harga_beli','harga_jual'];
                let ok = true;

                requiredIds.forEach(id => {
                    const el = document.getElementById(id);
                    if (!el || !String(el.value).trim()) {
                        ok = false;
                        el?.classList.add('border-red-300');
                        el?.classList.add('shake');
                        setTimeout(() => el?.classList.remove('shake'), 300);
                    } else {
                        el?.classList.remove('border-red-300');
                    }
                });

                if (!ok) {
                    showToast('Gagal', 'Lengkapi field yang wajib diisi.', 'error');
                    return;
                }

                showToast('Berhasil', 'Data barang siap disimpan (UI demo).', 'success');
            });

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

            if (btnSidebar) btnSidebar.addEventListener('click', openSidebar);
            if (btnCloseSidebar) btnCloseSidebar.addEventListener('click', closeSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);

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

            // Text Setelah Aksi
            // ===== UNSAVED CHANGES GUARD =====
            const form = document.getElementById('formBarang');
            const btnReset = document.getElementById('btnReset');
            const backLink = document.querySelector('a[href="{{ route('mengelola_barang') ?? '#' }}"]');
            let isDirty = false;

            // tandai form berubah
            const markDirty = () => { isDirty = true; };
            if (form) {
                form.querySelectorAll('input, select, textarea').forEach(el => {
                el.addEventListener('input', markDirty);
                el.addEventListener('change', markDirty);
                });
            }

            // ===== KONFIRMASI RESET =====
            btnReset?.addEventListener('click', () => {
                const ok = confirm('Konfirmasi reset? Semua perubahan akan hilang.');
                if (!ok) return;

                form.reset();

                // reset preview harga juga (punyamu sudah ada variabel previewBeli dll)
                if (typeof previewBeli !== 'undefined' && previewBeli) previewBeli.textContent = 'Rp 0';
                if (typeof previewJual !== 'undefined' && previewJual) previewJual.textContent = 'Rp 0';
                if (typeof previewSelisih !== 'undefined' && previewSelisih) previewSelisih.textContent = 'Rp 0';
                if (typeof selisihHint !== 'undefined' && selisihHint) selisihHint.textContent = '—';

                isDirty = false;
                if (typeof showToast === 'function') showToast('Reset', 'Form dikosongkan.', 'success');
            });

            // ===== KONFIRMASI KEMBALI =====
            // (kalau user klik tombol kembali di topbar)
            backLink?.addEventListener('click', (e) => {
                if (!isDirty) return; // kalau ga ada perubahan, langsung boleh

                const ok = confirm('Perubahan belum disimpan. Tetap mau keluar?');
                if (!ok) e.preventDefault();
            });

            // ===== BLOKIR TUTUP TAB / RELOAD kalau belum save =====
            window.addEventListener('beforeunload', (e) => {
                if (!isDirty) return;
                e.preventDefault();
                e.returnValue = ''; // wajib untuk sebagian browser
            });

            // ===== KONFIRMASI SIMPAN =====
            form?.addEventListener('submit', (e) => {
                // kalau kamu masih pakai e.preventDefault() untuk demo UI, biarkan seperti sekarang
                // tapi kita tambahin konfirmasi dulu sebelum lanjut logic submit kamu

                const ok = confirm('Simpan Perubahan?');
                if (!ok) {
                e.preventDefault();
                return;
                }

                // kalau user setuju, form dianggap "clean"
                isDirty = false;

                // NOTE:
                // - kalau kamu nanti sudah connect backend beneran, hapus e.preventDefault() di handler submit milikmu
                // - atau gabungkan jadi satu handler submit (biar ga dobel).
            });
        </script>

    </main>
</div>
</body>
</html>

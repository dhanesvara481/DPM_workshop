<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Barang Masuk</title>
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
                   data-nav
                   class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                    <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                        {{-- icon: home --}}
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
                            text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hiddenn">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            {{-- icon: box --}}
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
                            {{-- icon: arrow up-right --}}
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 7h7v7"/>
                            </svg>
                        </span>
                        Barang Keluar
                    </a>

                    <a href="/barang_masuk"
                       data-nav data-active="true"
                       class="nav-item is-active group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                            bg-white/12 text-white border border-white/10 relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            {{-- icon: arrow down-left --}}
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
                       class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
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
                       class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
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

                <div class="mt-3">
                    <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">MANAJEMEN</p>

                    <a href="#"
                       data-nav
                       class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
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
                       class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
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

                <div class="mt-4 pt-4 border-t border-white/10">
                    <a href="#"
                       class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition">
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
            </nav>
        </div>
    </aside>

    {{-- overlay (mobile) --}}
    <div id="overlay" class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-sm hidden md:hidden"></div>

    {{-- ================= MAIN ================= --}}
    <main id="main"
          class="flex-1 min-w-0 relative overflow-hidden md:ml-[280px] transition-[margin] duration-300 ease-out">

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
                        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Barang Masuk</h1>
                        <p class="text-xs text-slate-500">Catat stok masuk untuk menambah stok barang.</p>
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
            <div class="max-w-[1120px]">

                {{-- ALERTS --}}
                @if(session('success'))
                    <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-5 py-4">
                        <p class="text-sm font-semibold">Berhasil</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 text-red-800 px-5 py-4">
                        <p class="text-sm font-semibold">Gagal</p>
                        <ul class="mt-2 list-disc pl-5 text-sm space-y-1">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORM CARD --}}
                <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                            shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden mb-5">

                    <div class="px-6 py-5 border-b border-slate-200">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <h2 class="text-base font-semibold text-slate-900">Input Stok Masuk</h2>
                                <p class="mt-1 text-sm text-slate-500">
                                    Pilih kode barang, lalu masukkan jumlah stok masuk untuk menambah stok.
                                </p>
                            </div>

                            <span class="inline-flex self-start sm:self-auto items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs text-slate-600">
                                Form Barang Masuk
                            </span>
                        </div>
                    </div>

                    <form action="{{ url('/barang_masuk/store') }}" method="POST" class="px-6 py-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                            {{-- ROW 1 --}}
                            <div class="md:col-span-5">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Kode Barang</label>
                                <select name="barang_id"
                                        id="kodeBarangSelect"
                                        class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-white/90 text-sm
                                            focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                                    <option value="">-- Pilih Kode Barang --</option>
                                    @foreach(($barangs ?? []) as $b)
                                        <option value="{{ $b->id }}"
                                                data-kode="{{ $b->kode_barang ?? '' }}"
                                                data-nama="{{ $b->nama_barang ?? '' }}"
                                                data-satuan="{{ $b->satuan ?? '' }}"
                                                data-stok="{{ $b->stok ?? 0 }}"
                                                {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                            {{ $b->kode_barang ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-xs text-slate-500">Pilih kode barang yang sudah terdaftar</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Stok Saat Ini</label>
                                <input type="text"
                                       id="stokSaatIni"
                                       value=""
                                       readonly
                                       placeholder="-"
                                       class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-700
                                              focus:outline-none font-semibold tracking-tight text-center">
                                <p class="mt-2 text-xs text-slate-500">Otomatis</p>
                            </div>

                            <div class="md:col-span-5">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Tanggal</label>
                                <input type="date"
                                       name="tanggal"
                                       value="{{ old('tanggal', date('Y-m-d')) }}"
                                       class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-white/90 text-sm
                                              focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                            </div>

                            {{-- ROW 2 --}}
                            <div class="md:col-span-7">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Nama Barang</label>
                                <input type="text"
                                       id="namaBarang"
                                       value=""
                                       readonly
                                       placeholder="Akan terisi otomatis"
                                       class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-700
                                              focus:outline-none">
                            </div>

                            <div class="md:col-span-5">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Jumlah Stok Masuk</label>
                                <input type="number"
                                       min="1"
                                       name="qty_masuk"
                                       value="{{ old('qty_masuk') }}"
                                       placeholder="Masukkan jumlah masuk"
                                       class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-white/90 text-sm
                                              focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                                <p class="mt-2 text-xs text-slate-500">Stok akan bertambah sesuai jumlah masuk.</p>
                            </div>

                            {{-- ROW 3 --}}
                            <div class="md:col-span-7">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Satuan</label>
                                <input type="text"
                                       id="satuanBarang"
                                       value=""
                                       readonly
                                       placeholder="Akan terisi otomatis"
                                       class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-700
                                              focus:outline-none">
                            </div>
                        </div>

                        <div class="mt-5 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                            <div class="text-xs text-slate-500">
                                Pastikan barang sudah ada di menu <span class="font-semibold text-slate-700">Kelola Barang</span>.
                            </div>

                            <div class="flex gap-2">
                                <a href="/tampilan_barang"
                                   class="inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold
                                          border border-slate-200 bg-white hover:bg-slate-50 transition">
                                    Kembali
                                </a>

                                <button type="submit"
                                        class="btn-shine inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold
                                               bg-blue-950 text-white hover:bg-blue-900 transition
                                               shadow-[0_12px_24px_rgba(2,6,23,0.16)]">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5l7 7-7 7"/>
                                    </svg>
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- TABLE RIWAYAT BARANG MASUK (biarin sama dulu) --}}
                <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                            shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

                    <div class="px-6 py-5 border-b border-slate-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h2 class="text-base font-semibold text-slate-900">Riwayat Barang Masuk</h2>
                                <p class="text-sm text-slate-500">Daftar transaksi stok masuk terbaru.</p>
                            </div>

                            <div class="w-full sm:w-[380px]">
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                                        </svg>
                                    </span>

                                    <input id="searchMasuk"
                                           type="text"
                                           placeholder="Cari kode / nama barang..."
                                           class="w-full pl-9 pr-10 py-2.5 rounded-lg border border-slate-200 bg-white/90
                                                  text-sm placeholder:text-slate-400
                                                  focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm" id="tableMasuk">
                            <thead class="bg-slate-50/90 sticky top-0 z-10 backdrop-blur">
                            <tr class="text-left text-slate-600">
                                <th class="px-5 py-4 font-semibold w-[70px]">No</th>
                                <th class="px-5 py-4 font-semibold">Tanggal</th>
                                <th class="px-5 py-4 font-semibold">Kode</th>
                                <th class="px-5 py-4 font-semibold">Nama</th>
                                <th class="px-5 py-4 font-semibold text-right">Qty</th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-200">
                            @forelse(($barangMasuk ?? []) as $i => $m)
                                <tr class="row-lift hover:bg-slate-50/70 transition"
                                    data-row-text="{{ strtolower(($m->kode_barang ?? '').' '.($m->nama_barang ?? '')) }}">
                                    <td class="px-5 py-4 text-slate-600">{{ $i + 1 }}</td>
                                    <td class="px-5 py-4 text-slate-700">{{ $m->tanggal ?? '-' }}</td>
                                    <td class="px-5 py-4 font-semibold text-slate-900">{{ $m->kode_barang ?? '-' }}</td>
                                    <td class="px-5 py-4 text-slate-700">{{ $m->nama_barang ?? '-' }}</td>
                                    <td class="px-5 py-4 text-right font-semibold text-slate-900">{{ $m->qty_masuk ?? 0 }}</td>
                                </tr>
                            @empty
                                @for($r=1;$r<=3;$r++)
                                    <tr class="row-lift hover:bg-slate-50/70 transition">
                                        <td class="px-5 py-5 text-slate-400">{{ $r }}</td>
                                        <td class="px-5 py-5"><div class="h-4 w-28 rounded bg-slate-100"></div></td>
                                        <td class="px-5 py-5"><div class="h-4 w-20 rounded bg-slate-100"></div></td>
                                        <td class="px-5 py-5"><div class="h-4 w-52 rounded bg-slate-100"></div></td>
                                        <td class="px-5 py-5 text-right"><div class="h-4 w-16 ml-auto rounded bg-slate-100"></div></td>
                                    </tr>
                                @endfor
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
                        © DPW Workshop 2025
                    </div>
                </div>

            </div>
        </section>

        {{-- ===== CSS sama kayak barang_keluar kamu ===== --}}
        <style>
            @media (prefers-reduced-motion: reduce) {
                .animate-grid-scan, .row-lift, .btn-shine, .nav-item::before { animation: none !important; transition: none !important; }
            }
            @keyframes gridScan {
                0%   { background-position: 0 0, 0 0; opacity: 0.10; }
                40%  { opacity: 0.22; }
                60%  { opacity: 0.18; }
                100% { background-position: 220px 220px, -260px 260px; opacity: 0.10; }
            }
            .animate-grid-scan { animation: gridScan 8.5s ease-in-out infinite; }

            .nav-item::before{
                content:"";
                position:absolute;
                left:0; top:10px; bottom:10px;
                width:3px;
                background: linear-gradient(to bottom, rgba(255,255,255,.0), rgba(255,255,255,.75), rgba(255,255,255,.0));
                opacity:0;
                transform: translateX(-6px);
                transition: .25s ease;
                border-radius: 999px;
            }
            .nav-item.is-active::before{ opacity:.95; transform: translateX(0); }

            .row-lift{
                transform: translateY(0);
                transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease;
            }
            .row-lift:hover{
                transform: translateY(-1px);
                box-shadow: 0 10px 26px rgba(2,6,23,0.06);
            }

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

        {{-- ===== JS (sama + auto-fill fields) ===== --}}
        <script>
            // sidebar active indicator
            document.querySelectorAll('[data-nav]').forEach(a => {
                if (a.dataset.active === "true") a.classList.add('is-active');
                a.addEventListener('click', () => {
                    document.querySelectorAll('[data-nav].is-active').forEach(x => x.classList.remove('is-active'));
                    a.classList.add('is-active');
                });
            });

            // Mobile Sidebar
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

            document.querySelectorAll('#sidebar a[data-nav]').forEach(a => {
                a.addEventListener('click', () => {
                    if (window.innerWidth < 768) closeSidebar();
                });
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

            // Auto fill: nama, satuan, stok saat ini
            const kodeSelect = document.getElementById('kodeBarangSelect');
            const namaBarang = document.getElementById('namaBarang');
            const satuanBarang = document.getElementById('satuanBarang');
            const stokSaatIni = document.getElementById('stokSaatIni');

            const syncBarangFields = () => {
                if (!kodeSelect) return;
                const opt = kodeSelect.options[kodeSelect.selectedIndex];

                const nama = opt?.dataset?.nama || '';
                const satuan = opt?.dataset?.satuan || '';
                const stok = opt?.dataset?.stok || '0';
                const stokNum = parseInt(stok, 10) || 0;

                if (namaBarang) namaBarang.value = nama;
                if (satuanBarang) satuanBarang.value = satuan;
                if (stokSaatIni) stokSaatIni.value = String(stokNum);
            };

            if (kodeSelect) {
                kodeSelect.addEventListener('change', syncBarangFields);
                syncBarangFields();
            }

            // Search riwayat (biarin, sama konsepnya)
            const inputMasuk = document.getElementById('searchMasuk');
            if (inputMasuk) {
                const wrap = inputMasuk.parentElement;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = "clear-btn absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-700";
                btn.innerHTML = `
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                `;
                wrap.appendChild(btn);

                const sync = () => btn.classList.toggle('show', inputMasuk.value.trim().length > 0);
                sync();

                inputMasuk.addEventListener('input', () => {
                    sync();
                    const q = inputMasuk.value.trim().toLowerCase();
                    document.querySelectorAll('#tableMasuk tbody tr[data-row-text]').forEach(tr => {
                        tr.classList.toggle('hidden', q.length && !tr.dataset.rowText.includes(q));
                    });
                });

                btn.addEventListener('click', () => {
                    inputMasuk.value = "";
                    inputMasuk.focus();
                    sync();
                    document.querySelectorAll('#tableMasuk tbody tr').forEach(tr => tr.classList.remove('hidden'));
                });
            }
        </script>

    </main>
</div>
</body>
</html>

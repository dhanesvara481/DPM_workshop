<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stok Real-time</title>
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

        {{-- BACKGROUND --}}
        <div class="pointer-events-none absolute inset-0">
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
                        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Stok Real-time</h1>
                        <p class="text-xs text-slate-500">Tampilan stok terkini dari data barang (view-only untuk staf).</p>
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

                        <button type="button"
                                class="h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                            {{ now()->format('d M Y') }}
                        </button>

                        <a href="{{ route('tampilan_dashboard') ?? '#' }}"
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
        <section class="relative p-4 sm:p-6">
            <div class="max-w-[1120px]">

                @php
                    $totalItem = collect($barangs ?? [])->count();
                    $sumStok = collect($barangs ?? [])->sum(function($b){
                        return (int) ($b->stok ?? $b->stok_akhir ?? 0);
                    });
                @endphp

                {{-- SUMMARY --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
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
                        <p class="text-xs text-slate-500">Mode</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">View-only</p>
                    </div>
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
                                <th class="px-5 py-4 font-semibold w-[160px]">Status</th>
                                <th class="px-5 py-4 font-semibold text-right w-[160px]">Harga Jual</th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-200" id="stokTbody">
                            @forelse (($barangs ?? []) as $i => $b)
                                @php
                                    $stok = (int) ($b->stok ?? $b->stok_akhir ?? 0);
                                    $min  = (int) ($b->stok_min ?? 5); // default 5 kalau belum punya field stok_min
                                    if ($stok <= 0) { $label='Habis'; $cls='bg-rose-100 text-rose-700 border-rose-200'; }
                                    elseif ($stok <= $min) { $label='Menipis'; $cls='bg-amber-100 text-amber-800 border-amber-200'; }
                                    else { $label='Aman'; $cls='bg-emerald-100 text-emerald-700 border-emerald-200'; }
                                @endphp
                                <tr class="row-lift hover:bg-slate-50/70 transition">
                                    <td class="px-5 py-4 text-slate-600">{{ $i + 1 }}</td>
                                    <td class="px-5 py-4 font-semibold text-slate-900">{{ $b->kode_barang ?? '-' }}</td>
                                    <td class="px-5 py-4 text-slate-700">{{ $b->nama_barang ?? '-' }}</td>
                                    <td class="px-5 py-4 text-slate-700">{{ $b->satuan ?? '-' }}</td>
                                    <td class="px-5 py-4 text-right font-bold text-slate-900">
                                        {{ $stok }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold border {{ $cls }}">
                                            {{ $label }}
                                            <span class="text-[10px] opacity-70">(min {{ $min }})</span>
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right text-slate-700">
                                        {{ isset($b->harga_jual) ? 'Rp '.number_format($b->harga_jual,0,',','.') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                @for($r=1;$r<=4;$r++)
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

                    <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
                        © DPM Workshop 2025
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== CSS + Micro interactions ===== --}}
        <style>
            /* sidebar active indicator */
            .nav-item{ position: relative; overflow: hidden; }
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
            #sidebar { -webkit-overflow-scrolling: touch; }

            @media print {
                #sidebar, #overlay, #btnSidebar, #btnCloseSidebar, #btnPrint { display:none !important; }
                #main { margin-left: 0 !important; }
                body { background: #fff !important; }
            }
        </style>

        {{-- ===== JS ===== --}}
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

            // search filter (client-side)
            const input = document.getElementById('searchStok');
            const tbody = document.getElementById('stokTbody');
            if (input && tbody) {
                input.addEventListener('input', () => {
                    const q = input.value.trim().toLowerCase();
                    Array.from(tbody.querySelectorAll('tr')).forEach(tr => {
                        const text = tr.innerText.toLowerCase();
                        tr.style.display = text.includes(q) ? '' : 'none';
                    });
                });
            }

            // print
            document.getElementById('btnPrint')?.addEventListener('click', () => window.print());
        </script>

    </main>
</div>
</body>
</html>

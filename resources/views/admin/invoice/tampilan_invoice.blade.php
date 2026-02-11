<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice - DPM Workshop</title>
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
           data-nav
           class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                  text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
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
          <a href="/kelola_jadwal_kerja" class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </span>
            Kelola Jadwal Kerja
          </a>

          <a href="/tampilan_manajemen_staf" class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
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
          <a href="#" class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition">
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
    <header class="relative bg-white/75 backdrop-blur border-b border-slate-200 sticky top-0 z-20" data-animate>
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
            <h1 class="text-sm font-semibold tracking-tight text-slate-900">Invoice</h1>
            <p class="text-xs text-slate-500">Buat invoice Barang / Jasa</p>
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
        </div>

      </div>
    </header>

    {{-- CONTENT --}}
    <section class="relative p-4 sm:p-6">
      <div class="max-w-[1280px] mx-auto w-full space-y-6">

        {{-- Flash + error (Laravel) --}}
        @if(session('success'))
          <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
            {{ session('success') }}
          </div>
        @endif
        @if ($errors->any())
          <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900">
            <div class="font-semibold mb-1">Terjadi error:</div>
            <ul class="list-disc pl-5 space-y-1">
              @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form id="formInvoice"
              method="POST"
              action="{{ Route::has('invoice.store') ? route('invoice.store') : '#' }}"
              class="space-y-6" data-animate>
          @csrf

          <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
                      shadow-[0_16px_44px_rgba(2,6,23,0.08)] overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-200 flex items-center justify-between gap-3">
              <div class="min-w-0">
                <div class="text-sm font-semibold text-slate-900">INVOICE</div>
                <div class="text-xs text-slate-500 mt-0.5">Pilih kategori, input data, lalu simpan.</div>
              </div>

              <div class="flex items-center gap-2">
                <button type="button" id="btnReset"
                        class="h-10 px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                  Reset
                </button>
              </div>
            </div>

            <div class="p-5 sm:p-6 space-y-6">

              {{-- Top fields --}}
              <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="space-y-1">
                  <label class="text-xs font-semibold text-slate-700">Nama Pembuat Transaksi</label>

                  <input
                    value="{{ auth()->user()->name ?? auth()->user()->username ?? $userName ?? 'User' }}"
                    class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm outline-none"
                    readonly
                  />
                  <input type="hidden" name="user_id" value="{{ auth()->id() ?? '' }}">
                </div>

                <div class="space-y-1">
                  <label class="text-xs font-semibold text-slate-700">Tanggal</label>
                  <input type="date" name="tanggal_invoice"
                         value="{{ old('tanggal_invoice', now()->format('Y-m-d')) }}"
                         class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10" />
                </div>

                <div class="space-y-1">
                  <label class="text-xs font-semibold text-slate-700">Kategori Invoice</label>

                  <div class="grid grid-cols-2 rounded-xl border border-slate-200 bg-slate-50 p-1">
                    <button type="button" data-invtab="barang"
                            class="invtab h-10 rounded-lg text-sm font-semibold transition">
                      Barang
                    </button>
                    <button type="button" data-invtab="jasa"
                            class="invtab h-10 rounded-lg text-sm font-semibold transition">
                      Jasa
                    </button>
                  </div>

                  <input type="hidden" name="kategori" id="kategori" value="{{ old('kategori','barang') }}">
                  <p class="text-[11px] text-slate-500">Jasa: charge servis, plus barang yang memang ditagihkan (opsional).</p>
                </div>
              </div>

              {{-- Optional customer info --}}
              <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="space-y-1 lg:col-span-2">
                  <label class="text-xs font-semibold text-slate-700">Nama Pelanggan (opsional)</label>
                  <input name="nama_pelanggan" value="{{ old('nama_pelanggan') }}"
                         class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10"
                         placeholder="Contoh: Budi" />
                </div>
                <div class="space-y-1">
                  <label class="text-xs font-semibold text-slate-700">Kontak Pelanggan (opsional)</label>
                  <input name="kontak" value="{{ old('kontak') }}"
                         class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10"
                         placeholder="08xxxxxxxxxx" />
                </div>
              </div>

              {{-- ================= BARANG SECTION (kategori barang) ================= --}}
              <div id="sectionBarang" class="space-y-3">
                <div class="flex items-center justify-between gap-3">
                  <div>
                    <p class="text-sm font-semibold text-slate-900">Barang Yang Dibeli</p>
                    <p class="text-xs text-slate-500">Pilih dari barang yang tersedia (stok > 0).</p>
                  </div>
                  <button type="button" id="btnAddBarang"
                          class="inline-flex items-center gap-2 h-10 px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                    <span class="text-base">＋</span> Tambah
                  </button>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                  <div class="overflow-x-auto">
                    <table class="min-w-[1060px] w-full text-sm">
                      <thead class="bg-slate-50 border-b border-slate-200">
                      <tr class="text-xs text-slate-600">
                        <th class="px-4 py-3 text-left font-semibold w-[140px]">Kode</th>
                        <th class="px-4 py-3 text-left font-semibold">Barang</th>
                        <th class="px-4 py-3 text-left font-semibold w-[120px]">Satuan</th>
                        <th class="px-4 py-3 text-left font-semibold w-[160px]">Stok Digunakan</th>
                        <th class="px-4 py-3 text-left font-semibold w-[170px]">Harga Satuan</th>
                        <th class="px-4 py-3 text-left font-semibold w-[170px]">Jumlah</th>
                        <th class="px-3 py-3 text-right font-semibold w-[64px]"></th>
                      </tr>
                      </thead>
                      <tbody id="tbodyBarang"></tbody>
                    </table>
                  </div>
                </div>
              </div>

              {{-- ================= JASA SECTION (kategori jasa) ================= --}}
              <div id="sectionJasa" class="space-y-4 hidden">
                <div>
                  <p class="text-sm font-semibold text-slate-900">Detail Pelayanan / Service</p>
                  <p class="text-xs text-slate-500">Input biaya service, lalu (opsional) barang yang ditagihkan.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                  <div class="space-y-1 lg:col-span-2">
                    <label class="text-xs font-semibold text-slate-700">Nama Jasa / Service</label>
                    <input name="jasa_nama" value="{{ old('jasa_nama') }}"
                           class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10"
                           placeholder="Contoh: Service fan rusak" />
                  </div>

                  <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-700">Biaya Jasa</label>
                    <input type="number" min="0" step="1" name="jasa_biaya" id="jasaBiaya"
                           value="{{ old('jasa_biaya') }}"
                           class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10"
                           placeholder="0" />
                  </div>
                </div>

                <div class="space-y-3">
                  <div class="flex items-center justify-between gap-3">
                    <div>
                      <p class="text-sm font-semibold text-slate-900">Barang Yang Ditagihkan (Opsional)</p>
                      <p class="text-xs text-slate-500">Kalau tidak ditagihkan, tidak perlu diinput.</p>
                    </div>
                    <button type="button" id="btnAddJasaBarang"
                            class="inline-flex items-center gap-2 h-10 px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                      <span class="text-base">＋</span> Tambah
                    </button>
                  </div>

                  <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                    <div class="overflow-x-auto">
                      <table class="min-w-[1060px] w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-xs text-slate-600">
                          <th class="px-4 py-3 text-left font-semibold w-[140px]">Kode</th>
                          <th class="px-4 py-3 text-left font-semibold">Barang</th>
                          <th class="px-4 py-3 text-left font-semibold w-[120px]">Satuan</th>
                          <th class="px-4 py-3 text-left font-semibold w-[160px]">Stok Digunakan</th>
                          <th class="px-4 py-3 text-left font-semibold w-[170px]">Harga Satuan</th>
                          <th class="px-4 py-3 text-left font-semibold w-[170px]">Jumlah</th>
                          <th class="px-3 py-3 text-right font-semibold w-[64px]"></th>
                        </tr>
                        </thead>
                        <tbody id="tbodyJasaBarang"></tbody>
                      </table>
                    </div>
                  </div>

                  <p class="text-[11px] text-slate-500">
                    *Barang yang stok 0 otomatis tidak muncul di pilihan.
                  </p>
                </div>
              </div>

              {{-- Deskripsi + Total --}}
              <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2 space-y-1">
                  <label class="text-xs font-semibold text-slate-700">Deskripsi</label>
                  <textarea name="deskripsi" rows="6"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10"
                            placeholder="Catatan tambahan untuk invoice...">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="space-y-3">
                  <div class="rounded-2xl border border-slate-200 bg-white p-4">
                    <div class="flex items-center justify-between text-sm">
                      <span class="text-slate-600">Subtotal Barang</span>
                      <span id="sumBarang" class="font-semibold text-slate-900">0</span>
                    </div>
                    <div class="flex items-center justify-between text-sm mt-2">
                      <span class="text-slate-600">Biaya Jasa</span>
                      <span id="sumJasa" class="font-semibold text-slate-900">0</span>
                    </div>
                    <div class="flex items-center justify-between text-sm mt-2">
                      <span class="text-slate-600">Subtotal Keseluruhan</span>
                      <span id="sumSubtotal" class="font-semibold text-slate-900">0</span>
                    </div>

                    <div class="border-t border-slate-200 my-3"></div>

                    <div class="grid grid-cols-2 gap-2">
                      <div class="space-y-1">
                        <label class="text-[11px] font-semibold text-slate-700">Diskon (Rp)</label>
                        <input type="number" min="0" step="1" id="diskon"
                               class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10"
                               placeholder="0" />
                      </div>
                      <div class="space-y-1">
                        <label class="text-[11px] font-semibold text-slate-700">Pajak (%)</label>
                        <input type="number" min="0" step="0.01" id="pajak"
                               class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10"
                               placeholder="0" />
                      </div>
                    </div>

                    <div class="border-t border-slate-200 my-3"></div>

                    <div class="flex items-center justify-between">
                      <span class="text-sm font-semibold text-slate-900">TOTAL</span>
                      <span id="sumGrand" class="text-xl font-bold text-slate-900">0</span>
                    </div>

                    {{-- hidden totals for backend --}}
                    <input type="hidden" name="subtotal_barang" id="subtotal_barang" value="0">
                    <input type="hidden" name="subtotal_jasa" id="subtotal_jasa" value="0">
                    <input type="hidden" name="subtotal" id="subtotal" value="0">
                    <input type="hidden" name="grand_total" id="grand_total" value="0">
                  </div>

                  <div class="grid grid-cols-2 gap-2">
                    <a href="/tampilan_dashboard" id="btnBack"
                       class="h-11 inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                      Batal
                    </a>
                    <button type="submit" id="btnSave"
                            class="h-11 inline-flex items-center justify-center rounded-xl border border-slate-900 bg-slate-900 text-white hover:bg-slate-800 transition text-sm font-semibold">
                      Simpan
                    </button>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </form>

        <div data-animate class="text-xs text-slate-400 pt-2">
          © DPM Workshop 2025
        </div>
      </div>
    </section>

    {{-- Toast (notifikasi) --}}
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

    {{-- Inject barang list (stok > 0) --}}
    <script>
      window.BARANGS = @json($barangs ?? []);
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
      @media (prefers-reduced-motion: reduce){
        [data-animate]{ opacity: 1 !important; transform: none !important; filter: none !important; transition:none !important; }
      }

      /* tabs */
      .invtab{ background: transparent; color: rgba(15,23,42,.75); }
      .invtab.is-active{
        background: #0f172a;
        color: #fff;
        box-shadow: 0 10px 26px rgba(2,6,23,.18);
      }

      /* invalid shake */
      @keyframes shake {
        0% { transform: translateX(0) }
        25% { transform: translateX(-6px) }
        50% { transform: translateX(6px) }
        75% { transform: translateX(-4px) }
        100% { transform: translateX(0) }
      }
      .shake { animation: shake .28s ease; }
    </style>

    <script>
      // ================= ANIMASI MASUK =================
      (function(){
        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduce) return;

        const items = Array.from(document.querySelectorAll('[data-animate]'));
        const baseDelay = 60;
        const startDelay = 80;

        items.forEach((el, i) => {
          el.style.transitionDelay = (startDelay + (i * baseDelay)) + 'ms';
        });

        requestAnimationFrame(() => {
          items.forEach(el => el.classList.add('in'));
        });
      })();
    </script>

    <script>
      // ================= SIDEBAR ACTIVE + TOGGLE =================
      document.querySelectorAll('[data-nav]').forEach(a => {
        if (a.getAttribute('href') === '/tampilan_invoice') a.classList.add('is-active');
        if (a.dataset.active === "true") a.classList.add('is-active');
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

    <script>
      // ================= TOAST =================
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
    </script>

    <script>
      // ================= INVOICE LOGIC + BARRIER + SELECT BARANG =================
      const fmtID = (n) => (isFinite(n) ? n : 0).toLocaleString('id-ID');
      const barangList = Array.isArray(window.BARANGS) ? window.BARANGS : [];

      const form = document.getElementById('formInvoice');
      const kategoriEl = document.getElementById('kategori');
      const tabs = Array.from(document.querySelectorAll('.invtab'));
      const sectionBarang = document.getElementById('sectionBarang');
      const sectionJasa = document.getElementById('sectionJasa');

      const tbodyBarang = document.getElementById('tbodyBarang');
      const tbodyJasaBarang = document.getElementById('tbodyJasaBarang');

      const jasaBiaya = document.getElementById('jasaBiaya');
      const diskon = document.getElementById('diskon');
      const pajak = document.getElementById('pajak');

      const sumBarang = document.getElementById('sumBarang');
      const sumJasa = document.getElementById('sumJasa');
      const sumSubtotal = document.getElementById('sumSubtotal');
      const sumGrand = document.getElementById('sumGrand');

      const h_sub_barang = document.getElementById('subtotal_barang');
      const h_sub_jasa = document.getElementById('subtotal_jasa');
      const h_subtotal = document.getElementById('subtotal');
      const h_grand = document.getElementById('grand_total');

      let isDirty = false;
      const markDirty = () => { isDirty = true; };

      function barangOptionsHTML(){
        if (!barangList.length) return '';
        return barangList.map(b => `
          <option value="${b.id}"
            data-kode="${b.kode_barang}"
            data-nama="${b.nama_barang}"
            data-satuan="${b.satuan}"
            data-harga="${b.harga_jual}"
            data-stok="${b.stok}">
            ${b.nama_barang} (stok: ${b.stok})
          </option>
        `).join('');
      }

      function setKategori(kat){
        kategoriEl.value = kat;
        tabs.forEach(t => t.classList.toggle('is-active', t.dataset.invtab === kat));

        if (kat === 'barang') {
          sectionBarang.classList.remove('hidden');
          sectionJasa.classList.add('hidden');
        } else {
          sectionBarang.classList.add('hidden');
          sectionJasa.classList.remove('hidden');
        }

        recalc();
      }

      tabs.forEach(t => t.addEventListener('click', () => {
        setKategori(t.dataset.invtab);
        markDirty();
      }));

      function rowHTML(idx, prefix){
        return `
        <tr class="border-b border-slate-200 last:border-0">
            <td class="px-4 py-3">
            <input name="${prefix}[${idx}][kode]" data-kode
                class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm outline-none" readonly />
            </td>

            <td class="px-4 py-3">
            <select name="${prefix}[${idx}][barang_id]" data-barang-select required
                class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10">
                <option value="" selected disabled>Pilih barang</option>
                ${barangOptionsHTML()}
            </select>
            </td>

            <td class="px-4 py-3">
            <input name="${prefix}[${idx}][satuan]" data-satuan
                class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm outline-none" readonly />
            </td>

            <td class="px-4 py-3">
            <!-- Stok Digunakan -->
            <input type="number" min="1" step="1" data-qty required disabled
                name="${prefix}[${idx}][qty]"
                class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10"
                placeholder="0"/>
            <p class="mt-1 text-[11px] text-slate-500 hidden" data-stock-label>
                Tersedia: <span data-max>0</span>
            </p>
            </td>

            <td class="px-4 py-3">
            <input type="number" min="0" step="1" data-price
                name="${prefix}[${idx}][harga]"
                class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm outline-none"
                readonly />
            </td>

            <td class="px-4 py-3">
            <div class="h-10 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm flex items-center justify-between">
                <span class="text-slate-500">Rp</span>
                <span data-line-total class="font-semibold text-slate-900">0</span>
            </div>
            <input type="hidden" data-line-hidden name="${prefix}[${idx}][total]" value="0" />
            </td>

            <td class="px-3 py-3 text-right">
            <button type="button" data-remove
                class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-rose-50 hover:border-rose-200 transition grid place-items-center"
                aria-label="Hapus">
                <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.9 13a2 2 0 01-2 2H8a2 2 0 01-2-2L5 7"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16"/>
                </svg>
            </button>
            </td>
        </tr>`;
        }


      function recalcRow(tr){
        const qty = Number(tr.querySelector('[data-qty]')?.value || 0);
        const price = Number(tr.querySelector('[data-price]')?.value || 0);
        const total = Math.max(0, qty) * Math.max(0, price);

        tr.querySelector('[data-line-total]').textContent = fmtID(total);
        tr.querySelector('[data-line-hidden]').value = String(total);
      }

      function handleTableEvents(tbody){
        // pilih barang
        tbody.addEventListener('change', (e) => {
            const sel = e.target.closest('[data-barang-select]');
            if (!sel) return;

            const tr = sel.closest('tr');
            const opt = sel.options[sel.selectedIndex];

            const stok = Number(opt?.dataset?.stok || 0);
            const harga = Number(opt?.dataset?.harga || 0);

            tr.querySelector('[data-kode]').value = opt?.dataset?.kode || '';
            tr.querySelector('[data-satuan]').value = opt?.dataset?.satuan || '';

            const priceEl = tr.querySelector('[data-price]');
            if (priceEl) priceEl.value = String(harga);

            const qtyEl = tr.querySelector('[data-qty]');
            const maxEl = tr.querySelector('[data-max]');
            const stockLabel = tr.querySelector('[data-stock-label]');

            // update label stok tersedia
            if (maxEl) maxEl.textContent = String(stok);
            if (stockLabel) stockLabel.classList.remove('hidden');

            if (qtyEl) {
            // enable qty setelah barang dipilih
            qtyEl.disabled = false;
            qtyEl.max = String(stok);

            // reset qty saat ganti barang (biar gak kebawa)
            qtyEl.value = '';

            // kalau stok 0 (harusnya ga muncul), kunci lagi
            if (stok <= 0) {
                qtyEl.disabled = true;
                qtyEl.value = '';
            }
            }

            markDirty();
            recalcRow(tr);
            recalc();
        });

        // input qty
        tbody.addEventListener('input', (e) => {
            const qtyInput = e.target.closest('[data-qty]');
            if (!qtyInput) return;

            const tr = qtyInput.closest('tr');
            if (!tr) return;

            // kalau masih disabled, abaikan
            if (qtyInput.disabled) return;

            const max = Number(qtyInput.max || 0);

            let qty = Number(qtyInput.value || 0);
            if (max > 0 && qty > max) qty = max;
            if (qty < 0) qty = 0;

            qtyInput.value = qty ? String(qty) : '';

            recalcRow(tr);
            markDirty();
            recalc();
        });

        // remove row
        tbody.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-remove]');
            if (!btn) return;
            btn.closest('tr')?.remove();
            markDirty();
            recalc();
        });
        }


      let barangIdx = 0;
      let jasaBarangIdx = 0;

      function addBarangRow(){
        tbodyBarang.insertAdjacentHTML('beforeend', rowHTML(barangIdx++, 'barang'));
        markDirty();
      }
      function addJasaBarangRow(){
        tbodyJasaBarang.insertAdjacentHTML('beforeend', rowHTML(jasaBarangIdx++, 'jasa_barang'));
        markDirty();
      }

      document.getElementById('btnAddBarang')?.addEventListener('click', addBarangRow);
      document.getElementById('btnAddJasaBarang')?.addEventListener('click', addJasaBarangRow);

      handleTableEvents(tbodyBarang);
      handleTableEvents(tbodyJasaBarang);

      ;[jasaBiaya, diskon, pajak].forEach(el => el?.addEventListener('input', () => { markDirty(); recalc(); }));

      function sumTable(tbody){
        let sum = 0;
        tbody.querySelectorAll('[data-line-hidden]').forEach(h => sum += Number(h.value || 0));
        return sum;
      }

      function recalc(){
        const kategori = kategoriEl.value;

        const barangSum = sumTable(tbodyBarang);
        const jasaBarangSum = sumTable(tbodyJasaBarang);

        const jasa = Number(jasaBiaya?.value || 0);

        const subtotalBarangVal = (kategori === 'barang') ? barangSum : jasaBarangSum;
        const jasaVal = (kategori === 'jasa') ? Math.max(0, jasa) : 0;

        const subtotalVal = subtotalBarangVal + jasaVal;

        const diskonVal = Math.max(0, Number(diskon?.value || 0));
        const pajakPct = Math.max(0, Number(pajak?.value || 0));

        const afterDisc = Math.max(0, subtotalVal - diskonVal);
        const pajakVal = Math.round(afterDisc * (pajakPct / 100));
        const grand = afterDisc + pajakVal;

        sumBarang.textContent = fmtID(subtotalBarangVal);
        sumJasa.textContent = fmtID(jasaVal);
        sumSubtotal.textContent = fmtID(subtotalVal);
        sumGrand.textContent = fmtID(grand);

        h_sub_barang.value = String(subtotalBarangVal);
        h_sub_jasa.value = String(jasaVal);
        h_subtotal.value = String(subtotalVal);
        h_grand.value = String(grand);
      }

      // ===== INIT (pakai old kategori kalau ada) =====
      setKategori(kategoriEl.value || 'barang');

      // initial row sesuai kategori
      if ((kategoriEl.value || 'barang') === 'barang') addBarangRow();
      else addJasaBarangRow();

      recalc();

      // ===== RESET (confirm + toast) =====
      document.getElementById('btnReset')?.addEventListener('click', () => {
        const ok = confirm('Konfirmasi reset? Semua perubahan akan hilang.');
        if (!ok) return;

        form.reset();

        tbodyBarang.innerHTML = '';
        tbodyJasaBarang.innerHTML = '';
        barangIdx = 0;
        jasaBarangIdx = 0;

        setKategori('barang');
        addBarangRow();

        isDirty = false;
        recalc();
        showToast('Reset', 'Form dikosongkan.', 'success');
      });

      // ===== KONFIRMASI BACK (kalau ada perubahan) =====
      document.getElementById('btnBack')?.addEventListener('click', (e) => {
        if (!isDirty) return;
        const ok = confirm('Perubahan belum disimpan. Tetap mau keluar?');
        if (!ok) e.preventDefault();
      });

      // ===== BLOKIR TUTUP TAB / RELOAD =====
      window.addEventListener('beforeunload', (e) => {
        if (!isDirty) return;
        e.preventDefault();
        e.returnValue = '';
      });

      // ===== SUBMIT: validasi ringan + konfirmasi =====
      form?.addEventListener('submit', (e) => {
        const okConfirm = confirm('Simpan Perubahan?');
        if (!okConfirm) {
          e.preventDefault();
          return;
        }

        const kategori = kategoriEl.value;
        const hasBarang = tbodyBarang.querySelectorAll('tr').length > 0;
        const hasJasaBarang = tbodyJasaBarang.querySelectorAll('tr').length > 0;

        if (kategori === 'barang' && !hasBarang) {
          e.preventDefault();
          showToast('Gagal', 'Tambahkan minimal 1 item barang.', 'error');
          return;
        }

        if (kategori === 'jasa') {
          const jasaVal = Number(jasaBiaya?.value || 0);
          if (jasaVal <= 0) {
            e.preventDefault();
            jasaBiaya?.classList.add('border-red-300','shake');
            setTimeout(() => jasaBiaya?.classList.remove('shake'), 300);
            showToast('Gagal', 'Biaya jasa wajib diisi untuk kategori Jasa.', 'error');
            return;
          }
        }

        // kalau lolos, anggap clean
        isDirty = false;
      });
    </script>

  </main>
</div>
</body>
</html>

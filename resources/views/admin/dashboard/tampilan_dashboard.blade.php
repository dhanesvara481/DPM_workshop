@extends('admin.layout.app')

@section('title', 'DPM Workshop - Admin')

@push('head')
  {{-- Chart.js --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')

@php
  /**
   * =========================
   * DUMMY DATA (VIEW ONLY)
   * =========================
   * Nanti kalau sudah pakai controller/DB:
   * - ganti isi variabel ini dari controller
   */

  // Ringkasan stok
  $stockTotalItem = $stockTotalItem ?? 128;  // Total item (total row barang / total SKU)
  $stockLow       = $stockLow       ?? 4;    // Stok menipis
  $stockOut       = $stockOut       ?? 2;    // Barang habis

  // Ringkasan transaksi (ADMIN lihat semua transaksi sistem)
  $txTodayAll     = $txTodayAll     ?? 1;    // Total transaksi HARI INI (semua staff + admin)
  $txTotalAll     = $txTotalAll     ?? 12;   // Total transaksi keseluruhan sistem
@endphp

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
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8 4-8-4"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10l8 4 8-4V7"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v10"/>
            </svg>
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
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 14h6m-6-4h6m-7 10l-2 1V4l2 1 2-1 2 1 2-1 2 1v17l-2-1-2 1-2-1-2 1z"/>
            </svg>
          </div>
        </div>
        <div class="mt-4 text-sm text-slate-200 font-semibold group-hover:underline">
          Buat sekarang →
        </div>
      </a>
    </div>

    {{-- =========================
         RINGKASAN (STOK + TRANSAKSI)
         ========================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" data-animate-group>

      {{-- RINGKASAN STOK --}}
      <div data-animate
           class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
                  shadow-[0_16px_44px_rgba(2,6,23,0.08)] p-6">
        <div class="flex items-start justify-between gap-3">
          <div class="min-w-0">
            <p class="text-xl font-semibold text-slate-900">Ringkasan Stok</p>
            <p class="text-sm text-slate-500 mt-1">Gambaran cepat kondisi barang</p>
          </div>

          <div class="h-12 w-12 rounded-2xl bg-slate-900 text-white grid place-items-center border border-slate-900 shrink-0">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M20 13V7a2 2 0 00-1-1.732l-6-3.464a2 2 0 00-2 0L5 5.268A2 2 0 004 7v6a2 2 0 001 1.732l6 3.464a2 2 0 002 0l6-3.464A2 2 0 0020 13z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 22V12"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8 5-8-5"/>
            </svg>
          </div>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <div class="text-sm text-slate-500">Total Item</div>
            <div class="text-4xl font-extrabold text-slate-900 mt-2 leading-none">
              {{ $stockTotalItem }}
            </div>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <div class="text-sm text-slate-500">Stok Menipis</div>
            <div class="text-4xl font-extrabold text-amber-700 mt-2 leading-none">
              {{ $stockLow }}
            </div>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <div class="text-sm text-slate-500">Barang Habis</div>
            <div class="text-4xl font-extrabold text-rose-700 mt-2 leading-none">
              {{ $stockOut }}
            </div>
          </div>
        </div>

        <div class="mt-6 text-sm text-slate-500">
          Tips: Harap beritahu admin jika ada stok yang menipis atau habis!
        </div>
      </div>

      {{-- RINGKASAN TRANSAKSI (ADMIN: semua transaksi sistem) --}}
      <div data-animate
           class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
                  shadow-[0_16px_44px_rgba(2,6,23,0.08)] p-6">
        <div class="flex items-start justify-between gap-3">
          <div class="min-w-0">
            <p class="text-xl font-semibold text-slate-900">Ringkasan Transaksi</p>
            <p class="text-sm text-slate-500 mt-1">Statistik transaksi yang terjadi di sistem</p>
          </div>

          <div class="h-12 w-12 rounded-2xl bg-slate-900 text-white grid place-items-center border border-slate-900 shrink-0">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M7 14l3-3 3 3 5-6"/>
            </svg>
          </div>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <div class="text-sm text-slate-500">Transaksi Terjadi Hari Ini</div>
            <div class="text-4xl font-extrabold text-slate-900 mt-2 leading-none">
              {{ $txTodayAll }}
            </div>
            <div class="text-sm text-slate-500 mt-3">
              {{ now()->format('d M Y') }}
            </div>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <div class="text-sm text-slate-500">Total Transaksi</div>
            <div class="text-4xl font-extrabold text-slate-900 mt-2 leading-none">
              {{ $txTotalAll }}
            </div>
            <div class="text-sm text-slate-500 mt-3">
              Sejak sistem dibuat
            </div>
          </div>
        </div>

        <div class="mt-6">
          <a href="/riwayat_transaksi"
             class="inline-flex items-center justify-center h-11 px-5 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
            Lihat riwayat →
          </a>
        </div>
      </div>

    </div>

    {{-- CHARTS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" data-animate-group>

      {{-- MASUK --}}
      <div data-animate
           class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
                  shadow-[0_16px_44px_rgba(2,6,23,0.08)] p-5">

        <div class="flex items-center justify-between mb-3 gap-3">
          <div class="min-w-0">
            <p class="font-semibold text-slate-900">Barang Masuk</p>
            <p id="masukRangeLabel" class="text-xs text-slate-500">6 bulan terakhir</p>
          </div>

          <div class="flex flex-wrap items-center justify-end gap-2">
            <div class="flex gap-1">
              <button type="button" class="chart-btn chart-range" data-range="6m">6 Bulan</button>
              <button type="button" class="chart-btn chart-range" data-range="12m">12 Bulan</button>
              <button type="button" class="chart-btn chart-range" data-range="year">Tahun Ini</button>
            </div>

            <div class="w-px h-6 bg-slate-200 hidden sm:block"></div>

            <div class="flex gap-1">
              <button type="button" class="chart-btn" onclick="setMasuk('line')">Line</button>
              <button type="button" class="chart-btn" onclick="setMasuk('bar')">Bar</button>
            </div>
          </div>
        </div>

        <canvas id="chartMasuk" height="120"></canvas>
      </div>

      {{-- KELUAR --}}
      <div data-animate
           class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
                  shadow-[0_16px_44px_rgba(2,6,23,0.08)] p-5">

        <div class="flex items-center justify-between mb-3 gap-3">
          <div class="min-w-0">
            <p class="font-semibold text-slate-900">Barang Keluar</p>
            <p id="keluarRangeLabel" class="text-xs text-slate-500">6 bulan terakhir</p>
          </div>

          <div class="flex flex-wrap items-center justify-end gap-2">
            <div class="flex gap-1">
              <button type="button" class="chart-btn chart-range" data-range="6m">6 Bulan</button>
              <button type="button" class="chart-btn chart-range" data-range="12m">12 Bulan</button>
              <button type="button" class="chart-btn chart-range" data-range="year">Tahun Ini</button>
            </div>

            <div class="w-px h-6 bg-slate-200 hidden sm:block"></div>

            <div class="flex gap-1">
              <button type="button" class="chart-btn" onclick="setKeluar('bar')">Bar</button>
              <button type="button" class="chart-btn" onclick="setKeluar('line')">Line</button>
            </div>
          </div>
        </div>

        <canvas id="chartKeluar" height="120"></canvas>
      </div>

    </div>

    {{-- ================== JADWAL KERJA ================== --}}
    @php
      $MAX_EVENTS_PER_DAY = $MAX_EVENTS_PER_DAY ?? 4;

      $events = $events ?? [
        now()->format('Y-m-d') => [
          ['id'=>101,'title'=>'Shift Pagi - Asep','status'=>'aktif','time'=>'08:00 - 16:00','desc'=>'Servis rutin / tune up'],
          ['id'=>102,'title'=>'Catatan: Sparepart datang','status'=>'catatan','time'=>'10:30','desc'=>'Cek gudang + follow up supplier'],
        ],
        now()->addDay()->format('Y-m-d') => [
          ['id'=>103,'title'=>'Tutup (Libur)','status'=>'tutup','time'=>'-','desc'=>'Hari libur operasional'],
        ],
      ];

      $statusFor = function($date) use ($events) {
        $ev = $events[$date] ?? [];

        foreach ($ev as $e) if (strtolower($e['status'] ?? '') === 'tutup')
          return ['label'=>'Tutup', 'class'=>'bg-rose-100 text-rose-700'];

        foreach ($ev as $e) if (strtolower($e['status'] ?? '') === 'catatan')
          return ['label'=>'Catatan', 'class'=>'bg-amber-100 text-amber-800'];

        if (!empty($ev)) return ['label'=>'Aktif', 'class'=>'bg-emerald-100 text-emerald-700'];

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
          <div class="text-xs text-slate-500 mt-0.5">Klik tanggal untuk detail.</div>
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
            <div class="text-xs text-slate-500 mt-1">Berikut jadwal yang ada di DPM Workshop.</div>
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

{{-- MODAL DETAIL (DASHBOARD - SAMA KAYAK KELOLA) --}}
<div id="dashDetailModal" class="fixed inset-0 z-[90] hidden overflow-y-auto">
  <div id="dashDetailOverlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

  <div class="relative min-h-full w-full flex items-center justify-center p-3 sm:p-6">
    <div class="w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-[0_30px_90px_rgba(2,6,23,0.30)]
                overflow-hidden flex flex-col h-[92vh] sm:h-[86vh]">

      <div class="px-5 py-4 border-b border-slate-200 flex items-start justify-between gap-3 bg-white shrink-0">
        <div class="min-w-0">
          <div class="text-sm font-semibold text-slate-900">Detail Jadwal</div>
          <div id="dashModalDate" class="text-xs text-slate-500 mt-0.5">—</div>
        </div>
        <button id="dashBtnCloseModal" type="button"
                class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                aria-label="Tutup">
          <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <div class="p-5 flex-1 min-h-0 overflow-hidden flex flex-col gap-4">
        <div id="dashModalMeta" class="shrink-0"></div>

        <div class="rounded-xl border border-slate-200 bg-slate-50 flex flex-col min-h-0 flex-1">
          <div class="px-4 py-3 border-b border-slate-200 bg-white rounded-t-xl shrink-0">
            <div class="text-sm font-semibold text-slate-900">Daftar Jadwal</div>
            <div class="text-xs text-slate-500">Scroll kalau jadwalnya panjang.</div>
          </div>

          <div id="dashModalListScroll" class="flex-1 min-h-0 overflow-y-auto overscroll-contain p-3 sm:p-4">
            <div id="dashModalEvents" class="space-y-2"></div>

            <div id="dashModalEmpty" class="hidden rounded-xl border border-slate-200 bg-white p-4 text-sm text-slate-600">
              Belum ada jadwal di tanggal ini.
            </div>
          </div>
        </div>

        <div class="pt-3 border-t border-slate-200 flex flex-col sm:flex-row gap-2 sm:justify-end shrink-0">
          <a href="/kelola_jadwal_kerja"
             class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                    bg-slate-900 text-white hover:bg-slate-800 transition">
            Buka Kelola Jadwal
          </a>

          <button id="dashModalTutup" type="button"
                  class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                         border border-slate-200 bg-white hover:bg-slate-50 transition">
            Tutup
          </button>
        </div>

        <div id="dashModalHint" class="text-[11px] text-slate-500 shrink-0"></div>
      </div>
    </div>
  </div>
</div>

{{-- data utk JS --}}
<script>
  window.DASH_EVENTS = @json($events);
  window.DASH_MAX_PER_DAY = @json($MAX_EVENTS_PER_DAY);
</script>

{{-- STYLE --}}
<style>
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

  .chart-btn{
    font-size:11px;
    padding:4px 10px;
    border-radius:999px;
    border:1px solid rgba(15,23,42,.15);
    background:#fff;
    transition:.15s;
  }
  .chart-btn:hover{ background:#f1f5f9; }
  .chart-btn.is-active{
    border-color: rgba(2,6,23,.35);
    background: rgba(2,6,23,.04);
    font-weight: 800;
  }

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
  .day-top .right-slot{ min-width: 86px; display:flex; justify-content:flex-end; gap:8px; }

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

  .badge-full{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-size: 10px;
    font-weight: 800;
    padding: 4px 8px;
    border-radius: 999px;
    border: 1px solid rgba(244,63,94,0.25);
    background: rgba(244,63,94,0.10);
    color: rgba(190,18,60,0.95);
    white-space: nowrap;
  }

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

  #dashModalListScroll { scrollbar-gutter: stable; }
</style>

{{-- SCRIPT --}}
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

  // ================= CHARTS =================
  let chartMasuk, chartKeluar;
  let currentRange = '6m';

  const masukRangeLabel = document.getElementById('masukRangeLabel');
  const keluarRangeLabel = document.getElementById('keluarRangeLabel');

  const chartData = {
    '6m': {
      label: '6 bulan terakhir',
      labels: ['Jan','Feb','Mar','Apr','Mei','Jun'],
      masuk:  [40,55,30,70,60,90],
      keluar: [30,20,45,50,40,60]
    },
    '12m': {
      label: '12 bulan terakhir',
      labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
      masuk:  [40,55,30,70,60,90,75,65,80,95,85,100],
      keluar: [30,20,45,50,40,60,55,35,60,70,65,75]
    },
    'year': {
      label: 'Tahun ini (per kuartal)',
      labels: ['Q1','Q2','Q3','Q4'],
      masuk:  [125,180,210,240],
      keluar: [95,130,170,190]
    }
  };

  function renderMasuk(type='line'){
    chartMasuk?.destroy();
    const d = chartData[currentRange];

    chartMasuk = new Chart(document.getElementById('chartMasuk'), {
      type,
      data: {
        labels: d.labels,
        datasets: [{
          label: 'Barang Masuk',
          data: d.masuk,
          borderColor: '#10b981',
          backgroundColor: type === 'line' ? 'rgba(16,185,129,0.18)' : 'rgba(16,185,129,0.75)',
          borderWidth: 2,
          fill: type === 'line',
          tension: .4
        }]
      },
      options:{
        responsive:true,
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
    const d = chartData[currentRange];

    chartKeluar = new Chart(document.getElementById('chartKeluar'), {
      type,
      data: {
        labels: d.labels,
        datasets: [{
          label: 'Barang Keluar',
          data: d.keluar,
          borderColor: '#f43f5e',
          backgroundColor: type === 'line' ? 'rgba(244,63,94,0.18)' : 'rgba(244,63,94,0.78)',
          borderWidth: 2,
          fill: type === 'line',
          tension: .4
        }]
      },
      options:{
        responsive:true,
        plugins:{ legend:{ display:false } },
        scales:{
          x:{ grid:{ display:false } },
          y:{ grid:{ color:'rgba(2,6,23,0.06)' } }
        }
      }
    });
  }

  function setMasuk(type){ renderMasuk(type); }
  function setKeluar(type){ renderKeluar(type); }

  function setRange(range){
    currentRange = range;

    const label = chartData[currentRange]?.label || '';
    if (masukRangeLabel) masukRangeLabel.textContent = label;
    if (keluarRangeLabel) keluarRangeLabel.textContent = label;

    renderMasuk(chartMasuk?.config?.type || 'line');
    renderKeluar(chartKeluar?.config?.type || 'bar');

    document.querySelectorAll('.chart-range').forEach(btn => {
      btn.classList.toggle('is-active', btn.dataset.range === currentRange);
    });
  }

  document.querySelectorAll('.chart-range').forEach(btn => {
    btn.addEventListener('click', () => setRange(btn.dataset.range));
  });

  renderMasuk('line');
  renderKeluar('bar');
  setRange('6m');

  // ==================== JADWAL DASHBOARD (SINKRON KEL0LA) ====================
  const jadwalPopup = document.getElementById('jadwalPopup');
  const jadwalPopupOverlay = document.getElementById('jadwalPopupOverlay');
  const btnOpenJadwalPopup = document.getElementById('btnOpenJadwalPopup');
  const btnCloseJadwalPopup = document.getElementById('btnCloseJadwalPopup');

  const monthTitle = document.getElementById('dashMonthTitle');
  const grid = document.getElementById('dashCalendarGrid');
  const btnPrev = document.getElementById('dashBtnPrev');
  const btnNext = document.getElementById('dashBtnNext');
  const btnToday = document.getElementById('dashBtnToday');

  const dashDetailModal = document.getElementById('dashDetailModal');
  const detailOverlay   = document.getElementById('dashDetailOverlay');
  const btnCloseModal   = document.getElementById('dashBtnCloseModal');
  const modalTutup      = document.getElementById('dashModalTutup');

  const modalDate   = document.getElementById('dashModalDate');
  const modalMeta   = document.getElementById('dashModalMeta');
  const modalEvents = document.getElementById('dashModalEvents');
  const modalEmpty  = document.getElementById('dashModalEmpty');
  const modalHint   = document.getElementById('dashModalHint');

  const EVENTS = window.DASH_EVENTS || {};
  const MAX_EVENTS_PER_DAY = Number(window.DASH_MAX_PER_DAY || 4);

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

  const getEvents = (dateStr) => (EVENTS?.[dateStr] || []);

  const isClosedDay = (dateStr) => {
    const list = getEvents(dateStr);
    return list.some(e => String(e?.status || '').toLowerCase() === 'tutup');
  };

  const getVisibleEvents = (dateStr) => {
    const all = getEvents(dateStr);
    if (!isClosedDay(dateStr)) return all;
    return all.filter(e => String(e?.status || '').toLowerCase() === 'tutup');
  };

  const usedCount = (dateStr) => {
    if (isClosedDay(dateStr)) return 0;
    return getVisibleEvents(dateStr).length;
  };

  const remainingQuota = (dateStr) => {
    if (isClosedDay(dateStr)) return 0;
    return Math.max(0, MAX_EVENTS_PER_DAY - usedCount(dateStr));
  };

  let current = new Date();
  current.setDate(1);

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

  function dashShowModal(dateStr){
    const closed = isClosedDay(dateStr);
    const ev = getVisibleEvents(dateStr);

    const used = usedCount(dateStr);
    const left = remainingQuota(dateStr);

    modalDate.textContent = fmtLong(dateStr);

    modalMeta.innerHTML = `
      <div class="rounded-xl border border-slate-200 bg-white p-4">
        <div class="flex items-center justify-between gap-3">
          <div class="text-sm font-semibold text-slate-900">Batas & Sisa</div>
          <span class="text-[11px] ${closed ? 'text-rose-600' : 'text-slate-500'}">
            ${closed ? 'Tutup' : `Maks ${MAX_EVENTS_PER_DAY} jadwal/hari`}
          </span>
        </div>

        <div class="mt-3">
          <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
            <div class="text-[11px] text-slate-500">Jadwal terpakai</div>
            <div class="font-semibold text-slate-900">
              ${closed ? '- / -' : `${used} / ${MAX_EVENTS_PER_DAY}`}
            </div>
          </div>

          <div class="text-[11px] text-slate-500 mt-2">
            ${closed
              ? 'Hari ini TUTUP. Tidak bisa menambah jadwal.'
              : `Sisa slot: <span class="font-semibold text-slate-900">${left}</span>`}
          </div>
        </div>
      </div>
    `;

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
              <span class="pill ${status}">${String(status).toUpperCase()}</span>
            </div>
            ${desc}
          </div>
        `;
      });
      modalEmpty.classList.add('hidden');
      modalHint.textContent = `Total jadwal: ${getEvents(dateStr).length}`;
    } else {
      modalEmpty.classList.remove('hidden');
      modalHint.textContent = closed ? `Hari ini TUTUP.` : `Belum ada jadwal pada tanggal ini.`;
    }

    dashDetailModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  }

  function dashHideModal(){
    dashDetailModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  }

  detailOverlay?.addEventListener('click', dashHideModal);
  btnCloseModal?.addEventListener('click', dashHideModal);
  modalTutup?.addEventListener('click', dashHideModal);

  function dashRender() {
    if (!grid) return;

    grid.innerHTML = '';
    if (monthTitle) monthTitle.textContent = fmtMonth(current);

    const today = new Date();
    const year = current.getFullYear();
    const month = current.getMonth();

    const first = new Date(year, month, 1);
    const startDay = first.getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    for (let i = 0; i < startDay; i++) {
      const empty = document.createElement('div');
      empty.className = 'day-card day-muted';
      empty.innerHTML = `<div class="day-top"><div class="day-num"></div><div class="right-slot"></div></div>`;
      grid.appendChild(empty);
    }

    for (let day = 1; day <= daysInMonth; day++) {
      const dateObj = new Date(year, month, day);
      const key = ymd(dateObj);
      const isToday = sameDay(dateObj, today);

      const closed = isClosedDay(key);
      const ev = getVisibleEvents(key);
      const hasData = (getEvents(key).length > 0);

      const left = remainingQuota(key);
      const full = (!closed && left <= 0 && hasData);

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

      if (full) {
        const badge = document.createElement('div');
        badge.className = 'badge-full';
        badge.textContent = 'FULL';
        right.appendChild(badge);
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

      if (!closed && ev.length > 3) {
        const more = document.createElement('div');
        more.className = 'text-[11px] text-slate-500';
        more.textContent = `+${ev.length - 3} lainnya`;
        body.appendChild(more);
      }

      if (!hasData) {
        const hint = document.createElement('div');
        hint.className = 'text-[11px] text-slate-500/80';
        hint.textContent = '—';
        body.appendChild(hint);
      } else {
        const info = document.createElement('div');
        info.className = 'text-[11px] text-slate-600';
        info.textContent = closed ? `TUTUP` : `Sisa tambah: ${left}`;
        body.appendChild(info);
      }

      card.appendChild(top);
      card.appendChild(body);

      card.addEventListener('click', () => dashShowModal(key));
      grid.appendChild(card);
    }

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
    dashRender();
  });

  btnNext?.addEventListener('click', () => {
    current = new Date(current.getFullYear(), current.getMonth()+1, 1);
    dashRender();
  });

  btnToday?.addEventListener('click', () => {
    const t = new Date();
    current = new Date(t.getFullYear(), t.getMonth(), 1);
    dashRender();
    dashShowModal(ymd(t));
  });

  document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;

    const detailOpen = !dashDetailModal.classList.contains('hidden');
    if (detailOpen) dashHideModal();
    else if (!jadwalPopup.classList.contains('hidden')) closeJadwalPopup();
  });
</script>

@endsection
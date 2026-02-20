@extends('admin.layout.app')

@section('title', 'Riwayat Perubahan Stok - DPM Workshop')

@section('content')

{{-- TOPBAR --}}
<header class="relative h-16 bg-white/75 backdrop-blur border-b border-slate-200 sticky top-0 z-20">
  <div class="h-full px-4 sm:px-6 flex items-center justify-between gap-3">

    <div class="flex items-center gap-3 min-w-0">
      <button id="btnSidebar"
              type="button"
              class="md:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
              aria-label="Buka menu">
        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      <div class="min-w-0">
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Riwayat Perubahan Stok</h1>
        <p class="text-xs text-slate-500">Semua perubahan stok (masuk & keluar) tercatat di sini.</p>
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
  <div class="max-w-[1120px] mx-auto w-full">

    {{-- ── TOOLBAR: Search + Filter + Reset ────────────────────────────────── --}}
    <form method="GET" action="{{ route('riwayat_perubahan_stok') }}"
          class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-3 mb-4">

      {{-- Search --}}
      <div class="w-full lg:w-[420px]">
        <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">CARI</label>
        <div class="relative">
          <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 100-16 8 8 0 000 16z"/>
            </svg>
          </span>
          <input name="q" value="{{ $q ?? '' }}"
                 type="text" placeholder="Cari kode / nama barang..."
                 class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-slate-200 bg-white/90
                        text-sm placeholder:text-slate-400
                        focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
        </div>
      </div>

      {{-- Filter group --}}
      <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 w-full">

        {{-- Tipe --}}
        <div>
          <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">TIPE</label>
          <select name="tipe"
                  class="w-full py-2.5 px-3 rounded-lg border border-slate-200 bg-white/90 text-sm
                         focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
            <option value="">Semua</option>
            <option value="masuk"  {{ ($tipe ?? '') === 'masuk'  ? 'selected' : '' }}>Masuk</option>
            <option value="keluar" {{ ($tipe ?? '') === 'keluar' ? 'selected' : '' }}>Keluar</option>
          </select>
        </div>

        {{-- Dari --}}
        <div>
          <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">DARI</label>
          <input type="date" name="dari" value="{{ $dari ?? '' }}"
                 class="w-full py-2.5 px-3 rounded-lg border border-slate-200 bg-white/90 text-sm
                        focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
        </div>

        {{-- Sampai --}}
        <div>
          <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">SAMPAI</label>
          <input type="date" name="sampai" value="{{ $sampai ?? '' }}"
                 class="w-full py-2.5 px-3 rounded-lg border border-slate-200 bg-white/90 text-sm
                        focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
        </div>

        {{-- Buttons --}}
        <div class="flex gap-2 sm:justify-end sm:items-end">
          <button type="submit"
                  class="btn-shine inline-flex w-full sm:w-auto items-center justify-center gap-2
                         rounded-lg px-4 py-2.5 text-sm font-semibold
                         bg-blue-950 text-white hover:bg-blue-900 transition
                         shadow-[0_12px_24px_rgba(2,6,23,0.16)]">
            Filter
          </button>

          <a href="{{ route('riwayat_perubahan_stok') }}"
             class="inline-flex w-full sm:w-auto items-center justify-center gap-2
                    rounded-lg px-4 py-2.5 text-sm font-semibold
                    border border-slate-200 bg-white hover:bg-slate-50 transition">
            Reset
          </a>
        </div>

      </div>
    </form>

    {{-- ── INFO BADGE: jumlah hasil ──────────────────────────────────────────── --}}
    @if($rows->total() > 0)
      <p class="mb-3 text-xs text-slate-500">
        Menampilkan
        <span class="font-semibold text-slate-700">{{ $rows->firstItem() }}–{{ $rows->lastItem() }}</span>
        dari <span class="font-semibold text-slate-700">{{ $rows->total() }}</span> riwayat.
      </p>
    @endif

    {{-- ── TABLE CARD ────────────────────────────────────────────────────────── --}}
    <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

      <div class="overflow-x-auto">
        <table class="min-w-[1050px] w-full text-sm">
          <thead class="bg-slate-50/90 sticky top-0 z-10 backdrop-blur">
            <tr class="text-left text-slate-600">
              <th class="px-5 py-4 font-semibold w-[60px]">No</th>
              <th class="px-5 py-4 font-semibold">Kode Barang</th>
              <th class="px-5 py-4 font-semibold">Nama Barang</th>
              <th class="px-5 py-4 font-semibold">Pengguna</th>
              <th class="px-5 py-4 font-semibold text-center">Masuk</th>
              <th class="px-5 py-4 font-semibold text-center">Keluar</th>
              <th class="px-5 py-4 font-semibold text-right">Stok Awal</th>
              <th class="px-5 py-4 font-semibold text-right">Stok Akhir</th>
              <th class="px-5 py-4 font-semibold">Keterangan</th>
              <th class="px-5 py-4 font-semibold">Tanggal</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-200">
            @forelse($rows as $i => $r)
              <tr class="row-lift hover:bg-slate-50/70 transition">

                {{-- No --}}
                <td class="px-5 py-4 text-slate-500">
                  {{ $rows->firstItem() + $i }}
                </td>

                {{-- Kode --}}
                <td class="px-5 py-4 font-semibold text-slate-900">
                  {{ $r->kode_barang ?? '-' }}
                </td>

                {{-- Nama --}}
                <td class="px-5 py-4 text-slate-700">
                  {{ $r->nama_barang ?? '-' }}
                </td>

                {{-- Pengguna --}}
                <td class="px-5 py-4 text-slate-700">
                  {{ $r->nama_pengguna ?? '-' }}
                </td>

                {{-- Masuk --}}
                <td class="px-5 py-4 text-center">
                  @if($r->tipe === 'masuk')
                    <span class="inline-flex rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200
                                 px-2.5 py-1 text-xs font-semibold">
                      +{{ $r->qty }}
                    </span>
                  @else
                    <span class="text-slate-300">—</span>
                  @endif
                </td>

                {{-- Keluar --}}
                <td class="px-5 py-4 text-center">
                  @if($r->tipe === 'keluar')
                    <span class="inline-flex rounded-full bg-red-50 text-red-700 border border-red-200
                                 px-2.5 py-1 text-xs font-semibold">
                      -{{ $r->qty }}
                    </span>
                  @else
                    <span class="text-slate-300">—</span>
                  @endif
                </td>

                {{-- Stok Awal --}}
                <td class="px-5 py-4 text-right text-slate-600">
                  {{ $r->stok_awal }}
                </td>

                {{-- Stok Akhir --}}
                <td class="px-5 py-4 text-right font-semibold
                           {{ (int)$r->stok_akhir > (int)$r->stok_awal ? 'text-emerald-700' : 'text-red-700' }}">
                  {{ $r->stok_akhir }}
                </td>

                {{-- Keterangan --}}
                <td class="px-5 py-4 text-slate-700">
                  <span class="inline-flex rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs">
                    {{ $r->keterangan }}
                  </span>
                </td>

                {{-- Tanggal --}}
                <td class="px-5 py-4 text-slate-700 whitespace-nowrap">
                  {{ \Carbon\Carbon::parse($r->tanggal_riwayat_stok)->format('d/m/Y') }}
                </td>

              </tr>
            @empty
              <tr>
                <td colspan="10" class="px-6 py-14 text-center">
                  <div class="flex flex-col items-center gap-3">
                    <svg class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                      <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm text-slate-500">Belum ada riwayat perubahan stok.</p>
                    @if($q || $tipe || $dari || $sampai)
                      <a href="{{ route('riwayat_perubahan_stok') }}"
                         class="text-xs text-blue-700 hover:underline">Hapus filter</a>
                    @endif
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- ── Footer: copyright + pagination ─────────────────────────────────── --}}
      <div class="px-6 py-4 border-t border-slate-200
                  flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="text-xs text-slate-500">© DPM Workshop 2025</div>

        @if($rows->hasPages())
          <div class="text-sm">
            {{ $rows->links() }}
          </div>
        @endif
      </div>

    </div>
  </div>
</section>

@push('head')
<style>
  @media (prefers-reduced-motion: reduce) {
    .row-lift, .btn-shine { animation: none !important; transition: none !important; }
  }

  .row-lift {
    transform: translateY(0);
    transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease;
  }
  .row-lift:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 26px rgba(2,6,23,0.06);
  }

  .btn-shine { position: relative; overflow: hidden; }
  .btn-shine::after {
    content: "";
    position: absolute;
    inset: 0;
    transform: translateX(-120%);
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.28), transparent);
    transition: transform .65s ease;
  }
  .btn-shine:hover::after { transform: translateX(120%); }

  .tip { position: relative; }
  .tip[data-tip]::after {
    content: attr(data-tip);
    position: absolute;
    right: 0;
    top: calc(100% + 10px);
    background: rgba(15,23,42,.92);
    color: rgba(255,255,255,.92);
    font-size: 11px;
    padding: 6px 10px;
    border-radius: 10px;
    white-space: nowrap;
    opacity: 0;
    transform: translateY(-4px);
    pointer-events: none;
    transition: .15s ease;
  }
  .tip:hover::after { opacity: 1; transform: translateY(0); }
</style>
@endpush

@endsection
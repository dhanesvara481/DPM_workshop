@extends('admin.layout.app')

@section('title', 'DPM Workshop - Admin')

@section('content')

{{-- TOPBAR --}}
<header class="sticky top-0 z-20 border-b border-slate-200 bg-white/80 backdrop-blur">
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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Laporan Penjualan</h1>
        <p class="text-xs text-slate-500">Pilih range (minggu/bulan/tahun/custom) lalu export ke PDF.</p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('tampilan_notifikasi') }}"
         class="tip h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
         data-tip="Notifikasi"
         aria-label="Notifikasi">
        <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
        </svg>
      </a>
    </div>
  </div>
</header>

<section class="relative p-4 sm:p-6">
  {{-- BACKGROUND --}}
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
    <div class="absolute inset-0 opacity-[0.12]"
         style="background-image: linear-gradient(to right, rgba(2,6,23,0.06) 1px, transparent 1px), linear-gradient(to bottom, rgba(2,6,23,0.06) 1px, transparent 1px); background-size: 56px 56px;"></div>
    <div class="absolute inset-0 opacity-[0.20] mix-blend-screen animate-grid-scan"
         style="background-image: repeating-linear-gradient(90deg, transparent 0px, transparent 55px, rgba(255,255,255,0.95) 56px, transparent 57px, transparent 112px), repeating-linear-gradient(180deg, transparent 0px, transparent 55px, rgba(255,255,255,0.70) 56px, transparent 57px, transparent 112px); background-size: 112px 112px, 112px 112px;"></div>
    <div class="absolute -top-48 left-1/2 -translate-x-1/2 h-[720px] w-[720px] rounded-full blur-3xl opacity-10 bg-gradient-to-tr from-blue-950/25 via-blue-700/10 to-transparent"></div>
    <div class="absolute -bottom-72 right-1/4 h-[720px] w-[720px] rounded-full blur-3xl opacity-08 bg-gradient-to-tr from-blue-950/18 via-indigo-700/10 to-transparent"></div>
  </div>

  <div class="max-w-[980px] mx-auto w-full space-y-4">

    @php
      $mode   = $mode   ?? 'custom';
      $dari   = $dari   ?? null;
      $sampai = $sampai ?? null;
      $week   = $week   ?? null;
      $month  = $month  ?? null;
      $year   = $year   ?? null;

      $hasRange = false;
      if ($mode === 'custom') $hasRange = !empty($dari) && !empty($sampai);
      if ($mode === 'week')   $hasRange = !empty($week);
      if ($mode === 'month')  $hasRange = !empty($month);
      if ($mode === 'year')   $hasRange = !empty($year);

      $rowsCol    = collect($rows ?? []);
      $totalMasuk = (int) $rowsCol->sum(fn($x) => (int)($x->total ?? 0));
      $countTrx   = $rowsCol->count();
      $avg        = $countTrx ? (int) round($totalMasuk / $countTrx) : 0;
      $fmt        = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');
    @endphp

    {{-- SUMMARY CARDS — tampil setelah filter --}}
    @if($hasRange)
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
      <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur shadow-[0_10px_30px_rgba(2,6,23,0.08)] p-4">
        <div class="text-xs text-slate-500">Jumlah Transaksi</div>
        <div class="mt-1 text-lg font-semibold text-slate-900">{{ number_format($countTrx, 0, ',', '.') }}</div>
        <div class="mt-2 text-[11px] text-slate-500">Total invoice pada periode ini.</div>
      </div>
      <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur shadow-[0_10px_30px_rgba(2,6,23,0.08)] p-4">
        <div class="text-xs text-slate-500">Total Penjualan</div>
        <div class="mt-1 text-lg font-semibold text-emerald-700">{{ $fmt($totalMasuk) }}</div>
        <div class="mt-2 text-[11px] text-slate-500">Akumulasi seluruh invoice.</div>
      </div>
      <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur shadow-[0_10px_30px_rgba(2,6,23,0.08)] p-4">
        <div class="text-xs text-slate-500">Rata-rata / Invoice</div>
        <div class="mt-1 text-lg font-semibold text-slate-900">{{ $fmt($avg) }}</div>
        <div class="mt-2 text-[11px] text-slate-500">Estimasi nilai rata-rata per invoice.</div>
      </div>
    </div>
    @endif

    {{-- TOOLBAR --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_16px_44px_rgba(2,6,23,0.10)] overflow-hidden">
      <form method="GET" action="{{ route('laporan_penjualan') }}" class="p-4 sm:p-5">
        <div class="flex flex-col lg:flex-row lg:items-end gap-3">

          <div class="w-full lg:max-w-[220px]">
            <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">RANGE</label>
            <select name="mode" id="mode"
                    class="w-full py-2.5 px-3 rounded-xl border border-slate-200 bg-white/90 text-sm
                           focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
              <option value="custom" {{ $mode==='custom'?'selected':'' }}>Custom (Dari – Sampai)</option>
              <option value="week"   {{ $mode==='week'?'selected':'' }}>Mingguan</option>
              <option value="month"  {{ $mode==='month'?'selected':'' }}>Bulanan</option>
              <option value="year"   {{ $mode==='year'?'selected':'' }}>Tahunan</option>
            </select>
          </div>

          <div class="flex-1">
            {{-- Custom --}}
            <div id="box-custom" class="{{ $mode==='custom' ? '' : 'hidden' }}">
              <div class="grid grid-cols-1 sm:grid-cols-[1fr_auto_1fr] gap-3">
                <div>
                  <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">DARI</label>
                  <input type="date" name="dari" value="{{ $dari ?? '' }}"
                         class="w-full py-2.5 px-3 rounded-xl border border-slate-200 bg-white/90 text-sm
                                focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                </div>
                <div class="hidden sm:flex items-end justify-center pb-2.5 text-slate-400 font-semibold">—</div>
                <div>
                  <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">SAMPAI</label>
                  <input type="date" name="sampai" value="{{ $sampai ?? '' }}"
                         class="w-full py-2.5 px-3 rounded-xl border border-slate-200 bg-white/90 text-sm
                                focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                </div>
              </div>
            </div>
            {{-- Week --}}
            <div id="box-week" class="{{ $mode==='week' ? '' : 'hidden' }}">
              <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">MINGGU</label>
              <input type="week" name="week" value="{{ $week ?? '' }}"
                     class="w-full py-2.5 px-3 rounded-xl border border-slate-200 bg-white/90 text-sm
                            focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
            </div>
            {{-- Month --}}
            <div id="box-month" class="{{ $mode==='month' ? '' : 'hidden' }}">
              <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">BULAN</label>
              <input type="month" name="month" value="{{ $month ?? '' }}"
                     class="w-full py-2.5 px-3 rounded-xl border border-slate-200 bg-white/90 text-sm
                            focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
            </div>
            {{-- Year --}}
            <div id="box-year" class="{{ $mode==='year' ? '' : 'hidden' }}">
              <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">TAHUN</label>
              <input type="number" min="2000" max="2100" name="year" value="{{ $year ?? '' }}" placeholder="2026"
                     class="w-full py-2.5 px-3 rounded-xl border border-slate-200 bg-white/90 text-sm
                            focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
            </div>
          </div>

          <div class="flex flex-row gap-2 shrink-0">
            <button type="submit"
                    class="btn-shine inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold
                           bg-blue-950 text-white hover:bg-blue-900 transition shadow-[0_12px_24px_rgba(2,6,23,0.16)]">
              Filter
            </button>
            <a href="{{ route('laporan_penjualan') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold
                      border border-slate-200 bg-white hover:bg-slate-50 transition">
              Reset
            </a>
            <a href="{{ route('laporan_penjualan.print', request()->query()) }}" target="_blank"
               class="inline-flex items-center justify-center gap-1.5 rounded-xl px-4 py-2.5 text-sm font-semibold
                      {{ $hasRange ? 'bg-slate-900 text-white hover:bg-slate-800' : 'bg-slate-200 text-slate-400 cursor-not-allowed' }}
                      transition shadow-[0_12px_24px_rgba(2,6,23,0.14)]"
               {{ !$hasRange ? 'aria-disabled=true onclick=event.preventDefault()' : '' }}>
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
              </svg>
              Export PDF
            </a>
          </div>
        </div>

        <div class="mt-3 text-xs text-slate-500">
          Menampilkan <span class="font-semibold text-slate-700">{{ $countTrx }}</span> transaksi
          @if($hasRange)
            pada periode
            @if($mode==='custom') <span class="font-semibold">{{ $dari }}</span> s/d <span class="font-semibold">{{ $sampai }}</span>
            @elseif($mode==='week') minggu <span class="font-semibold">{{ $week }}</span>
            @elseif($mode==='month') bulan <span class="font-semibold">{{ $month }}</span>
            @elseif($mode==='year') tahun <span class="font-semibold">{{ $year }}</span>
            @endif
          @endif
        </div>
      </form>
    </div>

    {{-- TABLE --}}
    <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">
      <div class="p-5 sm:p-6">
        <div class="text-sm font-semibold text-slate-900 mb-4">Daftar Transaksi</div>

        <div class="rounded-xl border border-slate-200 overflow-hidden">
          {{-- Header --}}
          <div class="grid grid-cols-[48px_1fr_1fr_160px_160px] bg-slate-100 text-slate-600 text-xs font-semibold tracking-wide border-b border-slate-200">
            <div class="px-4 py-3 border-r border-slate-200 text-center">No</div>
            <div class="px-4 py-3 border-r border-slate-200">Kode Transaksi</div>
            <div class="px-4 py-3 border-r border-slate-200">Pelanggan</div>
            <div class="px-4 py-3 border-r border-slate-200 text-center">Tanggal</div>
            <div class="px-4 py-3 text-right">Total</div>
          </div>

          {{-- Body --}}
          <div class="divide-y divide-slate-100">
            @forelse($rowsCol as $i => $r)
              @php
                $name     = trim((string)($r->nama_pengguna ?? 'User'));
                $initials = collect(preg_split('/\s+/', $name))->filter()->take(2)
                              ->map(fn($p) => mb_strtoupper(mb_substr($p,0,1)))->join('');
              @endphp
              <div class="grid grid-cols-[48px_1fr_1fr_160px_160px] items-center hover:bg-slate-50/60 transition text-sm">
                <div class="px-4 py-3 border-r border-slate-100 text-center text-slate-500 text-xs">{{ $i + 1 }}</div>
                <div class="px-4 py-3 border-r border-slate-100 font-semibold text-slate-900">
                  {{ $r->kode_transaksi ?? ('INV-' . ($r->id ?? '-')) }}
                </div>
                <div class="px-4 py-3 border-r border-slate-100 flex items-center gap-2 min-w-0">
                  <div class="h-7 w-7 shrink-0 rounded-full grid place-items-center border border-slate-200
                              bg-gradient-to-br from-slate-50 to-white text-[10px] font-bold text-slate-700">
                    {{ $initials ?: 'U' }}
                  </div>
                  <span class="truncate text-slate-700">{{ $name }}</span>
                </div>
                <div class="px-4 py-3 border-r border-slate-100 text-center text-slate-600 text-xs">
                  {{ isset($r->created_at) ? \Carbon\Carbon::parse($r->created_at)->format('d M Y') : '-' }}
                </div>
                <div class="px-4 py-3 text-right font-semibold text-emerald-700">
                  {{ $fmt($r->total ?? 0) }}
                </div>
              </div>
            @empty
              <div class="py-14 text-center text-slate-500">
                <svg class="mx-auto h-8 w-8 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                </svg>
                <div class="text-sm font-semibold text-slate-800">Belum ada data untuk ditampilkan.</div>
                <div class="text-xs mt-1">Pilih range lalu klik <span class="font-semibold">Filter</span>.</div>
              </div>
            @endforelse
          </div>

          {{-- Footer total --}}
          @if($countTrx > 0)
          <div class="grid grid-cols-[48px_1fr_1fr_160px_160px] bg-slate-50 border-t-2 border-slate-200 font-semibold text-sm">
            <div class="col-span-4 px-4 py-3 text-right text-slate-700 border-r border-slate-200">
              Total Keseluruhan
            </div>
            <div class="px-4 py-3 text-right text-emerald-700">{{ $fmt($totalMasuk) }}</div>
          </div>
          @endif
        </div>
      </div>

      <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
        © DPM Workshop 2025
      </div>
    </div>

  </div>
</section>

@endsection

@push('head')
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
  .btn-shine { position: relative; overflow: hidden; }
  .btn-shine::after {
    content: ""; position: absolute; inset: 0; transform: translateX(-120%);
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.28), transparent);
    transition: transform .65s ease;
  }
  .btn-shine:hover::after { transform: translateX(120%); }
  .tip { position: relative; }
  .tip[data-tip]::after {
    content: attr(data-tip); position: absolute; right: 0; top: calc(100% + 10px);
    background: rgba(15,23,42,.92); color: rgba(255,255,255,.92); font-size: 11px;
    padding: 6px 10px; border-radius: 10px; white-space: nowrap;
    opacity: 0; transform: translateY(-4px); pointer-events: none; transition: .15s ease;
  }
  .tip:hover::after { opacity: 1; transform: translateY(0); }
</style>
@endpush

@push('scripts')
<script>
  const modeEl = document.getElementById('mode');
  const boxes  = {
    custom: document.getElementById('box-custom'),
    week:   document.getElementById('box-week'),
    month:  document.getElementById('box-month'),
    year:   document.getElementById('box-year'),
  };
  function showBox(mode) {
    Object.values(boxes).forEach(b => b?.classList.add('hidden'));
    boxes[mode]?.classList.remove('hidden');
  }
  modeEl?.addEventListener('change', e => showBox(e.target.value));
  showBox(modeEl?.value || 'custom');
</script>
@endpush
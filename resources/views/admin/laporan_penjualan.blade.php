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

<section class="relative p-4 sm:p-6">
  {{-- BACKGROUND --}}
  <div class="pointer-events-none absolute inset-0 -z-10">
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
          <div class="grid grid-cols-[80px_1fr_220px] items-center bg-slate-200 text-slate-900 font-semibold">
            <div class="px-4 py-3 border-r-4 border-slate-900">No</div>
            <div class="px-4 py-3 border-r-4 border-slate-900 text-center">Kode Transaksi</div>
            <div class="px-4 py-3 text-center">Tanggal</div>
          </div>

          <div class="h-1 bg-slate-900"></div>

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
</style>
@endpush

@push('scripts')
<script>
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
</script>
@endpush

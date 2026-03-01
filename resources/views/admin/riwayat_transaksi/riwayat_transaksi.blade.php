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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Riwayat Transaksi</h1>
        <p class="text-xs text-slate-500">Cari, filter tanggal, lalu tap invoice untuk detail.</p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('tampilan_notifikasi') }}"
         class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
         title="Notifikasi">
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
    <div class="absolute -top-48 left-1/2 -translate-x-1/2 h-[720px] w-[720px] rounded-full blur-3xl opacity-10
                bg-gradient-to-tr from-blue-950/25 via-blue-700/10 to-transparent"></div>
    <div class="absolute -bottom-72 right-1/4 h-[720px] w-[720px] rounded-full blur-3xl opacity-10
                bg-gradient-to-tr from-indigo-950/20 via-indigo-700/10 to-transparent"></div>
  </div>

  <div class="max-w-[980px] mx-auto w-full space-y-4">

    @php
      $rowsCol    = collect($rows->items());
      $totalMasuk = (int) $rows->sum(fn($x) => (int)($x->total ?? 0));
      $countTrx   = $rows->total();
      $avg        = $rows->count() ? (int) round($rowsCol->sum(fn($x)=>(int)($x->total??0)) / $rows->count()) : 0;
      $groups     = $rowsCol->groupBy(fn($r) => \Carbon\Carbon::parse($r->created_at)->toDateString());
      $fmt        = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');
      $sortNext   = ($sort ?? 'asc') === 'asc' ? 'desc' : 'asc';
    @endphp

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
      <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur shadow-[0_10px_30px_rgba(2,6,23,0.08)] p-4">
        <div class="text-xs text-slate-500">Jumlah Invoice</div>
        <div class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($countTrx, 0, ',', '.') }}</div>
        <div class="mt-1 text-[11px] text-slate-400">Total invoice pada hasil filter.</div>
      </div>
      <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur shadow-[0_10px_30px_rgba(2,6,23,0.08)] p-4">
        <div class="text-xs text-slate-500">Total Pemasukan</div>
        <div class="mt-1 text-2xl font-bold text-emerald-600">{{ $fmt($totalMasuk) }}</div>
        <div class="mt-1 text-[11px] text-slate-400">Akumulasi invoice pada hasil filter.</div>
      </div>
      <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur shadow-[0_10px_30px_rgba(2,6,23,0.08)] p-4">
        <div class="text-xs text-slate-500">Rata-rata / Invoice</div>
        <div class="mt-1 text-2xl font-bold text-slate-900">{{ $fmt($avg) }}</div>
        <div class="mt-1 text-[11px] text-slate-400">Estimasi nilai rata-rata per invoice.</div>
      </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_16px_44px_rgba(2,6,23,0.10)]">
      <form method="GET" action="{{ route('riwayat_transaksi') }}" class="p-4 sm:p-5">

        {{-- Hidden sort agar ikut submit --}}
        <input type="hidden" name="sort"     value="{{ $sort ?? 'asc' }}">
        <input type="hidden" name="per_page" value="{{ $perPage ?? 15 }}">

        <div class="flex flex-col sm:flex-row gap-3">

          {{-- Search --}}
          <div class="flex-1">
            <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-1.5">CARI</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 pointer-events-none">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3"/>
                  <circle cx="11" cy="11" r="8"/>
                </svg>
              </span>
              <input name="q" value="{{ $q ?? '' }}" type="text"
                     placeholder="Cari user / kode invoice..."
                     class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-200 bg-white text-sm placeholder:text-slate-400
                            focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
            </div>
          </div>

          {{-- Dari --}}
          <div class="sm:w-40">
            <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-1.5">DARI</label>
            <input type="date" name="dari" value="{{ $dari ?? '' }}"
                   class="w-full py-2.5 px-3 rounded-xl border border-slate-200 bg-white text-sm
                          focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
          </div>

          {{-- Sampai --}}
          <div class="sm:w-40">
            <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-1.5">SAMPAI</label>
            <input type="date" name="sampai" value="{{ $sampai ?? '' }}"
                   class="w-full py-2.5 px-3 rounded-xl border border-slate-200 bg-white text-sm
                          focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
          </div>

          {{-- Buttons --}}
          <div class="flex sm:flex-col gap-2 sm:justify-end sm:pt-[22px]">
            <button type="submit"
                    class="btn-shine flex-1 sm:flex-none inline-flex items-center justify-center rounded-xl px-5 py-2.5 text-sm font-semibold
                           bg-blue-950 text-white hover:bg-blue-900 transition shadow-[0_8px_20px_rgba(2,6,23,0.16)]">
              Filter
            </button>
            <a href="{{ route('riwayat_transaksi') }}"
               class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-xl px-5 py-2.5 text-sm font-semibold
                      border border-slate-200 bg-white hover:bg-slate-50 transition">
              Reset
            </a>
          </div>
        </div>

        {{-- Second row: sort + per_page + count info --}}
        <div class="mt-3 flex flex-wrap items-center gap-3">

          {{-- Sort toggle --}}
          <a href="{{ route('riwayat_transaksi', array_merge(request()->except(['sort','page']), ['sort' => $sortNext, 'per_page' => $perPage])) }}"
             class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold
                    text-slate-700 hover:bg-slate-50 transition">
            @if(($sort ?? 'asc') === 'asc')
              <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9M3 12h5m10 4V8m0 0l-3 3m3-3 3 3"/>
              </svg>
              Terlama
            @else
              <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9M3 12h5m10 0v8m0 0l-3-3m3 3 3-3"/>
              </svg>
              Terbaru
            @endif
          </a>

          {{-- Per page --}}
          <div class="flex items-center gap-1.5">
            <span class="text-xs text-slate-500">Tampilkan</span>
            <select name="per_page" form="formFilter"
                    onchange="document.getElementById('formFilter').submit()"
                    class="rounded-xl border border-slate-200 bg-white text-xs px-2 py-1.5
                           focus:outline-none focus:ring-4 focus:ring-blue-900/10 transition">
              @foreach([10, 15, 25, 50] as $pp)
                <option value="{{ $pp }}" @selected(($perPage ?? 15) == $pp)>{{ $pp }}</option>
              @endforeach
            </select>
            <span class="text-xs text-slate-500">/ halaman</span>
          </div>

          <div class="ml-auto text-xs text-slate-400">
            Menampilkan <span class="font-semibold text-slate-600">{{ $rows->firstItem() }}–{{ $rows->lastItem() }}</span>
            dari <span class="font-semibold text-slate-600">{{ $countTrx }}</span> invoice.
          </div>
        </div>

      </form>

      {{-- Re-expose form as named id for select onchange --}}
      <form id="formFilter" method="GET" action="{{ route('riwayat_transaksi') }}" class="hidden">
        <input type="hidden" name="q"       value="{{ $q ?? '' }}">
        <input type="hidden" name="dari"    value="{{ $dari ?? '' }}">
        <input type="hidden" name="sampai"  value="{{ $sampai ?? '' }}">
        <input type="hidden" name="sort"    value="{{ $sort ?? 'asc' }}">
        <input type="hidden" name="per_page" id="hiddenPerPage">
      </form>

    </div>

    {{-- LIST --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">
      <div class="divide-y divide-slate-100">

        @forelse($groups as $date => $items)

          {{-- Group header tanggal --}}
          <div class="px-4 sm:px-6 py-3 bg-slate-50/80 flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-700 tracking-wide">
              {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
            </span>
            <span class="text-xs text-slate-400">{{ $items->count() }} invoice</span>
          </div>

          {{-- Items --}}
          @foreach($items as $trx)
            @php
              $amount   = (int)($trx->total ?? 0);
              $name     = trim((string)($trx->nama_pengguna ?? 'User'));
              $initials = collect(preg_split('/\s+/', $name))
                            ->filter()->take(2)
                            ->map(fn($p) => mb_strtoupper(mb_substr($p, 0, 1)))
                            ->join('');

              $status   = strtolower((string)($trx->status ?? 'paid'));
              $statusUI = match($status) {
                'paid','lunas','success' => ['label' => 'PAID',   'cls' => 'bg-emerald-50 text-emerald-700 border border-emerald-200'],
                'unpaid','pending'       => ['label' => 'UNPAID', 'cls' => 'bg-amber-50 text-amber-700 border border-amber-200'],
                'expired'                => ['label' => 'EXPIRED','cls' => 'bg-slate-100 text-slate-600 border border-slate-200'],
                'refund','refunded'      => ['label' => 'REFUND', 'cls' => 'bg-red-50 text-red-600 border border-red-200'],
                default                  => ['label' => strtoupper($status ?: 'STATUS'), 'cls' => 'bg-slate-100 text-slate-600 border border-slate-200'],
              };
            @endphp

            <a href="{{ route('detail_riwayat_transaksi', $trx->id ?? 0) }}"
               class="group flex items-center justify-between gap-4 px-4 sm:px-6 py-4
                      hover:bg-slate-50/60 transition-colors">

              {{-- Kiri: Avatar + Info --}}
              <div class="flex items-center gap-3 min-w-0">
                <div class="h-10 w-10 shrink-0 rounded-full grid place-items-center
                            border border-slate-200 bg-gradient-to-br from-slate-100 to-white">
                  <span class="text-xs font-bold text-slate-700">{{ $initials ?: 'U' }}</span>
                </div>
                <div class="min-w-0">
                  <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-semibold text-slate-900 text-sm truncate">{{ $name ?: 'User' }}</span>
                    <span class="shrink-0 inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $statusUI['cls'] }}">
                      {{ $statusUI['label'] }}
                    </span>
                  </div>
                  <div class="text-xs text-slate-400 mt-0.5">
                    {{ $trx->kode_transaksi ?? ('INV-' . ($trx->id ?? '-')) }}
                    <span class="mx-1">·</span>
                    {{ \Carbon\Carbon::parse($trx->created_at)->format('H:i') }}
                  </div>
                </div>
              </div>

              {{-- Kanan: Nominal --}}
              <div class="text-right shrink-0">
                <div class="text-sm font-bold text-emerald-600">{{ $fmt($amount) }}</div>
                <div class="text-[11px] text-slate-400 group-hover:text-slate-500 mt-0.5 transition-colors">
                  Lihat detail →
                </div>
              </div>

            </a>
          @endforeach

        @empty
          <div class="py-16 text-center">
            <div class="mx-auto h-14 w-14 rounded-2xl border border-slate-200 bg-white grid place-items-center mb-3">
              <svg class="h-7 w-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M6 7V5a2 2 0 012-2h8a2 2 0 012 2v2M6 7v14a2 2 0 002 2h8a2 2 0 002-2V7"/>
              </svg>
            </div>
            <p class="text-sm font-semibold text-slate-800">Belum ada riwayat invoice</p>
            <p class="text-xs text-slate-400 mt-1">Coba ubah filter tanggal atau kata kunci pencarian.</p>
          </div>
        @endforelse

      </div>

      {{-- PAGINATION --}}
      @if($rows->hasPages())
        <div class="px-4 sm:px-6 py-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">

          <div class="text-xs text-slate-400">
            Halaman {{ $rows->currentPage() }} dari {{ $rows->lastPage() }}
          </div>

          <div class="flex items-center gap-1">

            {{-- Prev --}}
            @if($rows->onFirstPage())
              <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-300 cursor-not-allowed text-sm">
                ‹
              </span>
            @else
              <a href="{{ $rows->previousPageUrl() }}"
                 class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition text-slate-600 text-sm">
                ‹
              </a>
            @endif

            {{-- Page numbers --}}
            @foreach($rows->getUrlRange(max(1, $rows->currentPage()-2), min($rows->lastPage(), $rows->currentPage()+2)) as $page => $url)
              @if($page == $rows->currentPage())
                <span class="inline-flex h-8 min-w-[2rem] px-2 items-center justify-center rounded-lg bg-blue-950 text-white text-xs font-semibold">
                  {{ $page }}
                </span>
              @else
                <a href="{{ $url }}"
                   class="inline-flex h-8 min-w-[2rem] px-2 items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition text-slate-600 text-xs">
                  {{ $page }}
                </a>
              @endif
            @endforeach

            {{-- Next --}}
            @if($rows->hasMorePages())
              <a href="{{ $rows->nextPageUrl() }}"
                 class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition text-slate-600 text-sm">
                ›
              </a>
            @else
              <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-300 cursor-not-allowed text-sm">
                ›
              </span>
            @endif

          </div>
        </div>
      @endif

      <div class="px-6 py-4 border-t border-slate-100 text-xs text-slate-400">
        © DPM Workshop 2025
      </div>
    </div>

  </div>
</section>

@endsection

@push('head')
<style>
  @media (prefers-reduced-motion: reduce) {
    .btn-shine { animation: none !important; transition: none !important; }
  }
  .btn-shine { position: relative; overflow: hidden; }
  .btn-shine::after {
    content: ""; position: absolute; inset: 0; transform: translateX(-120%);
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.25), transparent);
    transition: transform .6s ease;
  }
  .btn-shine:hover::after { transform: translateX(120%); }
</style>
@endpush

@push('scripts')
<script>
  // Per page select submits the hidden form with correct value
  document.querySelector('[name="per_page"][form="formFilter"]')?.addEventListener('change', function() {
    document.getElementById('hiddenPerPage').value = this.value;
    document.getElementById('formFilter').submit();
  });
</script>
@endpush
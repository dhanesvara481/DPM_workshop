@extends('admin.layout.app')

@section('title', 'Riwayat Transaksi - DPM Workshop')

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
      <button type="button"
              class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
              title="Notifikasi">
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
    <div class="absolute -top-48 left-1/2 -translate-x-1/2 h-[720px] w-[720px] rounded-full blur-3xl opacity-10
                bg-gradient-to-tr from-blue-950/25 via-blue-700/10 to-transparent"></div>
    <div class="absolute -bottom-72 right-1/4 h-[720px] w-[720px] rounded-full blur-3xl opacity-10
                bg-gradient-to-tr from-indigo-950/20 via-indigo-700/10 to-transparent"></div>
  </div>

  <div class="max-w-[980px] mx-auto w-full space-y-4">

    {{-- SUMMARY (Pemasukan saja) --}}
    @php
      $rowsCol = collect($rows ?? []);
      $totalMasuk = (int) $rowsCol->sum(fn($x) => (int)($x->total ?? 0));
      $countTrx = $rowsCol->count();
      $avg = $countTrx ? (int) round($totalMasuk / $countTrx) : 0;

      $groups = $rowsCol->groupBy(fn($r) => \Carbon\Carbon::parse($r->created_at)->toDateString());
      $fmt = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
      <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur shadow-[0_10px_30px_rgba(2,6,23,0.08)] p-4">
        <div class="text-xs text-slate-500">Jumlah Invoice</div>
        <div class="mt-1 text-lg font-semibold text-slate-900">{{ number_format($countTrx, 0, ',', '.') }}</div>
        <div class="mt-2 text-[11px] text-slate-500">Total invoice pada hasil filter.</div>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur shadow-[0_10px_30px_rgba(2,6,23,0.08)] p-4">
        <div class="text-xs text-slate-500">Total Pemasukan</div>
        <div class="mt-1 text-lg font-semibold text-emerald-700">{{ $fmt($totalMasuk) }}</div>
        <div class="mt-2 text-[11px] text-slate-500">Akumulasi invoice pada hasil filter.</div>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur shadow-[0_10px_30px_rgba(2,6,23,0.08)] p-4">
        <div class="text-xs text-slate-500">Rata-rata / Invoice</div>
        <div class="mt-1 text-lg font-semibold text-slate-900">{{ $fmt($avg) }}</div>
        <div class="mt-2 text-[11px] text-slate-500">Estimasi nilai rata-rata per invoice.</div>
      </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_16px_44px_rgba(2,6,23,0.10)] overflow-hidden">
      <form method="GET" action="{{ route('riwayat_transaksi') }}" class="p-4 sm:p-5">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
          <div class="sm:col-span-5">
            <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">CARI</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3"/>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                </svg>
              </span>
              <input name="q" value="{{ $q ?? '' }}" type="text"
                     placeholder="Cari user / kode invoice..."
                     class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-200 bg-white/90 text-sm placeholder:text-slate-400
                            focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
            </div>
          </div>

          <div class="sm:col-span-3">
            <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">DARI</label>
            <input type="date" name="dari" value="{{ $dari ?? '' }}"
                   class="w-full py-2.5 px-3 rounded-xl border border-slate-200 bg-white/90 text-sm
                          focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
          </div>

          <div class="sm:col-span-3">
            <label class="block text-[11px] tracking-widest text-slate-500 font-semibold mb-2">SAMPAI</label>
            <input type="date" name="sampai" value="{{ $sampai ?? '' }}"
                   class="w-full py-2.5 px-3 rounded-xl border border-slate-200 bg-white/90 text-sm
                          focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
          </div>

          <div class="sm:col-span-1 flex sm:flex-col gap-2 sm:justify-end">
            <button type="submit"
                    class="btn-shine inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                           bg-blue-950 text-white hover:bg-blue-900 transition
                           shadow-[0_12px_24px_rgba(2,6,23,0.16)]">
              Filter
            </button>

            <a href="{{ route('riwayat_transaksi') }}"
               class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                      border border-slate-200 bg-white hover:bg-slate-50 transition">
              Reset
            </a>
          </div>
        </div>

        <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
          <div class="text-xs text-slate-500">
            Menampilkan <span class="font-semibold text-slate-700">{{ $rowsCol->count() }}</span> invoice.
          </div>
        </div>
      </form>
    </div>

    {{-- LIST --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">
      <div class="p-4 sm:p-6">

        @forelse($groups as $date => $items)
          <div class="mb-6 last:mb-0">
            <div class="sticky top-16 z-10 -mx-4 sm:-mx-6 px-4 sm:px-6 py-2 bg-white/80 backdrop-blur border-y border-slate-200">
              <div class="flex items-center justify-between">
                <div class="text-sm font-semibold text-slate-900">
                  {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                </div>
                <div class="text-xs text-slate-500">
                  {{ $items->count() }} invoice
                </div>
              </div>
            </div>

            <div class="mt-3 space-y-2">
              @foreach($items as $trx)
                @php
                  $amount = (int)($trx->total ?? 0);
                  $name = trim((string)($trx->nama_pengguna ?? 'User'));
                  $initials = collect(preg_split('/\s+/', $name))->filter()->take(2)->map(fn($p) => mb_strtoupper(mb_substr($p,0,1)))->join('');

                  $status = strtolower((string)($trx->status ?? 'paid'));
                  $statusUI = match($status){
                    'paid','lunas','success' => ['label'=>'PAID','cls'=>'bg-emerald-50 text-emerald-700 border border-emerald-100'],
                    'unpaid','pending'       => ['label'=>'UNPAID','cls'=>'bg-amber-50 text-amber-700 border border-amber-100'],
                    'expired'                => ['label'=>'EXPIRED','cls'=>'bg-slate-50 text-slate-700 border border-slate-200'],
                    'refund','refunded'      => ['label'=>'REFUND','cls'=>'bg-red-50 text-red-700 border border-red-100'],
                    default                  => ['label'=>strtoupper($status ?: 'STATUS'),'cls'=>'bg-slate-50 text-slate-700 border border-slate-200'],
                  };
                @endphp

                <a href="{{ route('detail_riwayat_transaksi', $trx->id ?? 0) }}"
                   class="group block rounded-2xl border border-transparent hover:border-slate-200 hover:bg-slate-50/70 transition">
                  <div class="flex items-center justify-between gap-4 px-4 py-3">
                    <div class="flex items-center gap-3 min-w-0">
                      <div class="h-10 w-10 rounded-full grid place-items-center border border-slate-200 bg-gradient-to-br from-slate-50 to-white">
                        <span class="text-xs font-bold text-slate-700">{{ $initials ?: 'U' }}</span>
                      </div>

                      <div class="min-w-0">
                        <div class="flex items-center gap-2 min-w-0">
                          <div class="font-semibold text-slate-900 truncate">{{ $name ?: 'User' }}</div>

                          <span class="shrink-0 inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $statusUI['cls'] }}">
                            {{ $statusUI['label'] }}
                          </span>
                        </div>

                        <div class="text-xs text-slate-500 truncate">
                          {{ $trx->kode_transaksi ?? ('INV-' . ($trx->id ?? '-')) }}
                          • {{ \Carbon\Carbon::parse($trx->created_at)->format('H:i') }}
                        </div>
                      </div>
                    </div>

                    <div class="text-right shrink-0">
                      <div class="text-sm font-semibold text-emerald-700">
                        {{ $fmt($amount) }}
                      </div>
                      <div class="text-[11px] text-slate-500 group-hover:text-slate-600">Tap untuk detail</div>
                    </div>
                  </div>
                </a>
              @endforeach
            </div>
          </div>
        @empty
          <div class="py-14 text-center">
            <div class="mx-auto h-12 w-12 rounded-2xl border border-slate-200 bg-white grid place-items-center text-slate-500">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M6 7V5a2 2 0 012-2h8a2 2 0 012 2v2M6 7v14a2 2 0 002 2h8a2 2 0 002-2V7"/>
              </svg>
            </div>
            <div class="mt-3 text-sm font-semibold text-slate-900">Belum ada riwayat invoice</div>
            <div class="mt-1 text-xs text-slate-500">Coba ubah filter tanggal atau kata kunci pencarian.</div>
          </div>
        @endforelse

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
    .btn-shine { animation: none !important; transition: none !important; }
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
</style>
@endpush

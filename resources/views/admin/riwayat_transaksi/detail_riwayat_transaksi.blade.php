@extends('admin.layout.app')

@section('title', 'Detail Riwayat Transaksi - DPM Workshop')

@section('content')


{{-- TOPBAR (ikut style halaman lain) --}}
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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Detail Invoice</h1>
        <p class="text-xs text-slate-500">Rincian transaksi & item invoice.</p>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('riwayat_transaksi') }}"
         class="inline-flex items-center justify-center rounded-xl px-3 py-2 text-sm font-semibold
                border border-slate-200 bg-white hover:bg-slate-50 transition">
        Kembali
      </a>

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

  @php
    // amanin akses
    $trx = $trx ?? null;

    $tanggal = $trx?->created_at
      ? \Carbon\Carbon::parse($trx->created_at)->translatedFormat('d F Y')
      : '-';

    $jam = $trx?->created_at
      ? \Carbon\Carbon::parse($trx->created_at)->format('H:i')
      : '-';

    $nominal = (int)($trx->total ?? 0);

    // Sesuaikan field jika beda:
    // kalau invoice cuma pemasukan, bisa selalu hijau.
    $isPlus = (strtolower((string)($trx->tipe ?? $trx->jenis ?? 'masuk')) !== 'keluar');

    $fmt = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');

    $nama = trim((string)($trx->nama_pengguna ?? $trx->nama_pelanggan ?? 'User'));
    $initials = collect(preg_split('/\s+/', $nama))
      ->filter()
      ->take(2)
      ->map(fn($p) => mb_strtoupper(mb_substr($p,0,1)))
      ->join('');

    $kode = $trx->kode_transaksi ?? $trx->kode ?? ('INV-' . ($trx->id ?? '-'));

    $status = strtolower((string)($trx->status ?? 'paid'));
    $statusUI = match($status){
      'paid','lunas','success' => ['label'=>'PAID','cls'=>'bg-emerald-50 text-emerald-700 border border-emerald-100'],
      'unpaid','pending'       => ['label'=>'UNPAID','cls'=>'bg-amber-50 text-amber-700 border border-amber-100'],
      'expired'                => ['label'=>'EXPIRED','cls'=>'bg-slate-50 text-slate-700 border border-slate-200'],
      'refund','refunded'      => ['label'=>'REFUND','cls'=>'bg-red-50 text-red-700 border border-red-100'],
      default                  => ['label'=>strtoupper($status ?: 'STATUS'),'cls'=>'bg-slate-50 text-slate-700 border border-slate-200'],
    };

    $metode = $trx->metode_pembayaran ?? $trx->metode ?? '-';
    $catatan = $trx->catatan ?? '-';
  @endphp

  <div class="max-w-5xl mx-auto w-full">

    {{-- CARD UTAMA --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

      {{-- HEADER CARD --}}
      <div class="p-5 sm:p-6 border-b border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
          <div>
            <div class="text-xs text-slate-500">Tanggal Transaksi</div>
            <div class="mt-1 text-lg font-semibold text-slate-900">{{ $tanggal }}</div>
            <div class="mt-1 text-xs text-slate-500">{{ $kode }} • {{ $jam }}</div>
          </div>

          <div class="flex items-center gap-2">
            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $statusUI['cls'] }}">
              {{ $statusUI['label'] }}
            </span>

            <div class="text-right">
              <div class="text-xs text-slate-500">Total</div>
              <div class="text-base font-bold {{ $isPlus ? 'text-emerald-700' : 'text-red-700' }}">
                {{ $isPlus ? '+' : '-' }}{{ $fmt(abs($nominal)) }}
              </div>
            </div>
          </div>
        </div>

        {{-- USER STRIP --}}
        <div class="mt-5 flex items-center justify-between gap-4">
          <div class="flex items-center gap-3 min-w-0">
            <div class="h-11 w-11 rounded-full grid place-items-center border border-slate-200 bg-gradient-to-br from-slate-50 to-white">
              <span class="text-xs font-bold text-slate-700">{{ $initials ?: 'U' }}</span>
            </div>
            <div class="min-w-0">
              <div class="font-semibold text-slate-900 truncate">{{ $nama ?: 'User' }}</div>
              <div class="text-xs text-slate-500 truncate">Metode: <span class="font-semibold text-slate-700">{{ $metode }}</span></div>
            </div>
          </div>

          <a href="{{ route('transaksi.nota', $trx->id ?? 0) }}"
             class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                    bg-slate-900 text-white hover:bg-slate-800 transition
                    shadow-[0_12px_24px_rgba(2,6,23,0.18)]">
            Cetak Nota
          </a>
        </div>
      </div>

      {{-- BODY --}}
      <div class="p-5 sm:p-6 space-y-5">

        {{-- INFO GRID --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
            <div class="flex items-center justify-between gap-3">
              <span class="text-slate-500">Kode</span>
              <span class="font-semibold text-slate-900">{{ $kode }}</span>
            </div>
            <div class="flex items-center justify-between gap-3">
              <span class="text-slate-500">Status</span>
              <span class="font-semibold text-slate-900">{{ $statusUI['label'] }}</span>
            </div>
            <div class="flex items-center justify-between gap-3">
              <span class="text-slate-500">Metode</span>
              <span class="font-semibold text-slate-900">{{ $metode }}</span>
            </div>
            <div class="flex items-center justify-between gap-3">
              <span class="text-slate-500">Catatan</span>
              <span class="font-semibold text-slate-900">{{ $catatan }}</span>
            </div>
          </div>
        </div>

        {{-- ITEM TABLE --}}
        <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
          <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <div class="text-sm font-semibold text-slate-900">Detail Item</div>
            <div class="text-xs text-slate-500">{{ is_countable($items ?? []) ? count($items) : 0 }} item</div>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-[760px] w-full text-sm">
              <thead class="bg-slate-50 text-slate-600">
                <tr class="text-left">
                  <th class="px-4 py-3 font-semibold">Barang</th>
                  <th class="px-4 py-3 font-semibold text-right">Harga</th>
                  <th class="px-4 py-3 font-semibold text-right">Qty</th>
                  <th class="px-4 py-3 font-semibold text-right">Subtotal</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-200">
                @forelse(($items ?? []) as $it)
                  @php
                    $namaItem  = $it->nama_barang ?? $it->barang->nama_barang ?? $it->nama ?? '-';
                    $harga     = (int)($it->harga ?? $it->barang->harga ?? 0);
                    $qty       = (int)($it->qty ?? $it->jumlah ?? 0);
                    $sub       = $harga * $qty;
                  @endphp
                  <tr class="hover:bg-slate-50/70 transition">
                    <td class="px-4 py-3 text-slate-900 font-semibold">{{ $namaItem }}</td>
                    <td class="px-4 py-3 text-right text-slate-700">{{ $fmt($harga) }}</td>
                    <td class="px-4 py-3 text-right text-slate-700">{{ number_format($qty, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-right font-semibold text-slate-900">{{ $fmt($sub) }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="px-4 py-10 text-center text-slate-500">
                      Tidak ada item untuk invoice ini.
                    </td>
                  </tr>
                @endforelse
              </tbody>

              {{-- FOOTER TOTAL --}}
              <tfoot class="bg-slate-50">
                <tr>
                  <td class="px-4 py-3 text-slate-500 font-semibold" colspan="3">Total</td>
                  <td class="px-4 py-3 text-right font-bold text-slate-900">{{ $fmt($nominal) }}</td>
                </tr>
              </tfoot>
            </table>
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

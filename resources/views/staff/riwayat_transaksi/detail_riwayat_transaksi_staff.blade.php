@extends('staff.layout.app')

@section('title', 'Detail Riwayat Transaksi - DPM Workshop')

@section('page_title', 'Detail Invoice')
@section('page_subtitle', 'Rincian transaksi & item invoice.')

{{-- Override TOPBAR khusus halaman detail --}}
@section('topbar')
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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">@yield('page_title')</h1>
        <p class="text-xs text-slate-500">@yield('page_subtitle')</p>
      </div>
    </div>

    <div class="flex items-center gap-2">

      {{-- Notifikasi --}}
      <a href="/staff/notifikasi"
         class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
         aria-label="Notifikasi">
        <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
        </svg>
      </a>

      {{-- Tanggal --}}
      <button type="button"
              class="h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
        {{ now()->format('d M Y') }}
      </button>

      {{-- Kembali --}}
      <a href="/staff/riwayat-transaksi"
         class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition px-4 py-2 text-sm font-semibold text-slate-700">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
      </a>
    </div>

  </div>
</header>
@endsection

@section('content')

@php
  // Guard: kalau backend belum siap dan $trx null / tidak diizinkan
  $trx = $trx ?? null;

  $fmt = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');

  if ($trx) {
    $tanggal = $trx->created_at ? \Carbon\Carbon::parse($trx->created_at)->translatedFormat('d F Y') : '-';
    $jam     = $trx->created_at ? \Carbon\Carbon::parse($trx->created_at)->format('H:i') : '-';

    $nominal = (int)($trx->total ?? 0);

    // kalau sistem kamu cuma pemasukan invoice, ini bisa selalu true (warna hijau).
    $isPlus  = (strtolower((string)($trx->tipe ?? $trx->jenis ?? 'masuk')) !== 'keluar');

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

    $metode  = $trx->metode_pembayaran ?? $trx->metode ?? '-';
    $catatan = $trx->catatan ?? '-';

    // items
    $items = $items ?? [];
    $itemsCount = is_countable($items) ? count($items) : 0;
  }
@endphp

<div class="max-w-5xl mx-auto w-full">

  @if(!$trx)
    {{-- Tidak ditemukan / tidak punya akses --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_18px_48px_rgba(2,6,23,0.10)] p-10 text-center">
      <div class="mx-auto h-12 w-12 rounded-2xl border border-slate-200 bg-white grid place-items-center text-slate-500">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
      </div>
      <div class="mt-3 text-sm font-semibold text-slate-900">Invoice tidak ditemukan / Anda tidak punya akses</div>
      <div class="mt-1 text-xs text-slate-500">Pastikan Anda membuka invoice yang Anda buat sendiri.</div>

      <div class="mt-6">
        <a href="/staff/riwayat-transaksi"
           class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                  border border-slate-200 bg-white hover:bg-slate-50 transition">
          Kembali ke Riwayat
        </a>
      </div>
    </div>

  @else

    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

      {{-- HEADER CARD --}}
      <div class="p-5 sm:p-6 border-b border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
          <div>
            <div class="text-xs text-slate-500">Tanggal Transaksi</div>
            <div class="mt-1 text-lg font-semibold text-slate-900">{{ $tanggal }}</div>
            <div class="mt-1 text-xs text-slate-500">{{ $kode }} • {{ $jam }}</div>
          </div>

          <div class="flex items-center gap-3">
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
        <div class="mt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
          <div class="flex items-center gap-3 min-w-0">
            <div class="h-11 w-11 rounded-full grid place-items-center border border-slate-200 bg-gradient-to-br from-slate-50 to-white">
              <span class="text-xs font-bold text-slate-700">{{ $initials ?: 'U' }}</span>
            </div>
            <div class="min-w-0">
              <div class="font-semibold text-slate-900 truncate">{{ $nama ?: 'User' }}</div>
              <div class="text-xs text-slate-500 truncate">
                Metode: <span class="font-semibold text-slate-700">{{ $metode }}</span>
              </div>
            </div>
          </div>

          {{-- Cetak Nota (print view staff) --}}
          <a href="/staff/riwayat-transaksi/{{ $trx->id ?? 0 }}/print"
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
            <div class="text-xs text-slate-500">{{ $itemsCount }} item</div>
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
                    $namaItem = $it->nama_barang ?? $it->barang->nama_barang ?? $it->nama ?? '-';
                    $harga    = (int)($it->harga ?? $it->barang->harga ?? 0);
                    $qty      = (int)($it->qty ?? $it->jumlah ?? 0);
                    $sub      = $harga * $qty;
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

  @endif

</div>
@endsection
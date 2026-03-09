@extends('admin.layout.app')

@section('title', 'DPM Workshop - Admin')

@section('content')

{{-- TOPBAR --}}
<header class="sticky top-0 z-20 border-b border-slate-200 bg-white/80 backdrop-blur">
  <div class="h-16 px-4 sm:px-6 flex items-center justify-between gap-3">
    <div class="flex items-center gap-3 min-w-0">
      <button id="btnSidebar" type="button"
              class="md:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center shrink-0"
              aria-label="Buka menu">
        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <div class="min-w-0">
        <h1 class="text-sm font-semibold tracking-tight text-slate-900 leading-tight">Detail Sesi Opname</h1>
        <p class="text-xs text-slate-500 leading-tight">{{ now()->format('d M Y') }}</p>
      </div>
    </div>
    <div class="flex items-center gap-2 shrink-0">
      @if($opname->isDraft())
        <a href="{{ route('stok_opname.edit', $opname->opname_id) }}"
           class="h-10 px-3 sm:px-4 rounded-xl bg-slate-900 text-white hover:bg-slate-700 transition text-sm font-semibold inline-flex items-center gap-1.5">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
          </svg>
          <span class="hidden sm:inline">Isi Stok</span>
        </a>
      @endif
      <a href="{{ route('stok_opname.index') }}"
         class="h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold inline-flex items-center gap-1.5">
        <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
      </a>
    </div>
  </div>
</header>

<section class="relative p-4 sm:p-6">
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
    <div class="absolute inset-0 opacity-[0.10]"
         style="background-image:
            linear-gradient(to right, rgba(2,6,23,0.05) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(2,6,23,0.05) 1px, transparent 1px);
            background-size: 56px 56px;">
    </div>
  </div>

  <div class="max-w-7xl mx-auto w-full space-y-5">

    {{-- Alert --}}
    @foreach(['success','error','info'] as $type)
      @if(session($type))
        @php $c = ['success'=>'emerald','error'=>'rose','info'=>'blue'][$type]; @endphp
        <div class="flex items-start gap-3 rounded-xl border border-{{ $c }}-200 bg-{{ $c }}-50 px-4 py-3 text-sm text-{{ $c }}-800">
          {{ session($type) }}
        </div>
      @endif
    @endforeach

    {{-- Header Sesi --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_4px_20px_rgba(2,6,23,0.06)] p-5 sm:p-6">
      <div class="flex flex-col gap-3 mb-5">
        <div class="flex items-start justify-between gap-3">
          <div>
            <div class="flex flex-wrap items-center gap-2">
              <h1 class="text-lg font-bold text-slate-800">
                Sesi Opname — {{ $opname->tanggal_opname->format('d M Y') }}
              </h1>
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $opname->status_badge_class }}">
                {{ $opname->status_label }}
              </span>
            </div>
            @if($opname->keterangan)
              <p class="text-sm text-slate-500 mt-1">{{ $opname->keterangan }}</p>
            @endif
          </div>
        </div>
        <div class="text-xs text-slate-500 space-y-0.5">
          <p>Dibuat oleh <strong class="text-slate-700">{{ $opname->nama_pembuat }}</strong> pada {{ $opname->created_at->format('d M Y H:i') }}</p>
          @if($opname->approved_at)
            <p>
              {{ $opname->isDisetujui() ? 'Disetujui' : 'Ditolak' }} oleh
              <strong class="text-slate-700">{{ $opname->nama_approver }}</strong>
              pada {{ $opname->approved_at->format('d M Y H:i') }}
              @if($opname->catatan_approval)
                — <em>"{{ $opname->catatan_approval }}"</em>
              @endif
            </p>
          @endif
        </div>
      </div>

      {{-- Statistik --}}
      @php
        $totalItem  = $opname->details->count();
        $sudahDiisi = $opname->details->filter(fn($d) => !is_null($d->stok_fisik))->count();
        $adaSelisih = $opname->details->filter(fn($d) => $d->has_selisih)->count();
        $balance    = $opname->details->filter(fn($d) => !is_null($d->stok_fisik) && $d->selisih === 0)->count();
      @endphp
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3 text-center">
          <p class="text-2xl font-bold text-slate-800">{{ $totalItem }}</p>
          <p class="text-xs text-slate-500 mt-0.5">Total Barang</p>
        </div>
        <div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3 text-center">
          <p class="text-2xl font-bold text-emerald-600">{{ $sudahDiisi }}</p>
          <p class="text-xs text-slate-500 mt-0.5">Sudah Diisi</p>
        </div>
        <div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3 text-center">
          <p class="text-2xl font-bold text-rose-600">{{ $adaSelisih }}</p>
          <p class="text-xs text-slate-500 mt-0.5">Ada Selisih</p>
        </div>
        <div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3 text-center">
          <p class="text-2xl font-bold text-blue-600">{{ $balance }}</p>
          <p class="text-xs text-slate-500 mt-0.5">Balance</p>
        </div>
      </div>
    </div>

    {{-- Panel Approval --}}
    @if($opname->isMenungguApproval())
    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
      <h2 class="font-semibold text-amber-800 mb-1 flex items-center gap-2 text-sm">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
        Menunggu Persetujuan
      </h2>
      <p class="text-sm text-amber-700 mb-4">
        Terdapat <strong>{{ $adaSelisih }}</strong> barang dengan selisih stok.
        Setelah disetujui, stok sistem akan disesuaikan ke stok fisik secara otomatis.
      </p>
      <div class="flex flex-wrap gap-3">
        <button onclick="document.getElementById('modalApprove').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
          Setujui & Adjust Stok
        </button>
        <button onclick="document.getElementById('modalTolak').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-rose-300 bg-white text-rose-700 text-sm font-medium hover:bg-rose-50 transition">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
          Tolak
        </button>
      </div>
    </div>
    @endif

    {{-- Filter tabel --}}
    <div class="flex items-center gap-3 flex-wrap">
      <span class="text-sm text-slate-600 font-medium">Tampilkan:</span>
      <a href="{{ route('stok_opname.show', $opname->opname_id) }}"
         class="px-3 py-1.5 rounded-lg text-sm font-medium transition
                {{ !$tampilkanSelisih ? 'bg-slate-900 text-white' : 'border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
        Semua Barang
      </a>
      <a href="{{ route('stok_opname.show', $opname->opname_id) }}?hanya_selisih=1"
         class="px-3 py-1.5 rounded-lg text-sm font-medium transition
                {{ $tampilkanSelisih ? 'bg-slate-900 text-white' : 'border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
        Hanya Selisih
        @if($adaSelisih > 0)
          <span class="ml-1 bg-rose-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $adaSelisih }}</span>
        @endif
      </a>
    </div>

    {{-- Tabel detail --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_4px_20px_rgba(2,6,23,0.06)] overflow-hidden">

      {{-- Desktop: tabel --}}
      <div class="hidden sm:block overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50">
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Barang</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Satuan</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok Sistem</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok Fisik</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Selisih</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
              @if($opname->isDisetujui())
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
              @endif
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($details as $detail)
            <tr class="hover:bg-slate-50 transition {{ $detail->has_selisih ? 'bg-rose-50/30' : '' }}">
              <td class="px-5 py-3 text-slate-500 font-mono text-xs">{{ $detail->kode_barang_snapshot }}</td>
              <td class="px-5 py-3 font-medium text-slate-800">{{ $detail->nama_barang_snapshot }}</td>
              <td class="px-5 py-3 text-center text-slate-500 text-xs">{{ $detail->satuan_snapshot }}</td>
              <td class="px-5 py-3 text-center font-semibold text-slate-700">{{ $detail->stok_sistem }}</td>
              <td class="px-5 py-3 text-center font-semibold text-slate-700">
                {{ !is_null($detail->stok_fisik) ? $detail->stok_fisik : '—' }}
              </td>
              <td class="px-5 py-3 text-center">
                @if(!is_null($detail->selisih))
                  <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold {{ $detail->selisih_badge_class }}">
                    {{ $detail->selisih_label }}
                  </span>
                @else
                  <span class="text-slate-400 text-xs">—</span>
                @endif
              </td>
              <td class="px-5 py-3 text-slate-500 text-xs">{{ $detail->keterangan ?? '—' }}</td>
              @if($opname->isDisetujui())
              <td class="px-5 py-3 text-center">
                @if($detail->item_status === 'adjusted')
                  <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Disesuaikan</span>
                @elseif($detail->item_status === 'balance')
                  <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Balance</span>
                @endif
              </td>
              @endif
            </tr>

            {{-- Riwayat mutasi (saat menunggu approval) --}}
            @if($opname->isMenungguApproval() && $detail->has_selisih && isset($riwayatSelisih[$detail->barang_id]))
            <tr class="bg-slate-50">
              <td colspan="{{ $opname->isDisetujui() ? 8 : 7 }}" class="px-5 py-3">
                <div class="text-xs text-slate-600">
                  <p class="font-semibold text-slate-700 mb-2">Riwayat mutasi terakhir — {{ $detail->nama_barang_snapshot }}:</p>
                  <div class="space-y-1 max-h-32 overflow-y-auto">
                    @foreach($riwayatSelisih[$detail->barang_id]->take(5) as $riwayat)
                    <div class="flex items-center gap-3 text-slate-500">
                      <span class="w-28 shrink-0">{{ \Carbon\Carbon::parse($riwayat->tanggal_riwayat_stok)->format('d M Y') }}</span>
                      <span class="w-16 shrink-0 font-medium {{ $riwayat->barang_masuk_id ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $riwayat->barang_masuk_id ? '+ Masuk' : '- Keluar' }}
                      </span>
                      <span>{{ $riwayat->stok_awal }} → {{ $riwayat->stok_akhir }}</span>
                      <span class="text-slate-400">oleh {{ $riwayat->username_snapshot ?? '-' }}</span>
                    </div>
                    @endforeach
                  </div>
                </div>
              </td>
            </tr>
            @endif

            @empty
            <tr>
              <td colspan="7" class="px-5 py-12 text-center text-slate-400 text-sm">
                Tidak ada barang yang ditampilkan.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Mobile: card list --}}
      <div class="sm:hidden divide-y divide-slate-100">
        @forelse($details as $detail)
        <div class="p-4 {{ $detail->has_selisih ? 'bg-rose-50/40' : '' }}">
          <div class="flex items-start justify-between gap-2 mb-2">
            <div class="min-w-0">
              <p class="text-sm font-semibold text-slate-800 leading-tight">{{ $detail->nama_barang_snapshot }}</p>
              <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $detail->kode_barang_snapshot }}</p>
            </div>
            @if(!is_null($detail->selisih))
              <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold {{ $detail->selisih_badge_class }} shrink-0">
                {{ $detail->selisih_label }}
              </span>
            @else
              <span class="text-slate-400 text-xs shrink-0">Belum diisi</span>
            @endif
          </div>
          <div class="grid grid-cols-3 gap-2 text-xs mt-3">
            <div class="rounded-lg bg-slate-50 border border-slate-100 px-3 py-2 text-center">
              <p class="text-slate-400 mb-0.5">Sistem</p>
              <p class="font-bold text-slate-700">{{ $detail->stok_sistem }}</p>
            </div>
            <div class="rounded-lg bg-slate-50 border border-slate-100 px-3 py-2 text-center">
              <p class="text-slate-400 mb-0.5">Fisik</p>
              <p class="font-bold text-slate-700">{{ !is_null($detail->stok_fisik) ? $detail->stok_fisik : '—' }}</p>
            </div>
            <div class="rounded-lg bg-slate-50 border border-slate-100 px-3 py-2 text-center">
              <p class="text-slate-400 mb-0.5">Satuan</p>
              <p class="font-bold text-slate-700">{{ $detail->satuan_snapshot }}</p>
            </div>
          </div>
          @if($detail->keterangan)
            <p class="text-xs text-slate-500 mt-2 italic">{{ $detail->keterangan }}</p>
          @endif
          @if($opname->isDisetujui() && $detail->item_status)
            <div class="mt-2">
              @if($detail->item_status === 'adjusted')
                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Disesuaikan</span>
              @elseif($detail->item_status === 'balance')
                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Balance</span>
              @endif
            </div>
          @endif

          {{-- Riwayat mutasi mobile (saat menunggu approval) --}}
          @if($opname->isMenungguApproval() && $detail->has_selisih && isset($riwayatSelisih[$detail->barang_id]))
          <div class="mt-3 rounded-lg bg-slate-50 border border-slate-200 p-3">
            <p class="text-xs font-semibold text-slate-600 mb-2">Riwayat mutasi terakhir:</p>
            <div class="space-y-1.5 max-h-28 overflow-y-auto">
              @foreach($riwayatSelisih[$detail->barang_id]->take(5) as $riwayat)
              <div class="flex items-center gap-2 text-xs text-slate-500">
                <span class="shrink-0">{{ \Carbon\Carbon::parse($riwayat->tanggal_riwayat_stok)->format('d M Y') }}</span>
                <span class="font-medium {{ $riwayat->barang_masuk_id ? 'text-emerald-600' : 'text-rose-600' }}">
                  {{ $riwayat->barang_masuk_id ? '+Masuk' : '-Keluar' }}
                </span>
                <span>{{ $riwayat->stok_awal }}→{{ $riwayat->stok_akhir }}</span>
              </div>
              @endforeach
            </div>
          </div>
          @endif
        </div>
        @empty
        <div class="py-12 text-center text-slate-400 text-sm">
          Tidak ada barang yang ditampilkan.
        </div>
        @endforelse
      </div>

    </div>

  </div>
</section>

{{-- Modal Approve --}}
<div id="modalApprove" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
    <h2 class="text-lg font-bold text-slate-800 mb-1">Setujui Stok Opname</h2>
    <p class="text-sm text-slate-500 mb-4">
      Stok sistem untuk <strong>{{ $adaSelisih }}</strong> barang akan disesuaikan ke stok fisik.
      Tindakan ini tidak dapat dibatalkan.
    </p>
    <form method="POST" action="{{ route('stok_opname.approve', $opname->opname_id) }}">
      @csrf
      <div class="mb-4">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Catatan (opsional)</label>
        <textarea name="catatan_approval" rows="2" placeholder="Misal: Sudah dicek ulang, semua barang balance"
                  class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 resize-none"></textarea>
      </div>
      <div class="flex gap-3">
        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
          Ya, Setujui
        </button>
        <button type="button" onclick="document.getElementById('modalApprove').classList.add('hidden')"
                class="flex-1 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition">
          Batal
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Tolak --}}
<div id="modalTolak" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
    <h2 class="text-lg font-bold text-slate-800 mb-1">Tolak Stok Opname</h2>
    <p class="text-sm text-slate-500 mb-4">Sesi ini akan ditolak dan stok tidak akan diubah. Wajib isi alasan penolakan.</p>
    <form method="POST" action="{{ route('stok_opname.tolak', $opname->opname_id) }}">
      @csrf
      <div class="mb-4">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Alasan Penolakan <span class="text-rose-500">*</span></label>
        <textarea name="catatan_approval" rows="3" required placeholder="Jelaskan alasan penolakan..."
                  class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 resize-none"></textarea>
      </div>
      <div class="flex gap-3">
        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-rose-600 text-white text-sm font-medium hover:bg-rose-700 transition">
          Ya, Tolak
        </button>
        <button type="button" onclick="document.getElementById('modalTolak').classList.add('hidden')"
                class="flex-1 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition">
          Batal
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
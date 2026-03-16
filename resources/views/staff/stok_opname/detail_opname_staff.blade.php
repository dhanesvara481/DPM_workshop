@extends('staff.layout.app')

@section('title', 'DPM Workshop - Staff')
@section('page_title', 'Detail Sesi Opname')
@section('page_subtitle', $opname->tanggal_opname->format('d M Y'))

@section('content')

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
  <div class="rounded-2xl border border-slate-700 bg-slate-900 shadow-[0_4px_20px_rgba(2,6,23,0.20)] p-5 sm:p-6">
    <div class="flex flex-col gap-3 mb-5">
      <div class="flex items-start justify-between gap-3">
        <div>
          <div class="flex flex-wrap items-center gap-2">
            <h1 class="text-lg font-bold text-white">
              Sesi Opname — {{ $opname->tanggal_opname->format('d M Y') }}
            </h1>
            {{-- Badge status disesuaikan ke versi putih transparan --}}
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/10 border border-white/20 text-white/80">
              {{ $opname->status_label }}
            </span>
          </div>
          @if($opname->keterangan)
            <p class="text-sm text-white/50 mt-1">{{ $opname->keterangan }}</p>
          @endif
        </div>
        <div class="flex items-center gap-2 shrink-0">
          @if($opname->isDraft())
            <a href="{{ route('stok_opname.ubahOpnameStaff', $opname->opname_id) }}"
               class="h-9 px-3 sm:px-4 rounded-xl bg-white text-slate-900 hover:bg-slate-100 transition text-sm font-semibold inline-flex items-center gap-1.5">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
              <span class="hidden sm:inline">Isi Stok</span>
            </a>
          @endif
          <a href="{{ route('stok_opname.daftarOpnameStaff') }}"
             class="h-9 px-4 rounded-xl border border-white/20 bg-white/10 hover:bg-white/20 transition text-sm font-semibold inline-flex items-center gap-1.5 text-white/80 hover:text-white">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
          </a>
        </div>
      </div>
      <div class="text-xs text-white/40 space-y-0.5">
        <p>Dibuat oleh <strong class="text-white/70">{{ $opname->nama_pembuat }}</strong> pada {{ $opname->created_at->format('d M Y H:i') }}</p>
        @if($opname->approved_at)
          <p>
            {{ $opname->isDisetujui() ? 'Disetujui' : 'Ditolak' }} oleh admin
            pada {{ $opname->approved_at->format('d M Y H:i') }}
            @if($opname->catatan_approval)
              — <em class="{{ $opname->isDitolak() ? 'text-rose-400' : 'text-white/60' }}">"{{ $opname->catatan_approval }}"</em>
            @endif
          </p>
        @endif
      </div>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
      <div class="rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-center">
        <p class="text-2xl font-bold text-white">{{ $totalItem }}</p>
        <p class="text-xs text-white/40 mt-0.5">Total Barang</p>
      </div>
      <div class="rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-center">
        <p class="text-2xl font-bold text-emerald-400">{{ $sudahDiisi }}</p>
        <p class="text-xs text-white/40 mt-0.5">Sudah Diisi</p>
      </div>
      <div class="rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-center">
        <p class="text-2xl font-bold text-rose-400">{{ $adaSelisih }}</p>
        <p class="text-xs text-white/40 mt-0.5">Ada Selisih</p>
      </div>
      <div class="rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-center">
        <p class="text-2xl font-bold text-blue-400">{{ $balance }}</p>
        <p class="text-xs text-white/40 mt-0.5">Balance</p>
      </div>
    </div>
  </div>

  {{-- Banner status --}}
  @if($opname->isMenungguApproval())
  <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 flex items-start gap-2">
    <svg class="h-5 w-5 shrink-0 text-amber-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    Data sudah kamu submit dan sedang menunggu persetujuan dari admin. Kamu tidak bisa mengubah data lagi.
  </div>
  @endif

  @if($opname->isDisetujui())
  <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 flex items-start gap-2">
    <svg class="h-5 w-5 shrink-0 text-emerald-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    Sesi opname ini telah disetujui admin. Stok sistem sudah disesuaikan.
  </div>
  @endif

  @if($opname->isDitolak())
  <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 flex items-start gap-2">
    <svg class="h-5 w-5 shrink-0 text-rose-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    Sesi ini ditolak oleh admin. Hubungi admin untuk informasi lebih lanjut.
  </div>
  @endif

  {{-- Filter --}}
  <div class="flex items-center gap-3 flex-wrap">
    <span class="text-sm text-slate-600 font-medium">Tampilkan:</span>
    <a href="{{ route('stok_opname.detailOpnameStaff', $opname->opname_id) }}"
       class="px-3 py-1.5 rounded-lg text-sm font-medium transition
              {{ !$tampilkanSelisih ? 'bg-slate-900 text-white' : 'border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
      Semua Barang
    </a>
    <a href="{{ route('stok_opname.detailOpnameStaff', $opname->opname_id) }}?hanya_selisih=1"
       class="px-3 py-1.5 rounded-lg text-sm font-medium transition
              {{ $tampilkanSelisih ? 'bg-slate-900 text-white' : 'border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
      Hanya Selisih
      @if($adaSelisih > 0)
        <span class="ml-1 bg-rose-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $adaSelisih }}</span>
      @endif
    </a>
  </div>

  {{-- Tabel --}}
  <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_4px_20px_rgba(2,6,23,0.06)] overflow-hidden">

    {{-- Desktop --}}
    <div class="hidden sm:block overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-100 bg-slate-50">
            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-[60px]">No</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Barang</th>
            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Satuan</th>
            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok Sistem</th>
            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok Fisik</th>
            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Selisih</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($details as $detail)
          <tr class="hover:bg-slate-50 transition {{ $detail->has_selisih ? 'bg-rose-50/30' : '' }}">
            <td class="px-5 py-3 text-slate-400 text-xs">{{ $details->firstItem() + $loop->index }}</td>
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
          </tr>
          @empty
          <tr>
            <td colspan="8" class="px-5 py-12 text-center text-slate-400 text-sm">
              Tidak ada barang yang ditampilkan.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Mobile --}}
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
      </div>
      @empty
      <div class="py-12 text-center text-slate-400 text-sm">
        Tidak ada barang yang ditampilkan.
      </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if($details->hasPages())
      <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between gap-3 flex-wrap">
        <p class="text-xs text-slate-500">
          Menampilkan {{ $details->firstItem() }}–{{ $details->lastItem() }} dari {{ $details->total() }} barang
        </p>
        <div class="flex items-center gap-1">
          @if ($details->onFirstPage())
            <span class="h-9 w-9 rounded-xl border border-slate-200 bg-slate-50 grid place-items-center text-slate-300 text-sm cursor-not-allowed">‹</span>
          @else
            <a href="{{ $details->previousPageUrl() }}" class="h-9 w-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center text-slate-700 text-sm">‹</a>
          @endif
          @foreach ($details->getUrlRange(max(1, $details->currentPage() - 2), min($details->lastPage(), $details->currentPage() + 2)) as $page => $url)
            @if ($page == $details->currentPage())
              <span class="h-9 w-9 rounded-xl bg-slate-900 text-white grid place-items-center text-sm font-semibold">{{ $page }}</span>
            @else
              <a href="{{ $url }}" class="h-9 w-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center text-slate-700 text-sm">{{ $page }}</a>
            @endif
          @endforeach
          @if ($details->hasMorePages())
            <a href="{{ $details->nextPageUrl() }}" class="h-9 w-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center text-slate-700 text-sm">›</a>
          @else
            <span class="h-9 w-9 rounded-xl border border-slate-200 bg-slate-50 grid place-items-center text-slate-300 text-sm cursor-not-allowed">›</span>
          @endif
        </div>
      </div>
    @endif

  </div>
</div>

@endsection
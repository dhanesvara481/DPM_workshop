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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900 leading-tight">Buat Sesi Opname</h1>
        <p class="text-xs text-slate-500 leading-tight">{{ now()->format('d M Y') }}</p>
      </div>
    </div>
    <div class="shrink-0">
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

  <div class="max-w-2xl mx-auto w-full space-y-5">

    {{-- Peringatan draft aktif --}}
    @if($draftAktif)
      <div class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
        <svg class="h-5 w-5 shrink-0 text-amber-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
          Masih ada sesi opname yang belum selesai
          ({{ $draftAktif->tanggal_opname->format('d M Y') }} — <strong>{{ $draftAktif->status_label }}</strong>).
          <a href="{{ route('stok_opname.show', $draftAktif->opname_id) }}" class="underline font-medium ml-1">
            Lihat sesi tersebut
          </a>
        </div>
      </div>
    @endif

    {{-- Form --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_4px_20px_rgba(2,6,23,0.06)] p-5 sm:p-6">
      <div class="mb-5">
        <h2 class="text-lg font-bold text-slate-800">Buat Sesi Stok Opname</h2>
        <p class="text-sm text-slate-500 mt-1">
          Sistem akan menyimpan snapshot stok semua barang saat ini sebagai acuan perbandingan.
        </p>
      </div>

      <form method="POST" action="{{ route('stok_opname.store') }}" class="space-y-5">
        @csrf

        {{-- Tanggal --}}
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">
            Tanggal Opname <span class="text-rose-500">*</span>
          </label>
          <input type="date" name="tanggal_opname"
                 value="{{ old('tanggal_opname', date('Y-m-d')) }}"
                 class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800
                        focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent
                        @error('tanggal_opname') border-rose-400 @enderror">
          @error('tanggal_opname')
            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Keterangan --}}
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Keterangan</label>
          <textarea name="keterangan" rows="3"
                    placeholder="Contoh: Opname bulanan Maret 2026, Opname akhir tahun, dll."
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800
                           focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent resize-none
                           @error('keterangan') border-rose-400 @enderror">{{ old('keterangan') }}</textarea>
          @error('keterangan')
            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Info --}}
        <div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3 text-sm text-slate-600">
          <p class="font-medium text-slate-700 mb-2">Yang akan terjadi setelah klik Buat:</p>
          <ul class="space-y-1.5 text-slate-500 text-xs">
            <li class="flex items-start gap-2">
              <span class="mt-1 h-1.5 w-1.5 rounded-full bg-slate-400 shrink-0"></span>
              Semua barang beserta stok sistem saat ini akan di-snapshot
            </li>
            <li class="flex items-start gap-2">
              <span class="mt-1 h-1.5 w-1.5 rounded-full bg-slate-400 shrink-0"></span>
              Kamu bisa mengisi stok fisik hasil hitung manual
            </li>
            <li class="flex items-start gap-2">
              <span class="mt-1 h-1.5 w-1.5 rounded-full bg-slate-400 shrink-0"></span>
              Sistem menghitung selisih otomatis untuk setiap barang
            </li>
          </ul>
        </div>

        <div class="flex flex-wrap gap-3 pt-1">
          <button type="submit"
                  class="h-10 px-5 rounded-xl bg-slate-900 text-white text-sm font-medium hover:bg-slate-700 transition shadow-sm">
            Buat Sesi Opname
          </button>
          <a href="{{ route('stok_opname.index') }}"
             class="h-10 px-5 rounded-xl border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition inline-flex items-center">
            Batal
          </a>
        </div>
      </form>
    </div>

  </div>
</section>

@endsection
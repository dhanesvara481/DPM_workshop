@extends('admin.layout.app')

@section('title', 'DPM Workshop - Admin')

@section('content')
<div class="px-4 md:px-8 py-8 max-w-2xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('stok_opname.index') }}" class="hover:text-slate-800 transition">Stok Opname</a>
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-800 font-medium">Buat Sesi Baru</span>
  </div>

  {{-- Peringatan jika ada draft aktif --}}
  @if($draftAktif)
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
      <svg class="h-5 w-5 shrink-0 text-amber-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 00-3.42 0z"/>
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
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
    <h1 class="text-lg font-bold text-slate-800 mb-1">Buat Sesi Stok Opname</h1>
    <p class="text-sm text-slate-500 mb-6">
      Sistem akan menyimpan snapshot stok semua barang saat ini sebagai acuan perbandingan.
    </p>

    <form method="POST" action="{{ route('stok_opname.store') }}">
      @csrf

      <div class="space-y-5">

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
          <p class="font-medium text-slate-700 mb-1">Yang akan terjadi setelah klik Buat:</p>
          <ul class="space-y-1 text-slate-500">
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

      </div>

      <div class="mt-6 flex items-center gap-3">
        <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-medium hover:bg-slate-700 transition shadow-sm">
          Buat Sesi Opname
        </button>
        <a href="{{ route('stok_opname.index') }}"
           class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition">
          Batal
        </a>
      </div>
    </form>
  </div>

</div>
@endsection
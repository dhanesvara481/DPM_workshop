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
      <a href="{{ route('stok_opname.daftarOpname') }}"
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
          <a href="{{ route('stok_opname.detailOpname', $draftAktif->opname_id) }}" class="underline font-medium ml-1">
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
          Sistem akan menyimpan snapshot stok semua barang saat ini. Kamu bisa assign staff untuk mengisi stok fisik.
        </p>
      </div>

      <form method="POST" action="{{ route('stok_opname.simpanOpname') }}" class="space-y-5">
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

        {{-- Assign Staff --}}
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">
            Assign ke Staff
            <span class="text-slate-400 font-normal text-xs ml-1">(opsional — biarkan kosong jika admin sendiri yang isi)</span>
          </label>
          <select name="assigned_to"
                  class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800
                         focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent
                         @error('assigned_to') border-rose-400 @enderror">
            <option value="">— Admin isi sendiri —</option>
            @foreach($staffList as $staff)
              <option value="{{ $staff->user_id }}" {{ old('assigned_to') == $staff->user_id ? 'selected' : '' }}>
                {{ $staff->username }}
              </option>
            @endforeach
          </select>
          @error('assigned_to')
            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
          @enderror
          {{-- Info box tergantung pilihan --}}
          <div id="infoAssign" class="hidden mt-2 rounded-lg bg-blue-50 border border-blue-200 px-3 py-2 text-xs text-blue-700">
            Staff yang dipilih akan mendapat tugas mengisi stok fisik dan melakukan submit untuk approval.
            Admin tetap yang melakukan approve/tolak.
          </div>
          <div id="infoAdmin" class="mt-2 rounded-lg bg-slate-50 border border-slate-200 px-3 py-2 text-xs text-slate-500">
            Admin akan mengisi stok fisik dan submit langsung.
          </div>
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

        {{-- Info alur --}}
        <div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3 text-sm text-slate-600">
          <p class="font-medium text-slate-700 mb-2">Alur proses:</p>
          <ol class="space-y-1.5 text-slate-500 text-xs list-none">
            <li class="flex items-start gap-2">
              <span class="mt-0.5 h-4 w-4 rounded-full bg-slate-200 text-slate-600 text-[10px] font-bold grid place-items-center shrink-0">1</span>
              Admin buat sesi → stok semua barang di-freeze (tidak bisa ada transaksi stok)
            </li>
            <li class="flex items-start gap-2">
              <span class="mt-0.5 h-4 w-4 rounded-full bg-slate-200 text-slate-600 text-[10px] font-bold grid place-items-center shrink-0">2</span>
              Staff yang di-assign (atau admin sendiri) input stok fisik hasil hitung manual
            </li>
            <li class="flex items-start gap-2">
              <span class="mt-0.5 h-4 w-4 rounded-full bg-slate-200 text-slate-600 text-[10px] font-bold grid place-items-center shrink-0">3</span>
              Staff/admin submit → status berubah jadi <em>Menunggu Approval</em>
            </li>
            <li class="flex items-start gap-2">
              <span class="mt-0.5 h-4 w-4 rounded-full bg-slate-200 text-slate-600 text-[10px] font-bold grid place-items-center shrink-0">4</span>
              Admin approve → stok otomatis disesuaikan + freeze dicabut<br>
              Admin tolak → stok tidak berubah, sesi bisa dihapus + freeze dicabut
            </li>
          </ol>
        </div>

        <div class="flex flex-wrap gap-3 pt-1">
          <button type="submit"
                  class="h-10 px-5 rounded-xl bg-slate-900 text-white text-sm font-medium hover:bg-slate-700 transition shadow-sm">
            Buat Sesi Opname
          </button>
          <a href="{{ route('stok_opname.daftarOpname') }}"
             class="h-10 px-5 rounded-xl border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition inline-flex items-center">
            Batal
          </a>
        </div>
      </form>
    </div>

  </div>
</section>

@push('scripts')
<script>
(function () {
  const sel       = document.querySelector('select[name="assigned_to"]');
  const infoAssign = document.getElementById('infoAssign');
  const infoAdmin  = document.getElementById('infoAdmin');

  function toggle() {
    if (sel.value) {
      infoAssign.classList.remove('hidden');
      infoAdmin.classList.add('hidden');
    } else {
      infoAssign.classList.add('hidden');
      infoAdmin.classList.remove('hidden');
    }
  }

  sel.addEventListener('change', toggle);
  toggle();
})();
</script>
@endpush

@endsection
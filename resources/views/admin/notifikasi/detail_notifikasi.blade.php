{{-- resources/views/admin/notifikasi/show.blade.php --}}
@extends('admin.layout.app')

@section('title', 'Detail Notifikasi - DPM Workshop')

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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Detail Notifikasi</h1>
        <p class="text-xs text-slate-500">Informasi lengkap notifikasi</p>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <a href="/tampilan_notifikasi"
         class="h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold inline-flex items-center">
        Kembali
      </a>
      <button type="button"
              class="h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
        {{ now()->format('d M Y') }}
      </button>
    </div>

  </div>
</header>

<section class="relative p-4 sm:p-6">
  {{-- BACKGROUND --}}
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
    <div class="absolute inset-0 opacity-[0.10]"
         style="background-image:
            linear-gradient(to right, rgba(2,6,23,0.05) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(2,6,23,0.05) 1px, transparent 1px);
            background-size: 56px 56px;">
    </div>
  </div>

  <div class="max-w-[980px] mx-auto w-full">

    @php
      /**
       * Controller ideal:
       * $notif = Notifikasi::findOrFail($id);
       */

      // Support object / array
      $isObj = is_object($notif ?? null);

      $id     = $isObj ? ($notif->notifikasi_id ?? null) : (($notif['notifikasi_id'] ?? null) ?? null);
      $judul  = $isObj ? ($notif->judul_notif ?? '') : (($notif['judul_notif'] ?? '') ?? '');
      $jenis  = $isObj ? ($notif->jenis_notifikasi ?? '') : (($notif['jenis_notifikasi'] ?? '') ?? '');
      $pesan  = $isObj ? ($notif->isi_pesan ?? '') : (($notif['isi_pesan'] ?? '') ?? '');
      $tglD   = $isObj ? ($notif->tanggal_dibuat ?? null) : (($notif['tanggal_dibuat'] ?? null) ?? null);
      $tglK   = $isObj ? ($notif->tanggal_dikirim ?? null) : (($notif['tanggal_dikirim'] ?? null) ?? null);

      $type = strtolower((string)$jenis);

      $iconWrap = match(true) {
        str_contains($type, 'stok')      => 'bg-amber-100 border-amber-200 text-amber-700',
        str_contains($type, 'jadwal')    => 'bg-emerald-100 border-emerald-200 text-emerald-700',
        str_contains($type, 'invoice')   => 'bg-sky-100 border-sky-200 text-sky-700',
        str_contains($type, 'transaksi') => 'bg-fuchsia-100 border-fuchsia-200 text-fuchsia-700',
        default                          => 'bg-slate-100 border-slate-200 text-slate-700',
      };

      $iconSvg = match(true) {
        str_contains($type, 'stok') => '
          <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8 4-8-4"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10l8 4 8-4V7"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v10"/>
          </svg>
        ',
        str_contains($type, 'jadwal') => '
          <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        ',
        str_contains($type, 'invoice') => '
          <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10a2 2 0 012 2v16l-2-1-2 1-2-1-2 1-2-1-2 1V5a2 2 0 012-2z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h6M9 16h4"/>
          </svg>
        ',
        default => '
          <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
          </svg>
        ',
      };

      $fmtTanggal = function($val){
        if (!$val) return '-';
        try { return \Carbon\Carbon::parse($val)->translatedFormat('d F Y'); }
        catch (\Throwable $e) { return (string) $val; }
      };
    @endphp

    @if (!($notif ?? null))
      {{-- NOT FOUND --}}
      <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
                  shadow-[0_16px_44px_rgba(2,6,23,0.08)] overflow-hidden">
        <div class="px-6 py-6">
          <div class="text-xl font-extrabold text-slate-900">Notifikasi tidak ditemukan</div>
          <p class="mt-2 text-sm text-slate-600">
            Data notifikasi belum tersedia / id tidak valid.
          </p>
          <div class="mt-5">
            <a href="/tampilan_notifikasi"
               class="inline-flex items-center justify-center h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
              Kembali ke Notifikasi
            </a>
          </div>
        </div>

        <div class="text-xs text-slate-400 pt-4 pb-6 px-6">
          © DPM Workshop 2025
        </div>
      </div>
    @else
      <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
                  shadow-[0_16px_44px_rgba(2,6,23,0.08)] overflow-hidden">

        {{-- Header bar --}}
        <div class="px-6 py-5">
          <div class="text-2xl font-extrabold tracking-tight text-slate-900">Detail Notifikasi</div>
          <div class="mt-3 h-1.5 bg-slate-900"></div>
          <p class="mt-3 text-sm text-slate-600">
            Notifikasi otomatis dari sistem.
          </p>
        </div>

        {{-- Detail card --}}
        <div class="px-6 pb-6">
          <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex flex-col sm:flex-row sm:items-start gap-4">

              <div class="h-14 w-14 rounded-full border grid place-items-center shrink-0 {{ $iconWrap }}">
                {!! $iconSvg !!}
              </div>

              <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                  <div class="text-xl sm:text-2xl font-extrabold text-slate-900">
                    {{ $judul ?: 'Judul Notif' }}
                  </div>
                  <div class="text-xl sm:text-2xl font-extrabold text-slate-900">|</div>
                  <div class="text-xl sm:text-2xl font-extrabold text-slate-900">
                    {{ $jenis ?: 'Jenis Notif' }}
                  </div>
                </div>

                <div class="mt-3 text-base leading-relaxed text-slate-800">
                  {{ $pesan ?: '-' }}
                </div>

                <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-xs font-semibold text-slate-500">Tanggal Dibuat</div>
                    <div class="mt-1 text-sm font-bold text-slate-900">{{ $fmtTanggal($tglD) }}</div>
                  </div>
                  <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-xs font-semibold text-slate-500">Tanggal Dikirim</div>
                    <div class="mt-1 text-sm font-bold text-slate-900">{{ $fmtTanggal($tglK) }}</div>
                  </div>
                </div>

                <div class="mt-5 flex flex-wrap gap-2">
                  <a href="/tampilan_notifikasi"
                     class="inline-flex items-center justify-center h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                    Kembali
                  </a>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="text-xs text-slate-400 pt-6 pb-6 px-6">
          © DPM Workshop 2025
        </div>
      </div>
    @endif

  </div>
</section>

{{-- Sidebar JS (kalau layout kamu belum punya) --}}
<script>
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const btnSidebar = document.getElementById('btnSidebar');
  const btnCloseSidebar = document.getElementById('btnCloseSidebar');

  const openSidebar = () => {
    sidebar?.classList.remove('-translate-x-full');
    overlay?.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  };
  const closeSidebar = () => {
    sidebar?.classList.add('-translate-x-full');
    overlay?.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  };

  btnSidebar?.addEventListener('click', openSidebar);
  btnCloseSidebar?.addEventListener('click', closeSidebar);
  overlay?.addEventListener('click', closeSidebar);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeSidebar();
  });

  const syncOnResize = () => {
    if (window.innerWidth >= 768) {
      overlay?.classList.add('hidden');
      sidebar?.classList.remove('-translate-x-full');
      document.body.classList.remove('overflow-hidden');
    } else {
      sidebar?.classList.add('-translate-x-full');
    }
  };
  window.addEventListener('resize', syncOnResize);
  syncOnResize();
</script>

@endsection

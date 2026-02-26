{{-- resources/views/admin/notifikasi/index.blade.php --}}
@extends('admin.layout.app')

@section('title', 'Notifikasi - DPM Workshop')

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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Notifikasi</h1>
        <p class="text-xs text-slate-500">Notifikasi otomatis dari sistem</p>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <a href="/tampilan_dashboard"
         class="h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold inline-flex items-center">
        <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
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

    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
                shadow-[0_16px_44px_rgba(2,6,23,0.08)] overflow-hidden">

      {{-- Header bar --}}
      <div class="px-6 py-5">
        <div class="text-2xl font-extrabold tracking-tight text-slate-900">Notifikasi</div>
        <div class="mt-3 h-1.5 bg-slate-900"></div>
        <p class="mt-3 text-sm text-slate-600">
          Daftar notifikasi otomatis (stok menipis, jadwal shift besok, dll).
        </p>
      </div>

      @php
        /**
         * BACKEND RECOMMENDED:
         * $notifs = $notifs ?? \App\Models\Notifikasi::latest()->get();
         *
         * Kalau dari controller sudah kirim $notifs, bagian dummy ini aman (tidak kepakai).
         */
        $notifs = $notifs ?? [
          [
            'notifikasi_id'     => 1,
            'judul_notif'       => 'Stok Menipis',
            'jenis_notifikasi'  => 'Stok',
            'isi_pesan'         => 'Barang "Oli Mesin A" tersisa 2 pcs. Segera restok untuk menghindari kehabisan.',
            'tanggal_dibuat'    => '2025-12-30',
          ],
          [
            'notifikasi_id'     => 2,
            'judul_notif'       => 'Shift Besok',
            'jenis_notifikasi'  => 'Jadwal',
            'isi_pesan'         => 'Besok ada shift pagi untuk Asep (08:00 - 16:00). Pastikan kesiapan operasional.',
            'tanggal_dibuat'    => '2025-12-29',
          ],
        ];
      @endphp

      {{-- LIST --}}
      <div class="px-6 pb-6">
        @if (empty($notifs) || (is_countable($notifs) && count($notifs) === 0))
          {{-- EMPTY STATE --}}
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center">
            <div class="mt-1 font-semibold text-slate-900">Belum ada notifikasi</div>
            <div class="mt-1 text-sm text-slate-600">
              Notifikasi akan muncul otomatis dari sistem (contoh: stok menipis, shift besok, dll).
            </div>
          </div>
        @else
          <div class="space-y-6">
            @foreach ($notifs as $n)

              @php
                // Support array atau Eloquent object
                $id     = is_array($n) ? ($n['notifikasi_id'] ?? null) : ($n->notifikasi_id ?? null);
                $judul  = is_array($n) ? ($n['judul_notif'] ?? '') : ($n->judul_notif ?? '');
                $jenis  = is_array($n) ? ($n['jenis_notifikasi'] ?? '') : ($n->jenis_notifikasi ?? '');
                $pesan  = is_array($n) ? ($n['isi_pesan'] ?? '') : ($n->isi_pesan ?? '');
                $tgl    = is_array($n) ? ($n['tanggal_dibuat'] ?? null) : ($n->tanggal_dibuat ?? null);

                $type = strtolower($jenis);

                $iconWrap = match(true) {
                  str_contains($type, 'stok')     => 'bg-amber-100 border-amber-200 text-amber-700',
                  str_contains($type, 'jadwal')   => 'bg-emerald-100 border-emerald-200 text-emerald-700',
                  str_contains($type, 'invoice')  => 'bg-sky-100 border-sky-200 text-sky-700',
                  str_contains($type, 'transaksi')=> 'bg-fuchsia-100 border-fuchsia-200 text-fuchsia-700',
                  default                         => 'bg-slate-100 border-slate-200 text-slate-700',
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
              @endphp

              <a href="/detail_notifikasi/{{ $id }}"
                 class="group block rounded-2xl hover:bg-slate-50/60 transition p-2 -m-2">
                <div class="flex gap-4">
                  <div class="h-14 w-14 rounded-full border grid place-items-center shrink-0 {{ $iconWrap }}">
                    {!! $iconSvg !!}
                  </div>

                  <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                      <div class="text-lg font-extrabold text-slate-900 group-hover:underline">
                        {{ $judul ?: 'Judul Notif' }}
                      </div>
                      <div class="text-lg font-extrabold text-slate-900">|</div>
                      <div class="text-lg font-extrabold text-slate-900">
                        {{ $jenis ?: 'Jenis Notif' }}
                      </div>
                    </div>

                    <div class="mt-1 text-base leading-relaxed text-slate-800">
                      {{ $pesan ?: '-' }}
                    </div>

                    <div class="mt-2 text-sm font-bold text-slate-900">
                      {{ $tgl ? \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') : '' }}
                    </div>
                  </div>
                </div>
              </a>

            @endforeach
          </div>
        @endif
      </div>

      <div class="text-xs text-slate-400 pt-6 pb-6 px-6">
        © DPM Workshop 2025
      </div>
    </div>

  </div>
</section>

@endsection
  
{{-- resources/views/admin/notifikasi/tampilan_notifikasi.blade.php --}}
@extends('admin.layout.app')

@section('title', 'Notifikasi - DPM Workshop')

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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900 leading-tight">Notifikasi</h1>
      </div>
    </div>
    <div class="shrink-0">
      <a href="/tampilan_dashboard"
         class="h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold inline-flex items-center gap-1.5">
        <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
      </a>
      <button type="button"
            class="h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold whitespace-nowrap shrink-0">
      {{ now()->format('d M Y') }}
    </button>
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

  <div class="max-w-[980px] mx-auto w-full">
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
                shadow-[0_16px_44px_rgba(2,6,23,0.08)] overflow-hidden">

      {{-- Header --}}
      <div class="px-6 py-5">
        <div class="text-2xl font-extrabold tracking-tight text-slate-900">Notifikasi</div>
        <div class="mt-3 h-1.5 bg-slate-900"></div>
        <p class="mt-3 text-sm text-slate-600">
          Daftar notifikasi otomatis (stok menipis, jadwal shift besok, dll).
        </p>
      </div>

      @php
        $isPaginator = $notifs instanceof \Illuminate\Pagination\LengthAwarePaginator
                    || $notifs instanceof \Illuminate\Pagination\Paginator;
        $items = $isPaginator ? $notifs : collect($notifs ?? []);
      @endphp

      @if ($isPaginator && $notifs->total() > 0)
        <div class="px-6 pb-3 flex items-center gap-2">
          <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">
            <span class="h-1.5 w-1.5 rounded-full bg-slate-500 inline-block"></span>
            {{ $notifs->total() }} notifikasi
          </span>
          <span class="text-xs text-slate-400">
            — halaman {{ $notifs->currentPage() }} dari {{ $notifs->lastPage() }}
          </span>
        </div>
      @endif

      <div class="px-6 pb-6">
        @if ($items->isEmpty())
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center">
            <div class="font-semibold text-slate-900">Belum ada notifikasi</div>
            <div class="mt-1 text-sm text-slate-600">
              Notifikasi akan muncul otomatis dari sistem (contoh: stok menipis, shift besok, dll).
            </div>
          </div>
        @else
          <div class="divide-y divide-slate-100">
            @foreach ($items as $n)
              @php
                $id    = is_array($n) ? ($n['notifikasi_id'] ?? null) : ($n->notifikasi_id ?? null);
                $judul = is_array($n) ? ($n['judul_notif'] ?? '') : ($n->judul_notif ?? '');
                $jenis = is_array($n) ? ($n['jenis_notifikasi'] ?? '') : ($n->jenis_notifikasi ?? '');
                $pesan = is_array($n) ? ($n['isi_pesan'] ?? '') : ($n->isi_pesan ?? '');
                $tgl   = is_array($n) ? ($n['tanggal_dibuat'] ?? null) : ($n->tanggal_dibuat ?? null);

                $type = strtolower((string) $jenis);

                $iconWrap = match(true) {
                  str_contains($type, 'stok')      => 'bg-amber-100 border-amber-200 text-amber-700',
                  str_contains($type, 'jadwal')    => 'bg-emerald-100 border-emerald-200 text-emerald-700',
                  str_contains($type, 'invoice')   => 'bg-sky-100 border-sky-200 text-sky-700',
                  str_contains($type, 'transaksi') => 'bg-fuchsia-100 border-fuchsia-200 text-fuchsia-700',
                  default                          => 'bg-slate-100 border-slate-200 text-slate-700',
                };

                $badgeColor = match(true) {
                  str_contains($type, 'stok')      => 'bg-amber-50 text-amber-700 border-amber-200',
                  str_contains($type, 'jadwal')    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                  str_contains($type, 'invoice')   => 'bg-sky-50 text-sky-700 border-sky-200',
                  str_contains($type, 'transaksi') => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200',
                  default                          => 'bg-slate-50 text-slate-600 border-slate-200',
                };

                $iconSvg = match(true) {
                  str_contains($type, 'stok') => '
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8 4-8-4"/>
                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10l8 4 8-4V7"/>
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v10"/>
                    </svg>',
                  str_contains($type, 'jadwal') => '
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14"/>
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>',
                  str_contains($type, 'invoice') => '
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10a2 2 0 012 2v16l-2-1-2 1-2-1-2 1-2-1-2 1V5a2 2 0 012-2z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h6M9 16h4"/>
                    </svg>',
                  default => '
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
                    </svg>',
                };
              @endphp

              <a href="/detail_notifikasi/{{ $id }}"
                 class="group py-4 first:pt-0 flex gap-4 items-start hover:bg-slate-50/60 -mx-6 px-6 transition rounded-xl">
                <div class="h-11 w-11 rounded-xl border grid place-items-center shrink-0 {{ $iconWrap }}">
                  {!! $iconSvg !!}
                </div>
                <div class="min-w-0 flex-1">
                  <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mb-1">
                    <span class="text-base font-extrabold text-slate-900 group-hover:underline leading-snug">
                      {{ $judul ?: 'Judul Notif' }}
                    </span>
                    <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-semibold {{ $badgeColor }}">
                      {{ $jenis ?: '-' }}
                    </span>
                  </div>
                  <p class="text-sm text-slate-700 leading-relaxed line-clamp-2">
                    {{ $pesan ?: '-' }}
                  </p>
                  <<div class="mt-2 flex items-center justify-between gap-2">
                    <span class="text-xs text-slate-400 whitespace-nowrap shrink-0">
                      {{ $tgl ? \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') : '' }}
                    </span>

                    <span class="text-xs font-semibold text-slate-400 group-hover:text-slate-600 transition flex items-center gap-1 shrink-0">
                      Lihat detail
                      <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                      </svg>
                    </span>
                  </div>
                </div>
              </a>
            @endforeach
          </div>

          @if ($isPaginator && $notifs->lastPage() > 1)
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
              <p class="text-xs text-slate-500 order-2 sm:order-1">
                Menampilkan
                <span class="font-semibold text-slate-700">{{ $notifs->firstItem() }}–{{ $notifs->lastItem() }}</span>
                dari
                <span class="font-semibold text-slate-700">{{ $notifs->total() }}</span>
                notifikasi
              </p>
              <div class="flex items-center gap-1 order-1 sm:order-2">
                @if ($notifs->onFirstPage())
                  <span class="h-9 w-9 rounded-xl border border-slate-200 bg-slate-50 grid place-items-center text-slate-300 cursor-not-allowed">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                  </span>
                @else
                  <a href="{{ $notifs->previousPageUrl() }}" class="h-9 w-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center text-slate-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                  </a>
                @endif
                @foreach ($notifs->getUrlRange(1, $notifs->lastPage()) as $page => $url)
                  @if ($page == $notifs->currentPage())
                    <span class="h-9 w-9 rounded-xl border border-slate-900 bg-slate-900 grid place-items-center text-xs font-bold text-white">{{ $page }}</span>
                  @elseif ($page == 1 || $page == $notifs->lastPage() || abs($page - $notifs->currentPage()) <= 1)
                    <a href="{{ $url }}" class="h-9 w-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center text-xs font-semibold text-slate-700">{{ $page }}</a>
                  @elseif ($page == $notifs->currentPage() - 2 || $page == $notifs->currentPage() + 2)
                    <span class="h-9 w-9 grid place-items-center text-xs text-slate-400">…</span>
                  @endif
                @endforeach
                @if ($notifs->hasMorePages())
                  <a href="{{ $notifs->nextPageUrl() }}" class="h-9 w-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center text-slate-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                  </a>
                @else
                  <span class="h-9 w-9 rounded-xl border border-slate-200 bg-slate-50 grid place-items-center text-slate-300 cursor-not-allowed">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                  </span>
                @endif
              </div>
            </div>
          @endif
        @endif
      </div>

      <div class="text-xs text-slate-400 pt-4 pb-6 px-6 border-t border-slate-100">
        © DPM Workshop 2025
      </div>
    </div>
  </div>
</section>

@endsection
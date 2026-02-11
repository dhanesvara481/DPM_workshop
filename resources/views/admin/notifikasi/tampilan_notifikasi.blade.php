<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Notifikasi - DPM Workshop</title>
  @vite('resources/js/app.js')
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
<div class="min-h-screen flex">

  {{-- ================= SIDEBAR (sama seperti dashboard kamu) ================= --}}
  <aside id="sidebar"
         class="fixed inset-y-0 left-0 z-40 h-screen
                w-[280px] md:w-[280px]
                -translate-x-full md:translate-x-0
                bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-white
                border-r border-white/5
                transition-[transform,width] duration-300 ease-out
                overflow-y-auto">

    <div class="h-16 px-5 flex items-center justify-between border-b border-white/10">
      <div class="flex items-center gap-3">
        <div class="h-9 w-9 rounded-xl bg-white/10 border border-white/15 grid place-items-center overflow-hidden">
          <img src="{{ asset('images/logo.png') }}" class="h-7 w-7 object-contain" alt="Logo">
        </div>
        <div class="leading-tight">
          <p class="font-semibold tracking-tight">DPM Workshop</p>
        </div>
      </div>

      <button id="btnCloseSidebar"
              type="button"
              class="md:hidden h-10 w-10 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition grid place-items-center"
              aria-label="Tutup menu">
        <svg class="h-5 w-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <div class="px-5 py-5">
      {{-- Profile --}}
      <div class="flex items-center gap-3 rounded-2xl bg-white/5 border border-white/10 px-4 py-3">
        <div class="h-10 w-10 rounded-full bg-white/10 border border-white/15"></div>
        <div class="min-w-0">
          <p class="text-sm font-medium truncate">{{ $userName ?? 'User' }}</p>
          <p class="text-[11px] text-white/60">{{ $role ?? 'Admin' }}</p>
        </div>
      </div>

      {{-- Menu (samakan sama dashboard kamu) --}}
      <nav class="mt-5 space-y-1">
        <a href="/tampilan_dashboard" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
          <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5V21a1.5 1.5 0 01-1.5 1.5H4.5A1.5 1.5 0 013 21V10.5z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 22V12h6v10"/>
            </svg>
          </span>
          Dashboard
        </a>

        <div class="mt-3">
          <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">BARANG</p>
          <a href="/tampilan_barang" data-nav class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8 4-8-4"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10l8 4 8-4V7"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v10"/>
              </svg>
            </span>
            Kelola Barang
          </a>
          <a href="/barang_keluar" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 7h7v7"/>
              </svg>
            </span>
            Barang Keluar
          </a>
          <a href="/barang_masuk" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 7L7 17"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 10v7h7"/>
              </svg>
            </span>
            Barang Masuk
          </a>
        </div>

        <div class="mt-3">
          <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">RIWAYAT & LAPORAN</p>
          <a href="/riwayat_perubahan_stok" data-nav class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5l3 2"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </span>
            Riwayat Perubahan Stok
          </a>
          <a href="/riwayat_transaksi" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10a2 2 0 012 2v16l-2-1-2 1-2-1-2 1-2-1-2 1V5a2 2 0 012-2z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h6M9 16h4"/>
              </svg>
            </span>
            Riwayat Transaksi
          </a>
          <a href="/laporan_penjualan" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 17v-6"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 17V9"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 17v-3"/>
              </svg>
            </span>
            Laporan Penjualan
          </a>
        </div>

        <div class="mt-3">
          <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">MANAJEMEN</p>
          <a href="/kelola_jadwal_kerja" class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </span>
            Kelola Jadwal Kerja
          </a>

          <a href="/tampilan_manajemen_staf" class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/80 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20c0-2.2-2.7-4-5-4s-5 1.8-5 4"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 20c0-1.7-1.4-3.1-3.3-3.7"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7a2.5 2.5 0 01-1.5 2.3"/>
              </svg>
            </span>
            Manajemen Staf
          </a>
        </div>

        <div class="mt-4 pt-4 border-t border-white/10">
          <a href="#" class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 17l5-5-5-5"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21V3a2 2 0 00-2-2h-6"/>
              </svg>
            </span>
            Logout
          </a>
        </div>
      </nav>
    </div>
  </aside>

  <div id="overlay" class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-sm hidden md:hidden"></div>

  {{-- ================= MAIN ================= --}}
  <main id="main" class="flex-1 min-w-0 relative overflow-hidden md:ml-[280px] transition-[margin] duration-300 ease-out">

    {{-- Background --}}
    <div class="pointer-events-none absolute inset-0">
      <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
      <div class="absolute inset-0 opacity-[0.10]"
           style="background-image:
              linear-gradient(to right, rgba(2,6,23,0.05) 1px, transparent 1px),
              linear-gradient(to bottom, rgba(2,6,23,0.05) 1px, transparent 1px);
              background-size: 56px 56px;">
      </div>
    </div>

    {{-- TOPBAR --}}
    <header class="relative bg-white/75 backdrop-blur border-b border-slate-200 sticky top-0 z-20">
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
            Kembali
          </a>
          <button type="button"
                  class="h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
            {{ now()->format('d M Y') }}
          </button>
        </div>

      </div>
    </header>

    {{-- CONTENT --}}
    <section class="relative p-4 sm:p-6">
      <div class="max-w-[980px] mx-auto w-full">

        <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
                    shadow-[0_16px_44px_rgba(2,6,23,0.08)] overflow-hidden">

          {{-- Header bar ala mockup --}}
          <div class="px-6 py-5">
            <div class="text-2xl font-extrabold tracking-tight text-slate-900">Notifikasi</div>
            <div class="mt-3 h-1.5 bg-slate-900"></div>
            <p class="mt-3 text-sm text-slate-600">
              Daftar notifikasi otomatis (stok menipis, jadwal shift besok, dll).
            </p>
          </div>

        @php
            /**
             * BACKEND NANTI:
             * $notifs = App\Models\Notifikasi::latest()->get();
             * (notif ini dibuat otomatis oleh sistem, bukan oleh user)
             */

            /** DUMMY DATA (untuk ngetes UI; hapus kalau backend sudah ada) **/
            $notifs = $notifs ?? [
                [
                'notifikasi_id'     => 1,
                'judul_notif'       => 'Stok Menipis',
                'jenis_notifikasi'  => 'Stok',
                'isi_pesan'         => 'Barang "Oli Mesin A" tersisa 2 pcs. Segera restok untuk menghindari kehabisan.',
                'tanggal_dibuat'    => '2025-12-30',
                'tanggal_dikirim'   => '2025-12-30',
                ],
                [
                'notifikasi_id'     => 2,
                'judul_notif'       => 'Shift Besok',
                'jenis_notifikasi'  => 'Jadwal',
                'isi_pesan'         => 'Besok ada shift pagi untuk Asep (08:00 - 16:00). Pastikan kesiapan operasional.',
                'tanggal_dibuat'    => '2025-12-29',
                'tanggal_dikirim'   => '2025-12-29',
                ],
            ];
        @endphp


        {{-- LIST NOTIF --}}
        <div class="px-6 pb-6">
            @if (empty($notifs) || count($notifs) === 0)
                {{-- EMPTY STATE --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center">
                {{-- <div class="mx-auto h-12 w-12 rounded-2xl bg-white border border-slate-200 grid place-items-center">
                    <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
                    </svg>
                </div> --}}
                <div class="mt-3 font-semibold text-slate-900">Belum ada notifikasi</div>
                    <div class="mt-1 text-sm text-slate-600">
                        Notifikasi akan muncul otomatis dari sistem (contoh: stok menipis, shift besok, dll).
                    </div>
                </div>

            @else
                <div class="space-y-6">
                @foreach ($notifs as $n)
                    {{-- Item notif (klik -> detail_notifikasi) --}}
                    <a
                    href="/detail_notifikasi/{{ $n['notifikasi_id'] }}"
                    {{-- NANTI kalau pakai route:
                        /** href="{{ route('notifikasi.show', $n->notifikasi_id) }}" **/
                    --}}
                    class="group block"
                    >
                    <div class="flex gap-4">
                        {{-- Icon bulat kiri (ala mockup) --}}
                        @php
                        $type = strtolower($n['jenis_notifikasi'] ?? '');

                        // style + icon per jenis
                        $iconWrap = match(true) {
                            str_contains($type, 'stok') => 'bg-amber-100 border-amber-200 text-amber-700',
                            str_contains($type, 'jadwal') => 'bg-emerald-100 border-emerald-200 text-emerald-700',
                            str_contains($type, 'invoice') => 'bg-sky-100 border-sky-200 text-sky-700',
                            str_contains($type, 'transaksi') => 'bg-fuchsia-100 border-fuchsia-200 text-fuchsia-700',
                            default => 'bg-slate-100 border-slate-200 text-slate-700',
                        };

                        $iconSvg = match(true) {
                            // STOK: box
                            str_contains($type, 'stok') => '
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8 4-8-4"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10l8 4 8-4V7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v10"/>
                            </svg>
                            ',

                            // JADWAL: calendar
                            str_contains($type, 'jadwal') => '
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            ',

                            // INVOICE: receipt
                            str_contains($type, 'invoice') => '
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10a2 2 0 012 2v16l-2-1-2 1-2-1-2 1-2-1-2 1V5a2 2 0 012-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h6M9 16h4"/>
                            </svg>
                            ',

                            // DEFAULT: bell
                            default => '
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
                            </svg>
                            ',
                        };
                        @endphp

                        <div class="h-14 w-14 rounded-full border grid place-items-center shrink-0 {{ $iconWrap }}">
                        {!! $iconSvg !!}
                        </div>


                        <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                            <div class="text-lg font-extrabold text-slate-900 group-hover:underline">
                            {{ $n['judul_notif'] ?? 'Judul Notif' }}
                            </div>
                            <div class="text-lg font-extrabold text-slate-900">|</div>
                            <div class="text-lg font-extrabold text-slate-900">
                            {{ $n['jenis_notifikasi'] ?? 'Jenis Notif' }}
                            </div>
                        </div>

                        <div class="mt-1 text-base leading-relaxed text-slate-800">
                            {{ $n['isi_pesan'] ?? '-' }}
                        </div>

                        <div class="mt-2 text-sm font-bold text-slate-900">
                            {{ isset($n['tanggal_dibuat']) ? \Carbon\Carbon::parse($n['tanggal_dibuat'])->format('d F Y') : '' }}
                        </div>
                        </div>
                    </div>
                    </a>
                @endforeach
                </div>
            @endif
        </div>


          {{-- FOOTER TARUH DI LUAR CARD --}}
           <div class="text-xs text-slate-400 pt-6 pb-6 px-6">
                © DPM Workshop 2025
            </div>
      </div>
    </section>

    <style>
      .nav-item{ position: relative; overflow: hidden; }
      .nav-item::before{
        content:"";
        position:absolute;
        left:0; top:10px; bottom:10px;
        width:3px;
        background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,.75), rgba(255,255,255,0));
        opacity:0;
        transform: translateX(-6px);
        transition: .25s ease;
        border-radius: 999px;
      }
      .nav-item.is-active::before{ opacity:.95; transform: translateX(0); }
      #sidebar { -webkit-overflow-scrolling: touch; }
    </style>

    <script>
      // sidebar active (optional)
      document.querySelectorAll('[data-nav]').forEach(a => {
        if (a.getAttribute('href') === '/tampilan_notifikasi') a.classList.add('is-active');
        if (a.dataset.active === "true") a.classList.add('is-active');
      });

      // mobile sidebar toggle
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('overlay');
      const btnSidebar = document.getElementById('btnSidebar');
      const btnCloseSidebar = document.getElementById('btnCloseSidebar');

      const openSidebar = () => {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
      };
      const closeSidebar = () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
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
          overlay.classList.add('hidden');
          sidebar.classList.remove('-translate-x-full');
          document.body.classList.remove('overflow-hidden');
        } else {
          sidebar.classList.add('-translate-x-full');
        }
      };
      window.addEventListener('resize', syncOnResize);
      syncOnResize();
    </script>

  </main>
</div>
</body>
</html>

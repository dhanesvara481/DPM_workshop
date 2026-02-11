<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Staf</title>
  @vite('resources/js/app.js')
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
<div class="min-h-screen flex">

  {{-- ================= SIDEBAR ================= --}}
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

      <button id="btnCloseSidebar" type="button"
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

      {{-- Menu --}}
      <nav class="mt-5 space-y-1">
        <a href="#" data-nav
           class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
          <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5V21a1.5 1.5 0 01-1.5 1.5H4.5A1.5 1.5 0 013 21V10.5z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 22V12h6v10"/>
            </svg>
          </span>
          Dashboard
        </a>

        {{-- ... (BARANG / RIWAYAT & LAPORAN) kamu copy aja sama persis dari halaman manajemen staf ... --}}

        <div class="mt-3">
          <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">MANAJEMEN</p>

          <a href="/tampilan_jadwal_kerja" data-nav
             class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </span>
            Kelola Jadwal Kerja
          </a>

          {{-- ACTIVE --}}
          <a href="/tampilan_manajemen_staf" data-nav data-active="true"
             class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                    bg-white/12 text-white border border-white/10
                    hover:bg-white/10 hover:text-white transition relative overflow-hidden">
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
          <a href="#"
             class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition">
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
            <h1 class="text-sm font-semibold tracking-tight text-slate-900">Detail Staf</h1>
            <p class="text-xs text-slate-500">Lihat profil staf & status akun.</p>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <a id="btnBackTop" href="/tampilan_manajemen_staf"
             class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition px-3 py-2 text-sm">
            <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
          </a>
        </div>
      </div>
    </header>

    {{-- CONTENT (modal page) --}}
    <section class="relative p-4 sm:p-6">
        @php
            // ===== MODE TOGGLE =====
            // nanti kalau backend/db sudah siap, ubah ke true (atau hapus saja fallback-nya)
            $USE_DB = false;

            // data dari controller (DB) idealnya berupa object/model
            // kalau belum ada, fallback dummy berdasarkan id query
            $dummyStafs = [
                ['id'=>1,'nama'=>'Asep','role'=>'Staf','username'=>'asep01','email'=>'asep@gmail.com','kontak'=>'081234567890','status'=>'Aktif','gabung'=>'2025-01-10','catatan'=>'Mekanik'],
                ['id'=>2,'nama'=>'Rina','role'=>'Admin','username'=>'rina01','email'=>'rina@gmail.com','kontak'=>'081299991111','status'=>'Aktif','gabung'=>'2025-03-22','catatan'=>'Owner'],
                ['id'=>3,'nama'=>'Budi','role'=>'Staf','username'=>'budi01','email'=>'budi@gmail.com','kontak'=>'081277771111','status'=>'Nonaktif','gabung'=>'2024-11-02','catatan'=>'Keuangan'],
            ];

            $id = (int) request('id');

            // ambil staf:
            // - kalau $USE_DB true: wajib ada $staf dari controller
            // - kalau false: cari dari dummy
            if (!$USE_DB || !isset($staf)) {
                $staf = collect($dummyStafs)->firstWhere('id', $id) ?? $dummyStafs[0];
            }

            // Adapter: jadikan object biar di Blade pakai -> terus (konsisten)
            if (is_array($staf)) $staf = (object) $staf;
        @endphp


      {{-- overlay modal --}}
      <div id="modalOverlay" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm"></div>

      {{-- modal --}}
      <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-[720px] rounded-2xl bg-white border border-slate-200 shadow-[0_18px_48px_rgba(2,6,23,0.16)] overflow-hidden">
          <div class="p-5 sm:p-6 border-b border-slate-200 flex items-start justify-between gap-3">
            <div class="min-w-0">
                <div class="text-lg font-semibold text-slate-900 truncate">{{ $staf->nama }}</div>
                <div class="text-xs text-slate-500 mt-1">
                    ID: {{ $staf->id }} • Role: <span class="font-semibold">{{ $staf->role }}</span>
                </div>
            </div>

            <a id="btnCloseModal" href="/tampilan_manajemen_staf"
               class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
               aria-label="Tutup">
              <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </a>
          </div>

          <div class="p-5 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

              <div class="rounded-2xl border border-slate-200 bg-slate-50/40 p-4">
                <div class="text-xs tracking-widest text-slate-500 font-semibold">STATUS</div>
                <div class="mt-2">
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                        {{ ($staf->status ?? '') === 'Aktif'
                            ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                            : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                        {{ $staf->status ?? '-' }}
                    </span>
                </div>
                <div class="text-xs text-slate-500 mt-3">Tgl Gabung: <span class="font-semibold text-slate-700">{{ $staf->gabung ?? '-' }}</span></div>
              </div>    

              <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <div class="text-xs tracking-widest text-slate-500 font-semibold">CATATAN</div>
                <div class="mt-2 text-sm text-slate-700">{{ $staf->catatan ?? '—' }}</div>
              </div>

              <div class="sm:col-span-2 rounded-2xl border border-slate-200 bg-white p-4">
                <div class="text-xs tracking-widest text-slate-500 font-semibold">INFORMASI AKUN</div>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                  <div>
                    <div class="text-xs text-slate-500">Username</div>
                    <div class="font-semibold text-slate-900">{{ $staf->username ?? '-' }}</div>
                  </div>

                  <div>
                    <div class="text-xs text-slate-500">Email</div>
                    <div class="font-semibold text-slate-900 break-words">{{ $staf->email ?? '-' }}</div>
                  </div>

                  <div class="sm:col-span-2">
                    <div class="text-xs text-slate-500">Kontak</div>
                    <div class="font-semibold text-slate-900">{{ $staf->kontak ?? '-' }}</div>
                  </div>
                </div>
              </div>
            </div>

            {{-- actions --}}
            <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:justify-end">
              <a href="/tampilan_manajemen_staf"
                 class="inline-flex h-11 items-center justify-center rounded-xl px-5 text-sm font-semibold border border-slate-200 bg-white hover:bg-slate-50 transition">
                Tutup
              </a>
                @if(($staf->role ?? '') === 'Staf')
                    <a href="/ubah_staf?id={{ $staf->id }}"
                        class="inline-flex h-11 items-center justify-center rounded-xl px-5 text-sm font-semibold border border-slate-200 bg-white hover:bg-slate-50 transition">
                        Ubah
                    </a>

                    <a id="btnNonaktif" href="/nonaktifkan_staf?id={{ $staf->id }}"
                        class="inline-flex h-11 items-center justify-center rounded-xl px-5 text-sm font-semibold bg-rose-600 text-white hover:bg-rose-700 transition shadow-[0_12px_24px_rgba(2,6,23,0.14)]">
                        Nonaktifkan
                    </a>
                @endif
            </div>
          </div>

          <div class="px-5 sm:px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
            © DPM Workshop 2025
          </div>
        </div>
      </div>
    </section>

    <script>
      // ===== mobile sidebar =====
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

      // active indicator
      document.querySelectorAll('[data-nav]').forEach(a => {
        if (a.dataset.active === "true") a.classList.add('is-active');
      });

      // ===== modal close (overlay click / ESC) =====
      const modalOverlay = document.getElementById('modalOverlay');
      const btnCloseModal = document.getElementById('btnCloseModal');
      const backHref = btnCloseModal?.getAttribute('href') || '/tampilan_manajemen_staf';

      modalOverlay?.addEventListener('click', () => window.location.href = backHref);

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') window.location.href = backHref;
      });

      // ===== confirm nonaktif =====
      const btnNonaktif = document.getElementById('btnNonaktif');
      btnNonaktif?.addEventListener('click', (e) => {
        const ok = confirm('Yakin nonaktifkan staf ini?');
        if (!ok) e.preventDefault();
      });
    </script>

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

  </main>
</div>
</body>
</html>

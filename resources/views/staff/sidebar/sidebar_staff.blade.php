@php
  $isActive = function(array $patterns) {
    foreach ($patterns as $p) {
      if (request()->is($p)) return true;
    }
    return false;
  };
@endphp

<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-40 h-screen
              w-[280px] md:w-[280px]
              -translate-x-full md:translate-x-0
              bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-white
              border-r border-white/5
              transition-[transform,width] duration-300 ease-out
              overflow-y-auto">

  {{-- HEADER --}}
  <div class="h-16 px-5 flex items-center justify-between border-b border-white/10">
    <div class="flex items-center gap-3">
      <div class="h-9 w-9 rounded-xl bg-white/10 border border-white/15 grid place-items-center overflow-hidden">
        <img src="{{ asset('images/logo.png') }}" class="h-7 w-7 object-contain" alt="Logo">
      </div>
      <div class="leading-tight">
        <p class="font-semibold tracking-tight">DPM Workshop</p>
      </div>
    </div>

    {{-- Tutup sidebar (mobile) --}}
    <button id="btnCloseSidebar" type="button"
            class="md:hidden h-10 w-10 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition grid place-items-center"
            aria-label="Tutup menu">
      <svg class="h-5 w-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>

  <div class="px-5 py-5">

    {{-- PROFILE — $username & $role diisi oleh View Composer di AppServiceProvider --}}
    <div class="flex items-center gap-3 rounded-2xl bg-white/5 border border-white/10 px-4 py-3">
      <div class="h-10 w-10 rounded-full bg-white/10 border border-white/15 grid place-items-center">
        <svg class="h-5 w-5 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 10-16 0"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 11a4 4 0 100-8 4 4 0 000 8z"/>
        </svg>
      </div>
      <div class="min-w-0">
        <p class="text-sm font-medium truncate">{{ $username }}</p>
        <p class="text-[11px] text-white/60 capitalize">{{ $role }}</p>
      </div>
    </div>

    {{-- MENU --}}
    <nav class="mt-5 space-y-1">

      {{-- DASHBOARD --}}
      <a href="/tampilan_dashboard_staff" data-nav
         class="nav-item {{ $isActive(['tampilan_dashboard_staff']) ? 'is-active' : '' }}
                group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
          <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5V21a1.5 1.5 0 01-1.5 1.5H4.5A1.5 1.5 0 013 21V10.5z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 22V12h6v10"/>
          </svg>
        </span>
        Dashboard
      </a>

      {{-- BARANG --}}
      <div class="mt-3">
        <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">BARANG</p>

        <a href="/stok_realtime_staff" data-nav
           class="nav-item {{ $isActive(['stok_realtime_staff']) ? 'is-active' : '' }}
                  group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                  text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
          <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8 4-8-4"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10l8 4 8-4V7"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v10"/>
            </svg>
          </span>
          Barang
        </a>
      </div>

      {{-- TRANSAKSI --}}
      <div class="mt-3">
        <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">TRANSAKSI</p>

        <a href="/tampilan_invoice_staff" data-nav
           class="nav-item {{ $isActive(['tampilan_invoice_staff']) ? 'is-active' : '' }}
                  group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                  text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
          <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9a3 3 0 000 6h6a3 3 0 010 6H7"/>
            </svg>
          </span>
          Invoice
        </a>

        <a href="/riwayat_transaksi_staff" data-nav
           class="nav-item {{ $isActive(['riwayat_transaksi_staff']) ? 'is-active' : '' }}
                  group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                  text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
          <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10a2 2 0 012 2v16l-2-1-2 1-2-1-2 1-2-1-2 1V5a2 2 0 012-2z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h6M9 16h4"/>
            </svg>
          </span>
          Riwayat Transaksi
        </a>
      </div>

      {{-- JADWAL --}}
      <div class="mt-3">
        <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">JADWAL</p>

        <a href="/jadwal_kerja_staff" data-nav
           class="nav-item {{ $isActive(['jadwal_kerja_staff']) ? 'is-active' : '' }}
                  group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                  text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
          <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
          </span>
          Jadwal Kerja
        </a>
      </div>

      {{-- LOGOUT --}}
      <div class="mt-4 pt-4 border-t border-white/10">
        <a href="/logout"
           onclick="event.preventDefault(); document.getElementById('logout-form-staff').submit();"
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

        <form id="logout-form-staff" action="{{ route('logout') }}" method="POST" class="hidden">
          @csrf
        </form>
      </div>

    </nav>
  </div>
</aside>
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

    {{-- close (mobile) --}}
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

    {{-- MENU --}}
    <nav class="mt-5 space-y-1">

      {{-- Dashboard --}}
      <a href="/tampilan_dashboard" data-nav
         class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden
                {{ request()->is('tampilan_dashboard') ? 'is-active' : '' }}">
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
                
        @php
            $barangActive = request()->is('tampilan_barang*')
            || request()->is('tambah_barang*')
            || request()->is('ubah_barang*')
        @endphp

        <a href="/tampilan_barang" data-nav
           class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                  text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden
                  {{ $barangActive ? 'is-active' : '' }}">
          <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8 4-8-4"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10l8 4 8-4V7"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v10"/>
            </svg>
          </span>
          Kelola Barang
        </a>

        <a href="/barang_keluar" data-nav
           class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                  text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden
                  {{ request()->is('barang_keluar*') ? 'is-active' : '' }}">
          <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M10 7h7v7"/>
            </svg>
          </span>
          Barang Keluar
        </a>

        <a href="/barang_masuk" data-nav
           class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                  text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden
                  {{ request()->is('barang_masuk*') ? 'is-active' : '' }}">
          <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 7L7 17"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M7 10v7h7"/>
            </svg>
          </span>
          Barang Masuk
        </a>
      </div>

      {{-- RIWAYAT & LAPORAN --}}
      <div class="mt-3">
        <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">RIWAYAT & LAPORAN</p>

        <a href="/riwayat_perubahan_stok" data-nav
           class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                  text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden
                  {{ request()->is('riwayat_perubahan_stok*') ? 'is-active' : '' }}">
          <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5l3 2"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </span>
          Riwayat Perubahan Stok
        </a>

        <a href="/riwayat_transaksi" data-nav
           class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                  text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden
                  {{ request()->is('riwayat_transaksi*') ? 'is-active' : '' }}">
          <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10a2 2 0 012 2v16l-2-1-2 1-2-1-2 1-2-1-2 1V5a2 2 0 012-2z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h6M9 16h4"/>
            </svg>
          </span>
          Riwayat Transaksi
        </a>

        <a href="/laporan_penjualan" data-nav
           class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                  text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden
                  {{ request()->is('laporan_penjualan*') ? 'is-active' : '' }}">
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

      {{-- MANAJEMEN --}}
      <div class="mt-3">
        <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">MANAJEMEN</p>

        @php
          $jadwalActive =request()->is('kelola_jadwal_kerja*')
              || request()->is('tambah_jadwal_kerja*')
              || request()->is('ubah_jadwal_kerja*')
              || request()->is('hapus_jadwal_kerja*');
        @endphp

        <a href="/kelola_jadwal_kerja" data-nav
          class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                  text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden
                  {{ $jadwalActive ? 'is-active bg-white/12 text-white border border-white/10' : '' }}">
          <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
          </span>
          Kelola Jadwal Kerja
        </a>

        @php
          $manajemenStafActive =request()->is('tampilan_manajemen_staf*')
              || request()->is('tambah_staf*')
              || request()->is('ubah_staf*');
        @endphp

        <a href="/tampilan_manajemen_staf" data-nav
           class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                  text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden
                  {{ $manajemenStafActive ? 'is-active bg-white/12 text-white border border-white/10' : '' }}">
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

      {{-- LOGOUT --}}
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

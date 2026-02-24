<header class="relative bg-white/75 backdrop-blur border-b border-slate-200 sticky top-0 z-20">
  <div class="h-16 px-4 sm:px-6 flex items-center justify-between gap-3">

    {{-- LEFT --}}
    <div class="flex items-center gap-3 min-w-0">

      {{-- Sidebar Toggle (mobile) --}}
      <button id="btnSidebar" type="button"
              class="md:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
              aria-label="Buka menu">
        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      <div class="min-w-0">
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">
          @yield('page_title', 'Dashboard')
        </h1>
        <p class="text-xs text-slate-500">
          @yield('page_subtitle', 'Staff')
        </p>
      </div>
    </div>

    {{-- RIGHT --}}
    <div class="flex items-center gap-2">

      <a href="/staff/notifikasi"
         class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
         aria-label="Notifikasi">
        <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 17a3 3 0 006 0"/>
        </svg>
      </a>

      <button type="button"
              class="h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
        {{ now()->format('d M Y') }}
      </button>

    </div>
  </div>
</header>
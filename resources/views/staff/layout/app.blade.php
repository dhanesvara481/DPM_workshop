<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'DPM Workshop - Staff')</title>

  @vite('resources/js/app.js')
  @stack('head')

  <style>
    /* ===== NAV ACTIVE (GLOBAL) ===== */
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
    .nav-item.is-active{
      background: rgba(255,255,255,.10);
      border: 1px solid rgba(255,255,255,.10);
      color: rgba(255,255,255,.95);
    }
    .nav-item.is-active::before{ opacity:.95; transform: translateX(0); }

    /* iOS scroll sidebar */
    #sidebar { -webkit-overflow-scrolling: touch; }
  </style>
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">
  <div class="min-h-screen flex">

    {{-- SIDEBAR (STAFF) --}}
    @include('staff.sidebar.sidebar_staff', [
      'userName' => $userName ?? 'User',
      'role' => $role ?? 'Staff'
    ])

    {{-- OVERLAY mobile --}}
    <div id="overlay"
        class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm hidden md:hidden"></div>

    {{-- MAIN --}}
    <main id="main"
      class="flex-1 min-w-0 relative overflow-x-hidden md:ml-[280px] transition-[margin] duration-300 ease-out">

      {{-- GLOBAL BACKGROUND (STAFF) --}}
      <div class="pointer-events-none absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
        <div class="absolute inset-0 opacity-[0.10]"
          style="background-image:
            linear-gradient(to right, rgba(2,6,23,0.05) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(2,6,23,0.05) 1px, transparent 1px);
            background-size: 56px 56px;">
        </div>
        <div class="absolute -top-48 left-1/2 -translate-x-1/2 h-[680px] w-[680px] rounded-full blur-3xl opacity-10
          bg-gradient-to-tr from-blue-950/25 via-blue-700/10 to-transparent"></div>
      </div>

      {{-- TOPBAR --}}
      @hasSection('topbar')
        @yield('topbar')
      @else
        @include('staff.topbar.topbar_staff')
      @endif

      {{-- CONTENT AREA --}}
      <section class="relative z-10 p-4 md:p-8 min-h-[calc(100vh-64px)]">
        @yield('content')
      </section>

      {{-- GLOBAL SCRIPT: sidebar toggle --}}
      <script>
        (function(){
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
            if (!sidebar || !overlay) return;

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
        })();
      </script>

    </main>
  </div>

  {{-- ✅ PENTING: MODALS harus di BODY, dan harus sebelum scripts --}}
  @stack('modals')

  {{-- ✅ scripts terakhir --}}
  @stack('scripts')
</body>
</html>
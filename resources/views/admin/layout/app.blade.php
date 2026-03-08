<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'DPM Workshop')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body { opacity: 0; }
  </style>

  <script>
    window.addEventListener('load', function() {
      document.body.style.opacity = '1';
      document.body.style.transition = 'opacity 0.2s ease';
    });
  </script>

  @vite('resources/js/app.js')
  @stack('head')

  <style>
    .nav-item { position: relative; overflow: hidden; }
    .nav-item::before {
      content: "";
      position: absolute;
      left: 0; top: 10px; bottom: 10px;
      width: 3px;
      background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,.75), rgba(255,255,255,0));
      opacity: 0;
      transform: translateX(-6px);
      transition: .25s ease;
      border-radius: 999px;
    }
    .nav-item.is-active {
      background: rgba(255,255,255,.12);
      border: 1px solid rgba(255,255,255,.10);
      color: rgba(255,255,255,.95);
    }
    .nav-item.is-active::before { opacity: .95; transform: translateX(0); }

    #sidebar { -webkit-overflow-scrolling: touch; }
  </style>
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
<div class="min-h-screen flex">

  @include('admin.sidebar', [
    'userName' => $userName ?? 'User',
    'role'     => $role ?? 'Admin'
  ])

  <div id="overlay"
       class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-sm hidden md:hidden"></div>

  <main id="main"
        class="flex-1 min-w-0 relative overflow-x-hidden md:ml-[280px] transition-[margin] duration-300 ease-out">

    <div class="pointer-events-none absolute inset-0">
      <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>

      <div class="absolute inset-0 opacity-[0.10]"
           style="background-image:
            linear-gradient(to right, rgba(2,6,23,0.05) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(2,6,23,0.05) 1px, transparent 1px);
            background-size: 56px 56px;">
      </div>

      <div class="absolute -top-48 left-1/2 -translate-x-1/2 h-[680px] w-[680px]
                  rounded-full blur-3xl opacity-10
                  bg-gradient-to-tr from-blue-950/25 via-blue-700/10 to-transparent"></div>
    </div>

    <div class="relative z-10">
      @yield('content')
    </div>

    <script>
      (function () {
        var sidebar  = document.getElementById('sidebar');
        var overlay  = document.getElementById('overlay');
        var btnOpen  = document.getElementById('btnSidebar');
        var btnClose = document.getElementById('btnCloseSidebar');

        var openSidebar = function () {
          sidebar?.classList.remove('-translate-x-full');
          overlay?.classList.remove('hidden');
          document.body.classList.add('overflow-hidden');
        };

        var closeSidebar = function () {
          if (window.innerWidth < 768) {
            sidebar?.classList.add('-translate-x-full');
          }
          overlay?.classList.add('hidden');
          document.body.classList.remove('overflow-hidden');
        };

        var syncOnResize = function () {
          if (!sidebar) return;
          if (window.innerWidth >= 768) {
            sidebar.classList.remove('-translate-x-full');
            overlay?.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
          } else {
            if (overlay?.classList.contains('hidden')) {
              sidebar.classList.add('-translate-x-full');
            }
          }
        };

        btnOpen?.addEventListener('click', openSidebar);
        btnClose?.addEventListener('click', closeSidebar);
        overlay?.addEventListener('click', closeSidebar);

        document.addEventListener('keydown', function (e) {
          if (e.key === 'Escape') closeSidebar();
        });

        window.addEventListener('resize', syncOnResize);
      })();
    </script>

  </main>
</div>

@stack('modals')
@stack('scripts')
</body>
</html>
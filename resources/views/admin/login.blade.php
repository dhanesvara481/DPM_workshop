<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    @vite('resources/js/app.js')
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    <div id="loginScene" class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

        {{-- BACKGROUND (GRID + DOT + ANIMATED LIGHT ON GRID) --}}
        <div class="pointer-events-none absolute inset-0">
            {{-- base --}}
            <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>

            {{-- grid lines --}}
            <div class="absolute inset-0 opacity-[0.16]"
                 style="background-image:
                    linear-gradient(to right, rgba(2,6,23,0.06) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(2,6,23,0.06) 1px, transparent 1px);
                    background-size: 56px 56px;">
            </div>

            {{-- micro dots --}}
            <div class="absolute inset-0 opacity-[0.08]"
                 style="background-image: radial-gradient(circle at 1px 1px, rgba(2,6,23,0.18) 1px, transparent 1px);
                        background-size: 22px 22px;">
            </div>

            {{-- vignette --}}
            <div class="absolute inset-0 opacity-[0.09]"
                 style="background: radial-gradient(circle at 50% 40%, rgba(59,130,246,0.14), transparent 60%);">
            </div>

            {{-- ✅ animated grid shine (cahaya jalan di garis grid) --}}
            <div class="absolute inset-0 opacity-[0.28] mix-blend-screen animate-grid-scan"
                 style="background-image:
                    /* vertical highlight bars */
                    repeating-linear-gradient(
                        90deg,
                        transparent 0px,
                        transparent 55px,
                        rgba(255,255,255,0.95) 56px,
                        transparent 57px,
                        transparent 112px
                    ),
                    /* horizontal highlight bars */
                    repeating-linear-gradient(
                        180deg,
                        transparent 0px,
                        transparent 55px,
                        rgba(255,255,255,0.70) 56px,
                        transparent 57px,
                        transparent 112px
                    );
                    background-size: 112px 112px, 112px 112px;
                    background-position: 0 0, 0 0;">
            </div>

            {{-- extra soft glow corners --}}
            <div class="absolute -top-56 left-1/2 -translate-x-1/2 h-[720px] w-[720px] rounded-full blur-3xl opacity-12
                        bg-gradient-to-tr from-blue-950/25 via-blue-700/10 to-transparent"></div>
            <div class="absolute -bottom-72 right-1/4 h-[720px] w-[720px] rounded-full blur-3xl opacity-10
                        bg-gradient-to-tr from-blue-950/20 via-indigo-700/10 to-transparent"></div>

            {{-- floating blobs (subtle) --}}
            <div class="absolute left-[-120px] top-[18%] h-[340px] w-[340px] rounded-full blur-3xl opacity-10 bg-blue-900/20 animate-float-slow"></div>
            <div class="absolute right-[-140px] top-[10%] h-[380px] w-[380px] rounded-full blur-3xl opacity-08 bg-indigo-900/20 animate-float-slower"></div>
        </div>

        {{-- MAIN CARD --}}
        <div id="loginCard" class="w-full max-w-5xl relative">
            <div class="rounded-3xl p-[1px] bg-gradient-to-br from-slate-200 via-slate-100 to-slate-200 shadow-[0_30px_90px_rgba(2,6,23,0.16)]">
                <div class="rounded-3xl bg-white/85 backdrop-blur-xl overflow-hidden border border-white/60">
                    <div class="grid grid-cols-1 md:grid-cols-2">

                        {{-- LEFT: BIG LOGO + TITLE --}}
                        <div class="relative bg-gradient-to-br from-blue-950 via-blue-900 to-blue-800 text-white
                                    flex items-center justify-center overflow-hidden p-10">

                            {{-- subtle stripes --}}
                            <div class="pointer-events-none absolute inset-0 opacity-[0.10]"
                                 style="background-image: repeating-linear-gradient(
                                        -45deg,
                                        rgba(255,255,255,0.18) 0px,
                                        rgba(255,255,255,0.18) 1px,
                                        transparent 1px,
                                        transparent 26px
                                 );">
                            </div>

                            {{-- shimmer --}}
                            <div class="pointer-events-none absolute -inset-x-24 top-10 h-24 rotate-[-8deg] opacity-20 blur-sm
                                        bg-gradient-to-r from-transparent via-white/70 to-transparent animate-shimmer"></div>

                            {{-- glow --}}
                            <div class="pointer-events-none absolute -top-24 left-1/2 -translate-x-1/2
                                        h-[520px] w-[520px] rounded-full blur-3xl opacity-20
                                        bg-blue-400/30"></div>

                            <div class="relative text-center px-6">
                                {{-- ✅ GANTI LOGO DI SINI --}}
                                {{-- Taruh file di: public/images/logo.png --}}
                                <div class="mx-auto w-[220px] h-[220px] md:w-[260px] md:h-[260px]">
                                    <img
                                        src="{{ asset('images/logo.png') }}"
                                        alt="Logo"
                                        class="w-full h-full object-contain drop-shadow-[0_22px_55px_rgba(0,0,0,0.35)]"
                                    >
                                </div>

                                <h1 class="mt-8 text-4xl md:text-5xl font-semibold tracking-tight">
                                    DPW Workshop
                                </h1>
                            </div>
                        </div>

                        {{-- RIGHT: FORM --}}
                        <div class="p-8 md:p-10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl font-semibold tracking-tight text-slate-900">Login</h2>
                                    <p class="mt-1 text-sm text-slate-500">Masukkan kredensial Anda.</p>
                                </div>

                                <span class="hidden sm:inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs text-slate-600">
                                    Secure Access
                                </span>
                            </div>

                            @if (session('error'))
                                <div id="loginError"
                                     class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form id="loginForm" method="POST" action="{{ route('login.attempt') }}" class="mt-7 space-y-5">
                                @csrf

                                {{-- Username --}}
                                <div class="group">
                                    <label for="username" class="block text-xs font-medium tracking-wide text-slate-700 mb-2">
                                        USERNAME
                                    </label>

                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400 group-focus-within:text-blue-950 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>

                                        <input
                                            id="username"
                                            name="username"
                                            type="text"
                                            value="{{ old('username') }}"
                                            autocomplete="username"
                                            required
                                            placeholder="Contoh: John Doe"
                                            class="block w-full pl-10 pr-4 py-3 rounded-xl
                                                   border border-slate-200 bg-white text-slate-900 placeholder:text-slate-400
                                                   shadow-sm
                                                   focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/40
                                                   transition"
                                        >

                                        <span class="pointer-events-none absolute left-4 right-4 -bottom-2 h-2 rounded-full blur-xl
                                                     bg-blue-900/0 group-focus-within:bg-blue-900/10 transition"></span>
                                    </div>

                                    @error('username')
                                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Password --}}
                                <div class="group">
                                    <label for="password" class="block text-xs font-medium tracking-wide text-slate-700 mb-2">
                                        PASSWORD
                                    </label>

                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400 group-focus-within:text-blue-950 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </div>

                                        <input
                                            id="password"
                                            name="password"
                                            type="password"
                                            autocomplete="current-password"
                                            required
                                            placeholder="••••••••"
                                            class="block w-full pl-10 pr-12 py-3 rounded-xl
                                                   border border-slate-200 bg-white text-slate-900 placeholder:text-slate-400
                                                   shadow-sm
                                                   focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/40
                                                   transition"
                                        >

                                        <button
                                            type="button"
                                            id="togglePassword"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-blue-950 transition"
                                            aria-label="Tampilkan password"
                                        >
                                            <svg id="eyeOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <svg id="eyeClosed" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.27-2.943-9.543-7a10.05 10.05 0 012.642-4.362M9.88 9.88a3 3 0 104.24 4.24"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M3 3l18 18"/>
                                            </svg>
                                        </button>

                                        <span class="pointer-events-none absolute left-4 right-4 -bottom-2 h-2 rounded-full blur-xl
                                                     bg-blue-900/0 group-focus-within:bg-blue-900/10 transition"></span>
                                    </div>

                                    @error('password')
                                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Button --}}
                                <div class="pt-2">
                                    <button
                                        id="loginBtn"
                                        type="submit"
                                        class="relative w-full rounded-xl py-3 font-semibold tracking-wide text-white
                                               bg-blue-950 hover:bg-blue-900
                                               shadow-[0_12px_30px_rgba(2,6,23,0.18)]
                                               focus:outline-none focus:ring-4 focus:ring-blue-900/20
                                               transition active:translate-y-[1px] overflow-hidden"
                                    >
                                        <span id="loginBtnText" class="relative z-10">Masuk</span>

                                        <span id="btnShimmer"
                                              class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/35 to-transparent hidden"></span>
                                    </button>

                                    <p class="mt-4 text-center text-xs text-slate-500">
                                        Protected access only
                                    </p>
                                </div>
                            </form>

                            <div class="mt-8 border-t border-slate-100 pt-5">
                                <p class="text-center text-xs text-slate-500">
                                    © DPW Workshop 2025
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- CSS Animations (no duplicates) --}}
        <style>
            @media (prefers-reduced-motion: reduce) {
                .animate-float-slow, .animate-float-slower, .animate-shimmer,
                .animate-btn-shimmer, .shake, .animate-grid-scan { animation: none !important; }
            }

            @keyframes floatSlow {
                0%, 100% { transform: translate(0, 0); }
                50% { transform: translate(18px, -14px); }
            }
            @keyframes floatSlower {
                0%, 100% { transform: translate(0, 0); }
                50% { transform: translate(-14px, 18px); }
            }
            .animate-float-slow { animation: floatSlow 9s ease-in-out infinite; }
            .animate-float-slower { animation: floatSlower 12s ease-in-out infinite; }

            @keyframes shimmer {
                0% { transform: translateX(-22%) rotate(-8deg); opacity: 0; }
                25% { opacity: 0.18; }
                50% { opacity: 0.12; }
                100% { transform: translateX(22%) rotate(-8deg); opacity: 0; }
            }
            .animate-shimmer { animation: shimmer 11s ease-in-out infinite; }

            /* ✅ grid shine moves by shifting background-position (keliatan) */
            @keyframes gridScan {
                0%   { background-position: 0 0, 0 0; opacity: 0.10; }
                40%  { opacity: 0.28; }
                60%  { opacity: 0.22; }
                100% { background-position: 220px 220px, -260px 260px; opacity: 0.10; }
            }
            .animate-grid-scan { animation: gridScan 8.5s ease-in-out infinite; }

            /* optional shake class (used by JS if error) */
            @keyframes shake {
                0% { transform: translateX(0); }
                20% { transform: translateX(-6px); }
                40% { transform: translateX(6px); }
                60% { transform: translateX(-4px); }
                80% { transform: translateX(4px); }
                100% { transform: translateX(0); }
            }
            .shake { animation: shake .35s ease-in-out; }
        </style>
    </div>
</body>
</html>

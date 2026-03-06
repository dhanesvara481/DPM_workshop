@extends('staff.layout.app')

@section('title', 'Profil – DPM Workshop')

@section('content')

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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Profil Saya</h1>
        <p class="text-xs text-slate-500">Informasi akun kamu</p>
      </div>
    </div>
    {{-- Tidak ada tombol edit untuk staff --}}
  </div>
</header>

<section class="p-4 sm:p-6">
  <div class="max-w-2xl mx-auto space-y-5">

    {{-- INFO BANNER --}}
    <div class="flex items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
      <svg class="h-5 w-5 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
      </svg>
      Perubahan data profil hanya bisa dilakukan oleh admin. Hubungi admin jika ada kesalahan data.
    </div>

    {{-- AVATAR CARD --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_16px_44px_rgba(2,6,23,0.08)] p-6">
      <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
        <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 border border-amber-200
                    grid place-items-center shadow-lg shrink-0">
          <span class="text-3xl font-bold text-white select-none">
            {{ strtoupper(substr($user->username, 0, 1)) }}
          </span>
        </div>

        <div class="text-center sm:text-left min-w-0">
          <h2 class="text-xl font-bold text-slate-900">{{ $user->username }}</h2>
          <p class="text-sm text-slate-500 mt-1">{{ $user->email }}</p>

          <div class="mt-3 flex flex-wrap justify-center sm:justify-start gap-2">
            <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50
                         px-3 py-1 text-xs font-semibold text-slate-700">
              <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20c0-2.2-2.7-4-5-4s-5 1.8-5 4"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              {{ ucfirst($user->role) }}
            </span>

            @if($user->status === 'aktif')
              <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50
                           px-3 py-1 text-xs font-semibold text-emerald-700">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                Aktif
              </span>
            @else
              <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50
                           px-3 py-1 text-xs font-semibold text-rose-700">
                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                Nonaktif
              </span>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- DETAIL INFO --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_16px_44px_rgba(2,6,23,0.08)] overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100">
        <p class="text-sm font-semibold text-slate-900">Informasi Akun</p>
        <p class="text-xs text-slate-500 mt-0.5">Data di bawah hanya bisa diubah oleh admin.</p>
      </div>

      <div class="divide-y divide-slate-100">
        @php
          $rows = [
            ['label' => 'Username',     'value' => $user->username,                               'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ['label' => 'Email',        'value' => $user->email,                                  'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
            ['label' => 'Nomor Kontak', 'value' => $user->kontak ?? '—',                          'icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'],
            ['label' => 'Role',         'value' => ucfirst($user->role),                          'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
            ['label' => 'Bergabung',    'value' => $user->created_at?->translatedFormat('d F Y'), 'icon' => 'M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
          ];
        @endphp

        @foreach($rows as $row)
          <div class="flex items-center gap-4 px-6 py-4">
            <div class="h-9 w-9 rounded-xl bg-slate-50 border border-slate-200 grid place-items-center shrink-0">
              <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $row['icon'] }}"/>
              </svg>
            </div>
            <div class="min-w-0 flex-1">
              <p class="text-xs text-slate-500">{{ $row['label'] }}</p>
              <p class="text-sm font-semibold text-slate-900 truncate">{{ $row['value'] }}</p>
            </div>
            {{-- Gembok kecil sebagai penanda read-only --}}
            <svg class="h-4 w-4 text-slate-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
          </div>
        @endforeach

        @if($user->catatan)
          <div class="flex items-start gap-4 px-6 py-4">
            <div class="h-9 w-9 rounded-xl bg-slate-50 border border-slate-200 grid place-items-center shrink-0 mt-0.5">
              <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
            </div>
            <div class="min-w-0 flex-1">
              <p class="text-xs text-slate-500">Catatan dari Admin</p>
              <p class="text-sm text-slate-900 whitespace-pre-line">{{ $user->catatan }}</p>
            </div>
            <svg class="h-4 w-4 text-slate-300 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
          </div>
        @endif
      </div>
    </div>

    {{-- BACK --}}
    <a href="{{ route('tampilan_dashboard_staff') }}"
       class="w-full inline-flex items-center justify-center gap-2 h-11 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold text-slate-900">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
      Kembali ke Dashboard
    </a>

    <p class="text-xs text-slate-400 text-center pb-2">© DPM Workshop 2025</p>
  </div>
</section>

@endsection
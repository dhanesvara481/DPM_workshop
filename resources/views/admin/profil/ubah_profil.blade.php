@extends('admin.layout.app')

@section('title', 'Edit Profil – DPM Workshop')

@section('content')

{{-- TOPBAR --}}
<header class="relative bg-white/75 backdrop-blur border-b border-slate-200 sticky top-0 z-20">
  <div class="h-16 px-4 sm:px-6 flex items-center justify-between gap-3">
    <div class="flex items-center gap-3 min-w-0">
      <button id="btnSidebar" type="button"
              class="md:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center">
        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <div class="min-w-0">
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Edit Profil</h1>
        <p class="text-xs text-slate-500">Perbarui informasi akun kamu</p>
      </div>
    </div>

    <a href="{{ route('tampilan_profil') }}"
       class="inline-flex items-center gap-2 h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold text-slate-700">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
      Kembali
    </a>
  </div>
</header>

<section class="p-4 sm:p-6">
  <div class="max-w-2xl mx-auto space-y-5">

    @if($errors->any())
      <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3">
        <p class="text-sm font-semibold text-rose-800 mb-1">Ada kesalahan:</p>
        <ul class="list-disc list-inside space-y-1">
          @foreach($errors->all() as $err)
            <li class="text-sm text-rose-700">{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('update_profil') }}" id="formEditProfil">
      @csrf
      @method('PUT')

      {{-- INFO DASAR --}}
      <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_16px_44px_rgba(2,6,23,0.08)] overflow-hidden mb-5">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
          <div class="h-8 w-8 rounded-xl bg-slate-900 grid place-items-center">
            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <p class="text-sm font-semibold text-slate-900">Informasi Dasar</p>
        </div>

        <div class="p-6 space-y-4">
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Username</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}" maxlength="20"
                   class="w-full h-11 rounded-xl border @error('username') border-rose-400 bg-rose-50 @else border-slate-200 bg-white @enderror px-4 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/15 focus:border-slate-400 transition"
                   placeholder="Masukkan username">
            @error('username')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" maxlength="100"
                   class="w-full h-11 rounded-xl border @error('email') border-rose-400 bg-rose-50 @else border-slate-200 bg-white @enderror px-4 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/15 focus:border-slate-400 transition"
                   placeholder="Masukkan email">
            @error('email')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor Kontak (WhatsApp)</label>
            <input type="text" name="kontak" value="{{ old('kontak', $user->kontak) }}" inputmode="numeric" maxlength="12"
                   class="w-full h-11 rounded-xl border @error('kontak') border-rose-400 bg-rose-50 @else border-slate-200 bg-white @enderror px-4 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/15 focus:border-slate-400 transition"
                   placeholder="Contoh: 081234567890">
            @error('kontak')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan <span class="font-normal text-slate-400">(opsional)</span></label>
            <textarea name="catatan" rows="3" maxlength="255"
                      class="w-full rounded-xl border @error('catatan') border-rose-400 bg-rose-50 @else border-slate-200 bg-white @enderror px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 resize-none focus:outline-none focus:ring-2 focus:ring-slate-900/15 focus:border-slate-400 transition"
                      placeholder="Tambahkan catatan...">{{ old('catatan', $user->catatan) }}</textarea>
            @error('catatan')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
          </div>
        </div>
      </div>

      {{-- GANTI PASSWORD --}}
      <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_16px_44px_rgba(2,6,23,0.08)] overflow-hidden mb-5">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-3">
          <div class="flex items-center gap-3">
            <div class="h-8 w-8 rounded-xl bg-slate-900 grid place-items-center">
              <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
            </div>
            <div>
              <p class="text-sm font-semibold text-slate-900">Ganti Password</p>
              <p class="text-xs text-slate-500">Kosongkan jika tidak ingin mengubah</p>
            </div>
          </div>
          <button type="button" id="togglePassword"
                  class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            Tampilkan
          </button>
        </div>

        <div class="p-6 space-y-4">
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password Baru</label>
            <div class="relative">
              <input type="password" name="password" id="inputPassword" autocomplete="new-password"
                     class="w-full h-11 rounded-xl border @error('password') border-rose-400 bg-rose-50 @else border-slate-200 bg-white @enderror px-4 pr-10 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/15 focus:border-slate-400 transition"
                     placeholder="Minimal 6 karakter">
              <button type="button" id="eyeBtn"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
              </button>
            </div>
            @error('password')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" id="inputPasswordConfirm" autocomplete="new-password"
                   class="w-full h-11 rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/15 focus:border-slate-400 transition"
                   placeholder="Ulangi password baru">
            <p id="pwMatchHint" class="mt-1.5 text-xs hidden"></p>
          </div>
        </div>
      </div>

      {{-- ACTIONS --}}
      <div class="flex flex-col sm:flex-row gap-3">
        <button type="submit"
                class="flex-1 inline-flex items-center justify-center gap-2 h-11 rounded-2xl border border-slate-900 bg-slate-900
                       hover:bg-slate-800 active:scale-[.98] transition text-sm font-semibold text-white">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
          Simpan Perubahan
        </button>
        <a href="{{ route('tampilan_profil') }}"
           class="inline-flex items-center justify-center h-11 px-6 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold text-slate-900">
          Batal
        </a>
      </div>

    </form>

    <p class="text-xs text-slate-400 text-center py-6">© DPM Workshop 2025</p>

    <button type="submit"
                class="w-full inline-flex items-center justify-center gap-2 h-11 rounded-2xl border border-slate-900 bg-slate-900
                        hover:bg-slate-800 active:scale-[.98] transition text-sm font-semibold text-white mt-2">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        Simpan Perubahan
    </button>
  </div>
</section>

@push('scripts')
<script>
  // Paksa scroll pada wrapper layout yang mungkin overflow-hidden
  document.addEventListener('DOMContentLoaded', () => {
    ['main', '#main', '#content', '.main-content', '[data-content]'].forEach(sel => {
      const el = document.querySelector(sel);
      if (el) el.style.overflowY = 'auto';
    });
  });

  // Toggle show/hide password
  const eyeBtn    = document.getElementById('eyeBtn');
  const inputPw   = document.getElementById('inputPassword');
  const inputPwC  = document.getElementById('inputPasswordConfirm');
  const toggleBtn = document.getElementById('togglePassword');
  let showing = false;

  function toggleShow() {
    showing = !showing;
    const t = showing ? 'text' : 'password';
    inputPw.type  = t;
    inputPwC.type = t;
    toggleBtn.textContent = showing ? 'Sembunyikan' : 'Tampilkan';
  }

  eyeBtn?.addEventListener('click', toggleShow);
  toggleBtn?.addEventListener('click', toggleShow);

  // Live password match hint
  const pwMatchHint = document.getElementById('pwMatchHint');
  function checkMatch() {
    const a = inputPw.value, b = inputPwC.value;
    if (!b.length) { pwMatchHint.classList.add('hidden'); return; }
    if (a === b) {
      pwMatchHint.className = 'mt-1.5 text-xs text-emerald-600';
      pwMatchHint.textContent = '✓ Password cocok';
    } else {
      pwMatchHint.className = 'mt-1.5 text-xs text-rose-600';
      pwMatchHint.textContent = '✗ Password tidak cocok';
    }
    pwMatchHint.classList.remove('hidden');
  }

  inputPw?.addEventListener('input', checkMatch);
  inputPwC?.addEventListener('input', checkMatch);
</script>
@endpush

@endsection
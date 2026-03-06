@extends('admin.layout.app')

@section('title', 'DPM Workshop – Admin')

@section('content')
<div class="relative z-10 px-4 py-6 sm:px-6 lg:px-8">
    <div class="mx-auto w-full max-w-4xl">

        {{-- HEADER --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Profil</h1>
                <p class="mt-1 text-sm text-slate-500">Perbarui informasi akun kamu</p>
            </div>

            <a href="{{ route('tampilan_profil') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>

        {{-- ERROR GLOBAL --}}
        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 shadow-sm">
                <p class="mb-2 text-sm font-semibold text-rose-800">Ada kesalahan:</p>
                <ul class="list-inside list-disc space-y-1">
                    @foreach($errors->all() as $err)
                        <li class="text-sm text-rose-700">{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('update_profil') }}" id="formEditProfil" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- INFORMASI DASAR --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white/95 shadow-sm backdrop-blur">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Informasi Dasar</h2>
                    <p class="mt-1 text-sm text-slate-500">Data utama akun pengguna</p>
                </div>

                <div class="grid grid-cols-1 gap-5 px-5 py-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" maxlength="20"
                               class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 @error('username') border-rose-300 bg-rose-50 focus:ring-rose-100 @else border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-100 @enderror"
                               placeholder="Masukkan username">
                        @error('username')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" maxlength="100"
                               class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 @error('email') border-rose-300 bg-rose-50 focus:ring-rose-100 @else border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-100 @enderror"
                               placeholder="Masukkan email">
                        @error('email')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Nomor Kontak (WhatsApp)</label>
                        <input type="text" name="kontak" value="{{ old('kontak', $user->kontak) }}" inputmode="numeric" maxlength="12"
                               class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 @error('kontak') border-rose-300 bg-rose-50 focus:ring-rose-100 @else border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-100 @enderror"
                               placeholder="Contoh: 081234567890">
                        @error('kontak')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Catatan <span class="text-slate-400">(opsional)</span>
                        </label>
                        <textarea name="catatan" rows="4" maxlength="255"
                                  class="w-full resize-none rounded-xl border px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 @error('catatan') border-rose-300 bg-rose-50 focus:ring-rose-100 @else border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-100 @enderror"
                                  placeholder="Tambahkan catatan...">{{ old('catatan', $user->catatan) }}</textarea>
                        @error('catatan')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- PASSWORD --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white/95 shadow-sm backdrop-blur">
                <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Ganti Password</h2>
                        <p class="mt-1 text-sm text-slate-500">Kosongkan jika tidak ingin mengubah password</p>
                    </div>

                  <button type="button" id="togglePassword"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-300">
                        Tampilkan
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-5 px-5 py-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Password Baru</label>
                        <div class="relative">
                            <input type="password" name="password" id="inputPassword" autocomplete="new-password"
                                   class="w-full rounded-xl border px-4 py-3 pr-11 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 @error('password') border-rose-300 bg-rose-50 focus:ring-rose-100 @else border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-100 @enderror"
                                   placeholder="Minimal 6 karakter">
                            <button type="button" id="eyeBtn"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="inputPasswordConfirm" autocomplete="new-password"
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                               placeholder="Ulangi password baru">
                        <p id="pwMatchHint" class="mt-1.5 hidden text-xs"></p>
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="flex justify-end">
              <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-300">
                    Simpan Perubahan
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-xs text-slate-400">© DPM Workshop 2025</p>
    </div>
</div>

@push('scripts')
<script>
    const eyeBtn = document.getElementById('eyeBtn');
    const inputPw = document.getElementById('inputPassword');
    const inputPwC = document.getElementById('inputPasswordConfirm');
    const toggleBtn = document.getElementById('togglePassword');
    const pwMatchHint = document.getElementById('pwMatchHint');

    let showing = false;

    function toggleShow() {
        showing = !showing;
        const type = showing ? 'text' : 'password';

        if (inputPw) inputPw.type = type;
        if (inputPwC) inputPwC.type = type;
        if (toggleBtn) toggleBtn.textContent = showing ? 'Sembunyikan' : 'Tampilkan';
    }

    function checkMatch() {
        if (!inputPw || !inputPwC || !pwMatchHint) return;

        const a = inputPw.value;
        const b = inputPwC.value;

        if (!b.length) {
            pwMatchHint.className = 'mt-1.5 hidden text-xs';
            pwMatchHint.textContent = '';
            return;
        }

        if (a === b) {
            pwMatchHint.className = 'mt-1.5 text-xs text-emerald-600';
            pwMatchHint.textContent = '✓ Password cocok';
        } else {
            pwMatchHint.className = 'mt-1.5 text-xs text-rose-600';
            pwMatchHint.textContent = '✗ Password tidak cocok';
        }
    }

    eyeBtn?.addEventListener('click', toggleShow);
    toggleBtn?.addEventListener('click', toggleShow);
    inputPw?.addEventListener('input', checkMatch);
    inputPwC?.addEventListener('input', checkMatch);
</script>
@endpush
@endsection
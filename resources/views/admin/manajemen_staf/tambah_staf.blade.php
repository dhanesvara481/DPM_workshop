{{-- resources/views/admin/manajemen_staf/tambah_staf.blade.php --}}
@extends('admin.layout.app')

@section('title', 'DPM Workshop - Admin')

@section('content')

{{-- TOPBAR --}}
<header class="sticky top-0 z-20 border-b border-slate-200 bg-white/80 backdrop-blur">
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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Tambah Staf</h1>
        <p class="text-xs text-slate-500">
          Buat akun staf agar bisa login (role otomatis <span class="font-semibold">staff</span>).
        </p>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('tampilan_notifikasi') }}"
         class="tip h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
         data-tip="Notifikasi" aria-label="Notifikasi">
        <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
        </svg>
      </a>
    </div>
  </div>
</header>

<section class="relative p-4 sm:p-6">
  {{-- BACKGROUND --}}
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
    <div class="absolute inset-0 opacity-[0.12]"
         style="background-image: linear-gradient(to right, rgba(2,6,23,0.06) 1px, transparent 1px), linear-gradient(to bottom, rgba(2,6,23,0.06) 1px, transparent 1px); background-size: 56px 56px;"></div>
    <div class="absolute inset-0 opacity-[0.18] mix-blend-screen animate-grid-scan"
         style="background-image: repeating-linear-gradient(90deg, transparent 0px, transparent 55px, rgba(255,255,255,0.95) 56px, transparent 57px, transparent 112px), repeating-linear-gradient(180deg, transparent 0px, transparent 55px, rgba(255,255,255,0.70) 56px, transparent 57px, transparent 112px); background-size: 112px 112px, 112px 112px;"></div>
    <div class="absolute -top-48 left-1/2 -translate-x-1/2 h-[720px] w-[720px] rounded-full blur-3xl opacity-10 bg-gradient-to-tr from-blue-950/25 via-blue-700/10 to-transparent"></div>
    <div class="absolute -bottom-72 right-1/4 h-[720px] w-[720px] rounded-full blur-3xl opacity-08 bg-gradient-to-tr from-blue-950/18 via-indigo-700/10 to-transparent"></div>
  </div>

  <div class="max-w-[920px] mx-auto w-full">

    {{-- BREADCRUMB --}}
    <nav class="mb-4 flex items-center gap-2 text-xs text-slate-500">
      <a href="{{ route('tampilan_manajemen_staf') }}" class="hover:text-slate-800 transition">Manajemen Staf</a>
      <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
      </svg>
      <span class="font-semibold text-slate-700">Tambah Staf</span>
    </nav>

    {{-- ALERTS --}}
    @if (session('success'))
      <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
        <div class="font-semibold">Berhasil</div>
        <div class="text-sm mt-0.5">{{ session('success') }}</div>
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-800">
        <div class="font-semibold">Ada error validasi</div>
        <ul class="list-disc ml-5 text-sm mt-1 space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200 shadow-[0_18px_48px_rgba(2,6,23,0.08)] overflow-hidden">

      {{-- Card header --}}
      <div class="p-5 sm:p-6 border-b border-slate-200">
        <div class="flex items-start justify-between gap-3">
          <div class="min-w-0">
            <div class="text-lg font-semibold text-slate-900">Form Tambah Staf</div>
            <div class="text-xs text-slate-500 mt-1">
              Admin tidak dapat membuat admin baru. Role dikunci sebagai <span class="font-semibold">staff</span>.
            </div>
          </div>
          <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border border-slate-200 bg-slate-50 text-slate-700">
            Role: staff
          </span>
        </div>
      </div>

      {{-- Form --}}
      <form id="formTambahStaf" action="{{ route('simpan_staf') }}" method="POST" class="p-5 sm:p-6">
        @csrf
        <input type="hidden" name="role" value="staff">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

          {{-- Username --}}
          <div>
            <label for="username" class="block text-sm font-semibold text-slate-800">
              Username <span class="text-rose-500">*</span>
            </label>
            <input id="username" name="username"
                   value="{{ old('username') }}"
                   maxlength="20" required
                   placeholder="contoh: asep01"
                   class="mt-2 h-11 w-full rounded-xl border @error('username') border-rose-300 @else border-slate-200 @enderror bg-white px-4 text-sm outline-none focus:ring-2 focus:ring-slate-200 transition">
            @error('username')
              <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @else
              <p class="mt-1.5 text-xs text-slate-500">Maks 20 karakter & harus unik.</p>
            @enderror
          </div>

          {{-- Email --}}
          <div>
            <label for="email" class="block text-sm font-semibold text-slate-800">
              Email <span class="text-rose-500">*</span>
            </label>
            <input id="email" name="email" type="email"
                   value="{{ old('email') }}"
                   required
                   placeholder="contoh: asep@gmail.com"
                   class="mt-2 h-11 w-full rounded-xl border @error('email') border-rose-300 @else border-slate-200 @enderror bg-white px-4 text-sm outline-none focus:ring-2 focus:ring-slate-200 transition">
            @error('email')
              <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @else
              <p class="mt-1.5 text-xs text-slate-500">Harus unik di sistem.</p>
            @enderror
          </div>

          {{-- Kontak --}}
          <div>
            <label for="kontak" class="block text-sm font-semibold text-slate-800">
              No HP / Kontak <span class="text-rose-500">*</span>
            </label>
            <input id="kontak" name="kontak"
                   value="{{ old('kontak') }}"
                   maxlength="12" required
                   placeholder="contoh: 081234567890"
                   inputmode="numeric"
                   class="mt-2 h-11 w-full rounded-xl border @error('kontak') border-rose-300 @else border-slate-200 @enderror bg-white px-4 text-sm outline-none focus:ring-2 focus:ring-slate-200 transition">
            @error('kontak')
              <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @else
              <p class="mt-1.5 text-xs text-slate-500">6–12 digit angka.</p>
            @enderror
          </div>

          {{-- Password --}}
          <div>
            <label for="password" class="block text-sm font-semibold text-slate-800">
              Password <span class="text-rose-500">*</span>
            </label>
            <div class="mt-2 relative">
              <input id="password" name="password" type="password"
                     required
                     placeholder="Minimal 6 karakter"
                     class="h-11 w-full rounded-xl border @error('password') border-rose-300 @else border-slate-200 @enderror bg-white px-4 pr-12 text-sm outline-none focus:ring-2 focus:ring-slate-200 transition">
              <button id="btnTogglePw" type="button"
                      class="absolute right-2 top-1/2 -translate-y-1/2 h-9 w-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                      aria-label="Lihat / sembunyikan password">
                {{-- Eye icon --}}
                <svg id="iconEye" class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{-- Eye-off icon (hidden by default) --}}
                <svg id="iconEyeOff" class="h-5 w-5 text-slate-600 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
              </button>
            </div>
            @error('password')
              <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @else
              <p class="mt-1.5 text-xs text-slate-500">Password akan di-hash di backend.</p>
            @enderror
          </div>

          {{-- Catatan --}}
          <div class="sm:col-span-2">
            <label for="catatan" class="block text-sm font-semibold text-slate-800">Catatan <span class="text-slate-400 font-normal">(opsional)</span></label>
            <textarea id="catatan" name="catatan" rows="3"
                      maxlength="255"
                      placeholder="Contoh: Mekanik / Keuangan / Marketing"
                      class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-slate-200 transition resize-none">{{ old('catatan') }}</textarea>
            <p class="mt-1.5 text-xs text-slate-500">Maks 255 karakter.</p>
          </div>
        </div>

        {{-- Divider --}}
        <div class="my-6 border-t border-slate-100"></div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-2 sm:justify-end">
          <a id="btnCancel" href="{{ route('tampilan_manajemen_staf') }}"
             class="inline-flex h-11 items-center justify-center rounded-xl px-5 text-sm font-semibold border border-slate-200 bg-white hover:bg-slate-50 transition">
            Batal
          </a>
          <button type="submit" id="btnSubmit"
                  class="inline-flex h-11 items-center justify-center gap-2 rounded-xl px-5 text-sm font-semibold bg-slate-900 text-white hover:bg-slate-800 transition shadow-[0_12px_24px_rgba(2,6,23,0.14)]">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan Staf
          </button>
        </div>
      </form>

      <div class="px-5 sm:px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
        © DPM Workshop 2025
      </div>
    </div>
  </div>
</section>

{{-- Toast --}}
<div id="toast"
     class="fixed bottom-6 right-6 z-50 hidden w-[340px] rounded-2xl border border-slate-200 bg-white/90 backdrop-blur px-4 py-3 shadow-[0_18px_48px_rgba(2,6,23,0.14)]">
  <div class="flex items-start gap-3">
    <div id="toastDot" class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></div>
    <div class="min-w-0">
      <p id="toastTitle" class="text-sm font-semibold text-slate-900">Info</p>
      <p id="toastMsg" class="text-xs text-slate-600 mt-0.5">—</p>
    </div>
    <button id="toastClose" class="ml-auto text-slate-500 hover:text-slate-800 transition" type="button" aria-label="Close">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>
</div>

@endsection

@push('head')
<style>
  @media (prefers-reduced-motion: reduce) {
    .animate-grid-scan { animation: none !important; }
  }
  @keyframes gridScan {
    0%   { background-position: 0 0, 0 0; opacity: 0.10; }
    40%  { opacity: 0.22; }
    60%  { opacity: 0.18; }
    100% { background-position: 220px 220px, -260px 260px; opacity: 0.10; }
  }
  .animate-grid-scan { animation: gridScan 8.5s ease-in-out infinite; }

  .tip { position: relative; }
  .tip[data-tip]::after {
    content: attr(data-tip);
    position: absolute; right: 0; top: calc(100% + 10px);
    background: rgba(15,23,42,.92); color: rgba(255,255,255,.92);
    font-size: 11px; padding: 6px 10px; border-radius: 10px;
    white-space: nowrap; opacity: 0; transform: translateY(-4px);
    pointer-events: none; transition: .15s ease;
  }
  .tip:hover::after { opacity: 1; transform: translateY(0); }

  @keyframes shake {
    0%   { transform: translateX(0) }
    25%  { transform: translateX(-6px) }
    50%  { transform: translateX(6px) }
    75%  { transform: translateX(-4px) }
    100% { transform: translateX(0) }
  }
  .shake { animation: shake .28s ease; }
</style>
@endpush

@push('scripts')
<script>
// ===== Toast =====
const toastEl    = document.getElementById('toast');
const toastTitle = document.getElementById('toastTitle');
const toastMsg   = document.getElementById('toastMsg');
const toastDot   = document.getElementById('toastDot');
let toastTimer   = null;

function showToast(title, msg, type = 'success') {
  if (!toastEl) return;
  toastTitle.textContent = title;
  toastMsg.textContent   = msg;
  toastDot.className = "mt-1 h-2.5 w-2.5 rounded-full " + (type === 'success' ? "bg-emerald-500" : "bg-red-500");
  toastEl.classList.remove('hidden');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => toastEl.classList.add('hidden'), 2600);
}
document.getElementById('toastClose')?.addEventListener('click', () => toastEl.classList.add('hidden'));

// ===== Confirm Modal =====
function showConfirmModal({ title, message, confirmText, cancelText, note, tone = "neutral", onConfirm }) {
  const toneMap = {
    neutral: { btn: "bg-slate-900 hover:bg-slate-800", noteBg: "bg-slate-50", noteBr: "border-slate-200", noteTx: "text-slate-600" },
    danger:  { btn: "bg-rose-600 hover:bg-rose-700",   noteBg: "bg-rose-50",   noteBr: "border-rose-200",  noteTx: "text-rose-700" },
  };
  const t = toneMap[tone] || toneMap.neutral;
  const wrap = document.createElement('div');
  wrap.className = "fixed inset-0 z-[999] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-3";
  wrap.innerHTML = `
    <div class="w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-[0_30px_80px_rgba(2,6,23,0.30)] overflow-hidden">
      <div class="p-5 border-b border-slate-200 flex items-start justify-between gap-3">
        <div>
          <div class="text-lg font-semibold text-slate-900">${title}</div>
          <div class="text-sm text-slate-600 mt-1">${message}</div>
        </div>
        <button type="button" class="btn-x h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 grid place-items-center">
          <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div class="p-5">
        <div class="rounded-xl border ${t.noteBr} ${t.noteBg} p-4 text-xs ${t.noteTx}">${note || 'Pastikan data sudah benar.'}</div>
        <div class="mt-4 flex justify-end gap-2">
          <button type="button" class="btn-cancel h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-semibold">${cancelText}</button>
          <button type="button" class="btn-ok h-10 px-5 rounded-xl ${t.btn} text-white text-sm font-semibold">${confirmText}</button>
        </div>
      </div>
    </div>`;

  const close = () => wrap.remove();
  wrap.addEventListener('click', e => { if (e.target === wrap) close(); });
  wrap.querySelector('.btn-x')?.addEventListener('click', close);
  wrap.querySelector('.btn-cancel')?.addEventListener('click', close);
  wrap.querySelector('.btn-ok')?.addEventListener('click', () => { close(); onConfirm?.(); });
  document.body.appendChild(wrap);
}

// ===== Toggle password visibility =====
const pw          = document.getElementById('password');
const btnTogglePw = document.getElementById('btnTogglePw');
const iconEye     = document.getElementById('iconEye');
const iconEyeOff  = document.getElementById('iconEyeOff');

btnTogglePw?.addEventListener('click', () => {
  if (!pw) return;
  const show = pw.type === 'password';
  pw.type = show ? 'text' : 'password';
  iconEye?.classList.toggle('hidden', show);
  iconEyeOff?.classList.toggle('hidden', !show);
});

// ===== Dirty checker =====
const form      = document.getElementById('formTambahStaf');
const btnCancel = document.getElementById('btnCancel');

const snapshot = () => {
  if (!form) return "";
  const fd = new FormData(form), obj = {};
  fd.forEach((v, k) => obj[k] = String(v));
  return JSON.stringify(obj);
};
let snap0 = snapshot();
const isDirty = () => snap0 !== snapshot();

// ===== Cancel — konfirmasi jika ada perubahan =====
btnCancel?.addEventListener('click', e => {
  if (!isDirty()) return;
  e.preventDefault();
  const go = btnCancel.getAttribute('href');
  showConfirmModal({
    title:       "Batalkan input?",
    message:     "Data yang sudah diisi akan hilang kalau keluar sekarang.",
    confirmText: "Ya, Keluar",
    cancelText:  "Tetap di sini",
    tone:        "danger",
    note:        'Klik "Tetap di sini" kalau masih mau lanjut isi form.',
    onConfirm:   () => window.location.href = go
  });
});

// ===== Submit — validasi cepat + konfirmasi =====
form?.addEventListener('submit', e => {
  if (form.dataset.confirmed === "1") return;
  e.preventDefault();

  const u  = document.getElementById('username');
  const em = document.getElementById('email');
  const k  = document.getElementById('kontak');
  const p  = document.getElementById('password');

  // Validasi wajib
  const fields = [u, em, k, p];
  let ok = true;
  fields.forEach(el => {
    if (!el || !String(el.value || '').trim()) {
      ok = false;
      el?.classList.add('border-rose-300', 'shake');
      setTimeout(() => el?.classList.remove('shake'), 300);
    } else {
      el?.classList.remove('border-rose-300');
    }
  });

  if (!ok) {
    showToast('Periksa form', 'Lengkapi semua field yang wajib diisi.', 'error');
    return;
  }

  // Validasi kontak: hanya angka, 6–12 digit
  if (!/^\d{6,12}$/.test(k.value.trim())) {
    k.classList.add('border-rose-300', 'shake');
    setTimeout(() => k.classList.remove('shake'), 300);
    showToast('Format kontak salah', 'Kontak harus berupa angka (6–12 digit).', 'error');
    return;
  }

  // Validasi password minimal 6 karakter
  if (p.value.trim().length < 6) {
    p.classList.add('border-rose-300', 'shake');
    setTimeout(() => p.classList.remove('shake'), 300);
    showToast('Password terlalu pendek', 'Password minimal 6 karakter.', 'error');
    return;
  }

  showConfirmModal({
    title:       "Simpan staf baru?",
    message:     "Akun staf baru akan dibuat dan langsung bisa digunakan untuk login.",
    confirmText: "Ya, Simpan",
    cancelText:  "Batal",
    note:        "Pastikan username & email benar karena harus unik di sistem.",
    onConfirm: () => {
      form.dataset.confirmed = "1";
      snap0 = snapshot();
      form.submit();
    }
  });
});
</script>
@endpush
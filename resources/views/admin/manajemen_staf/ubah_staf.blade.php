{{-- resources/views/admin/manajemen_staf/ubah_staf.blade.php --}}
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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Ubah Staf</h1>
        <p class="text-xs text-slate-500">
          Edit data staf. Role tetap <span class="font-semibold">staff</span>.
        </p>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <a href="/tampilan_notifikasi"
         class="tip h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
         data-tip="Notifikasi"
         aria-label="Notifikasi">
        <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
        </svg>
      </a>
    </div>
  </div>
</header>

<section class="relative p-4 sm:p-6">
  {{-- BACKGROUND (opsional) --}}
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
    <div class="absolute inset-0 opacity-[0.12]"
         style="background-image:
            linear-gradient(to right, rgba(2,6,23,0.06) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(2,6,23,0.06) 1px, transparent 1px);
            background-size: 56px 56px;">
    </div>
    <div class="absolute inset-0 opacity-[0.18] mix-blend-screen animate-grid-scan"
         style="background-image:
            repeating-linear-gradient(90deg, transparent 0px, transparent 55px, rgba(255,255,255,0.95) 56px, transparent 57px, transparent 112px),
            repeating-linear-gradient(180deg, transparent 0px, transparent 55px, rgba(255,255,255,0.70) 56px, transparent 57px, transparent 112px);
            background-size: 112px 112px, 112px 112px;">
    </div>
    <div class="absolute -top-48 left-1/2 -translate-x-1/2 h-[720px] w-[720px] rounded-full blur-3xl opacity-10
                bg-gradient-to-tr from-blue-950/25 via-blue-700/10 to-transparent"></div>
    <div class="absolute -bottom-72 right-1/4 h-[720px] w-[720px] rounded-full blur-3xl opacity-08
                bg-gradient-to-tr from-blue-950/18 via-indigo-700/10 to-transparent"></div>
  </div>

  <div class="max-w-[920px] mx-auto w-full">

    {{-- ALERTS --}}
    @if (session('success'))
      <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
        <div class="font-semibold">Berhasil</div>
        <div class="text-sm mt-0.5">{{ session('success') }}</div>
      </div>
    @endif

    @if (session('error'))
      <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-800">
        <div class="font-semibold">Gagal</div>
        <div class="text-sm mt-0.5">{{ session('error') }}</div>
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-800">
        <div class="font-semibold">Ada error</div>
        <ul class="list-disc ml-5 text-sm mt-1 space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200 shadow-[0_18px_48px_rgba(2,6,23,0.08)] overflow-hidden">
      <div class="p-5 sm:p-6 border-b border-slate-200">
        <div class="flex items-start justify-between gap-3">
          <div class="min-w-0">
            <div class="text-lg font-semibold text-slate-900">Form Ubah Staf</div>
            <div class="text-xs text-slate-500 mt-1">
              Edit data login dan profil staf. Password opsional (kosongkan jika tidak ingin mengganti).
            </div>
          </div>

          <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border border-slate-200 bg-slate-50 text-slate-700">
            Role: staff
          </span>
        </div>
      </div>

      <form id="formUbahStaf" action="/ubah_staf/{{ $staf->id ?? '' }}" method="POST" class="p-5 sm:p-6">
        @csrf
        @method('PUT')

        <input type="hidden" name="role" value="staff">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-slate-800">Username</label>
            <input id="username" name="username"
                   value="{{ old('username', $staf->username ?? '') }}"
                   maxlength="20" required
                   placeholder="contoh: asep01"
                   class="mt-2 h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:ring-2 focus:ring-slate-200">
            <p class="mt-2 text-xs text-slate-500">Maks 20 karakter & harus unik.</p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-800">Email</label>
            <input id="email" name="email" type="email"
                   value="{{ old('email', $staf->email ?? '') }}"
                   required
                   placeholder="contoh: asep@gmail.com"
                   class="mt-2 h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:ring-2 focus:ring-slate-200">
            <p class="mt-2 text-xs text-slate-500">Harus unik.</p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-800">Kontak (No HP)</label>
            <input id="kontak" name="kontak"
                   value="{{ old('kontak', $staf->kontak ?? '') }}"
                   maxlength="12" required
                   placeholder="contoh: 081234567890"
                   class="mt-2 h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:ring-2 focus:ring-slate-200">
            <p class="mt-2 text-xs text-slate-500">Maks 12 digit (sesuaikan kalau perlu).</p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-800">Password (opsional)</label>
            <div class="mt-2 relative">
              <input id="password" name="password" type="password"
                     placeholder="Kosongkan jika tidak diganti"
                     class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 pr-12 text-sm outline-none focus:ring-2 focus:ring-slate-200">
              <button id="btnTogglePw" type="button"
                      class="absolute right-2 top-1/2 -translate-y-1/2 h-9 w-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                      aria-label="Lihat password">
                <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </button>
            </div>
            <p class="mt-2 text-xs text-slate-500">Kalau diisi, password baru akan di-hash di backend.</p>
          </div>

          <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-slate-800">Catatan (opsional)</label>
            <textarea id="catatan" name="catatan" rows="3"
                      placeholder="Contoh: Mekanik / Keuangan / Marketing"
                      class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-slate-200">{{ old('catatan', $staf->catatan ?? '') }}</textarea>
            <p class="mt-2 text-xs text-slate-500">Opsional.</p>
          </div>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:justify-end">
          <a id="btnCancel" href="/tampilan_manajemen_staf"
             class="inline-flex h-11 items-center justify-center rounded-xl px-5 text-sm font-semibold border border-slate-200 bg-white hover:bg-slate-50 transition">
            Batal
          </a>

          <button id="btnSubmit" type="submit"
                  class="inline-flex h-11 items-center justify-center rounded-xl px-5 text-sm font-semibold bg-slate-900 text-white hover:bg-slate-800 transition shadow-[0_12px_24px_rgba(2,6,23,0.14)]">
            Update
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
      <p id="toastTitle" class="text-sm font-semibold text-slate-900">Berhasil</p>
      <p id="toastMsg" class="text-xs text-slate-600 mt-0.5">Aksi berhasil.</p>
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
    .animate-grid-scan { animation: none !important; transition: none !important; }
  }
  @keyframes gridScan {
    0%   { background-position: 0 0, 0 0; opacity: 0.10; }
    40%  { opacity: 0.22; }
    60%  { opacity: 0.18; }
    100% { background-position: 220px 220px, -260px 260px; opacity: 0.10; }
  }
  .animate-grid-scan { animation: gridScan 8.5s ease-in-out infinite; }

  /* tooltip notifikasi */
  .tip{ position: relative; }
  .tip[data-tip]::after{
    content: attr(data-tip);
    position:absolute;
    right:0;
    top: calc(100% + 10px);
    background: rgba(15,23,42,.92);
    color: rgba(255,255,255,.92);
    font-size: 11px;
    padding: 6px 10px;
    border-radius: 10px;
    white-space: nowrap;
    opacity:0;
    transform: translateY(-4px);
    pointer-events:none;
    transition: .15s ease;
  }
  .tip:hover::after{ opacity:1; transform: translateY(0); }

  /* shake */
  @keyframes shake {
    0% { transform: translateX(0) }
    25% { transform: translateX(-6px) }
    50% { transform: translateX(6px) }
    75% { transform: translateX(-4px) }
    100% { transform: translateX(0) }
  }
  .shake { animation: shake .28s ease; }
</style>
@endpush

@push('scripts')
<script>
  // ===== Toast =====
  const toastEl = document.getElementById('toast');
  const toastTitle = document.getElementById('toastTitle');
  const toastMsg = document.getElementById('toastMsg');
  const toastDot = document.getElementById('toastDot');
  const toastClose = document.getElementById('toastClose');
  let toastTimer = null;

  const showToast = (title, msg, type='success') => {
    if (!toastEl) return;
    toastTitle.textContent = title;
    toastMsg.textContent = msg;
    toastDot.className = "mt-1 h-2.5 w-2.5 rounded-full " + (type==='success' ? "bg-emerald-500" : "bg-red-500");
    toastEl.classList.remove('hidden');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toastEl.classList.add('hidden'), 2600);
  };
  toastClose?.addEventListener('click', () => toastEl.classList.add('hidden'));

  // ===== Confirm Modal (custom) =====
  function showConfirmModal({ title, message, confirmText, cancelText, note, tone = "neutral", onConfirm }) {
    const toneMap = {
      neutral: { btn: "bg-slate-900 hover:bg-slate-800", noteBg:"bg-slate-50", noteBr:"border-slate-200", noteTx:"text-slate-600" },
      danger:  { btn: "bg-rose-600 hover:bg-rose-700",  noteBg:"bg-rose-50",  noteBr:"border-rose-200",  noteTx:"text-rose-700" },
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
          <div class="rounded-xl border ${t.noteBr} ${t.noteBg} p-4 text-xs ${t.noteTx}">
            ${note || 'Pastikan data yang kamu isi sudah benar.'}
          </div>
          <div class="mt-4 flex justify-end gap-2">
            <button type="button" class="btn-cancel h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-semibold">${cancelText}</button>
            <button type="button" class="btn-ok h-10 px-5 rounded-xl ${t.btn} text-white text-sm font-semibold">${confirmText}</button>
          </div>
        </div>
      </div>
    `;

    function close(){ wrap.remove(); }
    wrap.addEventListener('click', (e)=>{ if(e.target===wrap) close(); });
    wrap.querySelector('.btn-x')?.addEventListener('click', close);
    wrap.querySelector('.btn-cancel')?.addEventListener('click', close);
    wrap.querySelector('.btn-ok')?.addEventListener('click', ()=>{ close(); onConfirm?.(); });

    document.body.appendChild(wrap);
  }

  // ===== Toggle password =====
  const pw = document.getElementById('password');
  const btnTogglePw = document.getElementById('btnTogglePw');
  btnTogglePw?.addEventListener('click', () => {
    if (!pw) return;
    pw.type = pw.type === 'password' ? 'text' : 'password';
  });

  // ===== Dirty checker (tanpa beforeunload) =====
  const form = document.getElementById('formUbahStaf');
  const btnCancel = document.getElementById('btnCancel');

  const snapshot = () => {
    if (!form) return "";
    const fd = new FormData(form);
    const obj = {};
    fd.forEach((v, k) => obj[k] = String(v));
    return JSON.stringify(obj);
  };

  let snap0 = snapshot();
  const isDirty = () => snap0 !== snapshot();

  // ===== Cancel (modal custom) =====
  btnCancel?.addEventListener('click', (e) => {
    if (!isDirty()) return;

    e.preventDefault();
    const go = btnCancel.getAttribute('href');

    showConfirmModal({
      title: "Batalkan perubahan?",
      message: "Perubahan belum disimpan. Kalau keluar sekarang, perubahan akan hilang.",
      confirmText: "Ya, Keluar",
      cancelText: "Tetap di sini",
      note: "Klik “Tetap di sini” kalau masih mau lanjut edit data.",
      tone: "danger",
      onConfirm: () => window.location.href = go
    });
  });

  // ===== Submit (modal custom + validasi cepat) =====
  form?.addEventListener('submit', (e) => {
    if (form.dataset.confirmed === "1") return;

    e.preventDefault();

    const u = document.getElementById('username');
    const em = document.getElementById('email');
    const k = document.getElementById('kontak');
    const p = document.getElementById('password'); // opsional

    // validasi wajib
    const required = [u, em, k];
    let ok = true;

    required.forEach(el => {
      if (!el || !String(el.value || '').trim()) {
        ok = false;
        el?.classList.add('border-rose-300', 'shake');
        setTimeout(() => el?.classList.remove('shake'), 300);
      } else {
        el?.classList.remove('border-rose-300');
      }
    });

    if (!ok) {
      showToast('Gagal', 'Lengkapi field yang wajib diisi.', 'error');
      return;
    }

    // kontak numeric 6-12 digit
    const kontakVal = String(k.value || '').trim();
    if (!/^\d{6,12}$/.test(kontakVal)) {
      k.classList.add('border-rose-300', 'shake');
      setTimeout(() => k.classList.remove('shake'), 300);
      showToast('Gagal', 'Kontak harus angka (6-12 digit).', 'error');
      return;
    }

    // kalau password diisi, minimal 6 char (biar aman)
    const pwVal = String(p?.value || '').trim();
    if (pwVal && pwVal.length < 6) {
      p.classList.add('border-rose-300', 'shake');
      setTimeout(() => p.classList.remove('shake'), 300);
      showToast('Gagal', 'Password minimal 6 karakter.', 'error');
      return;
    }

    showConfirmModal({
      title: "Simpan perubahan staf?",
      message: "Data staf akan diperbarui.",
      confirmText: "Ya, Update",
      cancelText: "Batal",
      note: "Pastikan username & email benar karena harus unik. Password boleh dikosongkan jika tidak diganti.",
      onConfirm: () => {
        form.dataset.confirmed = "1";
        snap0 = snapshot();
        form.submit();
      }
    });
  });
</script>
@endpush

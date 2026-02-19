@extends('admin.layout.app')

@section('title', 'Ubah Barang - DPM Workshop')

@section('content')

{{-- TOPBAR --}}
<header class="relative h-16 bg-white/75 backdrop-blur border-b border-slate-200 sticky top-0 z-20">
  <div class="h-full px-4 sm:px-6 flex items-center justify-between gap-3">

    <div class="flex items-center gap-3 min-w-0">
      <button id="btnSidebar"
              type="button"
              class="md:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
              aria-label="Buka menu">
        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      <div class="min-w-0">
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Ubah Barang</h1>
        <p class="text-xs text-slate-500">Edit data barang, lalu simpan perubahan.</p>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <button type="button"
              class="tip h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
              data-tip="Notifikasi"
              aria-label="Notifikasi">
        <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
        </svg>
      </button>

      <a href="{{ route('mengelola_barang') }}"
         id="btnBackBarang"
         class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition px-3 py-2 text-sm">
        <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
      </a>
    </div>

  </div>
</header>

{{-- CONTENT --}}
<section class="relative p-4 sm:p-6">
  <div class="max-w-[980px] mx-auto w-full space-y-6">

    <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200 shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">
      <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between">
        <div>
          <p class="text-sm font-semibold text-slate-900">Form Ubah Barang</p>
          <p class="text-xs text-slate-500">Edit data, lalu simpan perubahan.</p>
        </div>

        <span class="hidden sm:inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs text-slate-600">
          <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
          Ready
        </span>
      </div>

      {{-- FORM --}}
      <form id="formBarang"
            method="POST"
            action="{{ route('perbarui_barang', $barang->barang_id ?? 0) }}"
            class="px-6 py-6">
        @csrf
        @method('PUT')

        @php
          $sat = old('satuan', $barang->satuan ?? '');
          $hb  = (int) ($barang->harga_beli ?? 0);
          $hj  = (int) ($barang->harga_jual ?? 0);
        @endphp

        {{-- Session & Validation Alerts --}}
        @if(session('success'))
          <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
            <div class="flex items-start gap-3">
              <svg class="h-5 w-5 text-emerald-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <p class="text-sm text-emerald-800">{{ session('success') }}</p>
            </div>
          </div>
        @endif

        @if(session('error'))
          <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
            <div class="flex items-start gap-3">
              <svg class="h-5 w-5 text-red-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <p class="text-sm text-red-800">{{ session('error') }}</p>
            </div>
          </div>
        @endif

        @if($errors->any())
          <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
            <div class="flex items-start gap-3">
              <svg class="h-5 w-5 text-red-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <div class="flex-1">
                <p class="text-sm font-semibold text-red-800">Terdapat kesalahan:</p>
                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

          {{-- Kode Barang --}}
          <div class="field">
            <label class="block text-xs font-semibold tracking-widest text-slate-600 mb-2">KODE BARANG</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10M7 12h10M7 17h10"/>
                </svg>
              </span>
              <input id="kode_barang" name="kode_barang" type="text" required
                     value="{{ old('kode_barang', $barang->kode_barang ?? '') }}"
                     placeholder="Contoh: BRG-001"
                     class="w-full pl-9 pr-3 py-3 rounded-xl border border-slate-200 bg-white/95 text-sm
                            placeholder:text-slate-400
                            focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
            </div>
            <p class="mt-2 text-[11px] text-slate-500">Kode boleh diedit bila diperlukan.</p>
          </div>

          {{-- Nama Barang --}}
          <div class="field">
            <label class="block text-xs font-semibold tracking-widest text-slate-600 mb-2">NAMA BARANG</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16"/>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V8a2 2 0 012-2h6a2 2 0 012 2v8"/>
                </svg>
              </span>
              <input id="nama_barang" name="nama_barang" type="text" required
                     value="{{ old('nama_barang', $barang->nama_barang ?? '') }}"
                     placeholder="Contoh: Oli Mesin"
                     class="w-full pl-9 pr-3 py-3 rounded-xl border border-slate-200 bg-white/95 text-sm
                            placeholder:text-slate-400
                            focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
            </div>
          </div>

          {{-- Satuan --}}
          <div class="field md:col-span-2">
            <label class="block text-xs font-semibold tracking-widest text-slate-600 mb-2">SATUAN</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6M9 12h6M9 17h6"/>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 4h14v16H5z"/>
                </svg>
              </span>
              <select id="satuan" name="satuan" required
                      class="w-full pl-9 pr-3 py-3 rounded-xl border border-slate-200 bg-white/95 text-sm
                             focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                <option value="" disabled {{ $sat ? '' : 'selected' }}>Pilih satuan</option>
                @foreach (['pcs','unit','botol','liter','gram','set'] as $opt)
                  <option value="{{ $opt }}" {{ $sat === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
              </select>
            </div>
          </div>

          {{-- SECTION HARGA --}}
          <div class="md:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-slate-50/40 p-4">
              <div class="flex items-center justify-between mb-4">
                <div>
                  <p class="text-xs font-semibold tracking-widest text-slate-600">HARGA</p>
                  <p class="text-[11px] text-slate-500 mt-1">Perbarui harga beli & jual.</p>
                </div>
                <span class="text-[11px] text-slate-500">Preview di bawah</span>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Harga Beli --}}
                <div class="field">
                  <label class="block text-xs font-semibold tracking-widest text-slate-600 mb-2">HARGA BELI</label>
                  <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm font-semibold">Rp</span>
                    <input id="harga_beli" name="harga_beli" type="text" inputmode="numeric" required
                           value="{{ old('harga_beli', number_format($hb, 0, ',', '.')) }}"
                           placeholder="0"
                           class="money w-full pl-10 pr-3 py-3 rounded-xl border border-slate-200 bg-white text-sm
                                  placeholder:text-slate-400
                                  focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                  </div>
                  <p class="mt-2 text-[11px] text-slate-500">Masukkan harga dalam format angka.</p>
                </div>

                {{-- Harga Jual --}}
                <div class="field">
                  <label class="block text-xs font-semibold tracking-widest text-slate-600 mb-2">HARGA JUAL</label>
                  <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm font-semibold">Rp</span>
                    <input id="harga_jual" name="harga_jual" type="text" inputmode="numeric" required
                           value="{{ old('harga_jual', number_format($hj, 0, ',', '.')) }}"
                           placeholder="0"
                           class="money w-full pl-10 pr-3 py-3 rounded-xl border border-slate-200 bg-white text-sm
                                  placeholder:text-slate-400
                                  focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
                  </div>
                  <p class="mt-2 text-[11px] text-slate-500">Disarankan ≥ harga beli.</p>
                </div>

                {{-- Preview --}}
                <div class="md:col-span-2">
                  <div class="rounded-xl border border-slate-200 bg-white px-4 py-3">
                    <div class="flex items-center justify-between">
                      <div class="text-[11px] text-slate-500">
                        Preview Harga <span class="ml-2 text-[11px] text-slate-400">(Beli, Jual, Selisih)</span>
                      </div>
                      <div class="text-[11px] text-slate-400">Live</div>
                    </div>

                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-3">
                      <div class="rounded-xl border border-slate-200 bg-slate-50/40 px-4 py-3">
                        <div class="text-[11px] tracking-widest text-slate-500 font-semibold">HARGA BELI</div>
                        <div id="previewBeli" class="mt-1 text-sm font-semibold text-slate-900">Rp 0</div>
                      </div>

                      <div class="rounded-xl border border-slate-200 bg-slate-50/40 px-4 py-3">
                        <div class="text-[11px] tracking-widest text-slate-500 font-semibold">HARGA JUAL</div>
                        <div id="previewJual" class="mt-1 text-sm font-semibold text-slate-900">Rp 0</div>
                      </div>

                      <div class="rounded-xl border border-slate-200 bg-slate-50/40 px-4 py-3">
                        <div class="text-[11px] tracking-widest text-slate-500 font-semibold">SELISIH</div>
                        <div id="previewSelisih" class="mt-1 text-sm font-semibold text-slate-900">Rp 0</div>
                        <div id="selisihHint" class="mt-1 text-[11px] text-slate-500">—</div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

        </div>

        {{-- Actions --}}
        <div class="mt-7 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
          <button type="button" id="btnReset"
                  class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition px-4 py-2.5 text-sm font-semibold">
            Reset
          </button>

          <button type="submit" id="btnSave"
                  class="btn-shine inline-flex items-center justify-center gap-2 rounded-xl bg-blue-950 hover:bg-blue-900 transition px-5 py-2.5 text-sm font-semibold text-white
                         shadow-[0_12px_24px_rgba(2,6,23,0.16)]">
            Simpan Perubahan
          </button>
        </div>

      </form>

      <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
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
      <p id="toastMsg" class="text-xs text-slate-600 mt-0.5">Perubahan tersimpan.</p>
    </div>
    <button id="toastClose"
            class="ml-auto text-slate-500 hover:text-slate-800 transition"
            type="button"
            aria-label="Close">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>
</div>

<style>
  @media (prefers-reduced-motion: reduce) {
    .btn-shine { animation: none !important; transition: none !important; }
  }

  .btn-shine { position: relative; overflow: hidden; }
  .btn-shine::after {
    content: "";
    position: absolute;
    inset: 0;
    transform: translateX(-120%);
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.28), transparent);
    transition: transform .65s ease;
  }
  .btn-shine:hover::after { transform: translateX(120%); }

  @keyframes shake {
    0%   { transform: translateX(0)   }
    25%  { transform: translateX(-6px) }
    50%  { transform: translateX(6px)  }
    75%  { transform: translateX(-4px) }
    100% { transform: translateX(0)   }
  }
  .shake { animation: shake .28s ease; }

  .tip { position: relative; }
  .tip[data-tip]::after {
    content: attr(data-tip);
    position: absolute;
    right: 0;
    top: calc(100% + 10px);
    background: rgba(15,23,42,.92);
    color: rgba(255,255,255,.92);
    font-size: 11px;
    padding: 6px 10px;
    border-radius: 10px;
    white-space: nowrap;
    opacity: 0;
    transform: translateY(-4px);
    pointer-events: none;
    transition: .15s ease;
  }
  .tip:hover::after { opacity: 1; transform: translateY(0); }
</style>

<script>
  // --- helpers ---
  const rupiah     = (n) => 'Rp ' + Number(n || 0).toLocaleString('id-ID');
  const parseMoney = (s) => Number(String(s || '').replace(/[^\d]/g, '')) || 0;
  const formatMoneyInput = (el) => {
    const v = parseMoney(el.value);
    el.value = v > 0 ? v.toLocaleString('id-ID') : '';
    return v;
  };

  // --- toast ---
  const toastEl    = document.getElementById('toast');
  const toastTitle = document.getElementById('toastTitle');
  const toastMsg   = document.getElementById('toastMsg');
  const toastDot   = document.getElementById('toastDot');
  let toastTimer   = null;

  const showToast = (title, msg, type = 'success') => {
    if (!toastEl) return;
    toastTitle.textContent = title;
    toastMsg.textContent   = msg;
    toastDot.className     = 'mt-1 h-2.5 w-2.5 rounded-full ' + (type === 'success' ? 'bg-emerald-500' : 'bg-red-500');
    toastEl.classList.remove('hidden');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toastEl.classList.add('hidden'), 2400);
  };
  document.getElementById('toastClose')?.addEventListener('click', () => toastEl.classList.add('hidden'));

  // --- confirm modal kustom ---
  function showConfirmModal({ title, message, confirmText, cancelText, note, tone = 'neutral', onConfirm }) {
    const toneMap = {
      neutral: { btn: 'bg-slate-900 hover:bg-slate-800', noteBg: 'bg-slate-50', noteBr: 'border-slate-200', noteTx: 'text-slate-600' },
      danger:  { btn: 'bg-rose-600 hover:bg-rose-700',  noteBg: 'bg-rose-50',  noteBr: 'border-rose-200',  noteTx: 'text-rose-700'  },
    };
    const t = toneMap[tone] || toneMap.neutral;

    const wrap = document.createElement('div');
    wrap.className = 'fixed inset-0 z-[999] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-3';
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
          <div class="rounded-xl border ${t.noteBr} ${t.noteBg} p-4 text-xs ${t.noteTx}">${note || 'Pastikan perubahan yang kamu lakukan sudah benar.'}</div>
          <div class="mt-4 flex justify-end gap-2">
            <button type="button" class="btn-cancel h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-semibold">${cancelText}</button>
            <button type="button" class="btn-ok h-10 px-5 rounded-xl ${t.btn} text-white text-sm font-semibold">${confirmText}</button>
          </div>
        </div>
      </div>`;

    const close = () => wrap.remove();
    wrap.addEventListener('click', (e) => { if (e.target === wrap) close(); });
    wrap.querySelector('.btn-x')?.addEventListener('click', close);
    wrap.querySelector('.btn-cancel')?.addEventListener('click', close);
    wrap.querySelector('.btn-ok')?.addEventListener('click', () => { close(); onConfirm?.(); });
    document.body.appendChild(wrap);
  }

  // --- elements ---
  const form    = document.getElementById('formBarang');
  const beliEl  = document.getElementById('harga_beli');
  const jualEl  = document.getElementById('harga_jual');

  const previewBeli    = document.getElementById('previewBeli');
  const previewJual    = document.getElementById('previewJual');
  const previewSelisih = document.getElementById('previewSelisih');
  const selisihHint    = document.getElementById('selisihHint');

  // --- preview harga ---
  const updatePreview = () => {
    const b = parseMoney(beliEl?.value);
    const j = parseMoney(jualEl?.value);
    const s = j - b;

    if (previewBeli)    previewBeli.textContent    = rupiah(b);
    if (previewJual)    previewJual.textContent    = rupiah(j);
    if (previewSelisih) previewSelisih.textContent = rupiah(Math.abs(s));

    if (selisihHint) {
      if (!b && !j)   selisihHint.textContent = '—';
      else if (s > 0) selisihHint.textContent = 'Untung';
      else if (s < 0) selisihHint.textContent = 'Rugi';
      else            selisihHint.textContent = 'Impas';
    }
  };

  document.querySelectorAll('.money').forEach(el => {
    el.addEventListener('input', () => { formatMoneyInput(el); updatePreview(); });
    el.addEventListener('blur',  () => { formatMoneyInput(el); updatePreview(); });
  });

  // init preview dengan data dari server
  if (beliEl) formatMoneyInput(beliEl);
  if (jualEl) formatMoneyInput(jualEl);
  updatePreview();

  // --- dirty guard (snapshot-based, lebih akurat) ---
  let allowUnload = false;

  const getSnapshot = () => {
    if (!form) return '';
    const fd  = new FormData(form);
    const obj = {};
    fd.forEach((v, k) => { obj[k] = String(v); });
    return JSON.stringify(obj);
  };
  let snap0 = getSnapshot();

  const isDirty = () => form && snap0 !== getSnapshot();

  window.addEventListener('beforeunload', (e) => {
    if (allowUnload || !isDirty()) return;
    e.preventDefault();
    e.returnValue = '';
  });

  // --- reset ---
  document.getElementById('btnReset')?.addEventListener('click', () => {
    if (!isDirty()) {
      form.reset();
      if (beliEl) formatMoneyInput(beliEl);
      if (jualEl) formatMoneyInput(jualEl);
      updatePreview();
      showToast('Reset', 'Form dikembalikan ke data awal.', 'success');
      return;
    }

    showConfirmModal({
      title: 'Reset perubahan?',
      message: 'Semua perubahan akan dikembalikan ke data awal.',
      confirmText: 'Ya, Reset',
      cancelText: 'Batal',
      note: 'Kalau kamu yakin, klik "Ya, Reset".',
      tone: 'danger',
      onConfirm: () => {
        form.reset();
        if (beliEl) formatMoneyInput(beliEl);
        if (jualEl) formatMoneyInput(jualEl);
        updatePreview();
        snap0 = getSnapshot();
        showToast('Reset', 'Form dikembalikan ke data awal.', 'success');
      }
    });
  });

  // --- kembali guard ---
  document.getElementById('btnBackBarang')?.addEventListener('click', (e) => {
    if (!isDirty()) return;
    e.preventDefault();
    const go = e.currentTarget.getAttribute('href');
    showConfirmModal({
      title: 'Keluar dari halaman?',
      message: 'Perubahan belum disimpan. Kalau keluar sekarang, perubahan akan hilang.',
      confirmText: 'Ya, Keluar',
      cancelText: 'Tetap di sini',
      note: 'Klik "Tetap di sini" kalau masih mau lanjut edit.',
      onConfirm: () => { allowUnload = true; window.location.href = go; }
    });
  });

  // --- submit ---
  form?.addEventListener('submit', (e) => {
    if (form.dataset.confirmed === '1') return;
    e.preventDefault();

    // validasi client-side
    const requiredIds = ['kode_barang', 'nama_barang', 'satuan', 'harga_beli', 'harga_jual'];
    let ok = true;
    requiredIds.forEach(id => {
      const el = document.getElementById(id);
      if (!el || !String(el.value).trim()) {
        ok = false;
        el?.classList.add('border-red-300', 'shake');
        setTimeout(() => el?.classList.remove('shake'), 300);
      } else {
        el?.classList.remove('border-red-300');
      }
    });

    if (!ok) { showToast('Gagal', 'Lengkapi semua field yang wajib diisi.', 'error'); return; }

    showConfirmModal({
      title: 'Simpan perubahan?',
      message: 'Perubahan data barang akan disimpan ke sistem.',
      confirmText: 'Ya, Simpan',
      cancelText: 'Batal',
      note: 'Cek lagi Kode, Nama, Satuan, dan harga. Kalau sudah benar, lanjut simpan.',
      onConfirm: () => {
        form.dataset.confirmed = '1';
        allowUnload = true;
        form.submit();
      }
    });
  });
</script>

@endsection
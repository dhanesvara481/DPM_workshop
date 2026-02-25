@extends('admin.layout.app')

@section('title', 'DPM Workshop - Admin')

@section('content')

{{-- TOPBAR --}}
<header class="relative h-16 bg-white/75 backdrop-blur border-b border-slate-200 sticky top-0 z-20">
  <div class="h-full px-4 sm:px-6 flex items-center justify-between gap-3">

    <div class="flex items-center gap-3 min-w-0">
      {{-- hamburger (mobile) --}}
      <button id="btnSidebar"
              type="button"
              class="md:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
              aria-label="Buka menu">
        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      <div class="min-w-0">
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Barang Masuk</h1>
        <p class="text-xs text-slate-500">Catat stok masuk untuk menambah stok barang.</p>
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
    </div>

  </div>
</header>

{{-- CONTENT --}}
<section class="relative p-4 sm:p-6">
  <div class="max-w-[1120px] mx-auto w-full space-y-5">

    {{-- ALERTS --}}
    @if(session('success'))
      <div class="rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-5 py-4">
        <p class="text-sm font-semibold">Berhasil</p>
        <p class="text-sm">{{ session('success') }}</p>
      </div>
    @endif

    @if(session('error'))
      <div class="rounded-2xl border border-red-200 bg-red-50 text-red-800 px-5 py-4">
        <p class="text-sm font-semibold">Gagal</p>
        <p class="text-sm">{{ session('error') }}</p>
      </div>
    @endif

    @if($errors->any())
      <div class="rounded-2xl border border-red-200 bg-red-50 text-red-800 px-5 py-4">
        <p class="text-sm font-semibold">Gagal</p>
        <ul class="mt-2 list-disc pl-5 text-sm space-y-1">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- FORM CARD --}}
    <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

      <div class="px-6 py-5 border-b border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
          <div>
            <h2 class="text-base font-semibold text-slate-900">Input Stok Masuk</h2>
            <p class="mt-1 text-sm text-slate-500">
              Pilih kode barang, lalu masukkan jumlah stok masuk untuk menambah stok.
            </p>
          </div>

          <span class="inline-flex self-start items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs text-slate-600">
            Form Barang Masuk
          </span>
        </div>
      </div>

      <form id="formMasuk" action="{{ route('simpan_barang_masuk') }}" method="POST" class="px-6 py-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

          {{-- Kode Barang --}}
          <div class="md:col-span-5">
            <label class="block text-sm font-semibold text-slate-800 mb-2">Kode Barang</label>
            {{-- FIX: gunakan $b->barang_id bukan $b->id --}}
            <select name="barang_id" id="kodeBarangSelect"
                    class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-white/90 text-sm
                           focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
              <option value="">-- Pilih Kode Barang --</option>
              @foreach(($barangs ?? []) as $b)
                <option value="{{ $b->barang_id }}"
                        data-kode="{{ $b->kode_barang ?? '' }}"
                        data-nama="{{ $b->nama_barang ?? '' }}"
                        data-satuan="{{ $b->satuan ?? '' }}"
                        data-stok="{{ $b->stok ?? 0 }}"
                        {{ old('barang_id') == $b->barang_id ? 'selected' : '' }}>
                  {{ $b->kode_barang ?? '-' }}
                </option>
              @endforeach
            </select>
            <p class="mt-2 text-xs text-slate-500">Pilih kode barang yang sudah terdaftar</p>
          </div>

          {{-- Stok Saat Ini --}}
          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-800 mb-2">Stok Saat Ini</label>
            <input type="text" id="stokSaatIni" readonly placeholder="-"
                   class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-700
                          focus:outline-none font-semibold tracking-tight text-center">
            <p class="mt-2 text-xs text-slate-500">Otomatis</p>
          </div>

          {{-- Tanggal --}}
          <div class="md:col-span-5">
            <label class="block text-sm font-semibold text-slate-800 mb-2">Tanggal</label>
            <input type="date" name="tanggal"
                   value="{{ old('tanggal', date('Y-m-d')) }}"
                   class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-white/90 text-sm
                          focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
          </div>

          {{-- Nama Barang --}}
          <div class="md:col-span-7">
            <label class="block text-sm font-semibold text-slate-800 mb-2">Nama Barang</label>
            <input type="text" id="namaBarang" readonly placeholder="Akan terisi otomatis"
                   class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-700
                          focus:outline-none">
          </div>

          {{-- Qty Masuk --}}
          <div class="md:col-span-5">
            <label class="block text-sm font-semibold text-slate-800 mb-2">Jumlah Stok Masuk</label>
            <input type="number" min="1" name="qty_masuk" id="qtyMasuk"
                   value="{{ old('qty_masuk') }}"
                   placeholder="Masukkan jumlah masuk"
                   class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-white/90 text-sm
                          focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
            <p class="mt-2 text-xs text-slate-500">Stok akan bertambah sesuai jumlah masuk.</p>
          </div>

          {{-- Satuan --}}
          <div class="md:col-span-7">
            <label class="block text-sm font-semibold text-slate-800 mb-2">Satuan</label>
            <input type="text" id="satuanBarang" readonly placeholder="Akan terisi otomatis"
                   class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-700
                          focus:outline-none">
          </div>

        </div>

        <div class="mt-5 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
          <div class="text-xs text-slate-500">
            Pastikan barang sudah ada di menu <span class="font-semibold text-slate-700">Kelola Barang</span>.
          </div>

          <div class="flex gap-2">
            <a id="btnKembaliMasuk" href="/tampilan_barang"
               class="tip inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold
                      border border-slate-200 bg-white hover:bg-slate-50 transition">
              Kembali
            </a>

            <button type="button" id="btnResetMasuk"
                    class="tip inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold
                           border border-slate-200 bg-white hover:bg-slate-50 transition">
              Reset
            </button>

            <button type="submit" id="btnSimpanMasuk"
                    class="tip btn-shine inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold
                           bg-blue-950 text-white hover:bg-blue-900 transition
                           shadow-[0_12px_24px_rgba(2,6,23,0.16)]">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5l7 7-7 7"/>
              </svg>
              Simpan
            </button>
          </div>
        </div>
      </form>
    </div>

    {{-- TABLE RIWAYAT --}}
    <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

      <div class="px-6 py-5 border-b border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
          <div>
            <h2 class="text-base font-semibold text-slate-900">Riwayat Barang Masuk</h2>
            <p class="text-sm text-slate-500">Daftar transaksi stok masuk terbaru.</p>
          </div>

          <div class="w-full sm:w-[380px]">
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3"/>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                </svg>
              </span>

              <input id="searchMasuk" type="text"
                     placeholder="Cari kode / nama barang..."
                     class="w-full pl-9 pr-10 py-2.5 rounded-lg border border-slate-200 bg-white/90
                            text-sm placeholder:text-slate-400
                            focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">

              <button id="btnClearSearchMasuk" type="button"
                      class="clear-btn absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-700"
                      aria-label="Clear">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full text-sm" id="tableMasuk">
          <thead class="bg-slate-50/90 sticky top-0 z-10 backdrop-blur">
          <tr class="text-left text-slate-600">
            <th class="px-5 py-4 font-semibold w-[70px]">No</th>
            <th class="px-5 py-4 font-semibold">Tanggal</th>
            <th class="px-5 py-4 font-semibold">Kode</th>
            <th class="px-5 py-4 font-semibold">Nama</th>
            <th class="px-5 py-4 font-semibold text-right">Qty</th>
          </tr>
          </thead>

          <tbody class="divide-y divide-slate-200">
          @forelse(($barangMasuk ?? []) as $i => $m)
            <tr class="row-lift hover:bg-slate-50/70 transition"
                data-row-text="{{ strtolower(($m->kode_barang ?? '').' '.($m->nama_barang ?? '')) }}">
              <td class="px-5 py-4 text-slate-600">{{ $i + 1 }}</td>
              {{-- FIX: kolom asli di DB adalah tanggal_masuk, bukan tanggal --}}
              <td class="px-5 py-4 text-slate-700">{{ $m->tanggal_masuk ?? '-' }}</td>
              <td class="px-5 py-4 font-semibold text-slate-900">{{ $m->kode_barang ?? '-' }}</td>
              <td class="px-5 py-4 text-slate-700">{{ $m->nama_barang ?? '-' }}</td>
              {{-- FIX: kolom asli di DB adalah jumlah_masuk, bukan qty_masuk --}}
              <td class="px-5 py-4 text-right font-semibold text-slate-900">{{ $m->jumlah_masuk ?? 0 }}</td>
            </tr>
          @empty
            @for($r=1;$r<=3;$r++)
              <tr class="row-lift hover:bg-slate-50/70 transition">
                <td class="px-5 py-5 text-slate-400">{{ $r }}</td>
                <td class="px-5 py-5"><div class="h-4 w-28 rounded bg-slate-100"></div></td>
                <td class="px-5 py-5"><div class="h-4 w-20 rounded bg-slate-100"></div></td>
                <td class="px-5 py-5"><div class="h-4 w-52 rounded bg-slate-100"></div></td>
                <td class="px-5 py-5 text-right"><div class="h-4 w-16 ml-auto rounded bg-slate-100"></div></td>
              </tr>
            @endfor
          @endforelse
          </tbody>
        </table>
      </div>

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
      <p id="toastMsg" class="text-xs text-slate-600 mt-0.5">Aksi berhasil.</p>
    </div>
    <button id="toastClose" class="ml-auto text-slate-500 hover:text-slate-800 transition" type="button" aria-label="Close">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>
</div>

@push('head')
<style>
  @media (prefers-reduced-motion: reduce) {
    .row-lift, .btn-shine { animation: none !important; transition: none !important; }
  }

  .row-lift{ transform: translateY(0); transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease; }
  .row-lift:hover{ transform: translateY(-1px); box-shadow: 0 10px 26px rgba(2,6,23,0.06); }

  .btn-shine{ position: relative; overflow: hidden; }
  .btn-shine::after{
    content:"";
    position:absolute;
    inset:0;
    transform: translateX(-120%);
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.28), transparent);
    transition: transform .65s ease;
  }
  .btn-shine:hover::after{ transform: translateX(120%); }

  /* tooltip */
  .tip{ position: relative; }
  .tip[data-tip]::after{
    content: attr(data-tip);
    position:absolute;
    right: 50%;
    top: calc(100% + 10px);
    background: rgba(15,23,42,.92);
    color: rgba(255,255,255,.92);
    font-size: 11px;
    padding: 6px 10px;
    border-radius: 10px;
    white-space: nowrap;
    opacity:0;
    transform: translate(50%, -4px);
    pointer-events:none;
    transition: .15s ease;
  }
  .tip:hover::after{ opacity:1; transform: translate(50%, 0); }

  /* invalid shake */
  @keyframes shake {
    0% { transform: translateX(0) }
    25% { transform: translateX(-6px) }
    50% { transform: translateX(6px) }
    75% { transform: translateX(-4px) }
    100% { transform: translateX(0) }
  }
  .shake { animation: shake .28s ease; }

  .clear-btn{ opacity:0; pointer-events:none; transform: scale(.9); transition: .15s ease; }
  .clear-btn.show{ opacity:1; pointer-events:auto; transform: scale(1); }
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

  // ===== Sync barang fields =====
  const kodeSelect = document.getElementById('kodeBarangSelect');
  const namaBarang = document.getElementById('namaBarang');
  const satuanBarang = document.getElementById('satuanBarang');
  const stokSaatIni = document.getElementById('stokSaatIni');

  const syncBarangFields = () => {
    if (!kodeSelect) return;
    const opt = kodeSelect.options[kodeSelect.selectedIndex];
    const nama = opt?.dataset?.nama || '';
    const satuan = opt?.dataset?.satuan || '';
    const stokNum = parseInt(opt?.dataset?.stok || '0', 10) || 0;

    if (namaBarang) namaBarang.value = nama;
    if (satuanBarang) satuanBarang.value = satuan;
    // FIX: hanya tampilkan stok jika ada barang yang dipilih
    if (stokSaatIni) stokSaatIni.value = opt?.value ? String(stokNum) : '';
  };

  kodeSelect?.addEventListener('change', syncBarangFields);

  // ===== Search table =====
  const inputMasuk = document.getElementById('searchMasuk');
  const btnClear = document.getElementById('btnClearSearchMasuk');

  const applySearch = () => {
    const q = (inputMasuk?.value || '').trim().toLowerCase();
    document.querySelectorAll('#tableMasuk tbody tr[data-row-text]').forEach(tr => {
      tr.classList.toggle('hidden', q && !tr.dataset.rowText.includes(q));
    });
    btnClear?.classList.toggle('show', !!q);
  };

  inputMasuk?.addEventListener('input', applySearch);
  btnClear?.addEventListener('click', () => {
    inputMasuk.value = '';
    inputMasuk.focus();
    applySearch();
  });
  applySearch();

  // ===== Guard + Reset + Confirm Submit =====
  const form = document.getElementById('formMasuk');
  const btnReset = document.getElementById('btnResetMasuk');
  const btnKembali = document.getElementById('btnKembaliMasuk');
  const qtyInput = document.getElementById('qtyMasuk');

  let isDirty = false;
  const markDirty = () => { isDirty = true; };

  form?.querySelectorAll('input, select, textarea').forEach(el => {
    el.addEventListener('input', markDirty);
    el.addEventListener('change', markDirty);
  });

  // RESET (modal)
  btnReset?.addEventListener('click', () => {
    if (!form) return;

    if (!isDirty) {
      form.reset();
      const dateInput = form.querySelector('input[name="tanggal"]');
      if (dateInput) dateInput.value = new Date().toISOString().slice(0, 10);
      syncBarangFields();
      showToast('Reset', 'Form dikosongkan.', 'success');
      return;
    }

    showConfirmModal({
      title: "Reset form?",
      message: "Semua input yang sudah diisi akan dikosongkan.",
      confirmText: "Ya, Reset",
      cancelText: "Batal",
      note: "Kalau kamu yakin mau mulai ulang, klik \u201cYa, Reset\u201d.",
      tone: "danger",
      onConfirm: () => {
        form.reset();
        const dateInput = form.querySelector('input[name="tanggal"]');
        if (dateInput) dateInput.value = new Date().toISOString().slice(0, 10);
        syncBarangFields();
        isDirty = false;
        showToast('Reset', 'Form dikosongkan.', 'success');
      }
    });
  });

  // KEMBALI (modal)
  btnKembali?.addEventListener('click', (e) => {
    if (!isDirty) return;

    e.preventDefault();
    const go = e.currentTarget.getAttribute('href');

    showConfirmModal({
      title: "Keluar dari halaman?",
      message: "Perubahan belum disimpan. Kalau keluar sekarang, data yang sudah diisi akan hilang.",
      confirmText: "Ya, Keluar",
      cancelText: "Tetap di sini",
      note: "Klik \u201cTetap di sini\u201d kalau masih mau lanjut isi form.",
      onConfirm: () => window.location.href = go
    });
  });

  // SUBMIT (modal + validasi)
  form?.addEventListener('submit', (e) => {
    if (form.dataset.confirmed === "1") return;
    e.preventDefault();

    const barangId = kodeSelect?.value || '';
    const qty = parseInt(qtyInput?.value || '0', 10) || 0;

    if (!barangId) {
      kodeSelect?.classList.add('border-red-300', 'shake');
      setTimeout(() => kodeSelect?.classList.remove('shake'), 300);
      showToast('Gagal', 'Pilih kode barang terlebih dahulu.', 'error');
      return;
    } else {
      kodeSelect?.classList.remove('border-red-300');
    }

    if (qty <= 0) {
      qtyInput?.classList.add('border-red-300', 'shake');
      setTimeout(() => qtyInput?.classList.remove('shake'), 300);
      showToast('Gagal', 'Jumlah masuk minimal 1.', 'error');
      return;
    } else {
      qtyInput?.classList.remove('border-red-300');
    }

    showConfirmModal({
      title: "Simpan transaksi?",
      message: "Stok barang akan bertambah sesuai jumlah masuk.",
      confirmText: "Ya, Simpan",
      cancelText: "Batal",
      note: "Cek lagi kode barang, tanggal, dan jumlah masuk sebelum menyimpan.",
      onConfirm: () => {
        form.dataset.confirmed = "1";
        isDirty = false;
        form.submit();
      }
    });
  });

  // FIX: jalankan sync setelah DOM siap untuk handle old() value saat redirect
  document.addEventListener('DOMContentLoaded', () => {
    syncBarangFields();
  });
</script>
@endpush

@endsection
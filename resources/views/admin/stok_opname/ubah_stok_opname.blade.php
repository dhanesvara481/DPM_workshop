@extends('admin.layout.app')

@section('title', 'DPM Workshop - Admin')

@section('content')

{{-- TOPBAR --}}
<header class="sticky top-0 z-20 border-b border-slate-200 bg-white/80 backdrop-blur">
  <div class="h-16 px-4 sm:px-6 flex items-center justify-between gap-3">
    <div class="flex items-center gap-3 min-w-0">
      <button id="btnSidebar" type="button"
              class="md:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center shrink-0"
              aria-label="Buka menu">
        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <div class="min-w-0">
        <h1 class="text-sm font-semibold tracking-tight text-slate-900 leading-tight">Isi Stok Fisik</h1>
        <p class="text-xs text-slate-500 leading-tight">{{ $opname->tanggal_opname->format('d M Y') }}</p>
      </div>
    </div>
    <div class="shrink-0">
      <a href="{{ route('stok_opname.index') }}"
         class="h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold inline-flex items-center gap-1.5">
        <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
      </a>
    </div>
  </div>
</header>

<section class="relative p-4 sm:p-6">
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
    <div class="absolute inset-0 opacity-[0.10]"
         style="background-image:
            linear-gradient(to right, rgba(2,6,23,0.05) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(2,6,23,0.05) 1px, transparent 1px);
            background-size: 56px 56px;">
    </div>
  </div>

  <div class="max-w-6xl mx-auto w-full space-y-5">

    {{-- Alert --}}
    @if(session('success'))
      <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        <svg class="h-5 w-5 shrink-0 text-emerald-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
        {{ session('error') }}
      </div>
    @endif

    {{-- Info sesi --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_4px_20px_rgba(2,6,23,0.06)] p-4 sm:p-5">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <p class="text-sm text-slate-500">
            Tanggal: <strong class="text-slate-800">{{ $opname->tanggal_opname->format('d M Y') }}</strong>
            @if($opname->keterangan)
              &nbsp;·&nbsp; {{ $opname->keterangan }}
            @endif
          </p>
        </div>
        <span class="inline-flex items-center self-start sm:self-auto px-3 py-1 rounded-full text-xs font-medium border {{ $opname->status_badge_class }}">
          {{ $opname->status_label }}
        </span>
      </div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('stok_opname.update', $opname->opname_id) }}" id="formStokFisik">
      @csrf

      {{-- Toolbar --}}
      <div class="rounded-t-2xl border border-slate-200 bg-white/85 backdrop-blur px-4 py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <p class="text-sm text-slate-600">
          <strong>{{ $opname->details->count() }}</strong> barang —
          <span id="sudahDiisi" class="font-medium text-emerald-600">0</span> diisi,
          <span id="belumDiisi" class="font-medium text-rose-600">{{ $opname->details->count() }}</span> belum
        </p>
        <input type="text" id="searchBarang" placeholder="Cari nama / kode…"
               class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 w-full sm:w-56">
      </div>

      {{-- Desktop: tabel --}}
      <div class="hidden sm:block border-x border-b border-slate-200 rounded-b-2xl overflow-x-auto bg-white/85 backdrop-blur shadow-[0_4px_20px_rgba(2,6,23,0.06)]">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50">
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Barang</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Sat.</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok Sistem</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-36">Stok Fisik</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Selisih</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-44">Keterangan</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100" id="tabelBody">
            @foreach($opname->details as $i => $detail)
            <tr class="hover:bg-slate-50 transition barang-row"
                data-nama="{{ strtolower($detail->nama_barang_snapshot) }}"
                data-kode="{{ strtolower($detail->kode_barang_snapshot) }}">
              <input type="hidden" name="items[{{ $i }}][detail_id]" value="{{ $detail->detail_opname_id }}">
              <td class="px-5 py-3 text-slate-500 font-mono text-xs whitespace-nowrap">{{ $detail->kode_barang_snapshot }}</td>
              <td class="px-5 py-3 font-medium text-slate-800 whitespace-nowrap">{{ $detail->nama_barang_snapshot }}</td>
              <td class="px-5 py-3 text-center text-slate-500 text-xs">{{ $detail->satuan_snapshot }}</td>
              <td class="px-5 py-3 text-center font-semibold text-slate-700">{{ $detail->stok_sistem }}</td>
              <td class="px-5 py-3 text-center">
                <input type="number" name="items[{{ $i }}][stok_fisik]"
                       value="{{ old("items.{$i}.stok_fisik", $detail->stok_fisik) }}"
                       min="0" placeholder="—"
                       class="stok-fisik-input w-24 text-center rounded-lg border border-slate-200 px-2 py-1.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent"
                       data-sistem="{{ $detail->stok_sistem }}" data-row="{{ $i }}">
              </td>
              <td class="px-5 py-3 text-center">
                <span id="selisih-{{ $i }}" class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                  {{ !is_null($detail->stok_fisik)
                    ? ($detail->selisih > 0 ? 'bg-blue-100 text-blue-700' : ($detail->selisih < 0 ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700'))
                    : 'bg-slate-100 text-slate-400' }}">
                  {{ !is_null($detail->stok_fisik) ? $detail->selisih_label : '—' }}
                </span>
              </td>
              <td class="px-5 py-3">
                <input type="text" name="items[{{ $i }}][keterangan]"
                       value="{{ old("items.{$i}.keterangan", $detail->keterangan) }}"
                       placeholder="Opsional" maxlength="255"
                       class="w-full rounded-lg border border-slate-200 px-2 py-1.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-slate-900">
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Mobile: card input --}}
      <div class="sm:hidden border-x border-b border-slate-200 rounded-b-2xl bg-white/85 backdrop-blur shadow-[0_4px_20px_rgba(2,6,23,0.06)] divide-y divide-slate-100" id="mobileCards">
        @foreach($opname->details as $i => $detail)
        <div class="p-4 barang-row"
             data-nama="{{ strtolower($detail->nama_barang_snapshot) }}"
             data-kode="{{ strtolower($detail->kode_barang_snapshot) }}">
          <input type="hidden" name="items[{{ $i }}][detail_id]" value="{{ $detail->detail_opname_id }}">

          <div class="flex items-start justify-between gap-2 mb-3">
            <div>
              <p class="text-sm font-semibold text-slate-800">{{ $detail->nama_barang_snapshot }}</p>
              <p class="text-xs text-slate-400 font-mono">{{ $detail->kode_barang_snapshot }} · {{ $detail->satuan_snapshot }}</p>
            </div>
            <span id="selisih-mob-{{ $i }}" class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold shrink-0
              {{ !is_null($detail->stok_fisik)
                ? ($detail->selisih > 0 ? 'bg-blue-100 text-blue-700' : ($detail->selisih < 0 ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700'))
                : 'bg-slate-100 text-slate-400' }}">
              {{ !is_null($detail->stok_fisik) ? $detail->selisih_label : '—' }}
            </span>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-medium text-slate-500 mb-1">Stok Sistem</label>
              <div class="rounded-lg bg-slate-50 border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 text-center">
                {{ $detail->stok_sistem }}
              </div>
            </div>
            <div>
              <label class="block text-xs font-medium text-slate-500 mb-1">Stok Fisik <span class="text-rose-400">*</span></label>
              <input type="number" name="items[{{ $i }}][stok_fisik]"
                     value="{{ old("items.{$i}.stok_fisik", $detail->stok_fisik) }}"
                     min="0" placeholder="Isi jumlah"
                     class="stok-fisik-input w-full text-center rounded-lg border border-slate-200 px-3 py-2 text-sm
                            focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent"
                     data-sistem="{{ $detail->stok_sistem }}" data-row="{{ $i }}" data-mobile="1">
            </div>
          </div>
          <div class="mt-3">
            <label class="block text-xs font-medium text-slate-500 mb-1">Keterangan</label>
            <input type="text" name="items[{{ $i }}][keterangan]"
                   value="{{ old("items.{$i}.keterangan", $detail->keterangan) }}"
                   placeholder="Opsional" maxlength="255"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
          </div>
        </div>
        @endforeach
      </div>

      {{-- Tombol aksi --}}
      <div class="mt-5 flex flex-wrap items-center gap-3">
        <button type="submit"
                class="h-10 px-5 rounded-xl bg-slate-900 text-white text-sm font-medium hover:bg-slate-700 transition shadow-sm">
          Simpan Draft
        </button>
        <button type="button" onclick="submitOpname()"
                class="h-10 px-5 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition shadow-sm">
          Submit untuk Approval
        </button>
        <a href="{{ route('stok_opname.show', $opname->opname_id) }}"
           class="h-10 px-5 rounded-xl border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition inline-flex items-center">
          Lihat Detail
        </a>
      </div>
    </form>

    {{-- Form submit terpisah --}}
    <form id="formSubmit" method="POST" action="{{ route('stok_opname.submit', $opname->opname_id) }}" class="hidden">
      @csrf
    </form>

  </div>
</section>

{{-- Modal Konfirmasi Submit --}}
<div id="modalSubmitConfirm" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
    <div class="flex items-start gap-4 mb-4">
      <div class="h-10 w-10 rounded-xl bg-amber-100 text-amber-600 grid place-items-center shrink-0 border border-amber-200">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
      </div>
      <div>
        <h3 class="text-sm font-bold text-slate-800">Stok Fisik Belum Lengkap</h3>
        <p id="modalSubmitMsg" class="text-sm text-slate-500 mt-1"></p>
      </div>
    </div>
    <p class="text-xs text-slate-400 mb-5">Barang yang belum diisi akan dianggap belum diperiksa. Tetap lanjutkan submit?</p>
    <div class="flex gap-3">
      <button type="button" id="modalSubmitOk"
              class="flex-1 h-10 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
        Ya, Submit
      </button>
      <button type="button" id="modalSubmitCancel"
              class="flex-1 h-10 rounded-xl border border-slate-200 text-sm text-slate-600 font-semibold hover:bg-slate-50 transition">
        Batal
      </button>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function () {
  const inputs  = document.querySelectorAll('.stok-fisik-input');
  const sudahEl = document.getElementById('sudahDiisi');
  const belumEl = document.getElementById('belumDiisi');

  // ── Sanitize: hanya digit, hapus leading zero ─────────────────────────────
  function sanitizeInput(inp) {
    const pos    = inp.selectionStart;
    const before = inp.value;
    let raw = before.replace(/\D/g, '');                     // buang semua non-digit
    if (raw.length > 1) raw = raw.replace(/^0+/, '') || '0'; // hapus leading zero
    if (before !== raw) {
      inp.value = raw;
      try { inp.setSelectionRange(Math.min(pos, raw.length), Math.min(pos, raw.length)); } catch (_) {}
    }
  }

  // ── Hitung selisih realtime ───────────────────────────────────────────────
  function hitungSelisih(input) {
    const row      = input.dataset.row;
    const sistem   = parseInt(input.dataset.sistem);
    const fisik    = input.value !== '' ? parseInt(input.value) : null;
    const selEl    = document.getElementById('selisih-' + row);
    const selMobEl = document.getElementById('selisih-mob-' + row);

    const update = (el) => {
      if (!el) return;
      if (fisik === null || isNaN(fisik)) {
        el.textContent = '—';
        el.className = 'inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-400'
                     + (el.id.includes('mob') ? ' shrink-0' : '');
        return;
      }
      const s = fisik - sistem;
      el.textContent = s > 0 ? '+' + s : String(s);
      const base = 'inline-block px-2 py-0.5 rounded-full text-xs font-semibold'
                 + (el.id.includes('mob') ? ' shrink-0' : '');
      el.className = base + ' ' + (s > 0 ? 'bg-blue-100 text-blue-700' : s < 0 ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700');
    };
    update(selEl);
    update(selMobEl);

    // Sync desktop ↔ mobile
    document.querySelectorAll('.stok-fisik-input[data-row="' + row + '"]').forEach(el => {
      if (el !== input) el.value = input.value;
    });
  }

  // ── Counter ───────────────────────────────────────────────────────────────
  function updateCounter() {
    const seen = new Set();
    let sudah = 0;
    inputs.forEach(inp => {
      if (!seen.has(inp.dataset.row)) {
        seen.add(inp.dataset.row);
        if (inp.value !== '') sudah++;
      }
    });
    sudahEl.textContent = sudah;
    belumEl.textContent = seen.size - sudah;
  }

  // ── Bind events ───────────────────────────────────────────────────────────
  inputs.forEach(inp => {
    // Blokir karakter non-angka sebelum masuk (-, +, e, ., koma, spasi)
    inp.addEventListener('keydown', e => {
      const allowed = ['Backspace','Delete','Tab','ArrowLeft','ArrowRight','Home','End'];
      if (allowed.includes(e.key) || (e.key >= '0' && e.key <= '9')) return;
      e.preventDefault();
    });
    inp.addEventListener('input', () => { sanitizeInput(inp); hitungSelisih(inp); updateCounter(); });
    inp.addEventListener('blur',  () => { sanitizeInput(inp); hitungSelisih(inp); updateCounter(); });
    hitungSelisih(inp);
  });
  updateCounter();

  // ── Modal konfirmasi submit ───────────────────────────────────────────────
  const modalConfirm = document.getElementById('modalSubmitConfirm');
  const modalMsg     = document.getElementById('modalSubmitMsg');
  const modalOk      = document.getElementById('modalSubmitOk');
  const modalCancel  = document.getElementById('modalSubmitCancel');

  function closeModal() {
    modalConfirm.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  }
  modalCancel.addEventListener('click', closeModal);
  modalConfirm.addEventListener('click', e => { if (e.target === modalConfirm) closeModal(); });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && !modalConfirm.classList.contains('hidden')) closeModal();
  });

  function doSubmit() {
    document.getElementById('formStokFisik').addEventListener('submit', function handler(e) {
      e.preventDefault();
      this.removeEventListener('submit', handler);
      // Disable input mobile agar tidak duplikat di POST
      document.querySelectorAll('[data-mobile="1"]').forEach(el => el.disabled = true);
      fetch(this.action, { method: 'POST', body: new FormData(this) })
        .then(() => document.getElementById('formSubmit').submit());
    });
    document.getElementById('formStokFisik').dispatchEvent(new Event('submit'));
  }

  window.submitOpname = function () {
    const belum = parseInt(belumEl.textContent);
    if (belum > 0) {
      modalMsg.textContent = belum + ' barang belum diisi stok fisiknya.';
      modalConfirm.classList.remove('hidden');
      document.body.classList.add('overflow-hidden');
      modalOk.onclick = () => { closeModal(); doSubmit(); };
    } else {
      doSubmit();
    }
  };

  // ── Search ────────────────────────────────────────────────────────────────
  document.getElementById('searchBarang').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.barang-row').forEach(row => {
      row.style.display = (row.dataset.nama.includes(q) || row.dataset.kode.includes(q)) ? '' : 'none';
    });
  });
})();
</script>
@endpush

@endsection
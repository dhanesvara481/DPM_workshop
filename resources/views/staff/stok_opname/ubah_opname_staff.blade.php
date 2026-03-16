@extends('staff.layout.app')

@section('title', 'DPM Workshop - Staff')
@section('page_title', 'Isi Stok Fisik')
@section('page_subtitle', $opname->tanggal_opname->format('d M Y'))

@section('content')

<div class="max-w-6xl mx-auto w-full space-y-5">

  {{-- Alert --}}
  @foreach(['success','error','info'] as $type)
    @if(session($type))
      @php $c = ['success'=>'emerald','error'=>'rose','info'=>'blue'][$type]; @endphp
      <div class="flex items-start gap-3 rounded-xl border border-{{ $c }}-200 bg-{{ $c }}-50 px-4 py-3 text-sm text-{{ $c }}-800">
        {{ session($type) }}
      </div>
    @endif
  @endforeach

  {{-- Info sesi --}}
  <div class="rounded-2xl border border-slate-700 bg-slate-900 shadow-[0_4px_20px_rgba(2,6,23,0.15)] p-4 sm:p-5">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-white/10 border border-white/15 grid place-items-center shrink-0">
          <svg class="h-5 w-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
          </svg>
        </div>
        <div>
          <p class="text-sm font-semibold text-white">
            {{ $opname->tanggal_opname->format('d M Y') }}
            @if($opname->keterangan)
              <span class="text-white/50 font-normal ml-1">· {{ $opname->keterangan }}</span>
            @endif
          </p>
          <p class="text-xs text-white/50 mt-0.5">Dibuat oleh: {{ $opname->nama_pembuat }}</p>
        </div>
      </div>
      <div class="flex items-center gap-2 self-start sm:self-auto">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/10 border border-white/20 text-white/80">
          {{ $opname->status_label }}
        </span>
        <a href="{{ route('stok_opname.daftarOpnameStaff') }}"
           class="h-9 px-4 rounded-xl border border-white/20 bg-white/10 hover:bg-white/20 transition text-sm font-semibold inline-flex items-center gap-1.5 text-white/80 hover:text-white">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
          </svg>
          Kembali
        </a>
      </div>
    </div>
  </div>

  {{-- Panduan --}}
  <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800">
    <strong>Petunjuk:</strong> Hitung stok barang secara fisik di gudang, lalu isi kolom "Stok Fisik".
    Klik <em>Simpan Draft</em> untuk menyimpan sementara, atau <em>Submit</em> jika semua sudah diisi dan siap dikirim ke admin untuk disetujui.
  </div>

  {{-- Form --}}
  <form method="POST" action="{{ route('stok_opname.updateOpnameStaff', $opname->opname_id) }}" id="formStokFisik">
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
    <div class="hidden sm:block border-x border-slate-200 overflow-x-auto bg-white/85 backdrop-blur">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-100 bg-slate-50">
            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-[60px]">No</th>
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
              data-kode="{{ strtolower($detail->kode_barang_snapshot) }}"
              data-index="{{ $i }}">
            <input type="hidden" name="items[{{ $i }}][detail_id]" value="{{ $detail->detail_opname_id }}">
            <td class="px-5 py-3 text-slate-400 text-xs row-no">{{ $i + 1 }}</td>
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
              <span id="selisih-{{ $i }}" class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold {{ !is_null($detail->stok_fisik)
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
    <div class="sm:hidden border-x border-slate-200 bg-white/85 backdrop-blur divide-y divide-slate-100" id="mobileCards">
      @foreach($opname->details as $i => $detail)
      <div class="p-4 barang-row"
           data-nama="{{ strtolower($detail->nama_barang_snapshot) }}"
           data-kode="{{ strtolower($detail->kode_barang_snapshot) }}"
           data-index="{{ $i }}">
        <div class="flex items-start justify-between gap-2 mb-3">
          <div>
            <p class="text-xs text-slate-400 mb-0.5">No. {{ $i + 1 }}</p>
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

    {{-- Pagination bar --}}
    <div class="border-x border-b border-slate-200 rounded-b-2xl bg-white/85 backdrop-blur shadow-[0_4px_20px_rgba(2,6,23,0.06)]">
      <div id="paginationWrap" class="px-6 py-4 flex items-center justify-between gap-3 flex-wrap">
        <p id="paginationInfo" class="text-xs text-slate-500"></p>
        <div id="paginationBtns" class="flex items-center gap-1"></div>
      </div>
    </div>

    {{-- Tombol aksi --}}
    <div class="mt-5 flex flex-wrap items-center gap-3">
      <button type="button" onclick="saveDraft()"
              class="h-10 px-5 rounded-xl bg-slate-900 text-white text-sm font-medium hover:bg-slate-700 transition shadow-sm">
        Simpan Draft
      </button>
      <button type="button" onclick="submitOpname()"
              class="h-10 px-5 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition shadow-sm">
        Submit untuk Approval
      </button>
      <a href="{{ route('stok_opname.detailOpnameStaff', $opname->opname_id) }}"
         class="h-10 px-5 rounded-xl border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition inline-flex items-center">
        Lihat Detail
      </a>
    </div>
  </form>

  {{-- Form submit approval terpisah --}}
  <form id="formSubmit" method="POST" action="{{ route('stok_opname.submitOpnameStaff', $opname->opname_id) }}" class="hidden">
    @csrf
  </form>

</div>

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
  const PER_PAGE    = 10;
  let currentPage   = 1;

  // ── Semua input desktop (bukan mobile duplicate) ─────────────────────────
  const inputs      = document.querySelectorAll('.stok-fisik-input:not([data-mobile="1"])');
  const sudahEl     = document.getElementById('sudahDiisi');
  const belumEl     = document.getElementById('belumDiisi');
  const allRows     = Array.from(document.querySelectorAll('#tabelBody .barang-row'));
  const allMobCards = Array.from(document.querySelectorAll('#mobileCards .barang-row'));

  // ── Toast notifikasi inline (tidak refresh) ───────────────────────────────
  function showToast(msg, type = 'error') {
    const c = { error: 'rose', success: 'emerald', info: 'blue' }[type] ?? 'rose';
    let toast = document.getElementById('inlineToast');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'inlineToast';
      const container = document.querySelector('.max-w-6xl');
      container.insertBefore(toast, container.firstChild);
    }
    toast.className = `flex items-start gap-3 rounded-xl border border-${c}-200 bg-${c}-50 px-4 py-3 text-sm text-${c}-800 mb-2`;
    toast.innerHTML = msg;
    toast.scrollIntoView({ behavior: 'smooth', block: 'start' });
    // Auto hilang setelah 6 detik untuk success
    if (type === 'success') setTimeout(() => toast.remove(), 6000);
  }

  function removeToast() {
    document.getElementById('inlineToast')?.remove();
  }

  // ── Highlight baris yang belum diisi ─────────────────────────────────────
  function highlightKosong() {
    // Reset semua highlight dulu
    inputs.forEach(inp => {
      inp.classList.remove('border-rose-400', 'ring-2', 'ring-rose-300', 'bg-rose-50');
    });

    const kosongRows = [];
    inputs.forEach(inp => {
      if (inp.value === '') {
        inp.classList.add('border-rose-400', 'ring-2', 'ring-rose-300', 'bg-rose-50');
        const row = inp.closest('.barang-row');
        if (row) kosongRows.push(row);
      }
    });

    return kosongRows;
  }

  // ── Scroll ke baris kosong pertama (buka halaman pagination yang benar) ───
  function scrollKeKosongPertama(kosongRows) {
    if (!kosongRows.length) return;

    const firstRow     = kosongRows[0];
    const rowIndex     = allRows.indexOf(firstRow);
    const visibleRows  = allRows.filter(r => r.dataset.visible !== 'false');
    const posInVisible = visibleRows.indexOf(firstRow);

    if (posInVisible >= 0) {
      const targetPage = Math.floor(posInVisible / PER_PAGE) + 1;
      if (targetPage !== currentPage) renderPage(targetPage);
    }

    // Tunggu render selesai baru scroll
    requestAnimationFrame(() => {
      const inp = firstRow.querySelector('.stok-fisik-input');
      if (inp) {
        inp.scrollIntoView({ behavior: 'smooth', block: 'center' });
        inp.focus();
      }
    });
  }

  // ── Sanitize: hanya angka positif ────────────────────────────────────────
  function sanitizeInput(inp) {
    const pos    = inp.selectionStart;
    const before = inp.value;
    let raw = before.replace(/\D/g, '');
    if (raw.length > 1) raw = raw.replace(/^0+/, '') || '0';
    if (before !== raw) {
      inp.value = raw;
      try { inp.setSelectionRange(Math.min(pos, raw.length), Math.min(pos, raw.length)); } catch (_) {}
    }
    // Hapus highlight merah saat user mulai isi
    if (inp.value !== '') {
      inp.classList.remove('border-rose-400', 'ring-2', 'ring-rose-300', 'bg-rose-50');
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

  // ── Counter diisi / belum ─────────────────────────────────────────────────
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

  // ── Pagination ────────────────────────────────────────────────────────────
  function getVisibleRows() {
    return allRows.filter(r => r.dataset.visible !== 'false');
  }

  function renderPage(page) {
    currentPage = page;
    const visible  = getVisibleRows();
    const total    = visible.length;
    const lastPage = Math.max(1, Math.ceil(total / PER_PAGE));
    currentPage    = Math.min(currentPage, lastPage);
    const start    = (currentPage - 1) * PER_PAGE;
    const end      = start + PER_PAGE;

    allRows.forEach(r => {
      if (r.dataset.visible === 'false') {
        r.style.display = 'none';
        const mc = allMobCards.find(c => c.dataset.index === r.dataset.index);
        if (mc) mc.style.display = 'none';
        return;
      }
      const idx      = visible.indexOf(r);
      const show     = idx >= start && idx < end;
      r.style.display = show ? '' : 'none';
      const mc = allMobCards.find(c => c.dataset.index === r.dataset.index);
      if (mc) mc.style.display = show ? '' : 'none';
    });

    visible.forEach((r, idx) => {
      const noEl = r.querySelector('.row-no');
      if (noEl) noEl.textContent = idx + 1;
    });

    renderPaginationUI(currentPage, lastPage, total);
  }

  function renderPaginationUI(page, lastPage, total) {
    const infoEl = document.getElementById('paginationInfo');
    const btnsEl = document.getElementById('paginationBtns');
    const wrap   = document.getElementById('paginationWrap');

    if (lastPage <= 1) { wrap.classList.add('hidden'); return; }
    wrap.classList.remove('hidden');

    const firstItem = (page - 1) * PER_PAGE + 1;
    const lastItem  = Math.min(page * PER_PAGE, total);
    infoEl.textContent = `Menampilkan ${firstItem}–${lastItem} dari ${total} barang`;

    btnsEl.innerHTML = '';
    const btnClass   = 'h-9 w-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center text-slate-700 text-sm cursor-pointer select-none';
    const btnDisabled= 'h-9 w-9 rounded-xl border border-slate-200 bg-slate-50 grid place-items-center text-slate-300 text-sm cursor-not-allowed select-none';
    const btnActive  = 'h-9 w-9 rounded-xl bg-slate-900 text-white grid place-items-center text-sm font-semibold select-none';

    const prev = document.createElement('span');
    prev.innerHTML = '‹';
    prev.className = page <= 1 ? btnDisabled : btnClass;
    if (page > 1) prev.addEventListener('click', () => renderPage(page - 1));
    btnsEl.appendChild(prev);

    for (let p = 1; p <= lastPage; p++) {
      const btn = document.createElement('span');
      btn.textContent = p;
      btn.className   = p === page ? btnActive : btnClass;
      if (p !== page) { const _p = p; btn.addEventListener('click', () => renderPage(_p)); }
      btnsEl.appendChild(btn);
    }

    const next = document.createElement('span');
    next.innerHTML = '›';
    next.className = page >= lastPage ? btnDisabled : btnClass;
    if (page < lastPage) next.addEventListener('click', () => renderPage(page + 1));
    btnsEl.appendChild(next);
  }

  // ── Search ────────────────────────────────────────────────────────────────
  document.getElementById('searchBarang').addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    allRows.forEach(r => {
      const match = r.dataset.nama.includes(q) || r.dataset.kode.includes(q);
      r.dataset.visible = match ? 'true' : 'false';
      const mc = allMobCards.find(c => c.dataset.index === r.dataset.index);
      if (mc) mc.dataset.visible = r.dataset.visible;
    });
    renderPage(1);
  });

  // ── Bind input events ─────────────────────────────────────────────────────
  inputs.forEach(inp => {
    inp.addEventListener('keydown', e => {
      const allowed = ['Backspace','Delete','Tab','ArrowLeft','ArrowRight','Home','End'];
      if (allowed.includes(e.key) || (e.key >= '0' && e.key <= '9')) return;
      e.preventDefault();
    });
    inp.addEventListener('input', () => { sanitizeInput(inp); hitungSelisih(inp); updateCounter(); });
    inp.addEventListener('blur',  () => { sanitizeInput(inp); hitungSelisih(inp); updateCounter(); });
    hitungSelisih(inp);
  });

  allRows.forEach(r => r.dataset.visible = 'true');
  updateCounter();
  renderPage(1);

  // ── Kumpulkan data form sebagai FormData (disable mobile dulu) ────────────
  function collectFormData() {
    document.querySelectorAll('[data-mobile="1"]').forEach(el => el.disabled = true);
    allRows.forEach(r => r.style.display = ''); // tampilkan semua agar hidden input ikut
    return new FormData(document.getElementById('formStokFisik'));
  }

  function restoreRows() {
    // Re-enable mobile inputs
    document.querySelectorAll('[data-mobile="1"]').forEach(el => el.disabled = false);
    // Kembalikan pagination normal
    renderPage(currentPage);
  }

  // ── Simpan Draft (AJAX, tidak refresh) ───────────────────────────────────
  window.saveDraft = async function () {
    removeToast();
    const btnSave = document.querySelector('[onclick="saveDraft()"]');
    if (btnSave) { btnSave.disabled = true; btnSave.textContent = 'Menyimpan...'; }

    try {
      const fd = collectFormData();
      const res = await fetch(document.getElementById('formStokFisik').action, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: fd,
      });

      restoreRows();

      if (res.ok || res.status === 302) {
        showToast('✓ Draft berhasil disimpan.', 'success');
      } else {
        showToast('Gagal menyimpan draft. Coba lagi.', 'error');
      }
    } catch (err) {
      restoreRows();
      showToast('Terjadi kesalahan koneksi. Coba lagi.', 'error');
    } finally {
      if (btnSave) { btnSave.disabled = false; btnSave.textContent = 'Simpan Draft'; }
    }
  };

  // ── Submit untuk Approval ────────────────────────────────────────────────
  // Alur: 1) highlight kosong → 2) kalau ada kosong tanya konfirmasi
  //       → 3) AJAX save draft dulu → 4) kalau sukses baru submit approval
  // Tidak ada refresh halaman kecuali step 4 berhasil.

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

  // Step 3+4: save via AJAX lalu redirect ke submit approval
  async function doSaveThenSubmit() {
    closeModal();
    removeToast();

    const btnSubmit = document.querySelector('[onclick="submitOpname()"]');
    if (btnSubmit) { btnSubmit.disabled = true; btnSubmit.textContent = 'Memproses...'; }

    try {
      // Step 3: AJAX save draft
      const fd  = collectFormData();
      const res = await fetch(document.getElementById('formStokFisik').action, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: fd,
      });

      restoreRows();

      if (!res.ok && res.status !== 302) {
        showToast('Gagal menyimpan data sebelum submit. Coba lagi.', 'error');
        if (btnSubmit) { btnSubmit.disabled = false; btnSubmit.textContent = 'Submit untuk Approval'; }
        return;
      }

      // Step 4: Submit approval (form POST biasa → redirect ke detailOpnameStaff)
      document.getElementById('formSubmit').submit();

    } catch (err) {
      restoreRows();
      showToast('Terjadi kesalahan koneksi. Coba lagi.', 'error');
      if (btnSubmit) { btnSubmit.disabled = false; btnSubmit.textContent = 'Submit untuk Approval'; }
    }
  }

  window.submitOpname = function () {
    removeToast();
    const kosongRows = highlightKosong();
    const belum      = kosongRows.length;

    if (belum > 0) {
      // Scroll ke baris kosong pertama
      scrollKeKosongPertama(kosongRows);

      // Tampilkan modal konfirmasi
      modalMsg.textContent = belum + ' barang belum diisi stok fisiknya (ditandai merah).';
      modalConfirm.classList.remove('hidden');
      document.body.classList.add('overflow-hidden');
      modalOk.onclick = () => doSaveThenSubmit();
    } else {
      // Semua sudah diisi, langsung proses
      doSaveThenSubmit();
    }
  };

})();
</script>
@endpush

@endsection
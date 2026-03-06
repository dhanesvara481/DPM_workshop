@extends('admin.layout.app')

@section('title', 'Isi Stok Fisik')

@section('content')
<div class="px-4 md:px-8 py-8 max-w-6xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('stok_opname.index') }}" class="hover:text-slate-800 transition">Stok Opname</a>
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-800 font-medium">Isi Stok Fisik</span>
  </div>

  {{-- Header info sesi --}}
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div>
        <h1 class="text-lg font-bold text-slate-800">Isi Stok Fisik</h1>
        <p class="text-sm text-slate-500 mt-0.5">
          Tanggal: <strong>{{ $opname->tanggal_opname->format('d M Y') }}</strong>
          @if($opname->keterangan)
            &nbsp;·&nbsp; {{ $opname->keterangan }}
          @endif
        </p>
      </div>
      <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $opname->status_badge_class }}">
        {{ $opname->status_label }}
      </span>
    </div>
  </div>

  @if(session('success'))
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
      <svg class="h-5 w-5 shrink-0 text-emerald-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
      <svg class="h-5 w-5 shrink-0 text-rose-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 00-3.42 0z"/>
      </svg>
      {{ session('error') }}
    </div>
  @endif

  {{-- Form isi stok fisik --}}
  <form method="POST" action="{{ route('stok_opname.update', $opname->opname_id) }}" id="formStokFisik">
    @csrf

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">

      {{-- Toolbar atas tabel --}}
      <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <p class="text-sm text-slate-600">
          Total <strong>{{ $opname->details->count() }}</strong> barang —
          <span id="sudahDiisi" class="font-medium text-emerald-600">0</span> sudah diisi,
          <span id="belumDiisi" class="font-medium text-rose-600">{{ $opname->details->count() }}</span> belum
        </p>
        {{-- Search filter lokal --}}
        <input type="text" id="searchBarang" placeholder="Cari nama / kode barang…"
               class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 w-full sm:w-64">
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50">
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Barang</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Satuan</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok Sistem</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-36">Stok Fisik</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Selisih</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-48">Keterangan</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100" id="tabelBody">
            @foreach($opname->details as $i => $detail)
            <tr class="hover:bg-slate-50 transition barang-row"
                data-nama="{{ strtolower($detail->nama_barang_snapshot) }}"
                data-kode="{{ strtolower($detail->kode_barang_snapshot) }}">

              <input type="hidden" name="items[{{ $i }}][detail_id]" value="{{ $detail->detail_opname_id }}">

              <td class="px-5 py-3 text-slate-500 font-mono text-xs whitespace-nowrap">
                {{ $detail->kode_barang_snapshot }}
              </td>
              <td class="px-5 py-3 font-medium text-slate-800 whitespace-nowrap">
                {{ $detail->nama_barang_snapshot }}
              </td>
              <td class="px-5 py-3 text-center text-slate-500 text-xs">
                {{ $detail->satuan_snapshot }}
              </td>
              <td class="px-5 py-3 text-center font-semibold text-slate-700">
                {{ $detail->stok_sistem }}
              </td>
              <td class="px-5 py-3 text-center">
                <input type="number" name="items[{{ $i }}][stok_fisik]"
                       value="{{ old("items.{$i}.stok_fisik", $detail->stok_fisik) }}"
                       min="0"
                       placeholder="—"
                       class="stok-fisik-input w-24 text-center rounded-lg border border-slate-200 px-2 py-1.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent"
                       data-sistem="{{ $detail->stok_sistem }}"
                       data-row="{{ $i }}">
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
                       placeholder="Opsional"
                       maxlength="255"
                       class="w-full rounded-lg border border-slate-200 px-2 py-1.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-slate-900">
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- Tombol aksi --}}
    <div class="flex flex-wrap items-center gap-3">
      <button type="submit"
              class="px-5 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-medium hover:bg-slate-700 transition shadow-sm">
        Simpan Draft
      </button>

      <button type="button" onclick="submitOpname()"
              class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition shadow-sm">
        Submit untuk Approval
      </button>

      <a href="{{ route('stok_opname.index') }}"
         class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition">
        Kembali
      </a>
    </div>
  </form>

  {{-- Form submit terpisah --}}
  <form id="formSubmit" method="POST" action="{{ route('stok_opname.submit', $opname->opname_id) }}" class="hidden">
    @csrf
  </form>

</div>

@push('scripts')
<script>
  // ── Hitung selisih realtime ──────────────────────────────────────────────
  const inputs = document.querySelectorAll('.stok-fisik-input');
  const sudahEl = document.getElementById('sudahDiisi');
  const belumEl = document.getElementById('belumDiisi');
  const total   = inputs.length;

  function hitungSelisih(input) {
    const row     = input.dataset.row;
    const sistem  = parseInt(input.dataset.sistem);
    const fisik   = input.value !== '' ? parseInt(input.value) : null;
    const selEl   = document.getElementById('selisih-' + row);

    if (fisik === null || isNaN(fisik)) {
      selEl.textContent = '—';
      selEl.className   = 'inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-400';
      return;
    }

    const selisih = fisik - sistem;
    selEl.textContent = selisih > 0 ? '+' + selisih : selisih;

    if (selisih > 0) {
      selEl.className = 'inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700';
    } else if (selisih < 0) {
      selEl.className = 'inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700';
    } else {
      selEl.className = 'inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700';
    }
  }

  function updateCounter() {
    let sudah = 0;
    inputs.forEach(inp => { if (inp.value !== '') sudah++; });
    sudahEl.textContent = sudah;
    belumEl.textContent = total - sudah;
  }

  inputs.forEach(inp => {
    inp.addEventListener('input', () => { hitungSelisih(inp); updateCounter(); });
    // Init
    hitungSelisih(inp);
  });
  updateCounter();

  // ── Submit untuk approval ─────────────────────────────────────────────────
  function submitOpname() {
    const belum = parseInt(belumEl.textContent);
    if (belum > 0) {
      if (!confirm(belum + ' barang belum diisi stok fisiknya. Tetap submit?')) return;
    }
    // Simpan dulu lalu submit
    document.getElementById('formStokFisik').addEventListener('submit', function handler(e) {
      e.preventDefault();
      this.removeEventListener('submit', handler);
      // Submit via fetch lalu redirect ke form submit
      fetch(this.action, { method: 'POST', body: new FormData(this) })
        .then(() => document.getElementById('formSubmit').submit());
    });
    document.getElementById('formStokFisik').dispatchEvent(new Event('submit'));
  }

  // ── Search filter ─────────────────────────────────────────────────────────
  document.getElementById('searchBarang').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.barang-row').forEach(row => {
      const nama = row.dataset.nama;
      const kode = row.dataset.kode;
      row.style.display = (nama.includes(q) || kode.includes(q)) ? '' : 'none';
    });
  });
</script>
@endpush
@endsection
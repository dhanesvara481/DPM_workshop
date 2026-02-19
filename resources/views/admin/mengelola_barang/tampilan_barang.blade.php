@extends('admin.layout.app')

@section('title', 'Barang - DPM Workshop')

@section('content')

{{-- TOPBAR --}}
<header class="relative h-16 bg-white/75 backdrop-blur border-b border-slate-200 sticky top-0 z-20">
  <div class="h-full px-4 sm:px-6 flex items-center justify-between gap-3">
    <div class="flex items-center gap-3 min-w-0">
      <button id="btnSidebar" type="button"
              class="md:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
              aria-label="Buka menu">
        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      <div class="min-w-0">
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Kelola Barang</h1>
        <p class="text-xs text-slate-500">Tambah, ubah, dan kelola stok barang.</p>
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
  <div class="max-w-[1120px] mx-auto">

    {{-- Flash Messages --}}
    @if(session('success'))
      <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
        <div class="flex items-start gap-3">
          <svg class="h-5 w-5 text-emerald-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <p class="text-sm text-emerald-800">{{ session('success') }}</p>
        </div>
      </div>
    @endif

    @if(session('error'))
      <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
        <div class="flex items-start gap-3">
          <svg class="h-5 w-5 text-red-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
      </div>
    @endif

    {{-- TOOLBAR --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
      <a href="{{ route('tambah_barang') }}"
         class="btn-shine inline-flex w-fit items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold
                bg-blue-950 text-white hover:bg-blue-900 transition
                shadow-[0_12px_24px_rgba(2,6,23,0.16)]">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
        </svg>
        Tambah
      </a>

      <div class="w-full sm:w-[380px]">
        <div class="relative">
          <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 100-16 8 8 0 000 16z"/>
            </svg>
          </span>

          <input id="searchBarang"
                 type="text"
                 placeholder="Cari kode / nama barang..."
                 class="w-full pl-9 pr-10 py-2.5 rounded-lg border border-slate-200 bg-white/90
                        text-sm placeholder:text-slate-400
                        focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
        </div>
      </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

      <div class="overflow-x-auto">
        <table class="w-full text-sm table-auto" id="tableBarang">

          <thead class="bg-slate-50/90 sticky top-0 z-10 backdrop-blur">
            <tr class="text-slate-600">
              <th class="px-5 py-4 font-semibold text-xs uppercase tracking-wide whitespace-nowrap text-left w-[64px]">No</th>
              <th class="px-5 py-4 font-semibold text-xs uppercase tracking-wide whitespace-nowrap text-left w-[160px]">Kode Barang</th>
              <th class="px-5 py-4 font-semibold text-xs uppercase tracking-wide whitespace-nowrap text-left">Nama Barang</th>
              <th class="px-5 py-4 font-semibold text-xs uppercase tracking-wide whitespace-nowrap text-left w-[120px]">Satuan</th>
              <th class="px-5 py-4 font-semibold text-xs uppercase tracking-wide whitespace-nowrap text-right w-[110px]">Stok</th>
              <th class="px-5 py-4 font-semibold text-xs uppercase tracking-wide whitespace-nowrap text-right w-[150px]">Harga Beli</th>
              <th class="px-5 py-4 font-semibold text-xs uppercase tracking-wide whitespace-nowrap text-right w-[150px]">Harga Jual</th>
              <th class="px-5 py-4 font-semibold text-xs uppercase tracking-wide whitespace-nowrap text-right w-[120px]">Aksi</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-200" id="tableBody">
          @forelse (($barangs ?? []) as $i => $b)
            @php
              $stok = (int) ($b->stok ?? 0);

              $stokBadge = match(true) {
                $stok <= 0 => 'bg-rose-50 text-rose-700 border-rose-200',
                $stok <= 5 => 'bg-amber-50 text-amber-700 border-amber-200',
                default    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
              };

              $stokLabel = match(true) {
                $stok <= 0 => 'Habis',
                $stok <= 5 => 'Menipis',
                default    => 'Aman',
              };
            @endphp

            <tr class="row-lift hover:bg-slate-50/70 transition"
                data-searchable
                data-kode="{{ strtolower($b->kode_barang ?? '') }}"
                data-nama="{{ strtolower($b->nama_barang ?? '') }}">

              <td class="px-5 py-4 text-slate-600 tabular-nums">{{ $i + 1 }}</td>

              <td class="px-5 py-4 font-mono text-sm font-semibold text-blue-900 whitespace-nowrap">
                {{ $b->kode_barang ?? '-' }}
              </td>

              <td class="px-5 py-4 font-semibold text-slate-900">
                {{ $b->nama_barang ?? '-' }}
              </td>

              <td class="px-5 py-4 text-slate-700 whitespace-nowrap">
                <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-xs font-semibold">
                  {{ $b->satuan ?? '-' }}
                </span>
              </td>

              <td class="px-5 py-4 text-right whitespace-nowrap">
                <div class="inline-flex flex-col items-end leading-tight">
                  <span class="font-extrabold text-slate-900 tabular-nums">{{ $stok }}</span>
                  <span class="mt-1 inline-flex rounded-full border px-2 py-[2px] text-[10px] font-semibold {{ $stokBadge }}">
                    {{ $stokLabel }}
                  </span>
                </div>
              </td>

              <td class="px-5 py-4 text-right text-slate-700 tabular-nums whitespace-nowrap">
                {{ isset($b->harga_beli) ? 'Rp '.number_format($b->harga_beli, 0, ',', '.') : '-' }}
              </td>

              <td class="px-5 py-4 text-right text-slate-700 tabular-nums whitespace-nowrap">
                {{ isset($b->harga_jual) ? 'Rp '.number_format($b->harga_jual, 0, ',', '.') : '-' }}
              </td>

              <td class="px-5 py-4">
                <div class="flex flex-col items-end gap-2">
                  <a href="{{ route('ubah_barang', $b->barang_id ?? 0) }}"
                     class="w-[92px] rounded-md px-3 py-2 text-xs font-semibold text-center
                            border border-slate-200 bg-white hover:bg-slate-50 transition whitespace-nowrap">
                    Ubah
                  </a>

                  <button type="button"
                          onclick="confirmDelete({{ $b->barang_id ?? 0 }}, '{{ addslashes($b->kode_barang ?? '') }}', '{{ addslashes($b->nama_barang ?? '') }}')"
                          class="w-[92px] rounded-md px-3 py-2 text-xs font-semibold
                                 border border-red-200 bg-red-50 text-red-700 hover:bg-red-100 transition whitespace-nowrap">
                    Hapus
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr id="emptyRow">
              <td colspan="8" class="px-5 py-10 text-center text-slate-500">
                <div class="flex flex-col items-center gap-3">
                  <svg class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                  </svg>
                  <p class="text-sm font-medium">Belum ada data barang</p>
                </div>
              </td>
            </tr>
          @endforelse
          </tbody>

        </table>
      </div>

      {{-- No search results --}}
      <div id="noResults" class="hidden px-5 py-10 text-center text-slate-500">
        <div class="flex flex-col items-center gap-3">
          <svg class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.3-4.3m0 0A8 8 0 1116.7 4.3a8 8 0 010 12.4z"/>
          </svg>
          <p class="text-sm font-medium">Tidak ada hasil ditemukan</p>
        </div>
      </div>

      <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
        © DPM Workshop 2025
      </div>
    </div>

  </div>
</section>

{{-- Hidden form for delete --}}
<form id="deleteForm" method="POST" style="display:none;">
  @csrf
  @method('DELETE')
</form>

{{-- Confirm Modal (reusable) --}}
<div id="confirmModal"
     class="fixed inset-0 z-[999] hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-3">
  <div class="w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-[0_30px_80px_rgba(2,6,23,0.30)] overflow-hidden">
    <div class="p-5 border-b border-slate-200 flex items-start justify-between gap-3">
      <div>
        <div id="cmTitle" class="text-lg font-semibold text-slate-900">Konfirmasi</div>
        <div id="cmMsg" class="text-sm text-slate-600 mt-1">—</div>
      </div>
      <button type="button" id="cmX"
              class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 grid place-items-center"
              aria-label="Tutup">
        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <div class="p-5">
      <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs text-rose-700">
        <b>Peringatan:</b> Aksi ini tidak bisa dibatalkan. Pastikan barang yang dipilih benar.
      </div>
      <div class="mt-4 flex justify-end gap-2">
        <button type="button" id="cmCancel"
                class="h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-semibold">
          Batal
        </button>
        <button type="button" id="cmOk"
                class="h-10 px-5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold">
          Ya, Hapus
        </button>
      </div>
    </div>
  </div>
</div>

<style>
  @media (prefers-reduced-motion: reduce) {
    .row-lift, .btn-shine { animation: none !important; transition: none !important; }
  }

  .row-lift {
    transform: translateY(0);
    transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease;
  }
  .row-lift:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 26px rgba(2,6,23,0.06);
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

  .clear-btn {
    opacity: 0;
    pointer-events: none;
    transform: scale(.9);
    transition: .15s ease;
  }
  .clear-btn.show {
    opacity: 1;
    pointer-events: auto;
    transform: scale(1);
  }

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
  // ===== Search =====
  const searchInput = document.getElementById('searchBarang');
  const noResults   = document.getElementById('noResults');

  if (searchInput) {
    const wrap = searchInput.parentElement;

    // clear button
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = "clear-btn absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-700";
    btn.setAttribute('aria-label', 'Hapus pencarian');
    btn.innerHTML = `
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>`;
    wrap.appendChild(btn);

    const syncClearBtn = () => btn.classList.toggle('show', searchInput.value.trim().length > 0);

    const applySearch = () => {
      syncClearBtn();
      const q = searchInput.value.toLowerCase().trim();
      const rows = document.querySelectorAll('[data-searchable]');
      let visible = 0;

      rows.forEach(row => {
        const match = !q || (row.dataset.kode || '').includes(q) || (row.dataset.nama || '').includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
      });

      if (noResults) noResults.classList.toggle('hidden', visible > 0 || !q);
    };

    searchInput.addEventListener('input', applySearch);
    btn.addEventListener('click', () => {
      searchInput.value = '';
      searchInput.focus();
      applySearch();
    });

    applySearch();
  }

  // ===== Confirm Modal (hapus) =====
  const modal    = document.getElementById('confirmModal');
  const cmTitle  = document.getElementById('cmTitle');
  const cmMsg    = document.getElementById('cmMsg');
  const cmOk     = document.getElementById('cmOk');
  const cmCancel = document.getElementById('cmCancel');
  const cmX      = document.getElementById('cmX');
  let pendingAction = null;

  const openModal  = ({ title, msg, onConfirm }) => {
    pendingAction = onConfirm || null;
    cmTitle.textContent = title || 'Konfirmasi';
    cmMsg.textContent   = msg   || '—';
    modal?.classList.remove('hidden');
    modal?.classList.add('flex');
  };
  const closeModal = () => {
    pendingAction = null;
    modal?.classList.add('hidden');
    modal?.classList.remove('flex');
  };

  modal?.addEventListener('click',   (e) => { if (e.target === modal) closeModal(); });
  cmCancel?.addEventListener('click', closeModal);
  cmX?.addEventListener('click',      closeModal);
  cmOk?.addEventListener('click',     () => { pendingAction?.(); closeModal(); });

  // fungsi global dipanggil dari onclick di row
  function confirmDelete(barangId, kode, nama) {
    openModal({
      title: 'Hapus barang?',
      msg: `Yakin mau hapus ${kode} — ${nama}?`,
      onConfirm: () => {
        const form = document.getElementById('deleteForm');
        form.action = `/hapus_barang/${barangId}`;
        form.submit();
      }
    });
  }
</script>

@endsection
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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900 leading-tight">Stok Opname</h1>
        <p class="text-xs text-slate-500 leading-tight">{{ now()->format('d M Y') }}</p>
      </div>
    </div>
    <div class="shrink-0">
      <a href="{{ route('stok_opname.create') }}"
         class="h-10 px-4 rounded-xl bg-slate-900 text-white hover:bg-slate-700 transition text-sm font-semibold inline-flex items-center gap-1.5">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        <span class="hidden sm:inline">Buat Sesi</span>
        <span class="sm:hidden">Buat</span>
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

  <div class="max-w-7xl mx-auto w-full space-y-5">

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
        <svg class="h-5 w-5 shrink-0 text-rose-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        {{ session('error') }}
      </div>
    @endif

    {{-- Filter --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_4px_20px_rgba(2,6,23,0.06)] p-4 sm:p-5">
      <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[130px]">
          <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
          <select name="status"
                  class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900">
            <option value="">Semua</option>
            <option value="draft"             {{ request('status') === 'draft'             ? 'selected' : '' }}>Draft</option>
            <option value="menunggu_approval" {{ request('status') === 'menunggu_approval' ? 'selected' : '' }}>Menunggu Approval</option>
            <option value="disetujui"         {{ request('status') === 'disetujui'         ? 'selected' : '' }}>Disetujui</option>
            <option value="ditolak"           {{ request('status') === 'ditolak'           ? 'selected' : '' }}>Ditolak</option>
          </select>
        </div>
        <div class="flex-1 min-w-[130px]">
          <label class="block text-xs font-medium text-slate-500 mb-1">Dari</label>
          <input type="date" name="dari" value="{{ request('dari') }}"
                 class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900">
        </div>
        <div class="flex-1 min-w-[130px]">
          <label class="block text-xs font-medium text-slate-500 mb-1">Sampai</label>
          <input type="date" name="sampai" value="{{ request('sampai') }}"
                 class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900">
        </div>
        <div class="flex gap-2">
          <button type="submit"
                  class="h-9 px-4 rounded-lg bg-slate-900 text-white text-sm font-medium hover:bg-slate-700 transition">
            Filter
          </button>
          @if(request()->hasAny(['status','dari','sampai']))
            <a href="{{ route('stok_opname.index') }}"
               class="h-9 px-4 rounded-lg border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition inline-flex items-center">
              Reset
            </a>
          @endif
        </div>
      </form>
    </div>

    {{-- Konten --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_4px_20px_rgba(2,6,23,0.06)] overflow-hidden">

      @if($opnames->isEmpty())
        <div class="py-20 text-center">
          <div class="mx-auto h-14 w-14 rounded-2xl bg-slate-100 grid place-items-center mb-4">
            <svg class="h-7 w-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
          </div>
          <p class="text-slate-500 text-sm">Belum ada sesi stok opname.</p>
          <a href="{{ route('stok_opname.create') }}" class="mt-3 inline-block text-sm font-medium text-slate-900 underline underline-offset-2">
            Buat sesi pertama
          </a>
        </div>
      @else

        {{-- Desktop: tabel --}}
        <div class="hidden sm:block overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-slate-100 bg-slate-50">
                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Dibuat Oleh</th>
                <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Selisih</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @foreach($opnames as $opname)
              <tr class="hover:bg-slate-50 transition">
                <td class="px-5 py-4 font-medium text-slate-800 whitespace-nowrap">
                  {{ $opname->tanggal_opname->format('d M Y') }}
                </td>
                <td class="px-5 py-4 text-slate-600 max-w-[180px] truncate">
                  {{ $opname->keterangan ?? '-' }}
                </td>
                <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
                  {{ $opname->nama_pembuat }}
                </td>
                <td class="px-5 py-4 text-center text-slate-700 font-medium">
                  {{ $opname->details_count }}
                </td>
                <td class="px-5 py-4 text-center">
                  @if($opname->jumlah_selisih_count > 0)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                      {{ $opname->jumlah_selisih_count }} item
                    </span>
                  @else
                    <span class="text-slate-400 text-xs">—</span>
                  @endif
                </td>
                <td class="px-5 py-4">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $opname->status_badge_class }}">
                    {{ $opname->status_label }}
                  </span>
                </td>
                <td class="px-5 py-4 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <a href="{{ route('stok_opname.show', $opname->opname_id) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                      Detail
                    </a>
                    @if($opname->isDraft())
                      <a href="{{ route('stok_opname.edit', $opname->opname_id) }}"
                         class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-900 text-white text-xs font-medium hover:bg-slate-700 transition">
                        Isi Stok
                      </a>
                    @endif
                    @if(in_array($opname->status, ['draft', 'ditolak']))
                      <button type="button"
                              class="btn-hapus-opname inline-flex items-center px-3 py-1.5 rounded-lg border border-rose-200 text-xs font-medium text-rose-600 hover:bg-rose-50 transition"
                              data-id="{{ $opname->opname_id }}"
                              data-tanggal="{{ $opname->tanggal_opname->format('d M Y') }}"
                              data-keterangan="{{ $opname->keterangan ?? '-' }}"
                              data-action="{{ route('stok_opname.destroy', $opname->opname_id) }}">
                        Hapus
                      </button>
                    @endif
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- Mobile: card list --}}
        <div class="sm:hidden divide-y divide-slate-100">
          @foreach($opnames as $opname)
          <div class="p-4">
            <div class="flex items-start justify-between gap-3 mb-2">
              <div>
                <p class="text-sm font-bold text-slate-800">{{ $opname->tanggal_opname->format('d M Y') }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $opname->keterangan ?? '-' }}</p>
              </div>
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $opname->status_badge_class }} shrink-0">
                {{ $opname->status_label }}
              </span>
            </div>
            <div class="flex items-center gap-4 text-xs text-slate-500 mb-3">
              <span>Oleh: <strong class="text-slate-700">{{ $opname->nama_pembuat }}</strong></span>
              <span>{{ $opname->details_count }} barang</span>
              @if($opname->jumlah_selisih_count > 0)
                <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 font-medium">{{ $opname->jumlah_selisih_count }} selisih</span>
              @endif
            </div>
            <div class="flex flex-wrap gap-2">
              <a href="{{ route('stok_opname.show', $opname->opname_id) }}"
                 class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                Detail
              </a>
              @if($opname->isDraft())
                <a href="{{ route('stok_opname.edit', $opname->opname_id) }}"
                   class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-900 text-white text-xs font-medium hover:bg-slate-700 transition">
                  Isi Stok
                </a>
              @endif
              @if(in_array($opname->status, ['draft', 'ditolak']))
                <button type="button"
                        class="btn-hapus-opname inline-flex items-center px-3 py-1.5 rounded-lg border border-rose-200 text-xs font-medium text-rose-600 hover:bg-rose-50 transition"
                        data-id="{{ $opname->opname_id }}"
                        data-tanggal="{{ $opname->tanggal_opname->format('d M Y') }}"
                        data-keterangan="{{ $opname->keterangan ?? '-' }}"
                        data-action="{{ route('stok_opname.destroy', $opname->opname_id) }}">
                  Hapus
                </button>
              @endif
            </div>
          </div>
          @endforeach
        </div>

        {{-- Pagination --}}
        @if($opnames->hasPages())
          <div class="px-5 py-4 border-t border-slate-100">
            {{ $opnames->withQueryString()->links() }}
          </div>
        @endif
      @endif
    </div>

  </div>
</section>

{{-- Hidden form untuk hapus --}}
<form id="formHapusOpname" method="POST" action="" class="hidden">
  @csrf
  @method('DELETE')
</form>

{{-- MODAL HAPUS --}}
<div id="hapusOpnameModal" class="fixed inset-0 z-[999] hidden">
  <div id="hapusOpnameOverlay" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
  <div class="relative min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-2xl bg-white border border-slate-200 shadow-[0_30px_90px_rgba(2,6,23,0.30)] overflow-hidden">
      <div class="p-5 border-b border-slate-200 flex items-start justify-between gap-3">
        <div>
          <div class="text-sm font-semibold text-slate-900">Hapus Sesi Opname</div>
          <div class="text-xs text-slate-500 mt-0.5">Tindakan ini tidak dapat dibatalkan.</div>
        </div>
        <button id="hapusOpnameClose" type="button"
                class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center">
          <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div class="p-5 space-y-4">
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4">
          <div class="flex items-start gap-3">
            <div class="h-10 w-10 rounded-xl bg-rose-100 text-rose-600 grid place-items-center border border-rose-200 shrink-0">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
              </svg>
            </div>
            <div>
              <div class="text-sm font-semibold text-rose-900">Konfirmasi Penghapusan</div>
              <div class="text-xs text-rose-700 mt-0.5">Tanggal: <span id="hapusOpnameTanggal" class="font-semibold">—</span></div>
              <div class="text-xs text-rose-600 mt-0.5">Keterangan: <span id="hapusOpnameKeterangan" class="font-semibold">—</span></div>
            </div>
          </div>
        </div>
        <p class="text-xs text-slate-500">Seluruh data sesi opname beserta detail stok fisiknya akan <span class="font-semibold text-rose-600">dihapus permanen</span>.</p>
        <div class="flex flex-col sm:flex-row gap-2 sm:justify-end">
          <button type="button" id="hapusOpnameCancel"
                  class="h-11 px-5 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
            Batal
          </button>
          <button type="button" id="hapusOpnameConfirm"
                  class="h-11 px-5 rounded-2xl bg-rose-600 text-white hover:bg-rose-700 transition text-sm font-semibold">
            Ya, Hapus
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function () {
  const modal        = document.getElementById('hapusOpnameModal');
  const overlay      = document.getElementById('hapusOpnameOverlay');
  const closeBtn     = document.getElementById('hapusOpnameClose');
  const cancelBtn    = document.getElementById('hapusOpnameCancel');
  const confirmBtn   = document.getElementById('hapusOpnameConfirm');
  const tanggalEl    = document.getElementById('hapusOpnameTanggal');
  const keteranganEl = document.getElementById('hapusOpnameKeterangan');
  const form         = document.getElementById('formHapusOpname');

  function openModal(action, tanggal, keterangan) {
    form.setAttribute('action', action);
    tanggalEl.textContent    = tanggal    || '—';
    keteranganEl.textContent = keterangan || '-';
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  }
  function closeModal() {
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    form.setAttribute('action', '');
    tanggalEl.textContent = keteranganEl.textContent = '—';
  }

  document.querySelectorAll('.btn-hapus-opname').forEach(btn => {
    btn.addEventListener('click', () => openModal(btn.dataset.action, btn.dataset.tanggal, btn.dataset.keterangan));
  });
  overlay?.addEventListener('click', closeModal);
  closeBtn?.addEventListener('click', closeModal);
  cancelBtn?.addEventListener('click', closeModal);
  confirmBtn?.addEventListener('click', () => form.submit());
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal(); });
})();
</script>
@endpush

@endsection
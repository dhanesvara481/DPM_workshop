{{-- resources/views/admin/jadwal_kerja/hapus_jadwal_kerja.blade.php --}}
@extends('admin.layout.app')

@section('title', 'DPM Workshop - Hapus Jadwal')

@section('content')

<header class="sticky top-0 z-20 border-b border-slate-200 bg-white/80 backdrop-blur">
  <div class="h-16 px-4 sm:px-6 flex items-center justify-between gap-3">
    <div class="flex items-center gap-3 min-w-0">
      <button id="btnSidebar" type="button"
              class="md:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center">
        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <div class="min-w-0">
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Hapus Jadwal Kerja</h1>
        <p class="text-xs text-slate-500">Centang item yang ingin dihapus, lalu konfirmasi.</p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('kelola_jadwal_kerja') }}"
         class="inline-flex items-center gap-2 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition px-3 text-sm font-medium">
        <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
      </a>
    </div>
  </div>
</header>

<section class="relative p-4 sm:p-6">
  {{-- BACKGROUND --}}
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
    <div class="absolute inset-0 opacity-[0.10]"
         style="background-image:linear-gradient(to right,rgba(2,6,23,.06) 1px,transparent 1px),linear-gradient(to bottom,rgba(2,6,23,.06) 1px,transparent 1px);background-size:56px 56px"></div>
  </div>

  <div class="max-w-[980px] mx-auto w-full space-y-5">

    @php
      $date = $date ?? request('date') ?? now()->format('Y-m-d');

      $statusMap = [
        'aktif'   => ['label' => 'Aktif',   'pill' => 'pill-aktif'],
        'catatan' => ['label' => 'Catatan', 'pill' => 'pill-catatan'],
        'tutup'   => ['label' => 'Tutup',   'pill' => 'pill-tutup'],
      ];

      $items = collect($jadwalKerjas ?? [])->map(fn($j) => [
        'id'     => $j->jadwal_id,
        'title'  => strtolower($j->status) === 'tutup'
                      ? 'Hari Libur'
                      : (($j->waktu_shift ?? 'Jadwal') . ' — ' . ($j->user->username ?? 'Staf')),
        'status' => strtolower($j->status),
        'time'   => strtolower($j->status) === 'tutup'
                      ? null
                      : (substr($j->jam_mulai ?? '', 0, 5) . ' – ' . substr($j->jam_selesai ?? '', 0, 5)),
        'desc'   => $j->deskripsi,
      ])->toArray();

      $tanggalLabel = \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('l, d F Y');
    @endphp

    {{-- Flash messages --}}
    @if(session('success'))
      <div class="rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 text-sm flex items-center gap-3">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="rounded-xl bg-rose-50 border border-rose-200 text-rose-700 p-4 text-sm">{{ session('error') }}</div>
    @endif
    @if($errors->any())
      <div class="rounded-xl bg-rose-50 border border-rose-200 text-rose-700 p-4 text-sm">
        <ul class="list-disc pl-5 space-y-1">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
      </div>
    @endif

    {{-- STEP 1: INFO TANGGAL --}}
    <div class="rounded-2xl bg-white border border-slate-200 shadow-[0_4px_16px_rgba(2,6,23,0.06)] overflow-hidden">
      <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-3">
        <span class="h-6 w-6 rounded-lg bg-slate-900 text-white grid place-items-center text-[11px] font-bold shrink-0">1</span>
        <p class="text-sm font-semibold text-slate-900">Tanggal</p>
      </div>
      <div class="p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="inline-flex items-center gap-3 h-11 rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm text-slate-700 font-semibold">
          <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          {{ $tanggalLabel }}
        </div>
        <div class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2">
          <span class="h-2 w-2 rounded-full bg-rose-500"></span>
          <span class="text-xs font-semibold text-rose-700">Aksi ini tidak bisa dibatalkan</span>
        </div>
      </div>
    </div>

    {{-- STEP 2: PILIH ITEM --}}
    <div class="rounded-2xl bg-white border border-slate-200 shadow-[0_4px_16px_rgba(2,6,23,0.06)] overflow-hidden">
      <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
          <span class="h-6 w-6 rounded-lg bg-slate-900 text-white grid place-items-center text-[11px] font-bold shrink-0">2</span>
          <p class="text-sm font-semibold text-slate-900">
            Pilih yang mau dihapus
            <span class="text-xs font-normal text-slate-400 ml-1">bisa 1, beberapa, atau semua</span>
          </p>
        </div>
        <div class="flex items-center gap-2">
          <label class="inline-flex items-center gap-2 h-8 px-3 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 select-none cursor-pointer hover:bg-slate-50 transition">
            <input id="checkAll" type="checkbox" class="h-3.5 w-3.5 rounded border-slate-300">
            Pilih semua
          </label>
          <div class="inline-flex items-center gap-1.5 h-8 rounded-xl border border-slate-200 bg-slate-50 px-3">
            <span class="text-[11px] text-slate-500">Terpilih:</span>
            <span id="selectedCount" class="text-xs font-bold text-slate-900">0</span>
          </div>
        </div>
      </div>

      <form id="formDeleteSelected"
            method="POST"
            action="{{ route('hapus_jadwal_kerja_batch') }}"
            class="p-4 space-y-2">
        @csrf
        @method('DELETE')
        <input type="hidden" name="date" value="{{ $date }}">

        @forelse($items as $it)
          @php
            $s  = strtolower(trim($it['status'] ?? 'aktif'));
            $ui = $statusMap[$s] ?? ['label' => $it['status'] ?? '—', 'pill' => 'pill-aktif'];
          @endphp

          <label class="block cursor-pointer">
            <div class="item-card rounded-2xl border border-slate-200 bg-white p-4 hover:border-slate-300 hover:shadow-sm transition flex items-start gap-3">
              <div class="mt-0.5">
                <input type="checkbox" class="itemCheck h-4 w-4 rounded border-slate-300"
                       name="targets[]" value="event:{{ $it['id'] }}">
              </div>
              <div class="min-w-0 flex-1">
                <div class="flex items-start justify-between gap-3">
                  <div class="min-w-0">
                    <div class="text-sm font-semibold text-slate-900 truncate">{{ $it['title'] ?? 'Jadwal' }}</div>
                    @if(!empty($it['time']))
                      <div class="text-xs text-slate-500 mt-0.5">{{ $it['time'] }}</div>
                    @endif
                  </div>
                  <span class="{{ $ui['pill'] }}">{{ strtoupper($ui['label']) }}</span>
                </div>
                @if(!empty($it['desc']))
                  <div class="text-xs text-slate-500 mt-2">{{ $it['desc'] }}</div>
                @endif
              </div>
            </div>
          </label>
        @empty
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center">
            <div class="text-sm text-slate-500">Tidak ada jadwal di tanggal ini.</div>
          </div>
        @endforelse
      </form>
    </div>

    {{-- STEP 3: KONFIRMASI & AKSI --}}
    <div class="rounded-2xl bg-white border border-slate-200 shadow-[0_4px_16px_rgba(2,6,23,0.06)] overflow-hidden">
      <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-3">
        <span class="h-6 w-6 rounded-lg bg-rose-600 text-white grid place-items-center text-[11px] font-bold shrink-0">3</span>
        <div>
          <p class="text-sm font-semibold text-slate-900">Konfirmasi Hapus</p>
          <p class="text-xs text-slate-400" id="submitSummary">Belum ada item yang dipilih.</p>
        </div>
      </div>
      <div class="p-5 flex flex-col sm:flex-row gap-2 sm:justify-end">
        <a id="btnBatal" href="{{ route('kelola_jadwal_kerja') }}"
           class="h-11 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold inline-flex items-center justify-center">
          Batal
        </a>

        <button id="btnDeleteAll" type="button"
                class="h-11 px-5 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 transition text-sm font-semibold">
          Hapus semua di tanggal ini
        </button>

        <button id="btnDeleteSelected" type="button" disabled
                class="h-11 px-5 rounded-xl bg-rose-600 text-white hover:bg-rose-700 transition text-sm font-semibold
                       shadow-[0_8px_20px_rgba(244,63,94,0.25)] disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none">
          Hapus yang dipilih
        </button>
      </div>
      <div class="px-5 pb-4 text-[11px] text-slate-400">
        Tips: centang item yang mau dihapus → klik <b>Hapus yang dipilih</b>. Atau klik <b>Hapus semua</b> untuk hapus semuanya.
      </div>
    </div>

    {{-- Form hapus semua (hidden) --}}
    <form id="formDeleteAll" action="{{ route('hapus_jadwal_kerja_all') }}" method="POST" class="hidden">
      @csrf
      @method('DELETE')
      <input type="hidden" name="date" value="{{ $date }}">
    </form>

  </div>
</section>

@endsection

@push('head')
<style>
  .pill-aktif   { display:inline-flex;align-items:center;font-size:11px;padding:4px 10px;border-radius:12px;font-weight:700;background:rgba(16,185,129,0.12);border:1px solid rgba(16,185,129,0.25);color:rgba(6,95,70,0.95);white-space:nowrap; }
  .pill-catatan { display:inline-flex;align-items:center;font-size:11px;padding:4px 10px;border-radius:12px;font-weight:700;background:rgba(245,158,11,0.12);border:1px solid rgba(245,158,11,0.25);color:rgba(120,53,15,0.95);white-space:nowrap; }
  .pill-tutup   { display:inline-flex;align-items:center;font-size:11px;padding:4px 10px;border-radius:12px;font-weight:700;background:rgba(244,63,94,0.12);border:1px solid rgba(244,63,94,0.25);color:rgba(136,19,55,0.95);white-space:nowrap; }

  .item-card { transition: all .15s ease; }
  .item-card:has(input:checked) {
    border-color: rgba(244,63,94,0.35) !important;
    background: rgba(244,63,94,0.03) !important;
  }
</style>
@endpush

@push('scripts')
<script>
  const checkAll          = document.getElementById('checkAll');
  const checks            = Array.from(document.querySelectorAll('.itemCheck'));
  const selectedCount     = document.getElementById('selectedCount');
  const btnDeleteSelected = document.getElementById('btnDeleteSelected');
  const formDeleteSelected= document.getElementById('formDeleteSelected');
  const btnDeleteAll      = document.getElementById('btnDeleteAll');
  const formDeleteAll     = document.getElementById('formDeleteAll');
  const btnBatal          = document.getElementById('btnBatal');
  const submitSummary     = document.getElementById('submitSummary');

  function refresh() {
    const chosen = checks.filter(c => c.checked).length;
    selectedCount.textContent  = String(chosen);
    btnDeleteSelected.disabled = chosen === 0;
    submitSummary.textContent  = chosen === 0
      ? 'Belum ada item yang dipilih.'
      : `${chosen} item siap dihapus — aksi ini tidak bisa dibatalkan.`;

    if (checks.length > 0) {
      checkAll.indeterminate = chosen > 0 && chosen < checks.length;
      checkAll.checked = chosen === checks.length;
    }
  }

  checkAll?.addEventListener('change', e => {
    checks.forEach(c => c.checked = e.target.checked);
    refresh();
  });
  checks.forEach(c => c.addEventListener('change', refresh));
  refresh();

  // ── Confirm modal ─────────────────────────────────────────────────────────
  function showConfirm({ title, message, note, confirmText, cancelText, tone = 'neutral', onConfirm }) {
    const btnCls = tone === 'danger'
      ? 'bg-rose-600 hover:bg-rose-700'
      : 'bg-slate-900 hover:bg-slate-800';

    const wrap = document.createElement('div');
    wrap.className = 'fixed inset-0 z-[999] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-3';
    wrap.innerHTML = `
      <div class="w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-[0_30px_80px_rgba(2,6,23,0.28)] overflow-hidden">
        <div class="p-5 border-b border-slate-200 flex items-start justify-between gap-3">
          <div>
            <div class="text-base font-semibold text-slate-900">${title}</div>
            <div class="text-sm text-slate-500 mt-1">${message}</div>
          </div>
          <button type="button" class="btn-x h-9 w-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 grid place-items-center shrink-0">
            <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        <div class="p-5">
          ${note ? `<div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-xs text-slate-600 mb-4">${note}</div>` : ''}
          <div class="flex justify-end gap-2">
            <button type="button" class="btn-cancel h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-semibold">${cancelText}</button>
            <button type="button" class="btn-ok h-10 px-5 rounded-xl ${btnCls} text-white text-sm font-semibold">${confirmText}</button>
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

  // ── Batal ─────────────────────────────────────────────────────────────────
  btnBatal?.addEventListener('click', e => {
    e.preventDefault();
    const go     = btnBatal.getAttribute('href');
    const chosen = checks.filter(c => c.checked).length;

    if (chosen === 0) { window.location.href = go; return; }

    showConfirm({
      tone: 'neutral',
      title: 'Batalkan proses hapus?',
      message: 'Pilihan yang sudah kamu centang akan hilang.',
      note: 'Kalau masih ragu, klik "Tetap di sini".',
      confirmText: 'Ya, Keluar',
      cancelText: 'Tetap di sini',
      onConfirm: () => window.location.href = go
    });
  });

  // ── Hapus yang dipilih ────────────────────────────────────────────────────
  btnDeleteSelected?.addEventListener('click', () => {
    const chosen = checks.filter(c => c.checked).length;
    if (chosen === 0) return;

    showConfirm({
      tone: 'danger',
      title: `Hapus ${chosen} item yang dipilih?`,
      message: 'Item yang dihapus tidak bisa dikembalikan.',
      note: 'Cek lagi daftar yang kamu centang. Kalau sudah yakin, lanjutkan.',
      confirmText: 'Ya, Hapus',
      cancelText: 'Batal',
      onConfirm: () => {
        formDeleteSelected.dataset.confirmed = '1';
        formDeleteSelected.submit();
      }
    });
  });

  // ── Hapus semua ───────────────────────────────────────────────────────────
  btnDeleteAll?.addEventListener('click', () => {
    showConfirm({
      tone: 'danger',
      title: 'Hapus SEMUA di tanggal ini?',
      message: 'Semua jadwal pada tanggal ini akan dihapus permanen.',
      note: 'Ini termasuk semua shift, catatan, dan status tutup yang ada.',
      confirmText: 'Ya, Hapus Semua',
      cancelText: 'Batal',
      onConfirm: () => formDeleteAll.submit()
    });
  });
</script>
@endpush
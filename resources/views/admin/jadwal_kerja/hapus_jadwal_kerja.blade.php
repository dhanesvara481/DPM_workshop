{{-- resources/views/admin/jadwal_kerja/hapus_jadwal_kerja.blade.php --}}
@extends('admin.layout.app')

@section('title', 'DPM Workshop - Admin')

@section('content')

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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Hapus Jadwal Kerja</h1>
        <p class="text-xs text-slate-500">Pilih item yang ingin dihapus (bisa 1, beberapa, atau semua).</p>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('kelola_jadwal_kerja') }}"
         class="inline-flex items-center justify-center rounded-xl px-3 py-2 text-sm font-semibold
                border border-slate-200 bg-white hover:bg-slate-50 transition">
        Kembali
      </a>

      <button type="button"
              class="tip h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
              data-tip="Notifikasi">
        <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
        </svg>
      </button>
    </div>
  </div>
</header>

<section class="relative p-4 sm:p-6">
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
    <div class="absolute inset-0 opacity-[0.12]"
         style="background-image:
          linear-gradient(to right, rgba(2,6,23,0.06) 1px, transparent 1px),
          linear-gradient(to bottom, rgba(2,6,23,0.06) 1px, transparent 1px);
          background-size: 56px 56px;">
    </div>
    <div class="absolute inset-0 opacity-[0.20] mix-blend-screen animate-grid-scan"
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

  <div class="max-w-[980px] mx-auto w-full">

    @php
      $date = $date ?? request('date') ?? now()->format('Y-m-d');

      $statusMap = [
        'aktif'   => ['label' => 'Aktif',   'pill' => 'pill aktif'],
        'catatan' => ['label' => 'Catatan', 'pill' => 'pill catatan'],
        'tutup'   => ['label' => 'Tutup',   'pill' => 'pill tutup'],
      ];

      // Normalisasi dari controller
      $items = collect($jadwalKerjas ?? [])->map(fn($j) => [
        'id'     => $j->jadwal_id,
        'type'   => 'event',
        'title'  => ($j->waktu_shift ?? 'Jadwal') . ' - ' . ($j->user->username ?? 'Staf'),
        'status' => strtolower($j->status),
        'time'   => substr($j->jam_mulai, 0, 5) . ' - ' . substr($j->jam_selesai, 0, 5),
        'desc'   => $j->deskripsi,
      ])->toArray();

      $fmtLong = function($iso) {
        try {
          return \Carbon\Carbon::parse($iso)->locale('id')->translatedFormat('l, d F Y');
        } catch(\Throwable $e) { return $iso; }
      };
    @endphp

    <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200 shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">
      <div class="px-5 sm:px-6 py-5 border-b border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
          <div class="min-w-0">
            <div class="text-lg sm:text-xl font-semibold tracking-tight text-slate-900">Konfirmasi Hapus</div>
            <div class="text-xs text-slate-500 mt-1">
              Tanggal: <span class="font-semibold text-slate-900">{{ $fmtLong($date) }}</span>
              <span class="mx-2 text-slate-300">•</span>
              <span class="font-semibold text-rose-700">Aksi ini tidak bisa dibatalkan</span>.
            </div>
          </div>

          <div class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2">
            <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
            <span class="text-xs font-semibold text-rose-800">Peringatan!</span>
          </div>
        </div>
      </div>

      <div class="p-5 sm:p-6">
        <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
          <div class="px-5 py-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="min-w-0">
              <div class="text-sm font-semibold text-slate-900">Pilih yang mau dihapus</div>
              <div class="text-[11px] text-slate-500">
                Kamu bisa hapus <b>1 item</b>, <b>beberapa item</b>, atau <b>semua</b> pada tanggal ini.
              </div>
            </div>

            <div class="flex items-center gap-2">
              <label class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 select-none">
                <input id="checkAll" type="checkbox" class="h-4 w-4 rounded border-slate-300">
                Pilih semua
              </label>

              <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                <span class="text-[11px] text-slate-600">Terpilih:</span>
                <span id="selectedCount" class="text-xs font-semibold text-slate-900">0</span>
              </div>
            </div>
          </div>

          <form id="formDeleteSelected"
                method="POST"
                action="{{ route('hapus_jadwal_kerja_batch') }}"
                class="p-5 space-y-3">
            @csrf
            @method('DELETE')
            <input type="hidden" name="date" value="{{ $date }}">

            @forelse($items as $it)
              @php
                $s  = strtolower(trim($it['status'] ?? 'aktif'));
                $ui = $statusMap[$s] ?? ['label' => $it['status'] ?? '—', 'pill' => 'pill'];
              @endphp

              <label class="block cursor-pointer">
                <div class="rounded-2xl border border-slate-200 bg-white p-4 hover:bg-slate-50/50 transition flex items-start gap-3">
                  <input type="checkbox" class="itemCheck mt-1 h-4 w-4 rounded border-slate-300"
                         name="targets[]" value="event:{{ $it['id'] }}">
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
                      <div class="text-xs text-slate-600 mt-2">{{ $it['desc'] }}</div>
                    @endif
                  </div>
                </div>
              </label>
            @empty
              <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                Tidak ada jadwal di tanggal ini.
              </div>
            @endforelse

            <div class="pt-3 flex flex-col sm:flex-row gap-2 sm:justify-end">
              <a id="btnBatal" href="{{ route('kelola_jadwal_kerja') }}"
                 class="h-11 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold inline-flex items-center justify-center">
                Batal
              </a>

              <button id="btnDeleteSelected" type="submit" disabled
                      class="h-11 px-5 rounded-xl bg-rose-600 text-white hover:bg-rose-700 transition text-sm font-semibold
                             shadow-[0_12px_24px_rgba(244,63,94,0.25)] disabled:opacity-50 disabled:cursor-not-allowed">
                Hapus yang dipilih
              </button>

              <button id="btnDeleteAll" type="button"
                      class="h-11 px-5 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 transition text-sm font-semibold">
                Hapus semua di tanggal ini
              </button>
            </div>

            <div class="text-[11px] text-slate-500">
              Tips: centang item yang mau dihapus, lalu klik <b>Hapus yang dipilih</b>.
            </div>
          </form>

          <form id="formDeleteAll" action="{{ route('hapus_jadwal_kerja_all') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
            <input type="hidden" name="date" value="{{ $date }}">
          </form>
        </div>

        <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 p-4 sm:p-5">
          <div class="text-sm font-semibold text-rose-900">Catatan</div>
          <div class="text-[11px] text-rose-800/80 mt-1">
            Kalau salah hapus, harus buat ulang jadwalnya. Pastikan item yang dipilih sudah benar.
          </div>
        </div>
      </div>

      <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
        Tips: halaman ini dibuka dari detail tanggal (modal) → tombol "Hapus".
      </div>
    </div>
  </div>
</section>

@endsection

@push('head')
<style>
  @media (prefers-reduced-motion: reduce) { .animate-grid-scan { animation: none !important; transition: none !important; } }
  @keyframes gridScan {
    0%{ background-position:0 0,0 0; opacity:.10; }
    40%{ opacity:.22; }
    60%{ opacity:.18; }
    100%{ background-position:220px 220px,-260px 260px; opacity:.10; }
  }
  .animate-grid-scan{ animation:gridScan 8.5s ease-in-out infinite; }

  .tip{ position:relative; }
  .tip[data-tip]::after{
    content:attr(data-tip);
    position:absolute; right:0; top:calc(100% + 10px);
    background:rgba(15,23,42,.92); color:rgba(255,255,255,.92);
    font-size:11px; padding:6px 10px; border-radius:10px;
    white-space:nowrap; opacity:0; transform:translateY(-4px);
    pointer-events:none; transition:.15s ease;
  }
  .tip:hover::after{ opacity:1; transform:translateY(0); }

  .pill{
    display:inline-flex; align-items:center; gap:8px;
    font-size:11px; padding:6px 10px; border-radius:12px;
    border:1px solid rgba(15,23,42,0.10); background:rgba(255,255,255,0.75);
    white-space:nowrap;
  }
  .pill.aktif  { background:rgba(16,185,129,0.12); border-color:rgba(16,185,129,0.25); color:rgba(6,95,70,0.95); }
  .pill.catatan{ background:rgba(245,158,11,0.12); border-color:rgba(245,158,11,0.25); color:rgba(120,53,15,0.95); }
  .pill.tutup  { background:rgba(244,63,94,0.12);  border-color:rgba(244,63,94,0.25);  color:rgba(136,19,55,0.95); }
</style>
@endpush

@push('scripts')
<script>
  function showConfirmModal({ tone = "neutral", title, message, note, confirmText, cancelText, onConfirm }) {
    const toneMap = {
      neutral: { btn: "bg-slate-900 hover:bg-slate-800", noteBg: "bg-slate-50", noteBr: "border-slate-200", noteTx: "text-slate-600" },
      danger:  { btn: "bg-rose-600 hover:bg-rose-700",  noteBg: "bg-rose-50",  noteBr: "border-rose-200",  noteTx: "text-rose-700" },
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
            ${note || 'Pastikan pilihan kamu sudah benar.'}
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

  const checkAll          = document.getElementById('checkAll');
  const checks            = Array.from(document.querySelectorAll('.itemCheck'));
  const selectedCount     = document.getElementById('selectedCount');
  const btnDeleteSelected = document.getElementById('btnDeleteSelected');
  const formDeleteSelected= document.getElementById('formDeleteSelected');
  const btnDeleteAll      = document.getElementById('btnDeleteAll');
  const formDeleteAll     = document.getElementById('formDeleteAll');
  const btnBatal          = document.getElementById('btnBatal');

  function refresh(){
    const chosen = checks.filter(c => c.checked).length;
    selectedCount.textContent = String(chosen);
    btnDeleteSelected.disabled = chosen === 0;

    if (checks.length > 0) {
      const allChecked  = chosen === checks.length;
      const noneChecked = chosen === 0;
      checkAll.indeterminate = (!allChecked && !noneChecked);
      checkAll.checked = allChecked;
    } else {
      checkAll.checked = false;
      checkAll.indeterminate = false;
    }
  }

  checkAll?.addEventListener('change', (e) => {
    checks.forEach(c => c.checked = e.target.checked);
    refresh();
  });

  checks.forEach(c => c.addEventListener('change', refresh));
  refresh();

  // ===== Batal =====
  btnBatal?.addEventListener('click', (e) => {
    e.preventDefault();
    const go = btnBatal.getAttribute('href');
    const chosen = checks.filter(c => c.checked).length;

    if (chosen === 0) { window.location.href = go; return; }

    showConfirmModal({
      tone: "neutral",
      title: "Batalkan proses hapus?",
      message: "Pilihan item yang sudah kamu centang akan hilang kalau kamu keluar sekarang.",
      note: "Kalau masih ragu, klik \"Tetap di sini\".",
      confirmText: "Ya, Keluar",
      cancelText: "Tetap di sini",
      onConfirm: () => window.location.href = go
    });
  });

  // ===== Hapus yang dipilih =====
  formDeleteSelected?.addEventListener('submit', (e) => {
    const chosen = checks.filter(c => c.checked).length;
    if (chosen === 0) { e.preventDefault(); return; }
    if (formDeleteSelected.dataset.confirmed === "1") return;

    e.preventDefault();
    showConfirmModal({
      tone: "danger",
      title: `Hapus ${chosen} item yang dipilih?`,
      message: "Item yang dihapus tidak bisa dikembalikan.",
      note: "Cek lagi daftar yang kamu centang. Kalau sudah yakin, lanjutkan hapus.",
      confirmText: "Ya, Hapus",
      cancelText: "Batal",
      onConfirm: () => {
        formDeleteSelected.dataset.confirmed = "1";
        formDeleteSelected.submit();
      }
    });
  });

  // ===== Hapus semua =====
  btnDeleteAll?.addEventListener('click', () => {
    showConfirmModal({
      tone: "danger",
      title: "Hapus SEMUA di tanggal ini?",
      message: "Semua jadwal pada tanggal ini akan dihapus permanen.",
      note: "Ini termasuk semua shift/catatan/tutup (kalau ada). Pastikan kamu benar-benar yakin.",
      confirmText: "Ya, Hapus Semua",
      cancelText: "Batal",
      onConfirm: () => formDeleteAll.submit()
    });
  });
</script>
@endpush
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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Kelola Jadwal Kerja</h1>
        <p class="text-xs text-slate-500">Kalender bulanan untuk mengatur jadwal.</p>
      </div>
    </div>

    <div class="flex items-center gap-2">
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
  {{-- BACKGROUND --}}
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

  <div class="max-w-[1280px] mx-auto w-full">

    @php
      $MAX_EVENTS_PER_DAY = $MAX_EVENTS_PER_DAY ?? 4;

      // ===== DUMMY DATA (hapus kalau backend sudah siap) =====
      $events = $events ?? [
        now()->format('Y-m-d') => [
          ['id'=>101, 'title'=>'Shift Pagi - Asep', 'status'=>'aktif', 'time'=>'08:00 - 16:00', 'desc'=>'Servis rutin / tune up'],
          ['id'=>102, 'title'=>'Catatan: Sparepart datang', 'status'=>'catatan', 'time'=>'10:30', 'desc'=>'Cek gudang + follow up supplier'],
        ],
        now()->addDay()->format('Y-m-d') => [
          ['id'=>103, 'title'=>'Tutup (Libur)', 'status'=>'tutup', 'time'=>'-', 'desc'=>'Hari libur operasional'],
        //   // kalau ada data lain di tanggal ini, UI akan "anggap" tutup dan hanya tampilkan event tutup saja
        //   ['id'=>104, 'title'=>'Shift Siang - Budi', 'status'=>'aktif', 'time'=>'12:00 - 16:00', 'desc'=>'—'],
        //   ['id'=>105, 'title'=>'Shift Sore - Ujang', 'status'=>'aktif', 'time'=>'16:00 - 20:00', 'desc'=>'—'],
        ],
      ];
    @endphp

    <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

      {{-- header kalender --}}
      <div class="px-5 sm:px-6 py-5 border-b border-slate-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
          <div class="min-w-0">
            <div id="monthTitle" class="text-xl sm:text-2xl font-semibold tracking-tight text-slate-900">—</div>
            <div class="text-xs text-slate-500 mt-1">
              Klik tanggal untuk lihat detail. Tambah jadwal nonaktif kalau sudah penuh.
            </div>
          </div>

          <div class="flex flex-col sm:flex-row sm:items-center gap-2">
            <div class="flex items-center gap-2">
              <button id="btnToday" type="button"
                      class="h-10 px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                Today
              </button>

              <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-white">
                <button id="btnPrev" type="button"
                        class="h-10 w-10 grid place-items-center hover:bg-slate-50 transition"
                        aria-label="Prev">
                  <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                  </svg>
                </button>
                <button id="btnNext" type="button"
                        class="h-10 w-10 grid place-items-center hover:bg-slate-50 transition border-l border-slate-200"
                        aria-label="Next">
                  <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                  </svg>
                </button>
              </div>
            </div>

            <div class="hidden sm:block w-px h-10 bg-slate-200 mx-1"></div>

            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-slate-600">
              <span class="inline-flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Aktif
              </span>
              <span class="inline-flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span> Tutup
              </span>
              <span class="inline-flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span> Catatan
              </span>
            </div>
          </div>
        </div>
      </div>

      {{-- kalender --}}
      <div class="p-3 sm:p-4">
        <div class="overflow-x-auto">
          <div class="min-w-[980px]">
            <div class="grid grid-cols-7 gap-2 px-1 pb-2 text-[12px] font-semibold text-slate-600">
              <div class="px-2">Minggu</div>
              <div class="px-2">Senin</div>
              <div class="px-2">Selasa</div>
              <div class="px-2">Rabu</div>
              <div class="px-2">Kamis</div>
              <div class="px-2">Jumat</div>
              <div class="px-2">Sabtu</div>
            </div>

            <div id="calendarGrid" class="grid grid-cols-7 gap-2"></div>
          </div>
        </div>
      </div>

      <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
        © DPM Workshop 2025
      </div>
    </div>

    {{-- ✅ MODAL DETAIL --}}
    <div id="detailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
      <div id="detailOverlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

      <div class="relative min-h-full w-full flex items-center justify-center p-3 sm:p-6">
        <div class="w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-[0_30px_90px_rgba(2,6,23,0.30)]
                    overflow-hidden flex flex-col h-[92vh] sm:h-[86vh]">

          {{-- header --}}
          <div class="px-5 py-4 border-b border-slate-200 flex items-start justify-between gap-3 bg-white shrink-0">
            <div class="min-w-0">
              <div class="text-sm font-semibold text-slate-900">Detail Jadwal</div>
              <div id="modalDate" class="text-xs text-slate-500 mt-0.5">—</div>
            </div>
            <button id="btnCloseModal" type="button"
                    class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                    aria-label="Tutup">
              <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          {{-- body --}}
          <div class="p-5 flex-1 min-h-0 overflow-hidden flex flex-col gap-4">
            {{-- meta --}}
            <div id="modalMeta" class="shrink-0"></div>

            {{-- list --}}
            <div class="rounded-xl border border-slate-200 bg-slate-50 flex flex-col min-h-0 flex-1">
              <div class="px-4 py-3 border-b border-slate-200 bg-white rounded-t-xl shrink-0">
                <div class="text-sm font-semibold text-slate-900">Daftar Jadwal</div>
                <div class="text-xs text-slate-500">Scroll kalau jadwalnya panjang.</div>
              </div>

              <div id="modalListScroll" class="flex-1 min-h-0 overflow-y-auto overscroll-contain p-3 sm:p-4">
                <div id="modalEvents" class="space-y-2"></div>

                <div id="modalEmpty" class="hidden rounded-xl border border-slate-200 bg-white p-4 text-sm text-slate-600">
                  Belum ada jadwal di tanggal ini.
                </div>
              </div>
            </div>

            {{-- footer --}}
            <div class="pt-3 border-t border-slate-200 flex flex-col sm:flex-row gap-2 sm:justify-end shrink-0">
              <a id="modalTambah"
                 href="#"
                 class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                        bg-slate-900 text-white hover:bg-slate-800 transition">
                Tambah Jadwal
              </a>
              <a id="modalUbah"
                 href="#"
                 class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                        border border-slate-200 bg-white hover:bg-slate-50 transition">
                Ubah
              </a>
              <a id="modalHapus"
                 href="#"
                 class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                        border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 transition">
                Hapus
              </a>
            </div>

            <div id="modalHint" class="text-[11px] text-slate-500 shrink-0"></div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

@endsection

@push('head')
<style>
  @media (prefers-reduced-motion: reduce) { .animate-grid-scan { animation: none !important; transition: none !important; } }

  @keyframes gridScan {
    0%   { background-position: 0 0, 0 0; opacity: 0.10; }
    40%  { opacity: 0.22; }
    60%  { opacity: 0.18; }
    100% { background-position: 220px 220px, -260px 260px; opacity: 0.10; }
  }
  .animate-grid-scan { animation: gridScan 8.5s ease-in-out infinite; }

  .day-card{
    border: 1px solid rgba(15,23,42,0.10);
    background: rgba(255,255,255,0.92);
    border-radius: 18px;
    min-height: 132px;
    overflow: hidden;
    transition: .15s ease;
  }
  .day-card:hover{
    border-color: rgba(2,6,23,0.18);
    box-shadow: 0 14px 34px rgba(2,6,23,0.10);
    transform: translateY(-1px);
  }
  .day-muted{ opacity:.45; background: rgba(248,250,252,0.85); }

  .day-top{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding: 10px 12px 6px 12px;
  }

  .day-num{
    width: 32px;
    height: 32px;
    display: grid;
    place-items: center;
    border-radius: 999px;
    font-weight: 800;
    font-size: 13px;
    color: rgba(15,23,42,0.92);
  }
  .day-num.today{ background: rgba(2,6,23,0.92); color:#fff; }

  .pill{
    display:inline-flex;
    align-items:center;
    gap:8px;
    font-size: 11px;
    padding: 6px 10px;
    border-radius: 12px;
    border: 1px solid rgba(15,23,42,0.10);
    background: rgba(255,255,255,0.75);
    white-space: nowrap;
    overflow:hidden;
    text-overflow: ellipsis;
    max-width: 100%;
  }
  .pill.aktif   { background: rgba(16,185,129,0.12); border-color: rgba(16,185,129,0.25); color: rgba(6,95,70,0.95); }
  .pill.catatan { background: rgba(245,158,11,0.12); border-color: rgba(245,158,11,0.25); color: rgba(120,53,15,0.95); }
  .pill.tutup   { background: rgba(244,63,94,0.12); border-color: rgba(244,63,94,0.25); color: rgba(136,19,55,0.95); }

  .day-body{ padding: 8px 12px 12px 12px; display:flex; flex-direction:column; gap:6px; }
  .has-data{ outline: 2px solid rgba(2,6,23,0.10); }

  .badge-full{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-size: 10px;
    font-weight: 800;
    padding: 4px 8px;
    border-radius: 999px;
    border: 1px solid rgba(244,63,94,0.25);
    background: rgba(244,63,94,0.10);
    color: rgba(190,18,60,0.95);
    white-space: nowrap;
  }

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

  .btn-disabled{ opacity:.5 !important; pointer-events:none !important; }

  #modalListScroll { scrollbar-gutter: stable; }
</style>
@endpush

@push('scripts')
<script>
  const MAX_EVENTS_PER_DAY = @json($MAX_EVENTS_PER_DAY);

  const monthTitle = document.getElementById('monthTitle');
  const grid = document.getElementById('calendarGrid');
  const btnPrev = document.getElementById('btnPrev');
  const btnNext = document.getElementById('btnNext');
  const btnToday = document.getElementById('btnToday');

  const detailModal = document.getElementById('detailModal');
  const detailOverlay = document.getElementById('detailOverlay');
  const btnCloseModal = document.getElementById('btnCloseModal');

  const modalDate = document.getElementById('modalDate');
  const modalMeta = document.getElementById('modalMeta');
  const modalEvents = document.getElementById('modalEvents');
  const modalEmpty = document.getElementById('modalEmpty');
  const modalHint = document.getElementById('modalHint');

  const modalTambah = document.getElementById('modalTambah');
  const modalUbah = document.getElementById('modalUbah');
  const modalHapus = document.getElementById('modalHapus');

  const EVENTS = @json($events);

  const pad2 = (n) => String(n).padStart(2, '0');
  const ymd = (d) => `${d.getFullYear()}-${pad2(d.getMonth()+1)}-${pad2(d.getDate())}`;
  const sameDay = (a,b) => a.getFullYear()===b.getFullYear() && a.getMonth()===b.getMonth() && a.getDate()===b.getDate();

  const fmtMonth = (d) => d.toLocaleDateString('id-ID', { month:'long', year:'numeric' });
  const fmtLong = (iso) => {
    try {
      const [y,m,dd] = iso.split('-').map(Number);
      const obj = new Date(y, m-1, dd);
      return obj.toLocaleDateString('id-ID', { weekday:'long', day:'2-digit', month:'long', year:'numeric' });
    } catch(e) { return iso; }
  };

  const getEvents = (dateStr) => (EVENTS?.[dateStr] || []);

  // ✅ RULE: kalau ada status 'tutup' di tanggal tsb, hari dianggap TUTUP
  const isClosedDay = (dateStr) => {
    const list = getEvents(dateStr);
    return list.some(e => String(e?.status || '').toLowerCase() === 'tutup');
  };

  // ✅ kalau TUTUP, UI cuma tampilkan event tutup saja (yang lain diabaikan)
  const getVisibleEvents = (dateStr) => {
    const all = getEvents(dateStr);
    if (!isClosedDay(dateStr)) return all;
    return all.filter(e => String(e?.status || '').toLowerCase() === 'tutup');
  };

  const usedCount = (dateStr) => {
    if (isClosedDay(dateStr)) return 0; // dianggap N/A
    return getVisibleEvents(dateStr).length;
  };

  const remainingQuota = (dateStr) => {
    if (isClosedDay(dateStr)) return 0;
    return Math.max(0, MAX_EVENTS_PER_DAY - usedCount(dateStr));
  };

  // ✅ FULL hanya berlaku kalau tidak tutup
  const isFull = (dateStr) => {
    if (isClosedDay(dateStr)) return true; // tutup = tidak bisa tambah
    return remainingQuota(dateStr) <= 0;
  };

  let current = new Date();
  current.setDate(1);

  function showModal(dateStr){
    const closed = isClosedDay(dateStr);
    const ev = getVisibleEvents(dateStr);

    const used = usedCount(dateStr);
    const left = remainingQuota(dateStr);

    modalDate.textContent = fmtLong(dateStr);

    // ✅ META: kalau tutup => jadwal terpakai "- / -" + label "Tutup"
    modalMeta.innerHTML = `
      <div class="rounded-xl border border-slate-200 bg-white p-4">
        <div class="flex items-center justify-between gap-3">
          <div class="text-sm font-semibold text-slate-900">Batas & Sisa</div>
          <span class="text-[11px] ${closed ? 'text-rose-600' : 'text-slate-500'}">
            ${closed ? 'Tutup' : `Maks ${MAX_EVENTS_PER_DAY} jadwal/hari`}
          </span>
        </div>

        <div class="mt-3">
          <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
            <div class="text-[11px] text-slate-500">Jadwal terpakai</div>
            <div class="font-semibold text-slate-900">
              ${closed ? '- / -' : `${used} / ${MAX_EVENTS_PER_DAY}`}
            </div>
          </div>
        </div>
      </div>
    `;

    // LIST events (kalau tutup -> cuma tampil tutup)
    modalEvents.innerHTML = '';
    if (ev.length > 0) {
      ev.forEach((e) => {
        const status = (e.status || 'aktif');
        const time = e.time ? `<div class="text-xs text-slate-500 mt-0.5">${e.time}</div>` : '';
        const desc = e.desc ? `<div class="text-xs text-slate-600 mt-1">${e.desc}</div>` : '';
        modalEvents.innerHTML += `
          <div class="rounded-xl border border-slate-200 bg-white p-4">
            <div class="flex items-start justify-between gap-3">
              <div class="min-w-0">
                <div class="text-sm font-semibold text-slate-900 truncate">${e.title || 'Jadwal'}</div>
                ${time}
              </div>
              <span class="pill ${status}">${String(status).toUpperCase()}</span>
            </div>
            ${desc}
          </div>
        `;
      });
      modalEmpty.classList.add('hidden');
    } else {
      modalEmpty.classList.remove('hidden');
    }

    // ✅ hasData pakai data asli (biar ubah/hapus masih bisa kalau ada apa pun, termasuk tutup)
    const hasData = (getEvents(dateStr).length > 0);

    modalUbah.href  = hasData ? "{{ route('ubah_jadwal_kerja') }}?date=" + encodeURIComponent(dateStr) : '#';
    modalHapus.href = hasData ? "{{ route('hapus_jadwal_kerja') }}?date=" + encodeURIComponent(dateStr) : '#';

    modalUbah.classList.toggle('opacity-50', !hasData);
    modalUbah.classList.toggle('pointer-events-none', !hasData);
    modalHapus.classList.toggle('opacity-50', !hasData);
    modalHapus.classList.toggle('pointer-events-none', !hasData);

    // ✅ Tambah: kalau tutup -> disable + button jadi "TUTUP"
    if (!closed && !isFull(dateStr)) {
      modalTambah.href = "{{ route('tambah_jadwal_kerja') }}?date=" + encodeURIComponent(dateStr);
      modalTambah.classList.remove('btn-disabled');
      modalTambah.textContent = `Tambah Jadwal (sisa ${left})`;
      modalHint.textContent = `Masih bisa tambah ${left} jadwal lagi di tanggal ini.`;
    } else {
      modalTambah.href = '#';
      modalTambah.classList.add('btn-disabled');
      modalTambah.textContent = closed ? `TUTUP` : `Sudah FULL (${MAX_EVENTS_PER_DAY}/${MAX_EVENTS_PER_DAY})`;
      modalHint.textContent = closed
        ? `Hari ini TUTUP. Tidak bisa menambah jadwal.`
        : `Tidak bisa tambah jadwal karena sudah mencapai batas ${MAX_EVENTS_PER_DAY} jadwal per hari.`;
    }

    detailModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  }

  function hideModal(){
    detailModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  }

  detailOverlay?.addEventListener('click', hideModal);
  btnCloseModal?.addEventListener('click', hideModal);
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') hideModal(); });

  function render() {
    grid.innerHTML = '';
    monthTitle.textContent = fmtMonth(current);

    const today = new Date();
    const year = current.getFullYear();
    const month = current.getMonth();

    const first = new Date(year, month, 1);
    const startDay = first.getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // kosong awal
    for (let i = 0; i < startDay; i++) {
      const empty = document.createElement('div');
      empty.className = 'day-card day-muted';
      empty.innerHTML = `<div class="day-top"><div class="day-num"></div></div>`;
      grid.appendChild(empty);
    }

    for (let day = 1; day <= daysInMonth; day++) {
      const dateObj = new Date(year, month, day);
      const key = ymd(dateObj);
      const isToday = sameDay(dateObj, today);

      const closed = isClosedDay(key);

      // ✅ kalender card: kalau tutup, tampilkan cuma pill tutup
      const ev = getVisibleEvents(key);
      const hasData = (getEvents(key).length > 0);

      const left = remainingQuota(key);
      const full = (!closed && (left <= 0) && hasData); // FULL badge cuma buat hari normal

      const card = document.createElement('button');
      card.type = 'button';
      card.className = `day-card text-left ${hasData ? 'has-data' : ''}`;
      card.dataset.date = key;

      const top = document.createElement('div');
      top.className = 'day-top';

      const num = document.createElement('div');
      num.className = `day-num ${isToday ? 'today' : ''}`;
      num.textContent = String(day);

      const right = document.createElement('div');
      right.className = 'flex items-center gap-2';

      if (full) {
        const badge = document.createElement('div');
        badge.className = 'badge-full';
        badge.textContent = 'FULL';
        right.appendChild(badge);
      }

      top.appendChild(num);
      top.appendChild(right);

      const body = document.createElement('div');
      body.className = 'day-body';

      const take = ev.slice(0, 3);
      take.forEach(e => {
        const pill = document.createElement('div');
        const status = (e.status || 'aktif');
        pill.className = `pill ${status}`;
        pill.title = e.title || '';
        pill.textContent = e.title || 'Jadwal';
        body.appendChild(pill);
      });

      if (!closed && ev.length > 3) {
        const more = document.createElement('div');
        more.className = 'text-[11px] text-slate-500';
        more.textContent = `+${ev.length - 3} lainnya`;
        body.appendChild(more);
      }

      if (!hasData) {
        const hint = document.createElement('div');
        hint.className = 'text-[11px] text-slate-500/80';
        hint.textContent = '—';
        body.appendChild(hint);
      } else {
        const info = document.createElement('div');
        info.className = 'text-[11px] text-slate-600';
        info.textContent = closed ? `TUTUP` : `Sisa tambah: ${left}`;
        body.appendChild(info);
      }

      card.appendChild(top);
      card.appendChild(body);

      card.addEventListener('click', () => showModal(key));
      grid.appendChild(card);
    }

    // kosong akhir
    const totalCells = startDay + daysInMonth;
    const remaining = (7 - (totalCells % 7)) % 7;
    for (let i = 0; i < remaining; i++) {
      const empty = document.createElement('div');
      empty.className = 'day-card day-muted';
      empty.setAttribute('aria-hidden', 'true');
      empty.innerHTML = `<div class="day-top"><div class="day-num"></div></div>`;
      grid.appendChild(empty);
    }
  }

  btnPrev?.addEventListener('click', () => {
    current = new Date(current.getFullYear(), current.getMonth()-1, 1);
    render();
  });
  btnNext?.addEventListener('click', () => {
    current = new Date(current.getFullYear(), current.getMonth()+1, 1);
    render();
  });
  btnToday?.addEventListener('click', () => {
    const t = new Date();
    current = new Date(t.getFullYear(), t.getMonth(), 1);
    render();
    showModal(ymd(t));
  });

  render();
</script>
@endpush

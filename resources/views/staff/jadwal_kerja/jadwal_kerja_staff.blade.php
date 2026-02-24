{{-- resources/views/staff/jadwal/jadwal_kerja.blade.php --}}
@extends('staff.layout.app')

@section('page_title', 'Jadwal Kerja')
@section('page_subtitle', 'Kalender bulanan (view-only)')

@section('content')

<section class="relative p-0 sm:p-0">
  <div class="max-w-[1280px] mx-auto w-full">

    @php
      $MAX_EVENTS_PER_DAY = $MAX_EVENTS_PER_DAY ?? 4;

      $events = $events ?? [
        now()->format('Y-m-d') => [
          ['id'=>101, 'title'=>'Shift Pagi - Asep', 'status'=>'aktif', 'time'=>'08:00 - 16:00', 'desc'=>'Servis rutin / tune up'],
          ['id'=>102, 'title'=>'Catatan: Sparepart datang', 'status'=>'catatan', 'time'=>'10:30', 'desc'=>'Cek gudang + follow up supplier'],
        ],
        now()->addDay()->format('Y-m-d') => [
          ['id'=>103, 'title'=>'Tutup (Libur)', 'status'=>'tutup', 'time'=>'-', 'desc'=>'Hari libur operasional'],
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
              Klik tanggal untuk lihat detail jadwal.
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

  #modalListScroll { scrollbar-gutter: stable; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

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

  const isClosedDay = (dateStr) => {
    const list = getEvents(dateStr);
    return list.some(e => String(e?.status || '').toLowerCase() === 'tutup');
  };

  const getVisibleEvents = (dateStr) => {
    const all = getEvents(dateStr);
    if (!isClosedDay(dateStr)) return all;
    return all.filter(e => String(e?.status || '').toLowerCase() === 'tutup');
  };

  const usedCount = (dateStr) => {
    if (isClosedDay(dateStr)) return 0;
    return getVisibleEvents(dateStr).length;
  };

  const remainingQuota = (dateStr) => {
    if (isClosedDay(dateStr)) return 0;
    return Math.max(0, MAX_EVENTS_PER_DAY - usedCount(dateStr));
  };

  let current = new Date();
  current.setDate(1);

  function showModal(dateStr){
    if (!detailModal) return; // guard

    const closed = isClosedDay(dateStr);
    const ev = getVisibleEvents(dateStr);
    const used = usedCount(dateStr);

    modalDate.textContent = fmtLong(dateStr);

    modalMeta.innerHTML = `
      <div class="rounded-xl border border-slate-200 bg-white p-4">
        <div class="flex items-center justify-between gap-3">
          <div class="text-sm font-semibold text-slate-900">Info Hari</div>
          <span class="text-[11px] ${closed ? 'text-rose-600' : 'text-slate-500'}">
            ${closed ? 'Tutup' : `Maks ${MAX_EVENTS_PER_DAY} jadwal/hari`}
          </span>
        </div>
        <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
          <div class="text-[11px] text-slate-500">Jadwal terpakai</div>
          <div class="font-semibold text-slate-900">
            ${closed ? '- / -' : `${used} / ${MAX_EVENTS_PER_DAY}`}
          </div>
        </div>
      </div>
    `;

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

    detailModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  }

  function hideModal(){
    if (!detailModal) return;
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
      const ev = getVisibleEvents(key);
      const hasData = (getEvents(key).length > 0);
      const left = remainingQuota(key);

      const card = document.createElement('button');
      card.type = 'button';
      card.className = `day-card text-left ${hasData ? 'has-data' : ''}`;
      card.dataset.date = key;

      const top = document.createElement('div');
      top.className = 'day-top';

      const num = document.createElement('div');
      num.className = `day-num ${isToday ? 'today' : ''}`;
      num.textContent = String(day);

      top.appendChild(num);
      top.appendChild(document.createElement('div'));

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
        info.textContent = closed ? `TUTUP` : `Sisa: ${left}`;
        body.appendChild(info);
      }

      card.appendChild(top);
      card.appendChild(body);

      card.addEventListener('click', () => showModal(key));
      grid.appendChild(card);
    }

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
});
</script>
@endpush

{{-- ✅ PINDAHIN MODAL KE STACK MODALS (DI LUAR SIDEBAR/TOPBAR) --}}
@push('modals')
  <div id="detailModal" class="fixed inset-0 z-[9999] hidden">
    <div id="detailOverlay" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

    <div class="relative min-h-full w-full flex items-center justify-center p-3 sm:p-6">
      <div class="w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-[0_30px_90px_rgba(2,6,23,0.30)]
                  overflow-hidden flex flex-col h-[92vh] sm:h-[86vh]">

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

        <div class="p-5 flex-1 min-h-0 overflow-hidden flex flex-col gap-4">
          <div id="modalMeta" class="shrink-0"></div>

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

          <div class="pt-3 border-t border-slate-200 text-[11px] text-slate-500 shrink-0">
            Mode: <span class="font-semibold text-slate-700">View-only</span> (staf tidak bisa menambah/ubah/hapus jadwal).
          </div>
        </div>

      </div>
    </div>
  </div>
@endpush
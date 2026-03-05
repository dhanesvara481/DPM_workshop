{{-- resources/views/admin/jadwal_kerja/tambah_jadwal_kerja.blade.php --}}
@extends('admin.layout.app')

@section('title', 'DPM Workshop - Tambah Jadwal')

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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Tambah Jadwal Kerja</h1>
        <p class="text-xs text-slate-500">Pilih minggu → centang hari → isi agenda (maks 4/hari).</p>
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
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
    <div class="absolute inset-0 opacity-[0.10]"
         style="background-image:linear-gradient(to right,rgba(2,6,23,.06) 1px,transparent 1px),linear-gradient(to bottom,rgba(2,6,23,.06) 1px,transparent 1px);background-size:56px 56px"></div>
  </div>

  <div class="max-w-[1280px] mx-auto w-full">

    @if(session('success'))
      <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 text-sm">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 p-4 text-sm">
        <ul class="list-disc pl-5 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <div class="flex gap-5 items-start">

      {{-- ══════════════════════════════════════
           SIDEBAR QUICK FILL (desktop only)
      ══════════════════════════════════════ --}}
      <aside class="hidden lg:flex flex-col w-52 shrink-0 gap-3 sticky top-24">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-[0_8px_24px_rgba(2,6,23,0.07)] overflow-hidden">
          <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/60">
            <p class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
              <svg class="h-3.5 w-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
              </svg>
              Quick Fill
            </p>
            <p class="text-[11px] text-slate-400 mt-0.5">Klik nama → pilih hari</p>
          </div>
          <div class="p-3 space-y-1.5" id="quickFillList">
            @foreach(($users ?? []) as $u)
              <button type="button"
                      class="qf-btn w-full text-left px-3 py-2 rounded-xl border border-slate-100 bg-white
                             hover:border-slate-300 hover:shadow-sm transition text-xs font-semibold text-slate-700
                             flex items-center gap-2.5"
                      data-user-id="{{ $u->user_id }}"
                      data-username="{{ $u->username }}">
                <span class="h-6 w-6 rounded-full bg-slate-100 text-slate-600 grid place-items-center text-[10px] font-bold shrink-0 border border-slate-200">
                  {{ strtoupper(substr($u->username, 0, 1)) }}
                </span>
                <span class="truncate">{{ $u->username }}</span>
              </button>
            @endforeach
          </div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white/80 p-3 text-[11px] text-slate-500 leading-relaxed">
          <span class="font-semibold text-slate-700">Tips:</span> Quick Fill bisa isi shift &amp; jam sekaligus. Hari TUTUP &amp; hari penuh (4 agenda) tidak bisa dipilih.
        </div>
      </aside>

      {{-- ══════════════════════════════════════
           MAIN
      ══════════════════════════════════════ --}}
      <div class="flex-1 min-w-0 space-y-5">

        {{-- STEP 1: MINGGU --}}
        <div class="rounded-2xl bg-white border border-slate-200 shadow-[0_4px_16px_rgba(2,6,23,0.06)] overflow-hidden">
          <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-3">
            <span class="h-6 w-6 rounded-lg bg-slate-900 text-white grid place-items-center text-[11px] font-bold shrink-0">1</span>
            <p class="text-sm font-semibold text-slate-900">Pilih Minggu</p>
          </div>
          <div class="p-5 flex flex-col sm:flex-row sm:items-end gap-3">
            <div class="flex-1">
              <label class="block text-[11px] font-semibold text-slate-500 mb-1.5">Minggu</label>
              <input type="week" id="weekPicker"
                     value="{{ old('week', now()->format('Y-\WW')) }}"
                     class="w-full h-11 rounded-xl border border-slate-200 bg-white px-4 text-sm
                            focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300 transition">
            </div>
            <div class="inline-flex items-center gap-2 h-11 rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm text-slate-600 whitespace-nowrap">
              <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
              <span id="weekRangeText">—</span>
            </div>
          </div>
        </div>

        {{-- STEP 2: PILIH HARI --}}
        <div class="rounded-2xl bg-white border border-slate-200 shadow-[0_4px_16px_rgba(2,6,23,0.06)] overflow-hidden">
          <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <span class="h-6 w-6 rounded-lg bg-slate-900 text-white grid place-items-center text-[11px] font-bold shrink-0">2</span>
              <p class="text-sm font-semibold text-slate-900">Pilih Hari <span class="text-xs font-normal text-slate-400 ml-1">maks 4 agenda/hari</span></p>
            </div>
            <div class="flex gap-1.5">
              <button type="button" id="btnCheckAll"
                      class="h-7 px-3 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition text-xs font-semibold">Semua</button>
              <button type="button" id="btnUncheckAll"
                      class="h-7 px-3 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition text-xs font-semibold">Reset</button>
            </div>
          </div>
          <div class="p-4">
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2">
              @php
                $dayDefs = [
                  ['key'=>'senin',  'short'=>'Sen','label'=>'Senin',  'offset'=>0],
                  ['key'=>'selasa', 'short'=>'Sel','label'=>'Selasa', 'offset'=>1],
                  ['key'=>'rabu',   'short'=>'Rab','label'=>'Rabu',   'offset'=>2],
                  ['key'=>'kamis',  'short'=>'Kam','label'=>'Kamis',  'offset'=>3],
                  ['key'=>'jumat',  'short'=>'Jum','label'=>'Jumat',  'offset'=>4],
                  ['key'=>'sabtu',  'short'=>'Sab','label'=>'Sabtu',  'offset'=>5],
                  ['key'=>'minggu', 'short'=>'Min','label'=>'Minggu', 'offset'=>6],
                ];
              @endphp
              @foreach($dayDefs as $d)
                <label class="cursor-pointer select-none" data-day="{{ $d['key'] }}">
                  <input type="checkbox" class="day-check sr-only"
                         id="check_{{ $d['key'] }}"
                         data-day="{{ $d['key'] }}"
                         data-offset="{{ $d['offset'] }}">
                  <div class="day-check-card rounded-xl border border-slate-200 bg-slate-50 p-3 text-center hover:border-slate-300 transition-all">
                    <div class="text-lg font-bold text-slate-300 leading-none mb-0.5" id="dayNum_{{ $d['key'] }}">—</div>
                    <div class="text-[11px] font-semibold text-slate-600">{{ $d['short'] }}</div>
                    <div class="text-[10px] text-slate-400">{{ $d['label'] }}</div>
                    <div class="mt-2 h-4 w-4 rounded-full border-2 border-slate-200 mx-auto grid place-items-center transition-all day-check-dot">
                      <svg class="h-2.5 w-2.5 text-white hidden day-check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                      </svg>
                    </div>
                    <div class="mt-1 text-[10px] text-slate-400 leading-none day-agenda-count" id="agendaCount_{{ $d['key'] }}"></div>
                  </div>
                </label>
              @endforeach
            </div>
          </div>
        </div>

        {{-- STEP 3: FORM PER HARI --}}
        <form id="mainForm" action="{{ route('simpan_jadwal_kerja') }}" method="POST">
          @csrf
          <input type="hidden" name="week" id="weekInput" value="{{ old('week', now()->format('Y-\WW')) }}">

          <div id="dayFormsContainer" class="space-y-4"></div>

          <div id="submitSection" class="hidden rounded-2xl bg-white border border-slate-200 shadow-[0_4px_16px_rgba(2,6,23,0.06)] overflow-hidden mt-5">
            <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-3">
              <span class="h-6 w-6 rounded-lg bg-emerald-600 text-white grid place-items-center text-[11px] font-bold shrink-0">✓</span>
              <div>
                <p class="text-sm font-semibold text-slate-900">Simpan Jadwal</p>
                <p class="text-xs text-slate-500" id="submitSummary">—</p>
              </div>
            </div>
            <div class="p-5 flex flex-col sm:flex-row gap-2 sm:justify-end">
              <a href="{{ route('kelola_jadwal_kerja') }}"
                 class="h-11 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold inline-flex items-center justify-center">
                Batal
              </a>
              <button type="submit"
                      class="h-11 px-6 rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition text-sm font-semibold">
                Simpan Semua Jadwal
              </button>
            </div>
          </div>
        </form>

      </div>{{-- /main --}}
    </div>{{-- /flex --}}
  </div>
</section>

{{-- ══ QUICK FILL POPUP ════════════════════════════════════ --}}
<div id="qfPopup" class="fixed inset-0 z-[80] hidden">
  <div id="qfOverlay" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
  <div class="relative min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white border border-slate-200 shadow-[0_24px_64px_rgba(2,6,23,0.20)] overflow-hidden flex flex-col max-h-[90vh]">

      {{-- Header --}}
      <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between gap-3 shrink-0">
        <div>
          <p class="text-sm font-semibold text-slate-900">Quick Fill</p>
          <p class="text-xs text-slate-500 mt-0.5">Terapkan <span id="qfUsername" class="font-semibold text-slate-800">—</span> ke hari:</p>
        </div>
        <button type="button" id="qfClose"
                class="h-8 w-8 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center">
          <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <div class="p-4 space-y-4 overflow-y-auto flex-1 min-h-0">

        {{-- ── SHIFT & JAM (NEW) ── --}}
        <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-3 space-y-3">
          <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Detail Jadwal <span class="font-normal normal-case text-slate-400">(opsional)</span></p>

          <div>
            <label class="block text-[11px] font-semibold text-slate-500 mb-1">Waktu Shift</label>
            <select id="qfShift"
                    class="w-full h-9 rounded-xl border border-slate-200 bg-white px-3 text-sm
                           focus:outline-none focus:ring-4 focus:ring-slate-200/60 transition">
              <option value="">Pilih shift (opsional)</option>
              <option value="Pagi">Pagi</option>
              <option value="Siang">Siang</option>
              <option value="Sore">Sore</option>
              <option value="Malam">Malam</option>
            </select>
          </div>

          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="block text-[11px] font-semibold text-slate-500 mb-1">Jam Mulai</label>
              <input type="time" id="qfJamMulai"
                     class="w-full h-9 rounded-xl border border-slate-200 bg-white px-3 text-sm
                            focus:outline-none focus:ring-4 focus:ring-slate-200/60 transition">
            </div>
            <div>
              <label class="block text-[11px] font-semibold text-slate-500 mb-1">Jam Selesai</label>
              <input type="time" id="qfJamSelesai"
                     class="w-full h-9 rounded-xl border border-slate-200 bg-white px-3 text-sm
                            focus:outline-none focus:ring-4 focus:ring-slate-200/60 transition">
            </div>
          </div>

          <p class="text-[10px] text-slate-400 leading-relaxed">
            Kalau dikosongkan, shift &amp; jam akan mengikuti template dari agenda existing di hari tujuan (kalau ada).
          </p>
        </div>

        {{-- ── PILIH HARI ── --}}
        <div>
          <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-2">Pilih Hari</p>
          <div class="grid grid-cols-2 gap-2" id="qfDayGrid"></div>
        </div>

      </div>

      {{-- Footer sticky --}}
      <div class="px-4 pb-4 pt-3 border-t border-slate-100 bg-white shrink-0 flex gap-2 justify-end">
        <button type="button" id="qfCancel"
                class="h-9 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
          Batal
        </button>
        <button type="button" id="qfApply"
                class="h-9 px-5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition text-sm font-semibold">
          Terapkan
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('head')
<style>
  /* Day checkbox */
  .day-check:checked + .day-check-card {
    border-color: rgba(2,6,23,.4);
    background: rgba(2,6,23,.03);
  }
  .day-check:checked + .day-check-card .day-agenda-count,
  .day-check:checked + .day-check-card div { color: inherit; }
  .day-check:checked + .day-check-card #dayNum_senin,
  .day-check:checked + .day-check-card .text-slate-300 { color: rgba(2,6,23,.85)!important; }
  .day-check:checked + .day-check-card .day-check-dot {
    background: rgba(2,6,23,.85); border-color: rgba(2,6,23,.85);
  }
  .day-check:checked + .day-check-card .day-check-icon { display: block; }

  /* Day form */
  .day-form-card {
    border: 1px solid rgba(15,23,42,.10);
    border-radius: 18px;
    background: #fff;
    overflow: hidden;
  }

  /* Agenda slot */
  .agenda-slot {
    border: 1px solid rgba(15,23,42,.08);
    border-radius: 14px;
    background: rgba(248,250,252,.9);
    animation: slotIn .18s ease;
  }
  @keyframes slotIn {
    from { opacity:0; transform:translateY(-5px); }
    to   { opacity:1; transform:translateY(0); }
  }

  /* Locked select */
  select.locked {
    background:#f1f5f9!important; border-color:#cbd5e1!important;
    color:#64748b!important; cursor:not-allowed!important; pointer-events:none;
  }
  .field-hidden { display:none!important; }

  /* Radio status */
  .status-opt.is-active-emerald { outline: 2px solid #10b981; }
  .status-opt.is-active-amber   { outline: 2px solid #f59e0b; }
  .status-opt.is-active-rose    { outline: 2px solid #f43f5e; }

  /* Validation errors */
  .v-error-field { border-color: #f43f5e !important; background: #fff5f5 !important; }
  .bubble-error  { outline: 2px solid #f43f5e !important; animation: bubbleShake .3s ease; }

  /* Disabled day cards */
  .day-disabled-tutup { cursor: not-allowed !important; pointer-events: none; }
  .day-disabled-tutup .day-check-card { opacity: 0.6; }
  .day-disabled-full  { cursor: not-allowed !important; pointer-events: none; }
  .day-disabled-full  .day-check-card { opacity: 0.65; }
  @keyframes bubbleShake {
    0%,100% { transform: translateX(0); }
    25%      { transform: translateX(-4px); }
    75%      { transform: translateX(4px); }
  }

  /* QF day button */
  .qf-day-btn { transition: all .15s; cursor: pointer; }
  .qf-day-btn.selected {
    border-color: rgba(2,6,23,.45)!important;
    background: rgba(2,6,23,.04)!important;
    font-weight: 700;
  }
  .qf-day-btn.qf-disabled { opacity:.4; cursor:not-allowed; pointer-events:none; }
</style>
@endpush

@push('scripts')
<script>
// ══════════════════════════════════════════════════════
//  CONSTANTS
// ══════════════════════════════════════════════════════
const USERS           = @json($users ?? []);
const AUTH_USER_ID    = "{{ $authUser->user_id ?? '' }}";
const EXISTING_BY_DATE = @json($existingByDate ?? []);
const MAX_PER_DAY_AKTIF = 4;
const MAX_PER_DAY  = 4;
const MONTHS_ID    = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
const DAY_KEYS     = ['senin','selasa','rabu','kamis','jumat','sabtu','minggu'];
const DAY_LABELS   = {senin:'Senin',selasa:'Selasa',rabu:'Rabu',kamis:'Kamis',jumat:'Jumat',sabtu:'Sabtu',minggu:'Minggu'};

// ══════════════════════════════════════════════════════
//  WEEK HELPERS
// ══════════════════════════════════════════════════════
function parseWeek(val) {
  if (!val) return null;
  const [yr, wkStr] = val.split('-W');
  const year = +yr, week = +wkStr;
  const jan4 = new Date(year, 0, 4);
  const iso1 = new Date(jan4);
  iso1.setDate(jan4.getDate() - ((jan4.getDay() + 6) % 7));
  const mon = new Date(iso1);
  mon.setDate(iso1.getDate() + (week - 1) * 7);
  return mon;
}
const pad2 = n => String(n).padStart(2,'0');
const ymd  = d => `${d.getFullYear()}-${pad2(d.getMonth()+1)}-${pad2(d.getDate())}`;

function getDateForDay(key) {
  const mon = parseWeek(weekPicker.value);
  if (!mon) return '';
  const d = new Date(mon);
  d.setDate(mon.getDate() + DAY_KEYS.indexOf(key));
  return ymd(d);
}
function getDateLabelForDay(key) {
  const mon = parseWeek(weekPicker.value);
  if (!mon) return DAY_LABELS[key];
  const d = new Date(mon);
  d.setDate(mon.getDate() + DAY_KEYS.indexOf(key));
  return `${DAY_LABELS[key]}, ${d.getDate()} ${MONTHS_ID[d.getMonth()]} ${d.getFullYear()}`;
}

const weekPicker    = document.getElementById('weekPicker');
const weekInput     = document.getElementById('weekInput');
const weekRangeText = document.getElementById('weekRangeText');

function updateWeekUI() {
  weekInput.value = weekPicker.value;
  const mon = parseWeek(weekPicker.value);
  if (!mon) { weekRangeText.textContent = '—'; return; }
  const sun = new Date(mon); sun.setDate(mon.getDate() + 6);
  weekRangeText.textContent = `${mon.getDate()} ${MONTHS_ID[mon.getMonth()]} – ${sun.getDate()} ${MONTHS_ID[sun.getMonth()]} ${sun.getFullYear()}`;

  DAY_KEYS.forEach(key => {
    document.querySelectorAll(`input[name^="jadwal[${key}]"][name$="[tanggal_kerja]"]`)
      .forEach(el => el.value = getDateForDay(key));
    const lbl = document.getElementById('formDateLabel_' + key);
    if (lbl) lbl.textContent = getDateLabelForDay(key);
    const numEl = document.getElementById('dayNum_' + key);
    if (numEl) {
      const d = new Date(mon); d.setDate(mon.getDate() + DAY_KEYS.indexOf(key));
      numEl.textContent = pad2(d.getDate());
    }

    const dateKey    = getDateForDay(key);
    const existing   = EXISTING_BY_DATE[dateKey] || [];
    const exCount    = existing.length;
    const exHasTutup = existing.some(ag => (ag.status||'').toLowerCase() === 'tutup');
    const exAktifCount = existing.filter(ag => (ag.status||'Aktif') === 'Aktif').length;
    const exIsFull   = exAktifCount >= MAX_PER_DAY_AKTIF;
    const countEl    = document.getElementById('agendaCount_' + key);
    const cb         = document.getElementById('check_' + key);
    const card       = document.querySelector(`label[data-day="${key}"] .day-check-card`);

    if (countEl && !cb?.checked) {
      if (exCount > 0) {
        countEl.textContent = exHasTutup ? 'TUTUP' : (exIsFull ? `${exAktifCount}/${MAX_PER_DAY_AKTIF} penuh` : `${exAktifCount}/${MAX_PER_DAY_AKTIF}`);
        countEl.style.color = exHasTutup ? '#f43f5e' : (exIsFull ? '#f59e0b' : '#94a3b8');
      } else {
        countEl.textContent = '';
      }
    }

    if (cb) {
      const label = document.querySelector(`label[data-day="${key}"]`);
      if (exHasTutup) {
        cb.disabled = true;
        if (label) { label.classList.add('day-disabled-tutup'); label.classList.remove('cursor-pointer'); }
        if (card)  card.classList.add('!border-rose-200', '!bg-rose-50');
        if (cb.checked) { cb.checked = false; onDayCheck(key, false); }
      } else if (exIsFull) {
        cb.disabled = true;
        if (label) label.classList.add('day-disabled-full');
        if (card)  card.classList.add('!border-amber-200', '!bg-amber-50/50');
        if (cb.checked) { cb.checked = false; onDayCheck(key, false); }
      } else {
        cb.disabled = false;
        if (label) { label.classList.remove('day-disabled-tutup', 'day-disabled-full'); label.classList.add('cursor-pointer'); }
        if (card)  card.classList.remove('!border-rose-200', '!bg-rose-50', '!border-amber-200', '!bg-amber-50/50');
      }
    }
  });
}
weekPicker.addEventListener('change', () => {
  const week = weekPicker.value;
  if (!week) { updateWeekUI(); return; }
  fetch(`${window.location.pathname}?week=${encodeURIComponent(week)}`, {
    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
  })
  .then(r => r.ok ? r.json() : Promise.reject())
  .then(json => {
    Object.keys(EXISTING_BY_DATE).forEach(k => delete EXISTING_BY_DATE[k]);
    Object.assign(EXISTING_BY_DATE, json.existingByDate || {});
    DAY_KEYS.forEach(k => {
      const cb = document.getElementById('check_'+k);
      if (cb?.checked) { cb.checked = false; onDayCheck(k, false); }
    });
    updateWeekUI();
  })
  .catch(() => updateWeekUI());
});
updateWeekUI();

// ══════════════════════════════════════════════════════
//  DAY CHECKLIST
// ══════════════════════════════════════════════════════
document.getElementById('btnCheckAll').addEventListener('click', () =>
  DAY_KEYS.forEach(k => { const cb = document.getElementById('check_'+k); if(cb&&!cb.checked&&!cb.disabled){cb.checked=true;onDayCheck(k,true);} })
);
document.getElementById('btnUncheckAll').addEventListener('click', () =>
  DAY_KEYS.forEach(k => { const cb = document.getElementById('check_'+k); if(cb&&cb.checked){cb.checked=false;onDayCheck(k,false);} })
);
DAY_KEYS.forEach(key =>
  document.getElementById('check_'+key)?.addEventListener('change', function(){ onDayCheck(key, this.checked); })
);

function onDayCheck(key, checked) {
  if (checked) {
    if (!document.getElementById('dayForm_'+key)) renderDayBlock(key);
  } else {
    document.getElementById('dayForm_'+key)?.remove();
    if (window._agendaData) window._agendaData[key] = [];
    if (window._activeSlot) delete window._activeSlot[key];
    updateAgendaCount(key);
  }
  updateSubmitSection();
}

// ══════════════════════════════════════════════════════
//  RENDER DAY BLOCK
// ══════════════════════════════════════════════════════
function renderDayBlock(key) {
  const container = document.getElementById('dayFormsContainer');
  const idx       = DAY_KEYS.indexOf(key);

  const wrap = document.createElement('div');
  wrap.id        = 'dayForm_' + key;
  wrap.className = 'day-form-card';
  wrap.dataset.day = key;
  wrap.dataset.idx = idx;
  wrap.innerHTML = `
    <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between gap-3 bg-slate-50/50">
      <div class="flex items-center gap-2">
        <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span class="text-sm font-semibold text-slate-900" id="formDateLabel_${key}">${getDateLabelForDay(key)}</span>
      </div>
      <button type="button" onclick="uncheckDay('${key}')"
              class="h-7 px-3 rounded-lg border border-slate-200 bg-white hover:bg-rose-50 hover:border-rose-200 hover:text-rose-600 transition text-xs font-medium text-slate-400">
        ✕ Hapus hari
      </button>
    </div>
    <div class="px-4 pt-4 pb-1">
      <div class="flex items-center gap-2 flex-wrap" id="bubbleRow_${key}"></div>
    </div>
    <div class="px-4 pb-4 pt-3" id="agendaList_${key}"></div>
  `;

  const existing = Array.from(container.querySelectorAll('.day-form-card'));
  let inserted = false;
  for (const el of existing) {
    if (+el.dataset.idx > idx) { container.insertBefore(wrap, el); inserted = true; break; }
  }
  if (!inserted) container.appendChild(wrap);

  if (!window._agendaData) window._agendaData = {};
  if (!window._activeSlot) window._activeSlot = {};

  const dateForDay    = getDateForDay(key);
  const existingAgs   = EXISTING_BY_DATE[dateForDay] || [];
  const existingCount = existingAgs.length;

  window._agendaData[key] = existingAgs.map(ag => ({
    user_id:     String(ag.user_id),
    username:    getUserName(ag.user_id),
    waktu_shift: ag.waktu_shift || '',
    jam_mulai:   ag.jam_mulai   || '',
    jam_selesai: ag.jam_selesai || '',
    status:      ag.status      || 'Aktif',
    deskripsi:   ag.deskripsi   || '',
    filled:      true,
    fromDB:      true,
    jadwal_id:   ag.jadwal_id,
  }));

  const existingHasTutup  = window._agendaData[key].some(ag => (ag.status||'').toLowerCase() === 'tutup');
  const existingAktifCount = window._agendaData[key].filter(ag => (ag.status||'Aktif') === 'Aktif').length;
  const existingAktifFull  = existingAktifCount >= MAX_PER_DAY_AKTIF;

  if (existingHasTutup || existingAktifFull) {
    window._activeSlot[key] = 0;
    renderBubbleRow(key);
    renderActiveForm(key, 0);
    updateAgendaCount(key);
    updateSubmitSection();

    if (existingHasTutup) {
      const lbl = document.getElementById('formDateLabel_' + key);
      if (lbl) {
        const badge = document.createElement('span');
        badge.className = 'ml-2 text-[10px] font-bold bg-rose-100 text-rose-600 px-2 py-0.5 rounded-full';
        badge.textContent = 'TUTUP';
        lbl.appendChild(badge);
      }
    }
  } else {
    window._activeSlot[key] = existingCount > 0 ? existingCount : 0;
    if (existingCount > 0) renderBubbleRow(key);
    addAgenda(key);
  }
}

// ══════════════════════════════════════════════════════
//  BUBBLE HELPERS
// ══════════════════════════════════════════════════════
const STATUS_COLOR = { Aktif:'emerald', Catatan:'amber', Tutup:'rose' };

function renderBubbleRow(key) {
  const row = document.getElementById('bubbleRow_' + key);
  if (!row) return;
  const data   = window._agendaData[key] || [];
  const active = window._activeSlot?.[key] ?? 0;

  const aktifCount   = data.filter(ag => !ag.fromDB && (ag.status||'Aktif') === 'Aktif').length;
  const dbAktifCount = data.filter(ag => ag.fromDB && (ag.status||'Aktif') === 'Aktif').length;
  const totalAktif   = aktifCount + dbAktifCount;
  const maxed        = totalAktif >= MAX_PER_DAY_AKTIF;
  const hasTutupAny  = data.some(ag => (ag.status||'').toLowerCase() === 'tutup');

  row.innerHTML = '';

  data.forEach((ag, i) => {
    const isActive  = i === active;
    const statusVal = ag.status || 'Aktif';
    const isFromDB  = ag.fromDB === true;
    const col       = STATUS_COLOR[statusVal] || 'slate';
    const colMap    = {
      emerald: isActive ? 'bg-emerald-600 border-emerald-600 text-white shadow-md shadow-emerald-200' : 'bg-emerald-50 border-emerald-300 text-emerald-800 hover:bg-emerald-100',
      amber:   isActive ? 'bg-amber-500 border-amber-500 text-white shadow-md shadow-amber-200'       : 'bg-amber-50 border-amber-300 text-amber-800 hover:bg-amber-100',
      rose:    isActive ? 'bg-rose-500 border-rose-500 text-white shadow-md shadow-rose-200'          : 'bg-rose-50 border-rose-300 text-rose-800 hover:bg-rose-100',
      slate:   isActive ? 'bg-slate-800 border-slate-800 text-white shadow-md'                        : 'bg-slate-100 border-slate-200 text-slate-600 hover:bg-slate-200',
    };
    const cls = colMap[col] || colMap.slate;
    const uName = ag.username || '';

    const pill = document.createElement('button');
    pill.type      = 'button';
    pill.className = `agenda-bubble inline-flex items-center gap-1.5 h-9 px-3 rounded-full border text-xs font-bold transition-all duration-150 ${cls}`;
    pill.dataset.idx = i;
    pill.innerHTML = `
      <span class="agenda-bubble-num">${i+1}</span>
      ${uName ? `<span class="max-w-[72px] truncate opacity-90 font-semibold">${uName}</span>` : ''}
      ${statusVal === 'Tutup'   ? `<span class="text-[10px] opacity-80">✕</span>` : ''}
      ${statusVal === 'Catatan' ? `<span class="text-[10px] opacity-80">📝</span>` : ''}
      ${isFromDB ? `<span class="text-[10px] opacity-60" title="Sudah tersimpan">🔒</span>` : ''}
    `;
    pill.addEventListener('click', () => activateBubble(key, i));
    row.appendChild(pill);
  });

  if (!maxed && !hasTutupAny) {
    const addPill = document.createElement('button');
    addPill.type      = 'button';
    addPill.id        = 'addBubbleBtn_' + key;
    addPill.className = 'inline-flex items-center gap-1 h-9 px-3 rounded-full border-2 border-dashed border-slate-300 text-slate-400 text-xs font-bold hover:border-slate-500 hover:text-slate-600 transition-all duration-150';
    addPill.innerHTML = `<svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>`;
    addPill.addEventListener('click', () => addAgenda(key));
    row.appendChild(addPill);
  }
}

function activateBubble(key, idx) {
  if (!window._activeSlot) window._activeSlot = {};
  window._activeSlot[key] = idx;
  renderBubbleRow(key);
  renderActiveForm(key, idx);
}

// ══════════════════════════════════════════════════════
//  RENDER ACTIVE FORM PANEL
// ══════════════════════════════════════════════════════
function renderActiveForm(key, si) {
  const panel = document.getElementById('agendaList_' + key);
  if (!panel) return;
  const data      = window._agendaData[key] || [];
  const ag        = data[si] || {};
  const statusVal = ag.status || 'Aktif';
  const isTutup   = statusVal === 'Tutup';
  const isCatatan = statusVal === 'Catatan';
  const isRestrict = isTutup || isCatatan;
  const isFromDB   = ag.fromDB === true;

  if (isFromDB) {
    const colMap = { Aktif:'emerald', Catatan:'amber', Tutup:'rose' };
    const col = colMap[statusVal] || 'slate';
    panel.innerHTML = `
      <div class="agenda-slot p-4 bg-slate-50/80" data-slot="${si}">
        <div class="flex items-center justify-between gap-2 mb-3">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wide flex items-center gap-1.5">
            <span>Agenda ${si+1}</span>
            <span class="text-[10px] bg-slate-200 text-slate-500 px-2 py-0.5 rounded-full font-semibold">Tersimpan</span>
          </span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
          <div>
            <p class="text-[11px] font-semibold text-slate-400 mb-0.5">Nama</p>
            <p class="font-semibold text-slate-700">${ag.username || '—'}</p>
          </div>
          ${!isTutup ? `
          <div>
            <p class="text-[11px] font-semibold text-slate-400 mb-0.5">Shift</p>
            <p class="font-semibold text-slate-700">${ag.waktu_shift || '—'}</p>
          </div>
          <div>
            <p class="text-[11px] font-semibold text-slate-400 mb-0.5">Jam</p>
            <p class="font-semibold text-slate-700">${ag.jam_mulai||'—'} – ${ag.jam_selesai||'—'}</p>
          </div>` : ''}
          <div>
            <p class="text-[11px] font-semibold text-slate-400 mb-0.5">Status</p>
            <span class="inline-block text-xs font-bold px-2 py-0.5 rounded-full bg-${col}-100 text-${col}-700">${statusVal}</span>
          </div>
          ${ag.deskripsi ? `<div class="col-span-2 sm:col-span-3">
            <p class="text-[11px] font-semibold text-slate-400 mb-0.5">Deskripsi</p>
            <p class="text-slate-600">${ag.deskripsi}</p>
          </div>` : ''}
        </div>
        <p class="mt-3 text-[11px] text-slate-400 italic">Jadwal ini sudah tersimpan. Gunakan menu Ubah untuk mengedit.</p>
      </div>
      <div id="hiddenInputs_${key}"></div>
    `;
    renderHiddenInputs(key, si);
    return;
  }

  const statuses = [
    {val:'Aktif',   col:'emerald', desc:'Shift kerja'},
    {val:'Catatan', col:'amber',   desc:'Reminder/info'},
    {val:'Tutup',   col:'rose',    desc:'Libur/tutup'},
  ];

  const userNote = isRestrict
    ? `<p class="text-[11px] text-amber-600 mt-1 font-medium">
        ${isTutup ? 'ℹ️ Jadwal lain di hari ini akan tersembunyi selama Tutup aktif' : '📝 Catatan otomatis tercatat atas nama admin yang login'}
       </p>`
    : '';

  panel.innerHTML = `
    <div class="agenda-slot p-4" data-slot="${si}">
      <div class="flex items-center justify-between gap-2 mb-3">
        <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Agenda ${si+1}</span>
        ${si > 0 || (data.length > 1 && !data[0]?.fromDB) ? `<button type="button" onclick="removeBubble('${key}',${si})"
          class="h-7 px-2.5 rounded-lg border border-slate-200 bg-white hover:bg-rose-50 hover:border-rose-200 hover:text-rose-600 transition text-xs text-slate-400">Hapus</button>` : ''}
      </div>

      <input type="hidden" name="jadwal[${key}][${si}][tanggal_kerja]" value="${getDateForDay(key)}">

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

        <div class="sm:col-span-2">
          <label class="block text-[11px] font-semibold text-slate-500 mb-1">Nama</label>
          ${isRestrict ? `
            <div class="h-10 rounded-xl border border-slate-200 bg-slate-50 px-3 flex items-center text-sm font-semibold text-slate-500 gap-2">
              <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
              <span id="lockedUserName_${key}_${si}">${getUserName(AUTH_USER_ID) || 'Admin login'}</span>
            </div>
            <input type="hidden" name="jadwal[${key}][${si}][user_id]" id="uSel_${key}_${si}" value="${AUTH_USER_ID}">
          ` : `
            <select name="jadwal[${key}][${si}][user_id]" id="uSel_${key}_${si}"
                    class="w-full h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm
                           focus:outline-none focus:ring-4 focus:ring-slate-200/60 transition"
                    onchange="onUserChange('${key}',${si},this)">
              <option value="">Pilih user</option>
              ${USERS.map(u=>`<option value="${u.user_id}"${String(u.user_id)===String(ag.user_id||'')?'selected':''}>${u.username}</option>`).join('')}
            </select>
          `}
          ${userNote}
        </div>

        <div id="swWrap_${key}_${si}" ${isTutup?'class="field-hidden"':''}>
          <label class="block text-[11px] font-semibold text-slate-500 mb-1">Waktu Shift</label>
          <select name="jadwal[${key}][${si}][waktu_shift]" id="swSel_${key}_${si}"
                  class="w-full h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm
                         focus:outline-none focus:ring-4 focus:ring-slate-200/60 transition"
                  onchange="syncField('${key}',${si},'waktu_shift',this.value)">
            <option value="">Pilih shift</option>
            ${['Pagi','Siang','Sore','Malam'].map(s=>`<option value="${s}"${(ag.waktu_shift||'')===s?'selected':''}>${s}</option>`).join('')}
          </select>
        </div>

        <div id="jmWrap_${key}_${si}" ${isTutup?'class="field-hidden"':''}>
          <label class="block text-[11px] font-semibold text-slate-500 mb-1">Jam Mulai</label>
          <input type="time" name="jadwal[${key}][${si}][jam_mulai]" id="jm_${key}_${si}"
                 value="${isTutup?'':(ag.jam_mulai||'')}"
                 onchange="syncField('${key}',${si},'jam_mulai',this.value)"
                 class="w-full h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm
                        focus:outline-none focus:ring-4 focus:ring-slate-200/60 transition">
        </div>

        <div id="jsWrap_${key}_${si}" ${isTutup?'class="field-hidden"':''}>
          <label class="block text-[11px] font-semibold text-slate-500 mb-1">Jam Selesai</label>
          <input type="time" name="jadwal[${key}][${si}][jam_selesai]" id="js_${key}_${si}"
                 value="${isTutup?'':(ag.jam_selesai||'')}"
                 onchange="syncField('${key}',${si},'jam_selesai',this.value)"
                 class="w-full h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm
                        focus:outline-none focus:ring-4 focus:ring-slate-200/60 transition">
        </div>

        <div class="sm:col-span-2">
          <label class="block text-[11px] font-semibold text-slate-500 mb-1">Status</label>
          <div class="flex gap-2" id="statusRow_${key}_${si}">
            ${statuses.map(s=>`
              <label class="flex-1 cursor-pointer">
                <input type="radio" name="jadwal[${key}][${si}][status]" value="${s.val}"
                       class="sr-only" ${statusVal===s.val?'checked':''}
                       onchange="onStatusChange('${key}',${si},'${s.val}')">
                <div class="status-opt rounded-xl border border-${s.col}-200 bg-${s.col}-50 px-2 py-2 text-center
                            hover:bg-${s.col}-100 transition ${statusVal===s.val?`is-active-${s.col}`:''}">
                  <div class="text-xs font-bold text-${s.col}-800">${s.val}</div>
                  <div class="text-[10px] text-${s.col}-600">${s.desc}</div>
                </div>
              </label>`).join('')}
          </div>
        </div>

        <div class="sm:col-span-2" id="dWrap_${key}_${si}" ${isTutup?'class="field-hidden"':''}>
          <label class="block text-[11px] font-semibold text-slate-500 mb-1">Deskripsi <span class="font-normal opacity-50">(opsional)</span></label>
          <input type="text" name="jadwal[${key}][${si}][deskripsi]"
                 value="${esc(ag.deskripsi||'')}"
                 placeholder="Contoh: Servis rutin, booking pelanggan..."
                 onchange="syncField('${key}',${si},'deskripsi',this.value)"
                 class="w-full h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm
                        focus:outline-none focus:ring-4 focus:ring-slate-200/60 transition">
        </div>
      </div>
    </div>
    <div id="hiddenInputs_${key}"></div>
  `;

  renderHiddenInputs(key, si);
}

function renderHiddenInputs(key, activeIdx) {
  const container = document.getElementById('hiddenInputs_' + key);
  if (!container) return;
  const data = window._agendaData[key] || [];
  container.innerHTML = '';

  let submitIdx = 0;
  let activeSubmitIdx = 0;
  data.forEach((ag, i) => {
    if (ag.fromDB) return;
    if (i === activeIdx) { activeSubmitIdx = submitIdx; }
    submitIdx++;
  });

  const panel = document.getElementById('agendaList_' + key);
  if (panel) {
    panel.querySelectorAll('[name]').forEach(el => {
      el.name = el.name.replace(
        new RegExp('\\[' + key + '\\]\\[\\d+\\]\\['),
        '[' + key + '][' + activeSubmitIdx + ']['
      );
    });
  }

  submitIdx = 0;
  data.forEach((ag, i) => {
    if (ag.fromDB) return;
    if (i === activeIdx) { submitIdx++; return; }
    const fields = ['tanggal_kerja','user_id','waktu_shift','jam_mulai','jam_selesai','status','deskripsi'];
    fields.forEach(f => {
      const inp = document.createElement('input');
      inp.type  = 'hidden';
      inp.name  = `jadwal[${key}][${submitIdx}][${f}]`;
      inp.value = f === 'tanggal_kerja' ? getDateForDay(key) : (ag[f] || (f==='status'?'Aktif':''));
      container.appendChild(inp);
    });
    submitIdx++;
  });
}

// ══════════════════════════════════════════════════════
//  ADD AGENDA
// ══════════════════════════════════════════════════════
function addAgenda(key, prefill = {}) {
  if (!window._agendaData) window._agendaData = {};
  if (!window._agendaData[key]) window._agendaData[key] = [];

  const data         = window._agendaData[key];
  const hasTutupAny  = data.some(ag => (ag.status||'').toLowerCase() === 'tutup');
  if (hasTutupAny) { showToast('Hari ini TUTUP. Tidak bisa menambah agenda.', 'warn'); return; }

  const aktifCount = data.filter(ag => (ag.status||'Aktif') === 'Aktif').length;
  if (aktifCount >= MAX_PER_DAY_AKTIF) {
    showToast(`Maks ${MAX_PER_DAY_AKTIF} shift Aktif per hari (Catatan tidak terhitung).`, 'warn');
    return;
  }

  const isPrefill = Object.keys(prefill).length > 0;
  if (!isPrefill && data.length > 0) {
    const lastNew = [...data].reverse().find(ag => !ag.fromDB);
    if (lastNew && !lastNew.user_id && !lastNew.filled) {
      activateBubble(key, data.indexOf(lastNew));
      showToast('Isi agenda sebelumnya dulu ya.', 'warn');
      return;
    }
  }

  const newStatus = prefill.status || 'Aktif';
  const ag = {
    user_id:     (newStatus === 'Tutup' || newStatus === 'Catatan') ? AUTH_USER_ID : (prefill.user_id || ''),
    username:    (newStatus === 'Tutup' || newStatus === 'Catatan') ? getUserName(AUTH_USER_ID) : getUserName(prefill.user_id),
    waktu_shift: prefill.waktu_shift || '',
    jam_mulai:   prefill.jam_mulai   || '',
    jam_selesai: prefill.jam_selesai || '',
    status:      newStatus,
    deskripsi:   prefill.deskripsi   || '',
    filled:      !!(prefill.user_id || newStatus === 'Tutup' || newStatus === 'Catatan'),
  };

  if (newStatus === 'Tutup') {
    data.push(ag);
    const newIdx = data.length - 1;
    if (!window._activeSlot) window._activeSlot = {};
    window._activeSlot[key] = newIdx;
    renderBubbleRow(key);
    renderActiveForm(key, newIdx);
    updateAgendaCount(key);
    updateSubmitSection();
    showToast('ℹ️ Tutup ditambahkan. Jadwal lain di hari ini akan tersembunyi di tampilan.', 'info');
    return;
  }

  data.push(ag);
  const newIdx = data.length - 1;
  if (!window._activeSlot) window._activeSlot = {};
  window._activeSlot[key] = newIdx;

  renderBubbleRow(key);
  renderActiveForm(key, newIdx);
  updateAgendaCount(key);
  updateSubmitSection();
}

const esc = s => String(s).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
const getUserName = id => { const u = USERS.find(u => String(u.user_id) === String(id)); return u ? u.username : ''; };

// ══════════════════════════════════════════════════════
//  SYNC FIELD
// ══════════════════════════════════════════════════════
function syncField(key, si, field, val) {
  if (!window._agendaData?.[key]?.[si]) return;
  window._agendaData[key][si][field] = val;
  renderHiddenInputs(key, si);
}

function onUserChange(key, si, sel) {
  if (!window._agendaData?.[key]?.[si]) return;
  const uid = sel.value;
  window._agendaData[key][si].user_id  = uid;
  window._agendaData[key][si].username = getUserName(uid);
  window._agendaData[key][si].filled   = !!uid;
  renderBubbleRow(key);
  renderHiddenInputs(key, si);
}

// ══════════════════════════════════════════════════════
//  REMOVE BUBBLE
// ══════════════════════════════════════════════════════
function removeBubble(key, si) {
  const data = window._agendaData[key];
  if (!data) return;
  const nonDB = data.filter(ag => !ag.fromDB);
  if (nonDB.length <= 1 && !data[si]?.fromDB) return;
  data.splice(si, 1);

  if (!window._activeSlot) window._activeSlot = {};
  window._activeSlot[key] = Math.min(window._activeSlot[key] ?? 0, data.length - 1);

  renderBubbleRow(key);
  renderActiveForm(key, window._activeSlot[key]);
  updateAgendaCount(key);
  updateSubmitSection();
}

// ══════════════════════════════════════════════════════
//  STATUS CHANGE
// ══════════════════════════════════════════════════════
function onStatusChange(key, si, val) {
  const isTutup    = val === 'Tutup';
  const isCatatan  = val === 'Catatan';
  const isRestrict = isTutup || isCatatan;

  if (window._agendaData?.[key]?.[si]) {
    window._agendaData[key][si].status = val;
    if (isTutup || isCatatan) {
      window._agendaData[key][si].user_id  = AUTH_USER_ID;
      window._agendaData[key][si].username = getUserName(AUTH_USER_ID);
      window._agendaData[key][si].filled   = true;
    }
    if (isTutup) {
      window._agendaData[key][si].waktu_shift = '';
      window._agendaData[key][si].jam_mulai   = '';
      window._agendaData[key][si].jam_selesai = '';
    }
  }

  if (isTutup) {
    renderBubbleRow(key);
    renderActiveForm(key, si);
    updateAgendaCount(key);
    updateSubmitSection();
    return;
  }

  ['swWrap','jmWrap','jsWrap','dWrap'].forEach(p => {
    document.getElementById(`${p}_${key}_${si}`)?.classList.toggle('field-hidden', isTutup);
  });

  renderActiveForm(key, si);

  const row = document.getElementById(`statusRow_${key}_${si}`);
  if (row) {
    const colorMap = {Aktif:'emerald', Catatan:'amber', Tutup:'rose'};
    row.querySelectorAll('.status-opt').forEach(d => ['emerald','amber','rose'].forEach(c => d.classList.remove(`is-active-${c}`)));
    row.querySelector(`input[value="${val}"]`)?.nextElementSibling?.classList.add(`is-active-${colorMap[val]||'emerald'}`);
  }

  renderBubbleRow(key);
  renderHiddenInputs(key, si);
}

// ══════════════════════════════════════════════════════
//  UI HELPERS
// ══════════════════════════════════════════════════════
function updateAgendaCount(key) {
  const data = window._agendaData?.[key] || [];
  const el   = document.getElementById('agendaCount_' + key);
  if (!el) return;

  if (data.length === 0) { el.textContent = ''; el.style.color = ''; return; }

  const hasTutup   = data.some(ag => (ag.status||'').toLowerCase() === 'tutup');
  const aktifNew   = data.filter(ag => !ag.fromDB && (ag.status||'Aktif') === 'Aktif').length;
  const aktifDB    = data.filter(ag => ag.fromDB  && (ag.status||'Aktif') === 'Aktif').length;
  const totalAktif = aktifDB + aktifNew;
  const catNew     = data.filter(ag => !ag.fromDB && ag.status === 'Catatan').length;

  if (hasTutup) {
    el.textContent = 'TUTUP';
    el.style.color = '#f43f5e';
    return;
  }

  const parts = [];
  if (totalAktif > 0) parts.push(`${totalAktif}/${MAX_PER_DAY_AKTIF} Aktif`);
  if (catNew > 0) parts.push(`+${catNew} Catatan`);
  el.textContent = parts.join(' · ');
  el.style.color = totalAktif >= MAX_PER_DAY_AKTIF ? '#f59e0b' : '#94a3b8';
}

function uncheckDay(key) {
  const cb = document.getElementById('check_' + key);
  if (cb) { cb.checked = false; onDayCheck(key, false); }
}

function updateSubmitSection() {
  const checked = DAY_KEYS.filter(k => document.getElementById('check_'+k)?.checked);
  const section = document.getElementById('submitSection');
  const summary = document.getElementById('submitSummary');
  if (checked.length > 0) {
    section.classList.remove('hidden');
    const totalNew = checked.reduce((s,k) => {
      const data = window._agendaData?.[k] || [];
      return s + data.filter(ag => !ag.fromDB).length;
    }, 0);
    summary.textContent = totalNew === 0
      ? 'Belum ada agenda baru yang ditambahkan.'
      : `${checked.length} hari · ${totalNew} agenda baru akan disimpan`;
  } else {
    section.classList.add('hidden');
  }
}

// ══════════════════════════════════════════════════════
//  QUICK FILL  ← HANYA BAGIAN INI YANG BERUBAH
// ══════════════════════════════════════════════════════
let qfUserId = null, qfUserName = null;
const qfPopup      = document.getElementById('qfPopup');
const qfOverlay    = document.getElementById('qfOverlay');
const qfDayGrid    = document.getElementById('qfDayGrid');
const qfUsernameEl = document.getElementById('qfUsername');
// Elemen baru
const qfShift      = document.getElementById('qfShift');
const qfJamMulai   = document.getElementById('qfJamMulai');
const qfJamSelesai = document.getElementById('qfJamSelesai');

function openQF(userId, username) {
  qfUserId = userId; qfUserName = username;
  qfUsernameEl.textContent = username;

  // Reset field shift & jam setiap buka popup
  qfShift.value      = '';
  qfJamMulai.value   = '';
  qfJamSelesai.value = '';

  qfDayGrid.innerHTML = '';
  DAY_KEYS.forEach(key => {
    const dateForDay = getDateForDay(key);

    // Selalu pakai EXISTING_BY_DATE sebagai source of truth untuk data DB (sudah diupdate saat ganti minggu)
    const dbData      = EXISTING_BY_DATE[dateForDay] || [];
    // Tambahkan slot baru (non-DB) yang ditambah di sesi ini, kalau ada
    const sessionNew  = (window._agendaData?.[key] || []).filter(ag => !ag.fromDB);
    const combined    = [...dbData, ...sessionNew];

    const hasTutup    = combined.some(ag => (ag.status || '').toLowerCase() === 'tutup');
    const aktifCount  = combined.filter(ag => (ag.status||'Aktif') === 'Aktif').length;
    const isFull      = aktifCount >= MAX_PER_DAY_AKTIF;
    const disabled    = isFull || hasTutup;
    const remaining   = MAX_PER_DAY_AKTIF - aktifCount;

    const btn = document.createElement('button');
    btn.type = 'button';
    btn.dataset.day = key;
    btn.className = `qf-day-btn w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-center ${disabled?'qf-disabled':'hover:border-slate-300'}`;

    let note = hasTutup
      ? `<span class="text-[10px] text-rose-500 block mt-0.5">TUTUP</span>`
      : isFull
        ? `<span class="text-[10px] text-amber-600 block mt-0.5">PENUH (${MAX_PER_DAY_AKTIF}/${MAX_PER_DAY_AKTIF})</span>`
        : aktifCount > 0
          ? `<span class="text-[10px] text-slate-400 block mt-0.5">${aktifCount}/${MAX_PER_DAY_AKTIF} · sisa ${remaining}</span>`
          : `<span class="text-[10px] text-slate-400 block mt-0.5">Kosong</span>`;

    btn.innerHTML = `<div class="text-xs font-semibold text-slate-700">${DAY_LABELS[key]}</div>${note}`;
    if (!disabled) btn.addEventListener('click', () => btn.classList.toggle('selected'));
    qfDayGrid.appendChild(btn);
  });

  qfPopup.classList.remove('hidden');
  document.body.classList.add('overflow-hidden');
}

function closeQF() {
  qfPopup.classList.add('hidden');
  document.body.classList.remove('overflow-hidden');
}

document.getElementById('qfClose').addEventListener('click', closeQF);
document.getElementById('qfCancel').addEventListener('click', closeQF);
qfOverlay.addEventListener('click', closeQF);

document.getElementById('qfApply').addEventListener('click', () => {
  const selected = Array.from(qfDayGrid.querySelectorAll('.qf-day-btn.selected')).map(b => b.dataset.day);
  if (!selected.length) { closeQF(); return; }

  // Ambil nilai shift & jam dari popup (boleh kosong)
  const qfShiftVal    = qfShift.value.trim();
  const qfMulaiVal    = qfJamMulai.value.trim();
  const qfSelesaiVal  = qfJamSelesai.value.trim();

  selected.forEach(key => {
    const cb = document.getElementById('check_'+key);
    if (cb && !cb.checked) { cb.checked = true; if(!document.getElementById('dayForm_'+key)) renderDayBlock(key); }

    const data = window._agendaData?.[key] || [];
    const emptyIdx = data.findIndex(ag => !ag.user_id);

    // Kalau field QF dikosongkan → fallback ke template existing (behaviour lama)
    const template = data.find(ag => ag.user_id && ag.status === 'Aktif');
    const prefill = {
      user_id:     qfUserId,
      username:    getUserName(qfUserId),
      waktu_shift: qfShiftVal    || template?.waktu_shift || '',
      jam_mulai:   qfMulaiVal    || template?.jam_mulai   || '',
      jam_selesai: qfSelesaiVal  || template?.jam_selesai || '',
      status:      'Aktif',
      deskripsi:   template?.deskripsi || '',
    };

    if (emptyIdx !== -1) {
      Object.assign(data[emptyIdx], prefill);
      data[emptyIdx].filled = true;
      if (!window._activeSlot) window._activeSlot = {};
      window._activeSlot[key] = emptyIdx;
      renderBubbleRow(key);
      renderActiveForm(key, emptyIdx);
      updateAgendaCount(key);
    } else {
      addAgenda(key, prefill);
    }
  });

  updateSubmitSection();
  closeQF();

  // Toast info: tampilkan ringkasan apa yang diisi
  const parts = [];
  if (qfShiftVal)   parts.push(qfShiftVal);
  if (qfMulaiVal && qfSelesaiVal) parts.push(`${qfMulaiVal}–${qfSelesaiVal}`);
  const detail = parts.length ? ` · ${parts.join(', ')}` : '';
  showToast(`Quick Fill: ${selected.map(k=>DAY_LABELS[k]).join(', ')}${detail}`, 'success');
});

document.querySelectorAll('.qf-btn').forEach(btn =>
  btn.addEventListener('click', () => openQF(btn.dataset.userId, btn.dataset.username))
);

// ══════════════════════════════════════════════════════
//  FORM SUBMIT + VALIDATION
// ══════════════════════════════════════════════════════
function clearValidationErrors() {
  document.querySelectorAll('.v-error-msg').forEach(el => el.remove());
  document.querySelectorAll('.v-error-field').forEach(el => {
    el.classList.remove('v-error-field');
    el.style.borderColor = '';
  });
  document.querySelectorAll('.bubble-error').forEach(el => el.classList.remove('bubble-error'));
}

function markFieldError(fieldEl, msg) {
  if (!fieldEl) return;
  fieldEl.classList.add('v-error-field');
  fieldEl.style.borderColor = '#f43f5e';
  const existing = fieldEl.parentNode.querySelector('.v-error-msg');
  if (!existing) {
    const span = document.createElement('p');
    span.className = 'v-error-msg text-[11px] text-rose-500 mt-1 font-medium';
    span.textContent = msg;
    fieldEl.parentNode.appendChild(span);
  }
}

function validateAndSubmit(form) {
  clearValidationErrors();

  const checked = DAY_KEYS.filter(k => document.getElementById('check_'+k)?.checked);
  if (!checked.length) { showToast('Pilih minimal 1 hari.', 'warn'); return; }

  const validationErrors = [];

  checked.forEach(key => {
    const data = window._agendaData?.[key] || [];
    data.forEach((ag, i) => {
      if (ag.fromDB) return;
      const isTutup    = ag.status === 'Tutup';
      const isCatatan  = ag.status === 'Catatan';
      const isAktif    = ag.status === 'Aktif';
      const label      = DAY_LABELS[key] + ' agenda ' + (i+1);
      const activeIdx  = window._activeSlot?.[key] ?? 0;
      const isActive   = i === activeIdx;

      if (isAktif && !ag.user_id) {
        validationErrors.push({ key, si: i, fieldId: isActive ? 'uSel_'+key+'_'+i : null, msg: 'Nama belum dipilih — '+label });
      }
      if (isAktif) {
        if (!ag.waktu_shift)
          validationErrors.push({ key, si: i, fieldId: isActive ? 'swSel_'+key+'_'+i : null, msg: 'Waktu shift belum diisi — '+label });
        if (!ag.jam_mulai)
          validationErrors.push({ key, si: i, fieldId: isActive ? 'jm_'+key+'_'+i : null, msg: 'Jam mulai belum diisi — '+label });
        if (!ag.jam_selesai)
          validationErrors.push({ key, si: i, fieldId: isActive ? 'js_'+key+'_'+i : null, msg: 'Jam selesai belum diisi — '+label });
        if (ag.jam_mulai && ag.jam_selesai && ag.jam_selesai <= ag.jam_mulai)
          validationErrors.push({ key, si: i, fieldId: isActive ? 'js_'+key+'_'+i : null, msg: 'Jam selesai harus setelah jam mulai — '+label });
      }
    });

    const seenAktifUsers = new Map();
    data.forEach((ag, i) => {
      if (ag.fromDB || !ag.user_id || ag.status !== 'Aktif') return;
      const activeIdx = window._activeSlot?.[key] ?? 0;

      if (seenAktifUsers.has(String(ag.user_id))) {
        const prevIdx = seenAktifUsers.get(String(ag.user_id));
        validationErrors.push({
          key, si: i,
          fieldId: i === activeIdx ? 'uSel_'+key+'_'+i : null,
          msg: 'User sudah ada di agenda Aktif '+(prevIdx+1)+' — 1 user 1 shift Aktif per hari'
        });
      } else {
        seenAktifUsers.set(String(ag.user_id), i);
      }

      const dateKey = getDateForDay(key);
      const dbRecs  = EXISTING_BY_DATE[dateKey] || [];
      const inDB    = dbRecs.some(db => String(db.user_id) === String(ag.user_id) && (db.status||'Aktif') === 'Aktif');
      if (inDB) {
        validationErrors.push({
          key, si: i,
          fieldId: i === activeIdx ? 'uSel_'+key+'_'+i : null,
          msg: 'User sudah punya shift Aktif di hari ini'
        });
      }
    });
  });

  if (validationErrors.length === 0) {
    const total = checked.reduce((s,k) => s + (window._agendaData?.[k]?.filter(ag=>!ag.fromDB).length||0), 0);
    showConfirm('Simpan '+total+' agenda ('+checked.length+' hari)?', () => {
      form.dataset.confirmed = '1';
      form.submit();
    });
    return;
  }

  const firstErr = validationErrors[0];
  activateBubble(firstErr.key, firstErr.si);

  const errBubbles = new Set(validationErrors.map(e => e.key+'|'+e.si));
  errBubbles.forEach(bk => {
    const [k, siStr] = bk.split('|');
    const row = document.getElementById('bubbleRow_' + k);
    if (row) {
      const pills = row.querySelectorAll('.agenda-bubble');
      if (pills[+siStr]) pills[+siStr].classList.add('bubble-error');
    }
  });

  validationErrors.forEach(err => {
    if (err.fieldId) {
      const el = document.getElementById(err.fieldId);
      if (el) markFieldError(el, err.msg.split(' — ')[0]);
    }
  });

  requestAnimationFrame(() => {
    const dayCard = document.getElementById('dayForm_' + firstErr.key);
    if (dayCard) dayCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
    if (firstErr.fieldId) document.getElementById(firstErr.fieldId)?.focus();
  });

  const uniqueDays = [...new Set(validationErrors.map(e => DAY_LABELS[e.key]))];
  showToast(validationErrors.length + ' kolom belum lengkap (' + uniqueDays.join(', ') + ')', 'warn');
}

document.getElementById('mainForm').addEventListener('submit', function(e) {
  if (this.dataset.confirmed === '1') return;
  e.preventDefault();
  validateAndSubmit(this);
});

document.getElementById('mainForm').addEventListener('change', function(e) {
  const field = e.target;
  if (field.classList.contains('v-error-field')) {
    field.classList.remove('v-error-field');
    field.style.borderColor = '';
    field.parentNode.querySelector('.v-error-msg')?.remove();
  }
}, true);

// ══════════════════════════════════════════════════════
//  TOAST + CONFIRM
// ══════════════════════════════════════════════════════
function showToast(msg, type='info') {
  const bg = {success:'bg-emerald-800',warn:'bg-amber-700',info:'bg-slate-900'}[type]||'bg-slate-900';
  const t = document.createElement('div');
  t.className = `fixed bottom-6 right-6 z-[999] px-4 py-3 rounded-xl text-white text-sm font-medium shadow-xl ${bg} transition-opacity duration-300`;
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(()=>t.style.opacity='0', 2200);
  setTimeout(()=>t.remove(), 2600);
}
function showConfirm(msg, onOk) {
  const wrap = document.createElement('div');
  wrap.className = 'fixed inset-0 z-[999] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4';
  wrap.innerHTML = `
    <div class="w-full max-w-sm bg-white rounded-2xl border border-slate-200 shadow-[0_24px_64px_rgba(2,6,23,0.25)] overflow-hidden">
      <div class="p-5 border-b border-slate-100">
        <p class="text-base font-semibold text-slate-900">Konfirmasi</p>
        <p class="text-sm text-slate-500 mt-1">${msg}</p>
      </div>
      <div class="p-4 flex gap-2 justify-end">
        <button class="c-cancel h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-semibold">Batal</button>
        <button class="c-ok h-10 px-5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold">Simpan</button>
      </div>
    </div>`;
  const close = ()=>wrap.remove();
  wrap.addEventListener('click', e=>{ if(e.target===wrap) close(); });
  wrap.querySelector('.c-cancel').addEventListener('click', close);
  wrap.querySelector('.c-ok').addEventListener('click', ()=>{ close(); onOk(); });
  document.body.appendChild(wrap);
}
</script>
@endpush
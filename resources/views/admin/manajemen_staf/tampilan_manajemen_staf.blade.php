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
                <h1 class="text-sm font-semibold tracking-tight text-slate-900">Manajemen Staf</h1>
                <p class="text-xs text-slate-500">Kelola data staf: tambah, ubah, nonaktifkan, dan lihat detail.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="/tampilan_notifikasi"
               class="tip h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
               data-tip="Notifikasi"
               aria-label="Notifikasi">
                <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
                </svg>
            </a>

            <span class="h-10 px-4 rounded-xl border border-slate-200 bg-white grid place-items-center text-sm font-semibold">
                {{ now()->format('d M Y') }}
            </span>
        </div>
    </div>
</header>

<section class="relative p-4 sm:p-6">
    {{-- BACKGROUND (opsional) --}}
    <div class="pointer-events-none absolute inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>

        <div class="absolute inset-0 opacity-[0.12]"
             style="background-image:
                linear-gradient(to right, rgba(2,6,23,0.06) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(2,6,23,0.06) 1px, transparent 1px);
                background-size: 56px 56px;">
        </div>

        <div class="absolute inset-0 opacity-[0.18] mix-blend-screen animate-grid-scan"
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
            // ===============================
            // DUMMY DATA (nanti ganti DB)
            // ===============================
            $stafs = $stafs ?? [
                ['id'=>1, 'nama'=>'Asep', 'role'=>'Staf',  'hp'=>'0812-1111-2222', 'status'=>'Aktif',    'gabung'=>'2025-01-10', 'note'=>'Mekanik', 'username'=>'asep01', 'email'=>'asep@gmail.com', 'kontak'=>'0812-1111-2222', 'catatan'=>'Mekanik'],
                ['id'=>2, 'nama'=>'Rina', 'role'=>'Admin', 'hp'=>'0812-3333-4444', 'status'=>'Aktif',    'gabung'=>'2025-03-22', 'note'=>'Owner • Bisa servis', 'username'=>'rina01', 'email'=>'rina@gmail.com', 'kontak'=>'0812-3333-4444', 'catatan'=>'Owner'],
                ['id'=>3, 'nama'=>'Budi', 'role'=>'Staf',  'hp'=>'0812-5555-6666', 'status'=>'Nonaktif', 'gabung'=>'2024-11-02', 'note'=>'Kasir', 'username'=>'budi01', 'email'=>'budi@gmail.com', 'kontak'=>'0812-5555-6666', 'catatan'=>'Keuangan'],
            ];

            // map by id untuk modal (biar gampang ambil detailnya)
            $stafMap = [];
            foreach ($stafs as $s) { $stafMap[$s['id']] = $s; }
        @endphp

        <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200 shadow-[0_18px_48px_rgba(2,6,23,0.08)] overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-lg font-semibold text-slate-900">Daftar Staf</div>
                        <div class="text-xs text-slate-500 mt-1">Cari nama / filter role / filter status.</div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <div class="relative">
                            <input id="q" type="text" placeholder="Cari nama / no HP..."
                                   class="h-10 w-full sm:w-64 rounded-xl border border-slate-200 bg-white px-4 pr-10 text-sm outline-none focus:ring-2 focus:ring-slate-200">
                            <svg class="h-5 w-5 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 18a8 8 0 100-16 8 8 0 000 16z"/>
                            </svg>
                        </div>

                        <select id="filterRole"
                                class="h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-200">
                            <option value="">Semua Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Staf">Staf</option>
                        </select>

                        <select id="filterStatus"
                                class="h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-200">
                            <option value="">Semua Status</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>

                        <a href="/tambah_staf"
                           class="h-10 px-4 rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition text-sm font-semibold inline-flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                            </svg>
                            Tambah Staf
                        </a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="text-left font-semibold px-5 py-3">Nama</th>
                            <th class="text-left font-semibold px-5 py-3">Role</th>
                            <th class="text-left font-semibold px-5 py-3">No HP</th>
                            <th class="text-left font-semibold px-5 py-3">Status</th>
                            <th class="text-left font-semibold px-5 py-3">Tgl Gabung</th>
                            <th class="text-right font-semibold px-5 py-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="tbody" class="divide-y divide-slate-100">
                        @foreach($stafs as $s)
                            <tr class="bg-white">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-900 staff-nama">{{ $s['nama'] }}</div>
                                    <div class="text-xs text-slate-500">{{ $s['note'] ?? '' }}</div>
                                </td>

                                <td class="px-5 py-4 staff-role">{{ $s['role'] }}</td>
                                <td class="px-5 py-4 staff-hp">{{ $s['hp'] }}</td>

                                <td class="px-5 py-4 staff-status">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold border
                                        {{ $s['status']==='Aktif' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                        {{ $s['status'] }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-slate-700">{{ $s['gabung'] }}</td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- ✅ Detail jadi POPUP --}}
                                        <button type="button"
                                                class="btnDetail inline-flex h-9 items-center justify-center px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-xs font-semibold whitespace-nowrap"
                                                data-id="{{ $s['id'] }}">
                                            Detail
                                        </button>

                                        {{-- hanya Staf yang bisa diubah/nonaktifkan --}}
                                        @if($s['role'] === 'Staf')
                                            <a href="/ubah_staf?id={{ $s['id'] }}"
                                               class="inline-flex h-9 items-center justify-center px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-xs font-semibold whitespace-nowrap">
                                                Ubah
                                            </a>

                                            <a href="/nonaktifkan_staf?id={{ $s['id'] }}"
                                               class="btnNonaktif inline-flex h-9 items-center justify-center px-3 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 transition text-xs font-semibold whitespace-nowrap">
                                                Nonaktifkan
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="emptyState" class="hidden p-8 text-center text-slate-600">
                <div class="text-sm font-semibold">Tidak ada hasil</div>
                <div class="text-xs text-slate-500 mt-1">Coba ubah kata kunci / filter.</div>
            </div>

            <div class="px-5 sm:px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
                © DPM Workshop 2025
            </div>
        </div>
    </div>
</section>

{{-- ===================== MODAL DETAIL (POPUP) ===================== --}}
<div id="detailModal" class="fixed inset-0 z-[80] hidden">
    <div id="detailOverlay" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

    <div class="relative min-h-screen flex items-end sm:items-center justify-center p-3 sm:p-6">
        {{-- modal card (flex + max height biar GA KEpotong) --}}
        <div class="w-full max-w-[760px] rounded-2xl bg-white border border-slate-200 shadow-[0_30px_90px_rgba(2,6,23,0.30)]
                    overflow-hidden flex flex-col max-h-[92vh]">

            {{-- header (sticky) --}}
            <div class="px-5 py-4 border-b border-slate-200 flex items-start justify-between gap-3 bg-white shrink-0">
                <div class="min-w-0">
                    <div id="dmNama" class="text-lg font-semibold text-slate-900 truncate">—</div>
                    <div class="text-xs text-slate-500 mt-1">
                        ID: <span id="dmId">—</span> • Role: <span id="dmRole" class="font-semibold">—</span>
                    </div>
                </div>

                <button id="btnCloseDetail" type="button"
                        class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                        aria-label="Tutup">
                    <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- body (scroll area) --}}
            <div class="p-5 sm:p-6 flex-1 min-h-0 overflow-y-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div class="rounded-2xl border border-slate-200 bg-slate-50/40 p-4">
                        <div class="text-xs tracking-widest text-slate-500 font-semibold">STATUS</div>
                        <div class="mt-2">
                            <span id="dmStatusBadge"
                                  class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border">
                                —
                            </span>
                        </div>
                        <div class="text-xs text-slate-500 mt-3">
                            Tgl Gabung: <span id="dmGabung" class="font-semibold text-slate-700">—</span>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-хдㅈ
                        bg-white p-4">
                        <div class="text-xs tracking-widest text-slate-500 font-semibold">CATATAN</div>
                        <div id="dmCatatan" class="mt-2 text-sm text-slate-700">—</div>
                    </div>

                    <div class="sm:col-span-2 rounded-2xl border border-slate-200 bg-white p-4">
                        <div class="text-xs tracking-widest text-slate-500 font-semibold">INFORMASI AKUN</div>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-xs text-slate-500">Username</div>
                                <div id="dmUsername" class="font-semibold text-slate-900">—</div>
                            </div>

                            <div>
                                <div class="text-xs text-slate-500">Email</div>
                                <div id="dmEmail" class="font-semibold text-slate-900 break-words">—</div>
                            </div>

                            <div class="sm:col-span-2">
                                <div class="text-xs text-slate-500">Kontak</div>
                                <div id="dmKontak" class="font-semibold text-slate-900">—</div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- actions --}}
                <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:justify-end">
                    <button type="button" id="btnTutupDetail"
                            class="inline-flex h-11 items-center justify-center rounded-xl px-5 text-sm font-semibold border border-slate-200 bg-white hover:bg-slate-50 transition">
                        Tutup
                    </button>

                    <a id="dmLinkUbah" href="#"
                       class="hidden inline-flex h-11 items-center justify-center rounded-xl px-5 text-sm font-semibold border border-slate-200 bg-white hover:bg-slate-50 transition">
                        Ubah
                    </a>

                    <a id="dmLinkNonaktif" href="#"
                       class="hidden inline-flex h-11 items-center justify-center rounded-xl px-5 text-sm font-semibold bg-rose-600 text-white hover:bg-rose-700 transition">
                        Nonaktifkan
                    </a>
                </div>
            </div>

            <div class="px-5 sm:px-6 py-4 border-t border-slate-200 text-xs text-slate-500 shrink-0">
                © DPM Workshop 2025
            </div>
        </div>
    </div>
</div>

@endsection

@push('head')
<style>
    @media (prefers-reduced-motion: reduce) {
        .animate-grid-scan { animation: none !important; transition: none !important; }
    }
    @keyframes gridScan {
        0%   { background-position: 0 0, 0 0; opacity: 0.10; }
        40%  { opacity: 0.22; }
        60%  { opacity: 0.18; }
        100% { background-position: 220px 220px, -260px 260px; opacity: 0.10; }
    }
    .animate-grid-scan { animation: gridScan 8.5s ease-in-out infinite; }

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

    /* biar scroll modal halus */
    #detailModal * { -webkit-overflow-scrolling: touch; }
</style>
@endpush

@push('scripts')
<script>
  // =========================
  // FILTER DEMO (client-side)
  // =========================
  const q = document.getElementById('q');
  const filterRole = document.getElementById('filterRole');
  const filterStatus = document.getElementById('filterStatus');
  const tbody = document.getElementById('tbody');
  const emptyState = document.getElementById('emptyState');

  function applyFilter(){
    if (!tbody) return;

    const keyword = (q?.value || '').toLowerCase().trim();
    const role = filterRole?.value || '';
    const status = filterStatus?.value || '';

    const rows = Array.from(tbody.querySelectorAll('tr'));
    let shown = 0;

    rows.forEach(tr => {
      const nama = (tr.querySelector('.staff-nama')?.textContent || '').toLowerCase();
      const hp = (tr.querySelector('.staff-hp')?.textContent || '').toLowerCase();
      const r = (tr.querySelector('.staff-role')?.textContent || '');
      const st = (tr.querySelector('.staff-status')?.textContent || '');

      const matchKeyword = !keyword || nama.includes(keyword) || hp.includes(keyword);
      const matchRole = !role || r === role;
      const matchStatus = !status || st.includes(status);

      const ok = matchKeyword && matchRole && matchStatus;
      tr.classList.toggle('hidden', !ok);
      if (ok) shown++;
    });

    emptyState?.classList.toggle('hidden', shown !== 0);
  }

  q?.addEventListener('input', applyFilter);
  filterRole?.addEventListener('change', applyFilter);
  filterStatus?.addEventListener('change', applyFilter);

  // =========================
  // Toast (samain style halaman lain)
  // =========================
  // Kalau layout app kamu SUDAH punya toast global, boleh hapus blok toast ini.
  function ensureToast(){
    if (document.getElementById('toast')) return;

    const t = document.createElement('div');
    t.id = 'toast';
    t.className = "fixed bottom-6 right-6 z-[120] hidden w-[340px] rounded-2xl border border-slate-200 bg-white/90 backdrop-blur px-4 py-3 shadow-[0_18px_48px_rgba(2,6,23,0.14)]";
    t.innerHTML = `
      <div class="flex items-start gap-3">
        <div id="toastDot" class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></div>
        <div class="min-w-0">
          <p id="toastTitle" class="text-sm font-semibold text-slate-900">Info</p>
          <p id="toastMsg" class="text-xs text-slate-600 mt-0.5">—</p>
        </div>
        <button id="toastClose" class="ml-auto text-slate-500 hover:text-slate-800 transition" type="button" aria-label="Close">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    `;
    document.body.appendChild(t);
  }
  ensureToast();

  const toastEl = document.getElementById('toast');
  const toastTitle = document.getElementById('toastTitle');
  const toastMsg = document.getElementById('toastMsg');
  const toastDot = document.getElementById('toastDot');
  const toastClose = document.getElementById('toastClose');
  let toastTimer = null;

  const showToast = (title, msg, type='success') => {
    if (!toastEl) return;
    toastTitle.textContent = title;
    toastMsg.textContent = msg;
    toastDot.className = "mt-1 h-2.5 w-2.5 rounded-full " + (type==='success' ? "bg-emerald-500" : "bg-red-500");
    toastEl.classList.remove('hidden');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toastEl.classList.add('hidden'), 2600);
  };
  toastClose?.addEventListener('click', () => toastEl.classList.add('hidden'));

  // =========================
  // Confirm Modal (custom) - ganti confirm bawaan browser
  // =========================
  function showConfirmModal({ title, message, confirmText, cancelText, note, tone = "neutral", onConfirm }) {
    const toneMap = {
      neutral: { btn: "bg-slate-900 hover:bg-slate-800", noteBg:"bg-slate-50", noteBr:"border-slate-200", noteTx:"text-slate-600" },
      danger:  { btn: "bg-rose-600 hover:bg-rose-700",  noteBg:"bg-rose-50",  noteBr:"border-rose-200",  noteTx:"text-rose-700" },
    };
    const t = toneMap[tone] || toneMap.neutral;

    const wrap = document.createElement('div');
    wrap.className = "fixed inset-0 z-[150] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-3";
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

  // =========================
  // NONAKTIFKAN (tabel) - custom popup
  // =========================
  document.querySelectorAll('.btnNonaktif').forEach(a => {
    a.addEventListener('click', (e) => {
      e.preventDefault();
      const href = a.getAttribute('href');

      showConfirmModal({
        title: "Nonaktifkan staf?",
        message: "Staf akan dinonaktifkan dan tidak bisa login sampai diaktifkan kembali.",
        confirmText: "Ya, Nonaktifkan",
        cancelText: "Batal",
        tone: "danger",
        note: "Tindakan ini bisa dibatalkan kalau kamu punya fitur aktifkan lagi.",
        onConfirm: () => window.location.href = href
      });
    });
  });

  // =========================
  // DETAIL MODAL (POPUP)
  // =========================
  const STAFF_MAP = @json($stafMap);

  const detailModal = document.getElementById('detailModal');
  const detailOverlay = document.getElementById('detailOverlay');
  const btnCloseDetail = document.getElementById('btnCloseDetail');
  const btnTutupDetail = document.getElementById('btnTutupDetail');

  const dmNama = document.getElementById('dmNama');
  const dmId = document.getElementById('dmId');
  const dmRole = document.getElementById('dmRole');
  const dmStatusBadge = document.getElementById('dmStatusBadge');
  const dmGabung = document.getElementById('dmGabung');
  const dmCatatan = document.getElementById('dmCatatan');
  const dmUsername = document.getElementById('dmUsername');
  const dmEmail = document.getElementById('dmEmail');
  const dmKontak = document.getElementById('dmKontak');

  const dmLinkUbah = document.getElementById('dmLinkUbah');
  const dmLinkNonaktif = document.getElementById('dmLinkNonaktif');

  function openDetail(id){
    const s = STAFF_MAP?.[id];
    if (!s) return;

    dmNama.textContent = s.nama ?? '-';
    dmId.textContent = s.id ?? '-';
    dmRole.textContent = s.role ?? '-';

    const isAktif = (s.status ?? '') === 'Aktif';
    dmStatusBadge.textContent = s.status ?? '-';
    dmStatusBadge.className = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border ' +
      (isAktif
        ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
        : 'bg-slate-100 text-slate-700 border-slate-200');

    dmGabung.textContent = s.gabung ?? '-';
    dmCatatan.textContent = s.catatan ?? '—';
    dmUsername.textContent = s.username ?? '-';
    dmEmail.textContent = s.email ?? '-';
    dmKontak.textContent = s.kontak ?? (s.hp ?? '-');

    const isStaf = (s.role ?? '') === 'Staf';
    if (isStaf) {
      dmLinkUbah.classList.remove('hidden');
      dmLinkNonaktif.classList.remove('hidden');

      dmLinkUbah.href = `/ubah_staf?id=${encodeURIComponent(s.id)}`;
      dmLinkNonaktif.href = `/nonaktifkan_staf?id=${encodeURIComponent(s.id)}`;
    } else {
      dmLinkUbah.classList.add('hidden');
      dmLinkNonaktif.classList.add('hidden');
      dmLinkUbah.href = '#';
      dmLinkNonaktif.href = '#';
    }

    detailModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  }

  function closeDetail(){
    detailModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  }

  detailOverlay?.addEventListener('click', closeDetail);
  btnCloseDetail?.addEventListener('click', closeDetail);
  btnTutupDetail?.addEventListener('click', closeDetail);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !detailModal.classList.contains('hidden')) closeDetail();
  });

  document.querySelectorAll('.btnDetail').forEach(btn => {
    btn.addEventListener('click', () => openDetail(btn.dataset.id));
  });

  // =========================
  // NONAKTIFKAN (dari modal detail) - custom popup
  // =========================
  dmLinkNonaktif?.addEventListener('click', (e) => {
    const href = dmLinkNonaktif.getAttribute('href');
    if (!href || href === '#') return;

    e.preventDefault();
    showConfirmModal({
      title: "Nonaktifkan staf?",
      message: "Staf akan dinonaktifkan dan tidak bisa login.",
      confirmText: "Ya, Nonaktifkan",
      cancelText: "Batal",
      tone: "danger",
      note: "Pastikan kamu memilih staf yang benar sebelum menonaktifkan.",
      onConfirm: () => window.location.href = href
    });
  });
</script>
@endpush


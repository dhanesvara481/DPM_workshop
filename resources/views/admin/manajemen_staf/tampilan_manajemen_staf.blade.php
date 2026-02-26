{{-- resources/views/admin/manajemen_staf/tampilan_manajemen_staf.blade.php --}}
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
            <a href="{{ route('tampilan_notifikasi') }}"
               class="tip h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
               data-tip="Notifikasi" aria-label="Notifikasi">
                <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
                </svg>
            </a>
        </div>
    </div>
</header>

<section class="relative p-4 sm:p-6">
    {{-- BACKGROUND --}}
    <div class="pointer-events-none absolute inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
        <div class="absolute inset-0 opacity-[0.12]"
             style="background-image: linear-gradient(to right, rgba(2,6,23,0.06) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(2,6,23,0.06) 1px, transparent 1px);
                    background-size: 56px 56px;"></div>
        <div class="absolute inset-0 opacity-[0.18] mix-blend-screen animate-grid-scan"
             style="background-image:
                    repeating-linear-gradient(90deg, transparent 0px, transparent 55px, rgba(255,255,255,0.95) 56px, transparent 57px, transparent 112px),
                    repeating-linear-gradient(180deg, transparent 0px, transparent 55px, rgba(255,255,255,0.70) 56px, transparent 57px, transparent 112px);
                    background-size: 112px 112px, 112px 112px;"></div>
        <div class="absolute -top-48 left-1/2 -translate-x-1/2 h-[720px] w-[720px] rounded-full blur-3xl opacity-10
                    bg-gradient-to-tr from-blue-950/25 via-blue-700/10 to-transparent"></div>
        <div class="absolute -bottom-72 right-1/4 h-[720px] w-[720px] rounded-full blur-3xl opacity-08
                    bg-gradient-to-tr from-blue-950/18 via-indigo-700/10 to-transparent"></div>
    </div>

    <div class="max-w-[1280px] mx-auto w-full">

        {{-- FLASH MESSAGES --}}
        @if (session('success'))
            <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                <div class="font-semibold">Berhasil</div>
                <div class="text-sm mt-0.5">{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-800">
                <div class="font-semibold">Gagal</div>
                <div class="text-sm mt-0.5">{{ session('error') }}</div>
            </div>
        @endif

        <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200 shadow-[0_18px_48px_rgba(2,6,23,0.08)] overflow-hidden">

            {{-- Toolbar --}}
            <div class="p-5 sm:p-6 border-b border-slate-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-lg font-semibold text-slate-900">Daftar Staf</div>
                        <div class="text-xs text-slate-500 mt-1">Cari nama / filter status.</div>
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

                        {{-- Filter status: value pakai lowercase sesuai DB --}}
                        <select id="filterStatus" class="h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-200">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>

                        <a href="{{ route('tambah_staf') }}"
                           class="h-10 px-4 rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition text-sm font-semibold inline-flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                            </svg>
                            Tambah Staf
                        </a>
                    </div>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="text-left font-semibold px-5 py-3">Username</th>
                            <th class="text-left font-semibold px-5 py-3">Role</th>
                            <th class="text-left font-semibold px-5 py-3">No HP</th>
                            <th class="text-left font-semibold px-5 py-3">Status</th>
                            <th class="text-left font-semibold px-5 py-3">Tgl Gabung</th>
                            <th class="text-right font-semibold px-5 py-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="tbody" class="divide-y divide-slate-100">
                        @forelse($stafs as $staf)
                            @php
                                // Bandingkan langsung dengan nilai DB (lowercase)
                                $isAktif = $staf->status === 'aktif';
                                $isStaff = $staf->role   === 'staff';
                            @endphp
                            <tr class="bg-white hover:bg-slate-50/50 transition">

                                {{-- Username + email --}}
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-900 staff-nama">{{ $staf->username }}</div>
                                    <div class="text-xs text-slate-500">{{ $staf->email }}</div>
                                </td>

                                {{-- Role — data-value untuk filter JS --}}
                                <td class="px-5 py-4">
                                    <span class="staff-role" data-value="{{ $staf->role }}">
                                        {{ ucfirst($staf->role) }}
                                    </span>
                                </td>

                                {{-- No HP --}}
                                <td class="px-5 py-4 staff-hp">{{ $staf->kontak }}</td>

                                {{-- Status — data-value untuk filter JS --}}
                                <td class="px-5 py-4">
                                    <span class="staff-status inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold border
                                            {{ $isAktif
                                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                                : 'bg-slate-100 text-slate-500 border-slate-200' }}"
                                          data-value="{{ $staf->status }}">
                                        {{ $isAktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>

                                {{-- Tgl gabung --}}
                                <td class="px-5 py-4 text-slate-700">
                                    {{ $staf->created_at?->format('d M Y') ?? '-' }}
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">

                                        {{-- Detail popup — semua data di-pass via data-* --}}
                                        <button type="button"
                                                class="btnDetail inline-flex h-9 items-center justify-center px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-xs font-semibold whitespace-nowrap"
                                                data-id="{{ $staf->user_id }}"
                                                data-username="{{ $staf->username }}"
                                                data-email="{{ $staf->email }}"
                                                data-kontak="{{ $staf->kontak }}"
                                                data-role="{{ $staf->role }}"
                                                data-status="{{ $staf->status }}"
                                                data-catatan="{{ $staf->catatan ?? '' }}"
                                                data-gabung="{{ $staf->created_at?->format('d M Y') ?? '-' }}">
                                            Detail
                                        </button>

                                        @if($isStaff)
                                            <a href="{{ route('ubah_staf', $staf->user_id) }}"
                                               class="inline-flex h-9 items-center justify-center px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-xs font-semibold whitespace-nowrap">
                                                Ubah
                                            </a>

                                            @if($isAktif)
                                                {{-- DB status = 'aktif' → tombol Nonaktifkan (merah) --}}
                                                <button type="button"
                                                        class="btnToggleStatus inline-flex h-9 min-w-[112px] items-center justify-center px-3 rounded-xl
                                                                border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 transition
                                                                text-xs font-semibold whitespace-nowrap"
                                                        data-id="{{ $staf->user_id }}"
                                                        data-action="nonaktifkan"
                                                        data-nama="{{ $staf->username }}">
                                                    Nonaktifkan
                                                </button>
                                            @else
                                                {{-- DB status = 'nonaktif' → tombol Aktifkan (hijau) --}}
                                                <button type="button"
                                                        class="btnToggleStatus inline-flex h-9 min-w-[112px] items-center justify-center px-3 rounded-xl
                                                                border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition
                                                                text-xs font-semibold whitespace-nowrap"
                                                        data-id="{{ $staf->user_id }}"
                                                        data-action="aktifkan"
                                                        data-nama="{{ $staf->username }}">
                                                    Aktifkan
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-slate-500 text-sm">
                                    Belum ada data staf.
                                </td>
                            </tr>
                        @endforelse
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

{{-- ===================== MODAL DETAIL ===================== --}}
<div id="detailModal" class="fixed inset-0 z-[80] hidden">
    <div id="detailOverlay" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

    <div class="relative min-h-screen flex items-end sm:items-center justify-center p-3 sm:p-6">
        <div class="w-full max-w-[760px] rounded-2xl bg-white border border-slate-200
                    shadow-[0_30px_90px_rgba(2,6,23,0.30)] overflow-hidden flex flex-col max-h-[92vh]">

            {{-- Header modal --}}
            <div class="px-5 py-4 border-b border-slate-200 flex items-start justify-between gap-3 bg-white shrink-0">
                <div class="min-w-0">
                    <div id="dmUsername" class="text-lg font-semibold text-slate-900 truncate">—</div>
                    <div class="text-xs text-slate-500 mt-1">
                        ID: <span id="dmId">—</span> &bull; Role: <span id="dmRole" class="font-semibold">—</span>
                    </div>
                </div>
                <button id="btnCloseDetail" type="button"
                        class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center shrink-0"
                        aria-label="Tutup">
                    <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body modal --}}
            <div class="p-5 sm:p-6 flex-1 min-h-0 overflow-y-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div class="rounded-2xl border border-slate-200 bg-slate-50/40 p-4">
                        <div class="text-xs tracking-widest text-slate-500 font-semibold uppercase">Status</div>
                        <div class="mt-2">
                            <span id="dmStatusBadge" class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border">—</span>
                        </div>
                        <div class="text-xs text-slate-500 mt-3">
                            Tgl Gabung: <span id="dmGabung" class="font-semibold text-slate-700">—</span>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-4">
                        <div class="text-xs tracking-widest text-slate-500 font-semibold uppercase">Catatan</div>
                        <div id="dmCatatan" class="mt-2 text-sm text-slate-700">—</div>
                    </div>

                    <div class="sm:col-span-2 rounded-2xl border border-slate-200 bg-white p-4">
                        <div class="text-xs tracking-widest text-slate-500 font-semibold uppercase">Informasi Akun</div>
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-xs text-slate-500">Username</div>
                                <div id="dmUsernameDetail" class="font-semibold text-slate-900">—</div>
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

                {{-- Aksi --}}
                <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:justify-end">
                    <button type="button" id="btnTutupDetail"
                            class="inline-flex h-11 items-center justify-center rounded-xl px-5 text-sm font-semibold border border-slate-200 bg-white hover:bg-slate-50 transition">
                        Tutup
                    </button>
                    <a id="dmLinkUbah" href="#"
                       class="hidden inline-flex h-11 items-center justify-center rounded-xl px-5 text-sm font-semibold border border-slate-200 bg-white hover:bg-slate-50 transition">
                        Ubah
                    </a>
                    <button type="button" id="dmBtnToggle"
                            class="hidden inline-flex h-11 items-center justify-center rounded-xl px-5 text-sm font-semibold transition">
                        —
                    </button>
                </div>
            </div>

            <div class="px-5 sm:px-6 py-4 border-t border-slate-200 text-xs text-slate-500 shrink-0">
                © DPM Workshop 2025
            </div>
        </div>
    </div>
</div>

{{-- Hidden form untuk submit toggle status --}}
<form id="formToggleStatus" method="POST" action="" class="hidden">
    @csrf
    @method('PATCH')
</form>

@endsection

@push('head')
<style>
    @media (prefers-reduced-motion: reduce) { .animate-grid-scan { animation: none !important; } }
    @keyframes gridScan {
        0%   { background-position: 0 0, 0 0; opacity: 0.10; }
        40%  { opacity: 0.22; }
        60%  { opacity: 0.18; }
        100% { background-position: 220px 220px, -260px 260px; opacity: 0.10; }
    }
    .animate-grid-scan { animation: gridScan 8.5s ease-in-out infinite; }

    .tip { position: relative; }
    .tip[data-tip]::after {
        content: attr(data-tip);
        position: absolute; right: 0; top: calc(100% + 10px);
        background: rgba(15,23,42,.92); color: rgba(255,255,255,.92);
        font-size: 11px; padding: 6px 10px; border-radius: 10px;
        white-space: nowrap; opacity: 0; transform: translateY(-4px);
        pointer-events: none; transition: .15s ease;
    }
    .tip:hover::after { opacity: 1; transform: translateY(0); }
</style>
@endpush

@push('scripts')
<script>
// ============================================================
// TOAST
// ============================================================
(function () {
    if (document.getElementById('toast')) return;
    const t = document.createElement('div');
    t.id = 'toast';
    t.className = "fixed bottom-6 right-6 z-[120] hidden w-[340px] rounded-2xl border border-slate-200 bg-white/90 backdrop-blur px-4 py-3 shadow-[0_18px_48px_rgba(2,6,23,0.14)]";
    t.innerHTML = `
      <div class="flex items-start gap-3">
        <div id="toastDot" class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></div>
        <div class="min-w-0">
          <p id="toastTitle" class="text-sm font-semibold text-slate-900">Info</p>
          <p id="toastMsg"   class="text-xs text-slate-600 mt-0.5">—</p>
        </div>
        <button id="toastClose" class="ml-auto text-slate-500 hover:text-slate-800 transition" type="button">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>`;
    document.body.appendChild(t);
    document.getElementById('toastClose')?.addEventListener('click', () => t.classList.add('hidden'));
})();

let _tt = null;
function showToast(title, msg, type = 'success') {
    const t = document.getElementById('toast');
    if (!t) return;
    document.getElementById('toastTitle').textContent = title;
    document.getElementById('toastMsg').textContent   = msg;
    document.getElementById('toastDot').className = "mt-1 h-2.5 w-2.5 rounded-full " + (type === 'success' ? "bg-emerald-500" : "bg-red-500");
    t.classList.remove('hidden');
    clearTimeout(_tt);
    _tt = setTimeout(() => t.classList.add('hidden'), 2600);
}

// ============================================================
// CONFIRM MODAL
// ============================================================
function showConfirmModal({ title, message, confirmText, cancelText, note, tone = "neutral", onConfirm }) {
    const map = {
        neutral: { btn: "bg-slate-900 hover:bg-slate-800",       bg: "bg-slate-50",   br: "border-slate-200",   tx: "text-slate-600" },
        danger:  { btn: "bg-rose-600 hover:bg-rose-700",         bg: "bg-rose-50",    br: "border-rose-200",    tx: "text-rose-700" },
        success: { btn: "bg-emerald-600 hover:bg-emerald-700",   bg: "bg-emerald-50", br: "border-emerald-200", tx: "text-emerald-700" },
    };
    const c = map[tone] || map.neutral;
    const w = document.createElement('div');
    w.className = "fixed inset-0 z-[150] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-3";
    w.innerHTML = `
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
          <div class="rounded-xl border ${c.br} ${c.bg} p-4 text-xs ${c.tx}">${note || 'Pastikan pilihan kamu sudah benar.'}</div>
          <div class="mt-4 flex justify-end gap-2">
            <button type="button" class="btn-cancel h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-semibold">${cancelText}</button>
            <button type="button" class="btn-ok h-10 px-5 rounded-xl ${c.btn} text-white text-sm font-semibold">${confirmText}</button>
          </div>
        </div>
      </div>`;
    const close = () => w.remove();
    w.addEventListener('click', e => { if (e.target === w) close(); });
    w.querySelector('.btn-x')?.addEventListener('click', close);
    w.querySelector('.btn-cancel')?.addEventListener('click', close);
    w.querySelector('.btn-ok')?.addEventListener('click', () => { close(); onConfirm?.(); });
    document.body.appendChild(w);
}

// ============================================================
// FILTER — membaca data-value (nilai DB lowercase)
// ============================================================
const q            = document.getElementById('q');
const filterRole   = document.getElementById('filterRole');
const filterStatus = document.getElementById('filterStatus');
const tbody        = document.getElementById('tbody');
const emptyState   = document.getElementById('emptyState');

function applyFilter() {
    if (!tbody) return;
    const keyword = (q?.value || '').toLowerCase().trim();
    const role    = (filterRole?.value   || '').toLowerCase();  // '' | 'admin' | 'staff'
    const status  = (filterStatus?.value || '').toLowerCase();  // '' | 'aktif' | 'nonaktif'

    const rows = Array.from(tbody.querySelectorAll('tr'));
    let shown = 0;

    rows.forEach(tr => {
        const nama      = (tr.querySelector('.staff-nama')?.textContent || '').toLowerCase();
        const hp        = (tr.querySelector('.staff-hp')?.textContent   || '').toLowerCase();
        // Baca data-value bukan textContent agar tidak terpengaruh kapitalisasi tampilan
        const roleVal   = (tr.querySelector('.staff-role')?.dataset.value   || '').toLowerCase();
        const statusVal = (tr.querySelector('.staff-status')?.dataset.value || '').toLowerCase();

        const ok = (!keyword || nama.includes(keyword) || hp.includes(keyword))
                && (!role   || roleVal   === role)
                && (!status || statusVal === status);

        tr.classList.toggle('hidden', !ok);
        if (ok) shown++;
    });

    emptyState?.classList.toggle('hidden', shown !== 0);
}

q?.addEventListener('input', applyFilter);
filterRole?.addEventListener('change', applyFilter);
filterStatus?.addEventListener('change', applyFilter);

// ============================================================
// TOGGLE STATUS — dari tombol di tabel
// ============================================================
document.querySelectorAll('.btnToggleStatus').forEach(btn => {
    btn.addEventListener('click', () => {
        const id       = btn.dataset.id;
        const action   = btn.dataset.action;    // 'nonaktifkan' | 'aktifkan'
        const nama     = btn.dataset.nama;
        const isDanger = action === 'nonaktifkan';

        showConfirmModal({
            title:       isDanger ? `Nonaktifkan ${nama}?` : `Aktifkan ${nama}?`,
            message:     isDanger
                            ? 'Staf tidak bisa login sampai diaktifkan kembali.'
                            : 'Staf akan bisa login kembali setelah diaktifkan.',
            confirmText: isDanger ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan',
            cancelText:  'Batal',
            tone:        isDanger ? 'danger' : 'success',
            note:        isDanger
                            ? 'Tindakan ini bisa dibatalkan dengan mengaktifkan kembali.'
                            : 'Pastikan kamu memilih staf yang benar.',
            onConfirm: () => {
                const form = document.getElementById('formToggleStatus');
                form.action = `/toggle_status_staf/${id}`;
                form.submit();
            }
        });
    });
});

// ============================================================
// DETAIL MODAL — data dari data-* attribute tombol Detail
// ============================================================
const detailModal   = document.getElementById('detailModal');
const detailOverlay = document.getElementById('detailOverlay');

function openDetail(btn) {
    // Ambil semua nilai langsung dari DB via data-*
    const id       = btn.dataset.id;
    const username = btn.dataset.username;
    const email    = btn.dataset.email;
    const kontak   = btn.dataset.kontak;
    const role     = btn.dataset.role;     // 'staff' | 'admin'
    const status   = btn.dataset.status;   // 'aktif' | 'nonaktif'
    const catatan  = btn.dataset.catatan;
    const gabung   = btn.dataset.gabung;

    const isAktif = status === 'aktif';
    const isStaff = role   === 'staff';

    // Isi teks modal
    document.getElementById('dmUsername').textContent       = username || '-';
    document.getElementById('dmId').textContent             = id       || '-';
    document.getElementById('dmRole').textContent           = role ? (role.charAt(0).toUpperCase() + role.slice(1)) : '-';
    document.getElementById('dmUsernameDetail').textContent = username  || '-';
    document.getElementById('dmEmail').textContent          = email     || '-';
    document.getElementById('dmKontak').textContent         = kontak    || '-';
    document.getElementById('dmGabung').textContent         = gabung    || '-';
    document.getElementById('dmCatatan').textContent        = catatan   || '—';

    // Badge status — berdasarkan nilai DB
    const badge = document.getElementById('dmStatusBadge');
    badge.textContent = isAktif ? 'Aktif' : 'Nonaktif';
    badge.className   = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border '
        + (isAktif
            ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
            : 'bg-slate-100 text-slate-500 border-slate-200');

    const dmLinkUbah  = document.getElementById('dmLinkUbah');
    const dmBtnToggle = document.getElementById('dmBtnToggle');

    if (isStaff) {
        // Tombol Ubah
        dmLinkUbah.classList.remove('hidden');
        dmLinkUbah.href = `/ubah_staf/${id}`;

        // Tombol Toggle Status — teks & warna sesuai status DB
        dmBtnToggle.classList.remove('hidden');
        dmBtnToggle.dataset.id     = id;
        dmBtnToggle.dataset.nama   = username;
        dmBtnToggle.dataset.action = isAktif ? 'nonaktifkan' : 'aktifkan';

        if (isAktif) {
            // Status 'aktif' di DB → tampilkan "Nonaktifkan" (merah)
            dmBtnToggle.textContent = 'Nonaktifkan';
            dmBtnToggle.className   = 'inline-flex h-11 items-center justify-center rounded-xl px-5 text-sm font-semibold bg-rose-600 text-white hover:bg-rose-700 transition';
        } else {
            // Status 'nonaktif' di DB → tampilkan "Aktifkan" (hijau)
            dmBtnToggle.textContent = 'Aktifkan';
            dmBtnToggle.className   = 'inline-flex h-11 items-center justify-center rounded-xl px-5 text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-700 transition';
        }
    } else {
        dmLinkUbah.classList.add('hidden');
        dmBtnToggle.classList.add('hidden');
        dmLinkUbah.href = '#';
    }

    detailModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeDetail() {
    detailModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

detailOverlay?.addEventListener('click', closeDetail);
document.getElementById('btnCloseDetail')?.addEventListener('click', closeDetail);
document.getElementById('btnTutupDetail')?.addEventListener('click', closeDetail);
document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && !detailModal.classList.contains('hidden')) closeDetail();
});

document.querySelectorAll('.btnDetail').forEach(btn => {
    btn.addEventListener('click', () => openDetail(btn));
});

// Toggle status dari tombol di dalam modal detail
document.getElementById('dmBtnToggle')?.addEventListener('click', function () {
    const id       = this.dataset.id;
    const action   = this.dataset.action;
    const nama     = this.dataset.nama;
    const isDanger = action === 'nonaktifkan';

    showConfirmModal({
        title:       isDanger ? `Nonaktifkan ${nama}?` : `Aktifkan ${nama}?`,
        message:     isDanger
                        ? 'Staf tidak bisa login sampai diaktifkan kembali.'
                        : 'Staf akan bisa login kembali setelah diaktifkan.',
        confirmText: isDanger ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan',
        cancelText:  'Batal',
        tone:        isDanger ? 'danger' : 'success',
        note:        'Pastikan kamu memilih staf yang benar.',
        onConfirm: () => {
            const form = document.getElementById('formToggleStatus');
            form.action = `/toggle_status_staf/${id}`;
            form.submit();
        }
    });
});
</script>
@endpush